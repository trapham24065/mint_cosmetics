@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Chỉnh sửa câu</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.chatbot.update', $chatbot->id) }}" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Include the shared form file. The $rule variable will be automatically passed in. --}}
                    @include('admin.management.chatbot._form')

                    <div class="text-end mt-4">
                        <a href="{{ route('admin.chatbot.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
