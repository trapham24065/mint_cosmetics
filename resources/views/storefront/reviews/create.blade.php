@extends('storefront.layouts.app')
@section('content')
    <div class="container section-space ">
        <h3>Write a review for: {{ $orderItem->product_name }}</h3>
        <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="token" value="{{ $orderItem->review_token }}">
            <div class="mb-3">
                <label>Your Name</label>
                <input type="text" name="reviewer_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <div class="product-reviews-form">
                    <div class="form-input-item">
                        <div class="form-ratings-item">
                            <select name="rating" id="product-review-form-rating-select" class="select-ratings"
                                    style="display: none;">
                                <option value="1">01</option>
                                <option value="2">02</option>
                                <option value="3">03</option>
                                <option value="4">04</option>
                                <option value="5">05</option>
                            </select>
                            <div class="nice-select select-ratings" tabindex="0"><span class="current">01</span>
                                <ul class="list">
                                    <li data-value="1" class="option selected">01</li>
                                    <li data-value="2" class="option">02</li>
                                    <li data-value="3" class="option">03</li>
                                    <li data-value="4" class="option">04</li>
                                    <li data-value="5" class="option">05</li>
                                </ul>
                            </div>
                            <span class="title">Provide Your Ratings</span>
                            <div class="product-ratingsform-form-wrap">
                                <div class="product-ratingsform-form-icon">
                                    <i class="fa fa-star-o"></i>
                                    <i class="fa fa-star-o"></i>
                                    <i class="fa fa-star-o"></i>
                                    <i class="fa fa-star-o"></i>
                                    <i class="fa fa-star-o"></i>
                                </div>
                                <div id="product-review-form-rating" class="product-ratingsform-form-icon-fill"
                                     style="width: 20%;">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
