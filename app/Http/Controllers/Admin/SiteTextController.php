<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteText;
use App\Support\SiteCopy;
use Illuminate\Http\Request;

class SiteTextController extends Controller
{
    public function index(Request $request)
    {
        $tabs      = config('site.tabs');
        $overrides = SiteText::pluck('value', 'key');

        // One tab per page, each listing that page's fields (search-result text first).
        $byTab = collect(config('site.copy'))
            ->map(fn ($def, $key) => $def + ['key' => $key])
            ->groupBy(fn ($def) => SiteCopy::tabFor($def['key']));

        $panels = [];
        foreach ($tabs as $slug => [$label, $url]) {
            $fields = ($byTab[$slug] ?? collect())->sortBy(fn ($d) => str_starts_with($d['key'], 'meta.') ? 0 : 1)->values();
            $panels[$slug] = [
                'label'  => $label,
                'url'    => $url,
                'fields' => $fields,
                'edited' => $fields->filter(fn ($d) => filled($overrides[$d['key']] ?? null))->count(),
            ];
        }

        $active = array_key_exists($request->query('tab'), $tabs) ? $request->query('tab') : array_key_first($tabs);

        return view('admin.site-text.index', compact('panels', 'overrides', 'active'));
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

        // Come back to the tab that was being edited.
        $tab = array_key_exists((string) $request->input('tab'), config('site.tabs')) ? $request->input('tab') : null;

        return redirect()->route('admin.site-text.index', array_filter(['tab' => $tab]))->with('success', 'Site text saved.');
    }
}
