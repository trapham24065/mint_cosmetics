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
                                <li class="breadcrumb-item"><a class="text-dark" href="index.html">Home</a></li>
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
                        <option value="3">Alphabetically, A-Z</option>
                        <option value="4">Alphabetically, Z-A</option>
                        <option value="5">Price, low to high</option>
                        <option value="6">Price, high to low</option>
                        <option value="7">Date, new to old</option>
                        <option value="8">Date, old to new</option>
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
                    <div class="col-6 col-lg-4 col-lg-2 col-xl-2">
                        <!--== Start Product Category Item ==-->
                        <a href="product.html" class="product-category-item">
                            <img class="icon" src="{{asset('assets/storefront/images/shop/category/1.webp')}}"
                                 width="70" height="80"
                                 alt="Image-HasTech">
                            <h3 class="title">Hare care</h3>
                            <span class="flag-new">new</span>
                        </a>
                        <!--== End Product Category Item ==-->
                    </div>
                    <div class="col-6 col-lg-4 col-lg-2 col-xl-2">
                        <!--== Start Product Category Item ==-->
                        <a href="product.html" class="product-category-item" data-bg-color="#FFEDB4">
                            <img class="icon" src="{{asset('assets/storefront/images/shop/category/2.webp')}}"
                                 width="80" height="80"
                                 alt="Image-HasTech">
                            <h3 class="title">Skin care</h3>
                        </a>
                        <!--== End Product Category Item ==-->
                    </div>
                    <div class="col-6 col-lg-4 col-lg-2 col-xl-2 mt-lg-0 mt-sm-6 mt-4">
                        <!--== Start Product Category Item ==-->
                        <a href="product.html" class="product-category-item" data-bg-color="#DFE4FF">
                            <img class="icon" src="{{asset('assets/storefront/images/shop/category/3.webp')}}"
                                 width="80" height="80"
                                 alt="Image-HasTech">
                            <h3 class="title">Lip stick</h3>
                        </a>
                        <!--== End Product Category Item ==-->
                    </div>
                    <div class="col-6 col-lg-4 col-lg-2 col-xl-2 mt-xl-0 mt-sm-6 mt-4">
                        <!--== Start Product Category Item ==-->
                        <a href="product.html" class="product-category-item" data-bg-color="#FFEACC">
                            <img class="icon" src="{{asset('assets/storefront/images/shop/category/4.webp')}}"
                                 width="80" height="80"
                                 alt="Image-HasTech">
                            <h3 class="title">Face skin</h3>
                            <span data-bg-color="#835BF4" class="flag-new">sale</span>
                        </a>
                        <!--== End Product Category Item ==-->
                    </div>
                    <div class="col-6 col-lg-4 col-lg-2 col-xl-2 mt-xl-0 mt-sm-6 mt-4">
                        <!--== Start Product Category Item ==-->
                        <a href="product.html" class="product-category-item" data-bg-color="#FFDAE0">
                            <img class="icon" src="{{asset('assets/storefront/images/shop/category/5.webp')}}"
                                 width="80" height="80"
                                 alt="Image-HasTech">
                            <h3 class="title">Blusher</h3>
                        </a>
                        <!--== End Product Category Item ==-->
                    </div>
                    <div class="col-6 col-lg-4 col-lg-2 col-xl-2 mt-xl-0 mt-sm-6 mt-4">
                        <!--== Start Product Category Item ==-->
                        <a href="product.html" class="product-category-item" data-bg-color="#FFF3DA">
                            <img class="icon" src="{{asset('assets/storefront/images/shop/category/6.webp')}}"
                                 width="80" height="80"
                                 alt="Image-HasTech">
                            <h3 class="title">Natural</h3>
                        </a>
                        <!--== End Product Category Item ==-->
                    </div>
                </div>
            </div>
        </section>
        <!--== End Product Category Area Wrapper ==-->

        <!--== Start Product Area Wrapper ==-->
        <section class="section-space">
            <div class="container">
                <div class="row mb-n4 mb-sm-n10 g-3 g-sm-6">
                    <div class="col-6 col-lg-4 mb-4 mb-sm-8">
                        <!--== Start Product Item ==-->
                        <div class="product-item">
                            <div class="product-thumb">
                                <a class="d-block" href="product-details.html">
                                    <img src="{{asset('assets/storefront/images/shop/1.webp')}}" width="370"
                                         height="450" alt="Image-HasTech">
                                </a>
                                <span class="flag-new">new</span>
                                <div class="product-action">
                                    <button type="button" class="product-action-btn action-btn-quick-view"
                                            data-bs-toggle="modal" data-bs-target="#action-QuickViewModal">
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
                                <h4 class="title"><a href="product-details.html">Readable content DX22</a></h4>
                                <div class="prices">
                                    <span class="price">$210.00</span>
                                    <span class="price-old">300.00</span>
                                </div>
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
                                <button type="button" class="product-action-btn action-btn-cart" data-bs-toggle="modal"
                                        data-bs-target="#action-CartAddModal">
                                    <span>Add to cart</span>
                                </button>
                            </div>
                        </div>
                        <!--== End prPduct Item ==-->
                    </div>
                    <div class="col-6 col-lg-4 mb-4 mb-sm-8">
                        <!--== Start Product Item ==-->
                        <div class="product-item">
                            <div class="product-thumb">
                                <a class="d-block" href="product-details.html">
                                    <img src="{{asset('assets/storefront/images/shop/2.webp')}}" width="370"
                                         height="450" alt="Image-HasTech">
                                </a>
                                <span class="flag-new">new</span>
                                <div class="product-action">
                                    <button type="button" class="product-action-btn action-btn-quick-view"
                                            data-bs-toggle="modal" data-bs-target="#action-QuickViewModal">
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
                                <h4 class="title"><a href="product-details.html">Modern Eye Brush</a></h4>
                                <div class="prices">
                                    <span class="price">$210.00</span>
                                    <span class="price-old">300.00</span>
                                </div>
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
                                <button type="button" class="product-action-btn action-btn-cart" data-bs-toggle="modal"
                                        data-bs-target="#action-CartAddModal">
                                    <span>Add to cart</span>
                                </button>
                            </div>
                        </div>
                        <!--== End prPduct Item ==-->
                    </div>
                    <div class="col-6 col-lg-4 mb-4 mb-sm-8">
                        <!--== Start Product Item ==-->
                        <div class="product-item">
                            <div class="product-thumb">
                                <a class="d-block" href="product-details.html">
                                    <img src="{{asset('assets/storefront/images/shop/3.webp')}}" width="370"
                                         height="450" alt="Image-HasTech">
                                </a>
                                <span class="flag-new">new</span>
                                <div class="product-action">
                                    <button type="button" class="product-action-btn action-btn-quick-view"
                                            data-bs-toggle="modal" data-bs-target="#action-QuickViewModal">
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
                                <h4 class="title"><a href="product-details.html">Voyage face cleaner</a></h4>
                                <div class="prices">
                                    <span class="price">$210.00</span>
                                    <span class="price-old">300.00</span>
                                </div>
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
                                <button type="button" class="product-action-btn action-btn-cart" data-bs-toggle="modal"
                                        data-bs-target="#action-CartAddModal">
                                    <span>Add to cart</span>
                                </button>
                            </div>
                        </div>
                        <!--== End prPduct Item ==-->
                    </div>
                    <div class="col-6 col-lg-4 mb-4 mb-sm-8">
                        <!--== Start Product Item ==-->
                        <div class="product-item">
                            <div class="product-thumb">
                                <a class="d-block" href="product-details.html">
                                    <img src="{{asset('assets/storefront/images/shop/4.webp')}}" width="370"
                                         height="450" alt="Image-HasTech">
                                </a>
                                <span class="flag-new">new</span>
                                <div class="product-action">
                                    <button type="button" class="product-action-btn action-btn-quick-view"
                                            data-bs-toggle="modal" data-bs-target="#action-QuickViewModal">
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
                                <h4 class="title"><a href="product-details.html">Impulse Duffle</a></h4>
                                <div class="prices">
                                    <span class="price">$210.00</span>
                                    <span class="price-old">300.00</span>
                                </div>
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
                                <button type="button" class="product-action-btn action-btn-cart" data-bs-toggle="modal"
                                        data-bs-target="#action-CartAddModal">
                                    <span>Add to cart</span>
                                </button>
                            </div>
                        </div>
                        <!--== End prPduct Item ==-->
                    </div>
                    <div class="col-6 col-lg-4 mb-4 mb-sm-8">
                        <!--== Start Product Item ==-->
                        <div class="product-item">
                            <div class="product-thumb">
                                <a class="d-block" href="product-details.html">
                                    <img src="{{asset('assets/storefront/images/shop/5.webp')}}" width="370"
                                         height="450" alt="Image-HasTech">
                                </a>
                                <span class="flag-new">new</span>
                                <div class="product-action">
                                    <button type="button" class="product-action-btn action-btn-quick-view"
                                            data-bs-toggle="modal" data-bs-target="#action-QuickViewModal">
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
                                <h4 class="title"><a href="product-details.html">Sprite Yoga Straps1</a></h4>
                                <div class="prices">
                                    <span class="price">$210.00</span>
                                    <span class="price-old">300.00</span>
                                </div>
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
                                <button type="button" class="product-action-btn action-btn-cart" data-bs-toggle="modal"
                                        data-bs-target="#action-CartAddModal">
                                    <span>Add to cart</span>
                                </button>
                            </div>
                        </div>
                        <!--== End prPduct Item ==-->
                    </div>
                    <div class="col-6 col-lg-4 mb-4 mb-sm-8">
                        <!--== Start Product Item ==-->
                        <div class="product-item">
                            <div class="product-thumb">
                                <a class="d-block" href="product-details.html">
                                    <img src="{{asset('assets/storefront/images/shop/6.webp')}}" width="370"
                                         height="450" alt="Image-HasTech">
                                </a>
                                <span class="flag-new">new</span>
                                <div class="product-action">
                                    <button type="button" class="product-action-btn action-btn-quick-view"
                                            data-bs-toggle="modal" data-bs-target="#action-QuickViewModal">
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
                                <h4 class="title"><a href="product-details.html">Fusion facial cream</a></h4>
                                <div class="prices">
                                    <span class="price">$210.00</span>
                                    <span class="price-old">300.00</span>
                                </div>
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
                                <button type="button" class="product-action-btn action-btn-cart" data-bs-toggle="modal"
                                        data-bs-target="#action-CartAddModal">
                                    <span>Add to cart</span>
                                </button>
                            </div>
                        </div>
                        <!--== End prPduct Item ==-->
                    </div>
                    <div class="col-6 col-lg-4 mb-4 mb-sm-8">
                        <!--== Start Product Item ==-->
                        <div class="product-item">
                            <div class="product-thumb">
                                <a class="d-block" href="product-details.html">
                                    <img src="{{asset('assets/storefront/images/shop/8.webp')}}" width="370"
                                         height="450" alt="Image-HasTech">
                                </a>
                                <span class="flag-new">new</span>
                                <div class="product-action">
                                    <button type="button" class="product-action-btn action-btn-quick-view"
                                            data-bs-toggle="modal" data-bs-target="#action-QuickViewModal">
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
                                <h4 class="title"><a href="product-details.html">Readable content DX22</a></h4>
                                <div class="prices">
                                    <span class="price">$210.00</span>
                                    <span class="price-old">300.00</span>
                                </div>
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
                                <button type="button" class="product-action-btn action-btn-cart" data-bs-toggle="modal"
                                        data-bs-target="#action-CartAddModal">
                                    <span>Add to cart</span>
                                </button>
                            </div>
                        </div>
                        <!--== End prPduct Item ==-->
                    </div>
                    <div class="col-6 col-lg-4 mb-4 mb-sm-8">
                        <!--== Start Product Item ==-->
                        <div class="product-item">
                            <div class="product-thumb">
                                <a class="d-block" href="product-details.html">
                                    <img src="{{asset('assets/storefront/images/shop/6.webp')}}" width="370"
                                         height="450" alt="Image-HasTech">
                                </a>
                                <span class="flag-new">new</span>
                                <div class="product-action">
                                    <button type="button" class="product-action-btn action-btn-quick-view"
                                            data-bs-toggle="modal" data-bs-target="#action-QuickViewModal">
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
                                <h4 class="title"><a href="product-details.html">Voyage face cleaner</a></h4>
                                <div class="prices">
                                    <span class="price">$210.00</span>
                                    <span class="price-old">300.00</span>
                                </div>
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
                                <button type="button" class="product-action-btn action-btn-cart" data-bs-toggle="modal"
                                        data-bs-target="#action-CartAddModal">
                                    <span>Add to cart</span>
                                </button>
                            </div>
                        </div>
                        <!--== End prPduct Item ==-->
                    </div>
                    <div class="col-6 col-lg-4 mb-4 mb-sm-8">
                        <!--== Start Product Item ==-->
                        <div class="product-item">
                            <div class="product-thumb">
                                <a class="d-block" href="product-details.html">
                                    <img src="{{asset('assets/storefront/images/shop/7.webp')}}" width="370"
                                         height="450" alt="Image-HasTech">
                                </a>
                                <span class="flag-new">new</span>
                                <div class="product-action">
                                    <button type="button" class="product-action-btn action-btn-quick-view"
                                            data-bs-toggle="modal" data-bs-target="#action-QuickViewModal">
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
                                <h4 class="title"><a href="product-details.html">Modern Eye Brush</a></h4>
                                <div class="prices">
                                    <span class="price">$210.00</span>
                                    <span class="price-old">300.00</span>
                                </div>
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
                                <button type="button" class="product-action-btn action-btn-cart" data-bs-toggle="modal"
                                        data-bs-target="#action-CartAddModal">
                                    <span>Add to cart</span>
                                </button>
                            </div>
                        </div>
                        <!--== End prPduct Item ==-->
                    </div>
                    <div class="col-12">
                        <ul class="pagination justify-content-center me-auto ms-auto mt-5 mb-0 mb-sm-10">
                            <li class="page-item">
                                <a class="page-link previous" href="product.html" aria-label="Previous">
                                    <span class="fa fa-chevron-left" aria-hidden="true"></span>
                                </a>
                            </li>
                            <li class="page-item"><a class="page-link" href="product.html">01</a></li>
                            <li class="page-item"><a class="page-link" href="product.html">02</a></li>
                            <li class="page-item"><a class="page-link" href="product.html">03</a></li>
                            <li class="page-item"><a class="page-link" href="product.html">....</a></li>
                            <li class="page-item">
                                <a class="page-link next" href="product.html" aria-label="Next">
                                    <span class="fa fa-chevron-right" aria-hidden="true"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!--== End Product Area Wrapper ==-->

        <!--== Start Product Banner Area Wrapper ==-->
        <section>
            <div class="container">
                <!--== Start Product Category Item ==-->
                <a href="product.html" class="product-banner-item">
                    <img src="{{asset('assets/storefront/images/shop/banner/7.webp')}}" width="1170" height="240"
                         alt="Image-HasTech">
                </a>
                <!--== End Product Category Item ==-->
            </div>
        </section>
        <!--== End Product Banner Area Wrapper ==-->

        <!--== Start Product Area Wrapper ==-->
        <section class="section-space">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title">
                            <h2 class="title">Related Products</h2>
                            <p class="m-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit
                                amet luctus venenatis</p>
                        </div>
                    </div>
                </div>
                <div class="row mb-n10">
                    <div class="col-12">
                        <div class="swiper related-product-slide-container">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide mb-8">
                                    <!--== Start Product Item ==-->
                                    <div class="product-item">
                                        <div class="product-thumb">
                                            <a class="d-block" href="product-details.html">
                                                <img src="{{asset('assets/storefront/images/shop/4.webp')}}" width="370"
                                                     height="450"
                                                     alt="Image-HasTech">
                                            </a>
                                            <span class="flag-new">new</span>
                                            <div class="product-action">
                                                <button type="button" class="product-action-btn action-btn-quick-view"
                                                        data-bs-toggle="modal" data-bs-target="#action-QuickViewModal">
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
                                            <h4 class="title"><a href="product-details.html">Readable content DX22</a>
                                            </h4>
                                            <div class="prices">
                                                <span class="price">$210.00</span>
                                                <span class="price-old">300.00</span>
                                            </div>
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
                                                    data-bs-toggle="modal" data-bs-target="#action-CartAddModal">
                                                <span>Add to cart</span>
                                            </button>
                                        </div>
                                    </div>
                                    <!--== End prPduct Item ==-->
                                </div>
                                <div class="swiper-slide mb-8">
                                    <!--== Start Product Item ==-->
                                    <div class="product-item">
                                        <div class="product-thumb">
                                            <a class="d-block" href="product-details.html">
                                                <img src="{{asset('assets/storefront/images/shop/5.webp')}}" width="370"
                                                     height="450"
                                                     alt="Image-HasTech">
                                            </a>
                                            <span class="flag-new">new</span>
                                            <div class="product-action">
                                                <button type="button" class="product-action-btn action-btn-quick-view"
                                                        data-bs-toggle="modal" data-bs-target="#action-QuickViewModal">
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
                                            <h4 class="title"><a href="product-details.html">Voyage face cleaner</a>
                                            </h4>
                                            <div class="prices">
                                                <span class="price">$210.00</span>
                                                <span class="price-old">300.00</span>
                                            </div>
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
                                                    data-bs-toggle="modal" data-bs-target="#action-CartAddModal">
                                                <span>Add to cart</span>
                                            </button>
                                        </div>
                                    </div>
                                    <!--== End prPduct Item ==-->
                                </div>
                                <div class="swiper-slide mb-8">
                                    <!--== Start Product Item ==-->
                                    <div class="product-item">
                                        <div class="product-thumb">
                                            <a class="d-block" href="product-details.html">
                                                <img src="{{asset('assets/storefront/images/shop/6.webp')}}" width="370"
                                                     height="450"
                                                     alt="Image-HasTech">
                                            </a>
                                            <span class="flag-new">new</span>
                                            <div class="product-action">
                                                <button type="button" class="product-action-btn action-btn-quick-view"
                                                        data-bs-toggle="modal" data-bs-target="#action-QuickViewModal">
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
                                            <h4 class="title"><a href="product-details.html">Sprite Yoga Straps1</a>
                                            </h4>
                                            <div class="prices">
                                                <span class="price">$210.00</span>
                                                <span class="price-old">300.00</span>
                                            </div>
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
                                                    data-bs-toggle="modal" data-bs-target="#action-CartAddModal">
                                                <span>Add to cart</span>
                                            </button>
                                        </div>
                                    </div>
                                    <!--== End prPduct Item ==-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--== End Product Area Wrapper ==-->
    </main>

@endsection
