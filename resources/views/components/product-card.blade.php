@props(['product'])

<div class="product-item">
    <div class="product-thumb">
        <a class="d-block" href="{{ route('products.show', $product->slug) }}">
            <img
                src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/storefront/images/shop/1.webp') }}"
                width="370" height="450" alt="{{ $product->name }}">
        </a>
        {{-- You can add logic for flags like 'new' or 'sale' here --}}
        {{-- <span class="flag-new">new</span> --}}
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
                    data-bs-toggle="modal" data-bs-target="#action-WishlistModal">
                <i class="fa fa-heart-o"></i>
            </button>
        </div>
    </div>
    <div class="product-info">
        {{-- You can add a dynamic rating system later --}}
        <div class="product-rating">
            <div class="rating">
                <i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i
                    class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i>
            </div>
            <div class="reviews">150 reviews</div>
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
