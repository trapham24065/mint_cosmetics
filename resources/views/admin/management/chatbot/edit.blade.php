@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Chatbot Rule</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.chatbot.update', $rule) }}" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Include the shared form file. The $rule variable will be automatically passed in. --}}
                    @include('admin.management.chatbot._form')

                    <div class="text-end mt-4">
                        <a href="{{ route('admin.chatbot.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
