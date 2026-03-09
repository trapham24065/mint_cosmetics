@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Tạo phiếu giảm giá mới</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.coupons.store') }}" novalidate>
                    @csrf
                    @include('admin.management.coupons._form')

                    <div class="text-end">
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                        <button type="submit" class="btn btn-primary">Tạo mã giảm giá</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
