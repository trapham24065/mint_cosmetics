<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PurchaseOrder extends Model
{

    use HasFactory;

    protected $fillable = [
        'code',
        'supplier_id',
        'total_amount',
        'status', // pending, completed, cancelled
        'note',
        'received_at',
    ];

    protected $casts = [
        'received_at'  => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate a unique code when creating a Purchase Order
        static::creating(function ($model) {
            if (empty($model->code)) {
                // Example: PO-20231027-ABCD
                $model->code = 'PO-'.now()->format('Ymd').'-'.strtoupper(Str::random(4));
            }
        });
    }

    /**
     * Relationship: A purchase order belongs to a supplier.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relationship: A purchase order has many items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * Helper to check if the order is editable (pending).
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

}
