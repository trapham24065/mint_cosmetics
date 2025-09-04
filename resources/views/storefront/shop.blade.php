@extends('storefront.layouts.app')
@section('content')
    <main class="main-content">

        <!--== Start Page Header Area Wrapper ==-->
        <section class="page-header-area pt-10 pb-9" data-bg-color="#FFF3DA">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <div class="page-header-st3-content text-center text-md-start">
                            <ol class="breadcrumb justify-content-center justify-content-md-start">
                                <li class="breadcrumb-item"><a class="text-dark" href="{{route('home')}}">Home</a></li>
                                <li class="breadcrumb-item active text-dark" aria-current="page">Products</li>
                            </ol>
                            <h2 class="page-header-title">All Products</h2>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h5 class="showing-pagination-results mt-5 mt-md-9 text-center text-md-end">Showing 09
                            Results</h5>
                    </div>
                </div>
            </div>
        </section>
        <!--== End Page Header Area Wrapper ==-->

        <!--== Start Shop Top Bar Area Wrapper ==-->
        <div class="shop-top-bar-area">
            <div class="container">
                {{-- Wrap the entire bar in a form --}}
                <form id="product-filter-form" action="{{ route('shop') }}" method="GET">
                    <div class="shop-top-bar">
                        <select name="sort" class="select-shoing">
                            <option value="trending" @selected(request('sort') === 'trending')>Trending</option>
                            <option value="best-selling" @selected(request('sort') === 'best-selling')>Best Selling
                            </option>
                        </select>

                        <div class="select-price-range">
                            <h4 class="title">Pricing</h4>
                            <div class="select-price-range-slider">
                                <div class="slider-range" id="slider-range"></div>
                                {{-- Hidden inputs to store and submit price values --}}
                                <input type="hidden" name="min_price" id="min-price"
                                       value="{{ request('min_price') }}">
                                <input type="hidden" name="max_price" id="max-price"
                                       value="{{ request('max_price') }}">
                                <div class="slider-labels">
                                    <span id="slider-range-value1"></span>
                                    <span>-</span>
                                    <span id="slider-range-value2"></span>
                                </div>
                            </div>
                        </div>

                        <div class="select-on-sale d-none d-md-flex">
                            <span>On Sale :</span>
                            <select name="on_sale" class="select-on-sale-form">
                                <option value="no" @selected(request('on_sale') === 'no')>No</option>
                                <option value="yes" @selected(request('on_sale') === 'yes')>Yes</option>
                            </select>
                        </div>

                        {{-- A submit button for the form --}}
                        <button type="submit" class="btn btn-primary btn-sm ms-4">Apply Filter</button>
                        <a href="{{ route('shop') }}" class="btn btn-outline-secondary btn-sm ms-2">Reset Filter</a>

                    </div>
                </form>
            </div>
        </div>
        <!--== End Shop Top Bar Area Wrapper ==-->

        <!--== Start Product Category Area Wrapper ==-->
        <section class="section-space pb-0">
            <div class="container">
                <div class="row g-3 g-sm-6">
                    {{-- DYNAMIC CATEGORY LIST --}}
                    @foreach($categories->take(6) as $category)
                        {{-- Show up to 6 categories --}}
                        <div class="col-6 col-lg-4 col-lg-2 col-xl-2">
                            <a href="{{route('shop', ['category' => $category->slug]) }}"
                               class="product-category-item">
                                {{-- You would need to add an icon field to your category table for this image --}}

                                <img
                                    class="icon"
                                    src="{{ $category->image ? asset('storage/' . $category->image) : asset('assets/admin/images/default-category.png') }}"
                                    alt="{{ $category->name }}" width="70" height="80">
                                <h3 class="title">{{ $category->name }}</h3>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!--== End Product Category Area Wrapper ==-->

        <!--== Start Product Area Wrapper ==-->
        <section class="section-space">
            <div class="container">
                <div class="row mb-n4 mb-sm-n10 g-3 g-sm-6">
                    {{-- DYNAMIC PRODUCT GRID --}}
                    @forelse ($products as $product)
                        <div class="col-6 col-lg-4 mb-4 mb-sm-8">
                            {{-- Call the component and pass the product data into it --}}
                            <x-product-card :product="$product" />
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center">No products found matching your criteria.</p>
                        </div>
                    @endforelse
                    <div class="col-12">
                        {{-- DYNAMIC PAGINATION --}}
                        <div class="mt-5">
                            {{ $products->appends(request()->query())->links('vendor.pagination.storefront-pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--== End Product Area Wrapper ==-->
    </main>
    @push('scripts')
        {{-- Ensure you load jQuery and the range-slider.js plugin in your main layout --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // --- Price Range Slider Logic ---
                const rangeSlider = document.getElementById('slider-range');
                if (rangeSlider) {
                    const minPriceInput = document.getElementById('min-price');
                    const maxPriceInput = document.getElementById('max-price');
                    const minPriceDisplay = document.getElementById('slider-range-value1');
                    const maxPriceDisplay = document.getElementById('slider-range-value2');
                    const maxPriceValue = {{ $maxPrice ?? 0 }};
                    const minPriceValue = {{ $minPrice ?? 0 }};

                    noUiSlider.create(rangeSlider, {
                        start: [
                            minPriceInput.value || minPriceValue,
                            maxPriceInput.value || maxPriceValue,
                        ],
                        connect: true,
                        range: {
                            'min': minPriceValue,
                            'max': maxPriceValue,
                        },
                        format: {
                            to: function(value) { return Math.round(value); },
                            from: function(value) { return Number(value); },
                        },
                    });

                    rangeSlider.noUiSlider.on('update', function(values, handle) {
                        const value = values[handle];
                        if (handle === 0) {
                            minPriceInput.value = value;
                            minPriceDisplay.textContent = value.toLocaleString('vi-VN') + ' VNĐ';
                        } else {
                            maxPriceInput.value = value;
                            maxPriceDisplay.textContent = value.toLocaleString('vi-VN') + ' VNĐ';
                        }
                    });
                }

                // --- Auto-submit form on change ---
                const filterForm = document.getElementById('product-filter-form');
                filterForm.querySelectorAll('select').forEach(select => {
                    select.addEventListener('change', () => {
                        filterForm.submit();
                    });
                });
            });
        </script>
    @endpush
@endsection
