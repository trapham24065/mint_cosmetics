@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-18 mb-0">Create New Purchase Order</h2>
    </div>

    <form action="{{ route('admin.inventory.store') }}" method="POST" id="po-form">
        @csrf
        <div class="row">
            {{-- Left Column: Order Items --}}
            <div class="col-lg-8">
                <div class="card bg-white border-0 rounded-10 mb-4">
                    <div class="card-body p-4">
                        <h4 class="fs-18 mb-3">Order Items</h4>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="items-table">
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
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-item-btn">
                                            <i class="ri-add-line"></i> Add Item
                                        </button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="row justify-content-end mt-3">
                            <div class="col-md-4">
                                <div class="d-flex justify-content-between border-top pt-2">
                                    <span class="fw-bold">Grand Total:</span>
                                    <span class="fw-bold text-primary fs-18" id="grand-total">0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-white border-0 rounded-10 mb-4">
                    <div class="card-body p-4">
                        <label class="form-label fw-bold">Note</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Optional notes..."></textarea>
                    </div>
                </div>
            </div>

            {{-- Right Column: Supplier Info --}}
            <div class="col-lg-4">
                <div class="card bg-white border-0 rounded-10 mb-4">
                    <div class="card-body p-4">
                        <h4 class="fs-18 mb-3">Supplier Details</h4>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Supplier <span class="text-danger">*</span></label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">-- Choose Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="alert alert-info py-2">
                            <small><i class="ri-information-line"></i> Stock will only be updated after you <strong>Approve</strong>
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
                    <option value="">Select Product...</option>
                    @foreach($variants as $variant)
                        <option value="{{ $variant->id }}">{{ $variant->full_name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="items[INDEX][quantity]" class="form-control qty-input" min="1" value="1"
                       required>
            </td>
            <td>
                <input type="number" name="items[INDEX][import_price]" class="form-control price-input" min="0"
                       step="0.01" value="0" required>
            </td>
            <td class="text-end subtotal-display">0.00</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger remove-row-btn">
                    <iconify-icon icon="solar:trash-bin-trash-bold-duotone" width="16"></iconify-icon>
                </button>
            </td>
        </tr>
    </template>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('items-container');
            const template = document.getElementById('item-row-template').innerHTML;
            const grandTotalEl = document.getElementById('grand-total');
            let rowCount = 0;

            // Function to add a new row
            function addRow () {
                const html = template.replace(/INDEX/g, rowCount++);
                container.insertAdjacentHTML('beforeend', html);
                feather.replace(); // Refresh icons
            }

            // Add first row by default
            addRow();

            // Add row button
            document.getElementById('add-item-btn').addEventListener('click', addRow);

            // Remove row button (delegated)
            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row-btn')) {
                    const row = e.target.closest('tr');
                    // Prevent removing the last row if needed, or handle validation
                    if (container.querySelectorAll('tr').length > 1) {
                        row.remove();
                        calculateTotal();
                    } else {
                        alert('You must have at least one item.');
                    }
                }
            });

            // Calculate totals on input change
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
                row.querySelector('.subtotal-display').textContent = subtotal.toFixed(2);
            }

            function calculateTotal () {
                let total = 0;
                container.querySelectorAll('tr').forEach(row => {
                    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                    const price = parseFloat(row.querySelector('.price-input').value) || 0;
                    total += (qty * price);
                });
                grandTotalEl.textContent = total.toFixed(2);
            }
        });
    </script>
@endpush
