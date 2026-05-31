<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowedAuction extends Model
{
    protected $table = 'FollowedAuctions';

    public $timestamps = false;

    protected $fillable = [
        'userId',
        'auctionId',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class, 'auctionId');
    }
}
