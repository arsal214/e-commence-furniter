<?php

namespace App\Services;

use App\Models\Seller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/** Seller referrals: one free month ($19.99 off the next bill) when an invited business is approved and has paid. */
class ReferralService
{
    public const MAX_PER_YEAR = 12;
    public const COOKIE = 'pg_ref';

    public function __construct(protected SellerBilling $billing) {}

    /** The seller's invite code, made on first use. */
    public function codeFor(Seller $seller): string
    {
        if ($seller->referral_code) {
            return $seller->referral_code;
        }
        do {
            $code = strtoupper(Str::random(8));
        } while (Seller::where('referral_code', $code)->exists());
        $seller->update(['referral_code' => $code]);

        return $code;
    }

    public function linkFor(Seller $seller): string
    {
        return url('/sell') . '?ref=' . $this->codeFor($seller);
    }

    /** The referrer behind a code a visitor arrived with, if it is a live code. */
    public function referrerFor(?string $code): ?Seller
    {
        return is_string($code) && preg_match('/^[A-Z0-9]{8}$/', $code) ? Seller::where('referral_code', $code)->first() : null;
    }

    public function usedThisYear(Seller $referrer): int
    {
        return Seller::where('referred_by', $referrer->id)->whereNotNull('referral_credited_at')->where('referral_credited_at', '>=', now()->startOfYear())->count();
    }

    /**
     * Gives the referrer their free month once the invited business is approved and its plan is paid.
     * Safe to call from anywhere, any number of times: it credits once, never past the yearly limit.
     */
    public function creditFor(Seller $referred): bool
    {
        if (! $referred->referred_by || $referred->referral_credited_at || $referred->status !== 'approved' || $referred->subscription_status !== 'active') {
            return false;
        }

        return DB::transaction(function () use ($referred) {
            $locked = Seller::whereKey($referred->id)->lockForUpdate()->first();
            $referrer = Seller::find($locked?->referred_by);
            if (! $locked || $locked->referral_credited_at || ! $referrer || $referrer->id === $locked->id) {
                return false;
            }
            if (! $referrer->stripe_customer_id || $this->usedThisYear($referrer) >= self::MAX_PER_YEAR) {
                return false;
            }

            try {
                $this->billing->creditAmount($referrer, Seller::SUBSCRIPTION_PRICE, 'Referral: one free month');
            } catch (\Throwable $e) {
                Log::warning('Referral credit failed: ' . $e->getMessage()); // retried the next time either seller's status changes

                return false;
            }
            $locked->update(['referral_credited_at' => now()]);

            return true;
        });
    }
}
