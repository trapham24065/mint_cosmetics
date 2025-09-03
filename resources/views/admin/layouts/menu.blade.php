<!-- ========== App Menu Start ========== -->
<div class="main-nav">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="{{route('admin.dashboard')}}" class="logo-dark">
            <img src="{{asset('assets/admin/images/logo-sm.png')}}" class="logo-sm" alt="logo sm">
            <img src="{{asset('assets/admin/images/logo-dark.png')}}" class="logo-lg" alt="logo dark">
        </a>

        <a href="{{route('admin.dashboard')}}" class="logo-light">
            <img src="{{asset('assets/admin/images/logo-sm.png')}}" class="logo-sm" alt="logo sm">
            <img src="{{asset('assets/admin/images/logo-light.png')}}" class="logo-lg" alt="logo light">
        </a>
    </div>

    <!-- Menu Toggle Button (sm-hover) -->
    <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
        <iconify-icon icon="solar:double-alt-arrow-right-bold-duotone"
                      class="button-sm-hover-icon"></iconify-icon>
    </button>

    <div class="scrollbar" data-simplebar>
        <ul class="navbar-nav" id="navbar-nav">

            <li class="menu-title">General</li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('admin.dashboard')}}">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:widget-5-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text"> Dashboard </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarProducts" data-bs-toggle="collapse"
                   role="button" aria-expanded="false" aria-controls="sidebarProducts">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:t-shirt-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text"> Products </span>
                </a>
                <div class="collapse" id="sidebarProducts">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{route('admin.products.index')}}">List</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{route('admin.products.create')}}">Create</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarCategory" data-bs-toggle="collapse"
                   role="button" aria-expanded="false" aria-controls="sidebarCategory">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:clipboard-list-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text"> Category </span>
                </a>
                <div class="collapse" id="sidebarCategory">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{route('admin.categories.index')}}">List</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{route('admin.categories.create')}}">Create</a>
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
                    <span class="nav-text"> Attributes </span>
                </a>
                <div class="collapse" id="sidebarAttributes">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{route('admin.attributes.index')}}">List</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{route('admin.attributes.create')}}">Create</a>
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
                    <span class="nav-text"> Coupons </span>
                </a>
                <div class="collapse" id="sidebarCoupons">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{route('admin.coupons.index')}}">List</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{route('admin.coupons.create')}}">Add</a>
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
                    <span class="nav-text"> Brands </span>
                </a>
                <div class="collapse" id="sidebarBrands">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{route('admin.brands.index')}}">List</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{route('admin.brands.create')}}">Add</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarOrders" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarOrders">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:bag-smile-bold-duotone"></iconify-icon>
                                   </span>
                    <span class="nav-text"> Orders </span>
                </a>
                <div class="collapse" id="sidebarOrders">
                    <ul class="nav sub-navbar-nav">

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{route('admin.orders.index')}}">List</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
<!-- ========== App Menu End ========== -->
