@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Thêm quy tắc Chatbot mới</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.chatbot.store') }}" novalidate>
                    @csrf

                    {{-- Includes shared form file --}}
                    @include('admin.management.chatbot._form')

                    <div class="text-end mt-4">
                        <a href="{{ route('admin.chatbot.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                        <button type="submit" class="btn btn-primary">Tạo quy tắc</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
