<!-- ========== App Menu Start ========== -->
<div class="main-nav">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="{{route('admin.dashboard')}}" class="logo-dark">
            <img src="{{asset('assets/admin/images/logo-sm.png')}}" class="logo-sm" alt="logo sm">
            <img src="{{ asset('storage/' . setting('site_logo')) }}" class="logo-lg" alt="logo dark">
        </a>

        <a href="{{route('admin.dashboard')}}" class="logo-light">
            <img src="{{asset('assets/admin/images/logo-sm.png')}}" class="logo-sm" alt="logo sm">
            <img src="{{ asset('storage/' . setting('site_logo')) }}" class="logo-lg" alt="logo light">
        </a>
    </div>

    <!-- Menu Toggle Button (sm-hover) -->
    <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
        <iconify-icon icon="solar:double-alt-arrow-right-bold-duotone"
                      class="button-sm-hover-icon"></iconify-icon>
    </button>

    <div class="scrollbar" data-simplebar>
        <ul class="navbar-nav" id="navbar-nav">

            <li class="menu-title">Tổng quan</li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('admin.dashboard')}}">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:widget-5-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Bảng điều khiển </span>
                </a>
            </li>

            @if(auth()->user()->isAdmin() || auth()->user()->isSale())
                <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarProducts" data-bs-toggle="collapse"
                       role="button" aria-expanded="false" aria-controls="sidebarProducts">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:t-shirt-bold-duotone"></iconify-icon>
                    </span>
                        <span class="nav-text"> Sản phẩm </span>
                    </a>
                    <div class="collapse" id="sidebarProducts">
                        <ul class="nav sub-navbar-nav">
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{route('admin.products.index')}}">Danh sách</a>
                            </li>
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{route('admin.products.create')}}">Tạo sản phẩm</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            @if(auth()->user()->isAdmin() || auth()->user()->isSale())
                <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarCategory" data-bs-toggle="collapse"
                       role="button" aria-expanded="false" aria-controls="sidebarCategory">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:clipboard-list-bold-duotone"></iconify-icon>
                    </span>
                        <span class="nav-text"> Loại nhóm </span>
                    </a>
                    <div class="collapse" id="sidebarCategory">
                        <ul class="nav sub-navbar-nav">
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{route('admin.categories.index')}}">Danh sách</a>
                            </li>
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{route('admin.categories.create')}}">Tạo</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarAttributes" data-bs-toggle="collapse"
                       role="button" aria-expanded="false" aria-controls="sidebarAttributes">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:confetti-minimalistic-bold-duotone"></iconify-icon>
                    </span>
                        <span class="nav-text"> Thuộc tính</span>
                    </a>
                    <div class="collapse" id="sidebarAttributes">
                        <ul class="nav sub-navbar-nav">
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{route('admin.attributes.index')}}">Danh sách</a>
                            </li>
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{route('admin.attributes.create')}}">Tạo</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarCoupons" data-bs-toggle="collapse"
                       role="button" aria-expanded="false" aria-controls="sidebarCoupons">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:leaf-bold-duotone"></iconify-icon>
                    </span>
                        <span class="nav-text"> Phiếu giảm giá</span>
                    </a>
                    <div class="collapse" id="sidebarCoupons">
                        <ul class="nav sub-navbar-nav">
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{route('admin.coupons.index')}}">Danh sách</a>
                            </li>
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{route('admin.coupons.create')}}">Tạo</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarBrands" data-bs-toggle="collapse"
                       role="button" aria-expanded="false" aria-controls="sidebarCoupons">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:ufo-2-bold-duotone"></iconify-icon>
                    </span>
                        <span class="nav-text"> Thương hiệu </span>
                    </a>
                    <div class="collapse" id="sidebarBrands">
                        <ul class="nav sub-navbar-nav">
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{route('admin.brands.index')}}">Danh sách</a>
                            </li>
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{route('admin.brands.create')}}">Tạo</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if(auth()->user()->isAdmin() || auth()->user()->isWarehouse())
                <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarSuppliers" data-bs-toggle="collapse"
                       role="button" aria-expanded="false" aria-controls="sidebarSuppliers">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:box-minimalistic-bold-duotone"></iconify-icon>
                    </span>
                        <span class="nav-text"> Nhà cung cấp </span>
                    </a>
                    <div class="collapse" id="sidebarSuppliers">
                        <ul class="nav sub-navbar-nav">
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{route('admin.suppliers.index')}}">Danh sách</a>
                            </li>
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{route('admin.suppliers.create')}}">Tạo</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarInventory" data-bs-toggle="collapse"
                       role="button" aria-expanded="false" aria-controls="sidebarInventory">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:box-bold-duotone"></iconify-icon>
                    </span>
                        <span class="nav-text"> Hàng tồn kho </span>
                    </a>
                    <div class="collapse" id="sidebarInventory">
                        <ul class="nav sub-navbar-nav">
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{route('admin.inventory.index')}}">Đơn đặt hàng</a>
                            </li>
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{route('admin.inventory.create')}}">Hàng nhập khẩu</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if(auth()->user()->isAdmin() || auth()->user()->isSale())
                <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarOrders" data-bs-toggle="collapse" role="button"
                       aria-expanded="false" aria-controls="sidebarOrders">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:bag-smile-bold-duotone"></iconify-icon>
                    </span>
                        <span class="nav-text"> Đơn đặt hàng </span>
                    </a>
                    <div class="collapse" id="sidebarOrders">
                        <ul class="nav sub-navbar-nav">

                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{route('admin.orders.index')}}">Danh sách</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarCustomers" data-bs-toggle="collapse"
                       role="button" aria-expanded="false" aria-controls="sidebarCustomers">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon>
                    </span>
                        <span class="nav-text"> Khách hàng </span>
                    </a>
                    <div class="collapse" id="sidebarCustomers">
                        <ul class="nav sub-navbar-nav">
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{ route('admin.customers.index') }}">Danh sách</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarUsers" data-bs-toggle="collapse"
                       role="button" aria-expanded="false" aria-controls="sidebarUsers">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:user-id-bold-duotone"></iconify-icon>
                    </span>
                        <span class="nav-text"> Quản lý tài khoản </span>
                    </a>
                    <div class="collapse" id="sidebarUsers">
                        <ul class="nav sub-navbar-nav">
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{ route('admin.users.index') }}">Danh sách</a>
                            </li>
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{ route('admin.users.create') }}">Tạo tài khoản</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            <!-- ========== Blog Posts Menu Start ========== -->
            {{-- <li class="nav-item">--}}
            {{-- <a class="nav-link menu-arrow" href="#sidebarBlog" data-bs-toggle="collapse"--}}
            {{-- role="button" aria-expanded="false" aria-controls="sidebarBlog">--}}
            {{-- <span class="nav-icon">--}}
            {{-- <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>--}}
            {{-- </span>--}}
            {{-- <span class="nav-text"> Blog Posts </span>--}}
            {{-- </a>--}}
            {{-- <div class="collapse" id="sidebarBlog">--}}
            {{-- <ul class="nav sub-navbar-nav">--}}
            {{-- <li class="sub-nav-item">--}}
            {{-- <a class="sub-nav-link" href="{{ route('admin.blog-posts.index') }}">List</a>--}}
            {{-- </li>--}}
            {{-- <li class="sub-nav-item">--}}
            {{-- <a class="sub-nav-link" href="{{ route('admin.blog-posts.create') }}">Create</a>--}}
            {{-- </li>--}}
            {{-- --}}{{-- Optional: Add Categories later --}}
            {{-- --}}{{-- <li class="sub-nav-item">--}}
            {{-- <a class="sub-nav-link" href="#">Categories</a>--}}
            {{-- </li> --}}
            {{-- </ul>--}}
            {{-- </div>--}}
            {{-- </li>--}}
            <!-- ========== Blog Posts Menu End ========== -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarChatbot" data-bs-toggle="collapse"
                   role="button" aria-expanded="false" aria-controls="sidebarChatbot">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:chat-round-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Chat bot </span>
                </a>
                <div class="collapse" id="sidebarChatbot">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.chatbot.index') }}">Câu trả lời nhanh</a>
                        </li>
                        {{-- Training Center has been disabled - keyword matching removed --}}
                        {{-- <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.chatbot-replies.index') }}">Training
                        Center</a>
            </li> --}}
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.chat.index') }}">
                    <span class="nav-icon"><iconify-icon icon="solar:chat-line-bold-duotone"></iconify-icon></span>
                    <span class="nav-text"> Trò chuyện hỗ trợ </span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link " href="{{route('admin.reviews.index')}}">
            <span class="nav-icon">
                <iconify-icon icon="solar:chat-square-like-bold-duotone"></iconify-icon>
            </span>
                    <span class="nav-text"> Đánh giá </span>
                </a>
            </li>
            @if(auth()->user()->isAdmin() )
                <li class="nav-item ">
                    <a class="nav-link " href="{{route('admin.settings.index')}}">
            <span class="nav-icon">
                <iconify-icon icon="solar:settings-bold-duotone"></iconify-icon>
            </span>
                        <span class="nav-text"> Cài đặt</span>
                    </a>
                </li>
            @endif
            {{-- <li class="nav-item">--}}
            {{-- <a class="nav-link" href="{{ route('admin.scraper.index') }}">--}}
            {{-- <span class="nav-icon">--}}
            {{-- <iconify-icon icon="solar:cloud-download-bold-duotone"></iconify-icon>--}}
            {{-- </span>--}}
            {{-- <span class="nav-text"> Product Scraper </span>--}}
            {{-- </a>--}}
            {{-- </li>--}}
        </ul>
    </div>
</div>
<!-- ========== App Menu End ========== -->
