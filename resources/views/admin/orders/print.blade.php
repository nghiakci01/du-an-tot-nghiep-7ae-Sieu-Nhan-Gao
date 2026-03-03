<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }

        .invoice-box table {
            width: 100%;
            text-align: left;
            border-collapse: collapse;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .top-table td {
            padding-bottom: 20px;
        }

        .title {
            font-size: 28px;
            color: #333;
            font-weight: bold;
        }

        .information-table {
            margin-bottom: 20px;
        }

        .item-table th {
            background: #eee;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .item-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .text-right {
            text-align: right;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                margin: 0;
                padding: 0;
            }

            .invoice-box {
                box-shadow: none;
                border: none;
                padding: 0;
                max-width: 100%;
            }

            .print-button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div style="text-align: center; margin-bottom: 20px;" class="print-button">
        <button onclick="window.print()"
            style="padding: 10px 20px; font-size: 16px; cursor: pointer; background-color: #007bff; color: white; border: none; border-radius: 5px;">In
            Hóa Đơn Lập Tức</button>
        <button onclick="window.close()"
            style="padding: 10px 20px; font-size: 16px; cursor: pointer; background-color: #6c757d; color: white; border: none; border-radius: 5px; margin-left: 10px;">Đóng
            Màn Hình Này</button>
    </div>

    <div class="invoice-box">
        <table class="top-table">
            <tr>
                <td class="title">
                    <img src="{{ asset('assets/images/logo.png') }}" style="max-height: 50px; margin-bottom: 10px;"
                        alt="{{ config('app.name') }}"><br>
                    HÓA ĐƠN BÁN HÀNG
                </td>
                <td class="text-right" style="vertical-align: top;">
                    @php
                        $phone = $order->user ? ($order->user->phone ?? 'N/A') : ($order->phone ? $order->phone : 'N/A');
                        $name = $order->user ? $order->user->name : ($order->name ? $order->name : 'Khách vãng lai');
                        $total = number_format($order->final_total ?? $order->total_price, 0, ',', '.') . " VND";
                        $address = $order->shipping_address;
                        $status = $order->status_text;

                        $qrContent = "Mã Đơn: #" . $order->id . "\nKhách: " . $name . "\nSĐT: " . $phone . "\nĐịa Chỉ: " . $address . "\nTổng Tiền: " . $total . "\nTrạng Thái: " . $status;
                        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=" . urlencode($qrContent);
                    @endphp
                    Mã đơn: #{{ $order->id }}<br>
                    Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}<br>
                    Ngày in: {{ now()->format('d/m/Y H:i') }}
                </td>
                <td style="width: 130px; text-align: right; vertical-align: top;">
                    <img src="{{ $qrCodeUrl }}" alt="Mã QR Đơn Hàng" style="width: 120px; height: 120px; border: 1px solid #ddd; padding: 2px;">
                    <div style="font-size: 11px; margin-top: 3px; color: #666; text-align: center;">Quét để xem thông tin</div>
                </td>
            </tr>
        </table>

        <table class="information-table">
            <tr>
                <td width="50%">
                    <h3 style="margin-top: 0;">Thông tin cửa hàng</h3>
                    <strong>{{ config('app.name', 'Cửa hàng Gạo') }}</strong><br>
                    Địa chỉ: Số 123, Đường XYZ, Quận ABC<br>
                    SĐT: 0123 456 789<br>
                    Email: support@cuahang.com
                </td>
                <td width="50%" class="text-right">
                    <h3 style="margin-top: 0;">Thông tin khách hàng</h3>
                    <strong>{{ $order->user ? $order->user->name : ($order->name ? $order->name : 'Khách vãng lai') }}</strong><br>
                    Địa chỉ: {{ $order->shipping_address }}<br>
                    SĐT:
                    {{ $order->user ? ($order->user->phone ?? 'N/A') : ($order->phone ? $order->phone : 'N/A') }}<br>
                    Email: {{ $order->user ? $order->user->email : ($order->email ? $order->email : 'N/A') }}
                </td>
            </tr>
        </table>

        <table class="item-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th class="text-right">Đơn giá</th>
                    <th class="text-right">SL</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product->name }}</strong>
                            @if($item->variant)
                                <br><small>Phân loại: {{ $item->variant->name }}</small>
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->total, 0, ',', '.') }}đ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table style="margin-top: 20px; width: 50%; float: right;">
            <tr>
                <td>Tạm tính:</td>
                <td class="text-right">{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
            </tr>
            @if($order->shipping_fee > 0)
                <tr>
                    <td>Phí giao hàng:</td>
                    <td class="text-right">{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</td>
                </tr>
            @endif
            @if($order->discount_amount > 0)
                <tr>
                    <td>Giảm giá:</td>
                    <td class="text-right">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</td>
                </tr>
            @endif
            <tr>
                <td><strong>Tổng cộng thanh toán:</strong></td>
                <td class="text-right">
                    <strong>{{ number_format($order->final_total ?? $order->total_price, 0, ',', '.') }}đ</strong></td>
            </tr>
        </table>

        <div style="clear: both;"></div>

        <div style="margin-top: 40px; text-align: center;">
            <p><strong>Cảm ơn quý khách đã mua sắm tại {{ config('app.name', 'Cửa hàng Gạo') }}!</strong></p>
            <p style="font-style: italic; font-size: 12px;">(Xin vui lòng giữ lại hóa đơn để đối chiếu khi cần thiết)
            </p>
        </div>
    </div>
    <script>
        // // Tự động bật hộp thoại in khi trang vừa tải xong (Tùy chọn)
        // window.onload = function() { window.print(); }
    </script>
</body>

</html>