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

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * An attribute has multiple values.
     */
    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }

    /**
     * An attribute can belong to multiple categories.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'attribute_category');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'attribute_product');
    }
}
