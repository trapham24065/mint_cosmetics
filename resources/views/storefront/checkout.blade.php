@extends('storefront.layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Tùy chỉnh Select2 để khớp với giao diện Bootstrap/Storefront */
    .select2-hidden-accessible {
        display: none !important;
    }

    .select2-container .select2-selection--single {
        height: 45px !important;
        border: 1px solid #ebebeb !important;
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 45px !important;
        padding-left: 15px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 43px !important;
    }

    .select2-dropdown {
        border: 1px solid #ebebeb !important;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    /* Ẩn hiện phần phí vận chuyển cho chuyên nghiệp */
    #shipping-fee-value {
        font-weight: bold;
        color: #ff5e14;
    }
</style>
<section class="shopping-checkout-wrap section-space">
    <div class="container">
        <form
            action="{{ route('customer.checkout.placeOrder') }}"
            method="post"
            id="checkout-form"
            data-provinces-url="{{ route('customer.checkout.ghn.provinces') }}"
            data-districts-url="{{ route('customer.checkout.ghn.districts') }}"
            data-wards-url="{{ route('customer.checkout.ghn.wards') }}"
            data-default-province-id="{{ old('province_id', $shipping_defaults['province_id'] ?? '') }}"
            data-default-district-id="{{ old('district_id', $shipping_defaults['district_id'] ?? '') }}"
            data-default-ward-code="{{ old('ward_code', $shipping_defaults['ward_code'] ?? '') }}"
            data-fee-url="{{ route('customer.checkout.ghn.fee') }}">
            @csrf
            <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name') }}">
            <input type="hidden" name="district_name" id="district_name" value="{{ old('district_name') }}">
            <input type="hidden" name="ward_name" id="ward_name" value="{{ old('ward_name') }}">
            <div class="row">
                <div class="col-lg-6">
                    <div class="checkout-billing-details-wrap">
                        <h2 class="title">Chi tiết thanh toán</h2>
                        <div class="billing-form-wrap">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Tên<abbr class="required">*</abbr></label>
                                    <input name="first_name" type="text" class="form-control"
                                        value="{{ old('first_name', $customer->first_name ?? '') }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Họ<abbr class="required">*</abbr></label>
                                    <input name="last_name" type="text" class="form-control"
                                        value="{{ old('last_name', $customer->last_name ?? '') }}" required>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label>Tỉnh/Thành phố<abbr class="required">*</abbr></label>
                                    <select name="province_id" id="province_id"
                                        class="form-control select2-shipping" required>
                                        <option value="">Chọn tỉnh thành</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Quận/Huyện<abbr class="required">*</abbr></label>
                                    <select name="district_id" id="district_id"
                                        class="form-control select2-shipping" required disabled>
                                        <option value="">Chọn huyện</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Phường/Xã<abbr class="required">*</abbr></label>
                                    <select name="ward_code" id="ward_code" class="form-control select2-shipping"
                                        required disabled>
                                        <option value="">Chọn xã</option>
                                    </select>
                                </div>

                                <div class="col-md-12 form-group">
                                    <label>Địa chỉ đường phố<abbr class="required">*</abbr></label>
                                    <input name="address" type="text" class="form-control" required
                                        value="{{ old('address', $customer->address ?? '') }}"
                                        placeholder="Số nhà, tên đường...">
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Điện thoại <abbr class="required">*</abbr></label>
                                        <input name="phone" type="text" class="form-control"
                                            value="{{ old('phone', $customer->phone ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Địa chỉ email<abbr class="required">*</abbr></label>
                                        <input name="email" type="email" class="form-control"
                                            value="{{ old('email', $customer->email ?? '') }}" required>
                                        <small class="text-muted">Email này chỉ áp dụng cho đơn hàng hiện tại, không thay đổi tài khoản của bạn.</small>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label>Ghi chú đơn hàng (tùy chọn)</label>
                                        <textarea name="notes" class="form-control"
                                            placeholder="Ghi chú về đơn hàng của bạn..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="coupon-wrap mb-4" style="padding-top:5% ">
                        <h4 class="title">Mã giảm giá</h4>
                        <p class="desc">Nhập mã giảm giá của bạn nếu có.</p>
                        <div id="checkout-coupon-form-container">
                            @if ($coupon)
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <p class="m-0">Đang áp dụng mã: <strong>{{ $coupon->code }}</strong></p>
                                <button type="button" id="checkout-remove-coupon-btn" class="btn-coupon">Tháo
                                </button>
                            </div>
                            @else
                            <div class="d-flex gap-2 flex-wrap">
                                <input type="text" id="checkout-coupon-code-input" class="form-control"
                                    placeholder="Mã phiếu giảm giá" style="max-width: 280px;">
                                <button type="button" id="checkout-apply-coupon-btn" class="btn-coupon">Áp
                                    dụng
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="checkout-order-details-wrap">
                        <h2 class="title mb-25">Đơn hàng của bạn</h2>

                        <div class="order-details-table-wrap table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="product-name">Sản phẩm</th>
                                        <th class="product-total">Tổng cộng</th>
                                    </tr>
                                </thead>
                                <tbody class="table-body">
                                    @foreach($items as $item)
                                    <tr class="cart-item">
                                        <td class="product-name">{{ $item['product_name'] }} <span
                                                class="product-quantity">× {{ $item['quantity'] }}</span></td>
                                        <td class="product-total">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                            VNĐ
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-foot">
                                    <tr class="cart-subtotal">
                                        <th>Tổng phụ</th>
                                        <td id="subtotal-value">{{ number_format($subtotal, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                    <tr class="cart-discount {{ !$coupon ? 'd-none' : '' }}" id="checkout-discount-row">
                                        <th>Giảm giá</th>
                                        <td id="checkout-discount-value" class="text-danger">
                                            -{{ number_format($discount, 0, ',', '.') }} VNĐ
                                        </td>
                                    </tr>
                                    <tr class="shipping-fee-row">
                                        <th>Phí vận chuyển GHN</th>
                                        <td id="shipping-fee-value">Chọn địa chỉ giao hàng</td>
                                    </tr>
                                    <tr class="order-total">
                                        <th>Tổng cộng</th>
                                        <td><strong id="grand-total-value">{{ number_format($total, 0, ',', '.') }}
                                                VNĐ</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="shop-payment-method">
                                <button type="submit" class="btn-place-order">Đặt hàng</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<!-- @formatter:off -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('checkout-form');
            if (!form) return;

            // Các thành phần DOM
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const provinceSelect = $('#province_id');
            const districtSelect = $('#district_id');
            const wardSelect = $('#ward_code');
            const couponContainer = document.getElementById('checkout-coupon-form-container');
            const discountRow = document.getElementById('checkout-discount-row');
            const discountValueEl = document.getElementById('checkout-discount-value');
            const subtotalValueEl = document.getElementById('subtotal-value');
            const provinceNameInput = document.getElementById('province_name');
            const districtNameInput = document.getElementById('district_name');
            const wardNameInput = document.getElementById('ward_name');
            const shippingFeeValue = document.getElementById('shipping-fee-value');
            const grandTotalValue = document.getElementById('grand-total-value');
            const defaultProvinceId = form.dataset.defaultProvinceId || '';
            const defaultDistrictId = form.dataset.defaultDistrictId || '';
            const defaultWardCode = form.dataset.defaultWardCode || '';

            const subtotal = parseFloat('{{ $subtotal ?? 0 }}');
            const money = (v) => Number(v).toLocaleString('vi-VN');
            let currentShippingFee = 0;
            let cartTotalAfterDiscount = parseFloat('{{ $total ?? 0 }}');

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                },
            });

            const updateGrandTotal = () => {
                if (grandTotalValue) {
                    grandTotalValue.textContent = `${money(cartTotalAfterDiscount + currentShippingFee)} VNĐ`;
                }
            };

            function updateCheckoutTotals(cart) {
                if (!cart) return;

                if (subtotalValueEl) {
                    subtotalValueEl.textContent = `${money(cart.subtotal)} VNĐ`;
                }

                cartTotalAfterDiscount = Number(cart.total || 0);

                if (cart.coupon) {
                    if (discountValueEl) {
                        discountValueEl.textContent = `-${money(cart.discount)} VNĐ`;
                    }
                    if (discountRow) {
                        discountRow.classList.remove('d-none');
                    }

                    if (couponContainer) {
                        couponContainer.innerHTML = `
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <p class="m-0">Đang áp dụng mã: <strong>${cart.coupon.code}</strong></p>
                                <button type="button" id="checkout-remove-coupon-btn" class="btn-coupon">Tháo</button>
                            </div>
                        `;
                    }
                } else {
                    if (discountRow) {
                        discountRow.classList.add('d-none');
                    }

                    if (couponContainer) {
                        couponContainer.innerHTML = `
                            <div class="d-flex gap-2 flex-wrap">
                                <input type="text" id="checkout-coupon-code-input" class="form-control" placeholder="Mã phiếu giảm giá" style="max-width: 280px;">
                                <button type="button" id="checkout-apply-coupon-btn" class="btn-coupon">Áp dụng</button>
                            </div>
                        `;
                    }
                }

                updateGrandTotal();
            }

            function applyCoupon(code) {
                fetch("{{ route('cart.applyCoupon') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ coupon_code: code }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCheckoutTotals(data.cart);
                        Toast.fire({ icon: 'success', title: 'Đã áp dụng mã giảm giá' });
                    } else {
                        Toast.fire({ icon: 'error', title: data.message || 'Không thể áp dụng mã giảm giá.' });
                    }
                })
                .catch(() => Toast.fire({ icon: 'error', title: 'Không thể áp dụng mã giảm giá.' }));
            }

            function removeCoupon() {
                fetch("{{ route('cart.removeCoupon') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCheckoutTotals(data.cart);
                        Toast.fire({ icon: 'success', title: 'Đã tháo mã giảm giá' });
                    }
                })
                .catch(() => Toast.fire({ icon: 'error', title: 'Không thể tháo mã giảm giá.' }));
            }

            if (couponContainer) {
                couponContainer.addEventListener('click', function(event) {
                    if (event.target.id === 'checkout-apply-coupon-btn') {
                        const input = document.getElementById('checkout-coupon-code-input');
                        const code = input?.value?.trim();
                        if (!code) {
                            Toast.fire({ icon: 'info', title: 'Vui lòng nhập mã giảm giá.' });
                            return;
                        }
                        applyCoupon(code);
                    }

                    if (event.target.id === 'checkout-remove-coupon-btn') {
                        removeCoupon();
                    }
                });

                couponContainer.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter' && event.target.id === 'checkout-coupon-code-input') {
                        event.preventDefault();
                        const code = event.target.value?.trim();
                        if (code) {
                            applyCoupon(code);
                        }
                    }
                });
            }

            // Khởi tạo Select2 nếu thư viện đã tải thành công.
            if (typeof $.fn.select2 === 'function' && !provinceSelect.hasClass('select2-hidden-accessible')) {
                $('.select2-shipping').select2({
                    width: '100%',
                    placeholder: 'Chọn thông tin',
                });
            }
            const normalize = (res) => Array.isArray(res) ? res : (res.data || []);
            const getProvince = (item) => ({
                id: item?.ProvinceID ?? item?.province_id ?? item?.id ?? '',
                name: item?.ProvinceName ?? item?.province_name ?? item?.name ?? '',
            });
            const getDistrict = (item) => ({
                id: item?.DistrictID ?? item?.district_id ?? item?.id ?? '',
                name: item?.DistrictName ?? item?.district_name ?? item?.name ?? '',
            });
            const getWard = (item) => ({
                code: item?.WardCode ?? item?.ward_code ?? item?.code ?? '',
                name: item?.WardName ?? item?.ward_name ?? item?.name ?? '',
            });

            // ===== 1. TẢI TỈNH THÀNH =====
            const loadProvinces = async () => {
                try {
                    const res = await fetch(form.dataset.provincesUrl);
                    const data = await res.json();
                    const items = normalize(data);

                    provinceSelect.empty().append('<option value="">Chọn tỉnh/thành phố</option>');
                    items.forEach(item => {
                        const province = getProvince(item);
                        if (!province.id || !province.name) return;
                        provinceSelect.append(`<option value="${province.id}">${province.name}</option>`);
                    });

                    if (defaultProvinceId) {
                        provinceSelect.val(defaultProvinceId).trigger('change');
                    }

                } catch (e) {
                    console.error('Lỗi tỉnh:', e);
                    Toast.fire({ icon: 'error', title: 'Không tải được danh sách tỉnh/thành.' });
                }
            };

            // ===== 2. TẢI QUẬN HUYỆN =====
            const loadDistricts = async (provinceId) => {
                if (!provinceId) {
                    districtSelect.empty().append('<option value="">Chọn huyện</option>').prop('disabled', true);
                    wardSelect.empty().append('<option value="">Chọn xã</option>').prop('disabled', true);
                    return;
                }

                districtSelect.prop('disabled', true).empty().append('<option value="">Đang tải...</option>').trigger('change');

                try {
                    const res = await fetch(`${form.dataset.districtsUrl}?province_id=${encodeURIComponent(provinceId)}`);
                    const data = await res.json();
                    const items = normalize(data);

                    districtSelect.empty().append('<option value="">Chọn quận/huyện</option>');
                    items.forEach(item => {
                        const district = getDistrict(item);
                        if (!district.id || !district.name) return;
                        districtSelect.append(`<option value="${district.id}">${district.name}</option>`);
                    });
                    districtSelect.prop('disabled', false);

                    if (defaultDistrictId && String(provinceId) === String(defaultProvinceId)) {
                        districtSelect.val(defaultDistrictId);
                    }
                } catch (e) {
                    console.error('Lỗi huyện:', e);
                    districtSelect.empty().append('<option value="">Không tải được quận/huyện</option>').prop('disabled', true);
                    Toast.fire({ icon: 'error', title: 'Không tải được danh sách quận/huyện.' });
                }
                districtSelect.trigger('change');
            };

            // ===== 3. TẢI PHƯỜNG XÃ =====
            const loadWards = async (districtId) => {
                if (!districtId) {
                    wardSelect.empty().append('<option value="">Chọn xã</option>').prop('disabled', true);
                    return;
                }

                wardSelect.prop('disabled', true).empty().append('<option value="">Đang tải...</option>').trigger('change');

                try {
                    const res = await fetch(`${form.dataset.wardsUrl}?district_id=${encodeURIComponent(districtId)}`);
                    const data = await res.json();
                    const items = normalize(data);

                    wardSelect.empty().append('<option value="">Chọn phường/xã</option>');
                    items.forEach(item => {
                        const ward = getWard(item);
                        if (!ward.code || !ward.name) return;
                        wardSelect.append(`<option value="${ward.code}">${ward.name}</option>`);
                    });
                    wardSelect.prop('disabled', false);

                    if (defaultWardCode && String(districtId) === String(defaultDistrictId)) {
                        wardSelect.val(defaultWardCode);
                    }
                } catch (e) {
                    console.error('Lỗi xã:', e);
                    wardSelect.empty().append('<option value="">Không tải được phường/xã</option>').prop('disabled', true);
                    Toast.fire({ icon: 'error', title: 'Không tải được danh sách phường/xã.' });
                }
                wardSelect.trigger('change');
            };

            // ===== 4. TÍNH PHÍ VẬN CHUYỂN =====
            const refreshFee = async () => {
                const dId = districtSelect.val();
                const wCode = wardSelect.val();

                if (!dId || !wCode) {
                    shippingFeeValue.textContent = 'Chọn địa chỉ giao hàng';
                    currentShippingFee = 0;
                    updateGrandTotal();
                    return;
                }

                shippingFeeValue.textContent = 'Đang tính phí...';

                try {
                    const res = await fetch(`${form.dataset.feeUrl}?district_id=${dId}&ward_code=${wCode}`);
                    const data = await res.json();

                    if (data.shipping_fee !== undefined) {
                        currentShippingFee = Number(data.shipping_fee || 0);
                        shippingFeeValue.textContent = `${money(data.shipping_fee)} VNĐ`;
                        updateGrandTotal();
                    }
                } catch (e) {
                    currentShippingFee = 0;
                    shippingFeeValue.textContent = 'Lỗi tính phí';
                    updateGrandTotal();
                }
            };

            // ===== XỬ LÝ SỰ KIỆN =====
            provinceSelect.on('change', function() {
                const selected = this.options[this.selectedIndex];
                provinceNameInput.value = selected ? selected.text : '';
                districtNameInput.value = '';
                wardNameInput.value = '';
                loadDistricts(this.value);
            });
            districtSelect.on('change', function() {
                const selected = this.options[this.selectedIndex];
                districtNameInput.value = selected ? selected.text : '';
                wardNameInput.value = '';
                loadWards(this.value);
            });
            wardSelect.on('change', function() {
                const selected = this.options[this.selectedIndex];
                wardNameInput.value = selected ? selected.text : '';
                refreshFee();
            });

            // Chạy lần đầu
            loadProvinces();
        });
    </script>
    <!-- @formatter:on -->
@endpush