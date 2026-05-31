<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $table = 'Messages';

    public $timestamps = false;

    protected $fillable = [
        'chatId',
        'text',
        'sentAt',
        'senderId',
    ];

    protected $casts = [
        'sentAt' => 'datetime',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'chatId');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'senderId');
    }
}
