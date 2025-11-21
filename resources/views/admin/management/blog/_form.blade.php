@csrf
<div class="row">
    <div class="col-lg-8">
        {{-- Main Content --}}
        <div class="card bg-white border-0 rounded-10 mb-4">
            <div class="card-body p-4">
                {{-- Title --}}
                <div class="mb-3">
                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title"
                           value="{{ old('title', $post->title ?? '') }}" required>
                    @error('title')
                    <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>

                {{-- Slug (Optional for Edit) --}}
                @isset($post)
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug (URL)</label>
                        <input type="text" class="form-control" id="slug" name="slug"
                               value="{{ old('slug', $post->slug ?? '') }}" placeholder="Leave empty to auto-generate">
                        @error('slug')
                        <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                @endisset
                <div class="col-12 mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea name="content" id="description"
                              class="form-control @error('content') is-invalid @enderror"
                              rows="5">{{ old('content', $post?->content) }}</textarea>
                    @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        {{-- Sidebar --}}
        <div class="card bg-white border-0 rounded-10 mb-4">
            <div class="card-body p-4">
                {{-- Featured Image --}}
                <div class="mb-3">
                    <label for="image" class="form-label">Featured Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    @isset($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" alt="Current Image" class="mt-2 img-thumbnail"
                             style="max-height: 150px;">
                    @endisset
                    @error('image')
                    <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>

                {{-- Status --}}
                <div class="mb-3 form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published"
                           value="1"
                        {{ old('is_published', isset($post) && $post->is_published) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_published">Publish</label>
                </div>

                {{-- Optional: Categories --}}
                {{--
                <div class="mb-3">
                   <label for="blog_category_id" class="form-label">Category</label>
                   <select class="form-select" id="blog_category_id" name="blog_category_id">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('blog_category_id', $post->blog_category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                   </select>
                </div>
                --}}

                {{-- Submit Button --}}
                <button type="submit" class="btn btn-primary w-100">
                    {{ isset($post) ? 'Update Post' : 'Create Post' }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <!-- Quill JS -->
    <script src="{{ asset('assets/admin/js/tinymce-config.js') }}"></script>

@endpush
