<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuctionImage extends Pivot
{
    protected $table = 'AuctionsImages';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'imageId',
        'auctionId',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class, 'auctionId');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'imageId');
    }
}
