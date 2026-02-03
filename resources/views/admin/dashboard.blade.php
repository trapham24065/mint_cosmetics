@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Dashboard</h4>
                </div>
            </div>
        </div>

        <!-- ROW 1: Real-time Stats & Key Metrics -->
        <div class="row">
            <!-- Online Users -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase mb-2">Đang Online</h6>
                                <h2 class="mb-0 display-6 fw-bold text-white">{{ number_format($onlineUsers) }}</h2>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-white bg-opacity-25 rounded-circle fs-3">
                                    <i class="bx bx-user-voice"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="text-white-50 fs-13">Hoạt động trong 5 phút</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today Visits -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase mb-2">Lượt xem hôm nay</h6>
                                <h2 class="mb-0 display-6 fw-bold text-white">{{ number_format($todayVisits) }}</h2>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-white bg-opacity-25 rounded-circle fs-3">
                                    <i class="bx bx-show"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mt-3">
                            @if($growth >= 0)
                                <span class="badge bg-white bg-opacity-25 text-white"><i class="bx bx-up-arrow-alt"></i> {{ number_format($growth, 1) }}%</span>
                            @else
                                <span class="badge bg-danger text-white"><i class="bx bx-down-arrow-alt"></i> {{ number_format(abs($growth), 1) }}%</span>
                            @endif
                            <span class="text-white-50 fs-13 ms-1">So với hôm qua</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Orders -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase mb-2">Đơn chờ xử lý</h6>
                                <h2 class="mb-0 display-6 fw-bold text-white">{{ number_format($pendingOrders) }}</h2>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-white bg-opacity-25 rounded-circle fs-3">
                                    <i class="bx bx-time-five"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="text-white-50 fs-13">Cần duyệt ngay</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase mb-2">Tổng doanh thu</h6>
                                <h4 class="mb-0 fw-bold text-white">{{ number_format($totalRevenue) }} đ</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-white bg-opacity-25 rounded-circle fs-3">
                                    <i class="bx bx-money"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="text-white-50 fs-13">Đơn hàng hoàn tất</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 2: Additional Stats -->
        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-0 text-truncate">Total Orders</p>
                                <h3 class="text-dark mt-1 mb-0">{{ number_format($totalOrders) }}</h3>
                            </div>
                            <div class="avatar-md bg-soft-primary rounded">
                                <iconify-icon icon="solar:cart-5-bold-duotone"
                                              class="avatar-title fs-32 text-primary"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-0 text-truncate">Total Customers</p>
                                <h3 class="text-dark mt-1 mb-0">{{ number_format($totalCustomers) }}</h3>
                            </div>
                            <div class="avatar-md bg-soft-primary rounded">
                                <iconify-icon icon="solar:users-group-rounded-bold-duotone"
                                              class="avatar-title text-primary fs-32"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-0 text-truncate">Total Products</p>
                                <h3 class="text-dark mt-1 mb-0">{{ number_format($totalProducts) }}</h3>
                            </div>
                            <div class="avatar-md bg-soft-primary rounded">
                                <iconify-icon icon="solar:box-bold-duotone"
                                              class="avatar-title text-primary fs-32"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <!-- Placeholder for future stat -->
            </div>
        </div>

        <!-- ROW 3: Charts -->
        <div class="row">
            <!-- REVENUE CHART -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Biểu Đồ Doanh Thu (12 Tháng)</h4>
                        <div dir="ltr">
                            <div id="revenue-chart" class="apex-charts" style="height: 320px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VISITS CHART -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Lượt Truy Cập (7 Ngày Gần Nhất)</h4>
                        <div dir="ltr">
                            <div id="visits-chart" class="apex-charts" style="height: 320px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 4: Recent Orders Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Đơn Hàng Gần Đây</h4>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-soft-primary">Xem Tất Cả</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap table-centered m-0">
                            <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th>Order ID</th>
                                <th>Khách Hàng</th>
                                <th>Tổng Tiền</th>
                                <th>Trạng Thái</th>
                                <th>Ngày Đặt</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($recentOrders as $order)
                                <tr>
                                    <td><a href="{{ route('admin.orders.show', $order) }}" class="link-primary fw-bold">#{{ $order->id }}</a>
                                    </td>
                                    <td>
                                        @if($order->customer)
                                            {{ $order->customer->full_name }}
                                        @else
                                            <span
                                                class="text-muted">{{ $order->first_name }} {{ $order->last_name }}</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold">{{ number_format($order->total_price, 0, ',', '.') }} đ</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status->color() }}">
                                            {{ $order->status->label() }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Chưa có đơn hàng nào.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{--    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>--}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- 1. REVENUE CHART ---
            const revenueEl = document.getElementById('revenue-chart');
            if (revenueEl) {
                const options1 = {
                    series: [{ name: 'Doanh thu', data: @json($revenueValues) }],
                    chart: { height: 320, type: 'area', toolbar: { show: false } },
                    stroke: { curve: 'smooth', width: 3 },
                    fill: {
                        type: 'gradient',
                        gradient: { shadeIntensity: 1, opacityFrom: 0.5, opacityTo: 0.05, stops: [0, 90, 100] },
                    },
                    xaxis: { categories: @json($revenueLabels) },
                    yaxis: {
                        labels: { formatter: (val) => new Intl.NumberFormat('vi-VN').format(val) },
                    },
                    colors: ['#22c55e'], // Green
                    tooltip: { y: { formatter: (val) => new Intl.NumberFormat('vi-VN').format(val) + ' VNĐ' } },
                };
                new ApexCharts(revenueEl, options1).render();
            }

            // --- 2. VISITS CHART ---
            const visitsEl = document.getElementById('visits-chart');
            if (visitsEl) {
                const options2 = {
                    series: [{ name: 'Lượt xem', data: @json($visitValues) }],
                    chart: { height: 320, type: 'bar', toolbar: { show: false } },
                    xaxis: { categories: @json($visitLabels) },
                    colors: ['#3b82f6'], // Blue
                    plotOptions: { bar: { borderRadius: 4, columnWidth: '45%' } },
                    tooltip: { y: { formatter: (val) => val + ' lượt' } },
                };
                new ApexCharts(visitsEl, options2).render();
            }
        });
    </script>
@endpush
