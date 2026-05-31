<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Auction extends Model
{
    protected $table = 'Auctions';

    protected $fillable = [
        'name',
        'description',
        'price',
        'negotiable',
        'location',
        'status',
        'userId',
        'categoryId',
        'imageId',
    ];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $casts = [
        'price' => 'decimal:2',
        'negotiable' => 'boolean',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categoryId');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'imageId');
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'FollowedAuctions', 'auctionId', 'userId');
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'auctionId');
    }

    public function additionalImages(): BelongsToMany
    {
        return $this->belongsToMany(
            Image::class,
            'AuctionsImages',
            'auctionId',
            'imageId'
        );
    }
}
