@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fs-18 mb-1">Đơn đặt hàng: {{ $purchaseOrder->code }}</h2>
                <span class="text-muted">Được tạo vào lúc: {{ $purchaseOrder->created_at->format('d M, Y H:i') }}</span>
            </div>
            <div>
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary me-2">Trở lại danh
                    sách</a>

                {{-- Chỉ hiện nút hành động nếu đơn hàng đang Pending --}}
                @if($purchaseOrder->status === 'pending')
                    {{-- Form Hủy --}}
                    <form action="{{ route('admin.inventory.cancel', $purchaseOrder) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger me-2">Hủy đơn hàng</button>
                    </form>

                    {{-- Form Duyệt (Quan trọng) --}}
                    <form action="{{ route('admin.inventory.approve', $purchaseOrder) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Bạn chấp nhận đơn hàng này? Tình trạng kho hàng sẽ được cập nhật ngay lập tức.');">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-check-line me-1"></i> Phê duyệt và thêm hàng tồn kho
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card  border-0 rounded-10 mb-4">
                    <div class="card-body p-4">
                        <h4 class="fs-18 mb-3">Mặt hàng</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm / Biến thể</th>
                                    <th>Số lượng</th>
                                    <th>Giá nhập khẩu</th>
                                    <th>Tổng phụ</th>
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
                                                <span class="text-danger">Biến thể đã bị xóa</span>
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
                                    <th colspan="3" class="text-end">Tổng cộng</th>
                                    <th class="fw-bold text-primary fs-16">{{ number_format($purchaseOrder->total_amount, 2) }}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                @if($purchaseOrder->note)
                    <div class="card  border-0 rounded-10 mb-4">
                        <div class="card-body p-4">
                            <h5 class="fs-16 fw-bold">Ghi chú</h5>
                            <p class="mb-0">{{ $purchaseOrder->note }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card  border-0 rounded-10 mb-4">
                    <div class="card-body p-4">
                        <h4 class="fs-18 mb-3">Thông tin</h4>
                        <div class="mb-3">
                            <label class="text-muted d-block">Trạng thái</label>
                            @if($purchaseOrder->status === 'completed')
                                <span class="badge bg-success-subtle text-success fs-14">Hoàn thành</span>
                                <div class="small text-muted mt-1">Đã nhận
                                    at: {{ $purchaseOrder->received_at->format('d M, Y H:i') }}</div>
                            @elseif($purchaseOrder->status === 'cancelled')
                                <span class="badge bg-danger-subtle text-danger fs-14">Đã hủy</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning fs-14">Đang chờ phê duyệt</span>
                            @endif
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="text-muted d-block">Nhà cung cấp</label>
                            <div class="fw-bold fs-16">{{ $purchaseOrder->supplier->name }}</div>
                            <div>{{ $purchaseOrder->supplier->contact_person }}</div>
                            <div>{{ $purchaseOrder->supplier->phone }}</div>
                            <div>{{ $purchaseOrder->supplier->email }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
