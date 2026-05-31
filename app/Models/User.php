<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['firstName', 'lastName', 'email', 'phoneNumber', 'password', 'joinedAt', 'lastOnline', 'isAdmin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'Users';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'joinedAt' => 'datetime',
            'lastOnline' => 'datetime',
            'isAdmin' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function auctions(): HasMany
    {
        return $this->hasMany(Auction::class, 'userId');
    }

    public function followedAuctions(): BelongsToMany
    {
        return $this->belongsToMany(Auction::class, 'FollowedAuctions', 'userId', 'auctionId');
    }

    public function chatsAsBuyer(): HasMany
    {
        return $this->hasMany(Chat::class, 'buyerId');
    }

    public function chatsAsSeller(): HasMany
    {
        return $this->hasMany(Chat::class, 'sellerId');
    }

    public function ratingsReceived(): HasMany
    {
        return $this->hasMany(Rating::class, 'sellerId');
    }

    public function ratingsGiven(): HasMany
    {
        return $this->hasMany(Rating::class, 'userId');
    }
}
