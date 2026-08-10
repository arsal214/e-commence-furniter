<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdatedMail;
use App\Mail\PaymentRequestMail;
use App\Models\DeliveryProof;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment')) {
            $query->where('payment_status', $request->payment);
        }

        $orders = $query->paginate(20)->withQueryString();

        $counts = [
            'all'        => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped'    => Order::where('status', 'shipped')->count(),
            'delivered'  => Order::where('status', 'delivered')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'deliveryProofs.uploader');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Email the customer a link to pay for an order that was placed but never
     * paid for.
     *
     * Manual, not automatic: the decision to chase a customer belongs to whoever
     * is looking at the order. Repeat sends are allowed — people lose emails —
     * and each one is counted so the screen can show how often this order has
     * been chased.
     */
    public function requestPayment(Request $request, Order $order)
    {
        $data = $request->validate([
            'message' => 'nullable|string|max:1000',
        ]);

        if (! $order->awaitingPayment()) {
            return back()->with('error', $order->payment_status === 'paid'
                ? 'Order #' . $order->id . ' is already paid — no payment request sent.'
                : 'Order #' . $order->id . ' is cancelled — no payment request sent.');
        }

        // Minted before the mailable is built; the pay link is the whole point of
        // the email, and a null URL would ship a dead button.
        $order->ensurePaymentToken();

        try {
            Mail::to($order->email)->send(new PaymentRequestMail($order, $data['message'] ?? null));
        } catch (\Exception $e) {
            \Log::warning("Payment request email failed for order #{$order->id}: " . $e->getMessage());
            \App\Models\EmailLog::recordFailure(
                $order->email, PaymentRequestMail::class, $e->getMessage(), $order->user_id, $order->id
            );

            return back()->with('error', 'Payment request could not be sent — check the email log.');
        }

        $order->forceFill([
            'payment_requested_at'  => now(),
            'payment_request_count' => $order->payment_request_count + 1,
        ])->save();

        return back()->with('success', 'Payment request emailed to ' . $order->email . '.');
    }

    /**
     * Attach courier proof of delivery to an order. Internal record only — the
     * files go to the private disk and are never linked to the customer.
     */
    public function storeDeliveryProof(Request $request, Order $order)
    {
        $data = $request->validate([
            'files'   => 'required|array|max:10',
            'files.*' => 'file|mimes:jpg,jpeg,png,webp,gif,pdf|max:8192',
            'note'    => 'nullable|string|max:1000',
        ], [
            'files.required' => 'Choose at least one photo or PDF to upload.',
            'files.*.mimes'  => 'Proof files must be an image (JPG, PNG, WEBP, GIF) or a PDF.',
            'files.*.max'    => 'Each proof file must be 8 MB or smaller.',
        ]);

        foreach ($request->file('files') as $file) {
            $path = $file->store('delivery-proofs/' . $order->id, DeliveryProof::DISK);

            $order->deliveryProofs()->create([
                'path'          => $path,
                'original_name' => $file->getClientOriginalName(),
                // Read from the stored file, not the browser's Content-Type header,
                // which is client-supplied and trivially wrong.
                'mime'          => Storage::disk(DeliveryProof::DISK)->mimeType($path) ?: $file->getClientMimeType(),
                'size'          => $file->getSize(),
                'note'          => $data['note'] ?? null,
                'uploaded_by'   => auth()->id(),
            ]);
        }

        $count = count($request->file('files'));

        return back()->with('success', $count . ' proof of delivery ' . ($count === 1 ? 'file' : 'files') . ' saved to order #' . $order->id . '.');
    }

    /** Stream a proof file back. Admin-only, since the disk is not web-readable. */
    public function showDeliveryProof(Order $order, DeliveryProof $proof)
    {
        abort_unless($proof->order_id === $order->id, Response::HTTP_NOT_FOUND);

        $disk = Storage::disk(DeliveryProof::DISK);

        abort_unless($disk->exists($proof->path), Response::HTTP_NOT_FOUND);

        return $disk->response($proof->path, $proof->display_name);
    }

    public function destroyDeliveryProof(Order $order, DeliveryProof $proof)
    {
        abort_unless($proof->order_id === $order->id, Response::HTTP_NOT_FOUND);

        $proof->deleteFile();
        $proof->delete();

        return back()->with('success', 'Proof of delivery removed.');
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status'            => 'sometimes|in:pending,processing,shipped,delivered,cancelled',
            'payment_status'    => 'sometimes|in:pending,paid',
            'supplier_name'     => 'nullable|string|max:100',
            'supplier_order_id' => 'nullable|string|max:200',
            'supplier_tracking' => 'nullable|string|max:200',
            'carrier'           => 'nullable|string|max:100',
        ]);

        $previousStatus = $order->status;
        $newStatus      = $data['status'] ?? $previousStatus;
        $statusChanged  = $newStatus !== $previousStatus;

        // Stamp the ship date on the first transition into 'shipped'.
        if ($statusChanged && $newStatus === 'shipped') {
            $data['shipped_at'] = now();
        }

        $order->update($data);

        // Notify the customer on any status change they'd care about. Carrier and
        // tracking fields are saved above, so the shipped email can include them.
        $notified = false;

        if ($statusChanged && OrderStatusUpdatedMail::shouldNotify($newStatus)) {
            try {
                Mail::to($order->email)->send(new OrderStatusUpdatedMail($order));
                $notified = true;
            } catch (\Exception $e) {
                \Log::warning("Order status email ({$newStatus}) failed for order #{$order->id}: " . $e->getMessage());
                \App\Models\EmailLog::recordFailure(
                    $order->email, OrderStatusUpdatedMail::class, $e->getMessage(), $order->user_id, $order->id
                );
            }
        }

        $message = 'Order #' . $order->id . ' updated successfully.';

        if ($statusChanged) {
            $message .= $notified
                ? ' Customer notified by email.'
                : (OrderStatusUpdatedMail::shouldNotify($newStatus)
                    ? ' Customer email could not be sent — check the logs.'
                    : '');
        }

        return back()->with('success', $message);
    }
}
