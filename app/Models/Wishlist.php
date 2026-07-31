<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Wishlist extends Model
{
    protected $fillable = ['user_id', 'product_id'];

    /**
     * Product ids the signed-in user has wishlisted, memoised for the request.
     *
     * The layout renders these into a <meta> tag and the navbar renders their
     * count, which were two separate queries on every page. Not cached across
     * requests: this is per-user data that changes the instant someone clicks
     * the heart icon.
     */
    public static function productIdsForCurrentUser(): array
    {
        static $memo = null;

        if ($memo !== null) {
            return $memo;
        }

        if (! Auth::check()) {
            return $memo = [];
        }

        return $memo = static::where('user_id', Auth::id())
            ->pluck('product_id')
            ->all();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
