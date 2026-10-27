<?php

namespace App\Services;

use App\Mail\SellerVerifyCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/** Six-digit email code: stored hashed, expires in 10 minutes, locked after 5 wrong guesses. */
class EmailCodeService
{
    public const TTL_MINUTES = 10;
    public const MAX_ATTEMPTS = 5;

    public function issue(User $user): void
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->forceFill([
            'email_code_hash' => Hash::make($code),
            'email_code_expires_at' => now()->addMinutes(self::TTL_MINUTES),
            'email_code_attempts' => 0,
        ])->save();

        Mail::to($user->email)->send(new SellerVerifyCodeMail($user, $code));
    }

    /** @return 'ok'|'wrong'|'expired'|'locked' */
    public function check(User $user, string $code): string
    {
        if (! $user->email_code_hash || ! $user->email_code_expires_at || $user->email_code_expires_at->isPast()) {
            return 'expired';
        }
        if ($user->email_code_attempts >= self::MAX_ATTEMPTS) {
            return 'locked';
        }
        if (! Hash::check($code, $user->email_code_hash)) {
            $user->forceFill(['email_code_attempts' => $user->email_code_attempts + 1])->save();

            return 'wrong';
        }

        $user->forceFill([
            'email_verified_at' => now(),
            'email_code_hash' => null,
            'email_code_expires_at' => null,
            'email_code_attempts' => 0,
        ])->save();

        return 'ok';
    }
}
