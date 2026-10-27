<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SellerNoticeMail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ModerationController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.market', ['page' => 'products', 'heading' => 'Product moderation'] + \App\Support\AdminViews::products($request));
    }

    public function show(Product $product)
    {
        abort_unless($product->seller_id, 404); // house products are not moderated
        $product->load('seller.user', 'category.parent', 'variants', 'productImages');

        return view('admin.moderation.show', ['product' => $product]);
    }

    public function decide(Request $request, Product $product)
    {
        abort_unless($product->seller_id, 404);
        $data = $request->validate([
            'action' => 'required|in:approve,remove',
            'note' => ['nullable', 'string', 'max:500', 'required_if:action,remove', 'min:5'],
        ], ['note.required_if' => 'Tell the seller why it was removed.', 'note.min' => 'Tell the seller why it was removed.']);

        $locked = DB::transaction(function () use ($data, $product) {
            $p = Product::with('seller.user')->whereKey($product->id)->lockForUpdate()->firstOrFail();
            $approve = $data['action'] === 'approve';

            // Re-clicking the same decision is a no-op, so the seller is not emailed twice.
            if ($p->moderation_status === ($approve ? 'approved' : 'removed')) {
                return null;
            }
            $p->update(['moderation_status' => $approve ? 'approved' : 'removed', 'moderation_note' => $approve ? null : $data['note']]);

            return $p;
        });

        if (! $locked) {
            return back()->with('error', 'That listing already has this decision.');
        }

        if ($data['action'] === 'remove') {
            try {
                Mail::to($locked->seller->user->email)->send(new SellerNoticeMail(
                    'A listing was removed from Peyton Ghalib', 'We removed "' . $locked->name . '"',
                    'This listing does not meet our marketplace rules. You can edit it and it will be reviewed again.', $data['note'],
                    'Open Seller Center', route('seller.products.edit', $locked),
                ));
            } catch (\Throwable $e) {
                Log::warning("Listing-removed email for product {$locked->id} failed: " . $e->getMessage());
            }
        }

        return redirect()->route('admin.moderation.index', ['tab' => $request->input('back', 'pending')])
            ->with('success', $data['action'] === 'approve' ? 'Listing approved. It is live if the store is live.' : 'Listing removed. The seller was emailed.');
    }
}
