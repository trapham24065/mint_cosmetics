@props(['product'])

<div class="product-item">
    <div class="product-thumb">
        <a class="d-block" href="{{ route('products.show', $product->slug) }}">
            <img
                src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/storefront/images/shop/default.webp') }}"
                width="370" height="450" alt="{{ $product->name }}">
        </a>
        {{-- Sale badge --}}
        @php
        $firstVariant = $product->variants->first();
        $isOnSale = $firstVariant && $firstVariant->discount_price && $firstVariant->discount_price < $firstVariant->price;
            @endphp
            @if($isOnSale)
            <span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
            @endif
            <div class="product-action">
                <button type="button" class="product-action-btn action-btn-quick-view"
                    data-product-id="{{ $product->id }}">
                    <i class="fa fa-expand"></i>
                </button>
                @if($product->variants->isNotEmpty())
                <button type="button" class="product-action-btn action-btn-cart"
                    data-variant-id="{{ $product->variants->first()->id }}"
                    data-context="product-card">
                    <span>Add to cart</span>
                </button>
                @else
                <button type="button" class="product-action-btn" disabled>
                    <span>Out of stock</span>
                </button>
                @endif
                <button type="button" class="product-action-btn action-btn-wishlist"
                    data-product-id="{{ $product->id }}">
                    <i class="fa fa-heart-o"></i>
                </button>
            </div>
    </div>
    <div class="product-info">

        <div class="product-rating"> @php
            $averageRating = $product->averageRating();
            $reviewsCount = $product->approved_reviews_count;
            @endphp

            <div class="rating">
                @if ($reviewsCount > 0)
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <=$averageRating)
                    <i class="fa fa-star"></i>
                    @else
                    <i class="fa fa-star-o"></i>
                    @endif
                    @endfor
                    @else
                    {{-- Show empty seats if there are no reviews yet --}}
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="fa fa-star-o"></i>
                        @endfor
                        @endif
            </div>
            <div class="reviews">{{ $reviewsCount }} reviews</div>
        </div>
        <h4 class="title"><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h4>
        <div class="prices">
            @php $firstVariant = $product->variants->first(); @endphp
            @if($firstVariant)
            @if($firstVariant->discount_price && $firstVariant->discount_price < $firstVariant->price)
                <span class="price">{{ number_format($firstVariant->discount_price, 0, ',', '.') }} VNĐ</span>
                <span class="price-old">{{ number_format($firstVariant->price, 0, ',', '.') }} VNĐ</span>
                @else
                <span class="price">{{ number_format($firstVariant->price, 0, ',', '.') }} VNĐ</span>
                @endif
                @endif
        </div>

    </div>
</div>