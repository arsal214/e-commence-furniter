<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    public const STATUS_SENT   = 'sent';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'to_email', 'to_name', 'cc', 'bcc', 'subject', 'mailable',
        'user_id', 'order_id', 'status', 'error', 'message_id', 'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function failed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * The mailable's short class name — 'OrderConfirmationMail' rather than the
     * full namespace — for display in the admin table.
     */
    public function getMailableShortAttribute(): string
    {
        return $this->mailable ? class_basename($this->mailable) : 'Other';
    }

    /**
     * A readable label for what the email was, derived from the mailable name:
     * 'OrderConfirmationMail' becomes 'Order Confirmation'.
     */
    public function getTypeLabelAttribute(): string
    {
        if (! $this->mailable) {
            return 'Other';
        }

        $name = preg_replace('/Mail$/', '', class_basename($this->mailable));

        return trim(preg_replace('/(?<!^)[A-Z]/', ' $0', $name)) ?: 'Other';
    }

    /**
     * Record a send that threw before it reached the transport.
     *
     * The MessageSent event only fires on success, so a failure never reaches
     * the listener and has to be recorded from the call site's catch block.
     */
    public static function recordFailure(
        string $toEmail,
        string $mailable,
        string $error,
        ?int $userId = null,
        ?int $orderId = null,
    ): void {
        try {
            static::create([
                'to_email' => $toEmail,
                'mailable' => $mailable,
                'status'   => self::STATUS_FAILED,
                'error'    => mb_substr($error, 0, 2000),
                'user_id'  => $userId,
                'order_id' => $orderId,
                'sent_at'  => now(),
            ]);
        } catch (\Throwable $e) {
            // Logging must never be the reason a request fails.
            \Log::warning('Failed to write email failure log: ' . $e->getMessage());
        }
    }
}
