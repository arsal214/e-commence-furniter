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
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = in_array($request->input('status'), ['active', 'inactive'], true)
            ? $request->input('status')
            : null;

        // Search applies to the counts too, so the tab numbers reflect what's filtered.
        $base = Product::query()->when($search, function ($q) use ($search) {
            $q->where(function ($q2) use ($search) {
                $q2->where('name', 'like', "%{$search}%")
                   ->orWhere('sku', 'like', "%{$search}%")
                   ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$search}%"));
            });
        });

        $counts = [
            'all'      => (clone $base)->count(),
            'active'   => (clone $base)->where('is_active', true)->count(),
            'inactive' => (clone $base)->where('is_active', false)->count(),
        ];

        $products = (clone $base)
            ->with('category')
            ->when($status === 'active',   fn($q) => $q->where('is_active', true))
            ->when($status === 'inactive', fn($q) => $q->where('is_active', false))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', compact('products', 'search', 'status', 'counts'));
    }

    public function export()
    {
        $products = Product::orderBy('name')->get(['name', 'slug', 'price', 'sale_price']);

        $filename = 'products-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($products) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'URL', 'Price', 'Sale Price']);

            foreach ($products as $product) {
                fputcsv($handle, [
                    $product->name,
                    route('product-details', $product->slug),
                    number_format($product->price, 2),
                    $product->sale_price ? number_format($product->sale_price, 2) : '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
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
            'brand'          => ['nullable', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'key_feature_1'  => ['nullable', 'string', 'max:255'],
            'key_feature_2'  => ['nullable', 'string', 'max:255'],
            'key_feature_3'  => ['nullable', 'string', 'max:255'],
            'review_content' => ['nullable', 'string'],
            'shipping_info'  => ['nullable', 'string'],
            'price'          => ['required', 'numeric', 'min:0'],
            'sale_price'     => ['nullable', 'numeric', 'min:0'],
            'image'          => ['nullable', 'image', 'max:4096'],
            'images.*'       => ['nullable', 'image', 'max:4096'],
            'size_chart'     => ['nullable', 'image', 'max:8192'],
            'tag'            => ['nullable', 'in:Sale,NEW,OFF,OFF1'],
            'sku'           => ['nullable', 'string', 'max:100'],
            'gtin'          => ['nullable', 'string', 'max:50'],
            'mpn'           => ['nullable', 'string', 'max:100'],
            'spec_label'    => ['nullable', 'array'],
            'spec_label.*'  => ['nullable', 'string', 'max:120'],
            'spec_value'    => ['nullable', 'array'],
            'spec_value.*'  => ['nullable', 'string', 'max:255'],
            'stock'         => ['required', 'integer', 'min:0'],
            'is_featured'    => ['nullable', 'boolean'],
            'is_best_seller' => ['nullable', 'boolean'],
            'is_active'      => ['nullable', 'boolean'],
            'colors_raw'    => ['nullable', 'string'],
            'sizes_raw'     => ['nullable', 'string'],
            'supplier_name' => ['nullable', 'string', 'max:100'],
            'supplier_url'  => ['nullable', 'url', 'max:500'],
            'supplier_sku'  => ['nullable', 'string', 'max:100'],
        ]);

        $data['slug']           = Str::slug($data['name']);
        $data['is_featured']    = $request->boolean('is_featured');
        $data['is_best_seller'] = $request->boolean('is_best_seller');
        $data['is_active']      = $request->boolean('is_active');
        $data['sale_price']  = ($data['sale_price'] ?? null) ?: null;
        $data['colors']      = $this->parseVariants($request->input('colors_raw'));
        $data['sizes']       = $this->parseVariants($request->input('sizes_raw'));
        $data['key_features']   = $this->buildKeyFeatures($request);
        $data['specifications'] = $this->buildSpecifications($request);
        $data['gtin'] = trim((string) $request->input('gtin')) ?: null;
        $data['mpn']  = trim((string) $request->input('mpn')) ?: null;

        unset($data['colors_raw'], $data['sizes_raw'], $data['images'],
              $data['key_feature_1'], $data['key_feature_2'], $data['key_feature_3'],
              $data['spec_label'], $data['spec_value']);

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
            'brand'              => ['nullable', 'string', 'max:255'],
            'slug'               => ['nullable', 'string', 'max:255', 'unique:products,slug,' . $product->id, 'regex:/^[a-z0-9\-]+$/'],
            'meta_title'         => ['nullable', 'string', 'max:160'],
            'meta_description'   => ['nullable', 'string', 'max:320'],
            'description'        => ['nullable', 'string'],
            'key_feature_1'      => ['nullable', 'string', 'max:255'],
            'key_feature_2'      => ['nullable', 'string', 'max:255'],
            'key_feature_3'      => ['nullable', 'string', 'max:255'],
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
            'sku'           => ['nullable', 'string', 'max:100'],
            'gtin'          => ['nullable', 'string', 'max:50'],
            'mpn'           => ['nullable', 'string', 'max:100'],
            'spec_label'    => ['nullable', 'array'],
            'spec_label.*'  => ['nullable', 'string', 'max:120'],
            'spec_value'    => ['nullable', 'array'],
            'spec_value.*'  => ['nullable', 'string', 'max:255'],
            'stock'         => ['required', 'integer', 'min:0'],
            'is_featured'    => ['nullable', 'boolean'],
            'is_best_seller' => ['nullable', 'boolean'],
            'is_active'      => ['nullable', 'boolean'],
            'colors_raw'    => ['nullable', 'string'],
            'sizes_raw'     => ['nullable', 'string'],
            'supplier_name' => ['nullable', 'string', 'max:100'],
            'supplier_url'  => ['nullable', 'url', 'max:500'],
            'supplier_sku'  => ['nullable', 'string', 'max:100'],
        ]);

        // Use provided slug or keep existing; never auto-overwrite with name-derived slug here
        $data['slug'] = !empty($data['slug']) ? $data['slug'] : $product->slug;

        $data['is_featured']    = $request->boolean('is_featured');
        $data['is_best_seller'] = $request->boolean('is_best_seller');
        $data['is_active']      = $request->boolean('is_active');
        $data['sale_price']  = ($data['sale_price'] ?? null) ?: null;
        $data['colors']       = $this->parseVariants($request->input('colors_raw'));
        $data['sizes']        = $this->parseVariants($request->input('sizes_raw'));
        $data['key_features']   = $this->buildKeyFeatures($request);
        $data['specifications'] = $this->buildSpecifications($request);
        $data['gtin'] = trim((string) $request->input('gtin')) ?: null;
        $data['mpn']  = trim((string) $request->input('mpn')) ?: null;

        unset($data['colors_raw'], $data['sizes_raw'], $data['remove_size_chart'], $data['remove_images'], $data['images'],
              $data['key_feature_1'], $data['key_feature_2'], $data['key_feature_3'],
              $data['spec_label'], $data['spec_value']);

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

    private function buildKeyFeatures(Request $request): ?string
    {
        $features = array_values(array_filter([
            trim($request->input('key_feature_1', '')),
            trim($request->input('key_feature_2', '')),
            trim($request->input('key_feature_3', '')),
        ], fn($v) => $v !== ''));

        return !empty($features) ? json_encode($features) : null;
    }

    /**
     * Zip the parallel spec_label[] / spec_value[] inputs into ordered label/value
     * rows, dropping any row where either side is blank. Returns null when empty so
     * the column stays NULL rather than storing "[]".
     */
    private function buildSpecifications(Request $request): ?array
    {
        $labels = $request->input('spec_label', []);
        $values = $request->input('spec_value', []);
        $specs  = [];

        foreach ((array) $labels as $i => $label) {
            $label = trim((string) $label);
            $value = trim((string) ($values[$i] ?? ''));
            if ($label !== '' && $value !== '') {
                $specs[] = ['label' => $label, 'value' => $value];
            }
        }

        return $specs ?: null;
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
