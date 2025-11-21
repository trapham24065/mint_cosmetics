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
                                        <a class="btn btn-border-dark" href="{{ route('shop') }}">SHOP
                                            NOW</a> {{-- Link to shop --}}
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
                                        <a class="btn btn-border-dark" href="{{ route('shop') }}">SHOP
                                            NOW</a> {{-- Link to shop --}}
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
                {{-- NOTE: This section should ideally fetch categories dynamically too --}}
                <div class="row g-3 g-sm-6">
                    <div class="col-6 col-lg-4 col-lg-2 col-xl-2">
                        <!--== Start Product Category Item ==-->
                        <a href="{{ route('shop') }}" class="product-category-item">
                            <img class="icon" src="{{asset('assets/storefront/images/shop/category/1.webp')}}"
                                 width="70" height="80"
                                 alt="Image-HasTech">
                            <h3 class="title">Hare care</h3>
                            <span class="flag-new">new</span>
                        </a>
                        <!--== End Product Category Item ==-->
                    </div>
                    {{-- Add other static categories or loop through dynamic ones --}}
                    <div class="col-6 col-lg-4 col-lg-2 col-xl-2">
                        <!--== Start Product Category Item ==-->
                        <a href="{{ route('shop') }}" class="product-category-item" data-bg-color="#FFEDB4">
                            <img class="icon" src="{{asset('assets/storefront/images/shop/category/2.webp')}}"
                                 width="80" height="80"
                                 alt="Image-HasTech">
                            <h3 class="title">Skin care</h3>
                        </a>
                        <!--== End Product Category Item ==-->
                    </div>
                    <div class="col-6 col-lg-4 col-lg-2 col-xl-2 mt-lg-0 mt-sm-6 mt-4">
                        <!--== Start Product Category Item ==-->
                        <a href="{{ route('shop') }}" class="product-category-item" data-bg-color="#DFE4FF">
                            <img class="icon" src="{{asset('assets/storefront/images/shop/category/3.webp')}}"
                                 width="80" height="80"
                                 alt="Image-HasTech">
                            <h3 class="title">Lip stick</h3>
                        </a>
                        <!--== End Product Category Item ==-->
                    </div>
                    <div class="col-6 col-lg-4 col-lg-2 col-xl-2 mt-xl-0 mt-sm-6 mt-4">
                        <!--== Start Product Category Item ==-->
                        <a href="{{ route('shop') }}" class="product-category-item" data-bg-color="#FFEACC">
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
                        <a href="{{ route('shop') }}" class="product-category-item" data-bg-color="#FFDAE0">
                            <img class="icon" src="{{asset('assets/storefront/images/shop/category/5.webp')}}"
                                 width="80" height="80"
                                 alt="Image-HasTech">
                            <h3 class="title">Blusher</h3>
                        </a>
                        <!--== End Product Category Item ==-->
                    </div>
                    <div class="col-6 col-lg-4 col-lg-2 col-xl-2 mt-xl-0 mt-sm-6 mt-4">
                        <!--== Start Product Category Item ==-->
                        <a href="{{ route('shop') }}" class="product-category-item" data-bg-color="#FFF3DA">
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
                    {{-- DYNAMIC PRODUCT GRID --}}
                    @forelse($latestProducts as $product)
                        <div class="col-6 col-lg-4 mb-4 mb-sm-8">
                            {{-- Call the component and pass the product data into it --}}
                            <x-product-card :product="$product" />
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center">No products found matching your criteria.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
        <!--== End Product Area Wrapper ==-->

        <!--== Start Product Banner Area Wrapper ==-->
        <section>
            <div class="container">
                {{-- Static Banners - Can be made dynamic later --}}
                <div class="row">
                    <div class="col-sm-6 col-lg-4">
                        <a href="{{ route('shop') }}" class="product-banner-item">
                            <img src="{{asset('assets/storefront/images/shop/banner/1.webp')}}" width="370" height="370"
                                 alt="Image-HasTech">
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-4 mt-sm-0 mt-6">
                        <a href="{{ route('shop') }}" class="product-banner-item">
                            <img src="{{asset('assets/storefront/images/shop/banner/2.webp')}}" width="370" height="370"
                                 alt="Image-HasTech">
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-4 mt-lg-0 mt-6">
                        <a href="{{ route('shop') }}" class="product-banner-item">
                            <img src="{{asset('assets/storefront/images/shop/banner/3.webp')}}" width="370" height="370"
                                 alt="Image-HasTech">
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <!--== End Product Banner Area Wrapper ==-->


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
                {{-- NOTE: This section should ideally fetch blog posts dynamically --}}
                <div class="row mb-n9">
                    <div class="col-sm-6 col-lg-4 mb-8">
                        <!--== Start Blog Item ==-->
                        <div class="post-item">
                            <a href="#" class="thumb"> {{-- Link to blog post --}}
                                <img src="{{asset('assets/storefront/images/blog/1.webp')}}" width="370" height="320"
                                     alt="Image-HasTech">
                            </a>
                            <div class="content">
                                <a class="post-category" href="#">beauty</a> {{-- Link to category --}}
                                <h4 class="title"><a href="#">Lorem ipsum dolor sit amet consectetur
                                        adipiscing.</a></h4> {{-- Link to blog post --}}
                                <ul class="meta">
                                    <li class="author-info"><span>By:</span> <a href="#">Tomas De Momen</a>
                                    </li> {{-- Link to author --}}
                                    <li class="post-date">February 13, 2022</li>
                                </ul>
                            </div>
                        </div>
                        <!--== End Blog Item ==-->
                    </div>
                    {{-- Add other static blog posts or loop through dynamic ones --}}
                    <div class="col-sm-6 col-lg-4 mb-8">
                        <!--== Start Blog Item ==-->
                        <div class="post-item">
                            <a href="#" class="thumb">
                                <img src="{{asset('assets/storefront/images/blog/2.webp')}}" width="370" height="320"
                                     alt="Image-HasTech">
                            </a>
                            <div class="content">
                                <a class="post-category post-category-two" data-bg-color="#A49CFF" href="#">beauty</a>
                                <h4 class="title"><a href="#">Facial Scrub is natural treatment for
                                        face.</a></h4>
                                <ul class="meta">
                                    <li class="author-info"><span>By:</span> <a href="#">Tomas De Momen</a></li>
                                    <li class="post-date">February 13, 2022</li>
                                </ul>
                            </div>
                        </div>
                        <!--== End Blog Item ==-->
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-8">
                        <!--== Start Blog Item ==-->
                        <div class="post-item">
                            <a href="#" class="thumb">
                                <img src="{{asset('assets/storefront/images/blog/3.webp')}}" width="370" height="320"
                                     alt="Image-HasTech">
                            </a>
                            <div class="content">
                                <a class="post-category post-category-three" data-bg-color="#9CDBFF" href="#">beauty</a>
                                <h4 class="title"><a href="#">Benefit of Hot Ston Spa for your health &
                                        life.</a></h4>
                                <ul class="meta">
                                    <li class="author-info"><span>By:</span> <a href="#">Tomas De Momen</a></li>
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

        {{-- Find the Blog Area Wrapper section --}}
        <section class="section-space">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title text-center">
                            <h2 class="title">Blog posts</h2>
                            <p>Latest news and articles from our blog.</p> {{-- Updated description --}}
                        </div>
                    </div>
                </div>
                <div class="row mb-n9">
                    {{-- === START DYNAMIC BLOG LOOP === --}}
                    @forelse ($latestPosts as $post)
                        <div class="col-sm-6 col-lg-4 mb-8">
                            <!--== Start Blog Item ==-->
                            <div class="post-item">
                                <a href="#" class="thumb"> {{-- TODO: Link to blog post details page --}}
                                    <img
                                        src="{{ $post->image ? asset('storage/' . $post->image) : asset('assets/storefront/images/blog/default.webp') }}"
                                        width="370" height="320" alt="{{ $post->title }}">
                                </a>
                                <div class="content">
                                    {{-- TODO: Add category link if using categories --}}
                                    {{-- <a class="post-category" href="#">{{ $post->category->name ?? 'Uncategorized' }}</a> --}}
                                    <h4 class="title"><a href="#">{{ Str::limit($post->title, 50) }}</a>
                                    </h4> {{-- TODO: Link to blog post details page --}}
                                    <ul class="meta">
                                        {{-- TODO: Add author link if using authors --}}
                                        {{-- <li class="author-info"><span>By:</span> <a href="#">{{ $post->author->name ?? 'Admin' }}</a></li> --}}
                                        @if($post->published_at)
                                            <li class="post-date">{{ $post->published_at->format('F d, Y') }}</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <!--== End Blog Item ==-->
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center">No recent blog posts found.</p>
                        </div>
                    @endforelse
                    {{-- === END DYNAMIC BLOG LOOP === --}}
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
