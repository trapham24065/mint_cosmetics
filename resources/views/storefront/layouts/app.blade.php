<!DOCTYPE html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Brancy - Cosmetic & Beauty Salon Website Template</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="Brancy - Cosmetic & Beauty Salon Website Template">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords"
          content="bootstrap, ecommerce, ecommerce html, beauty, cosmetic shop, beauty products, cosmetic, beauty shop, cosmetic store, shop, beauty store, spa, cosmetic, cosmetics, beauty salon" />
    <meta name="author" content="codecarnival" />

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/storefront/images/favicon.webp')}}">

    <!-- CSS (Font, Vendor, Icon, Plugins & Style CSS files) -->

    <!-- Font CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap"
          rel="stylesheet">

    <!-- Vendor CSS (Bootstrap & Icon Font) -->
    <link rel="stylesheet" href="{{asset('assets/storefront/css/vendor/bootstrap.min.css')}}">

    <!-- Plugins CSS (All Plugins Files) -->
    <link rel="stylesheet" href="{{asset('assets/storefront/css/plugins/swiper-bundle.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/storefront/css/plugins/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/storefront/css/plugins/fancybox.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/storefront/css/plugins/range-slider.css')}}">
    <link rel="stylesheet" href="{{asset('assets/storefront/css/plugins/nice-select.css')}}">

    <!-- Style CSS -->
    <link rel="stylesheet" href="{{asset('assets/storefront/css/style.min.css')}}">

</head>

<body>
<!--== Wrapper Start ==-->
<div class="wrapper">
    @include('storefront.partials.header')
    @include('storefront.partials.aside-search')
    @include('storefront.partials.aside-cart')
    @include('storefront.partials.aside-menu')
    @include('storefront.layouts.quick-view-modal')
    @include('storefront.layouts.quick-add-cart-modal')
    @include('storefront.layouts.quick-wishlist-modal')
    @yield('content')
    <!--== Scroll Top Button ==-->
    <div id="scroll-to-top" class="scroll-to-top"><span class="fa fa-angle-up"></span></div>

    @include('storefront.partials.footer')
</div>

<script src="{{asset('assets/storefront/js/vendor/modernizr-3.11.7.min.js')}}"></script>
<script src="{{asset('assets/storefront/js/vendor/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/storefront/js/vendor/jquery-migrate-3.3.2.min.js')}}"></script>
<script src="{{asset('assets/storefront/js/vendor/bootstrap.bundle.min.js')}}"></script>

<!-- Plugins JS -->
<script src="{{asset('assets/storefront/js/plugins/swiper-bundle.min.js')}}"></script>
<script src="{{asset('assets/storefront/js/plugins/fancybox.min.js')}}"></script>
<script src="{{asset('assets/storefront/js/plugins/range-slider.js')}}"></script>
<script src="{{asset('assets/storefront/js/plugins/jquery.nice-select.min.js')}}"></script>

<!-- Custom Main JS -->
<script src="{{asset('assets/storefront/js/main.js')}}"></script>

</body>


</html>
