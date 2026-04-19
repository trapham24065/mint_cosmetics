@extends('admin.layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style>
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
        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px !important;
            display: flex;
            align-items: center;
        }
        .table-responsive {
            overflow: visible !important;
        }

        .choices {
            position: relative;
            z-index: 1000;
        }

        .choices__list--dropdown {
            z-index: 9999 !important;
        }
        .choices[data-type*="select-one"] .choices__list--dropdown {
            top: 100% !important;
            bottom: auto !important;
            transform: none !important;
        }
        #items-table tr {
            position: relative;
            z-index: 1;
        }

        #items-table tr:hover {
            z-index: 10;
        }

        #items-table tr:has(.choices.is-open) {
            z-index: 999;
        }
    </style>

    <div class="container-xxl">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Tạo đơn đặt hàng mới</h2>
            </div>
            <form action="{{ route('admin.inventory.store') }}" method="POST" id="po-form">
                @csrf
                <div class="row p-3">
                    {{-- Left Column: Order Items --}}
                    <div class="col-lg-8">
                        <div class="card border-0 rounded-10 mb-4 shadow-sm">
                            <div class="card-body p-4">
                                <h4 class="fs-18 mb-3">Đặt hàng các mặt hàng</h4>

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle" id="items-table"
                                           style="overflow: visible;">
                                        <thead class="table-light">
                                        <tr>
                                            <th style="width: 40%">Biến thể sản phẩm</th>
                                            <th style="width: 15%">Số lượng</th>
                                            <th style="width: 20%">Giá nhập khẩu</th>
                                            <th style="width: 20%">Tổng phụ</th>
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
                                                    <i class="ri-add-line"></i> Thêm mục
                                                </button>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="row justify-content-end mt-3">
                                    <div class="col-md-5">
                                        <div class="d-flex justify-content-between border-top pt-2">
                                            <span class="fw-bold">Tổng cộng:</span>
                                            <span class="fw-bold text-primary fs-18" id="grand-total">0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 rounded-10 mb-4 shadow-sm">
                            <div class="card-body p-4">
                                <label class="form-label fw-bold">Ghi chú</label>
                                <textarea name="note" class="form-control" rows="3"
                                          placeholder="Ghi chú tùy chọn...">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Supplier Info --}}
                    <div class="col-lg-4">
                        <div class="card  border-0 rounded-10 mb-4 shadow-sm">
                            <div class="card-body p-4">
                                <h4 class="fs-18 mb-3">Thông tin nhà cung cấp</h4>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        Chọn nhà cung cấp <span class="text-danger">*</span>
                                    </label>

                                    <select
                                        name="supplier_id"
                                        id="supplier-select"
                                        class="form-control @error('supplier_id') is-invalid @enderror"
                                        data-choices
                                        data-choices-search="true"
                                        data-choices-placeholder="true"
                                        required
                                    >
                                        <option value="">-- Chọn nhà cung cấp --</option>

                                        @foreach($suppliers as $supplier)
                                            <option
                                                value="{{ $supplier->id }}"
                                                @selected((int) old('supplier_id') === $supplier->id)
                                            >
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="alert alert-info py-2">
                                    <small><i class="ri-information-line"></i> Kho hàng sẽ chỉ được cập nhật sau khi bạn
                                        <strong>Chấp thuận</strong>
                                        thứ tự này.</small>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                                    Tạo đơn đặt hàng
                                </button>
                                <a href="{{ route('admin.inventory.index') }}"
                                   class="btn btn-outline-secondary w-100 py-2 mt-2">
                                    Hủy bỏ
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
                        <select
                            name="items[INDEX][variant_id]"
                            class="form-control variant-select"
                            data-choices
                            data-choices-search="true"
                            required
                        >
                            <option value="">Tìm kiếm sản phẩm...</option>

                            @foreach($variants as $variant)
                                <option value="{{ $variant->id }}">
                                    [{{ $variant->sku ?? ('VAR-' . $variant->id) }}]
                                    {{ $variant->product->name }}
                                    (Tồn: {{ $variant->stock }})
                                </option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <input type="number" name="items[INDEX][quantity]"
                               class="form-control qty-input" min="1" value="1" required>
                    </td>

                    <td>
                        <input type="number" name="items[INDEX][import_price]"
                               class="form-control price-input" min="0" step="0.01" value="0" required>
                    </td>

                    <td class="text-end subtotal-display">0.00</td>

                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger remove-row-btn">
                            🗑
                        </button>
                    </td>
                </tr>
            </template>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const container = document.getElementById('items-container');
            const template = document.getElementById('item-row-template').innerHTML;
            const grandTotalEl = document.getElementById('grand-total');

            let rowCount = 0;

            // =============================
            // INIT CHOICES
            // =============================
            function initChoices (selectElement) {
                if (!selectElement) {
                    return;
                }

                new Choices(selectElement, {
                    searchEnabled: true,
                    itemSelectText: '',
                    shouldSort: false,
                    searchResultLimit: 10,
                    allowHTML: true,
                });
            }

            // =============================
            // ADD ROW
            // =============================
            function addRow () {
                const html = template.replace(/INDEX/g, rowCount);
                container.insertAdjacentHTML('beforeend', html);

                const newSelect = container.querySelector(
                    `select[name="items[${rowCount}][variant_id]"]`,
                );

                initChoices(newSelect);

                rowCount++;
            }

            // =============================
            // REMOVE ROW
            // =============================
            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row-btn')) {
                    const row = e.target.closest('tr');

                    if (container.querySelectorAll('tr').length > 1) {
                        row.remove();
                        calculateTotal();
                    } else {
                        alert('Phải có ít nhất một sản phẩm');
                    }
                }
            });

            // =============================
            // CALCULATE ROW
            // =============================
            function calculateRowTotal (row) {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;

                const subtotal = qty * price;

                row.querySelector('.subtotal-display').textContent =
                    new Intl.NumberFormat('vi-VN').format(subtotal);
            }

            // =============================
            // CALCULATE TOTAL
            // =============================
            function calculateTotal () {
                let total = 0;

                container.querySelectorAll('tr').forEach(row => {
                    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                    const price = parseFloat(row.querySelector('.price-input').value) || 0;

                    total += qty * price;
                });

                grandTotalEl.textContent =
                    new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND',
                    }).format(total);
            }

            // =============================
            // INPUT CHANGE
            // =============================
            container.addEventListener('input', function(e) {
                if (e.target.classList.contains('qty-input') ||
                    e.target.classList.contains('price-input')) {

                    const row = e.target.closest('tr');
                    calculateRowTotal(row);
                    calculateTotal();
                }
            });

            // =============================
            // ADD FIRST ROW
            // =============================
            addRow();

            // =============================
            // BUTTON ADD
            // =============================
            document.getElementById('add-item-btn').addEventListener('click', addRow);

        });
    </script>
@endpush
