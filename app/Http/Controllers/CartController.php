<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function index()
    {
        return view('cart', [
            'cartItems' => $this->cart->items(),
            'cartTotal' => $this->cart->total(),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $qty     = (int) $request->input('qty', 1);
        $color   = $request->input('color') ?: null;
        $size    = $request->input('size')  ?: null;

        $stock = (int) $product->stock;
        if ($stock > 0) {
            $inCart = $this->cart->getQty($product->id, $color, $size);
            if ($inCart >= $stock) {
                return back()->with('error', '"' . $product->name . '" is already at the maximum stock quantity (' . $stock . ') in your cart.');
            }
            if ($inCart + $qty > $stock) {
                $qty = $stock - $inCart;
                $this->cart->add($product, $qty, $color, $size);
                return back()->with('error', 'Only ' . $qty . ' more unit(s) added — stock limit of ' . $stock . ' reached for "' . $product->name . '".');
            }
        }

        $this->cart->add($product, $qty, $color, $size);

        return back()->with('success', '"' . $product->name . '" added to cart.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string',
            'qty'      => 'required|integer|min:0',
        ]);

        $qty       = (int) $request->qty;
        $cartItems = $this->cart->items();

        if ($qty > 0 && isset($cartItems[$request->cart_key])) {
            $product = Product::find($cartItems[$request->cart_key]['id']);
            $stock   = (int) ($product->stock ?? 0);
            if ($stock > 0 && $qty > $stock) {
                return back()->with('error', 'Only ' . $stock . ' unit(s) of "' . $product->name . '" are in stock.');
            }
        }

        $this->cart->update($request->cart_key, $qty);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request)
    {
        $request->validate(['cart_key' => 'required|string']);
        $this->cart->remove($request->cart_key);

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        $this->cart->clear();
        return back()->with('success', 'Cart cleared.');
    }
}
