@extends('admin.layouts.app')
@section('content')
    <div class="container-xxl">

        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-2">Pending Payment</h4>
                                <p class="text-muted fw-medium fs-22 mb-0">{{ $statusCounts['pending'] ?? 0 }}</p>
                            </div>
                            <div class="avatar-md bg-primary-subtle rounded">
                                <iconify-icon icon="solar:clock-circle-broken"
                                              class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-2">In Progress</h4>
                                <p class="text-muted fw-medium fs-22 mb-0">{{ $statusCounts['processing'] ?? 0 }}</p>
                            </div>
                            <div class="avatar-md bg-info-subtle rounded">
                                <iconify-icon icon="solar:inbox-line-broken"
                                              class="fs-32 text-info avatar-title"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-2">Order Shipped</h4>
                                <p class="text-muted fw-medium fs-22 mb-0">{{ $statusCounts['shipped'] ?? 0 }}</p>
                            </div>
                            <div>
                                <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:box-broken"
                                                  class="fs-32 text-primary avatar-title"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-2">Delivered</h4>
                                <p class="text-muted fw-medium fs-22 mb-0">{{ $statusCounts['delivered'] ?? 0 }}</p>
                            </div>
                            <div class="avatar-md bg-success-subtle rounded">
                                <iconify-icon icon="solar:clipboard-check-broken"
                                              class="fs-32 text-success avatar-title"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-2">Order Cancelled</h4>
                                <p class="text-muted fw-medium fs-22 mb-0">{{ $statusCounts['cancelled'] ?? 0 }}</p>
                            </div>
                            <div class="avatar-md bg-danger-subtle rounded">
                                <iconify-icon icon="solar:cart-cross-broken"
                                              class="fs-32 text-danger avatar-title"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="card-title">All Order List</h4>
                        {{-- Filter/Search Form can be added here if needed --}}
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Created At</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Payment Status</th>
                                    <th>Order Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td><a href="{{ route('admin.orders.show', $order) }}"
                                               class="link-primary">#{{ $order->id }}</a></td>
                                        <td>{{ $order->created_at->format('d M, Y') }}</td>
                                        <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                                        <td>{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</td>
                                        <td><span class="badge bg-light text-dark px-2 py-1 fs-13">{{-- Payment logic --}} Paid</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $order->status->color() }}">
                                                {{ $order->status->name }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.orders.show', $order) }}"
                                                   class="btn btn-light btn-sm">
                                                    <iconify-icon icon="solar:eye-broken"
                                                                  class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                {{-- Add edit/delete buttons if needed --}}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No orders found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($orders->hasPages())
                        <div class="card-footer border-top">
                            <nav>
                                {{ $orders->appends(request()->query())->links('vendor.pagination.admin-paginnation') }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
