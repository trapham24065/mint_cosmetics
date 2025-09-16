<!--== Start Header Wrapper ==-->
<header class="header-area sticky-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-5 col-sm-6 col-lg-3">
                <div class="header-logo">
                    <a href="{{route('home')}}">
                        <img class="logo-main" src="{{asset('assets/storefront/images/logo.webp')}}" width="95"
                             height="68" alt="Logo" />
                    </a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <div class="header-navigation">
                    <ul class="main-nav justify-content-start">
                        <li><a href="{{route('home')}}">home</a></li>
                        <li><a href="{{route('about-us.index')}}">about</a></li>
                        <li><a href="{{route('shop')}}">shop</a></li>
                        <li><a href="{{route('blog.index')}}">Blog</a></li>
                        <li><a href="{{route('contact.index')}}">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-7 col-sm-6 col-lg-3">
                <div class="header-action justify-content-end">
                    <button class="header-action-btn ms-0" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#AsideOffcanvasSearch" aria-controls="AsideOffcanvasSearch">
                                <span class="icon">
                  <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"
                       xmlns:xlink="http://www.w3.org/1999/xlink">
                    <rect class="icon-rect" width="30" height="30" fill="url(#pattern1)" />
                    <defs>
                      <pattern id="pattern1" patternContentUnits="objectBoundingBox" width="1" height="1">
                        <use xlink:href="#image0_504:11" transform="scale(0.0333333)" />
                      </pattern>
                      <image id="image0_504:11" width="30" height="30"
                             xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAABiUlEQVRIie2Wu04CQRSGP0G2EUtIbHwA8B3EQisLIcorEInx8hbEZ9DKy6toDI1oAgalNFpDoYWuxZzJjoTbmSXERP7kZDbZ859vdmb27MJcf0gBUAaugRbQk2gBV3IvmDa0BLwA4Zh4BorTACaAU6fwPXAI5IAliTxwBDScvJp4vWWhH0BlTLEEsC+5Fu6lkgNdV/gKDnxHCw2I9rSiNQNV8baBlMZYJtpTn71KAg9SY3dUYn9xezLPgG8P8BdwLteq5X7CzDbnAbXKS42WxtQVUzoGeFlqdEclxXrnhmhhkqR+8KuMqzHA1vumAddl3IwB3pLxVmOyr1NjwKQmURJ4lBp7GmOAafghpg1qdSDeDrCoNReJWmZB4dsAPsW7rYVa1Rx4FbOEw5TEPKmFvgMZX3DCgYeYNniMaQ5piTXghGhPLdTmZ33hYNpem98f/UHRwSxvhqhXx4anMA3/EmhiOlJPJnSBOb3uQcpOE65VhujPpAms/Bu4u+x3swRbeB24mTV4LgB+AFuLedkPkcmmAAAAAElFTkSuQmCC" />
                    </defs>
                  </svg>
                </span>
                    </button>

                    <button class="header-action-btn" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#AsideOffcanvasCart" aria-controls="AsideOffcanvasCart">
                        <span class="icon position-relative">
                            <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                 xmlns="http://www.w3.org/2000/svg"
                                 xmlns:xlink="http://www.w3.org/1999/xlink">
                                <rect class="icon-rect" width="30" height="30" fill="url(#pattern2)" />
                                <defs>
                                  <pattern id="pattern2" patternContentUnits="objectBoundingBox" width="1" height="1">
                                    <use xlink:href="#image0_504:9" transform="scale(0.0333333)" />
                                  </pattern>
                                  <image id="image0_504:9" width="30" height="30"
                                         xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAABFUlEQVRIie2VMU7DMBSGvwAqawaYuAmKxCW4A1I5Qg4AA93KBbp1ZUVUlQJSVVbCDVhgzcTQdLEVx7WDQ2xLRfzSvzzb+d6zn2MYrkugBBYevuWsHKiFn2JBMwH8Bq6Aw1jgBwHOYwGlPgT4LDZ4I8BJDNiEppl034UEJ8DMAJ0DByHBACPgUYEugePQUKkUWAmnsaB/Ry/YO9aXCwlT72AdrqaWEohwBWxSwc8ReIVtYIr5bM5pXqO+Men7rozGlkVSv4lJj1WQfsbvXVkNVNk1eEK4ik9/yuwzAPhLh5iuU4jtftMDR4ZJJXChxTJ2H3zXGDgWc43/X2Wro8G81a8u2fXU2nXiLVAxvNIKuPGW/r/2SltF+a3Rkw4pmwAAAABJRU5ErkJggg==" />
                                </defs>
                            </svg>
                            <span id="cart-count"
                                  class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ get_cart_count() }}
                            </span>
                        </span>
                    </button>
                    <a class=" header-action-btn" href="{{ route('wishlist.index') }}">
                            <span class="icon position-relative">
            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M14.9999 27.2375C14.5874 27.0375 1.2499 15.6 1.2499 8.75C1.2499 5.375 4.1249 2.5 7.4999 2.5C9.8374 2.5 12.0499 3.8625 13.4374 5.75C14.1624 4.8125 15.1499 3.325 16.5624 2.5C18.4249 1.4875 21.0374 2.0125 22.4999 3.475C25.3249 6.3 23.9124 10.975 22.4999 12.3875L14.9999 20.225L13.5249 18.75"
                    stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span id="wishlist-count"
                  class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ get_wishlist_count() }}
            </span>
        </span>
                    </a>
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
