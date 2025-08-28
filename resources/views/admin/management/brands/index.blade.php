@extends('admin.layouts.app')
@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="card-title">All Brands List</h4>
                        <a href="{{ route('admin.brands.create') }}" class="btn btn-sm btn-success">
                            <i class="bx bx-plus"></i> New Brand
                        </a>
                    </div>
                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                <tr>
                                    <th>ID</th>
                                    <th>Logo</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($brands as $brand)
                                    <tr>
                                        <td>{{ $brand->id }}</td>
                                        <td>
                                            <img
                                                src="{{ $brand->logo ? asset('storage/' . $brand->logo) : asset('assets/admin/images/default-brand.png') }}"
                                                alt="{{ $brand->name }}" class="avatar-sm">
                                        </td>
                                        <td>{{ $brand->name }}</td>
                                        <td>{{ $brand->slug }}</td>
                                        <td>
                                            @if ($brand->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.brands.show', $brand) }}"
                                               class="btn btn-sm btn-soft-info">View</a>
                                            <a href="{{ route('admin.brands.edit', $brand) }}"
                                               class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No coupons found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($brands->hasPages())
                        <div class="card-footer border-top">
                            <nav aria-label="Page navigation">
                                {{ $brands->links() }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
