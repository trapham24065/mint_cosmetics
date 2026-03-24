@extends('admin.layouts.app')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-9 col-lg-8">
            {{-- Return Information Card --}}
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <h4 class="fw-medium text-dark d-flex align-items-center gap-2">
                                {{ $return->return_code }}
                                <span class="badge bg-{{ $return->status_color }}">{{ $return->status_label }}</span>
                            </h4>
                            <p class="mb-0">Yêu cầu trả hàng được tạo vào: {{ $return->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.returns.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Trở lại danh sách
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Return Items Card --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Sản phẩm trả lại</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover">
                            <thead class="bg-light-subtle border-bottom">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Số lượng trả</th>
                                    <th>Giá hoàn lại</th>
                                    <th class="text-end">Tổng cộng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($return->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($item->orderItem->product->image)
                                            <img src="{{ asset('storage/' . $item->orderItem->product->image) }}"
                                                alt="{{ $item->orderItem->product_name }}"
                                                class="rounded"
                                                style="width: 48px; height: 48px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item->orderItem->product_name }}</h6>
                                                @if($item->item_reason)
                                                <small class="text-muted">Lý do: {{ $item->item_reason }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->refund_price, 0, ',', '.') }} ₫</td>
                                    <td class="text-end fw-medium">{{ number_format($item->quantity * $item->refund_price, 0, ',', '.') }} ₫</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Tổng số tiền hoàn:</td>
                                    <td class="text-end fw-bold text-primary fs-5">{{ number_format($return->refund_amount, 0, ',', '.') }} ₫</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Return Reason Card --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Lý do trả hàng</h4>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $return->reason }}</p>
                    @if($return->description)
                    <hr>
                    <h6 class="fw-medium">Mô tả chi tiết:</h6>
                    <p class="mb-0">{{ $return->description }}</p>
                    @endif

                    @if(!empty($return->evidence_images) && is_array($return->evidence_images))
                    <hr>
                    <h6 class="fw-medium mb-3">Ảnh bằng chứng:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($return->evidence_images as $image)
                        <a href="{{ asset('storage/' . $image) }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('storage/' . $image) }}" alt="Evidence Image" class="rounded border" style="width: 100px; height: 100px; object-fit: cover;">
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            {{-- Admin Note Card --}}
            @if($return->admin_note)
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Ghi chú của Admin</h4>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $return->admin_note }}</p>
                    @if($return->processedBy)
                    <small class="text-muted">
                        Xử lý bởi: {{ $return->processedBy->name }}
                        vào {{ $return->processed_at->format('d/m/Y H:i') }}
                    </small>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="col-xl-3 col-lg-4">
            {{-- Actions Card --}}
            @if($return->isPending())
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Hành động</h4>
                </div>
                <div class="card-body">
                    {{-- Approve Form --}}
                    <form action="{{ route('admin.returns.approve', $return) }}" method="POST" class="mb-3">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Ghi chú (tùy chọn)</label>
                            <textarea name="admin_note" class="form-control" rows="3" placeholder="Nhập ghi chú..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle"></i> Duyệt yêu cầu
                        </button>
                    </form>

                    {{-- Reject Form --}}
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="bi bi-x-circle"></i> Từ chối
                    </button>
                </div>
            </div>
            @endif

            @if($return->isApproved())
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Xử lý hoàn tiền</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.returns.refund', $return) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Phương thức hoàn tiền <span class="text-danger">*</span></label>
                            <select name="refund_method" class="form-select" required>
                                <option value="">Chọn phương thức</option>
                                <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                                <option value="cash">Tiền mặt</option>
                                <option value="momo">MoMo</option>
                                <option value="zalopay">ZaloPay</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mã giao dịch (tùy chọn)</label>
                            <input type="text" name="refund_transaction_id" class="form-control" placeholder="Nhập mã giao dịch">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-cash-coin"></i> Xác nhận hoàn tiền
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Order Info Card --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Thông tin đơn hàng</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Mã đơn hàng:</label>
                        <p class="mb-0">
                            <a href="{{ route('admin.orders.show', $return->order_id) }}" class="text-primary">
                                #{{ $return->order_id }}
                            </a>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Khách hàng:</label>
                        <p class="mb-0">
                            @if($return->customer)
                            {{ $return->customer->first_name }} {{ $return->customer->last_name }}
                            @else
                            N/A
                            @endif
                        </p>
                    </div>
                    @if($return->refunded_at)
                    <div class="mb-3">
                        <label class="form-label fw-medium">Ngày hoàn tiền:</label>
                        <p class="mb-0">{{ $return->refunded_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Phương thức:</label>
                        <p class="mb-0">{{ $return->refund_method }}</p>
                    </div>
                    @if($return->refund_transaction_id)
                    <div class="mb-3">
                        <label class="form-label fw-medium">Mã giao dịch:</label>
                        <p class="mb-0">{{ $return->refund_transaction_id }}</p>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.returns.reject', $return) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Từ chối yêu cầu trả hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Lý do từ chối <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="4" placeholder="Nhập lý do từ chối..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xác nhận từ chối</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection