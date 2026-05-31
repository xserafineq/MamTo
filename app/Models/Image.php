<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Image extends Model
{
    protected $table = 'Images';

    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'filename',
        'uploadedAt',
    ];

    protected $casts = [
        'uuid' => 'integer',
        'uploadedAt' => 'datetime',
    ];

    public function auctions(): HasMany
    {
        return $this->hasMany(Auction::class, 'imageId');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'imageId');
    }

    public function additionalAuctions(): BelongsToMany
    {
        return $this->belongsToMany(
            Auction::class,
            'AuctionsImages',
            'imageId',
            'auctionId'
        );
    }
}
