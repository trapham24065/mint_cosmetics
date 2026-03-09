@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Tạo nhà cung cấp mới</h2>
            </div>
            <form action="{{ route('admin.suppliers.store') }}" method="POST">
                @include('admin.management.supplier._form', ['buttonText' => 'Tạo nhà cung cấp'])
            </form>
        </div>
    </div>
@endsection
