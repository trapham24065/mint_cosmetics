<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        .container { width: 100%; margin: 0 auto; }
        .header, .footer { text-align: center; }
        .header h1 { margin: 0; }
        .invoice-details { margin-top: 20px; margin-bottom: 20px; }
        .invoice-details table { width: 100%; border-collapse: collapse; }
        .invoice-details td { padding: 5px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .items-table th { background-color: #f2f2f2; }
        .totals { float: right; width: 40%; margin-top: 20px; }
        .totals table { width: 100%; }
        .totals td { padding: 5px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>HÓA ĐƠN</h1>
        <p>Mint Cosmetics</p>
    </div>

    <div class="invoice-details">
        <table>
            <tr>
                <td><strong>Hóa đơn #:</strong> {{ $order->id }}</td>
                <td style="text-align: right;"><strong>Date:</strong> {{ $order->created_at->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Đã lập hóa đơn cho:</strong></td>
            </tr>
            <tr>
                <td colspan="2">
                    {{ $order->first_name }} {{ $order->last_name }}<br>
                    {{ $order->address }}<br>
                    {{ $order->phone }}<br>
                    {{ $order->email }}
                </td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
        <tr>
            <th>Sản phẩm</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Tổng cộng</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} VNĐ</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td><strong>Tổng phụ :</strong></td>
                <td style="text-align: right;">{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</td>
            </tr>
            <tr>
                <td><strong>Vận chuyển :</strong></td>
                <td style="text-align: right;">0 VNĐ</td>
            </tr>
            <tr style="font-weight: bold; border-top: 2px solid black;">
                <td><strong>Tổng cộng:</strong></td>
                <td style="text-align: right;">{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</td>
            </tr>
        </table>
    </div>

    <div class="footer" style="margin-top: 100px;">
        <p>Cảm ơn quý khách đã tin dùng sản phẩm của chúng tôi!</p>
    </div>
</div>
</body>
</html>
