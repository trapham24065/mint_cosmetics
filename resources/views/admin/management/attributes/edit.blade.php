@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <form method="POST" action="{{ route('admin.attributes.update', $attribute) }} " novalidate>
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Edit Attribute</h4>
                            <p class="text-primary fw-bold">{{ $attribute->name }}</p>
                            <p class="text-muted">
                                Update the attribute name and manage its values.
                            </p>
                        </div>
                        <div class="card-footer border-top">
                            <div class="row g-2">
                                <div class="col-lg-6">
                                    <button type="submit" class="btn btn-primary w-100">Update Attribute</button>
                                </div>
                                <div class="col-lg-6">
                                    <a href="{{ route('admin.attributes.index') }}"
                                       class="btn btn-outline-secondary w-100">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">General Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="attribute-name" class="form-label">Attribute Name</label>
                                <input type="text" id="attribute-name" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="e.g., Color, Size, RAM" value="{{ old('name', $attribute->name) }}"
                                       required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Attribute Values</h4>
                            <button type="button" id="add-value-btn" class="btn btn-sm btn-soft-primary">
                                <i class="fas fa-plus me-1"></i> Add New Value
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="values-container">
                                {{-- Display existing values --}}
                                @foreach($attribute->values as $value)
                                    <div class="input-group mb-2">
                                        <input type="text" name="values[{{ $value->id }}]" class="form-control"
                                               value="{{ old('values.'.$value->id, $value->value) }}" required>
                                        <button type="button" class="btn btn-outline-danger"
                                                onclick="this.parentElement.remove()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <div id="new-values-container">
                                {{-- Dynamic new value inputs will be added here --}}
                            </div>
                            @error('values.*')
                            <div class="text-danger mt-2">{{ $message }}</div> @enderror
                            @error('new_values.*')
                            <div class="text-danger mt-2">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const addValueBtn = document.getElementById('add-value-btn');
                const newValuesContainer = document.getElementById('new-values-container');

                addValueBtn.addEventListener('click', function() {
                    const newRow = document.createElement('div');
                    newRow.classList.add('input-group', 'mb-2');

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.name = 'new_values[]'; // Note: new_values[]
                    input.className = 'form-control';
                    input.placeholder = 'Enter a new value';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-outline-danger';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    removeBtn.addEventListener('click', function() {
                        newRow.remove();
                    });

                    newRow.appendChild(input);
                    newRow.appendChild(removeBtn);
                    newValuesContainer.appendChild(newRow);
                });
            });
        </script>
    @endpush
@endsection
