@extends('admin.layouts.app')
@section('content')
    <div class="container-xxl">

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">Quản lý trả hàng</h4>
                            <p class="text-muted mb-0">Danh sách yêu cầu trả hàng từ khách hàng</p>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- Grid.js will render the table here --}}
                        <div id="table-returns-gridjs"></div>
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
            if (document.getElementById("table-returns-gridjs")) {
                new gridjs.Grid({
                    columns: [
                        {
                            id: 'return_code',
                            name: 'Mã trả hàng',
                            formatter: (cell, row) => {
                                const returnId = row.cells[0].data;
                                const showUrl = `/admin/returns/${returnId}`;
                                return gridjs.html(`<a href="${showUrl}" class="fw-bold text-primary">${cell}</a>`);
                            }
                        },
                        { id: 'order_id', name: 'Đơn hàng' },
                        { id: 'customer', name: 'Khách hàng' },
                        {
                            id: 'reason',
                            name: 'Lý do',
                            width: '200px'
                        },
                        {
                            id: 'refund_amount',
                            name: 'Số tiền hoàn'
                        },
                        {
                            id: 'status',
                            name: 'Trạng thái',
                            formatter: (cell) => gridjs.html(cell)
                        },
                        { id: 'created_at', name: 'Ngày tạo' },
                        {
                            name: 'Hành động',
                            width: '120px',
                            formatter: (cell, row) => {
                                const returnID = row.cells[8].data;
                                const showUrl = `/admin/returns/${returnID}`;
                                return gridjs.html(`<a href="${showUrl}" class="btn btn-sm btn-light" aria-label="View return ${returnID}"><i class="bi bi-eye"></i></a>`);
                            }
                        },
                        { id: 'id', name: 'ID', hidden: true },
                        { id: 'actions', name: 'Actions HTML', hidden: true }
                    ],
                    server: {
                        url: '{{ route('admin.returns.data') }}',
                        then: results => results.data,
                    },
                    sort: true,
                    search: {
                        enabled: true,
                        placeholder: 'Tìm kiếm...'
                    },
                    pagination: {
                        limit: 10,
                        summary: true
                    },
                }).render(document.getElementById("table-returns-gridjs"));
            }
        });
    </script>
    <!-- @formatter:on -->
@endpush

