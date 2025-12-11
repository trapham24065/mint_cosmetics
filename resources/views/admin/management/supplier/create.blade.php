@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-18 mb-0">Create New Supplier</h2>
    </div>

    <form action="{{ route('admin.suppliers.store') }}" method="POST">
        @include('admin.management.supplier._form', ['buttonText' => 'Create Supplier'])
    </form>
@endsection
