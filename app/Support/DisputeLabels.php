<?php

namespace App\Support;

use App\Services\DisputeService;

class DisputeLabels
{
    public static function reason(string $key): string
    {
        return DisputeService::REASONS[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }
}
