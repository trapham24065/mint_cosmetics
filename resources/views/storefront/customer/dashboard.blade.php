@extends('storefront.layouts.app')
@section('content')
    @php $shouldOpenAddressModal =
$errors->has('address') ||
$errors->has('shipping_province_id') ||
$errors->has('shipping_district_id') ||
$errors->has('shipping_ward_code') ||
$errors->has('phone') ||
($errors->has('first_name') && request()->routeIs('customer.address.update'));
    @endphp
    <style>
        table {
            table-layout: fixed;
            width: 100%;
        }

        .reason-cell {
            max-width: 250px;
        }

        .reason-text {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* hiển thị 2 dòng */
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            cursor: pointer;
        }

        .reason-text:hover {
            text-decoration: underline;
        }
    </style>
    <main class="main-content" data-should-open-address-modal="{{ $shouldOpenAddressModal ? '1' : '0' }}">

        <!--== Start Page Header Area Wrapper ==-->
        <section class="page-header-area pt-10 pb-9" data-bg-color="#FFF3DA">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <div class="page-header-st3-content text-center text-md-start">
                            <ol class="breadcrumb justify-content-center justify-content-md-start">
                                <li class="breadcrumb-item"><a class="text-dark" href="{{ route('home') }}">Trang
                                        chủ</a>
                                </li>
                                <li class="breadcrumb-item active text-dark" aria-current="page">Tài khoản</li>
                            </ol>
                            <h2 class="page-header-title">Tài khoản</h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--== End Page Header Area Wrapper ==-->

        <!--== Start Tài khoản của tôi Area Wrapper ==-->
        <section class="my-account-area section-space">
            <div class="container">
                <div class="row">
                    {{-- SIDEBAR MENU --}}
                    <div class="col-lg-3 col-md-4">
                        <div class="my-account-tab-menu nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="dashboad-tab" data-bs-toggle="tab"
                                    data-bs-target="#dashboad" type="button" role="tab" aria-controls="dashboad"
                                    aria-selected="true">Trang tổng quan
                            </button>
                            <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders"
                                    type="button" role="tab" aria-controls="orders" aria-selected="false"> Đơn đặt hàng
                            </button>
                            <button class="nav-link" id="returns-tab" data-bs-toggle="tab" data-bs-target="#returns"
                                    type="button" role="tab" aria-controls="returns" aria-selected="false"> Danh sách
                                yêu cầu trả
                                hàng
                            </button>
                            <button class="nav-link" id="address-edit-tab" data-bs-toggle="tab"
                                    data-bs-target="#address-edit" type="button" role="tab" aria-controls="address-edit"
                                    aria-selected="false">Địa chỉ
                            </button>
                            <button class="nav-link" id="account-info-tab" data-bs-toggle="tab"
                                    data-bs-target="#account-info" type="button" role="tab" aria-controls="account-info"
                                    aria-selected="false">Chi tiết tài khoản
                            </button>
                            <button class="nav-link"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    type="button">
                                Đăng xuất
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
                                    <h3>Trang tổng quan</h3>
                                    <div class="welcome">
                                        <p>Xin chào, <strong>{{ $customer->full_name }}</strong> (Nếu không phải
                                            <strong>{{ $customer->last_name }} !</strong> <a
                                                href="#"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                                class="logout"> Đăng xuất
                                            </a>)
                                        </p>
                                    </div>
                                    <p>Từ trang tổng quan tài khoản, bạn có thể dễ dàng kiểm tra và xem các đơn đặt hàng
                                        gần đây,

                                        quản lý địa chỉ giao hàng và thanh toán, chỉnh sửa mật khẩu và thông tin tài
                                        khoản.

                                        (Thông tin tài khoản)</p>
                                </div>
                            </div>

                            {{-- ORDERS TAB --}}
                            <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                                <div class="myaccount-content">
                                    <h3>Đơn đặt hàng</h3>
                                    <div class="myaccount-table table-responsive text-center">
                                        <table class="table table-bordered">
                                            <thead class="thead-light">
                                            <tr>
                                                <th>Đặt hàng</th>
                                                <th>Ngày</th>
                                                <th>Hoàn thành lúc</th>
                                                <th>Trạng thái</th>
                                                <th>Tổng cộng</th>
                                                <th>Hoạt động</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($orders as $order)
                                                <tr>
                                                    <td>#{{ $order->id }}</td>
                                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                    <td>
                                                        @if($order->status === \App\Enums\OrderStatus::Completed)
                                                            {{ optional($order->completed_at ?? $order->updated_at)->format('d/m/Y H:i') }}
                                                        @else
                                                            <span class="text-muted">Chưa hoàn thành</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                    <span
                                                        class="badge bg-{{ $order->status->color() ?? 'secondary' }}">
                                                        {{ $order->status->label() ?? $order->status }}
                                                    </span>
                                                    </td>
                                                    <td>{{ number_format($order->total_price) }} VND</td>
                                                    <td>
                                                        <a href="{{ route('customer.orders.show', $order->id) }}"
                                                           class="check-btn sqr-btn">Xem</a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6">Không tìm thấy đơn đặt hàng nào.</td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- RETURNS TAB--}}
                            <div class="tab-pane fade" id="returns" role="tabpanel" aria-labelledby="returns-tab">
                                <div class="myaccount-content">
                                    <h3>Danh sách yêu cầu trả hàng</h3>
                                    <div class="myaccount-table table-responsive text-center">
                                        <table class="table table-bordered">
                                            <thead class="bg-light">
                                            <tr>
                                                <th>Đơn hàng</th>
                                                <th>Lý do</th>
                                                <th>Trạng thái</th>
                                                <th>Ngày gửi</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($returns as $return)
                                                @php
                                                    $statusValue = $return->status instanceof \App\Enums\ReturnStatus
                                                    ? $return->status->value
                                                    : (string) $return->status;
                                                    $statusLabel = $return->status instanceof \App\Enums\ReturnStatus
                                                    ? $return->status->label()
                                                    : ucfirst($statusValue);
                                                    $statusClass = [
                                                    'pending' => 'warning',
                                                    'approved' => 'info',
                                                    'rejected' => 'danger',
                                                    'refunded' => 'success',
                                                    'cancelled' => 'secondary',
                                                    ][$statusValue] ?? 'secondary';
                                                @endphp
                                                <tr>
                                                    <td>
                                                        @if($return->order)
                                                            <a href="{{ route('customer.orders.show', $return->order) }}"
                                                               class="text-decoration-none">
                                                                #{{ $return->order->id }}
                                                            </a>
                                                        @else
                                                            <span class="text-muted">Không tồn tại</span>
                                                        @endif
                                                    </td>
                                                    <td class="reason-cell">
                                                        <div
                                                            class="fw-semibold reason-text"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#reasonModal"
                                                            data-reason="{{ $return->reason }}"
                                                            data-description="{{ $return->description }}"
                                                            data-images='@json($return->evidence_images, JSON_THROW_ON_ERROR)'>
                                                            {{ $return->reason }}
                                                        </div>

                                                        @if($return->description)
                                                            <small
                                                                class="text-muted reason-text"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#reasonModal"
                                                                data-reason="{{ $return->reason }}"
                                                                data-description="{{ $return->description }}"
                                                                data-images='@json($return->evidence_images, JSON_THROW_ON_ERROR)'>
                                                                {{ $return->description }}
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                    <span
                                                        class="badge bg-{{ $statusClass }}">{{ $statusLabel }}</span>
                                                    </td>
                                                    <td class=" pe-4">
                                                        {{ $return->created_at->format('d/m/Y H:i') }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-5">
                                                        Bạn chưa có yêu cầu trả hàng nào.
                                                    </td>
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
                                    <h3>Địa chỉ thanh toán</h3>
                                    <address>
                                        <p><strong>{{ $customer->full_name }}</strong></p>
                                        <p>{{ $customer->address ?? 'Chưa có địa chỉ' }}</p>
                                        <p>
                                            <strong>Phường/Xã:</strong> {{ $customer->shipping_ward_name ?? ($customer->shipping_ward_code ?? 'Chưa chọn') }}
                                            <br>
                                            <strong>Quận/Huyện:</strong> {{ $customer->shipping_district_name ?? 'Chưa chọn' }}
                                            <br>
                                            <strong>Tỉnh/Thành
                                                phố:</strong> {{ $customer->shipping_province_name ?? 'Chưa chọn' }}
                                        </p>
                                        <p><strong>Điện thoại:</strong> {{ $customer->phone ?? 'N/A' }}</p>
                                    </address>
                                    {{-- Nút mở Modal sửa địa chỉ --}}
                                    <a href="#" class="check-btn sqr-btn" data-bs-toggle="modal"
                                       data-bs-target="#editAddressModal">
                                        <i class="fa fa-edit"></i> Chỉnh sửa địa chỉ
                                    </a>
                                </div>
                            </div>

                            {{-- ACCOUNT DETAILS TAB --}}
                            <div class="tab-pane fade" id="account-info" role="tabpanel"
                                 aria-labelledby="account-info-tab">
                                <div class="myaccount-content">
                                    <h3>Chi tiết tài khoản</h3>
                                    <div class="account-details-form">
                                        <form action="{{ route('customer.profile.update') }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="single-input-item">
                                                        <label for="first_name" class="required">Tên</label>
                                                        <input type="text" id="first_name" name="first_name"
                                                               value="{{ old('first_name', $customer->first_name) }}"
                                                               required />
                                                        @error('first_name') <span
                                                            class="text-danger small">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="single-input-item">
                                                        <label for="last_name" class="required">Họ</label>
                                                        <input type="text" id="last_name" name="last_name"
                                                               value="{{ old('last_name', $customer->last_name) }}"
                                                               required />
                                                        @error('last_name') <span
                                                            class="text-danger small">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="single-input-item">
                                                <label for="email" class="required">Địa chỉ email
                                                </label>
                                                <input type="email" id="email" name="email"
                                                       value="{{ old('email', $customer->email) }}" required />
                                                @error('email') <span
                                                    class="text-danger small">{{ $message }}</span> @enderror
                                            </div>
                                            <fieldset>
                                                <legend>
                                                    Thay đổi mật khẩu
                                                </legend>
                                                <div class="single-input-item">
                                                    <label for="current_password" class="required">Mật khẩu hiện
                                                        tại</label>
                                                    <input type="password" id="current_password"
                                                           name="current_password" />
                                                    @error('current_password') <span
                                                        class="text-danger small">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="single-input-item">
                                                            <label for="new_password" class="required">Mật khẩu
                                                                mới</label>
                                                            <input type="password" id="new_password"
                                                                   name="new_password" />
                                                            @error('new_password') <span
                                                                class="text-danger small">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="single-input-item">
                                                            <label for="new_password_confirmation" class="required">Nhập
                                                                lại mật khẩu</label>
                                                            <input type="password" id="new_password_confirmation"
                                                                   name="new_password_confirmation" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <div class="single-input-item">
                                                <button class="check-btn sqr-btn">Lưu thay đổi</button>
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
        <!--== End Tài khoản của tôi Area Wrapper ==-->

    </main>

    {{-- EDIT ADDRESS MODAL (Using Bootstrap Modal as requested) --}}
    <div class="modal fade" id="editAddressModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa địa chỉ thanh toán</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('customer.address.update') }}" method="POST" id="dashboard-address-form"
                      data-provinces-url="{{ route('customer.checkout.ghn.provinces') }}"
                      data-districts-url="{{ route('customer.checkout.ghn.districts') }}"
                      data-wards-url="{{ route('customer.checkout.ghn.wards') }}"
                      data-default-province-id="{{ old('shipping_province_id', $customer->shipping_province_id ?? '') }}"
                      data-default-district-id="{{ old('shipping_district_id', $customer->shipping_district_id ?? '') }}"
                      data-default-ward-code="{{ old('shipping_ward_code', $customer->shipping_ward_code ?? '') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="shipping_province_name" id="shipping_province_name"
                           value="{{ old('shipping_province_name', $customer->shipping_province_name ?? '') }}">
                    <input type="hidden" name="shipping_district_name" id="shipping_district_name"
                           value="{{ old('shipping_district_name', $customer->shipping_district_name ?? '') }}">
                    <input type="hidden" name="shipping_ward_name" id="shipping_ward_name"
                           value="{{ old('shipping_ward_name', $customer->shipping_ward_name ?? '') }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tên <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control"
                                       value="{{ old('first_name', $customer->first_name) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control"
                                       value="{{ old('last_name', $customer->last_name) }}" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Địa chỉ<span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-control"
                                       value="{{ old('address', $customer->address) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                <select name="shipping_province_id" id="shipping_province_id" class="form-control"
                                        required>
                                    <option value="">Chọn tỉnh/thành phố</option>
                                </select>
                                @error('shipping_province_id') <span
                                    class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Điện thoại
                                    <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control"
                                       value="{{ old('phone', $customer->phone) }}" required>
                                @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                <select name="shipping_district_id" id="shipping_district_id" class="form-control"
                                        required disabled>
                                    <option value="">Chọn quận/huyện</option>
                                </select>
                                @error('shipping_district_id') <span
                                    class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                <select name="shipping_ward_code" id="shipping_ward_code" class="form-control" required
                                        disabled>
                                    <option value="">Chọn phường/xã</option>
                                </select>
                                @error('shipping_ward_code') <span
                                    class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="reasonModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết lý do trả hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p><strong>Lý do:</strong></p>
                    <p id="modal-reason"></p>

                    <div id="modal-description-wrapper" style="display:none;">
                        <p><strong>Mô tả chi tiết:</strong></p>
                        <p id="modal-description"></p>
                    </div>

                    <!-- ✅ THÊM PHẦN ẢNH -->
                    <div id="modal-images-wrapper" style="display:none;">
                        <p><strong>Ảnh bằng chứng:</strong></p>
                        <div id="modal-images" class="d-flex flex-wrap gap-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const mainContent = document.querySelector('.main-content');
            const shouldOpenAddressModal = mainContent && mainContent.dataset.shouldOpenAddressModal === '1';

            if (shouldOpenAddressModal) {
                const editAddressModal = new bootstrap.Modal(document.getElementById('editAddressModal'));
                editAddressModal.show();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const addressForm = document.getElementById('dashboard-address-form');
            if (!addressForm) {
                return;
            }

            const provinceSelect = document.getElementById('shipping_province_id');
            const districtSelect = document.getElementById('shipping_district_id');
            const wardSelect = document.getElementById('shipping_ward_code');

            const provinceNameInput = document.getElementById('shipping_province_name');
            const districtNameInput = document.getElementById('shipping_district_name');
            const wardNameInput = document.getElementById('shipping_ward_name');

            const defaultProvinceId = addressForm.dataset.defaultProvinceId || '';
            const defaultDistrictId = addressForm.dataset.defaultDistrictId || '';
            const defaultWardCode = addressForm.dataset.defaultWardCode || '';

            const normalize = (res) => Array.isArray(res) ? res : (res.data || []);

            const loadProvinces = async () => {
                try {
                    const res = await fetch(addressForm.dataset.provincesUrl);
                    const data = await res.json();
                    const items = normalize(data);

                    provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
                    items.forEach((p) => {
                        provinceSelect.insertAdjacentHTML('beforeend',
                            `<option value="${p.ProvinceID}">${p.ProvinceName}</option>`);
                    });

                    if (defaultProvinceId) {
                        provinceSelect.value = String(defaultProvinceId);
                        provinceSelect.dispatchEvent(new window.Event('change'));
                    }
                } catch (e) {
                    console.error('Lỗi tải tỉnh/thành:', e);
                }
            };

            const loadDistricts = async (provinceId) => {
                if (!provinceId) {
                    districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                    districtSelect.disabled = true;
                    wardSelect.disabled = true;
                    return;
                }

                districtSelect.disabled = true;
                districtSelect.innerHTML = '<option value="">Đang tải...</option>';
                wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                wardSelect.disabled = true;

                try {
                    const res = await fetch(`${addressForm.dataset.districtsUrl}?province_id=${provinceId}`);
                    const data = await res.json();
                    const items = normalize(data);

                    districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                    items.forEach((d) => {
                        districtSelect.insertAdjacentHTML('beforeend',
                            `<option value="${d.DistrictID}">${d.DistrictName}</option>`);
                    });
                    districtSelect.disabled = false;

                    if (defaultDistrictId && String(provinceId) === String(defaultProvinceId)) {
                        districtSelect.value = String(defaultDistrictId);
                    }
                    districtSelect.dispatchEvent(new window.Event('change'));
                } catch (e) {
                    console.error('Lỗi tải quận/huyện:', e);
                }
            };

            const loadWards = async (districtId) => {
                if (!districtId) {
                    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                    wardSelect.disabled = true;
                    return;
                }

                wardSelect.disabled = true;
                wardSelect.innerHTML = '<option value="">Đang tải...</option>';

                try {
                    const res = await fetch(`${addressForm.dataset.wardsUrl}?district_id=${districtId}`);
                    const data = await res.json();
                    const items = normalize(data);

                    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                    items.forEach((w) => {
                        wardSelect.insertAdjacentHTML('beforeend',
                            `<option value="${w.WardCode}">${w.WardName}</option>`);
                    });
                    wardSelect.disabled = false;

                    if (defaultWardCode && String(districtId) === String(defaultDistrictId)) {
                        wardSelect.value = String(defaultWardCode);
                    }
                } catch (e) {
                    console.error('Lỗi tải phường/xã:', e);
                }
            };

            provinceSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                provinceNameInput.value = selectedOption ? selectedOption.text : '';
                districtNameInput.value = '';
                wardNameInput.value = '';
                loadDistricts(this.value);
            });

            districtSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                districtNameInput.value = selectedOption ? selectedOption.text : '';
                wardNameInput.value = '';
                loadWards(this.value);
            });

            wardSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                wardNameInput.value = selectedOption ? selectedOption.text : '';
            });

            loadProvinces();
        });

        document.addEventListener('DOMContentLoaded', function() {
            var reasonModal = document.getElementById('reasonModal');
            if (!reasonModal) {
                return;
            }

            reasonModal.addEventListener('show.bs.modal', function(event) {
                var trigger = event.relatedTarget;
                if (!trigger) {
                    return;
                }

                var reason = trigger.getAttribute('data-reason');
                var description = trigger.getAttribute('data-description');
                var images = trigger.getAttribute('data-images');

                document.getElementById('modal-reason').textContent = reason;

                // description
                if (description) {
                    document.getElementById('modal-description').textContent = description;
                    document.getElementById('modal-description-wrapper').style.display = 'block';
                } else {
                    document.getElementById('modal-description-wrapper').style.display = 'none';
                }

                // images
                var imagesWrapper = document.getElementById('modal-images-wrapper');
                var imagesContainer = document.getElementById('modal-images');
                imagesContainer.innerHTML = '';

                if (images) {
                    try {
                        var imageArray = JSON.parse(images);

                        if (imageArray.length > 0) {
                            imageArray.forEach(function(img) {
                                var imageEl = document.createElement('img');
                                imageEl.src = '/storage/' + img;
                                imageEl.style.width = '100px';
                                imageEl.style.height = '100px';
                                imageEl.style.objectFit = 'cover';
                                imageEl.classList.add('rounded', 'border');

                                imagesContainer.appendChild(imageEl);
                            });

                            imagesWrapper.style.display = 'block';
                        } else {
                            imagesWrapper.style.display = 'none';
                        }
                    } catch (e) {
                        imagesWrapper.style.display = 'none';
                    }
                } else {
                    imagesWrapper.style.display = 'none';
                }
            });
        });
    </script>
@endpush
