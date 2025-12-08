@extends('storefront.layouts.app')
@section('content')
    <section class="shopping-checkout-wrap section-space">
        <div class="container">
            <form action="{{ route('customer.checkout.placeOrder') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="checkout-billing-details-wrap">
                            <h2 class="title">Billing details</h2>
                            <div class="billing-form-wrap">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group"><label>First name <abbr
                                                    class="required">*</abbr></label><input name="first_name"
                                                                                            type="text"
                                                                                            class="form-control"
                                                                                            required></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Last name <abbr class="required">*</abbr></label><input
                                                name="last_name" type="text" class="form-control" required></div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group"><label>Street address <abbr
                                                    class="required">*</abbr></label><input name="address" type="text"
                                                                                            class="form-control"
                                                                                            required></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Phone <abbr
                                                    class="required">*</abbr></label><input name="phone" type="text"
                                                                                            class="form-control"
                                                                                            required></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Email address <abbr
                                                    class="required">*</abbr></label><input name="email" type="email"
                                                                                            class="form-control"
                                                                                            required></div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-0"><label>Order notes (optional)</label><textarea
                                                name="notes" class="form-control"
                                                placeholder="Notes about your order..."></textarea></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="checkout-order-details-wrap">
                            <h2 class="title mb-25">Your order</h2>
                            <div class="order-details-table-wrap table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="product-name">Product</th>
                                        <th class="product-total">Total</th>
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
                                        <th>Subtotal</th>
                                        <td>{{ number_format($subtotal, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                    <tr class="order-total">
                                        <th>Total</th>
                                        <td><strong>{{ number_format($total, 0, ',', '.') }} VNĐ</strong></td>
                                    </tr>
                                    </tfoot>
                                </table>
                                <div class="shop-payment-method">
                                    <button type="submit" class="btn-place-order">Place order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
