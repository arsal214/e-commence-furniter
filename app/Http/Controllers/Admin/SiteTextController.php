<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteText;
use App\Support\SiteCopy;
use Illuminate\Http\Request;

class SiteTextController extends Controller
{
    public function index()
    {
        $fields    = collect(config('site.copy'))->groupBy('group');
        $overrides = SiteText::pluck('value', 'key');

        return view('admin.site-text.index', compact('fields', 'overrides'));
    }

    public function update(Request $request)
    {
        $known = config('site.copy');
        $input = $request->validate(['texts' => 'array', 'texts.*' => 'nullable|string|max:2000'])['texts'] ?? [];

        foreach ($input as $key => $value) {
            if (! isset($known[$key])) {
                continue; // only keys that exist in config/site.php can be stored
            }

            $value = trim((string) $value);
            // Blank, or identical to the default, means "use the default": no row kept.
            if ($value === '' || $value === $known[$key]['default']) {
                SiteText::where('key', $key)->delete();
            } else {
                SiteText::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }

        SiteCopy::forget();

        return back()->with('success', 'Site text saved.');
    }
}
