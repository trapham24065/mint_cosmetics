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
                            attributes: () => ({ style: 'min-width:120px;' }),
                            formatter: (cell, row) => {
                                const couponId = row.cells[0].data;
                                const editUrl = `/admin/coupons/${couponId}/edit`;
                                const deleteUrl = `/admin/coupons/${couponId}`;

                                return gridjs.html(`
                        <div class="d-flex gap-2">
                            <a href="${editUrl}" class="btn btn-sm btn-primary" aria-label="Edit coupon ${couponId}">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="${deleteUrl}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-danger" aria-label="Delete coupon ${couponId}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
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
    </script>
    <!-- @formatter:on -->
@endpush
