@extends('storefront.layouts.app')

@section('content')
<section class="shopping-checkout-wrap section-space">
    <div class="container">
        @if (session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
        @endif

        <form
            action="{{ route('customer.checkout.placeOrder') }}"
            method="post"
            id="checkout-form"
            data-provinces-url="{{ route('customer.checkout.ghn.provinces') }}"
            data-districts-url="{{ route('customer.checkout.ghn.districts') }}"
            data-wards-url="{{ route('customer.checkout.ghn.wards') }}"
            data-fee-url="{{ route('customer.checkout.ghn.fee') }}">
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <div class="checkout-billing-details-wrap">
                        <h2 class="title">Chi tiết thanh toán</h2>
                        <div class="billing-form-wrap">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tên<abbr class="required">*</abbr></label>
                                        <input name="first_name" type="text" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Họ<abbr class="required">*</abbr></label>
                                        <input name="last_name" type="text" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tỉnh/Thành phố<abbr class="required">*</abbr></label>
                                        <select name="province_id" id="province_id" class="form-control" required>
                                            <option value="">Đang tải...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Quận/Huyện<abbr class="required">*</abbr></label>
                                        <select name="district_id" id="district_id" class="form-control" required disabled>
                                            <option value="">Chọn tỉnh/thành phố trước</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Phường/Xã<abbr class="required">*</abbr></label>
                                        <select name="ward_code" id="ward_code" class="form-control" required disabled>
                                            <option value="">Chọn quận/huyện trước</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Địa chỉ đường phố<abbr class="required">*</abbr></label>
                                        <input name="address" type="text" class="form-control" required placeholder="Số nhà, tên đường...">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Điện thoại <abbr class="required">*</abbr></label>
                                        <input name="phone" type="text" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Địa chỉ email<abbr class="required">*</abbr></label>
                                        <input name="email" type="email" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label>Ghi chú đơn hàng (tùy chọn)</label>
                                        <textarea name="notes" class="form-control" placeholder="Ghi chú về đơn hàng của bạn..."></textarea>
                                    </div>
                                </div>
                            </div>
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
                                        <td class="product-name">{{ $item['product_name'] }} <span class="product-quantity">× {{ $item['quantity'] }}</span></td>
                                        <td class="product-total">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-foot">
                                    <tr class="cart-subtotal">
                                        <th>Tổng phụ</th>
                                        <td id="subtotal-value">{{ number_format($subtotal, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                    <tr class="shipping-fee-row">
                                        <th>Phí vận chuyển GHN</th>
                                        <td id="shipping-fee-value">Chọn địa chỉ giao hàng</td>
                                    </tr>
                                    <tr class="order-total">
                                        <th>Tổng cộng</th>
                                        <td><strong id="grand-total-value">{{ number_format($total, 0, ',', '.') }} VNĐ</strong></td>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('checkout-form');
        if (!form) {
            return;
        }

        const provincesUrl = form.dataset.provincesUrl;
        const districtsUrl = form.dataset.districtsUrl;
        const wardsUrl = form.dataset.wardsUrl;
        const feeUrl = form.dataset.feeUrl;
        const provinceSelect = document.getElementById('province_id');
        const districtSelect = document.getElementById('district_id');
        const wardSelect = document.getElementById('ward_code');
        const shippingFeeValue = document.getElementById('shipping-fee-value');
        const grandTotalValue = document.getElementById('grand-total-value');
        const subtotal = parseFloat('{{ $subtotal ?? 0 }}');

        const money = (value) => Number(value).toLocaleString('vi-VN');

        const resetSelect = (select, placeholder, disabled = true) => {
            select.innerHTML = `<option value="">${placeholder}</option>`;
            select.disabled = disabled;
        };

        const setFee = (shippingFee, grandTotal) => {
            shippingFeeValue.textContent = `${money(shippingFee)} VNĐ`;
            grandTotalValue.textContent = `${money(grandTotal)} VNĐ`;
        };

        const loadProvinces = async () => {
            resetSelect(provinceSelect, 'Đang tải tỉnh/thành phố...');

            try {
                const response = await fetch(provincesUrl, {
                    headers: {
                        Accept: 'application/json'
                    }
                });
                const provinces = await response.json();

                provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
                provinces.forEach((province) => {
                    const option = document.createElement('option');
                    option.value = province.ProvinceID;
                    option.textContent = province.ProvinceName;
                    provinceSelect.appendChild(option);
                });

                provinceSelect.disabled = false;
            } catch (error) {
                console.error('Load provinces failed:', error);
                provinceSelect.innerHTML = '<option value="">Không tải được danh sách tỉnh/thành phố</option>';
            }
        };

        const loadDistricts = async (provinceId) => {
            resetSelect(districtSelect, 'Đang tải quận/huyện...');
            resetSelect(wardSelect, 'Chọn quận/huyện trước');

            if (!provinceId) {
                return;
            }

            try {
                const response = await fetch(`${districtsUrl}?province_id=${encodeURIComponent(provinceId)}`, {
                    headers: {
                        Accept: 'application/json'
                    },
                });
                const districts = await response.json();

                districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                districts.forEach((district) => {
                    const option = document.createElement('option');
                    option.value = district.DistrictID;
                    option.textContent = district.DistrictName;
                    districtSelect.appendChild(option);
                });

                districtSelect.disabled = false;
            } catch (error) {
                console.error('Load districts failed:', error);
                districtSelect.innerHTML = '<option value="">Không tải được danh sách quận/huyện</option>';
            }
        };

        const loadWards = async (districtId) => {
            resetSelect(wardSelect, 'Đang tải phường/xã...');

            if (!districtId) {
                return;
            }

            try {
                const response = await fetch(`${wardsUrl}?district_id=${encodeURIComponent(districtId)}`, {
                    headers: {
                        Accept: 'application/json'
                    },
                });
                const wards = await response.json();

                wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                wards.forEach((ward) => {
                    const option = document.createElement('option');
                    option.value = ward.WardCode;
                    option.textContent = ward.WardName;
                    wardSelect.appendChild(option);
                });

                wardSelect.disabled = false;
            } catch (error) {
                console.error('Load wards failed:', error);
                wardSelect.innerHTML = '<option value="">Không tải được danh sách phường/xã</option>';
            }
        };

        const refreshFee = async () => {
            const districtId = districtSelect.value;
            const wardCode = wardSelect.value;

            if (!districtId || !wardCode) {
                shippingFeeValue.textContent = 'Chọn đầy đủ quận/huyện và phường/xã';
                grandTotalValue.textContent = `${money(subtotal)} VNĐ`;
                return;
            }

            shippingFeeValue.textContent = 'Đang tính phí GHN...';

            try {
                const response = await fetch(`${feeUrl}?district_id=${encodeURIComponent(districtId)}&ward_code=${encodeURIComponent(wardCode)}`, {
                    headers: {
                        Accept: 'application/json'
                    },
                });
                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Không thể tính phí vận chuyển.');
                }

                setFee(data.shipping_fee ?? 0, data.grand_total ?? subtotal);
            } catch (error) {
                console.error('Calculate shipping fee failed:', error);
                shippingFeeValue.textContent = 'Không tính được phí GHN';
                grandTotalValue.textContent = `${money(subtotal)} VNĐ`;
            }
        };

        provinceSelect.addEventListener('change', async () => {
            resetSelect(districtSelect, 'Chọn quận/huyện trước');
            resetSelect(wardSelect, 'Chọn quận/huyện trước');
            shippingFeeValue.textContent = 'Chọn địa chỉ giao hàng';
            grandTotalValue.textContent = `${money(subtotal)} VNĐ`;
            await loadDistricts(provinceSelect.value);
        });

        districtSelect.addEventListener('change', async () => {
            resetSelect(wardSelect, 'Chọn phường/xã trước');
            shippingFeeValue.textContent = 'Chọn địa chỉ giao hàng';
            grandTotalValue.textContent = `${money(subtotal)} VNĐ`;
            await loadWards(districtSelect.value);
        });

        wardSelect.addEventListener('change', refreshFee);

        loadProvinces();
    });
</script>
@endpush