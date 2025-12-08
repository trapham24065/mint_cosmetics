@extends('storefront.layouts.app')
@section('content')
    <main class="main-content">

        <!--== Start Page Header Area Wrapper ==-->
        <section class="page-header-area pt-10 pb-9" data-bg-color="#FFF3DA">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <div class="page-header-st3-content text-center text-md-start">
                            <ol class="breadcrumb justify-content-center justify-content-md-start">
                                <li class="breadcrumb-item"><a class="text-dark" href="{{ route('home') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item active text-dark" aria-current="page">My Account</li>
                            </ol>
                            <h2 class="page-header-title">My Account</h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--== End Page Header Area Wrapper ==-->

        <!--== Start My Account Area Wrapper ==-->
        <section class="my-account-area section-space">
            <div class="container">
                <div class="row">
                    {{-- SIDEBAR MENU --}}
                    <div class="col-lg-3 col-md-4">
                        <div class="my-account-tab-menu nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="dashboad-tab" data-bs-toggle="tab"
                                    data-bs-target="#dashboad" type="button" role="tab" aria-controls="dashboad"
                                    aria-selected="true">Dashboard
                            </button>
                            <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders"
                                    type="button" role="tab" aria-controls="orders" aria-selected="false"> Orders
                            </button>
                            <button class="nav-link" id="address-edit-tab" data-bs-toggle="tab"
                                    data-bs-target="#address-edit" type="button" role="tab" aria-controls="address-edit"
                                    aria-selected="false">Address
                            </button>
                            <button class="nav-link" id="account-info-tab" data-bs-toggle="tab"
                                    data-bs-target="#account-info" type="button" role="tab" aria-controls="account-info"
                                    aria-selected="false">Account Details
                            </button>
                            <button class="nav-link"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    type="button">
                                Logout
                            </button>
                            <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>

                    {{-- CONTENT AREA --}}
                    <div class="col-lg-9 col-md-8">
                        {{-- Hiển thị thông báo thành công/lỗi --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="tab-content" id="nav-tabContent">

                            {{-- DASHBOARD TAB --}}
                            <div class="tab-pane fade show active" id="dashboad" role="tabpanel"
                                 aria-labelledby="dashboad-tab">
                                <div class="myaccount-content">
                                    <h3>Dashboard</h3>
                                    <div class="welcome">
                                        <p>Hello, <strong>{{ $customer->full_name }}</strong> (If Not
                                            <strong>{{ $customer->last_name }} !</strong> <a
                                                href="#"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                                class="logout"> Logout</a>)</p>
                                    </div>
                                    <p>From your account dashboard. you can easily check & view your recent orders,
                                        manage your shipping and billing addresses and edit your password and account
                                        details.</p>
                                </div>
                            </div>

                            {{-- ORDERS TAB --}}
                            <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                                <div class="myaccount-content">
                                    <h3>Orders</h3>
                                    <div class="myaccount-table table-responsive text-center">
                                        <table class="table table-bordered">
                                            <thead class="thead-light">
                                            <tr>
                                                <th>Order</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($orders as $order)
                                                <tr>
                                                    <td>#{{ $order->id }}</td>
                                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{ $order->status->color() ?? 'secondary' }}">
                                                            {{ $order->status->label() ?? $order->status }}
                                                        </span>
                                                    </td>
                                                    <td>{{ number_format($order->total_price) }} VND</td>
                                                    <td><a href="{{ route('payment.show', $order->id) }}"
                                                           class="check-btn sqr-btn ">View</a></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5">No orders found.</td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- ADDRESS TAB --}}
                            <div class="tab-pane fade" id="address-edit" role="tabpanel"
                                 aria-labelledby="address-edit-tab">
                                <div class="myaccount-content">
                                    <h3>Billing Address</h3>
                                    <address>
                                        <p><strong>{{ $customer->full_name }}</strong></p>
                                        <p>{{ $customer->address ?? 'Address not set' }} <br>
                                            {{ $customer->city ?? 'City not set' }}</p>
                                        <p>Mobile: {{ $customer->phone ?? 'N/A' }}</p>
                                    </address>
                                    {{-- Nút mở Modal sửa địa chỉ --}}
                                    <a href="#" class="check-btn sqr-btn" data-bs-toggle="modal"
                                       data-bs-target="#editAddressModal">
                                        <i class="fa fa-edit"></i> Edit Address
                                    </a>
                                </div>
                            </div>

                            {{-- ACCOUNT DETAILS TAB --}}
                            <div class="tab-pane fade" id="account-info" role="tabpanel"
                                 aria-labelledby="account-info-tab">
                                <div class="myaccount-content">
                                    <h3>Account Details</h3>
                                    <div class="account-details-form">
                                        <form action="{{ route('customer.profile.update') }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="single-input-item">
                                                        <label for="first_name" class="required">First Name</label>
                                                        <input type="text" id="first_name" name="first_name"
                                                               value="{{ old('first_name', $customer->first_name) }}"
                                                               required />
                                                        @error('first_name') <span
                                                            class="text-danger small">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="single-input-item">
                                                        <label for="last_name" class="required">Last Name</label>
                                                        <input type="text" id="last_name" name="last_name"
                                                               value="{{ old('last_name', $customer->last_name) }}"
                                                               required />
                                                        @error('last_name') <span
                                                            class="text-danger small">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="single-input-item">
                                                <label for="email" class="required">Email Address</label>
                                                <input type="email" id="email" name="email"
                                                       value="{{ old('email', $customer->email) }}" required />
                                                @error('email') <span
                                                    class="text-danger small">{{ $message }}</span> @enderror
                                            </div>
                                            <fieldset>
                                                <legend>Password change</legend>
                                                <div class="single-input-item">
                                                    <label for="current_password" class="required">Current
                                                        Password</label>
                                                    <input type="password" id="current_password"
                                                           name="current_password" />
                                                    @error('current_password') <span
                                                        class="text-danger small">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="single-input-item">
                                                            <label for="new_password" class="required">New
                                                                Password</label>
                                                            <input type="password" id="new_password"
                                                                   name="new_password" />
                                                            @error('new_password') <span
                                                                class="text-danger small">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="single-input-item">
                                                            <label for="new_password_confirmation" class="required">Confirm
                                                                Password</label>
                                                            <input type="password" id="new_password_confirmation"
                                                                   name="new_password_confirmation" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <div class="single-input-item">
                                                <button class="check-btn sqr-btn">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--== End My Account Area Wrapper ==-->

    </main>

    {{-- EDIT ADDRESS MODAL (Using Bootstrap Modal as requested) --}}
    <div class="modal fade" id="editAddressModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Billing Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('customer.address.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control"
                                       value="{{ old('first_name', $customer->first_name) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control"
                                       value="{{ old('last_name', $customer->last_name) }}" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-control"
                                       value="{{ old('address', $customer->address) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" name="city" class="form-control"
                                       value="{{ old('city', $customer->city) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control"
                                       value="{{ old('phone', $customer->phone) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            @if ($errors->has('address') || $errors->has('city') || $errors->has('phone') || ($errors->has('first_name') && request()->routeIs('customer.address.update')))
            var editAddressModal = new bootstrap.Modal(document.getElementById('editAddressModal'));
            editAddressModal.show();
            @endif
        });
    </script>
@endpush
