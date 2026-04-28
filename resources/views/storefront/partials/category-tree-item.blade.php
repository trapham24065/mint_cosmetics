@php
$hasChildren = $category->children->isNotEmpty();
$isExpanded = $hasChildren && in_array($category->id, $expandedCategoryIds ?? [], true);
$isLeaf = ! $hasChildren;
@endphp
<style>
    .product-sidebar-widget .product-widget-category li:first-child a {}
</style>
<li class="category-tree-item">
    <div class="category-item">
        @if($isLeaf)
        <a href="{{ route('shop', array_merge($queryParams, ['category' => $category->slug])) }}"
            class="category-link {{ $selectedCategorySlug === $category->slug ? 'active' : '' }}"
            title="{{ $category->name }}">
            {{ $category->name }}
        </a>
        @else
        <button
            type="button"
            class="category-link category-parent-toggle {{ in_array($category->id, $expandedCategoryIds ?? [], true) ? 'active' : '' }}"
            data-bs-toggle="collapse"
            data-bs-target="#category-children-{{ $category->id }}"
            aria-expanded="{{ $isExpanded ? 'true' : 'false' }}"
            aria-controls="category-children-{{ $category->id }}"
            title="{{ $category->name }}">
            {{ $category->name }}
        </button>
        @endif

        <div class="category-actions">
            <span class="category-count">({{ (int) ($category->subtree_products_count ?? 0) }})</span>

            @if($hasChildren)
            <button
                type="button"
                class="category-toggle"
                data-bs-toggle="collapse"
                data-bs-target="#category-children-{{ $category->id }}"
                aria-expanded="{{ $isExpanded ? 'true' : 'false' }}"
                aria-controls="category-children-{{ $category->id }}">
                <i class="bi bi-chevron-down"></i>
            </button>
            @endif
        </div>
    </div>

    @if($hasChildren)
    <div class="collapse {{ $isExpanded ? 'show' : '' }}" id="category-children-{{ $category->id }}">
        <ul class="category-children product-widget-category">
            <li class="category-tree-item-all">
                <a href="{{ route('shop', array_merge($queryParams, ['category' => $category->slug])) }}"
                    class="category-all-link {{ $selectedCategorySlug === $category->slug ? 'active' : '' }}"
                    title="Xem tất cả">
                    Xem tất cả
                </a>
            </li>
            @foreach($category->children as $childCategory)
            @include('storefront.partials.category-tree-item', [
            'category' => $childCategory,
            'selectedCategorySlug' => $selectedCategorySlug,
            'queryParams' => $queryParams,
            'expandedCategoryIds' => $expandedCategoryIds,
            ])
            @endforeach
        </ul>
    </div>
    @endif
</li>