<div class="row">
    <!-- Keyword -->
    <div class="col-12 mb-3">
        <label for="keyword" class="form-label">Keyword / Question</label>
        <input type="text" id="keyword" name="keyword"
               class="form-control @error('keyword') is-invalid @enderror"
               value="{{ old('keyword', $rule->keyword ?? '') }}"
               placeholder="Enter the keyword or question the user might ask" required>
        @error('keyword')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Reply -->
    <div class="col-12 mb-3">
        <label for="reply" class="form-label">Bot's Reply</label>
        <textarea id="reply" name="reply" class="form-control @error('reply') is-invalid @enderror"
                  rows="5" placeholder="Enter the answer the bot should give"
                  required>{{ old('reply', $rule->reply ?? '') }}</textarea>
        @error('reply')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Active Status -->
    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="is_active"
                   name="is_active" value="1"
                @checked(old('is_active', $rule->is_active ?? true))>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
    </div>
</div>
