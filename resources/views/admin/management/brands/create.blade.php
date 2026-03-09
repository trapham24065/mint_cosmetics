@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Tạo thương hiệu mới</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.brands.store') }}" enctype="multipart/form-data" novalidate>
                    @csrf

                    {{-- Include the shared form partial --}}
                    @include('admin.management.brands._form')

                    <div class="text-end mt-4">
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                        <button type="submit" class="btn btn-primary">Tạo thương hiệu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
