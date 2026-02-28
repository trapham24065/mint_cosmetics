@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Create New Supplier</h2>
            </div>
            <form action="{{ route('admin.suppliers.store') }}" method="POST">
                @include('admin.management.supplier._form', ['buttonText' => 'Create Supplier'])
            </form>
        </div>
    </div>
@endsection
