@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Brand: {{ $brand->name }}</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.brands.update', $brand) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    @include('admin.management.brands._form')

                    <div class="text-end mt-4">
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
