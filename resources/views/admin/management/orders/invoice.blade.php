<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
            line-height: 1.5;
            margin: 0;
            padding: 18px;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 6px;
            letter-spacing: 1px;
            text-align: center;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            text-align: center;
        }

        .muted {
            color: #5f6368;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 6px;
            letter-spacing: 0.4px;
        }

        .top-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .top-grid td {
            vertical-align: top;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            background: #fafafa;
        }

        .top-grid .info-block {
            width: 48.5%;
        }

        .top-grid .spacer {
            width: 3%;
            border: 0;
            background: transparent;
            padding: 0;
        }

        .meta-table,
        .summary-table,
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .meta-label {
            width: 42%;
            color: #5f6368;
        }

        .block {
            margin-top: 14px;
        }

        .items-table {
            margin-top: 8px;
            border: 1px solid #d1d5db;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #d1d5db;
            padding: 8px;
            vertical-align: top;
        }

        .items-table th {
            background: #f3f4f6;
            font-weight: bold;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-wrap {
            width: 42%;
            margin-left: auto;
            margin-top: 14px;
        }

        .summary-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-table tr.total-row td {
            border-top: 2px solid #111827;
            border-bottom: 0;
            font-size: 13px;
            font-weight: bold;
            padding-top: 9px;
        }

        .footer {
            margin-top: 24px;
            text-align: center;
            color: #6b7280;
            font-size: 11px;
        }

        .note-box {
            margin-top: 14px;
            border: 1px dashed #d1d5db;
            padding: 10px;
            background: #fcfcfc;
        }

        .note-box p {
            margin: 0;
        }
    </style>
</head>

<body>
    @php
    $subtotal = $order->items->sum(fn($item) => (float) $item->price * (int) $item->quantity);
    $discount = (float) ($order->discount_amount ?? 0);
    $shippingFee = (float) ($order->shipping_fee ?? 0);

    $provinceName = $order->shipping_province_name ?? $order->customer?->shipping_province_name ?? null;
    $districtName = $order->shipping_district_name ?? $order->customer?->shipping_district_name ?? null;
    $wardName = $order->shipping_ward_name ?? $order->customer?->shipping_ward_name ?? null;
    $fullShippingArea = implode(', ', array_filter([$wardName, $districtName, $provinceName]));
    @endphp

    <div class="container">
        <p class="invoice-title">HÓA ĐƠN</p>
        <p class="company-name">{{ setting('site_name', ' Cửa hàng ') }}</p>
        <p class="muted" style="margin: 4px 0 0;">Mã hóa đơn #{{ $order->id }} | Ngày lập: {{ $order->created_at->format('d/m/Y H:i') }}</p>

        <table class="top-grid block">
            <tr>
                <td class="info-block">
                    <p class="section-title">Thông Tin Khách Hàng</p>
                    <table class="meta-table">
                        <tr>
                            <td class="meta-label">Họ tên</td>
                            <td><strong>{{ trim(($order->first_name ?? '') . ' ' . ($order->last_name ?? '')) ?: 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="meta-label">Điện thoại</td>
                            <td>{{ $order->phone ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Email</td>
                            <td>{{ $order->email ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Địa chỉ giao hàng</td>
                            <td>
                                {{ $order->address ?: 'N/A' }}
                                @if($fullShippingArea !== '')
                                <br>
                                {{ $fullShippingArea }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="spacer"></td>
                <td class="info-block">
                    <p class="section-title">Thông Tin Đơn Hàng</p>
                    <table class="meta-table">
                        <tr>
                            <td class="meta-label">Trạng thái</td>
                            <td><strong>{{ $order->status->label() }}</strong></td>
                        </tr>
                        <tr>
                            <td class="meta-label">Thanh toán</td>
                            <td>Đã thanh toán trước qua QR</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Đơn vị vận chuyển</td>
                            <td>{{ $order->shipping_provider ? strtoupper((string) $order->shipping_provider) : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Mã đơn vận chuyển</td>
                            <td>{{ $order->ghn_order_code ?: 'Chưa tạo' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        @if(!empty($order->notes))
        <div class="note-box">
            <p><strong>Ghi chú khách hàng:</strong> {{ $order->notes }}</p>
        </div>
        @endif

        <table class="items-table block">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">#</th>
                    <th>Sản phẩm</th>
                    <th style="width: 10%;" class="text-center">SL</th>
                    <th style="width: 18%;" class="text-right">Đơn giá</th>
                    <th style="width: 20%;" class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format((float) $item->price, 0, ',', '.') }} VNĐ</td>
                    <td class="text-right">{{ number_format((float) $item->price * (int) $item->quantity, 0, ',', '.') }} VNĐ</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center muted">Không có sản phẩm trong đơn hàng.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="summary-wrap">
            <table class="summary-table">
                <tr>
                    <td><strong>Tổng phụ :</strong></td>
                    <td style="text-align: right;">{{ number_format($subtotal, 0, ',', '.') }} VNĐ</td>
                </tr>
                @if($discount > 0)
                <tr>
                    <td><strong>Giảm giá :</strong></td>
                    <td style="text-align: right;">-{{ number_format($discount, 0, ',', '.') }} VNĐ</td>
                </tr>
                @endif
                @if(!empty($order->coupon_code))
                <tr>
                    <td><strong>Mã ưu đãi :</strong></td>
                    <td style="text-align: right;">{{ $order->coupon_code }}</td>
                </tr>
                @endif
                <tr>
                    <td><strong>Phí vận chuyển :</strong></td>
                    <td style="text-align: right;">{{ number_format($shippingFee, 0, ',', '.') }} VNĐ</td>
                </tr>
                <tr class="total-row">
                    <td><strong>Tổng cộng:</strong></td>
                    <td style="text-align: right;">{{ number_format((float) $order->total_price, 0, ',', '.') }} VNĐ</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Cảm ơn quý khách đã tin dùng sản phẩm của chúng tôi!</p>
            <p>Hóa đơn được tạo tự động từ hệ thống Mint Cosmetics.</p>
        </div>
    </div>
</body>

</html>