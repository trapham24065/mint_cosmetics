@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Attribute Details</h4>
                    </div>
                    <div class="card-body">
                        <h5>{{ $attribute->name }}</h5>
                        <p class="text-muted">ID: {{ $attribute->id }}</p>

                        <hr>
                        <strong>Values:</strong>
                        <p>
                            @forelse($attribute->values as $value)
                                <span class="badge bg-light text-dark">{{ $value->value }}</span>
                            @empty
                                <span class="text-muted">No values defined.</span>
                            @endforelse
                        </p>

                        <strong>Linked to {{ $attribute->categories->count() }} Categories:</strong>
                        <ul>
                            @forelse($attribute->categories as $category)
                                <li>{{ $category->name }}</li>
                            @empty
                                <li>Not linked to any category.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-secondary w-100">Back to
                            List</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Products Using '{{ $attribute->name }}'</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="bg-light-subtle">
                                <tr>
                                    <th>ID</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>
                                            @if ($product->active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No products are currently using this
                                            attribute.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($products->hasPages())
                        <div class="card-footer border-top">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
