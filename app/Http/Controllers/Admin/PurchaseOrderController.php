<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\StockAdjustment;
use App\Models\Supplier;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class PurchaseOrderController extends Controller
{

    /**
     * Display list of import vouchers.
     */
    public function index(): View
    {
        $title = 'Hàng tồn kho / Đơn đặt hàng';
        $variants = ProductVariant::query()
            ->with(['product'])
            ->whereHas('product')
            ->orderBy('id')
            ->get();

        $adjustmentTypes = $this->adjustmentTypes();

        $recentAdjustments = StockAdjustment::query()
            ->with(['productVariant.product', 'user'])
            ->latest()
            ->limit(20)
            ->get();

        return view(
            'admin.management.inventory.index',
            compact(
                'title',
                'variants',
                'adjustmentTypes',
                'recentAdjustments'
            )
        );
    }

    public function adjustStock(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'variant_id'      => ['required', 'exists:product_variants,id'],
                'adjustment_type' => ['required', 'string', Rule::in(array_keys($this->adjustmentTypes()))],
                'quantity'        => ['required', 'integer', 'min:1', 'max:100000'],
                'reason'          => ['nullable', 'string', 'max:2000'],
            ],
            [
                'variant_id.required' => 'Vui lòng chọn sản phẩm',
                'variant_id.exists'   => 'Sản phẩm không tồn tại',

                'adjustment_type.required' => 'Vui lòng chọn loại điều chỉnh',
                'adjustment_type.in'       => 'Loại điều chỉnh không hợp lệ',

                'quantity.required' => 'Vui lòng nhập số lượng',
                'quantity.integer'  => 'Số lượng phải là số nguyên',
                'quantity.min'      => 'Số lượng phải lớn hơn 0',
                'quantity.max'      => 'Số lượng quá lớn',

                'reason.max' => 'Lý do không được vượt quá 2000 ký tự',
            ],
            [
                'variant_id'      => 'Sản phẩm',
                'adjustment_type' => 'Loại điều chỉnh',
                'quantity'        => 'Số lượng',
                'reason'          => 'Lý do',
            ]
        );

        $type = $request->string('adjustment_type')->toString();
        $quantity = (int)$request->integer('quantity');
        $change = $this->getSignedQuantity($type, $quantity);

        try {
            DB::transaction(function () use ($request, $type, $change) {
                $variant = ProductVariant::query()
                    ->lockForUpdate()
                    ->findOrFail((int)$request->integer('variant_id'));

                $before = (int)$variant->stock;
                $after = $before + $change;

                if ($after < 0) {
                    throw new \RuntimeException(
                        'Hàng tồn kho không đủ để thực hiện thao tác này.'
                    );
                }

                $variant->update(['stock' => $after]);

                StockAdjustment::create([
                    'product_variant_id' => $variant->id,
                    'user_id'            => Auth::id(),
                    'adjustment_type'    => $type,
                    'quantity_change'    => $change,
                    'stock_before'       => $before,
                    'stock_after'        => $after,
                    'reason'             => $request->input('reason'),
                ]);
            });

            return redirect()->route('admin.inventory.index')->with(
                'success',
                'Kho dữ liệu đã được cập nhật và các thay đổi về sự cố đã được ghi nhận thành công.'
            );
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        } catch (\Exception $e) {
            Log::error('Stock adjustment error: '.$e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi khi điều chỉnh tồn kho.')->withInput();
        }
    }

    /**
     * Show the form for creating a new purchase order.
     */
    public function create(): View
    {
        $title = 'Tạo đơn đặt hàng';
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
                //                $variant->full_name = $variant->product->name . ($attributes ? " ({$attributes})" : "") . " - " . ($variant->sku ?? 'No SKU');
                $variant->full_name = $variant->sku;
                return $variant;
            });

        return view('admin.management.inventory.create', compact('title', 'suppliers', 'variants'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'supplier_id'          => 'required|exists:suppliers,id',
                'note'                 => 'nullable|string',
                'items'                => 'required|array|min:1',
                'items.*.variant_id'   => 'required|exists:product_variants,id',
                'items.*.quantity'     => 'required|integer|min:1',
                'items.*.import_price' => 'required|numeric|min:0',
            ],
            [
                'supplier_id.required' => 'Vui lòng chọn nhà cung cấp',
                'supplier_id.exists'   => 'Nhà cung cấp không tồn tại',

                'items.required' => 'Vui lòng thêm ít nhất 1 sản phẩm',
                'items.array'    => 'Dữ liệu sản phẩm không hợp lệ',
                'items.min'      => 'Phải có ít nhất 1 sản phẩm',

                'items.*.variant_id.required' => 'Vui lòng chọn sản phẩm',
                'items.*.variant_id.exists'   => 'Sản phẩm không tồn tại',

                'items.*.quantity.required' => 'Vui lòng nhập số lượng',
                'items.*.quantity.integer'  => 'Số lượng phải là số nguyên',
                'items.*.quantity.min'      => 'Số lượng phải lớn hơn 0',

                'items.*.import_price.required' => 'Vui lòng nhập giá nhập',
                'items.*.import_price.numeric'  => 'Giá nhập phải là số',
                'items.*.import_price.min'      => 'Giá nhập phải lớn hơn hoặc bằng 0',
            ],
            [
                'supplier_id'          => 'Nhà cung cấp',
                'items'                => 'Danh sách sản phẩm',
                'items.*.variant_id'   => 'Sản phẩm',
                'items.*.quantity'     => 'Số lượng',
                'items.*.import_price' => 'Giá nhập',
            ]
        );

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
                ->with(
                    'success',
                    'Đơn đặt hàng đã được tạo thành công. Vui lòng xem xét và phê duyệt để cập nhật tồn kho.'
                );
        } catch (QueryException $e) {
            Log::error('PO Creation Error: '.$e->getMessage());
            $message = $this->getQueryExceptionMessage($e);
            return back()->with('error', $message)->withInput();
        } catch (\Exception $e) {
            Log::error('PO Creation Error: '.$e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Show import voucher details.
     */
    public function show(PurchaseOrder $purchaseOrder): View
    {
        $title = 'Chi tiết đơn đặt hàng: '.$purchaseOrder->code;
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
            return back()->with('error', 'Đơn hàng này đã được xử lý.');
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

            return back()->with('success', 'Đơn đặt hàng đã được phê duyệt. Hàng tồn kho đã được cập nhật.');
        } catch (QueryException $e) {
            Log::error('PO Approve Error: '.$e->getMessage());
            $message = $this->getQueryExceptionMessage($e);
            return back()->with('error', $message);
        } catch (\Exception $e) {
            Log::error('PO Approve Error: '.$e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel entry (only if not approved).
     */
    public function cancel(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if ($purchaseOrder->status !== 'pending') {
            return back()->with('error', 'Không thể hủy đơn hàng đã được xử lý.');
        }

        $purchaseOrder->update(['status' => 'cancelled']);

        return back()->with('success', 'Đơn đặt hàng đã được hủy thành công.');
    }

    public function getDataForGrid(): JsonResponse
    {
        $orders = PurchaseOrder::with('supplier')->latest()->get();

        $data = $orders->map(function ($order) {
            return [
                'id'            => $order->id,
                'code'          => $order->code,
                'supplier_name' => $order->supplier->name ?? 'Không rõ',
                'total_amount'  => number_format($order->total_amount, 2),
                'status'        => $order->status,
                'created_at'    => $order->created_at->format('d/m/Y'),
            ];
        });

        return response()->json(['data' => $data]);
    }

    private function adjustmentTypes(): array
    {
        return [
            'damaged'       => 'Hàng hỏng / vỡ',
            'lost'          => 'Thất thoát kho',
            'expired'       => 'Hết hạn sử dụng',
            'repair_out'    => 'Xuất đi sửa chữa',
            'repair_in'     => 'Nhận lại sau sửa chữa',
            'found'         => 'Kiểm kê dư hàng',
            'recount_minus' => 'Kiểm kê thiếu',
            'recount_plus'  => 'Kiểm kê thừa',
            'other'         => 'Điều chỉnh khác',
        ];
    }

    private function getSignedQuantity(string $type, int $quantity): int
    {
        $negativeTypes = ['damaged', 'lost', 'expired', 'repair_out', 'recount_minus'];
        return in_array($type, $negativeTypes, true) ? -$quantity : $quantity;
    }

}
