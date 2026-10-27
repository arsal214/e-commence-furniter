<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SellerNoticeMail;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SellerController extends Controller
{
    public const DOCUMENTS = ['registration', 'id_front', 'id_back', 'ein_letter'];
    protected const WEBMAIL = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'aol.com', 'icloud.com', 'mail.com', 'proton.me', 'protonmail.com'];

    public function index(Request $request)
    {
        return view('admin.market', ['page' => 'sellers', 'heading' => 'Seller approvals'] + \App\Support\AdminViews::sellers($request));
    }

    public function show(Seller $seller)
    {
        return view('admin.market', ['page' => 'seller', 'heading' => 'Seller review'] + \App\Support\AdminViews::seller($seller));
    }

    public function decide(Request $request, Seller $seller)
    {
        $data = $request->validate([
            'action' => 'required|in:approve,request_info,reject',
            'note' => ['nullable', 'string', 'max:1000', 'required_if:action,request_info,reject', 'min:5'],
        ], ['note.required_if' => 'Write a note for the seller. It is sent by email.', 'note.min' => 'Write a note for the seller. It is sent by email.']);

        $result = DB::transaction(function () use ($data, $seller) {
            $locked = Seller::with('user')->whereKey($seller->id)->lockForUpdate()->firstOrFail();

            // Two admins clicking at once: only the first decision counts.
            if (! in_array($locked->status, ['in_review', 'info_requested'], true)) {
                return ['error' => 'This application was already decided (' . str_replace('_', ' ', $locked->status) . ').'];
            }
            if ($data['action'] === 'approve' && ($blockers = self::blockers($locked))) {
                return ['error' => 'Cannot approve yet: ' . implode('; ', $blockers) . '.'];
            }

            $status = ['approve' => 'approved', 'request_info' => 'info_requested', 'reject' => 'rejected'][$data['action']];
            $locked->update([
                'status' => $status,
                'admin_note' => $data['note'] ?? null,
                'approved_at' => $status === 'approved' ? now() : null,
            ]);

            return ['seller' => $locked, 'status' => $status];
        });

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        $this->notify($result['seller'], $result['status'], $data['note'] ?? null);
        app(\App\Services\ReferralService::class)->creditFor($result['seller']->fresh());

        return redirect()->route('admin.sellers.index')->with('success', $result['seller']->store_name . ': ' . str_replace('_', ' ', $result['status']) . '. The seller was emailed.');
    }

    /** Streams a private document to the signed-in admin; never a public URL. */
    public function document(Seller $seller, string $type)
    {
        abort_unless(in_array($type, self::DOCUMENTS, true), 404);
        $doc = $seller->documents()->where('type', $type)->firstOrFail();
        abort_unless(Storage::disk('local')->exists($doc->path), 404);

        Log::info("Admin " . auth()->id() . " viewed seller {$seller->id} document {$type}");

        return Storage::disk('local')->response($doc->path, null, [
            'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    /** Hard stops: approving without these would let an unpaid or unverified store go live. */
    public static function blockers(Seller $seller): array
    {
        $out = [];
        if ($seller->documents->pluck('type')->intersect(self::DOCUMENTS)->count() < count(self::DOCUMENTS)) {
            $out[] = 'a required document is missing';
        }
        if ($seller->subscription_status !== 'active') {
            $out[] = 'the $19.99 plan is not active';
        }
        if (! $seller->stripe_account_id) {
            $out[] = 'no bank account is connected';
        }

        return $out;
    }

    /** @return list<array{0:string,1:string,2:string}> [state ok|warn|fail, text] */
    public static function checks(Seller $seller): array
    {
        $domain = strtolower(substr(strrchr((string) $seller->owner_email, '@') ?: '', 1));
        $dupe = $seller->ein ? Seller::where('ein', $seller->ein)->where('id', '!=', $seller->id)->exists() : false;

        return [
            [preg_match('/^\d{2}-\d{7}$/', (string) $seller->ein) && $seller->ein !== '00-0000000' ? 'ok' : 'fail', 'EIN format is valid'],
            [$dupe ? 'fail' : 'ok', $dupe ? 'Another seller already uses this EIN' : 'EIN is not used by another seller'],
            [in_array($domain, self::WEBMAIL, true) ? 'warn' : 'ok', in_array($domain, self::WEBMAIL, true) ? 'Owner email is a free webmail address' : 'Owner email is on a business domain'],
            [$seller->documents->count() >= count(self::DOCUMENTS) ? 'ok' : 'fail', 'All four documents uploaded'],
            [$seller->subscription_status === 'active' ? 'ok' : 'fail', '$19.99 plan is active'],
            [$seller->stripe_account_id ? 'ok' : 'fail', 'Bank connected through Stripe'],
        ];
    }

    protected function notify(Seller $seller, string $status, ?string $note): void
    {
        [$subject, $headline, $body, $btn] = match ($status) {
            'approved' => ['Your Peyton Ghalib store is approved', 'You\'re approved!', 'Your seller account is verified. Your store goes live as soon as your products are approved too.', ['Open Seller Center', route('seller.dashboard')]],
            'info_requested' => ['More information needed for your seller application', 'We need a bit more from you', 'Please update your details and resubmit. We will review again right away.', ['Update my application', route('seller.dashboard')]],
            default => ['Your Peyton Ghalib seller application', 'We couldn\'t approve your application', 'After review, we are unable to approve your seller application.', [null, null]],
        };

        try {
            Mail::to($seller->user->email)->send(new SellerNoticeMail($subject, $headline, $body, $note, $btn[0], $btn[1]));
        } catch (\Throwable $e) {
            Log::warning("Seller decision email to {$seller->id} failed: " . $e->getMessage());
        }
    }
}
