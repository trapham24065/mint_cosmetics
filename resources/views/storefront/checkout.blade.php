@extends('storefront.layouts.app')
@section('content')
    <section class="shopping-checkout-wrap section-space">
        <div class="container">
            <form action="{{ route('customer.checkout.placeOrder') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="checkout-billing-details-wrap">
                            <h2 class="title">Chi tiết thanh toán</h2>
                            <div class="billing-form-wrap">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Tên<abbr
                                                    class="required">*</abbr></label><input name="first_name"
                                                                                            type="text"
                                                                                            class="form-control"
                                                                                            required></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Họ<abbr class="required">*</abbr></label><input
                                                name="last_name" type="text" class="form-control" required></div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group"><label>Địa chỉ đường phố<abbr
                                                    class="required">*</abbr></label><input name="address" type="text"
                                                                                            class="form-control"
                                                                                            required></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Điện thoại <abbr
                                                    class="required">*</abbr></label><input name="phone" type="text"
                                                                                            class="form-control"
                                                                                            required></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Địa chỉ email<abbr
                                                    class="required">*</abbr></label><input name="email" type="email"
                                                                                            class="form-control"
                                                                                            required></div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-0"><label>Ghi chú đơn hàng (tùy chọn)</label><textarea
                                                name="notes" class="form-control"
                                                placeholder="Ghi chú về đơn hàng của bạn..."></textarea></div>
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
                                        <td>{{ number_format($subtotal, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                    <tr class="order-total">
                                        <th>Tổng cộng</th>
                                        <td><strong>{{ number_format($total, 0, ',', '.') }} VNĐ</strong></td>
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
