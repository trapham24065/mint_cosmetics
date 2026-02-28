@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <form method="POST" action="{{ route('admin.attributes.store') }} " novalidate>
            @csrf
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">New Attribute</h4>
                            <p class="text-muted">
                                Provide a name for the attribute and add one or more values.
                            </p>
                        </div>
                        <div class="card-footer border-top">
                            <div class="row g-2">
                                <div class="col-lg-6">
                                    <button type="submit" class="btn btn-primary w-100">Create Attribute</button>
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
                                       placeholder="e.g., Color, Size, RAM" value="{{ old('name') }}" required>
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
                                <i class="fas fa-plus me-1"></i> Add Value
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="values-container">
                            </div>
                            @error('values.*')
                            <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Add this script section for dynamic inputs --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const addValueBtn = document.getElementById('add-value-btn');
                const valuesContainer = document.getElementById('values-container');

                addValueBtn.addEventListener('click', function() {
                    addValueInput();
                });

                // Function to add a new value input row
                function addValueInput () {
                    const newRow = document.createElement('div');
                    newRow.classList.add('input-group', 'mb-2');

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.name = 'values[]';
                    input.className = 'form-control';
                    input.placeholder = 'Enter a value (e.g., Red, Small, 8GB)';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-outline-danger';
                    removeBtn.innerHTML = '<iconify-icon icon="solar:close-circle-bold-duotone" width="18"></iconify-icon>';
                    removeBtn.addEventListener('click', function() {
                        newRow.remove();
                    });

                    newRow.appendChild(input);
                    newRow.appendChild(removeBtn);
                    valuesContainer.appendChild(newRow);
                }

                // Add one input row by default when the page loads
                addValueInput();
            });
        </script>
    @endpush
@endsection
