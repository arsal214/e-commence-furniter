<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

/**
 * Flags products whose description shares no significant word with the product
 * name — the signature of the import bug where a shifted CSV row dropped one
 * product's description onto another. Report only; fixing is a human/re-import
 * decision since we can't know the correct text.
 *
 *   php artisan products:audit-descriptions            (active products)
 *   php artisan products:audit-descriptions --all      (include inactive)
 */
class AuditProductDescriptions extends Command
{
    protected $signature = 'products:audit-descriptions {--all : Include inactive products}';

    protected $description = 'Flag products whose description does not appear to match their name';

    public function handle(): int
    {
        $query = Product::query()->whereNotNull('description')->where('description', '!=', '');
        if (! $this->option('all')) {
            $query->where('is_active', true);
        }

        $products = $query->get(['id', 'name', 'description']);
        $flagged  = [];

        foreach ($products as $p) {
            if (! $this->matches($p->description, $p->name)) {
                $flagged[] = [
                    $p->id,
                    \Illuminate\Support\Str::limit($p->name, 40),
                    \Illuminate\Support\Str::limit(strip_tags($p->description), 60),
                ];
            }
        }

        $this->info("Scanned {$products->count()} product(s) with a description.");

        if (empty($flagged)) {
            $this->info('No mismatches found. ✓');
            return self::SUCCESS;
        }

        $this->warn(count($flagged) . ' product(s) may have a mismatched description — review these:');
        $this->table(['ID', 'Product name', 'Description (start)'], $flagged);
        $this->line('');
        $this->comment('Note: this is a heuristic — a product whose copy simply never repeats its name');
        $this->comment('will show up as a false positive. Eyeball each row before changing anything.');

        return self::FAILURE; // non-zero so CI / scripts can detect findings
    }

    /** True if the description shares at least one significant word with the title. */
    private function matches(string $description, string $title): bool
    {
        $desc  = strtolower(strip_tags($description));
        $words = array_filter(
            preg_split('/\W+/', strtolower($title)),
            fn ($w) => strlen($w) > 3
        );

        if (empty($words)) {
            return true; // nothing meaningful to check against
        }

        foreach ($words as $w) {
            if (str_contains($desc, $w)) {
                return true;
            }
        }

        return false;
    }
}
