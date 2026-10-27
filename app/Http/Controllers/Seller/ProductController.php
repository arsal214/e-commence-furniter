<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Rules\ImageSpec;
use App\Services\ListingScreen;
use App\Services\StoreSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public const MAX_GALLERY = 8;
    public const MAX_OPTIONS = 30;

    public function index(Request $request)
    {
        $seller = $request->user()->seller;
        $status = $request->query('status');

        $live = fn () => $seller->products()->where('is_active', true)->where('moderation_status', 'approved')->count();

        $products = $seller->products()->withCount('variants')
            ->withSum(['variants as color_stock' => fn ($q) => $q->where('type', 'color')], 'stock')->withSum(['variants as size_stock' => fn ($q) => $q->where('type', 'size')], 'stock')
            ->withSum(['orderItems as sold_qty' => fn ($q) => $q->whereHas('order', fn ($o) => $o->where('payment_status', 'paid'))], 'qty')
            ->when($status === 'live', fn ($q) => $q->where('is_active', true)->where('moderation_status', 'approved'))
            ->when($status === 'review', fn ($q) => $q->whereIn('moderation_status', ['pending', 'flagged']))
            ->when($status === 'rejected', fn ($q) => $q->where('moderation_status', 'removed'))
            ->when($status === 'draft', fn ($q) => $q->where('is_active', false))
            ->latest('id')->paginate(20)->withQueryString();

        return view('seller.center.products.index', ['seller' => $seller, 'products' => $products, 'status' => $status, 'liveCount' => $live(),
            'reviewCount' => $seller->products()->whereIn('moderation_status', ['pending', 'flagged'])->count()]);
    }

    public function create(Request $request)
    {
        return view('seller.center.products.form', ['seller' => $request->user()->seller, 'product' => new Product(['is_active' => true]), 'categories' => $this->categories()]);
    }

    public function store(Request $request)
    {
        $seller = $request->user()->seller;
        $data = $this->validated($request, null);

        // Double-tap guard: the same title from the same seller within a minute is the same product.
        // The lock is held across the check AND the create, so two taps cannot both pass the check.
        $lock = \Illuminate\Support\Facades\Cache::lock("seller-product:{$seller->id}", 30);
        $lock->block(15);

        try {
            $dupe = $seller->products()->where('name', trim($data['name']))->where('created_at', '>=', now()->subMinute())->first();
            if ($dupe) {
                return redirect()->route('seller.products.edit', $dupe)->with('status', 'Product saved. It will go live after our team reviews it.');
            }

            $product = DB::transaction(function () use ($request, $seller, $data) {
                $product = Product::create($this->attributes($data, $request) + [
                    'seller_id' => $seller->id,
                    'slug' => $this->uniqueSlug($data['name']),
                    'image' => $request->file('image')->store('products', 'public'),
                ] + $this->reviewState($data));
                $this->syncGallery($product, $request);
                $this->syncVariants($product, $data);
                app(StoreSale::class)->refresh($product->fresh('variants'), $seller);

                return $product;
            });
        } finally {
            $lock->release();
        }

        return redirect()->route('seller.products.edit', $product)->with('status', 'Product saved. It will go live after our team reviews it.');
    }

    public function edit(Request $request, int $product)
    {
        $seller = $request->user()->seller;
        $product = $seller->products()->with('variants', 'productImages')->findOrFail($product);

        return view('seller.center.products.form', ['seller' => $seller, 'product' => $product, 'categories' => $this->categories()]);
    }

    public function update(Request $request, int $product)
    {
        $seller = $request->user()->seller;
        $product = $seller->products()->with('variants')->findOrFail($product);
        $data = $this->validated($request, $product);

        DB::transaction(function () use ($request, $product, $data, $seller) {
            $attrs = $this->attributes($data, $request);
            $wasStorewide = $product->storewide_sale;
            $oldSale = (float) $product->sale_price;

            // Words and photos are what the team reviews; price and stock changes go live at once.
            $changedContent = $product->name !== $data['name'] || $product->description !== ($data['description'] ?? null)
                || $request->hasFile('image') || $request->hasFile('gallery');
            // New wording or photos go back to a reviewer, including a removed listing the seller fixed.
            if ($changedContent) {
                $attrs += $this->reviewState($data);
            }
            if ($request->hasFile('image')) {
                $old = $product->image;
                $attrs['image'] = $request->file('image')->store('products', 'public');
                $old && Storage::disk('public')->delete($old);
            }

            $product->update($attrs);
            $this->syncGallery($product, $request);
            $this->syncVariants($product, $data);

            // Changing the sale price by hand while a store sale runs makes it the seller's own; otherwise the sale follows the new price.
            if ($wasStorewide && abs((float) ($attrs['sale_price'] ?? 0) - $oldSale) > 0.001) {
                $product->forceFill(['storewide_sale' => false, 'sale_backup' => null])->save();
            }
            app(StoreSale::class)->refresh($product->fresh('variants'), $seller);
        });

        return redirect()->route('seller.products.edit', $product)->with('status', 'Changes saved.');
    }

    public function destroy(Request $request, int $product)
    {
        $product = $request->user()->seller->products()->findOrFail($product);

        // A product with sales stays in the database for order history; it just stops being sold.
        if ($product->orderItems()->exists()) {
            $product->update(['is_active' => false]);

            return redirect()->route('seller.products.index')->with('status', 'This product has orders, so it was hidden instead of deleted.');
        }

        $product->delete();

        return redirect()->route('seller.products.index')->with('status', 'Product deleted.');
    }

    // ── validation + saving ───────────────────────────────────────────────────────

    protected function validated(Request $request, ?Product $product): array
    {
        $leaf = Rule::exists('categories', 'id')->where(fn ($q) => $q->whereNotNull('parent_id')->where('is_active', true));
        $option = fn (string $type) => [
            "{$type}" => ['nullable', 'array', 'max:' . self::MAX_OPTIONS],
            "{$type}.*.value" => ['nullable', 'string', 'max:60'],
            "{$type}.*.price" => ['nullable', 'numeric', 'min:0.5', 'max:100000'],
            "{$type}.*.stock" => ['nullable', 'integer', 'min:0', 'max:1000000'],
            "{$type}.*.sku" => ['nullable', 'string', 'max:60'],
            "{$type}.*.image" => ['nullable', new ImageSpec('product')],
        ];

        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'category_id' => ['required', $leaf],
            'description' => ['nullable', 'string', 'max:5000'],
            'brand' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0.5', 'max:100000'],
            'sale_price' => ['nullable', 'numeric', 'min:0.5', 'lt:price'],
            'stock' => ['required', 'integer', 'min:0', 'max:1000000'],
            'sku' => ['nullable', 'string', 'max:60'],
            'is_active' => ['nullable', 'boolean'],
            'image' => [$product ? 'nullable' : 'required', new ImageSpec('product')],
            'gallery' => ['nullable', 'array', 'max:' . self::MAX_GALLERY],
            'gallery.*' => [new ImageSpec('product')],
        ] + $option('colors') + $option('sizes'), [
            'category_id.exists' => 'Choose a subcategory.',
            'sale_price.lt' => 'The sale price must be lower than the regular price.',
            'image.required' => 'Add a main product photo.',
        ]);

        foreach (['colors', 'sizes'] as $type) {
            $values = collect($data[$type] ?? [])->pluck('value')->filter()->map(fn ($v) => Str::lower(trim($v)));
            if ($values->count() !== $values->unique()->count()) {
                throw \Illuminate\Validation\ValidationException::withMessages([$type => 'Each ' . rtrim($type, 's') . ' can be listed only once.']);
            }
        }

        return $data;
    }

    /** Every new or changed listing is reviewed; the screen only decides whether it is also flagged. */
    protected function reviewState(array $data): array
    {
        $hit = app(ListingScreen::class)->match($data['name'], $data['description'] ?? '', $data['brand'] ?? '');

        return $hit
            ? ['moderation_status' => 'flagged', 'moderation_note' => "Auto-check: possible prohibited item (\"{$hit}\")"]
            : ['moderation_status' => 'pending', 'moderation_note' => null];
    }

    protected function attributes(array $data, Request $request): array
    {
        return [
            'name' => trim($data['name']),
            'category_id' => $data['category_id'],
            'description' => $data['description'] ?? null,
            'brand' => $data['brand'] ?? null,
            'price' => $data['price'],
            'sale_price' => $data['sale_price'] ?? null,
            'stock' => $data['stock'],
            'sku' => $data['sku'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'colors' => collect($data['colors'] ?? [])->pluck('value')->filter()->map('trim')->values()->all() ?: null,
            'sizes' => collect($data['sizes'] ?? [])->pluck('value')->filter()->map('trim')->values()->all() ?: null,
        ];
    }

    protected function syncGallery(Product $product, Request $request): void
    {
        foreach ($request->file('gallery', []) as $i => $file) {
            if ($product->productImages()->count() >= self::MAX_GALLERY) {
                break;
            }
            ProductImage::create(['product_id' => $product->id, 'image' => $file->store('products', 'public'), 'type' => 'image', 'sort_order' => $i]);
        }

        // Removing a photo: only ids that belong to this product are honoured.
        foreach ($product->productImages()->whereIn('id', (array) $request->input('remove_images', []))->get() as $img) {
            Storage::disk('public')->delete($img->image);
            $img->delete();
        }
    }

    /** Replaces the colour and size rows. Order lines keep colour/size as text, so ids may change. */
    protected function syncVariants(Product $product, array $data): void
    {
        $kept = $product->variants->mapWithKeys(fn ($v) => [$v->type . '|' . Str::lower($v->value) => $v->image]);
        $product->variants()->delete();

        foreach (['color' => 'colors', 'size' => 'sizes'] as $type => $field) {
            foreach (array_values(array_filter($data[$field] ?? [], fn ($r) => filled($r['value'] ?? null))) as $i => $row) {
                $value = trim($row['value']);
                $image = isset($row['image']) ? $row['image']->store('products', 'public') : ($kept[$type . '|' . Str::lower($value)] ?? null);

                $product->variants()->create([
                    'type' => $type, 'value' => $value, 'price' => $row['price'] ?? $product->price, 'stock' => $row['stock'] ?? 0,
                    'sku' => $row['sku'] ?? null, 'image' => $image, 'sort_order' => $i, 'is_active' => true,
                ]);
            }
        }
    }

    protected function categories()
    {
        return Category::where('is_active', true)->whereNull('parent_id')->with(['children' => fn ($q) => $q->where('is_active', true)->orderBy('name')])->orderBy('name')->get()
            ->filter(fn ($c) => $c->children->isNotEmpty());
    }

    protected function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        for ($i = 2; Product::where('slug', $slug)->exists(); $i++) {
            $slug = $base . '-' . $i;
        }

        return $slug;
    }
}
