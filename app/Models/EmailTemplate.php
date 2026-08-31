<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * A saved starting point for a promotional email.
 *
 * @see \App\Http\Controllers\Admin\CampaignController
 */
class EmailTemplate extends Model
{
    protected $fillable = [
        'name', 'subject', 'eyebrow', 'heading', 'body_html',
        'cta_label', 'cta_url', 'promo_code', 'promo_note',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * The shape the compose screen's JavaScript loads into the form when a
     * template is picked. Kept here so the view has no field knowledge.
     *
     * @return array<string, string>
     */
    public function toFormPayload(): array
    {
        return [
            'subject'    => (string) $this->subject,
            'eyebrow'    => (string) $this->eyebrow,
            'heading'    => (string) $this->heading,
            'body_html'  => (string) $this->body_html,
            'cta_label'  => (string) $this->cta_label,
            'cta_url'    => (string) $this->cta_url,
            'promo_code' => (string) $this->promo_code,
            'promo_note' => (string) $this->promo_note,
        ];
    }
}
