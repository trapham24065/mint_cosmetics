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
                            width: '150px',
                            formatter: (cell, row) => {
                                const chat_bot_repliesID = row.cells[0].data;
                                const editUrl = `{{ route('admin.chatbot-replies.index') }}/${chat_bot_repliesID}/edit`;
                                const deleteUrl = `{{ route('admin.chatbot-replies.index') }}/${chat_bot_repliesID}`;

                                return gridjs.html(`
                                <div class="d-flex gap-2">
                                    <a href="${editUrl}" class="btn btn-sm btn-primary" aria-label="Edit chat bot replies ${chat_bot_repliesID}"> <i class="bi bi-pencil-square"></i></iconify-icon></a>

                                    <form action="${deleteUrl}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-sm btn-danger" aria-label="Delete chat bot replies ${chat_bot_repliesID}"><i class="bi bi-trash"></i></button>
                                    </form>
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
    </script>
    <!-- @formatter:on -->

@endpush
