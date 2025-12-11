@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-18 mb-0">Edit Supplier: {{ $supplier->name }}</h2>
    </div>

    <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST">
        @method('PUT')
        @include('admin.management.supplier._form', ['buttonText' => 'Update Supplier'])
    </form>
@endsection
