<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Best-effort US ZIP for a visitor's IP address, used only to pre-fill the delivery ZIP.
 * One short call to ipapi.co (free tier, 1,000 lookups a day per server; set GEO_IP_KEY for more),
 * cached per IP so a visitor costs at most one lookup a week. Any failure returns null.
 */
class GeoIp
{
    public function zip(?string $ip): ?string
    {
        // Private and reserved addresses (localhost, LAN) have no location.
        if (! $ip || ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return null;
        }

        return Cache::remember('geoip:' . $ip, now()->addDays(7), fn () => ['zip' => $this->lookup($ip)])['zip'];
    }

    private function lookup(string $ip): ?string
    {
        try {
            $r = Http::timeout(2)->acceptJson()->get("https://ipapi.co/{$ip}/json/", array_filter(['key' => config('marketplace.geo_ip_key')]));
            $d = $r->successful() ? $r->json() : [];
        } catch (\Throwable) {
            return null;
        }

        // US only, and only a clean 5-digit ZIP; rate-limit and error replies carry "error".
        if (! empty($d['error']) || ($d['country_code'] ?? '') !== 'US') {
            return null;
        }

        return preg_match('/^\d{5}$/', (string) ($d['postal'] ?? '')) ? $d['postal'] : null;
    }
}
