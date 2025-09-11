@extends('storefront.layouts.app')

@section('content')
    <main class="main-content">
        @push('body-class', 'page-wishlist')
        <!--== Start Page Header Area Wrapper ==-->
        <nav aria-label="breadcrumb" class="breadcrumb-style1">
            <div class="container">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
                </ol>
            </div>
        </nav>
        <!--== End Page Header Area Wrapper ==-->

        <!--== Start Wishlist Area Wrapper ==-->
        <section class="section-space">
            <div class="container">
                <div class="shopping-wishlist-form table-responsive">
                    <table class="table text-center">
                        <thead>
                        <tr>
                            <th class="product-remove">&nbsp;</th>
                            <th class="product-thumbnail">&nbsp;</th>
                            <th class="product-name">Product name</th>
                            <th class="product-price">Unit price</th>
                            <th class="product-stock-status">Stock status</th>
                            <th class="product-add-to-cart">&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($products as $product)
                            <tr class="tbody-item">
                                <td class="product-remove">
                                    {{-- Nút này giờ sẽ được JS xử lý --}}
                                    <a href="javascript:void(0)" class="remove action-btn-wishlist"
                                       data-product-id="{{ $product->id }}">×</a>
                                </td>
                                <td class="product-thumbnail">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <img src="{{ $product->image ? asset('storage/' . $product->image) : '' }}"
                                             width="68" height="84" alt="Image">
                                    </a>
                                </td>
                                <td class="product-name">
                                    <a class="title"
                                       href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                </td>
                                <td class="product-price">
                                    @if($product->variants->first())
                                        <span class="price">{{ number_format($product->variants->first()->price, 0, ',', '.') }} VNĐ</span>
                                    @endif
                                </td>
                                <td class="product-stock-status">
                                    @if($product->variants->sum('stock') > 0)
                                        <span class="wishlist-in-stock">In Stock</span>
                                    @else
                                        <span class="wishlist-out-of-stock">Out of Stock</span>
                                    @endif
                                </td>
                                <td class="product-add-to-cart">
                                    @if($product->variants->first())
                                        <button class="btn-shop-cart action-btn-cart"
                                                data-variant-id="{{ $product->variants->first()->id }}">Add to Cart
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">Your wishlist is empty.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <!--== End Wishlist Area Wrapper ==-->

    </main>
    @push('scripts')
        <script>
            document.body.classList.add('page-wishlist');
        </script>
    @endpush
@endsection
