@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        {{-- Header & Actions --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Customers /</span> Customer Details
            </h4>
            <div class="d-flex gap-2">
                <form action="{{ route('admin.customers.toggle-status', $customer) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @if($customer->status)
                        <button type="submit" class="btn btn-outline-warning">
                            <i class="bi bi-lock me-1"></i> Block Customer
                        </button>
                    @else
                        <button type="submit" class="btn btn-outline-success">
                            <i class="bi bi-unlock me-1"></i> Activate Customer
                        </button>
                    @endif
                </form>

                <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>

        <div class="row">
            {{-- Cột Trái: Thông tin cá nhân --}}
            <div class="col-xl-4 col-lg-5 col-md-5">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img src="{{ asset('assets/storefront/images/blog/default-avatar.png') }}"
                             alt="user-avatar"
                             class="rounded-circle img-fluid mb-3" style="width: 100px;">
                        <h5 class="mb-1">{{ $customer->full_name }}</h5>
                        <p class="text-muted mb-3">Customer #{{ $customer->id }}</p>

                        <div class="d-flex justify-content-center gap-2">
                            <span class="badge bg-{{ $customer->status ? 'success' : 'danger' }} px-3 py-2">
                                {{ $customer->status ? 'Active' : 'Blocked' }}
                            </span>
                        </div>
                    </div>
                    <hr class="my-0">
                    <div class="card-body">
                        <div class="info-container">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Email:</span>
                                    <span>{{ $customer->email }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Phone:</span>
                                    <span>{{ $customer->phone ?? 'N/A' }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Joined At:</span>
                                    <span>{{ $customer->created_at->format('d M, Y') }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Total Spent:</span>
                                    <span class="text-primary fw-bold">{{ number_format($totalSpent) }} VND</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Địa chỉ (Nếu có) --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Address</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-1">{{ $customer->address ?? 'No address provided' }}</p>
                        <p class="mb-0">{{ $customer->city }}</p>
                    </div>
                </div>
            </div>

            {{-- Cột Phải: Lịch sử đơn hàng --}}
            <div class="col-xl-8 col-lg-7 col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order History</h5>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($customer->orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold">
                                            #{{ $order->id }}
                                        </a>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>
                                        {{-- Sử dụng Enum hoặc label có sẵn --}}
                                        <span class="badge bg-{{ $order->status->color() }}">
                                                {{ $order->status->label() }}
                                            </span>
                                    </td>
                                    <td>{{ $order->order_items_count }} items</td>
                                    <td class="fw-semibold">{{ number_format($order->total_price) }} VND</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                           class="btn btn-sm btn-light text-primary"
                                           title="View Order">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        This customer hasn't placed any orders yet.
                                    </td>
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
