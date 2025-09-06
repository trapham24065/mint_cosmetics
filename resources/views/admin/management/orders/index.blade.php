@extends('admin.layouts.app')
@section('content')
    <div class="container-xxl">

        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-2">Pending Payment</h4>
                                <p class="text-muted fw-medium fs-22 mb-0">{{ $statusCounts['pending'] ?? 0 }}</p>
                            </div>
                            <div class="avatar-md bg-primary-subtle rounded">
                                <iconify-icon icon="solar:clock-circle-broken"
                                              class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-2">In Progress</h4>
                                <p class="text-muted fw-medium fs-22 mb-0">{{ $statusCounts['processing'] ?? 0 }}</p>
                            </div>
                            <div class="avatar-md bg-info-subtle rounded">
                                <iconify-icon icon="solar:inbox-line-broken"
                                              class="fs-32 text-info avatar-title"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-2">Order Shipped</h4>
                                <p class="text-muted fw-medium fs-22 mb-0">{{ $statusCounts['shipped'] ?? 0 }}</p>
                            </div>
                            <div>
                                <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:box-broken"
                                                  class="fs-32 text-primary avatar-title"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-2">Delivered</h4>
                                <p class="text-muted fw-medium fs-22 mb-0">{{ $statusCounts['delivered'] ?? 0 }}</p>
                            </div>
                            <div class="avatar-md bg-success-subtle rounded">
                                <iconify-icon icon="solar:clipboard-check-broken"
                                              class="fs-32 text-success avatar-title"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-2">Order Cancelled</h4>
                                <p class="text-muted fw-medium fs-22 mb-0">{{ $statusCounts['cancelled'] ?? 0 }}</p>
                            </div>
                            <div class="avatar-md bg-danger-subtle rounded">
                                <iconify-icon icon="solar:cart-cross-broken"
                                              class="fs-32 text-danger avatar-title"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="card-title">All Order List</h4>
                        {{-- Filter/Search Form can be added here if needed --}}
                    </div>
                    <div class="card-body">
                        {{-- Grid.js will render the table here --}}
                        <div id="table-orders-gridjs"></div>
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
            if (document.getElementById("table-orders-gridjs")) {

                new gridjs.Grid({
                    columns: [
                        { id: 'id', name: 'Order ID', formatter: (cell) => gridjs.html(`<a href="/admin/orders/${cell}" class="fw-bold">#${cell}</a>`) },
                        { id: 'created_at', name: 'Date' },
                        { id: 'customer', name: 'Customer' },
                        {
                            id: 'total',
                            name: 'Total',
                            formatter: (cell) => `${parseFloat(cell).toLocaleString('vi-VN')} VNĐ`
                        },
                        {
                            id: 'status',
                            name: 'Order Status',
                            formatter: (cell, row) => {
                                const statusValue = row.cells[4].data;
                                const statusColor = row.cells[6].data;
                                return gridjs.html(`<span class="badge bg-${statusColor}">${statusValue}</span>`);
                            }
                        },
                        {
                            name: 'Actions',
                            width: '100px',
                            formatter: (cell, row) => {
                                const orderId = row.cells[0].data;
                                const showUrl = `/admin/orders/${orderId}`;
                                return gridjs.html(`<a href="${showUrl}" class="btn btn-sm btn-light"><iconify-icon icon="solar:eye-broken"
                                                                  class="align-middle fs-18"></iconify-icon></a>`);
                            }
                        },
                        { id: 'status_color', name: 'Status Color', hidden: true }
                    ],
                    server: {
                        url: '{{ route('admin.api.orders.data') }}',
                        then: results => results.data,
                    },
                    sort: true,
                    search: true,
                    pagination: {
                        limit: 10
                    },
                }).render(document.getElementById("table-orders-gridjs"));
            }
        });
    </script>
    <!-- @formatter:on -->

@endpush
