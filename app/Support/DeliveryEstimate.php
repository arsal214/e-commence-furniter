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
 * Business days only: weekends and US federal holidays are skipped (the store
 * ships to the US). Processing days (config checkout.delivery.processing_days)
 * come first, then the min/max transit days. Still an estimate, never a
 * guaranteed date.
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
        $from   = self::addBusinessDays($from, max(0, (int) config('checkout.delivery.processing_days', 1)));

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

            if (! $date->isWeekend() && ! self::isFederalHoliday($date)) {
                $days--;
            }
        }

        return $date;
    }

    /** US federal holidays, with the Saturday→Friday / Sunday→Monday observed rule. */
    public static function isFederalHoliday(CarbonImmutable $date): bool
    {
        $y = $date->year;
        $fixed = [[1, 1], [6, 19], [7, 4], [11, 11], [12, 25]];
        $days = [];

        foreach ($fixed as [$m, $d]) {
            $actual = CarbonImmutable::create($y, $m, $d);
            $days[] = $actual->isSaturday() ? $actual->subDay() : ($actual->isSunday() ? $actual->addDay() : $actual);
        }

        // Jan 1 falling on a Saturday is observed Dec 31 of the previous year.
        $next = CarbonImmutable::create($y + 1, 1, 1);
        if ($next->isSaturday()) {
            $days[] = $next->subDay();
        }

        $nth = fn (int $month, int $dow, int $n) => CarbonImmutable::create($y, $month, 1)->nthOfMonth($n, $dow);
        $days[] = $nth(1, 1, 3);   // Martin Luther King Jr. Day
        $days[] = $nth(2, 1, 3);   // Washington's Birthday
        $days[] = CarbonImmutable::create($y, 5, 1)->lastOfMonth(1); // Memorial Day
        $days[] = $nth(9, 1, 1);   // Labor Day
        $days[] = $nth(10, 1, 2);  // Columbus / Indigenous Peoples' Day
        $days[] = $nth(11, 4, 4);  // Thanksgiving

        foreach ($days as $holiday) {
            if ($holiday->isSameDay($date)) {
                return true;
            }
        }

        return false;
    }
}
