@forelse($reviews as $review)
    <div class="product-review-item mb-4 pb-4 border-bottom">
        <div class="product-review-top">
            <div class="product-review-content">
                <span class="product-review-name fw-bold">{{ $review->reviewer_name }}</span>
                <span class="product-review-designation text-muted ms-2" style="font-size: 12px;">Người mua đã được xác minh</span>
                <div class="product-review-icon text-warning mt-1">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $review->rating)
                            <i class="fa fa-star"></i>
                        @else
                            <i class="fa fa-star-o"></i>
                        @endif
                    @endfor
                </div>
            </div>
        </div>
        <p class="desc mt-2">{{ $review->review }}</p>
        @if(!empty($review->media) && is_array($review->media))
            <div class="review-media-gallery d-flex flex-wrap gap-2 mt-3">
                @foreach($review->media as $mediaPath)
                    <a href="{{ asset('storage/' . $mediaPath) }}" data-fancybox="review-{{ $review->id }}">
                        <img src="{{ asset('storage/' . $mediaPath) }}" alt="Review image" class="img-thumbnail"
                             width="80" height="80" style="object-fit: cover;">
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@empty
    <div class="product-review-item">
        <p>Sản phẩm này chưa có đánh giá nào.</p>
    </div>
@endforelse

{{-- KHU VỰC NÚT PHÂN TRANG --}}
@if($reviews->hasPages())
    <div class="mt-5 review-pagination-container">
        {{ $reviews->links('vendor.pagination.storefront-pagination') }}
    </div>
@endif
