<li>
    <div class="category-item">
        <a href="{{ route('shop', array_merge($queryParams, ['category' => $category->slug])) }}"
            class="category-link {{ $selectedCategorySlug === $category->slug ? 'active' : '' }}">
            {{ $category->name }}
        </a>
        <span>({{ (int) ($category->subtree_products_count ?? 0) }})</span>
    </div>

    @if($category->children->isNotEmpty())
    <ul class="category-children product-widget-category">
        @foreach($category->children as $childCategory)
        @include('storefront.partials.category-tree-item', [
        'category' => $childCategory,
        'selectedCategorySlug' => $selectedCategorySlug,
        'queryParams' => $queryParams,
        ])
        @endforeach
    </ul>
    @endif
</li>