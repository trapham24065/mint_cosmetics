@extends('admin.layouts.app')

@section('content')
    <form action="{{ route('admin.blog.update', $blogPost) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.management.blog._form', ['post' => $blogPost])
    </form>
@endsection
