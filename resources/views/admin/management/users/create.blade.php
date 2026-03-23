@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Tạo tài khoản mới</h2>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @include('admin.management.users._form', ['buttonText' => 'Tạo tài khoản'])
            </form>
        </div>
    </div>
@endsection

