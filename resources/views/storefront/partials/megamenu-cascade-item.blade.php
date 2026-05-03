@php
    $hasChildren = $category->children && $category->children->count() > 0;
@endphp
<li class="megamenu-cascade-item {{ $hasChildren ? 'has-cascade-submenu' : '' }}">
    <a href="{{ route('shop', ['category' => $category->slug]) }}"
       class="megamenu-cascade-link {{ $currentCategorySlug === $category->slug ? 'active' : '' }}"
       title="{{ $category->name }}">
        <span class="megamenu-link-text">{{ $category->name }}</span>
        <span class="megamenu-cascade-meta">
            @if($category->subtree_products_count > 0)
                <span class="product-count">{{ $category->subtree_products_count }}</span>
            @endif
            @if($hasChildren)
                <span class="megamenu-cascade-arrow" aria-hidden="true">
                    <svg width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1L5 5L1 9" stroke="currentColor" stroke-width="1.5"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            @endif
        </span>
    </a>

    @if($hasChildren)
        <ul class="megamenu-cascade-submenu">
            @foreach($category->children as $child)
                @include('storefront.partials.megamenu-cascade-item', [
                    'category' => $child,
                    'currentCategorySlug' => $currentCategorySlug,
                ])
            @endforeach
        </ul>
    @endif
</li>
