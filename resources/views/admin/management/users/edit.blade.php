@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Chỉnh sửa tài khoản: {{ $user->name }}</h2>
            </div>
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @method('PUT')
                @include('admin.management.users._form', ['buttonText' => 'Cập nhật tài khoản'])
            </form>
        </div>
    </div>
@endsection

