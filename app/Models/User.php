<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['firstName', 'lastName', 'email', 'phoneNumber', 'password', 'joinedAt', 'lastOnline', 'isAdmin', 'isMainAdmin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'Users';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'joinedAt' => 'datetime',
            'lastOnline' => 'datetime',
            'isAdmin' => 'boolean',
            'isMainAdmin' => 'boolean',
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

    public function hasAccountOlderThanMonths(int $months): bool
    {
        return $this->joinedAt !== null
            && $this->joinedAt->lte(now()->subMonths($months));
    }

    public function scopeMatchingSearch(Builder $query, string $search): Builder
    {
        $like = '%'.$search.'%';

        return $query->where(function (Builder $builder) use ($like) {
            $builder->where('email', 'ilike', $like)
                ->orWhere('firstName', 'ilike', $like)
                ->orWhere('lastName', 'ilike', $like)
                ->orWhere('phoneNumber', 'ilike', $like)
                ->orWhereRaw('CONCAT("firstName", \' \', "lastName") ILIKE ?', [$like])
                ->orWhereRaw('CONCAT("lastName", \' \', "firstName") ILIKE ?', [$like]);
        });
    }
}
