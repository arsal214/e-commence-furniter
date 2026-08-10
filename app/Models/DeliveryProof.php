<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * A courier's proof of delivery for an order — internal record only.
 *
 * Files live on the private disk and are streamed back through an admin-only
 * route, so nothing here is ever linkable to a customer.
 */
class DeliveryProof extends Model
{
    /** The disk these files are written to. Private by design — see the migration. */
    public const DISK = 'local';

    protected $fillable = [
        'order_id', 'path', 'original_name', 'mime', 'size', 'note', 'uploaded_by',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function isImage(): bool
    {
        return str_starts_with((string) $this->mime, 'image/');
    }

    public function isPdf(): bool
    {
        return $this->mime === 'application/pdf';
    }

    /** What to call the file in the UI when the browser sent no filename. */
    public function getDisplayNameAttribute(): string
    {
        return $this->original_name ?: basename($this->path);
    }

    public function getHumanSizeAttribute(): string
    {
        $bytes = (int) $this->size;

        if ($bytes <= 0) {
            return '—';
        }

        foreach (['B', 'KB', 'MB'] as $i => $unit) {
            if ($bytes < 1024 || $unit === 'MB') {
                return round($bytes, $i === 0 ? 0 : 1) . ' ' . $unit;
            }
            $bytes /= 1024;
        }

        return $bytes . ' B';
    }

    /** Remove the underlying file. Safe to call when it has already gone. */
    public function deleteFile(): void
    {
        if ($this->path) {
            Storage::disk(self::DISK)->delete($this->path);
        }
    }
}
