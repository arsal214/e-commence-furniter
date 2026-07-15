<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductImportController extends Controller
{
    public function index()
    {
        return view('admin.products.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $handle = fopen($request->file('csv_file')->getRealPath(), 'r');

        $rawHeaders = fgetcsv($handle);
        if (!$rawHeaders) {
            fclose($handle);
            return back()->withErrors(['csv_file' => 'The CSV file appears to be empty.']);
        }

        // Strip UTF-8 BOM from first header cell if present (Excel sometimes adds it)
        $rawHeaders[0] = ltrim($rawHeaders[0], "\xEF\xBB\xBF");

        // Convert header row encoding (Excel on Windows saves as Windows-1252)
        $rawHeaders = $this->toUtf8($rawHeaders);

        // Normalize headers for flexible matching
        $map = [];
        foreach ($rawHeaders as $i => $h) {
            $map[strtolower(trim(preg_replace('/\s+/', ' ', $h)))] = $i;
        }

        $colOriginal    = $this->col($map, ['original supplier title', 'original title', 'original name', 'supplier title']);
        $colTitle       = $this->col($map, ['optimized product title', 'product title', 'title', 'name']);
        $colMetaTitle   = $this->col($map, ['meta title', 'metatitle', 'seo title']);
        $colMetaDesc    = $this->col($map, ['meta description', 'metadescription', 'meta desc']);
        $colDescription = $this->col($map, ['product description', 'description', 'desc']);
        $colSlug        = $this->col($map, ['seo slug', 'slug', 'url slug', 'url']);

        if ($colOriginal === null) {
            fclose($handle);
            return back()->withErrors([
                'csv_file' => 'Could not find "Original Supplier Title" column. Found columns: ' . implode(', ', $rawHeaders),
            ]);
        }

        $updated      = [];
        $notFound     = [];
        $skipped      = [];
        $warnings     = [];
        $rowNum       = 1;
        $expectedCols = count($rawHeaders);

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            $row = $this->toUtf8($row);

            // Skip completely blank lines quietly (trailing newline, spacer rows).
            if (count(array_filter($row, fn($c) => trim((string) $c) !== '')) === 0) {
                continue;
            }

            // Guard: a row whose column count doesn't match the header is almost
            // always a shifted row (an unescaped comma or line break in the source).
            // Importing it would drop the wrong text into the wrong field — the exact
            // "description belongs to another product" bug. Skip it instead.
            if (count($row) !== $expectedCols) {
                $skipped[] = "Row {$rowNum}: expected {$expectedCols} columns but found " . count($row)
                    . " — row skipped (likely an unescaped comma or line break in the source CSV).";
                continue;
            }

            $originalTitle = trim($row[$colOriginal] ?? '');
            if ($originalTitle === '') continue;

            $product = Product::where('name', $originalTitle)->first();

            if (!$product) {
                $notFound[] = $originalTitle;
                continue;
            }

            $data = [];

            if ($colTitle !== null) {
                $v = trim($row[$colTitle] ?? '');
                if ($v !== '') $data['name'] = $v;
            }

            if ($colMetaTitle !== null) {
                $data['meta_title'] = trim($row[$colMetaTitle] ?? '') ?: null;
            }

            if ($colMetaDesc !== null) {
                $data['meta_description'] = trim($row[$colMetaDesc] ?? '') ?: null;
            }

            if ($colDescription !== null) {
                $v = trim($row[$colDescription] ?? '');
                if ($v !== '') {
                    $data['description'] = $v;
                    // Soft check: does the incoming description mention the product at
                    // all? Share no significant word with the title → flag for review
                    // (still applied, since the column count already passed the guard).
                    $titleForCheck = $data['name'] ?? $product->name;
                    if (! $this->descriptionMatchesTitle($v, $titleForCheck)) {
                        $warnings[] = "Row {$rowNum} \"{$titleForCheck}\": the new description doesn't mention the product — please double-check it's not another item's text.";
                    }
                }
            }

            if ($colSlug !== null) {
                $rawSlug = trim($row[$colSlug] ?? '');
                if ($rawSlug !== '') {
                    $slug = Str::slug($rawSlug);
                    $conflict = Product::where('slug', $slug)->where('id', '!=', $product->id)->exists();
                    if ($conflict) {
                        $skipped[] = "Row {$rowNum} \"{$originalTitle}\": slug \"{$slug}\" already used by another product - slug not changed.";
                    } else {
                        $data['slug'] = $slug;
                    }
                }
            }

            $product->update($data);
            $updated[] = $product->fresh()->name;
        }

        fclose($handle);

        return back()->with([
            'import_updated'   => $updated,
            'import_not_found' => $notFound,
            'import_skipped'   => $skipped,
            'import_warnings'  => $warnings,
        ]);
    }

    /** True if the description shares at least one significant word with the title. */
    private function descriptionMatchesTitle(string $description, string $title): bool
    {
        $desc  = strtolower(strip_tags($description));
        $words = array_filter(
            preg_split('/\W+/', strtolower($title)),
            fn ($w) => strlen($w) > 3
        );

        if (empty($words)) {
            return true;
        }

        foreach ($words as $w) {
            if (str_contains($desc, $w)) {
                return true;
            }
        }

        return false;
    }

    private function col(array $map, array $candidates): ?int
    {
        foreach ($candidates as $key) {
            if (isset($map[$key])) return $map[$key];
        }
        return null;
    }

    private function toUtf8(array $row): array
    {
        return array_map(function ($value) {
            if ($value === null) return null;
            // Already valid UTF-8 — return as-is
            if (mb_check_encoding($value, 'UTF-8')) return $value;
            // Convert from Windows-1252 (common Excel encoding on Windows)
            return mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
        }, $row);
    }
}
