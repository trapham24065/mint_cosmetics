@extends('admin.layouts.app')
@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">Chatbot Training</h4>
                <a href="{{ route('admin.chatbot.create') }}" class="btn btn-primary">Add New Rule</a>
            </div>
            <div class="card-body">
                <div id="table-data-chatbot"></div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- @formatter:off -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById("table-data-chatbot")) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                new gridjs.Grid({
                    columns: [
                        { id: 'id', name: 'ID', width: '80px' },
                        { id: 'keyword', name: 'Keyword' },
                        { id: 'reply', name: 'Slug' },
                        {
                            id: 'is_active',
                            name: 'Status',
                            formatter: (cell) => {
                                return cell
                                    ? gridjs.html('<span class="badge bg-success">Active</span>')
                                    : gridjs.html('<span class="badge bg-secondary">Inactive</span>');
                            }
                        },
                        {
                            name: 'Actions',
                            width: '150px',
                            formatter: (cell, row) => {
                                const chatbotID = row.cells[0].data;
                                const editUrl = `{{ route('admin.chatbot.index') }}/${chatbotID}/edit`;
                                const deleteUrl = `{{ route('admin.chatbot.index') }}/${chatbotID}`;

                                return gridjs.html(`
                                <div class="d-flex gap-2">

                                    <a href="${editUrl}" class="btn btn-sm btn-primary" aria-label="Edit chat box rule ${chatbotID}"> <i class="bi bi-pencil-square"></i></iconify-icon></a>

                                    <form action="${deleteUrl}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-sm btn-danger" aria-label="Delete chat box rule ${chatbotID}"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>`
                                );
                            }
                        }
                    ],
                    server: {
                        url: '{{ route('admin.api.chatbot.data') }}',
                        then: results => results.data,
                    },
                    sort: true,
                    search: true,
                    pagination: {
                        limit: 10
                    },
                }).render(document.getElementById("table-data-chatbot"));
            }
        });
    </script>
    <!-- @formatter:on -->

@endpush
