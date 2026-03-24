@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="d-flex card-header justify-content-between align-items-center">
                <h4 class="card-title">{{ $title }}</h4>
                <a href="{{ route('admin.inventory.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i> Tạo đơn đặt hàng
                </a>
            </div>

            <div class="card-body">
                {{-- Grid.js Container --}}
                <div id="table-inventory-gridjs"></div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Xử lý sự cố kho / Điều chỉnh tồn</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.inventory.adjustStock') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Biến thể sản phẩm <span class="text-danger">*</span></label>
                                <select name="variant_id" class="form-select" required>
                                    <option value="">Chọn biến thể</option>
                                    @foreach($variants as $variant)
                                        <option value="{{ $variant->id }}" @selected(old('variant_id')==$variant->id)>
                                            SKU: {{ $variant->sku ?? ('VAR-' . $variant->id) }}
                                            | {{ $variant->product->name }}
                                            | Tồn: {{ $variant->stock }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Loại điều chỉnh <span class="text-danger">*</span></label>
                                <select name="adjustment_type" class="form-select" required>
                                    <option value="">Chọn loại</option>
                                    @foreach($adjustmentTypes as $key => $label)
                                        <option value="{{ $key }}" @selected(old('adjustment_type')===$key)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Số lượng điều chỉnh <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" min="1" max="100000"
                                       class="form-control"
                                       value="{{ old('quantity', 1) }}" required>
                                <small class="text-muted">
                                    Hệ thống sẽ tự động cộng (+) hoặc trừ (-) dựa theo loại điều chỉnh.
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ghi chú / Nguyên nhân</label>
                                <textarea name="reason" rows="3" class="form-control"
                                          placeholder="Mô tả sự cố, biên bản, mã tham chiếu...">{{ old('reason') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fa fa-tools me-1"></i>
                                Ghi nhận sự cố & cập nhật tồn kho
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Lịch sử điều chỉnh tồn gần đây</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Thời gian</th>
                                    <th>SKU</th>
                                    <th>Loại</th>
                                    <th>Biến động</th>
                                    <th>Tồn sau</th>
                                    <th>Người xử lý</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($recentAdjustments as $adj)
                                    <tr>
                                        <td class="ps-3">{{ $adj->created_at->format('d/m H:i') }}</td>
                                        <td>
                                            <div class="fw-semibold">
                                                {{ $adj->productVariant->sku ?? ('VAR-' . $adj->product_variant_id) }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $adj->productVariant->product->name ?? 'Không xác định' }}
                                            </small>
                                        </td>
                                        <td>
                                            {{ $adjustmentTypes[$adj->adjustment_type] ?? $adj->adjustment_type }}
                                        </td>
                                        <td>
                                <span
                                    class="badge {{ $adj->quantity_change < 0 ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}">
                                    {{ $adj->quantity_change > 0 ? '+' : '' }}{{ $adj->quantity_change }}
                                </span>
                                        </td>
                                        <td>{{ $adj->stock_after }}</td>
                                        <td>{{ $adj->user->name ?? 'Hệ thống' }}</td>
                                    </tr>

                                    @if($adj->reason)
                                        <tr>
                                            <td></td>
                                            <td colspan="5">
                                                <small class="text-muted">
                                                    Ghi chú: {{ $adj->reason }}
                                                </small>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Chưa có lịch sử điều chỉnh tồn kho.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- @formatter:off -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById("table-inventory-gridjs")) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Khởi tạo Grid.js
                const grid = new gridjs.Grid({
                    columns: [
                        { id: 'id', name: 'ID', width: '60px', hidden: true }, // Ẩn ID nếu không cần thiết
                        {
                            id: 'code',
                            name: 'Mã',
                            formatter: (cell, row) => {
                                const id = row.cells[0].data; // Lấy ID từ cột ẩn
                                const url = `{{ url('admin/inventory') }}/${id}`;
                                return gridjs.html(`<a href="${url}" class="fw-bold text-primary">${cell}</a>`);
                            }
                        },
                        { id: 'supplier_name', name: 'Nhà cung cấp' },
                        {
                            id: 'total_amount',
                            name: 'Tổng số tiền',
                            formatter: (cell) => gridjs.html(`<span class="fw-bold">${cell} VND</span>`)
                        },
                        {
                            id: 'status',
                            name: 'Trạng thái',
                            formatter: (cell) => {
                                if (cell === 'completed') {
                                    return gridjs.html('<span class="badge bg-success-subtle text-success">Hoàn thành</span>');
                                } else if (cell === 'cancelled') {
                                    return gridjs.html('<span class="badge bg-danger-subtle text-danger">Đã hủy</span>');
                                } else {
                                    return gridjs.html('<span class="badge bg-warning-subtle text-warning">Chưa giải quyết</span>');
                                }
                            }
                        },
                        { id: 'created_at', name: 'Được tạo vào lúc' },
                        {
                            name: 'Hành động',
                            width: '100px',
                            sort: false,
                            formatter: (cell, row) => {
                                const id = row.cells[0].data; // Lấy ID
                                const showUrl = `{{ url('admin/inventory') }}/${id}`;

                                return gridjs.html(`
                                    <a href="${showUrl}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Xem
                                    </a>
                                `);
                            }
                        }
                    ],
                    server: {
                        url: 'inventories/api',
                        then: results => results.data.map(order => [
                            order.id,
                            order.code,
                            order.supplier_name,
                            order.total_amount,
                            order.status,
                            order.created_at,
                            null // Actions placeholder
                        ])
                    },
                    search: true,
                    sort: true,
                    pagination: { limit: 10 },
                    className: {
                        table: 'table table-hover align-middle mb-0',
                        thead: 'table-light',
                        th: 'fw-bold text-muted'
                    },

                }).render(document.getElementById("table-inventory-gridjs"));
            }
        });
    </script>
    <!-- @formatter:on -->
@endpush
