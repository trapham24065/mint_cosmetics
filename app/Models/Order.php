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

use App\Enums\OrderStatus;
use App\Traits\HasOrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{

    use HasFactory;
    use HasOrderStatus;

    protected $fillable = [
        'customer_id',
        'total_price',
        'status',
        'payment_method',
        'coupon_code',
        'discount_amount',
        'shipping_fee',
        'shipping_provider',
        'shipping_province_id',
        'shipping_district_id',
        'shipping_ward_code',
        'ghn_order_code',
        'ghn_response',
        'transaction_id',
        'notes',
        'first_name',
        'last_name',
        'address',
        'phone',
        'email',
    ];

    protected $casts = [
        'status'       => OrderStatus::class,
        'completed_at' => 'datetime',
        'shipping_fee' => 'decimal:2',
        'ghn_response' => 'array',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function returns(): HasMany
    {
        return $this->hasMany(OrderReturn::class);
    }

    // Helper method to get total items count
    public function getTotalItemsAttribute()
    {
        return $this->orderItems->sum('quantity');
    }

    // Helper method to calculate total from order items
    public function calculateTotal()
    {
        return $this->orderItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    /**
     * Calculate total revenue from completed orders.
     */
    public static function getTotalRevenue(): float
    {
        return self::where('status', OrderStatus::Completed)->sum('total_price');
    }
}
