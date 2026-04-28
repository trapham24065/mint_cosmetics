@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        @php
            $isProcessed = $contactMessage->processed_at !== null;
            $fullName = trim($contactMessage->first_name . ' ' . $contactMessage->last_name) ?: 'N/A';
        @endphp

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h4 class="mb-1">Chi tiết liên hệ #{{ $contactMessage->id }}</h4>
                <div class="text-muted small">Xem nhanh thông tin người gửi, trạng thái xử lý và nội dung liên hệ.</div>
            </div>
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>

        <div class="card border-0 shadow-sm mb-4 overflow-hidden">
            <div class="card-body p-4 p-lg-5 bg-body-tertiary">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div>
                        <div class="text-uppercase small text-muted mb-2">Người gửi</div>
                        <h3 class="mb-2">{{ $fullName }}</h3>
                        <div class="d-flex flex-wrap gap-3 text-muted small">
                            <span><i class="bi bi-envelope me-1"></i>{{ $contactMessage->email }}</span>
                            <span><i class="bi bi-calendar3 me-1"></i>{{ $contactMessage->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>

                    <div class="text-lg-end">
                        <div class="text-uppercase small text-muted mb-2">Trạng thái</div>
                        @if($isProcessed)
                            <span class="badge bg-success-subtle text-success px-3 py-2 fs-6">
                        <i class="bi bi-check-circle me-1"></i> Đã xử lý
                    </span>
                            <div class="small text-muted mt-2">
                                @if($contactMessage->processedBy)
                                    <div><i class="bi bi-person me-1"></i>{{ $contactMessage->processedBy->name }}</div>
                                @endif
                                <div>
                                    <i class="bi bi-clock me-1"></i>{{ $contactMessage->processed_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        @else
                            <span class="badge bg-warning-subtle text-warning px-3 py-2 fs-6">
                        <i class="bi bi-exclamation-circle me-1"></i> Chưa xử lý
                    </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="fw-semibold mb-3">Thông tin liên hệ</div>
                        <div class="d-grid gap-3">
                            <div>
                                <div class="text-muted small mb-1">Họ tên</div>
                                <div class="fw-semibold">{{ $fullName }}</div>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">Email</div>
                                <div class="fw-semibold text-break">{{ $contactMessage->email }}</div>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">Thời gian gửi</div>
                                <div class="fw-semibold">{{ $contactMessage->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="fw-semibold mb-3">Nội dung liên hệ</div>
                        <div class="p-3 p-lg-4 rounded-3 border bg-light" style="white-space: pre-line;">
                            {{ $contactMessage->message }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(!$isProcessed)
            <div class="mt-4">
                <form action="{{ route('admin.contacts.markProcessed', $contactMessage) }}" method="POST"
                      class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-check2-circle me-1"></i>
                        Đánh dấu đã xử lý
                    </button>
                </form>
            </div>
        @endif

        {{-- REPLY FORM --}}
        <div class="mt-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-reply-all me-2"></i>Phản hồi qua email</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Có lỗi xảy ra:</strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.contacts.reply', $contactMessage) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Gửi tới: <span
                                    class="text-primary">{{ $contactMessage->email }}</span></label>
                        </div>

                        <div class="mb-3">
                            <label for="reply_message" class="form-label fw-semibold">Nội dung phản hồi</label>
                            <textarea
                                class="form-control @error('reply_message') is-invalid @enderror"
                                id="reply_message"
                                name="reply_message"
                                rows="6"
                                placeholder="Nhập nội dung phản hồi cho khách hàng..."
                                required>{{ old('reply_message') }}</textarea>
                            @error('reply_message')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Tối thiểu 10 ký tự, tối đa 5000 ký tự
                            </small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-send me-1"></i>
                                Gửi phản hồi
                            </button>
                            <button type="reset" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                Xóa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
