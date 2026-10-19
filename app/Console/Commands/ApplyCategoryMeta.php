<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;

/**
 * Writes the default category meta titles/descriptions (config/site.php) into the
 * categories table. Existing categories may still carry old "toy store" wording, and
 * a category's own meta wins over the fallback, so this replaces it.
 *
 *   php artisan seo:apply-category-meta          (preview only)
 *   php artisan seo:apply-category-meta --force  (write)
 *
 * The text keeps its {shipping} token, so the free-shipping wording stays automatic.
 */
class ApplyCategoryMeta extends Command
{
    protected $signature = 'seo:apply-category-meta {--force : Write the changes (default is a preview)}';

    protected $description = 'Set category meta titles/descriptions to the defaults in config/site.php';

    public function handle(): int
    {
        $changed = 0;

        foreach (Category::orderBy('slug')->get() as $category) {
            $copy  = config('site.copy');   // keys contain dots, so index the array directly
            $title = $copy["meta.category.{$category->slug}.title"]['default'] ?? null;
            $desc  = $copy["meta.category.{$category->slug}.description"]['default'] ?? null;
            if (! $title || ! $desc) {
                $this->line("skip  {$category->slug} (no default text)");
                continue;
            }
            if ($category->meta_title === $title && $category->meta_description === $desc) {
                continue;
            }

            $this->line(($this->option('force') ? 'set   ' : 'would ') . $category->slug);
            $this->line("        title: {$category->meta_title}  ->  {$title}");
            if ($this->option('force')) {
                $category->update(['meta_title' => $title, 'meta_description' => $desc]);
            }
            $changed++;
        }

        $this->info($this->option('force') ? "Updated {$changed} categories." : "{$changed} categories would change. Re-run with --force to write.");

        return self::SUCCESS;
    }
}
