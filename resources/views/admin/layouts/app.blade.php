{{--    <!-- Fonts -->--}}
{{--    <link rel="preconnect" href="https://fonts.bunny.net">--}}
{{--    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />--}}

{{--    <!-- Scripts -->--}}
{{--    @vite(['resources/css/app.css', 'resources/js/app.js'])--}}
{{--</head>--}}
{{--<body class="font-sans antialiased">--}}
{{--<div class="min-h-screen bg-gray-100 dark:bg-gray-900">--}}
{{--    @include('admin.layouts.navigation')--}}

{{--    <!-- Page Heading -->--}}
{{--    @isset($header)--}}
{{--        <header class="bg-white dark:bg-gray-800 shadow">--}}
{{--            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">--}}
{{--                {{ $header }}--}}
{{--            </div>--}}
{{--        </header>--}}
{{--    @endisset--}}

{{--    <!-- Page Content -->--}}
{{--    <main>--}}
{{--        {{ $slot }}--}}
{{--    </main>--}}
{{--</div>--}}
{{--</body>--}}
{{--</html>--}}

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
