{{-- Megamenu Navigation Component --}}
<div class="megamenu-wrapper">
    <!-- Desktop Megamenu -->
    <ul class="megamenu-list d-none d-lg-flex">
        <li class="megamenu-item">
            <a href="{{ route('home') }}" class="megamenu-link">Trang chủ</a>
        </li>

        {{-- Category Megamenu --}}
        @if($categoryTree && count($categoryTree) > 0)
            <li class="megamenu-item megamenu-category-item">
                <a href="{{ route('shop') }}" class="megamenu-link megamenu-category-toggle">
                    Danh mục
                    <span class="megamenu-arrow">
                    <svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                              stroke-linejoin="round" />
                    </svg>
                </span>
                </a>

                <!-- Megamenu Dropdown -->
                <div class="megamenu-dropdown">
                    <div class="megamenu-content">
                        @php $categoryCount = 0; @endphp
                        @foreach($categoryTree as $category)
                            @if($category->children->count() > 0)
                                @php $categoryCount++; @endphp
                                <div class="megamenu-column" data-column="{{ $categoryCount }}">
                                    <h5 class="megamenu-column-title">
                                        <a href="{{ route('shop', ['category' => $category->slug]) }}"
                                           class="megamenu-parent-link">
                                            {{ $category->name }}
                                            @if($category->subtree_products_count > 0)
                                                <span
                                                    class="product-count">({{ $category->subtree_products_count }})</span>
                                            @endif
                                        </a>
                                    </h5>
                                    <ul class="megamenu-sublist">
                                        @foreach($category->children as $child)
                                            <li>
                                                <a href="{{ route('shop', ['category' => $child->slug]) }}"
                                                   class="megamenu-sublink">
                                                    {{ $child->name }}
                                                    @if($child->subtree_products_count > 0)
                                                        <span class="product-count">({{ $child->subtree_products_count }})</span>
                                                    @endif
                                                </a>
                                                {{-- Show 3rd level if exists --}}
                                                @if($child->children->count() > 0)
                                                    <ul class="megamenu-sublist-level-3">
                                                        @foreach($child->children->take(5) as $grandchild)
                                                            <li>
                                                                <a href="{{ route('shop', ['category' => $grandchild->slug]) }}"
                                                                   class="megamenu-sub-item">
                                                                    {{ $grandchild->name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                        @if($child->children->count() > 5)
                                                            <li>
                                                                <a href="{{ route('shop', ['category' => $child->slug]) }}"
                                                                   class="megamenu-sub-item megamenu-view-more">
                                                                    Xem thêm...
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                {{-- Limit columns to 4 for layout --}}
                                @if($categoryCount >= 4)
                                    @break
                                @endif
                            @endif
                        @endforeach
                    </div>

                </div>
            </li>
        @endif

        <li class="megamenu-item">
            <a href="{{ route('about-us.index') }}" class="megamenu-link">Về chúng tôi</a>
        </li>

        <li class="megamenu-item">
            <a href="{{ route('contact.index') }}" class="megamenu-link">Liên hệ</a>
        </li>
    </ul>

</div>
