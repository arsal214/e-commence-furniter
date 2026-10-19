<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    public function index()
    {
        $bundles = Bundle::with('items.product.category', 'items.variant')->orderBy('id')->get();

        return view('admin.bundles.index', compact('bundles'));
    }

    public function update(Request $request, Bundle $bundle)
    {
        $data = $request->validate([
            'price'     => 'required|numeric|min:0.01|max:100000',
            'starts_at' => 'nullable|date',
            'ends_at'   => 'nullable|date|after:starts_at',
        ]);

        $bundle->load('items.product', 'items.variant');
        $activate = $request->boolean('is_active');

        // A set priced at or above its parts is not a saving; refuse to switch it on.
        if ($activate && (float) $data['price'] >= $bundle->regularTotal()) {
            return back()->withErrors(['price' => "\"{$bundle->name}\": set price must be below the regular total of $" . number_format($bundle->regularTotal(), 2) . '.']);
        }

        $bundle->update($data + ['is_active' => $activate]);

        return back()->with('success', "\"{$bundle->name}\" saved.");
    }
}
