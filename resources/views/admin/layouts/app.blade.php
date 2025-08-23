<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<head>
    <meta charset="utf-8" />
    <title>Dashboard | Approx - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
    <!-- Vendor css (Require in all Page) -->
    <link href="{{asset('assets/css/vendor.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons css (Require in all Page) -->
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App css (Require in all Page) -->
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Theme Config js (Require in all Page) -->
    <script src="{{asset('assets/js/config.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>

<body>


<!-- START Wrapper -->
<div class="wrapper">
    @include('admin.layouts.header')
    @include('admin.layouts.timeline')
    @include('admin.layouts.sidebar')
    @include('admin.layouts.menu')
    <div class="page-content">
        
        <!-- Page Content-->
        @yield('content')
        @include('admin.layouts.footer')
        <!-- end page content -->
    </div>


</div>

<!-- Vendor Javascript (Require in all Page) -->
<script src="{{asset('assets/js/vendor.js')}}"></script>

<!-- App Javascript (Require in all Page) -->
<script src="{{asset('assets/js/app.js')}}"></script>

<!-- Vector Map Js -->
<script src="{{asset('assets/vendor/jsvectormap/js/jsvectormap.min.js')}}"></script>
<script src="{{asset('assets/vendor/jsvectormap/maps/world-merc.js')}}"></script>
<script src="{{asset('assets/vendor/jsvectormap/maps/world.js')}}"></script>

<!-- Dashboard Js -->
<script src="{{asset('assets/js/pages/dashboard.js')}}"></script>
<x-toast />
</body>

</html>
