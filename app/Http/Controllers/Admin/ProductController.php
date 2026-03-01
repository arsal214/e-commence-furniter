<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
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
            'category_id'    => ['required', 'exists:categories,id'],
            'name'           => ['required', 'string', 'max:255', 'unique:products,name'],
            'description'    => ['nullable', 'string'],
            'review_content' => ['nullable', 'string'],
            'shipping_info'  => ['nullable', 'string'],
            'price'          => ['required', 'numeric', 'min:0'],
            'sale_price'     => ['nullable', 'numeric', 'min:0'],
            'image'          => ['nullable', 'image', 'max:4096'],
            'images.*'       => ['nullable', 'image', 'max:4096'],
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

        unset($data['colors_raw'], $data['sizes_raw'], $data['images']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        if ($request->hasFile('size_chart')) {
            $data['size_chart'] = $request->file('size_chart')->store('size-charts', 'public');
        }

        $product = Product::create($data);

        // Store additional gallery images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                $product->productImages()->create([
                    'image'      => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $product->load('productImages');
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
            'images.*'           => ['nullable', 'image', 'max:4096'],
            'size_chart'         => ['nullable', 'image', 'max:8192'],
            'remove_size_chart'  => ['nullable', 'boolean'],
            'remove_images'      => ['nullable', 'array'],
            'remove_images.*'    => ['integer'],
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

        unset($data['colors_raw'], $data['sizes_raw'], $data['remove_size_chart'], $data['remove_images'], $data['images']);

        if ($request->hasFile('image')) {
            if ($product->image && \Storage::disk('public')->exists($product->image)) {
                \Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

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

        // Remove selected gallery images
        $removeIds = $request->input('remove_images', []);
        if (!empty($removeIds)) {
            $toDelete = $product->productImages()->whereIn('id', $removeIds)->get();
            foreach ($toDelete as $img) {
                if (\Storage::disk('public')->exists($img->image)) {
                    \Storage::disk('public')->delete($img->image);
                }
                $img->delete();
            }
        }

        // Add new gallery images
        if ($request->hasFile('images')) {
            $nextOrder = $product->productImages()->max('sort_order') + 1;
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                $product->productImages()->create([
                    'image'      => $path,
                    'sort_order' => $nextOrder + $index,
                ]);
            }
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

        foreach ($product->productImages as $img) {
            if (\Storage::disk('public')->exists($img->image)) {
                \Storage::disk('public')->delete($img->image);
            }
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
