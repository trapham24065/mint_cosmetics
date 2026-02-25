@extends('admin.layouts.app')

@section('content')
    {{-- Đưa CSS trực tiếp vào phần content để đảm bảo luôn được render --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style>
        /* Ép kiểu cứng để loại bỏ dấu chấm <li> và fix lỗi xuyên thấu */
        .select2-dropdown {
            background-color: #ffffff !important;
            z-index: 9999 !important;
            border: 1px solid #ced4da !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        .select2-results__options {
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .select2-results__option {
            list-style-type: none !important;
            padding: 8px 12px !important;
        }
        /* Chỉnh chiều cao bằng với thẻ input form-control thông thường */
        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px !important;
            display: flex;
            align-items: center;
        }
    </style>

    <div class="container-xxl">
        <div class="card">
            <div class="d-flex card-header justify-content-between align-items-center">
                <h2 class="card-title">Create New Purchase Order</h2>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger m-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.inventory.store') }}" method="POST" id="po-form">
                @csrf
                <div class="row p-3">
                    {{-- Left Column: Order Items --}}
                    <div class="col-lg-8">
                        <div class="card bg-white border-0 rounded-10 mb-4 shadow-sm">
                            <div class="card-body p-4">
                                <h4 class="fs-18 mb-3">Order Items</h4>

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle" id="items-table"
                                           style="overflow: visible;">
                                        <thead class="table-light">
                                        <tr>
                                            <th style="width: 40%">Product Variant</th>
                                            <th style="width: 15%">Quantity</th>
                                            <th style="width: 20%">Import Price</th>
                                            <th style="width: 20%">Subtotal</th>
                                            <th style="width: 5%"></th>
                                        </tr>
                                        </thead>
                                        <tbody id="items-container">
                                        {{-- Rows will be added here by JS --}}
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="5">
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                        id="add-item-btn">
                                                    <i class="ri-add-line"></i> Add Item
                                                </button>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="row justify-content-end mt-3">
                                    <div class="col-md-5">
                                        <div class="d-flex justify-content-between border-top pt-2">
                                            <span class="fw-bold">Grand Total:</span>
                                            <span class="fw-bold text-primary fs-18" id="grand-total">0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-white border-0 rounded-10 mb-4 shadow-sm">
                            <div class="card-body p-4">
                                <label class="form-label fw-bold">Note</label>
                                <textarea name="note" class="form-control" rows="3"
                                          placeholder="Optional notes...">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Supplier Info --}}
                    <div class="col-lg-4">
                        <div class="card bg-white border-0 rounded-10 mb-4 shadow-sm">
                            <div class="card-body p-4">
                                <h4 class="fs-18 mb-3">Supplier Details</h4>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Select Supplier <span class="text-danger">*</span></label>
                                    <select name="supplier_id" class="form-select" required>
                                        <option value="">-- Choose Supplier --</option>
                                        @foreach($suppliers as $supplier)
                                            <option
                                                value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="alert alert-info py-2">
                                    <small><i class="ri-information-line"></i> Stock will only be updated after you
                                        <strong>Approve</strong>
                                        this order.</small>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                                    Create Purchase Order
                                </button>
                                <a href="{{ route('admin.inventory.index') }}"
                                   class="btn btn-outline-secondary w-100 py-2 mt-2">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Hidden Template Row --}}
            <template id="item-row-template">
                <tr class="item-row">
                    <td>
                        <select name="items[INDEX][variant_id]" class="form-select variant-select" required>
                            <option value="">Search Product...</option>
                            {{-- In trực tiếp danh sách variant đã được truyền từ Controller --}}
                            @foreach($variants as $variant)
                                <option value="{{ $variant->id }}">{{ $variant->full_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="items[INDEX][quantity]" class="form-control qty-input" min="1"
                               value="1" required>
                    </td>
                    <td>
                        <input type="number" name="items[INDEX][import_price]" class="form-control price-input" min="0"
                               step="0.01" value="0" required>
                    </td>
                    <td class="text-end subtotal-display">0.00</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger remove-row-btn" title="Remove">
                            <iconify-icon icon="solar:trash-bin-trash-bold-duotone" width="16"></iconify-icon>
                        </button>
                    </td>
                </tr>
            </template>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Select2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('items-container');
            const template = document.getElementById('item-row-template').innerHTML;
            const grandTotalEl = document.getElementById('grand-total');
            let rowCount = 0;

            // Cấu hình Select2 với giao diện Bootstrap 5
            function initSelect2 (selectElement) {
                $(selectElement).select2({
                    theme: 'bootstrap-5', // Áp dụng theme mới để đẹp hơn
                    width: '100%',
                    placeholder: 'Select a product...',
                    allowClear: true,
                });
            }

            // Thêm dòng mới
            function addRow () {
                const html = template.replace(/INDEX/g, rowCount++);
                container.insertAdjacentHTML('beforeend', html);

                // Tìm thẻ select vừa được thêm và khởi tạo Select2
                const newSelect = container.querySelector(`select[name="items[${rowCount - 1}][variant_id]"]`);
                if (newSelect) {
                    initSelect2(newSelect);
                }
            }

            // Thêm dòng đầu tiên mặc định
            addRow();

            // Sự kiện click nút "Add Item"
            document.getElementById('add-item-btn').addEventListener('click', addRow);

            // Sự kiện click nút "Xóa dòng"
            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row-btn')) {
                    const row = e.target.closest('tr');
                    if (container.querySelectorAll('tr').length > 1) {
                        // Cần destroy select2 trước khi xóa DOM để tránh rò rỉ bộ nhớ
                        const selectEl = $(row).find('.variant-select');
                        if (selectEl.hasClass('select2-hidden-accessible')) {
                            selectEl.select2('destroy');
                        }
                        row.remove();
                        calculateTotal();
                    } else {
                        alert('You must have at least one item.');
                    }
                }
            });

            // Tính toán tổng tiền khi input thay đổi
            container.addEventListener('input', function(e) {
                if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
                    calculateRowTotal(e.target.closest('tr'));
                    calculateTotal();
                }
            });

            function calculateRowTotal (row) {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                const subtotal = qty * price;
                // Định dạng tiền tệ
                row.querySelector('.subtotal-display').textContent = new Intl.NumberFormat('vi-VN').format(subtotal);
            }

            function calculateTotal () {
                let total = 0;
                container.querySelectorAll('tr').forEach(row => {
                    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                    const price = parseFloat(row.querySelector('.price-input').value) || 0;
                    total += (qty * price);
                });
                grandTotalEl.textContent = new Intl.NumberFormat('vi-VN',
                    { style: 'currency', currency: 'VND' }).format(total);
            }
        });
    </script>
@endpush
