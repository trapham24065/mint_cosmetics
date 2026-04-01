<div class="row">
    <!-- Keyword -->
    <div class="col-12 mb-3">
        <label for="keyword" class="form-label">Từ khóa / Câu hỏi</label>
        <input type="text" id="keyword" name="keyword"
               class="form-control @error('keyword') is-invalid @enderror"
               value="{{ old('keyword', $chatbot->keyword ?? '') }}"
               placeholder="Nhập từ khóa hoặc câu hỏi mà người dùng có thể hỏi." required>
        @error('keyword')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Reply -->
    <div class="col-12 mb-3">
        <label for="reply" class="form-label">Phản hồi của Bot</label>
        <textarea id="reply" name="reply" class="form-control @error('reply') is-invalid @enderror"
                  rows="5" placeholder="Nhập câu trả lời mà bot nên đưa ra."
                  required>{{ old('reply', $chatbot->reply ?? '') }}</textarea>
        @error('reply')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Active Status -->
    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="is_active"
                   name="is_active" value="1"
                @checked(old('is_active', $chatbot->is_active ?? true))>
            <label class="form-check-label" for="is_active">Hoạt động</label>
        </div>
    </div>
</div>
