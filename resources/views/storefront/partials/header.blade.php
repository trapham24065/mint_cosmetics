<!--== Start Header Wrapper ==-->
<header class="header-area sticky-header">
    <div class="container">
        <div class="row align-items-center">
            {{-- Logo Column: Giảm kích thước trên mobile để nhường chỗ cho actions --}}
            <div class="col-5 col-sm-4 col-lg-3">
                <div class="header-logo">
                    <a href="{{ route('home') }}">
                        @if(setting('site_logo'))
                            <img class="logo-main"
                                 src="{{ asset('storage/' . setting('site_logo')) }}"
                                 width="95" height="68"
                                 alt="{{ setting('site_name', 'Logo') }}"
                                 style="object-fit: contain;">
                        @else
                            <img class="logo-main"
                                 src="{{ asset('assets/storefront/images/logo.webp') }}"
                                 width="95" height="68"
                                 alt="Logo" />
                        @endif
                    </a>
                </div>
            </div>

            {{-- Navigation Column: Chỉ hiện trên Desktop --}}
            <div class="col-lg-6 d-none d-lg-block">
                <div class="header-navigation">
                    <ul class="main-nav justify-content-start">
                        <li><a href="{{route('home')}}">home</a></li>
                        <li><a href="{{route('about-us.index')}}">about</a></li>
                        <li><a href="{{route('shop')}}">shop</a></li>
                        {{--                        <li><a href="{{route('blog.index')}}">Blog</a></li>--}}
                        <li><a href="{{route('contact.index')}}">Contact</a></li>
                    </ul>
                </div>
            </div>

            {{-- Actions Column: Tăng kích thước trên mobile để chứa đủ nút --}}
            <div class="col-7 col-sm-8 col-lg-3">
                <div class="header-action justify-content-end d-flex align-items-center">
                    {{-- Search Button --}}
                    <button class="header-action-btn ms-0" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#AsideOffcanvasSearch" aria-controls="AsideOffcanvasSearch">
                        <span class="icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2"
                                      stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </button>

                    {{-- Cart Button --}}
                    <button class="header-action-btn" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#AsideOffcanvasCart" aria-controls="AsideOffcanvasCart">
                        <span class="icon position-relative">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9 22C9.55228 22 10 21.5523 10 21C10 20.4477 9.55228 20 9 20C8.44772 20 8 20.4477 8 21C8 21.5523 8.44772 22 9 22Z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M20 22C20.5523 22 21 21.5523 21 21C21 20.4477 20.5523 20 20 20C19.4477 20 19 20.4477 19 21C19 21.5523 19.4477 22 20 22Z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M1 1H5L7.68 14.39C7.77144 14.8504 8.02191 15.264 8.38755 15.5583C8.75318 15.8526 9.2107 16.009 9.68 16H19.4C19.8693 16.009 20.3268 15.8526 20.6925 15.5583C21.0581 15.264 21.3086 14.8504 21.4 14.39L23 6H6"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span id="cart-count"
                                  class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                  style="font-size: 10px;">
                                {{ get_cart_count() }}
                            </span>
                        </span>
                    </button>

                    {{-- Wishlist Button --}}
                    <a class="header-action-btn" href="{{ route('wishlist.index') }}">
                        <span class="icon position-relative">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M20.84 4.61001C20.3292 4.09901 19.7228 3.69365 19.0554 3.41709C18.3879 3.14052 17.6725 2.99818 16.95 2.99818C16.2275 2.99818 15.5121 3.14052 14.8446 3.41709C14.1772 3.69365 13.5708 4.09901 13.06 4.61001L12 5.67001L10.94 4.61001C9.9083 3.57831 8.50903 2.99871 7.05 2.99871C5.59096 2.99871 4.19169 3.57831 3.16 4.61001C2.1283 5.64171 1.54871 7.04098 1.54871 8.50001C1.54871 9.95903 2.1283 11.3583 3.16 12.39L4.22 13.45L12 21.23L19.78 13.45L20.84 12.39C21.351 11.8792 21.7563 11.2728 22.0329 10.6053C22.3095 9.93789 22.4518 9.22249 22.4518 8.50001C22.4518 7.77752 22.3095 7.06212 22.0329 6.39465C21.7563 5.72718 21.351 5.12082 20.84 4.61001V4.61001Z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span id="wishlist-count"
                                  class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                  style="font-size: 10px;">
                                {{ get_wishlist_count() }}
                            </span>
                        </span>
                    </a>

                    {{-- User Button --}}
                    <a class="header-action-btn" href="{{route('customer.dashboard')}}">
                        <span class="icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </span>
                    </a>

                    {{-- Mobile Menu Button (Hamburger) --}}
                    <button class="header-menu-btn" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#AsideOffcanvasMenu" aria-controls="AsideOffcanvasMenu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>
<!--== End Header Wrapper ==-->
