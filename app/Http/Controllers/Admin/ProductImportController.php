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

        $updated  = [];
        $notFound = [];
        $skipped  = [];
        $rowNum   = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            $row = $this->toUtf8($row);
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
                if ($v !== '') $data['description'] = $v;
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
        ]);
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
