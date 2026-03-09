@extends('admin.layouts.app')
@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="card-title">Tất cả các thuộc tính</h4>
                        {{-- Add a "Create" button --}}
                        <a href="{{ route('admin.attributes.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Thêm thuộc tính
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
                        { id: 'name', name: 'Tên' },
                        { id: 'values', name: 'Giá trị' },
                        {id: 'created_at', name: 'Được tạo vào lúc'},
                        {
                            name: 'Hành động',
                            width: '80px',
                            sort: false,
                            formatter: (cell, row) => {
                                const attributeId = row.cells[0].data;
                                const attributeName=row.cells[2].data;

                                const showUrl = `{{ route('admin.attributes.index') }}/${attributeId}`;
                                const editUrl = `{{ route('admin.attributes.index') }}/${attributeId}/edit`;
                                const deleteUrl = `{{ route('admin.attributes.index') }}/${attributeId}`;
                                return gridjs.html(`
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                            <li>
                                                                <a class="dropdown-item" href="${showUrl}">
                                                                    <i class="bi bi-eye me-2 text-info"></i>Xem
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="${editUrl}">
                                                                    <i class="bi bi-pencil-square me-2 text-primary"></i>Chỉnh sửa
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <a class="dropdown-item text-danger delete-item" href="#"
                                                                       data-id="${attributeId}"
                                                                       data-name="${attributeName}"
                                                                       data-url="${deleteUrl}">
                                                                    <i class="bi bi-trash me-2"></i>Xóa
                                                                </a>
                                                            </li>
                                                        </ul>
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
        AdminCRUD.initDeleteHandler('.delete-item', {
            confirmTitle: 'Xóa thuộc tính?',
            confirmText: 'Bạn sắp xóa thuộc tính:',
            successText: 'Thuộc tính đã được xóa thành công.',
            onSuccess: () => {
                // Custom callback nếu cần
                console.log('Attribute deleted!');
                location.reload();
            }
        });

    </script>
    <!-- @formatter:on -->

@endpush
