@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <form method="POST" action="{{ route('admin.chatbot-replies.store') }}">
            @csrf
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Create New Chatbot Reply</h4>
                    <div>
                        <a href="{{ route('admin.chatbot-replies.index') }}"
                           class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Reply</button>
                    </div>
                </div>
                <div class="card-body">
                    @include('admin.management.chatbot.replies._form')
                </div>
            </div>
        </form>
    </div>
@endsection
