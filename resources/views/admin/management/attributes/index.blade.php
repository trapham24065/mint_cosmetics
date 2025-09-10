@extends('admin.layouts.app')
@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="card-title">All Attributes</h4>
                        {{-- Add a "Create" button --}}
                        <a href="{{ route('admin.attributes.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Add Attribute
                        </a>
                    </div>
                    <div>
                        <div class="card-body">
                            <div id="table-data-attributes"></div>
                        </div>
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
            if (document.getElementById("table-data-attributes")) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                new gridjs.Grid({
                    columns: [
                        { id: 'id', name: 'ID', width: '80px' },
                        { id: 'name', name: 'Name' },
                        { id: 'values', name: 'Values' },
                        {id: 'created_at', name: 'Created At'},
                        {
                            name: 'Actions',
                            width: '150px',
                            formatter: (cell, row) => {
                                const attributeId = row.cells[0].data;
                                const showUrl = `{{ route('admin.attributes.index') }}/${attributeId}`;
                                const editUrl = `{{ route('admin.attributes.index') }}/${attributeId}/edit`;
                                const deleteUrl = `{{ route('admin.attributes.index') }}/${attributeId}`;

                                return gridjs.html(`
                                <div class="d-flex gap-2">
                                    <a href="${showUrl}" class="btn btn-sm btn-soft-info" aria-label="View attribute ${attributeId}"><i class="bi bi-eye"></i></a>
                                    <a href="${editUrl}" class="btn btn-sm btn-primary" aria-label="Edit attribute ${attributeId}"><i class="bi bi-pencil-square"></i></a>

                                    <form action="${deleteUrl}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-sm btn-danger" aria-label="Delete attribute ${attributeId}"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>`
                                );
                            }
                        }
                    ],
                    server: {
                        url: '{{ route('admin.api.attributes.data') }}',
                        then: results => results.data,
                    },
                    sort: true,
                    search: true,
                    pagination: {
                        limit: 10
                    },
                }).render(document.getElementById("table-data-attributes"));
            }
        });
    </script>
    <!-- @formatter:on -->

@endpush
