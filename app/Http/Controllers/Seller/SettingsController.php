<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Rules\ImageSpec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/** Seller Center: how a store ships and takes returns, and how it looks to shoppers. */
class SettingsController extends Controller
{
    public const HANDLING = [1 => '1 business day', 2 => '2 business days', 3 => '3 business days', 5 => '5 business days', 10 => 'Made to order (up to 2 weeks)'];
    public const RETURN_WINDOWS = [14, 30, 60];

    public function settings(Request $request)
    {
        return view('seller.center.settings', ['seller' => $request->user()->seller, 'handling' => self::HANDLING, 'windows' => self::RETURN_WINDOWS]);
    }

    public function saveSettings(Request $request)
    {
        $data = $request->validate([
            'handling_days' => ['required', 'integer', Rule::in(array_keys(self::HANDLING))],
            'return_window_days' => ['required', 'integer', Rule::in(self::RETURN_WINDOWS)],
            'return_shipping_payer' => ['required', Rule::in(['buyer', 'seller'])],
        ]);

        $request->user()->seller->update($data);

        return redirect()->route('seller.settings')->with('status', 'Settings saved. Shoppers see them on your product pages.');
    }

    public function storefront(Request $request)
    {
        return view('seller.center.storefront', ['seller' => $request->user()->seller]);
    }

    public function saveStorefront(Request $request)
    {
        $seller = $request->user()->seller;
        $data = $request->validate([
            'about' => ['nullable', 'string', 'max:1000'],
            'logo' => ['nullable', new ImageSpec('logo')],
            'remove_logo' => ['nullable', 'boolean'],
        ]);

        $attrs = ['about' => filled($data['about'] ?? null) ? trim($data['about']) : null];

        if ($request->hasFile('logo')) {
            $old = $seller->logo;
            $attrs['logo'] = $request->file('logo')->store('logos', 'public');
            $old && Storage::disk('public')->delete($old);
        } elseif ($request->boolean('remove_logo') && $seller->logo) {
            Storage::disk('public')->delete($seller->logo);
            $attrs['logo'] = null;
        }

        $seller->update($attrs);

        return redirect()->route('seller.storefront')->with('status', 'Storefront saved.');
    }
}
