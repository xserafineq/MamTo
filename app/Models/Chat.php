<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    protected $table = 'Chats';

    public $timestamps = false;

    protected $fillable = [
        'auctionId',
        'sellerId',
        'buyerId',
        'archived',
    ];

    protected $casts = [
        'archived' => 'boolean',
    ];

    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class, 'auctionId');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sellerId');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyerId');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'chatId');
    }

    public function scopeVisible($query)
    {
        return $query->where('archived', false);
    }
}
