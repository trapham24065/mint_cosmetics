@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="d-flex card-header justify-content-between align-items-center">
                <h4 class="card-title">{{ $title }}</h4>
                <a href="{{ route('admin.inventory.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i> Create Purchase Order
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger m-3">{{ session('error') }}</div>
            @endif

            <div class="card-body">
                {{-- Grid.js Container --}}
                <div id="table-inventory-gridjs"></div>
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
                            name: 'Code',
                            formatter: (cell, row) => {
                                const id = row.cells[0].data; // Lấy ID từ cột ẩn
                                const url = `{{ url('admin/inventory') }}/${id}`;
                                return gridjs.html(`<a href="${url}" class="fw-bold text-primary">${cell}</a>`);
                            }
                        },
                        { id: 'supplier_name', name: 'Supplier' },
                        {
                            id: 'total_amount',
                            name: 'Total Amount',
                            formatter: (cell) => gridjs.html(`<span class="fw-bold">${cell} VND</span>`)
                        },
                        {
                            id: 'status',
                            name: 'Status',
                            formatter: (cell) => {
                                if (cell === 'completed') {
                                    return gridjs.html('<span class="badge bg-success-subtle text-success">Completed</span>');
                                } else if (cell === 'cancelled') {
                                    return gridjs.html('<span class="badge bg-danger-subtle text-danger">Cancelled</span>');
                                } else {
                                    return gridjs.html('<span class="badge bg-warning-subtle text-warning">Pending</span>');
                                }
                            }
                        },
                        { id: 'created_at', name: 'Created At' },
                        {
                            name: 'Actions',
                            width: '100px',
                            sort: false,
                            formatter: (cell, row) => {
                                const id = row.cells[0].data; // Lấy ID
                                const showUrl = `{{ url('admin/inventory') }}/${id}`;

                                return gridjs.html(`
                                    <a href="${showUrl}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                `);
                            }
                        }
                    ],
                    server: {
                        url: '{{ route('admin.api.inventories.data') }}',
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
                    language: {
                        'search': { 'placeholder': 'Search orders...' },
                        'pagination': { 'previous': 'Prev', 'next': 'Next' }
                    }
                }).render(document.getElementById("table-inventory-gridjs"));
            }
        });
    </script>
    <!-- @formatter:on -->
@endpush
