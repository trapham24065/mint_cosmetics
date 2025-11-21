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
                            width: '80px',
                            sort: false,
                            formatter: (cell, row) => {
                                const chatbotId = row.cells[0].data;
                                const chatbotName=row.cells[1].data;

                                const editUrl = `{{ route('admin.chatbot.index') }}/${chatbotId}/edit`;
                                const deleteUrl = `{{ route('admin.chatbot.index') }}/${chatbotId}`;
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
                                                                       data-id="${chatbotId}"
                                                                       data-name="${chatbotName}"
                                                                       data-url="${deleteUrl}">
                                                                    <i class="bi bi-trash me-2"></i>Delete
                                                                </a>
                                                            </li>
                                                        </ul>
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

        AdminCRUD.initDeleteHandler('.delete-item', {
            confirmTitle: 'Delete Chatbot?',
            confirmText: 'You are about to delete chatbot:',
            successText: 'Chatbot deleted successfully.',
            onSuccess: () => {
                // Custom callback nếu cần
                console.log('Chatbot deleted!');
                location.reload();
            }
        });
    </script>
    <!-- @formatter:on -->

@endpush
