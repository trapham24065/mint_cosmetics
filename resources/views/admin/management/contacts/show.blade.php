@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">

        {{-- ALERT --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Chi tiết liên hệ #{{ $contactMessage->id }}</h4>
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>

        {{-- CARD --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-4">

                    {{-- HỌ TÊN --}}
                    <div class="col-md-6">
                        <label class="text-muted small">Họ tên</label>
                        <div class="fw-semibold fs-6">
                            {{ trim($contactMessage->first_name . ' ' . $contactMessage->last_name) ?: 'N/A' }}
                        </div>
                    </div>

                    {{-- EMAIL --}}
                    <div class="col-md-6">
                        <label class="text-muted small">Email</label>
                        <div class="fw-semibold">
                            <i class="bi bi-envelope me-1 text-primary"></i>
                            {{ $contactMessage->email }}
                        </div>
                    </div>

                    {{-- THỜI GIAN --}}
                    <div class="col-md-6">
                        <label class="text-muted small">Thời gian gửi</label>
                        <div class="fw-semibold">
                            {{ $contactMessage->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    {{-- TRẠNG THÁI --}}
                    <div class="col-md-6">
                        <label class="text-muted small">Trạng thái</label>

                        @if($contactMessage->processed_at)
                            <div>
                            <span class="badge bg-success-subtle text-success px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i> Đã xử lý
                            </span>

                                <div class="small text-muted mt-2">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $contactMessage->processed_at->format('d/m/Y H:i') }}

                                    @if($contactMessage->processedBy)
                                        <span class="mx-1">|</span>
                                        <i class="bi bi-person me-1"></i>
                                        {{ $contactMessage->processedBy->name }}
                                    @endif
                                </div>
                            </div>
                        @else
                            <span class="badge bg-warning-subtle text-warning px-3 py-2">
                            <i class="bi bi-exclamation-circle me-1"></i> Chưa xử lý
                        </span>
                        @endif
                    </div>

                    {{-- NỘI DUNG --}}
                    <div class="col-12">
                        <label class="text-muted small">Nội dung liên hệ</label>
                        <div class="p-3 bg-light rounded border">
                            {{ $contactMessage->message }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ACTION --}}
        @if(!$contactMessage->processed_at)
            <div class="mt-3">
                <form action="{{ route('admin.contacts.markProcessed', $contactMessage) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-check2-circle me-1"></i>
                        Đánh dấu đã xử lý
                    </button>
                </form>
            </div>
        @endif

    </div>
@endsection
