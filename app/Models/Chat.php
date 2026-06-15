<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Chat extends Model
{
    protected $table = 'Chats';

    public $timestamps = false;

    protected $fillable = [
        'auctionId',
        'sellerId',
        'buyerId',
        'archived',
        'buyerLastReadAt',
        'sellerLastReadAt',
    ];

    protected $casts = [
        'archived' => 'boolean',
        'buyerLastReadAt' => 'datetime',
        'sellerLastReadAt' => 'datetime',
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

    public function lastReadAtFor(int $userId): ?Carbon
    {
        if ((int) $this->buyerId === $userId) {
            return $this->buyerLastReadAt;
        }

        if ((int) $this->sellerId === $userId) {
            return $this->sellerLastReadAt;
        }

        return null;
    }

    public function markAsReadBy(int $userId): void
    {
        $now = now();

        if ((int) $this->buyerId === $userId) {
            $this->update(['buyerLastReadAt' => $now]);
            $this->buyerLastReadAt = $now;

            return;
        }

        if ((int) $this->sellerId === $userId) {
            $this->update(['sellerLastReadAt' => $now]);
            $this->sellerLastReadAt = $now;
        }
    }

    public function unreadMessagesCountFor(int $userId): int
    {
        $lastReadAt = $this->lastReadAtFor($userId);

        if ($this->relationLoaded('messages')) {
            return $this->messages
                ->filter(fn (Message $message) => (int) $message->senderId !== $userId)
                ->filter(fn (Message $message) => ! $lastReadAt || $message->sentAt->gt($lastReadAt))
                ->count();
        }

        $query = $this->messages()->where('senderId', '!=', $userId);

        if ($lastReadAt) {
            $query->where('sentAt', '>', $lastReadAt);
        }

        return $query->count();
    }

    public function hasUnreadFor(int $userId): bool
    {
        return $this->unreadMessagesCountFor($userId) > 0;
    }
}