<?php

/**
 * @project mint_cosmetics
 *
 * @author PhamTra
 *
 * @email trapham24065@gmail.com
 *
 * @date 8/22/2025
 *
 * @time 3:24 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{

    protected $fillable = [
        'name',
        'slug',
        'image',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function products(): HasMany|Category
    {
        return $this->hasMany(Product::class);
    }

    /**
     * A category can have multiple product attributes.
     */
    public function productAttributes(): BelongsToMany
    {
        return $this->belongsToMany(
            Attribute::class,
            'attribute_category',
            'category_id',
            'attribute_id'
        );
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(static function (Category $category) {
            $category->slug = Str::slug($category->name);
        });
    }

}
