<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'Categories';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'imageId',
        'parentId',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'imageId');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parentId');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parentId');
    }

    public function auctions(): HasMany
    {
        return $this->hasMany(Auction::class, 'categoryId');
    }

    // Kategoria wymaga akceptacji administratora (Praca i podkategorie)
    public static function requiresApproval(int $categoryId): bool
    {
        $category = static::find($categoryId);

        while ($category) {
            if ($category->name === 'Praca') {
                return true;
            }

            $category = $category->parentId
                ? static::find($category->parentId)
                : null;
        }

        return false;
    }
}
