<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{

    use HasFactory;

    /**
     * Attributes can be assigned in bulk.
     */
    protected $fillable = [
        'purchase_order_id',
        'product_variant_id',
        'quantity',
        'import_price',
        'subtotal',
    ];

    /**
     * Cast data types to columns.
     */
    protected $casts = [
        'import_price' => 'decimal:2',
        'subtotal'     => 'decimal:2',
        'quantity'     => 'integer',
    ];

    /**
     * Relationship: A receipt detail belongs to one receipt.
     */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * Relationship: An order detail belongs to a product variant.
     */
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

}
