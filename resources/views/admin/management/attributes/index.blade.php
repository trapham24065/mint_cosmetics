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
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                <tr>
                                    <th>ID</th>
                                    <th>Attribute Name</th>
                                    <th>Values</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($attributes as $attribute)
                                    <tr>
                                        <td>{{ $attribute->id }}</td>
                                        <td>
                                            <p class="text-dark fw-medium fs-15 mb-0">{{ $attribute->name }}</p>
                                        </td>
                                        <td>
                                            {{-- Use Collection methods to cleanly display values --}}
                                            {{ $attribute->values->pluck('value')->implode(', ') }}
                                        </td>
                                        <td>{{ $attribute->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.attributes.show', $attribute) }}"
                                                   class="btn btn-soft-info btn-sm">
                                                    <iconify-icon icon="solar:eye-broken"
                                                                  class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                {{-- Update these routes when you build the edit/delete functionality --}}
                                                <a href="{{ route('admin.attributes.edit', $attribute) }}"
                                                   class="btn btn-soft-primary btn-sm">
                                                    <iconify-icon icon="solar:pen-2-broken"
                                                                  class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                <form action="{{ route('admin.attributes.destroy', $attribute) }}"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this attribute and all its values?');">
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
                                        <td colspan="5" class="text-center">No attributes found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($attributes->hasPages())
                        <div class="card-footer border-top">
                            <nav>
                                {{ $attributes->appends(request()->query())->links('vendor.pagination.admin-paginnation') }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
