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
    <link rel="shortcut icon" href="{{asset('template/admin/assets/images/favicon.ico')}}">

    <!-- App css -->
    <link href="{{asset('template/admin/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('template/admin/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('template/admin/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />

</head>

<body>
@include('admin.layouts.sidebar')
<div class="page-wrapper">
    <!-- Page Content-->
    <div class="page-content">
        @yield('content')

    </div>
</div>
@include('admin.layouts.footer')
<!-- vendor js -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
<script src="{{asset('template/admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('template/admin/assets/libs/simplebar/simplebar.min.js')}}"></script>

<script src="{{asset('template/admin/assets/libs/apexcharts/apexcharts.min.js')}}"></script>
<script src="{{asset('template/admin/assets/js/pages/index.init.js')}}"></script>
<script src="{{asset('template/admin/assets/js/DynamicSelect.js')}}"></script>
<script src="{{asset('template/admin/assets/js/app.js')}}"></script>
</body>

</html>
