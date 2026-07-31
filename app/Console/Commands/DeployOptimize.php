<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Post-deploy warm-up for the zip-upload workflow.
 *
 * Measured on this app: rendering the homepage with a cold view cache takes
 * ~900 ms, versus ~23 ms once the Blade templates are compiled. That difference
 * lands directly in TTFB, and TTFB lands directly in FCP and LCP — so on a
 * shared VPS the first visitor to hit each of the 134 templates after a deploy
 * pays close to a second of avoidable server time.
 *
 * config:cache and route:cache remove the per-request cost of re-parsing config
 * files and rebuilding the route table on top of that.
 *
 * Asset steps are included because both produce files that are referenced but
 * never generated at runtime: without images:webp the .htaccess WebP rewrite
 * silently falls through to the full-size JPEGs, and without assets:split-vendor
 * there is no scripts.slim.js for the layouts to load at all.
 */
class DeployOptimize extends Command
{
    protected $signature = 'deploy:optimize {--skip-images : Skip WebP generation (slow; only needed when images changed)}';

    protected $description = 'Warm caches and build derived assets after a deploy';

    public function handle(): int
    {
        $steps = [
            // Clear first: a cache built from the previous release's files is
            // worse than no cache at all.
            ['config:clear', [], 'Clearing stale config cache'],
            ['route:clear', [], 'Clearing stale route cache'],
            ['view:clear', [], 'Clearing stale compiled views'],
            ['event:clear', [], 'Clearing stale event cache'],

            ['config:cache', [], 'Caching config'],
            ['route:cache', [], 'Caching routes'],
            ['view:cache', [], 'Precompiling all Blade templates'],
            ['event:cache', [], 'Caching event/listener discovery'],

            // Categories are cached for 6h and busted on admin writes; a deploy
            // may ship different rendering, so start from a clean slate.
            ['cache:clear', [], 'Flushing application cache'],

            ['assets:split-vendor', [], 'Splitting DataTables out of the theme bundle'],
        ];

        if (! $this->option('skip-images')) {
            $steps[] = ['images:webp', [], 'Generating WebP twins'];
        }

        foreach ($steps as [$command, $args, $label]) {
            $this->line("→ {$label}");

            if ($this->call($command, $args) !== self::SUCCESS) {
                $this->error("Failed at: {$command}");

                return self::FAILURE;
            }
        }

        $this->newLine();
        $this->info('Deploy optimisation complete.');

        // Not run automatically: composer is frequently unavailable or an old
        // version on shared hosting, and a failed autoload dump mid-deploy is
        // worse than not having the optimised classmap.
        $this->line('Also run once per deploy, if composer is available on the server:');
        $this->line('  composer install --no-dev --optimize-autoloader --classmap-authoritative');

        // storage:link is deliberately not run here: it fails on hosts that
        // disallow symlinks, and it only needs doing once per server, not per
        // deploy.
        $this->line('Note: run `php artisan storage:link` once per server if product images 404.');

        return self::SUCCESS;
    }
}
