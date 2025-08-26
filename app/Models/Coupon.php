<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/26/2025
 * @time 12:23 AM
 */
declare(strict_types=1);

namespace App\Models;

use App\Enums\CouponType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{

    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_purchase_amount',
        'max_uses',
        'times_used',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'type'       => CouponType::class,
        'is_active'  => 'boolean',
        'starts_at'  => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Check if the coupon is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if the coupon has reached its usage limit.
     */
    public function hasExceededMaxUses(): bool
    {
        return $this->max_uses !== null && $this->times_used >= $this->max_uses;
    }

    /**
     * Master validity check for the coupon.
     */
    public function isValid(): bool
    {
        if (!$this->is_active || $this->isExpired() || $this->hasExceededMaxUses()) {
            return false;
        }
        return true;
    }

}
