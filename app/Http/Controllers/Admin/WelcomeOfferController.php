<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\WelcomeClaim;
use App\Models\WelcomeOffer;
use App\Models\WelcomeOfferTier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class WelcomeOfferController extends Controller
{
    /** The one offer the store runs; created inactive on first visit if the seeder never ran. */
    protected function offer(): WelcomeOffer
    {
        return WelcomeOffer::query()->latest('id')->first()
            ?? WelcomeOffer::create(['name' => 'New customer welcome deal', 'is_active' => false, 'expiry_hours' => 72]);
    }

    public function index()
    {
        $offer = $this->offer()->load('tiers');

        $claims = WelcomeClaim::where('welcome_offer_id', $offer->id);
        $used   = (clone $claims)->whereNotNull('used_at');

        $stats = [
            'claims'   => (clone $claims)->count(),
            'redeemed' => (clone $used)->count(),
            'discount' => (float) (clone $used)->sum('discount_given'),
            'revenue'  => (float) Order::whereIn('id', (clone $used)->pluck('used_order_id'))->sum('total'),
        ];
        $stats['rate'] = $stats['claims'] ? round($stats['redeemed'] / $stats['claims'] * 100, 1) : 0.0;

        $perTier = (clone $used)->get()->groupBy(fn ($c) => $c->used_tier_label ?: 'Unknown')
            ->map(fn ($g) => ['count' => $g->count(), 'discount' => (float) $g->sum('discount_given')]);

        $recent = (clone $claims)->latest('claimed_at')->limit(25)->get();

        return view('admin.welcome-offer.index', compact('offer', 'stats', 'perTier', 'recent'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name'                 => 'required|string|max:120',
            'expiry_hours'         => 'required|integer|min:1|max:720',
            'starts_at'            => 'nullable|date',
            'ends_at'              => 'nullable|date|after:starts_at',
            'copy'                 => 'nullable|array',
            'copy.*'               => 'nullable|string|max:200',
            'tiers'                => 'required|array|min:1|max:6',
            'tiers.*.type'         => ['required', Rule::in([WelcomeOfferTier::FIXED, WelcomeOfferTier::PERCENT, WelcomeOfferTier::FREE_SHIPPING])],
            'tiers.*.amount'       => 'required|numeric|min:0|max:1000',
            'tiers.*.min_subtotal' => 'required|numeric|min:0|max:10000',
            'tiers.*.max_discount' => 'nullable|numeric|min:0|max:1000',
        ]);

        foreach ($data['tiers'] as $i => $tier) {
            if ($tier['type'] === WelcomeOfferTier::PERCENT && (float) $tier['amount'] > 50) {
                return back()->withErrors(["tiers.$i.amount" => 'A percent tier cannot exceed 50%.'])->withInput();
            }
        }

        $offer = $this->offer();

        DB::transaction(function () use ($offer, $data, $request) {
            $offer->update([
                'name'             => $data['name'],
                'is_active'        => $request->boolean('is_active'),
                'expiry_hours'     => $data['expiry_hours'],
                'starts_at'        => $data['starts_at'] ?? null,
                'ends_at'          => $data['ends_at'] ?? null,
                'display_settings' => array_filter($data['copy'] ?? [], fn ($v) => filled($v)),
            ]);

            // Tiers are rebuilt from the form; spent claims keep their own discount_given.
            $offer->tiers()->delete();
            foreach (array_values($data['tiers']) as $i => $tier) {
                $offer->tiers()->create([
                    'type'         => $tier['type'],
                    'amount'       => $tier['amount'],
                    'min_subtotal' => $tier['min_subtotal'],
                    'max_discount' => $tier['max_discount'] ?? null,
                    'sort_order'   => $i + 1,
                ]);
            }
        });

        return redirect()->route('admin.welcome-offer.index')->with('success', 'Welcome offer saved.');
    }
}
