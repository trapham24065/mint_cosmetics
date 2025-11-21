@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Chatbot Training Center - Replies</h4>
                <a href="{{ route('admin.chatbot-replies.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus me-1"></i> Add New Reply
                </a>
            </div>
            <div class="card-body">
                <div id="table-data-replies"></div>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- @formatter:off -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById("table-data-replies")) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                new gridjs.Grid({
                    columns: [
                        { id: 'id', name: 'ID', width: '80px' },
                        {id:'topic', name: 'Topic'},
                        { id:'reply', name: 'Reply', formatter: (cell) => {
                                return cell.length > 70 ? cell.substring(0, 70) + '...' : cell;
                            }},
                        {
                            id:'keywords',
                            name: 'Keywords',
                            sort: false,
                            formatter: (cell) => {
                                if (!cell) return '';
                                const keywords = cell.split(', ');
                                const badgesHtml = keywords.map(keyword =>
                                    `<span class="badge bg-light text-dark me-1">${keyword.trim()}</span>`
                                ).join('');
                                return gridjs.html(badgesHtml);
                            }
                        },
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
                                const chat_bot_repliesName=row.cells[1].data;

                                const chat_bot_repliesId = row.cells[0].data;
                                const editUrl = `{{ route('admin.chatbot-replies.index') }}/${chat_bot_repliesId}/edit`;
                                const deleteUrl = `{{ route('admin.chatbot-replies.index') }}/${chat_bot_repliesId}`;

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
                                                                       data-id="${chat_bot_repliesId}"
                                                                       data-name="${chat_bot_repliesName}"
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
                        url: '{{ route('admin.api.chatbot-replies.data') }}',
                        then: results => results.data,
                    },
                    sort: true,
                    search: true,
                    pagination: {
                        limit: 10
                    },
                }).render(document.getElementById("table-data-replies"));
            }
        });

        AdminCRUD.initDeleteHandler('.delete-item', {
            confirmTitle: 'Delete Replies?',
            confirmText: 'You are about to delete replies:',
            successText: 'replies deleted successfully.',
            onSuccess: () => {
                console.log('replies deleted!');
                location.reload();
            }
        });
    </script>
    <!-- @formatter:on -->

@endpush
