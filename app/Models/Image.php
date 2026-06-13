<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    protected function fileUrl(): Attribute
    {
        return Attribute::get(function () {
            // pobieranie oryginalnego rozszerzenia pliku
            $extension = pathinfo($this->filename, PATHINFO_EXTENSION);

            // budowa nazwy
            $diskName = $this->uuid . '.' . $extension;

            // zwracanie gotowego adresu URL
            return Storage::disk('public')->url('images/' . $diskName);
        });
    }
}
