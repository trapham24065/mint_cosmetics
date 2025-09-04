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
                                    src="{{ $category->image ? asset('storage/' . $category->image) : asset('assets/admin/images/default-category.png') }}"
                                    alt="{{ $category->name }}" style="height: 80px; width: 80px;">
                                {{-- Placeholder Icon, you can add dynamic images later if available --}}

                            </div>
                            <h5 class="mt-3 mb-0">{{ $category->name }}</h5>
                            <small class="text-muted">{{ $category->created_at->format('Y-m-d') }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">All Categories ({{ $totalCategories }})</h4>

                        <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Add Category
                        </a>
                    </div>
                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>
                                            <p class="text-dark fw-medium fs-15 mb-0">{{ $category->name }}</p>
                                        </td>
                                        <td>
                                            <img
                                                src="{{ $category->image ? asset('storage/' . $category->image) : asset('assets/admin/images/default-category.png') }}"
                                                alt="{{ $category->name }}" class="avatar-sm">
                                        </td>
                                        <td>{{ $category->slug }}</td>
                                        <td>
                                            @if ($category->active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $category->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.categories.edit', $category) }}"
                                                   class="btn btn-soft-primary btn-sm">
                                                    <iconify-icon icon="solar:pen-2-broken"
                                                                  class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                <form action="{{ route('admin.categories.destroy', $category) }}"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                                      class="align-middle fs-18"></iconify-icon>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No categories found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($categories->hasPages())
                        <div class="card-footer border-top">
                            <nav>
                                {{ $categories->appends(request()->query())->links('vendor.pagination.admin-paginnation') }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
