<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;

/** What shoppers can browse: only categories that currently hold visible products. */
class MarketCatalog
{
    /** Built once per request: the app binds this class as a singleton. */
    protected ?Collection $tree = null;

    /**
     * Top-level categories with their non-empty subcategories. A product sits on a
     * subcategory (or, for the old catalogue, directly on a top-level category).
     *
     * @return Collection<int, Category> each with `children` (non-empty) and a `shop_count`
     */
    public function tree(): Collection
    {
        if ($this->tree !== null) {
            return $this->tree;
        }

        $counts = Product::visible()->selectRaw('products.category_id, count(*) c')->groupBy('products.category_id')->pluck('c', 'category_id');
        $cats = Category::where('is_active', true)->orderBy('name')->get();
        $kids = $cats->whereNotNull('parent_id')->groupBy('parent_id');

        return $this->tree = $cats->whereNull('parent_id')->map(function (Category $root) use ($counts, $kids) {
            $children = ($kids[$root->id] ?? collect())->filter(fn ($c) => ($counts[$c->id] ?? 0) > 0)->each(fn ($c) => $c->shop_count = $counts[$c->id]);
            $root->setRelation('children', $children->values());
            $root->shop_count = ($counts[$root->id] ?? 0) + $children->sum('shop_count');

            return $root;
        })->filter(fn ($c) => $c->shop_count > 0)->values();
    }

    /** Ids a category page should list: the category itself plus its subcategories. */
    public function idsFor(Category $category): array
    {
        return Category::where('id', $category->id)->orWhere('parent_id', $category->id)->pluck('id')->all();
    }
}
