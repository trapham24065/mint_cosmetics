@extends('admin.layouts.app')

@section('content')
    <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.management.blog._form', ['post' => null])
    </form>
@endsection
