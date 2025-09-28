@extends('admin.layouts.app')
@section('content')
    <!-- Start Container Fluid -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">
                <div class="row">
                    <!-- Total Orders Card -->
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
                    <!-- Total Revenue Card -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="text-muted mb-0 text-truncate">Total Revenue</p>
                                        <h3 class="text-dark mt-1 mb-0">{{ number_format($totalRevenue, 0, ',', '.') }}
                                            VNĐ</h3>
                                    </div>
                                    <div class="avatar-md bg-soft-primary rounded">
                                        <iconify-icon icon="solar:wallet-money-bold-duotone"
                                                      class="avatar-title text-primary fs-32"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Total Customers Card -->
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
                    <!-- Total Products Card -->
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
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-7">
                <!-- Performance Chart -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Performance (Revenue per Month)</h4>
                        <div dir="ltr">
                            <div id="dashboard-performance-chart" class="apex-charts" style="height: 320px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-5">
                <!-- Recent Orders Table -->
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Recent Orders</h4>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-soft-primary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap table-centered m-0">
                            <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($recentOrders as $order)
                                <tr>
                                    <td><a href="{{ route('admin.orders.show', $order) }}"
                                           class="link-primary">#{{ $order->id }}</a></td>
                                    <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                                    <td>{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</td>
                                    <td><span
                                            class="badge bg-{{ $order->status->color() }}">{{ $order->status->name }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No recent orders.</td>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const performanceChartEl = document.getElementById('dashboard-performance-chart');
            if (performanceChartEl) {
                const chartData = @json($chartData);
                const chartLabels = @json($chartLabels);

                const options = {
                    series: [
                        {
                            name: 'Revenue',
                            type: 'area',
                            data: chartData,
                        },
                    ],
                    chart: {
                        height: 313,
                        type: 'line',
                        toolbar: { show: false },
                        fontFamily: 'Inter, sans-serif',
                    },
                    stroke: {
                        width: [2],
                        curve: 'smooth',
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            type: 'vertical',
                            opacityFrom: 0.5,
                            opacityTo: 0,
                            stops: [0, 90],
                        },
                    },
                    markers: { size: 0 },
                    xaxis: {
                        categories: chartLabels,
                        axisTicks: { show: false },
                        axisBorder: { show: false },
                    },
                    yaxis: {
                        min: 0,
                        axisBorder: { show: false },
                        labels: {
                            formatter: function(value) {
                                return Math.round(value).toLocaleString('vi-VN') + ' VNĐ';
                            },
                        },
                    },
                    grid: {
                        show: true,
                        strokeDashArray: 3,
                        xaxis: { lines: { show: false } },
                        yaxis: { lines: { show: true } },
                    },
                    legend: { show: true, horizontalAlign: 'center' },
                    colors: ['#22c55e'],
                    tooltip: {
                        shared: true,
                        y: {
                            formatter: function(y) {
                                if (typeof y !== 'undefined') {
                                    return Math.round(y).toLocaleString('vi-VN') + ' VNĐ';
                                }
                                return y;
                            },
                        },
                    },
                };

                const chart = new ApexCharts(performanceChartEl, options);
                chart.render();
            }
        });
    </script>
@endpush
