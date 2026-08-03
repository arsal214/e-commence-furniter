<?php

namespace App\Support;

use Carbon\CarbonImmutable;

/**
 * The delivery window shown to customers.
 *
 * Day counts come from config('checkout.delivery'), which the shipping policy,
 * the FAQ and the category FAQ all render too — so a product page saying
 * "arrives Thu 14th" and a policy page quoting a day range are the same promise
 * by construction, rather than by four people remembering to edit four files.
 *
 * Business days only: Saturday and Sunday are skipped, matching how every page
 * words it. Public holidays are NOT skipped — there is no holiday calendar in
 * the app, and a hardcoded one would be wrong for half the destinations the
 * checkout now accepts. That leaves the estimate slightly optimistic around a
 * public holiday, which is why both ends are presented as an estimate and never
 * as a guaranteed date.
 */
class DeliveryEstimate
{
    public static function minDays(): int
    {
        return max(0, (int) config('checkout.delivery.min_days', 3));
    }

    /**
     * Never below the minimum: a config with max < min would otherwise render a
     * backwards range ("arrives Fri – Tue").
     */
    public static function maxDays(): int
    {
        return max(self::minDays(), (int) config('checkout.delivery.max_days', 7));
    }

    /**
     * @return array{
     *     earliest: CarbonImmutable,
     *     latest: CarbonImmutable,
     *     min_days: int,
     *     max_days: int
     * }
     */
    public static function window(?CarbonImmutable $from = null): array
    {
        $from ??= CarbonImmutable::now();

        $min = self::minDays();
        $max = self::maxDays();

        return [
            'min_days' => $min,
            'max_days' => $max,
            'earliest' => self::addBusinessDays($from, $min),
            'latest'   => self::addBusinessDays($from, $max),
        ];
    }

    /**
     * Advance past $days weekdays. Counts only days that land Mon–Fri, so an
     * order placed on a Friday does not quietly "use up" the weekend.
     */
    public static function addBusinessDays(CarbonImmutable $date, int $days): CarbonImmutable
    {
        while ($days > 0) {
            $date = $date->addDay();

            if (! $date->isWeekend()) {
                $days--;
            }
        }

        return $date;
    }
}
