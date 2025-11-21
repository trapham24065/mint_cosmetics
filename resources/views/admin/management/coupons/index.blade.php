@extends('admin.layouts.app')
@section('content')
    <div class="container-xxl">

        <div class="row">
            <div class="col-md-6 col-xl-4">
                <div class="card bg-primary-subtle">
                    <div class="card-body">
                        <h4 class="mb-1">{{ $totalCoupons }} Total Coupons</h4>
                        <p>All coupons created in the system.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card bg-success-subtle">
                    <div class="card-body">
                        <h4 class=" mb-1">{{ $activeCoupons }} Active Coupons</h4>
                        <p class="">Coupons that are currently valid for use.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card bg-danger-subtle">
                    <div class="card-body">
                        <h4 class=" mb-1">{{ $expiredCoupons }} Expired Coupons</h4>
                        <p class="">Coupons that are no longer valid.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="card-title">All Coupons List</h4>
                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-sm btn-success">
                            <i class="bx bx-plus"></i> New Coupon
                        </a>
                    </div>
                    <div class="card-body">

                        <div id="table-coupons-gridjs"></div>
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
            if (document.getElementById("table-coupons-gridjs")) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                new gridjs.Grid({
                    columns: [
                        { id: 'id', name: 'ID' },
                        { id: 'code', name: 'Code',
                            attributes: () => ({
                                style: ' min-width: 100px; max-width: 100px;'
                            }) },
                        { id: 'type', name: 'Type' },
                        {
                            id: 'value',
                            name: 'Value',
                            formatter: (cell, row) => {
                                const type = row.cells[2].data;
                                if (type === 'percentage') {
                                    return `${cell}%`;
                                }
                                return `${parseFloat(cell).toLocaleString('vi-VN')} VNĐ`;
                            }
                        },
                        { id: 'usage', name: 'Usage (Used/Max)',
                            attributes: () => ({
                                style: ' min-width: 40px; max-width: 40px;'
                            })
                        },
                        { id: 'dates', name: 'Effective Dates' },
                        {
                            id: 'is_active',
                            name: 'Status',
                            formatter: (cell) => cell
                                ? gridjs.html('<span class="badge bg-success">Active</span>')
                                : gridjs.html('<span class="badge bg-secondary">Inactive</span>')
                        },
                        {
                            name: 'Actions',
                            width: '80px',
                            sort: false,
                            formatter: (cell, row) => {
                                const couponId = row.cells[0].data;
                                const couponName=row.cells[2].data;

                                const editUrl = `/admin/coupons/${couponId}/edit`;
                                const deleteUrl = `/admin/coupons/${couponId}`;
                                return gridjs.html(`
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                            <li>
                                                                <a class="dropdown-item" href="${editUrl}">
                                                                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <a class="dropdown-item text-danger delete-item" href="#"
                                                                       data-id="${couponId}"
                                                                       data-name="${couponName}"
                                                                       data-url="${deleteUrl}">
                                                                    <i class="bi bi-trash me-2"></i>Delete
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>`
                                );
                            }
                        },
                    ],
                    server: {
                        url: '{{ route('admin.api.coupons.data') }}',
                        then: results => results.data,
                    },
                    sort: true,
                    search: true,
                    pagination: {
                        limit: 10
                    },
                }).render(document.getElementById("table-coupons-gridjs"));
            }
        });

        AdminCRUD.initDeleteHandler('.delete-item', {
            confirmTitle: 'Delete Coupon?',
            confirmText: 'You are about to delete coupon:',
            successText: 'Coupon deleted successfully.',
            onSuccess: () => {
                // Custom callback nếu cần
                console.log('Coupon deleted!');
                location.reload();
            }
        });
    </script>
    <!-- @formatter:on -->
@endpush
