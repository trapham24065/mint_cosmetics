@extends('admin.layouts.app')

@section('content')
<div class="container-xxl">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Chi tiet lien he #{{ $contactMessage->id }}</h4>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">Quay lai danh sach</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="text-muted d-block">Ho ten</label>
                    <div class="fw-semibold">{{ trim($contactMessage->first_name . ' ' . $contactMessage->last_name) ?: 'N/A' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted d-block">Email</label>
                    <div class="fw-semibold">{{ $contactMessage->email }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted d-block">Thoi gian gui</label>
                    <div class="fw-semibold">{{ $contactMessage->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted d-block">Trang thai</label>
                    @if($contactMessage->processed_at)
                    <div>
                        <span class="badge bg-success-subtle text-success">Da xu ly</span>
                        <div class="small text-muted mt-1">
                            Luc: {{ $contactMessage->processed_at->format('d/m/Y H:i') }}
                            @if($contactMessage->processedBy)
                            | Boi: {{ $contactMessage->processedBy->name }}
                            @endif
                        </div>
                    </div>
                    @else
                    <span class="badge bg-warning-subtle text-warning">Chua xu ly</span>
                    @endif
                </div>
                <div class="col-12">
                    <label class="text-muted d-block">Noi dung lien he</label>
                    <div class="p-3 bg-light rounded">{{ $contactMessage->message }}</div>
                </div>
            </div>
        </div>
    </div>

    @if(!$contactMessage->processed_at)
    <form action="{{ route('admin.contacts.markProcessed', $contactMessage) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">
            <i class="bi bi-check2-circle me-1"></i> Danh dau da xu ly
        </button>
    </form>
    @endif
</div>
@endsection