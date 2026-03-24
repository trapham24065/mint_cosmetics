<?php

namespace App\Models;

use App\Enums\ReturnStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class OrderReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_code',
        'order_id',
        'customer_id',
        'reason',
        'description',
        'evidence_images',
        'status',
        'refund_amount',
        'refund_method',
        'refund_transaction_id',
        'refunded_at',
        'admin_note',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'status' => ReturnStatus::class,
        'evidence_images' => 'array',
        'refund_amount' => 'decimal:2',
        'refunded_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    /**
     * Boot function to auto-generate return code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->return_code)) {
                $model->return_code = 'RT-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
            }
        });
    }

    /**
     * Relationships
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderReturnItem::class);
    }

    /**
     * Helper methods
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    public function getStatusColorAttribute(): string
    {
        return $this->status->color();
    }

    public function isPending(): bool
    {
        return $this->status === ReturnStatus::Pending;
    }

    public function isApproved(): bool
    {
        return $this->status === ReturnStatus::Approved;
    }

    public function isRefunded(): bool
    {
        return $this->status === ReturnStatus::Refunded;
    }
}
