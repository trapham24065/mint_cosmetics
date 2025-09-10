@extends('storefront.layouts.app')
@section('content')
    <div class="container section-space">
        <h3>Write a review for: {{ $orderItem->product_name }}</h3>
        <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="token" value="{{ $orderItem->review_token }}">
            <div class="mb-3">
                <label>Your Name</label>
                <input type="text" name="reviewer_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Rating (1-5)</label>
                <select name="rating" class="form-select" required>
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Your Review</label>
                <textarea name="review" rows="5" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="review-media" class="form-label">Attach images (Optional, up to 5 files, each up to
                    2MB)</label>
                <input class="form-control" type="file" id="review-media" name="media[]" multiple accept="image/*">
                @error('media.*')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Submit Review</button>
        </form>
    </div>
@endsection
