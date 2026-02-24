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

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'brand_id',
        'description',
        'image',
        'list_image',
        'active',
    ];

    protected $casts = [
        'list_image' => 'array',
        'active'     => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get a list of related products (same category).
     */
    public function getRelatedProducts(): Collection
    {
        return self::where('category_id', $this->category_id)
            ->where('id', '!=', $this->id)
            ->where('active', true)
            ->inRandomOrder()
            ->take(12)
            ->get();
    }

    /**
     * A product has many variations.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'attribute_product');
    }

    public function orderItems(): HasManyThrough|Product
    {
        return $this->hasManyThrough(OrderItem::class, ProductVariant::class);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(static function (Product $product) {
            $product->slug = self::generateUniqueSlug($product->name);
        });

        static::updating(static function (Product $product) {
            // Only regenerate slug if name changed
            if ($product->isDirty('name')) {
                $product->slug = self::generateUniqueSlug($product->name, $product->id);
            }
        });
    }

    /**
     * Generate a unique slug for the product.
     * If slug already exists, append a suffix like -1, -2, etc.
     */
    protected static function generateUniqueSlug(string $name, ?int $exceptId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        // Keep appending counter until we find a unique slug
        while (self::where('slug', $slug)->when($exceptId, function ($query) use ($exceptId) {
            return $query->where('id', '!=', $exceptId);
        })->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    /**
     * Calculate the average rating from approved reviews.
     * Returns an integer from 0 to 5.
     */
    public function averageRating(): int
    {
        // avg() will return null if there are no reviews, so we default to 0
        return (int)round($this->approvedReviews()->avg('rating') ?? 0);
    }

    /**
     * Get the total count of approved reviews.
     */
    public function reviewsCount(): int
    {
        return $this->approvedReviews()->count();
    }
}
