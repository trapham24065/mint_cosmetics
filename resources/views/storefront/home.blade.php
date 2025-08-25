@extends('storefront.layouts.app')
@section('content')
    <main class="main-content">

        <!--== Start Hero Area Wrapper ==-->
        <section class="hero-slider-area position-relative">
            <div class="swiper hero-slider-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide hero-slide-item">
                        <div class="container">
                            <div class="row align-items-center position-relative">
                                <div class="col-12 col-md-6">
                                    <div class="hero-slide-content">
                                        <div class="hero-slide-text-img"><img
                                                src="{{asset('assets/storefront/images/slider/text-theme.webp')}}"
                                                width="427" height="232" alt="Image">
                                        </div>
                                        <h2 class="hero-slide-title">CLEAN FRESH</h2>
                                        <p class="hero-slide-desc">Lorem ipsum dolor sit amet, consectetur adipiscing
                                            elit ut aliquam, purus sit amet luctus venenatis.</p>
                                        <a class="btn btn-border-dark" href="product.html">BUY NOW</a>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="hero-slide-thumb">
                                        <img src="{{asset('assets/storefront/images/slider/slider1.webp')}}" width="841"
                                             height="832"
                                             alt="Image">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hero-slide-text-shape"><img
                                src="{{asset('assets/storefront/images/slider/text1.webp')}}" width="70"
                                height="955" alt="Image"></div>
                        <div class="hero-slide-social-shape"></div>
                    </div>
                    <div class="swiper-slide hero-slide-item">
                        <div class="container">
                            <div class="row align-items-center position-relative">
                                <div class="col-12 col-md-6">
                                    <div class="hero-slide-content">
                                        <div class="hero-slide-text-img"><img
                                                src="{{asset('assets/storefront/images/slider/text-theme.webp')}}"
                                                width="427" height="232" alt="Image">
                                        </div>
                                        <h2 class="hero-slide-title">Facial Cream</h2>
                                        <p class="hero-slide-desc">Lorem ipsum dolor sit amet, consectetur adipiscing
                                            elit ut aliquam, purus sit amet luctus venenatis.</p>
                                        <a class="btn btn-border-dark" href="product.html">BUY NOW</a>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="hero-slide-thumb">
                                        <img src="{{asset('assets/storefront/images/slider/slider2.webp')}}" width="841"
                                             height="832"
                                             alt="Image">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hero-slide-text-shape"><img
                                src="{{asset('assets/storefront/images/slider/text1.webp')}}" width="70"
                                height="955" alt="Image"></div>
                        <div class="hero-slide-social-shape"></div>
                    </div>
                </div>
                <!--== Add Pagination ==-->
                <div class="hero-slider-pagination"></div>
            </div>
            <div class="hero-slide-social-media">
                <a href="https://www.pinterest.com/" target="_blank" rel="noopener"><i
                        class="fa fa-pinterest-p"></i></a>
                <a href="https://twitter.com/" target="_blank" rel="noopener"><i class="fa fa-twitter"></i></a>
                <a href="https://www.facebook.com/" target="_blank" rel="noopener"><i class="fa fa-facebook"></i></a>
            </div>
        </section>
        <!--== End Hero Area Wrapper ==-->

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
                <div class="row">
                    <div class="col-12">
                        <div class="section-title text-center">
                            <h2 class="title">Top sale</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus
                                venenatis</p>
                        </div>
                    </div>
                </div>
                <div class="row mb-n4 mb-sm-n10 g-3 g-sm-6">
                    <div class="col-6 col-lg-4 mb-4 mb-sm-9">
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
                    <div class="col-6 col-lg-4 mb-4 mb-sm-9">
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
                    <div class="col-6 col-lg-4 mb-4 mb-sm-9">
                        <!--== Start Product Item ==-->
                        <div class="product-item">
                            <div class="product-thumb">
                                <a class="d-block" href="product-details.html">
                                    <img src="{{asset('assets/storefront/images/shop/3.webp')}}" width="370')}}"
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
                    <div class="col-6 col-lg-4 mb-4 mb-sm-9">
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
                    <div class="col-6 col-lg-4 mb-4 mb-sm-9">
                        <!--== Start Product Item ==-->
                        <div class="product-item">
                            <div class="product-thumb">
                                <a class="d-block" href="product-details.html">
                                    <img src="{{asset('assets/storefront/images/shop/5.webp')}}" width="370')}}"
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
                    <div class="col-6 col-lg-4 mb-4 mb-sm-9">
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
                </div>
            </div>
        </section>
        <!--== End Product Area Wrapper ==-->

        <!--== Start Product Banner Area Wrapper ==-->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-lg-4">
                        <!--== Start Product Category Item ==-->
                        <a href="product.html" class="product-banner-item">
                            <img src="{{asset('assets/storefront/images/shop/banner/1.webp')}}" width="370" height="370"
                                 alt="Image-HasTech">
                        </a>
                        <!--== End Product Category Item ==-->
                    </div>
                    <div class="col-sm-6 col-lg-4 mt-sm-0 mt-6">
                        <!--== Start Product Category Item ==-->
                        <a href="product.html" class="product-banner-item">
                            <img src="{{asset('assets/storefront/images/shop/banner/2.webp')}}" width="370" height="370"
                                 alt="Image-HasTech">
                        </a>
                        <!--== End Product Category Item ==-->
                    </div>
                    <div class="col-sm-6 col-lg-4 mt-lg-0 mt-6">
                        <!--== Start Product Category Item ==-->
                        <a href="product.html" class="product-banner-item">
                            <img src="{{asset('assets/storefront/images/shop/banner/3.webp')}}" width="370" height="370"
                                 alt="Image-HasTech">
                        </a>
                        <!--== End Product Category Item ==-->
                    </div>
                </div>
            </div>
        </section>
        <!--== End Product Banner Area Wrapper ==-->

        <!--== Start Product Area Wrapper ==-->
        <section class="section-space pb-0">
            <div class="container">
                <div class="row mb-n4 mb-sm-n10 g-3 g-sm-6">
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
                </div>
            </div>
        </section>
        <!--== End Product Area Wrapper ==-->

        <!--== Start Blog Area Wrapper ==-->
        <section class="section-space">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title text-center">
                            <h2 class="title">Blog posts</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus
                                venenatis</p>
                        </div>
                    </div>
                </div>
                <div class="row mb-n9">
                    <div class="col-sm-6 col-lg-4 mb-8">
                        <!--== Start Blog Item ==-->
                        <div class="post-item">
                            <a href="blog-details.html" class="thumb">
                                <img src="{{asset('assets/storefront/images/blog/1.webp')}}" width="370" height="320"
                                     alt="Image-HasTech">
                            </a>
                            <div class="content">
                                <a class="post-category" href="blog.html">beauty</a>
                                <h4 class="title"><a href="blog-details.html">Lorem ipsum dolor sit amet consectetur
                                        adipiscing.</a></h4>
                                <ul class="meta">
                                    <li class="author-info"><span>By:</span> <a href="blog.html">Tomas De Momen</a></li>
                                    <li class="post-date">February 13, 2022</li>
                                </ul>
                            </div>
                        </div>
                        <!--== End Blog Item ==-->
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-8">
                        <!--== Start Blog Item ==-->
                        <div class="post-item">
                            <a href="blog-details.html" class="thumb">
                                <img src="{{asset('assets/storefront/images/blog/2.webp')}}" width="370" height="320"
                                     alt="Image-HasTech">
                            </a>
                            <div class="content">
                                <a class="post-category post-category-two" data-bg-color="#A49CFF" href="blog.html">beauty</a>
                                <h4 class="title"><a href="blog-details.html">Facial Scrub is natural treatment for
                                        face.</a></h4>
                                <ul class="meta">
                                    <li class="author-info"><span>By:</span> <a href="blog.html">Tomas De Momen</a></li>
                                    <li class="post-date">February 13, 2022</li>
                                </ul>
                            </div>
                        </div>
                        <!--== End Blog Item ==-->
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-8">
                        <!--== Start Blog Item ==-->
                        <div class="post-item">
                            <a href="blog-details.html" class="thumb">
                                <img src="{{asset('assets/storefront/images/blog/3.webp')}}" width="370" height="320"
                                     alt="Image-HasTech">
                            </a>
                            <div class="content">
                                <a class="post-category post-category-three" data-bg-color="#9CDBFF" href="blog.html">beauty</a>
                                <h4 class="title"><a href="blog-details.html">Benefit of Hot Ston Spa for your health &
                                        life.</a></h4>
                                <ul class="meta">
                                    <li class="author-info"><span>By:</span> <a href="blog.html">Tomas De Momen</a></li>
                                    <li class="post-date">February 13, 2022</li>
                                </ul>
                            </div>
                        </div>
                        <!--== End Blog Item ==-->
                    </div>
                </div>
            </div>
        </section>
        <!--== End Blog Area Wrapper ==-->

        <!--== Start News Letter Area Wrapper ==-->
        <section class="section-space pt-0">
            <div class="container">
                <div class="newsletter-content-wrap"
                     data-bg-img="{{asset('assets/storefront/images/photos/bg1.webp')}}">
                    <div class="newsletter-content">
                        <div class="section-title mb-0">
                            <h2 class="title">Join with us</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam.</p>
                        </div>
                    </div>
                    <div class="newsletter-form">
                        <form>
                            <input type="email" class="form-control" placeholder="enter your email">
                            <button class="btn-submit" type="submit"><i class="fa fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!--== End News Letter Area Wrapper ==-->

    </main>

@endsection
