@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Create New Brand</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.brands.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Include the shared form partial --}}
                    @include('admin.management.brands._form')

                    <div class="text-end mt-4">
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Brand</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
