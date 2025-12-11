<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class PurchaseOrderController extends Controller
{

    /**
     * Display list of import vouchers.
     */
    public function index(): View
    {
        $title = 'Inventory / Purchase Orders';
        return view('admin.management.inventory.index', compact('title'));
    }

    /**
     * Show the form for creating a new purchase order.
     */
    public function create(): View
    {
        $title = 'Create Purchase Order';
        $suppliers = Supplier::where('is_active', true)->get();

        // Fetch variants with product info for selection dropdown
        $variants = ProductVariant::with(['product', 'attributeValues.attribute'])
            ->get()
            ->filter(function ($variant) {
                return $variant->product !== null;
            })
            ->map(function ($variant) {
                $attributes = $variant->attributeValues->map(fn($v) => "{$v->attribute->name}: {$v->value}")->join(
                    ', '
                );
                $variant->full_name = $variant->product->name.($attributes ? " ({$attributes})" : "")." - ".($variant->sku ?? 'No SKU');
                return $variant;
            });

        return view('admin.management.inventory.create', compact('title', 'suppliers', 'variants'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'supplier_id'          => 'required|exists:suppliers,id',
            'note'                 => 'nullable|string',
            'items'                => 'required|array|min:1',
            'items.*.variant_id'   => 'required|exists:product_variants,id',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.import_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Create Entry Form (Header)
                $po = PurchaseOrder::create([
                    'supplier_id'  => $request->supplier_id,
                    'status'       => 'pending',
                    'note'         => $request->note,
                    'total_amount' => 0,
                ]);

                $totalAmount = 0;

                // 2.Create Receipt Details (Items)
                foreach ($request->items as $itemData) {
                    $quantity = (int)$itemData['quantity'];
                    $importPrice = (float)$itemData['import_price'];
                    $subtotal = $quantity * $importPrice;

                    PurchaseOrderItem::create([
                        'purchase_order_id'  => $po->id,
                        'product_variant_id' => $itemData['variant_id'],
                        'quantity'           => $quantity,
                        'import_price'       => $importPrice,
                        'subtotal'           => $subtotal,
                    ]);

                    $totalAmount += $subtotal;
                }

                // 3.Update total for receipt
                $po->update(['total_amount' => $totalAmount]);
            });

            return redirect()->route('admin.inventory.index')
                ->with('success', 'Purchase order created successfully. Please review and approve to update stock.');
        } catch (\Exception $e) {
            Log::error('PO Creation Error: '.$e->getMessage());
            return back()->with('error', 'Failed to create purchase order. '.$e->getMessage())->withInput();
        }
    }

    /**
     * Show import voucher details.
     */
    public function show(PurchaseOrder $purchaseOrder): View
    {
        $title = 'Purchase Order Details: '.$purchaseOrder->code;
        $purchaseOrder->load(['supplier', 'items.productVariant.product', 'items.productVariant.attributeValues']);

        return view('admin.management.inventory.show', compact('purchaseOrder', 'title'));
    }

    /**
     * Review the receipt and update inventory.
     * This action will add the quantity imported into the current inventory.
     */
    public function approve(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if ($purchaseOrder->status !== 'pending') {
            return back()->with('error', 'This order has already been processed.');
        }

        try {
            DB::transaction(function () use ($purchaseOrder) {
                // 1. Update the status of the receipt
                $purchaseOrder->update([
                    'status'      => 'completed',
                    'received_at' => now(),
                ]);

                // 2.Update inventory for each product variant
                foreach ($purchaseOrder->items as $item) {
                    $variant = $item->productVariant;
                    if ($variant) {
                        $variant->increment('stock', $item->quantity);
                    }
                }
            });

            return back()->with('success', 'Purchase order approved. Stock has been updated.');
        } catch (\Exception $e) {
            Log::error('PO Approve Error: '.$e->getMessage());
            return back()->with('error', 'Failed to approve order.');
        }
    }

    /**
     * Cancel entry (only if not approved).
     */
    public function cancel(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if ($purchaseOrder->status !== 'pending') {
            return back()->with('error', 'Cannot cancel a processed order.');
        }

        $purchaseOrder->update(['status' => 'cancelled']);

        return back()->with('success', 'Purchase order cancelled successfully.');
    }

    public function getDataForGrid(): JsonResponse
    {
        $orders = PurchaseOrder::with('supplier')->latest()->get();

        $data = $orders->map(function ($order) {
            return [
                'id'            => $order->id,
                'code'          => $order->code,
                'supplier_name' => $order->supplier->name ?? 'Unknown',
                'total_amount'  => number_format($order->total_amount, 2),
                'status'        => $order->status,
                'created_at'    => $order->created_at->format('d M, Y'),
            ];
        });

        return response()->json(['data' => $data]);
    }

}
