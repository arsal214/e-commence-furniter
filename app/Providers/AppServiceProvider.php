<?php

namespace App\Providers;

use App\Listeners\LogSentEmail;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerVersionedAssetDirective();

        // Registered explicitly rather than relying on listener auto-discovery,
        // which is not enabled in this application.
        Event::listen(MessageSent::class, LogSentEmail::class);
    }

    /**
     * asset() with a cache-busting fingerprint.
     *
     * The two heaviest files on the site — assets/css/style.css and
     * assets/js/scripts.js — are vendored template files, not Vite output, so
     * nothing hashes their names. That left only two options: cache them for a
     * short time and pay the re-download on every visit, or cache them for a
     * year and have visitors stuck on a stale copy after a deploy. Appending
     * the mtime gives a new URL whenever the file actually changes, which makes
     * the year-long immutable Cache-Control in public/.htaccess safe.
     */
    private function registerVersionedAssetDirective(): void
    {
        Blade::directive('versionedAsset', function (string $expression) {
            return "<?php echo \\App\\Providers\\AppServiceProvider::versionedAsset({$expression}); ?>";
        });
    }

    public static function versionedAsset(string $path): string
    {
        $url = asset($path);

        // filemtime() is a stat call on every render, so memoise per request.
        // A miss (file moved or not yet deployed) must not fatal the page — the
        // unversioned URL still works, it just revalidates more often.
        static $stamps = [];

        if (! array_key_exists($path, $stamps)) {
            $file = public_path($path);
            $stamps[$path] = is_file($file) ? filemtime($file) : null;
        }

        return $stamps[$path] ? "{$url}?v={$stamps[$path]}" : $url;
    }
}
