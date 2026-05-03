@php
$currentCategorySlug = request('category');
$isOnShop = request()->routeIs('shop');
@endphp
{{-- Megamenu Navigation Component --}}
<div class="megamenu-wrapper">
    <!-- Desktop Megamenu -->
    <ul class="megamenu-list d-none d-lg-flex">
        <li class="megamenu-item">
            <a href="{{ route('home') }}"
                class="megamenu-link {{ request()->routeIs('home') ? 'active' : '' }}">Trang chủ</a>
        </li>

        {{-- Category Megamenu --}}
        @if($categoryTree && count($categoryTree) > 0)
        <li class="megamenu-item megamenu-category-item">
            <a href="{{ route('shop') }}"
                class="megamenu-link megamenu-category-toggle {{ $isOnShop ? 'active' : '' }}">
                Danh mục
                <span class="megamenu-arrow">
                    <svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </span>
            </a>

            <!-- Megamenu Dropdown (Cascade) -->
            <div class="megamenu-dropdown megamenu-dropdown-cascade">
                <ul class="megamenu-cascade-list">
                    @foreach($categoryTree as $category)
                    @include('storefront.partials.megamenu-cascade-item', [
                    'category' => $category,
                    'currentCategorySlug' => $currentCategorySlug,
                    ])
                    @endforeach
                </ul>
            </div>
        </li>
        @endif

        <li class="megamenu-item">
            <a href="{{ route('about-us.index') }}"
                class="megamenu-link {{ request()->routeIs('about-us.*') ? 'active' : '' }}">Về chúng tôi</a>
        </li>

        <li class="megamenu-item">
            <a href="{{ route('contact.index') }}"
                class="megamenu-link {{ request()->routeIs('contact.*') ? 'active' : '' }}">Liên hệ</a>
        </li>
    </ul>

</div>