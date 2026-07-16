<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'product_reviews';

    protected $fillable = ['product_id', 'user_id', 'reviewer_name', 'rating', 'comment'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Name shown for a review: the linked customer's name, else the admin-supplied
     * reviewer_name, else a neutral fallback. Single source of truth for review authorship.
     */
    public function getAuthorNameAttribute(): string
    {
        return $this->user?->name ?: ($this->reviewer_name ?: 'Customer');
    }
}
