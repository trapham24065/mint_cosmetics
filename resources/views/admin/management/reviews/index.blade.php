@extends('admin.layouts.app')
@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Review Management</h4>
            </div>
            <div class="card-body">
                {{-- Grid.js will render the table here --}}
                <div id="table-reviews-gridjs"></div>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- @formatter:off -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById("table-reviews-gridjs")) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                new gridjs.Grid({
                    columns: [
                        { id: 'product_name', name: 'Product' },
                        { id: 'reviewer_name', name: 'Reviewer' },
                        {
                            id: 'rating',
                            name: 'Rating',
                            formatter: (cell) => {
                                let stars = '';
                                for (let i = 1; i <= 5; i++) {
                                    const starClass = i <= cell ? 'bxs-star text-warning' : 'bx-star text-muted';
                                    stars += `<i class="bx ${starClass}"></i>`;
                                }
                                return gridjs.html(stars);
                            }
                        },
                        { id: 'review', name: 'Review' },
                        {
                            id: 'is_approved',
                            name: 'Status',
                            formatter: (cell) => cell
                                ? gridjs.html('<span class="badge bg-success">Approved</span>')
                                : gridjs.html('<span class="badge bg-warning">Pending</span>')
                        },
                        {
                            name: 'Actions',
                            sort: false,
                            formatter: (cell, row) => {
                                const reviewId = row.cells[6].data;
                                const isApproved = row.cells[4].data;

                                let approveOrRejectButton;
                                if (!isApproved) {
                                    const approveUrl = `/admin/reviews/${reviewId}/approve`;
                                    approveOrRejectButton = `
                                    <form action="${approveUrl}" method="POST" class="d-inline">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="PUT">
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>`;
                                } else {
                                    const rejectUrl = `/admin/reviews/${reviewId}/reject`;
                                    approveOrRejectButton = `
                                    <form action="${rejectUrl}" method="POST" class="d-inline">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="PUT">
                                        <button type="submit" class="btn btn-sm btn-secondary">Reject</button>
                                    </form>`;
                                }

                                const deleteUrl = `/admin/reviews/${reviewId}`;
                                const deleteButton = `
                                <form action="${deleteUrl}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>`;

                                return gridjs.html(`<div class="d-flex gap-2">${approveOrRejectButton} ${deleteButton}</div>`);
                            }
                        },
                        { id: 'id', name: 'ID', hidden: true }
                    ],
                    server: {
                        url: '{{ route('admin.api.reviews.data') }}',
                        then: results => results.data.map(review => [
                            review.product_name,
                            review.reviewer_name,
                            review.rating,
                            review.review,
                            review.is_approved,
                            null, // Placeholder cho Actions
                            review.id
                        ])
                    },
                    search: true,
                    sort: true,
                    pagination: {
                        enabled: true,
                        limit: 10,
                    }
                }).render(document.getElementById("table-reviews-gridjs"));
            }
        });
    </script>
    <!-- @formatter:on -->

@endpush
