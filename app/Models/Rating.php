<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    protected $table = 'Ratings';

    public $timestamps = false;

    protected $fillable = [
        'sellerId',
        'rating',
        'userId',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sellerId');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public static function recommendationPercent(Collection $ratings): ?int
    {
        $valid = $ratings->whereNotNull('rating');

        if ($valid->isEmpty()) {
            return null;
        }

        return (int) round($valid->where('rating', 1)->count() / $valid->count() * 100);
    }
}
