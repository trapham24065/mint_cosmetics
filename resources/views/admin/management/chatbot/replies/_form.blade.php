<div class="row">
    <!-- Topic -->
    <div class="col-12 mb-3">
        <label for="topic" class="form-label">Đề tài</label>
        <input type="text" id="topic" name="topic"
               class="form-control @error('topic') is-invalid @enderror"
               value="{{ old('topic', $reply->topic ?? '') }}"
               placeholder="e.g., Shipping Policy" required>
        <small class="text-muted">Một tiêu đề ngắn gọn, dễ hiểu cho câu trả lời này.</small>
    </div>

    <!-- Reply Content -->
    <div class="col-12 mb-3">
        <label for="reply" class="form-label">Phản hồi của Bot</label>
        <textarea id="reply" name="reply" class="form-control @error('reply') is-invalid @enderror"
                  rows="5" placeholder="Câu trả lời đầy đủ mà bot nên đưa ra."
                  required>{{ old('reply', $reply->reply ?? '') }}</textarea>
    </div>

    <!-- Active Status -->
    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="is_active"
                   name="is_active" value="1"
                @checked(old('is_active', $reply->is_active ?? true))>
            <label class="form-check-label" for="is_active">Hoạt động</label>
        </div>
    </div>
</div>
