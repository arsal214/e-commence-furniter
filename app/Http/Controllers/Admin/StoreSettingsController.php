<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\StoreSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StoreSettingsController extends Controller
{
    public function index()
    {
        return view('admin.store-settings.index', [
            'groups'   => collect(StoreSettings::definitions())->groupBy(fn ($def) => $def[0], preserveKeys: true),
            'saved'    => StoreSettings::saved(),
            'defaults' => StoreSettings::defaults(),
        ]);
    }

    public function update(Request $request)
    {
        $defs  = StoreSettings::definitions();
        $rules = [];
        foreach ($defs as $key => $def) {
            $rules["settings.$key"] = match ($def[3]) {
                'bool'  => ['nullable', 'in:on,off'],
                'int'   => ['nullable', 'integer', 'min:' . $def[5], 'max:' . $def[6]],
                default => ['nullable', 'numeric', 'min:' . $def[5], 'max:' . $def[6]],
            };
        }
        $input = $request->validate($rules, [], collect($defs)->mapWithKeys(fn ($d, $k) => ["settings.$k" => $d[1]])->all())['settings'] ?? [];

        // The window must make sense using whatever will actually be live after saving.
        $live = fn (string $key) => ($input[$key] ?? '') !== '' ? (int) $input[$key] : (int) StoreSettings::defaults()[$key];
        if ($live('delivery_max_days') < $live('delivery_min_days')) {
            return back()->withErrors(['settings.delivery_max_days' => 'Slowest delivery cannot be lower than fastest delivery.'])->withInput();
        }

        $before = StoreSettings::saved();
        foreach ($defs as $key => $_) {
            StoreSettings::put($key, $input[$key] ?? null);
        }

        $changed = array_keys(array_filter($defs, fn ($_, $k) => ($before[$k] ?? '') !== (string) ($input[$k] ?? ''), ARRAY_FILTER_USE_BOTH));
        if ($changed) {
            Log::info('Store settings changed by admin #' . $request->user()?->id . ': ' . implode(', ', $changed));
        }

        return back()->with('success', 'Store settings saved. They are live now.');
    }
}
