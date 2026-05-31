<?php

namespace App\Models;

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
}
