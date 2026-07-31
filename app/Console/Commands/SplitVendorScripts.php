<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Splits DataTables out of the concatenated theme bundle.
 *
 * public/assets/js/scripts.js ships jQuery, Bootstrap, DataTables (+9 of its
 * extensions), imagesLoaded, Magnific Popup, Owl, AOS, Isotope, slick and the
 * theme's own init code as one 899 KB file loaded on every page. DataTables
 * alone is 328 KB of that, and the only thing on the entire site that touches
 * it is one call against #cart-table — a selector that matches nothing. It is
 * a leftover from the purchased template's demo markup: the real cart view
 * (resources/views/cart.blade.php) has no table with that id, so the call has
 * always been a no-op and the library has always been dead weight.
 *
 * The block is therefore lifted out to vendor-datatables.js, which no layout
 * loads. The file is kept rather than deleted so that reinstating DataTables is
 * a one-line <script> tag if a future admin grid ever wants it.
 *
 * This is generated rather than hand-edited so that re-running it after a theme
 * update reproduces the split exactly. The cut points are located by unique
 * string markers, never by byte offset, and the command refuses to write
 * anything if a marker is missing or ambiguous.
 */
class SplitVendorScripts extends Command
{
    protected $signature = 'assets:split-vendor {--check : Verify the outputs are current without rewriting them}';

    protected $description = 'Split DataTables out of scripts.js into a cart-only bundle';

    /** Start of the DataTables core UMD block (its first SpryMedia banner). */
    private const DT_START = "/*!\n   Copyright 2008-2021 SpryMedia Ltd.";

    /** First thing after the last DataTables extension. */
    private const DT_END = "/*!\n * imagesLoaded PACKAGED v5.0.0";

    /** The one site call into DataTables, in the theme's init object. */
    private const CART_INIT = "\$('#cart-table', ).DataTable(";

    public function handle(): int
    {
        $source = public_path('assets/js/scripts.js');

        if (! is_file($source)) {
            $this->error("Missing {$source}");

            return self::FAILURE;
        }

        $js = file_get_contents($source);

        foreach ([
            'DataTables block start' => self::DT_START,
            'DataTables block end' => self::DT_END,
            'cart DataTable init' => self::CART_INIT,
        ] as $label => $marker) {
            $hits = substr_count($js, $marker);

            if ($hits !== 1) {
                $this->error("Expected exactly 1 occurrence of the {$label} marker, found {$hits}.");
                $this->line('The theme bundle has changed shape — re-derive the markers before splitting.');

                return self::FAILURE;
            }
        }

        $start = strpos($js, self::DT_START);
        $end = strpos($js, self::DT_END);

        if ($start >= $end) {
            $this->error('DataTables end marker precedes its start marker.');

            return self::FAILURE;
        }

        $dataTables = substr($js, $start, $end - $start);
        $slim = substr($js, 0, $start).substr($js, $end);

        // With DataTables gone, $.fn.DataTable is undefined on every page except
        // the cart. An unguarded call would throw inside the theme's init chain
        // and take every init after it down with it, so gate the call rather
        // than remove it — the cart still loads DataTables and still sorts.
        $slim = str_replace(
            self::CART_INIT,
            '$.fn.DataTable && '.self::CART_INIT,
            $slim
        );

        $slimPath = public_path('assets/js/scripts.slim.js');
        $dtPath = public_path('assets/js/vendor-datatables.js');

        if ($this->option('check')) {
            $current = is_file($slimPath) && file_get_contents($slimPath) === $slim
                && is_file($dtPath) && file_get_contents($dtPath) === $dataTables;

            if ($current) {
                $this->info('Split outputs are up to date.');

                return self::SUCCESS;
            }

            $this->error('Split outputs are stale — run: php artisan assets:split-vendor');

            return self::FAILURE;
        }

        file_put_contents($slimPath, $slim);
        file_put_contents($dtPath, $dataTables);

        $this->info(sprintf(
            'scripts.js %s KB  ->  scripts.slim.js %s KB  +  vendor-datatables.js %s KB',
            number_format(strlen($js) / 1024),
            number_format(strlen($slim) / 1024),
            number_format(strlen($dataTables) / 1024)
        ));
        $this->line(sprintf(
            '  %s KB removed from every non-cart page.',
            number_format((strlen($js) - strlen($slim)) / 1024)
        ));

        return self::SUCCESS;
    }
}
