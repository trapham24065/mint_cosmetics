@extends('storefront.layouts.app')
@section('content')
<style>
    /* === Category Tree Widget === */
    .product-widget-category {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .product-widget-category .category-tree-item {
        list-style: none;
        margin-bottom: 2px;
    }

    .product-widget-category .category-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 8px 12px;
        border-radius: 8px;
        transition: background-color .2s ease;
    }

    .product-widget-category .category-item:hover {
        background-color: #fff5f5;
    }

    .product-widget-category .category-link {
        flex: 1;
        min-width: 0;
        font-size: 14px;
        color: #364958;
        text-decoration: none;
        line-height: 1.4;
        transition: color .2s ease;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        word-break: break-word;
    }

    .product-widget-category .category-link:hover {
        color: #ff6565;
    }

    .product-widget-category .category-parent-toggle {
        border: 0;
        background: transparent;
        padding: 0;
        text-align: left;
        cursor: pointer;
        font: inherit;
        color: #364958;
    }

    .product-widget-category .category-link.active,
    .product-widget-category .category-parent-toggle.active,
    .product-widget-category .category-all-link.active {
        color: #ff6565;
        font-weight: 600;
    }

    .product-widget-category .category-item:has(.active) {
        background-color: #fff5f5;
    }

    .product-widget-category .category-actions {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }

    .product-widget-category .category-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 26px;
        height: 22px;
        padding: 0 8px;
        background: #f3f3f3;
        color: #6b6b6b;
        font-size: 12px;
        font-weight: 500;
        border-radius: 11px;
        transition: background-color .2s ease, color .2s ease;
    }

    .product-widget-category .category-item:hover .category-count,
    .product-widget-category .category-item:has(.active) .category-count {
        background: #ffe5e5;
        color: #ff6565;
    }

    .product-widget-category .category-toggle {
        width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 0;
        background: transparent;
        color: #888;
        border-radius: 50%;
        padding: 0;
        line-height: 1;
        transition: background-color .2s ease, color .2s ease;
    }

    .product-widget-category .category-toggle:hover {
        background: #ffe5e5;
        color: #ff6565;
    }

    .product-widget-category .category-toggle:focus {
        outline: none;
        box-shadow: none;
    }

    .product-widget-category .category-toggle i {
        font-size: 14px;
        transition: transform .25s ease;
    }

    .product-widget-category .category-toggle[aria-expanded="true"] i {
        transform: rotate(180deg);
    }

    .product-widget-category .category-children {
        list-style: none;
        margin: 4px 0 6px 4px;
        padding: 4px 0 4px 14px;
        border-left: 2px solid #ffd6d6;
    }

    .product-widget-category .category-tree-item-all {
        list-style: none;
        margin: 0 0 6px 12px;
    }

    .product-widget-category .category-all-link {
        display: inline-block;
        font-size: 12px;
        color: #6b6b6b;
        text-decoration: none;
        padding: 3px 10px;
        border-radius: 12px;
        background: #f7f7f7;
        transition: background-color .2s ease, color .2s ease;
    }

    .product-widget-category .category-all-link:hover,
    .product-widget-category .category-all-link.active {
        color: #ff6565;
        background: #ffe5e5;
    }

    .product-widget-brand li {
        margin-bottom: 8px;
    }

    .product-widget-brand label {
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
</style>
<main class="main-content">

    <!--== Start Page Header Area Wrapper ==-->
    <section class="page-header-area pt-10 pb-9" data-bg-color="#FFF3DA">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="page-header-st3-content text-center text-md-start">
                        <ol class="breadcrumb justify-content-center justify-content-md-start">
                            <li class="breadcrumb-item"><a class="text-dark" href="{{route('home')}}">Trang chủ</a>
                            </li>
                            <li class="breadcrumb-item active text-dark" aria-current="page">Sản phẩm</li>
                        </ol>
                        <h2 class="page-header-title">Tất cả sản phẩm</h2>
                    </div>
                </div>
                <div class="col-md-7">
                    <h5 class="showing-pagination-results mt-5 mt-md-9 text-center text-md-end">

                        Hiển thị
                        {{ $products->firstItem() ?? 0 }}
                        -
                        {{ $products->lastItem() ?? 0 }}

                        của

                        {{ $products->total() }}

                        kết quả

                    </h5>
                </div>
            </div>
        </div>
    </section>
    <!--== End Page Header Area Wrapper ==-->

    <!--== Start Product Area Wrapper ==-->
    <section class="section-space">
        <div class="container">
            <div class="row justify-content-between flex-xl-row-reverse">
                <div class="col-xl-9">
                    <div class="row g-3 g-sm-6">
                        @forelse ($products as $product)
                        <div class="col-6 col-lg-4 mb-4 mb-sm-8">
                            {{-- Call the component and pass the product data into it --}}
                            <x-product-card :product="$product" />
                        </div>
                        @empty
                        <div class="col-12">
                            <p class="text-center">Không tìm thấy sản phẩm nào phù hợp với tiêu chí tìm kiếm của
                                bạn.</p>
                        </div>
                        @endforelse

                        <div class="col-12">
                            {{-- DYNAMIC PAGINATION --}}
                            {{ $products->appends(request()->query())->links('vendor.pagination.storefront-pagination') }}
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <form id="sidebar-filter-form" action="{{ route('shop') }}" method="GET">
                        @foreach(request()->except(['min_price','max_price']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <div class="product-sidebar-widget">

                            {{-- SEARCH --}}
                            <div class="product-widget-search">

                                <input type="search"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Tìm kiếm ở đây">

                                <button type="submit">
                                    <i class="fa fa-search"></i>
                                </button>

                            </div>


                            {{-- CATEGORY --}}
                            <div class="product-widget">

                                <h4 class="product-widget-title">Thể loại</h4>

                                <ul class="product-widget-category">
                                    @foreach(($categoryTree ?? collect()) as $category)
                                    @include('storefront.partials.category-tree-item', [
                                    'category' => $category,
                                    'selectedCategorySlug' => request('category'),
                                    'queryParams' => request()->query(),
                                    'expandedCategoryIds' => $expandedCategoryIds ?? [],
                                    ])
                                    @endforeach

                                </ul>

                            </div>


                            {{-- BRAND --}}
                            <div class="product-widget">

                                <h4 class="product-widget-title">Thương hiệu</h4>

                                <ul class="product-widget-brand">

                                    @foreach($brands as $brand)

                                    <li>

                                        <label>

                                            <input type="radio"
                                                name="brand"
                                                value="{{ $brand->slug }}"
                                                {{ request('brand')===$brand->slug ? 'checked':'' }}
                                                onchange="this.form.submit()">

                                            <span>{{ $brand->name }}</span>

                                        </label>

                                    </li>

                                    @endforeach

                                </ul>

                            </div>


                            {{-- PRICE SLIDER (GIỮ UI CŨ) --}}
                            <div class="product-widget">

                                <h4 class="product-widget-title">Định giá</h4>

                                <div class="product-widget-range-slider">

                                    <div id="slider-range"></div>

                                    <input type="hidden"
                                        name="min_price"
                                        id="min-price"
                                        value="{{ request('min_price') }}">

                                    <input type="hidden"
                                        name="max_price"
                                        id="max-price"
                                        value="{{ request('max_price') }}">

                                    <div class="slider-labels">

                                        <span id="slider-range-value1"></span>
                                        <span>-</span>
                                        <span id="slider-range-value2"></span>

                                    </div>

                                </div>

                            </div>


                            <a href="{{ route('shop') }}" class="btn btn-light mt-3">
                                Xóa bộ lọc
                            </a>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
    <!--== End Product Area Wrapper ==-->

</main>
@push('scripts')
<!-- @formatter:off -->

        {{-- Ensure you load jQuery and the range-slider.js plugin in your main layout --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const slider = document.getElementById('slider-range');

                if(!slider) return;

                const minInput = document.getElementById('min-price');
                const maxInput = document.getElementById('max-price');

                const minDisplay = document.getElementById('slider-range-value1');
                const maxDisplay = document.getElementById('slider-range-value2');

                const form = document.getElementById('sidebar-filter-form');

                const minPrice = {{ $minPrice }};
                const maxPrice = {{ $maxPrice }};

                noUiSlider.create(slider, {

                    start: [
                        minInput.value || minPrice,
                        maxInput.value || maxPrice
                    ],

                    connect:true,

                    range:{
                        min:minPrice,
                        max:maxPrice
                    },

                    format:{
                        to: v => Math.round(v),
                        from: v => Number(v)
                    }

                });

                slider.noUiSlider.on('update',function(values,handle){

                    if(handle===0){

                        minInput.value = values[0];
                        minDisplay.textContent =
                            Number(values[0]).toLocaleString('vi-VN')+' VNĐ';

                    }else{

                        maxInput.value = values[1];
                        maxDisplay.textContent =
                            Number(values[1]).toLocaleString('vi-VN')+' VNĐ';

                    }

                });

                slider.noUiSlider.on('change',function(){
                    form.submit();
                });

            });
        </script>
                    <!-- @formatter:on -->

@endpush
@endsection