<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashDeal;
use Illuminate\Http\Request;

class FlashDealController extends Controller
{
    public function index()
    {
        $deal = FlashDeal::current();
        return view('admin.flash-deal.index', compact('deal'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'is_active'      => 'boolean',
            'title'          => 'required|string|max:120',
            'subtitle'       => 'nullable|string|max:255',
            'discount_label' => 'required|string|max:60',
            'badge_text'     => 'required|string|max:40',
            'ends_at'        => 'nullable|date',
            'cta_text'       => 'required|string|max:60',
            'cta_url'        => 'required|string|max:255',
            'bg_color'       => 'required|string|max:20',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $deal = FlashDeal::current();
        $deal->update($data);

        return back()->with('success', 'Flash deal updated successfully.');
    }
}
