<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Needs one server cron line: * * * * * php artisan schedule:run
Schedule::call(fn () => app(\App\Services\ShipmentService::class)->autoDeliverDue())->hourly()->name('shipments-auto-deliver')->withoutOverlapping();
Schedule::call(fn () => app(\App\Services\PayoutService::class)->releaseDue())->dailyAt('06:00')->name('payouts-release')->withoutOverlapping();
Schedule::call(fn () => app(\App\Services\AdService::class)->billAll())->dailyAt('03:00')->name('ads-bill')->withoutOverlapping();
Schedule::call(fn () => app(\App\Services\RewardsService::class)->expireInactive())->dailyAt('04:00')->name('rewards-expire')->withoutOverlapping();
