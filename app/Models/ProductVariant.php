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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{

    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'discount_price',
        'stock',
        'image',
    ];

    /**
     * A variation belongs to an original product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * A variant has multiple attribute values (e.g. Color Black, Size L).
     */
    /**
     * The attribute values that belong to the product variant.
     */
    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'attribute_value_product_variant',
            'product_variant_id',
            'attribute_value_id'
        );
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price'          => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    /**
     * Get the order items for the product variant.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

}
