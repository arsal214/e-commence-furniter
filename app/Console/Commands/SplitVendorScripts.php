<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Rebuilds public/assets/js/scripts.js without the vendor libraries nothing on
 * this site uses.
 *
 * The purchased theme ships one hand-concatenated 899 KB bundle on every page.
 * Auditing every library's init selector against all 134 blade templates showed
 * that most of it is never reachable:
 *
 *   Bootstrap 4 + Popper   ~50 KB   zero data-toggle hooks, zero component calls
 *   DataTables + 9 exts    ~329 KB  only call is #cart-table, which does not exist
 *   imagesLoaded + Isotope  ~40 KB  .portfolio1/2-isotope, .shop-isotope, .bestSeller-isotope: none exist
 *   Magnific Popup          ~20 KB  .popup-image / .popup-video: neither exists
 *   jQuery UI 1.11.2       ~233 KB  only call is #slider-container, which does not exist
 *
 * They are removed as two contiguous banner-delimited ranges. What survives is
 * jQuery, Nice Select (9 templates have <select>), Owl Carousel (6 templates),
 * AOS, slick and the theme's own init code.
 *
 * The theme's init object still calls the removed plugins unconditionally, so
 * no-op shims are appended rather than patching each call site — one small
 * addition is far less fragile than string-matching a dozen minified callers,
 * and it keeps working if the theme adds another dead call later.
 *
 * Generated, never hand-edited: re-running after a theme update reproduces the
 * result exactly. Cut points are located by unique string markers and the
 * command aborts if any marker is missing or ambiguous.
 */
class SplitVendorScripts extends Command
{
    protected $signature = 'assets:split-vendor {--check : Verify the output is current without rewriting it}';

    protected $description = 'Rebuild scripts.slim.js without unused vendor libraries';

    /**
     * Contiguous [start, end) ranges to drop. Each end marker is the start of
     * the next library that is kept, so every cut lands on a banner boundary
     * immediately after a complete statement.
     */
    private const DEAD_RANGES = [
        // Bootstrap -> DataTables -> imagesLoaded/Isotope -> Magnific Popup.
        // Ends at Nice Select, which is kept.
        'Bootstrap, DataTables, Isotope, Magnific' => [
            "/*!\n  * Bootstrap v4.1.1",
            '/*  jQuery Nice Select - v1.0',
        ],
        // jQuery UI, ending at Owl Carousel, which is kept.
        'jQuery UI' => [
            '/*! jQuery UI - v1.11.2',
            "/**\n * Owl Carousel v2.3.3",
        ],
    ];

    /**
     * jQuery plugins the theme calls but that no longer exist.
     *
     * imagesLoaded is special-cased: the theme chains .progress() off its
     * return value, so a plain `return this` shim would throw where the others
     * would not.
     */
    private const SHIMMED_PLUGINS = [
        'DataTable', 'dataTable', 'isotope', 'magnificPopup', 'slider',
        'modal', 'tooltip', 'popover', 'collapse', 'dropdown', 'carousel', 'tab',
    ];

    public function handle(): int
    {
        $source = public_path('assets/js/scripts.js');

        if (! is_file($source)) {
            $this->error("Missing {$source}");

            return self::FAILURE;
        }

        $js = file_get_contents($source);
        $original = strlen($js);

        // Resolve every range against the untouched source first, so removing
        // one range cannot shift the offsets used to find another.
        $cuts = [];

        foreach (self::DEAD_RANGES as $label => [$startMarker, $endMarker]) {
            foreach ([[$startMarker, 'start'], [$endMarker, 'end']] as [$marker, $which]) {
                $hits = substr_count($js, $marker);

                if ($hits !== 1) {
                    $this->error("{$label}: expected 1 occurrence of the {$which} marker, found {$hits}.");
                    $this->line('The theme bundle has changed shape — re-derive the markers before stripping.');

                    return self::FAILURE;
                }
            }

            $start = strpos($js, $startMarker);
            $end = strpos($js, $endMarker);

            if ($start >= $end) {
                $this->error("{$label}: end marker precedes start marker.");

                return self::FAILURE;
            }

            $cuts[] = ['label' => $label, 'start' => $start, 'end' => $end];
        }

        // Apply back to front so earlier offsets stay valid.
        usort($cuts, fn ($a, $b) => $b['start'] <=> $a['start']);

        $slim = $js;
        $report = [];

        foreach ($cuts as $cut) {
            $report[] = sprintf('  -%s KB  %s', number_format(($cut['end'] - $cut['start']) / 1024), $cut['label']);
            $slim = substr($slim, 0, $cut['start']).substr($slim, $cut['end']);
        }

        // The shims must be defined before any theme code runs, not appended at
        // EOF: the theme's init chain executes partway through this bundle, so a
        // shim at the end is only reached if the chain did not throw — exactly
        // the case it exists to prevent. The earliest cut sits immediately after
        // jQuery core, which is the first point where $.fn is available.
        $insertAt = end($cuts)['start'];
        $slim = substr($slim, 0, $insertAt).$this->shims().substr($slim, $insertAt);

        $slimPath = public_path('assets/js/scripts.slim.js');

        if ($this->option('check')) {
            if (is_file($slimPath) && file_get_contents($slimPath) === $slim) {
                $this->info('scripts.slim.js is up to date.');

                return self::SUCCESS;
            }

            $this->error('scripts.slim.js is stale — run: php artisan assets:split-vendor');

            return self::FAILURE;
        }

        file_put_contents($slimPath, $slim);

        // The DataTables-only artefact from the previous iteration of this
        // command is now covered by the ranges above.
        $stale = public_path('assets/js/vendor-datatables.js');

        if (is_file($stale)) {
            unlink($stale);
        }

        foreach (array_reverse($report) as $line) {
            $this->line($line);
        }

        $this->newLine();
        $this->info(sprintf(
            'scripts.js %s KB  ->  scripts.slim.js %s KB  (%s%% smaller)',
            number_format($original / 1024),
            number_format(strlen($slim) / 1024),
            number_format((1 - strlen($slim) / $original) * 100, 1)
        ));

        return self::SUCCESS;
    }

    private function shims(): string
    {
        $plugins = json_encode(self::SHIMMED_PLUGINS);

        return <<<JS


/* ── Removed-plugin shims (generated by `php artisan assets:split-vendor`) ──
   Bootstrap, DataTables, Isotope, Magnific Popup and jQuery UI were stripped
   from this bundle because nothing on the site uses them. The theme's init
   object still calls them unconditionally, and an undefined jQuery plugin
   throws — which would abort the whole init chain and take the carousels and
   AOS down with it. These no-ops keep every call harmless and chainable. */
(function (\$) {
    if (!\$ || !\$.fn) return;

    {$plugins}.forEach(function (name) {
        if (!\$.fn[name]) {
            \$.fn[name] = function () { return this; };
        }
    });

    // The theme chains .progress() off imagesLoaded(), so this one has to
    // return a thenable-shaped object rather than the jQuery set.
    if (!\$.fn.imagesLoaded) {
        \$.fn.imagesLoaded = function () {
            var chain = {
                progress: function () { return chain; },
                always: function (fn) { if (typeof fn === 'function') { fn(); } return chain; },
                done: function (fn) { if (typeof fn === 'function') { fn(); } return chain; },
                fail: function () { return chain; }
            };
            return chain;
        };
    }
})(window.jQuery);

JS;
    }
}
