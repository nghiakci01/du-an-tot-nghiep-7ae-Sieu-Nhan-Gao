<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phiếu Giao Hàng #{{ $order->id }}</title>
    <style>
        @page {
            size: A5;
            margin: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            color: #000;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            background: #fff;
            font-size: 13px;
        }

        .shipping-label {
            max-width: 148mm;
            /* A5 width */
            margin: auto;
            border: 2px solid #000;
            padding: 10px;
            box-sizing: border-box;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .logo-area {
            width: 40%;
        }

        .logo-area img {
            max-height: 50px;
            max-width: 100%;
        }

        .barcode-area {
            width: 60%;
            text-align: right;
        }

        .barcode-area img {
            height: 50px;
            max-width: 100%;
        }

        .order-code {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
        }

        .address-section {
            display: flex;
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
        }

        .sender,
        .receiver {
            width: 50%;
            padding: 5px;
            box-sizing: border-box;
        }

        .receiver {
            border-left: 2px dashed #000;
        }

        .address-title {
            font-weight: bold;
            margin-bottom: 5px;
            text-decoration: underline;
        }

        .content-section {
            display: flex;
            border-bottom: 2px solid #000;
        }

        .items-list {
            width: 70%;
            padding: 5px;
            border-right: 2px dashed #000;
        }

        .items-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .item {
            margin-bottom: 5px;
        }

        .qr-area {
            width: 30%;
            text-align: center;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .qr-area img {
            max-width: 100%;
            height: auto;
        }

        .footer-section {
            display: flex;
            margin-top: 10px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .cod-area {
            width: 50%;
            padding: 5px;
            border-right: 2px solid #000;
        }

        .cod-title {
            font-size: 12px;
        }

        .cod-amount {
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
        }

        .signature-area {
            width: 50%;
            padding: 5px;
            text-align: center;
        }

        .signature-box {
            border: 1px solid #000;
            height: 80px;
            margin-top: 5px;
            position: relative;
        }
        
        .signature-note {
            font-size: 10px;
            font-style: italic;
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            text-align: center;
            color: #555;
        }

        .instruction-area {
            padding: 5px;
            font-weight: bold;
            font-size: 12px;
        }

        @media print {
            body {
                padding: 0;
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
            Phiếu Giao Hàng</button>
        <button onclick="window.close()"
            style="padding: 10px 20px; font-size: 16px; cursor: pointer; background-color: #6c757d; color: white; border: none; border-radius: 5px; margin-left: 10px;">Đóng
            Màn Hình Này</button>
    </div>

    @php
        $phone = $order->user ? ($order->user->phone ?? 'N/A') : ($order->phone ? $order->phone : 'N/A');
        $name = $order->user ? $order->user->name : ($order->name ? $order->name : 'Khách vãng lai');
        
        // Tiền thu hộ COD: Nếu thanh toán rồi thì thu 0đ, nếu chưa thanh toán thì thu tổng tiền
        $isBankTransfer = $order->payment_method === 'BANK_TRANSFER' && $order->payment_status === 'PAID';
        $totalToCollect = $isBankTransfer ? 0 : ($order->final_total ?? $order->total_price);
        $totalFormatted = number_format($totalToCollect, 0, ',', '.') . " VNĐ";
        
        $address = $order->shipping_address;
        $status = $order->status_text;

        // Mã Vạch
        $barcodeUrl = "https://barcode.tec-it.com/barcode.ashx?data=" . $order->id . "&code=Code128&translate-esc=true&dpi=96";
        
        // QR Code thông tin
        $qrContent = "Mã Đơn: #" . $order->id . "\nKhách: " . $name . "\nSĐT: " . $phone . "\nĐịa Chỉ: " . $address . "\nThu hộ: " . $totalFormatted;
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrContent);
        
        // Tính tổng số lượng SP
        $totalQty = 0;
        foreach($order->items as $item) {
            $totalQty += $item->quantity;
        }
    @endphp

    <div class="shipping-label">
        <div class="header-section">
            <div class="logo-area">
                <img src="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name') }}">
                <div style="font-weight: bold; font-size: 16px; margin-top: 5px;">{{ $order->shipping_service_name ?? 'Giao Hàng Tiêu Chuẩn' }}</div>
            </div>
            <div class="barcode-area">
                <img src="{{ $barcodeUrl }}" alt="Barcode">
                <div class="order-code">Mã đơn hàng: {{ $order->id }}</div>
            </div>
        </div>

        <div class="address-section">
            <div class="sender">
                <div class="address-title">Từ:</div>
                <strong>{{ config('app.name', 'Cửa hàng Gạo') }}</strong><br>
                {{ \App\Models\Setting::get('store_address', 'Số 123, Đường XYZ, Quận ABC, TP.HCM') }}<br>
                <br>
                <strong>SĐT:</strong> {{ \App\Models\Setting::get('store_phone', '0123 456 789') }}
            </div>
            <div class="receiver">
                <div class="address-title">Đến:</div>
                <strong>{{ $name }}</strong><br>
                {{ $address }}<br>
                <br>
                <strong>SĐT:</strong> {{ $phone }}
            </div>
        </div>

        <div class="content-section">
            <div class="items-list">
                <div class="items-title">Nội dung hàng (Tổng SL sản phẩm: {{ $totalQty }})</div>
                @foreach($order->items as $index => $item)
                    <div class="item">
                        {{ $index + 1 }}. {{ $item->product->name }}
                        @if($item->variant)
                            ({{ $item->variant->name }})
                        @endif
                        - SL: {{ $item->quantity }}
                    </div>
                @endforeach
                <div style="font-style: italic; color: #666; font-size: 11px; margin-top: 10px;">
                    Một số sản phẩm có thể bị ẩn do danh sách quá dài
                </div>
            </div>
            <div class="qr-area">
                <img src="{{ $qrCodeUrl }}" alt="QR Code">
            </div>
        </div>

        <div class="footer-section">
            <div class="cod-area">
                <div class="cod-title">Tiền thu Người nhận:</div>
                <div class="cod-amount">{{ $totalFormatted }}</div>
            </div>
            <div class="signature-area">
                <div style="font-size: 11px; text-align: right; margin-bottom: 5px;">Khối lượng tối đa: {{ $order->weight ?? 1000 }} g</div>
                <div style="font-weight: bold; font-size: 12px;">Chữ ký người nhận</div>
                <div class="signature-box">
                    <div class="signature-note">Xác nhận hàng nguyên vẹn, không móp/méo, bể/vỡ</div>
                </div>
            </div>
        </div>

        <div class="instruction-area">
            Chỉ dẫn giao hàng: Không đồng kiểm.
            @if($order->note)
            <br>Ghi chú: {{ $order->note }}
            @endif
        </div>
    </div>
    
    <script>
        // window.onload = function() { window.print(); }
    </script>
</body>

</html>