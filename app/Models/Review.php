<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_item_id',
        'product_id',
        'reviewer_name',
        'rating',
        'media',
        'review',
        'is_approved',
        'is_public_visible',
        'hidden_reason',
        'hidden_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_approved'       => 'boolean',
        'is_public_visible' => 'boolean',
        'media'             => 'array',
        'hidden_at'         => 'datetime',
    ];

    public function scopePublicVisible($query)
    {
        return $query
            ->where('is_approved', true)
            ->where('is_public_visible', true);
    }

    public function hideFromPublic(string $reason = 'return_refunded'): void
    {
        $this->forceFill([
            'is_public_visible' => false,
            'hidden_reason'     => $reason,
            'hidden_at'         => now(),
        ])->save();
    }

    /**
     * Get the product that the review belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }
}
