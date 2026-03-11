@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">

        <div class="row">
            @foreach ($latestCategories as $category)
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <div
                                class="rounded bg-secondary-subtle d-flex align-items-center justify-content-center mx-auto"
                                style="height: 80px; width: 80px;">
                                <img
                                    src="{{ $category->image ? asset('storage/' . $category->image) : asset('assets/admin/images/default.webp') }}"
                                    alt="{{ $category->name }}" style="height: 80px; width: 80px;">
                                {{-- Placeholder Icon, you can add dynamic images later if available --}}

                            </div>
                            <h5 class="mt-3 mb-0">{{ $category->name }}</h5>
                            <small class="text-muted">{{ $category->created_at->format('d/m/Y') }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">Tất cả các danh mục ({{ $totalCategories }})</h4>

                        <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Thêm danh mục
                        </a>
                    </div>
                    <div>
                        <div class="card-body">
                            <div id="table-data-categoies"></div>
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
            if (document.getElementById("table-data-categoies")) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                new gridjs.Grid({
                    columns: [
                        { id: 'id', name: 'ID', width: '80px' },
                        {
                            id: 'image',
                            name: 'Hình ảnh',
                            width: '100px',
                            formatter: (cell) => {
                                const imageUrl = cell ? `{{ asset('storage') }}/${cell}` : `{{ asset('assets/admin/images/default.webp') }}`;
                                return gridjs.html(`<img src="${imageUrl}" alt="Category" class="avatar-sm">`);
                            }
                        },
                        { id: 'name', name: 'Tên' },
                        { id: 'slug', name: 'Slug' },
                        {
                            id: 'is_active',
                            name: 'Trạng thái',
                            formatter: (cell) => {
                                return cell
                                    ? gridjs.html('<span class="badge bg-success">Hoạt động</span>')
                                    : gridjs.html('<span class="badge bg-secondary">Không hoạt động</span>');
                            }
                        },
                        { id: 'created_at', name: 'Được tạo vào lúc' },
                        {
                            name: 'Hành động',
                            width: '80px',
                            sort: false,
                            formatter: (cell, row) => {
                                const categoryId = row.cells[0].data;
                                const categoryName=row.cells[3].data;

                                const showUrl = `{{ url('admin/categories') }}/${categoryId}`;
                                const editUrl = `{{ url('admin/categories') }}/${categoryId}/edit`;
                                const deleteUrl = `{{ url('admin/categories') }}/${categoryId}`;
                                return gridjs.html(`
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                            <li>
                                                                <a class="dropdown-item" href="${showUrl}">
                                                                    <i class="bi bi-eye me-2 text-info"></i>Xem chi tiết
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
                                                                       data-id="${categoryId}"
                                                                       data-name="${categoryName}"
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
                        url: '{{ route('admin.api.categories.data') }}',
                        then: results => results.data,
                    },
                    sort: true,
                    search: {
                        enabled: true,
                        selector: (cell, rowIndex, cellIndex) => {
                            if (cellIndex === 1 || cellIndex === 6) {
                                return '';
                            }
                            if (cellIndex === 4) {
                                return cell ? 'active' : 'inactive';
                            }
                            return cell ? cell.toString() : '';
                        }
                    },
                    pagination: {
                        limit: 10
                    },
                }).render(document.getElementById("table-data-categoies"));
            }
        });

        AdminCRUD.initDeleteHandler('.delete-item', {
            confirmTitle: 'Xóa danh mục?',
            confirmText: 'Bạn sắp xóa danh mục:',
            successText: 'Danh mục đã được xóa thành công.',
            onSuccess: () => {
                location.reload();
            }
        });

    </script>
    <!-- @formatter:on -->

@endpush
