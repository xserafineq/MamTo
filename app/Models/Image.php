<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

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
        'uuid' => 'string',
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

    public function diskPath(): string
    {
        $extension = pathinfo($this->filename, PATHINFO_EXTENSION);

        return 'images/' . $this->uuid . '.' . $extension;
    }

    public function fileExists(): bool
    {
        return Storage::disk('public')->exists($this->diskPath());
    }

    protected function fileUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->fileExists()) {
                return asset('assets/notfound.png');
            }

            return Storage::disk('public')->url($this->diskPath());
        });
    }
}
