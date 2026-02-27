<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:255', 'unique:products,name'],
            'description'    => ['nullable', 'string'],
            'review_content' => ['nullable', 'string'],
            'shipping_info'  => ['nullable', 'string'],
            'price'          => ['required', 'numeric', 'min:0'],
            'sale_price'     => ['nullable', 'numeric', 'min:0'],
            'image'          => ['nullable', 'image', 'max:4096'],
            'size_chart'     => ['nullable', 'image', 'max:8192'],
            'tag'            => ['nullable', 'in:Sale,NEW,OFF,OFF1'],
            'sku'            => ['nullable', 'string', 'max:100'],
            'stock'          => ['required', 'integer', 'min:0'],
            'is_featured'    => ['nullable', 'boolean'],
            'is_active'      => ['nullable', 'boolean'],
            'colors_raw'     => ['nullable', 'string'],
            'sizes_raw'      => ['nullable', 'string'],
        ]);

        $data['slug']        = Str::slug($data['name']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active']   = $request->boolean('is_active', true);
        $data['sale_price']  = $data['sale_price'] ?: null;
        $data['colors']      = $this->parseVariants($request->input('colors_raw'));
        $data['sizes']       = $this->parseVariants($request->input('sizes_raw'));

        unset($data['colors_raw'], $data['sizes_raw']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        if ($request->hasFile('size_chart')) {
            $data['size_chart'] = $request->file('size_chart')->store('size-charts', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id'        => ['required', 'exists:categories,id'],
            'name'               => ['required', 'string', 'max:255', 'unique:products,name,' . $product->id],
            'description'        => ['nullable', 'string'],
            'review_content'     => ['nullable', 'string'],
            'shipping_info'      => ['nullable', 'string'],
            'price'              => ['required', 'numeric', 'min:0'],
            'sale_price'         => ['nullable', 'numeric', 'min:0'],
            'image'              => ['nullable', 'image', 'max:4096'],
            'size_chart'         => ['nullable', 'image', 'max:8192'],
            'remove_size_chart'  => ['nullable', 'boolean'],
            'tag'                => ['nullable', 'in:Sale,NEW,OFF,OFF1'],
            'sku'                => ['nullable', 'string', 'max:100'],
            'stock'              => ['required', 'integer', 'min:0'],
            'is_featured'        => ['nullable', 'boolean'],
            'is_active'          => ['nullable', 'boolean'],
            'colors_raw'         => ['nullable', 'string'],
            'sizes_raw'          => ['nullable', 'string'],
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active']   = $request->boolean('is_active', true);
        $data['sale_price']  = $data['sale_price'] ?: null;
        $data['colors']      = $this->parseVariants($request->input('colors_raw'));
        $data['sizes']       = $this->parseVariants($request->input('sizes_raw'));

        unset($data['colors_raw'], $data['sizes_raw'], $data['remove_size_chart']);

        if ($request->hasFile('image')) {
            if ($product->image && \Storage::disk('public')->exists($product->image)) {
                \Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Handle size chart: upload new, or remove existing
        if ($request->hasFile('size_chart')) {
            if ($product->size_chart && \Storage::disk('public')->exists($product->size_chart)) {
                \Storage::disk('public')->delete($product->size_chart);
            }
            $data['size_chart'] = $request->file('size_chart')->store('size-charts', 'public');
        } elseif ($request->boolean('remove_size_chart')) {
            if ($product->size_chart && \Storage::disk('public')->exists($product->size_chart)) {
                \Storage::disk('public')->delete($product->size_chart);
            }
            $data['size_chart'] = null;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && \Storage::disk('public')->exists($product->image)) {
            \Storage::disk('public')->delete($product->image);
        }

        if ($product->size_chart && \Storage::disk('public')->exists($product->size_chart)) {
            \Storage::disk('public')->delete($product->size_chart);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product deleted successfully.');
    }

    private function parseVariants(?string $raw): ?array
    {
        if (!$raw || trim($raw) === '') {
            return null;
        }
        $values = array_map('trim', explode(',', $raw));
        $values = array_filter($values, fn($v) => $v !== '');
        return array_values($values) ?: null;
    }
}
