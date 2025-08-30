@extends('storefront.layouts.app')
@section('content')
    <main class="main-content">

        <!--== Start Page Header Area Wrapper ==-->
        <section class="page-header-area pt-10 pb-9" data-bg-color="#FFF3DA">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <div class="page-header-st3-content text-center text-md-start">
                            <ol class="breadcrumb justify-content-center justify-content-md-start">
                                <li class="breadcrumb-item"><a class="text-dark" href="{{route('home')}}">Home</a></li>
                                <li class="breadcrumb-item active text-dark" aria-current="page">Products</li>
                            </ol>
                            <h2 class="page-header-title">All Products</h2>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h5 class="showing-pagination-results mt-5 mt-md-9 text-center text-md-end">Showing 09
                            Results</h5>
                    </div>
                </div>
            </div>
        </section>
        <!--== End Page Header Area Wrapper ==-->

        <!--== Start Shop Top Bar Area Wrapper ==-->
        <div class="shop-top-bar-area">
            <div class="container">
                <div class="shop-top-bar">
                    <select class="select-shoing">
                        <option data-display="Trending">Trending</option>
                        <option value="1">Featured</option>
                        <option value="2">Best Selling</option>
                    </select>

                    <div class="select-on-sale d-flex d-md-none">
                        <span>On Sale :</span>
                        <select class="select-on-sale-form">
                            <option selected>Yes</option>
                            <option value="1">No</option>
                        </select>
                    </div>

                    <div class="select-price-range">
                        <h4 class="title">Pricing</h4>
                        <div class="select-price-range-slider">
                            <div class="slider-range" id="slider-range"></div>
                            <div class="slider-labels">
                                <span id="slider-range-value1"></span>
                                <span>-</span>
                                <span id="slider-range-value2"></span>
                            </div>
                        </div>
                    </div>

                    <div class="select-on-sale d-none d-md-flex">
                        <span>On Sale :</span>
                        <select class="select-on-sale-form">
                            <option selected>Yes</option>
                            <option value="1">No</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <!--== End Shop Top Bar Area Wrapper ==-->

        <!--== Start Product Category Area Wrapper ==-->
        <section class="section-space pb-0">
            <div class="container">
                <div class="row g-3 g-sm-6">
                    {{-- DYNAMIC CATEGORY LIST --}}
                    @foreach($categories->take(6) as $category)
                        {{-- Show up to 6 categories --}}
                        <div class="col-6 col-lg-4 col-lg-2 col-xl-2">
                            <a href="{{-- route('products.index', ['category' => $category->slug]) --}}"
                               class="product-category-item">
                                {{-- You would need to add an icon field to your category table for this image --}}
                                <img class="icon" src="{{asset('assets/storefront/images/shop/category/1.webp')}}"
                                     width="70" height="80" alt="Image">
                                <h3 class="title">{{ $category->name }}</h3>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!--== End Product Category Area Wrapper ==-->

        <!--== Start Product Area Wrapper ==-->
        <section class="section-space">
            <div class="container">
                <div class="row mb-n4 mb-sm-n10 g-3 g-sm-6">
                    @forelse ($products as $product)
                        <div class="col-6 col-lg-4 mb-4 mb-sm-8">
                            <!--== Start Product Item ==-->
                            <div class="product-item">
                                <div class="product-thumb">
                                    <a class="d-block" href="{{-- route('products.show', $product->slug) --}}">
                                        <img
                                            src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/storefront/images/shop/1.webp') }}"
                                            width="370"
                                            height="450" alt="{{ $product->name }}">
                                    </a>
                                    <span class=" flag-new">new</span>
                                    <div class="product-action">
                                        <button type="button" class="product-action-btn action-btn-quick-view"
                                                data-product-id="{{ $product->id }}">
                                            <i class="fa fa-expand"></i>
                                        </button>
                                        <button type="button" class="product-action-btn action-btn-cart"
                                                data-bs-toggle="modal" data-bs-target="#action-CartAddModal">
                                            <span>Add to cart</span>
                                        </button>
                                        <button type="button" class="product-action-btn action-btn-wishlist"
                                                data-bs-toggle="modal" data-bs-target="#action-WishlistModal">
                                            <i class="fa fa-heart-o"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="product-rating">
                                        <div class="rating">
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-half-o"></i>
                                        </div>
                                        <div class="reviews">150 reviews</div>
                                    </div>
                                    <h4 class="title"><a
                                            href="{{-- route('products.show', $product->slug) --}}">{{ $product->name }}</a>
                                    </h4>
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
                                    <div class="product-action-bottom">
                                        <button type="button" class="product-action-btn action-btn-quick-view"
                                                data-bs-toggle="modal" data-bs-target="#action-QuickViewModal">
                                            <i class="fa fa-expand"></i>
                                        </button>
                                        <button type="button" class="product-action-btn action-btn-wishlist"
                                                data-bs-toggle="modal" data-bs-target="#action-WishlistModal">
                                            <i class="fa fa-heart-o"></i>
                                        </button>
                                        <button type="button" class="product-action-btn action-btn-cart"
                                                data-bs-toggle="modal"
                                                data-bs-target="#action-CartAddModal">
                                            <span>Add to cart</span>
                                        </button>
                                    </div>
                                </div>
                                <!--== End product Item ==-->

                            </div>

                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center">No products found matching your criteria.</p>
                        </div>
                    @endforelse
                    <div class="col-12">
                        {{-- DYNAMIC PAGINATION --}}
                        <div class="mt-5">
                            {{ $products->appends(request()->query())->links('vendor.pagination.storefront-pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--== End Product Area Wrapper ==-->
    </main>

@endsection
