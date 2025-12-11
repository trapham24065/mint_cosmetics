@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        {{-- Header & Actions --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold py-3 mb-0">
                    <span class="text-muted fw-light">Suppliers /</span> {{ $supplier->name }}
                </h4>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-primary">
                    <i class="ri-edit-line me-1"></i> Edit Supplier
                </a>
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary">Back to List</a>
            </div>
        </div>

        <div class="row">
            {{-- Cột Trái: Thông tin chi tiết --}}
            <div class="col-xl-4 col-lg-5 col-md-5">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Overview</h5>

                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar bg-light-primary rounded me-3 p-2">
                                <i class="ri-building-line fs-24 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $supplier->name }}</h6>
                                <small class="text-muted">ID: #{{ $supplier->id }}</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <span class="fw-bold d-block mb-1">Status:</span>
                            @if($supplier->is_active)
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Inactive</span>
                            @endif
                        </div>

                        <hr>

                        <div class="info-list">
                            <div class="mb-3">
                                <span class="fw-bold d-block mb-1"><i
                                        class="ri-user-line me-1"></i> Contact Person:</span>
                                <span>{{ $supplier->contact_person ?? 'N/A' }}</span>
                            </div>
                            <div class="mb-3">
                                <span class="fw-bold d-block mb-1"><i class="ri-mail-line me-1"></i> Email:</span>
                                <a href="mailto:{{ $supplier->email }}">{{ $supplier->email ?? 'N/A' }}</a>
                            </div>
                            <div class="mb-3">
                                <span class="fw-bold d-block mb-1"><i class="ri-phone-line me-1"></i> Phone:</span>
                                <span>{{ $supplier->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="mb-3">
                                <span class="fw-bold d-block mb-1"><i class="ri-map-pin-line me-1"></i> Address:</span>
                                <span>{{ $supplier->address ?? 'N/A' }}</span>
                            </div>
                        </div>

                        @if($supplier->note)
                            <div class="alert alert-light mt-3 mb-0 border">
                                <i class="ri-sticky-note-line me-1"></i> <strong>Note:</strong><br>
                                {{ $supplier->note }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Thống kê nhanh --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Statistics</h5>
                        <div class="row text-center">
                            <div class="col-6 border-end">
                                <h4 class="fw-bold text-primary mb-0">{{ $purchaseOrders->total() }}</h4>
                                <small class="text-muted">Total Orders</small>
                            </div>
                            <div class="col-6">
                                <h4 class="fw-bold text-success mb-0">{{ number_format($totalImportValue) }}</h4>
                                <small class="text-muted">Total Value (VND)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cột Phải: Lịch sử nhập kho --}}
            <div class="col-xl-8 col-lg-7 col-md-7">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Purchase Order History (Inventory)</h5>
                        <a href="{{ route('admin.inventory.create') }}?supplier_id={{ $supplier->id }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="ri-add-line"></i> New Purchase Order
                        </a>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>PO Code</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Items Count</th>
                                <th>Total Amount</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($purchaseOrders as $po)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.inventory.show', $po->id) }}" class="fw-bold">
                                            {{ $po->code }}
                                        </a>
                                    </td>
                                    <td>{{ $po->created_at->format('d M, Y') }}</td>
                                    <td>
                                        @if($po->status === 'completed')
                                            <span class="badge bg-success-subtle text-success">Completed</span>
                                        @elseif($po->status === 'cancelled')
                                            <span class="badge bg-danger-subtle text-danger">Cancelled</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $po->items_count }} items</td>
                                    <td class="fw-bold">{{ number_format($po->total_amount) }}</td>
                                    <td>
                                        <a href="{{ route('admin.inventory.show', $po->id) }}"
                                           class="btn btn-sm btn-light text-primary"
                                           title="View Order">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ri-file-list-3-line fs-1 mb-2"></i>
                                            <p>No purchase orders found for this supplier.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer border-top-0">
                        {{ $purchaseOrders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
