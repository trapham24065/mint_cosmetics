<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<head>
    <meta charset="utf-8" />
    <title>Bảng điều khiển </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Mint Cosmetics') }}</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('storage/' . setting('site_favicon')) }}">
    <!-- Gridjs Plugin css -->
    <link href="{{asset('assets/admin/vendor/gridjs/theme/mermaid.min.css')}}" rel="stylesheet" type="text/css">
    <!-- Vendor css (Require in all Page) -->
    <link href="{{asset('assets/admin/css/vendor.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons css (Require in all Page) -->
    <link href="{{asset('assets/admin/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App css (Require in all Page) -->
    <link href="{{asset('assets/admin/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Theme Config js (Require in all Page) -->
    <script src="{{asset('assets/admin/js/config.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">


</head>

<body>


    <!-- START Wrapper -->
    <div class="wrapper">
        @include('admin.partials.header')
        {{-- @include('admin.layouts.timeline')--}}
        @include('admin.partials.sidebar')
        @include('admin.layouts.menu')
        <div class="page-content">

            <!-- Page Content-->
            @yield('content')
            @include('admin.partials.footer')
            <!-- end page content -->
        </div>


    </div>

    <div class="modal fade" id="adminDeleteConfirmModal" tabindex="-1" aria-labelledby="adminDeleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="admin-delete-modal-title">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-1" id="admin-delete-modal-text">Bạn có chắc muốn xóa mục này?</p>
                    <p class="mb-0"><strong id="admin-delete-item-name"></strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="admin-delete-confirm-btn">Xóa</button>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('assets/storefront/js/vendor/jquery-3.6.0.min.js')}}"></script>

    <script src="https://cdn.tiny.cloud/1/456eizv25xorupt47tepkzopmaeapg49dxyklm5u5yarht9b/tinymce/8/tinymce.min.js"
        referrerpolicy="origin" crossorigin="anonymous"></script>
    <!-- Admin CRUD Utilities -->
    <script src="{{ asset('assets/admin/js/admin-crud.js') }}"></script>
    <!-- Vendor Javascript (Require in all Page) -->
    <script src="{{asset('assets/admin/js/vendor.js')}}"></script>

    <!-- App Javascript (Require in all Page) -->
    <script src="{{asset('assets/admin/js/app.js')}}"></script>
    <!-- Gridjs Plugin js -->
    <script src="{{asset('assets/admin/vendor/gridjs/gridjs.umd.js')}}"></script>
    <script src="{{asset('assets/admin/js/components/table-gridjs.js')}}"></script>
    <!-- Vector Map Js -->
    <script src="{{asset('assets/admin/vendor/jsvectormap/js/jsvectormap.min.js')}}"></script>
    <script src="{{asset('assets/admin/vendor/jsvectormap/maps/world-merc.js')}}"></script>
    <script src="{{asset('assets/admin/vendor/jsvectormap/maps/world.js')}}"></script>

    <!-- Dashboard Js -->
    <script src="{{asset('assets/admin/js/pages/dashboard.js')}}"></script>
    @stack('scripts')
    <x-toast />
</body>

</html>