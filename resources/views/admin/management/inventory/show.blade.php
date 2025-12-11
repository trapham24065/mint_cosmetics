@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fs-18 mb-1">Purchase Order: {{ $purchaseOrder->code }}</h2>
            <span class="text-muted">Created at: {{ $purchaseOrder->created_at->format('d M, Y H:i') }}</span>
        </div>
        <div>
            <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary me-2">Back to List</a>

            {{-- Chỉ hiện nút hành động nếu đơn hàng đang Pending --}}
            @if($purchaseOrder->status === 'pending')
                {{-- Form Hủy --}}
                <form action="{{ route('admin.inventory.cancel', $purchaseOrder) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Are you sure you want to cancel this order?');">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger me-2">Cancel Order</button>
                </form>

                {{-- Form Duyệt (Quan trọng) --}}
                <form action="{{ route('admin.inventory.approve', $purchaseOrder) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Approve this order? Stock will be updated immediately.');">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">
                        <i class="ri-check-line me-1"></i> Approve & Add Stock
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card bg-white border-0 rounded-10 mb-4">
                <div class="card-body p-4">
                    <h4 class="fs-18 mb-3">Items</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>Product / Variant</th>
                                <th>Quantity</th>
                                <th>Import Price</th>
                                <th>Subtotal</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($purchaseOrder->items as $item)
                                <tr>
                                    <td>
                                        @if($item->productVariant && $item->productVariant->product)
                                            <div class="fw-bold">{{ $item->productVariant->product->name }}</div>
                                            <small class="text-muted">
                                                SKU: {{ $item->productVariant->sku ?? 'N/A' }} |
                                                @foreach($item->productVariant->attributeValues as $val)
                                                    {{ $val->attribute->name }}: {{ $val->value }}
                                                @endforeach
                                            </small>
                                        @else
                                            <span class="text-danger">Variant Deleted</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->import_price, 2) }}</td>
                                    <td class="fw-bold">{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Grand Total</th>
                                <th class="fw-bold text-primary fs-16">{{ number_format($purchaseOrder->total_amount, 2) }}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            @if($purchaseOrder->note)
                <div class="card bg-white border-0 rounded-10 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fs-16 fw-bold">Note</h5>
                        <p class="mb-0">{{ $purchaseOrder->note }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card bg-white border-0 rounded-10 mb-4">
                <div class="card-body p-4">
                    <h4 class="fs-18 mb-3">Information</h4>
                    <div class="mb-3">
                        <label class="text-muted d-block">Status</label>
                        @if($purchaseOrder->status === 'completed')
                            <span class="badge bg-success-subtle text-success fs-14">Completed</span>
                            <div class="small text-muted mt-1">Received
                                at: {{ $purchaseOrder->received_at->format('d M, Y H:i') }}</div>
                        @elseif($purchaseOrder->status === 'cancelled')
                            <span class="badge bg-danger-subtle text-danger fs-14">Cancelled</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning fs-14">Pending Approval</span>
                        @endif
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="text-muted d-block">Supplier</label>
                        <div class="fw-bold fs-16">{{ $purchaseOrder->supplier->name }}</div>
                        <div>{{ $purchaseOrder->supplier->contact_person }}</div>
                        <div>{{ $purchaseOrder->supplier->phone }}</div>
                        <div>{{ $purchaseOrder->supplier->email }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
