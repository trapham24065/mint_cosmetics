@extends('storefront.layouts.app')
<style>
    .rating-stars {
        font-size: 30px;
        color: #ddd;
        cursor: pointer;
        user-select: none;
    }

    .rating-stars .star {
        margin-right: 6px;
        transition: all 0.2s ease;
    }

    .rating-stars .star.active {
        color: #ff6565;
    }

    .rating-stars .star:hover {
        transform: scale(1.15);
    }

    .rating-text {
        font-size: 14px;
        color: #666;
    }
</style>
@section('content')
    <div class="container section-space ">
        <h3>Viết đánh giá cho: {{ $orderItem->product_name }}</h3>
        <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="token" value="{{ $orderItem->review_token }}">
            <div class="mb-3">
                <label>Tên của bạn</label>
                <input type="text" name="reviewer_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Đánh giá của bạn</label>

                <div class="rating-stars">
                    <input type="hidden" name="rating" id="rating-value" value="5">

                    <i class="fa fa-star star active" data-value="1"></i>
                    <i class="fa fa-star star active" data-value="2"></i>
                    <i class="fa fa-star star active" data-value="3"></i>
                    <i class="fa fa-star star active" data-value="4"></i>
                    <i class="fa fa-star star active" data-value="5"></i>
                </div>

                <div id="rating-text" class="rating-text mt-1">
                    Rất tốt
                </div>
            </div>
            <div class="mb-3">
                <label>Đánh giá của bạn</label>
                <textarea name="review" rows="5" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="review-media" class="form-label">Đính kèm hình ảnh (Tùy chọn, tối đa 5 tệp, mỗi tệp tối đa
                    2MB)</label>
                <input class="form-control" type="file" id="review-media" name="media[]" multiple accept="image/*">
                @error('media.*')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
        </form>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const stars = document.querySelectorAll('.rating-stars .star');
        const ratingInput = document.getElementById('rating-value');
        const ratingText = document.getElementById('rating-text');

        const ratingDescriptions = {
            1: 'Rất tệ',
            2: 'Tệ',
            3: 'Bình thường',
            4: 'Tốt',
            5: 'Rất tốt',
        };

        let selectedRating = 5;

        function highlightStars (rating) {
            stars.forEach(star => {
                star.classList.remove('active');
            });

            for (let i = 0; i < rating; i++) {
                stars[i].classList.add('active');
            }
        }

        stars.forEach(star => {

            star.addEventListener('mouseenter', function() {
                const rating = this.dataset.value;
                highlightStars(rating);
                ratingText.innerText = ratingDescriptions[rating];
            });

            star.addEventListener('click', function() {
                selectedRating = this.dataset.value;
                ratingInput.value = selectedRating;
                ratingText.innerText = ratingDescriptions[selectedRating];
            });

        });

        document.querySelector('.rating-stars').addEventListener('mouseleave', function() {
            highlightStars(selectedRating);
            ratingText.innerText = ratingDescriptions[selectedRating];
        });

    });
</script>
