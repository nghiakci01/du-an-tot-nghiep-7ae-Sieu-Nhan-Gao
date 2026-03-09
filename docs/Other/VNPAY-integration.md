# Hướng dẫn Tích hợp VNPAY (VNPAY Full Integration)

Tài liệu này hướng dẫn cách cấu hình, sử dụng và quản lý tích hợp thanh toán VNPAY trên website.

## 1. Cấu hình (Configuration)

### Môi trường Production (Thật)
Cấu hình trong file `.env` khi chạy chính thức:

```env
VNPAY_TMN_CODE=xxxxxx               # TmnCode do VNPAY cấp
VNPAY_HASH_SECRET=xxxxxxxxxxxxxx    # HashSecret do VNPAY cấp
VNPAY_URL=https://pay.vnpay.vn/vpcpay.html
VNPAY_RETURN_URL=https://yourdomain.com/vnpay/callback
VNPAY_API_URL=https://merchant.vnpay.vn/merchant_webapi/api/transaction
```

> [!WARNING]
> Trong môi trường Production, bạn bắt buộc phải sử dụng **HTTPS** và URL thật đã đăng ký với VNPAY.

### Môi trường Sandbox (Thử nghiệm)
Sử dụng các thông số sau để test:

```env
VNPAY_TMN_CODE=AWU16HEZ
VNPAY_HASH_SECRET=0A5JFNKM9QXOA8MJNCBWZT0WQN4L0FG2
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
VNPAY_RETURN_URL=http://elite.test/vnpay/callback
VNPAY_API_URL=https://sandbox.vnpayment.vn/merchant_webapi/api/transaction
```

> [!IMPORTANT]
> - `VNPAY_RETURN_URL`: URL mà khách hàng được chuyển hướng về sau khi thanh toán thành công/thất bại tại cổng VNPAY.
> - IPN URL mặc định được tính dựa trên App URL: `{base_url}/vnpay/ipn`. Bạn cần đảm bảo IPN URL này có thể truy cập từ server VNPAY (cần Public IP/Domain có SSL cho môi trường production).

## 2. Các thành phần chính

### VnpayService (`app/Services/VnpayService.php`)
Tập trung toàn bộ logic nghiệp vụ:
- `createPaymentUrl(Order $order)`: Tạo link thanh toán kèm `vnp_ExpireDate` (15 phút).
- `verifySignature(array $data)`: Xác thực chữ ký checksum (HMAC SHA512).
- `queryTransaction(Order $order)`: API QueryDR giúp admin kiểm tra trạng thái GD trực tiếp từ VNPAY.
- `refundTransaction(Order $order, int $amount, ...)`: API Refund giúp thực hiện hoàn tiền (toàn phần hoặc một phần).

### PaymentController (`app/Http/Controllers/Frontend/PaymentController.php`)
- `createPayment`: Xử lý redirect khách sang VNPAY.
- `vnpayReturn`: Xử lý kết quả trả về cho khách (User interface).
- `ipn`: Xử lý thông báo từ Server VNPAY (Server-to-Server). Thực hiện kiểm tra 5 bước (Checksum, OrderID, Amount, Duplicate, Update status).

## 3. Quản lý Đơn hàng (Admin)

Trong trang chi tiết đơn hàng admin (`admin/orders/{id}`), có 2 tính năng dành riêng cho đơn VNPAY:

1. **Truy vấn trạng thái VNPAY**: Gọi API QueryDR để kiểm tra tình trạng thực tế của giao dịch trên hệ thống VNPAY. Hữu ích khi IPN bị chậm hoặc lỗi.
2. **Hoàn tiền VNPAY**: Cho phép admin yêu cầu hoàn tiền (Refund) cho khách ngay tại dashboard nếu đơn đã thanh toán. Hỗ trợ hoàn toàn bộ hoặc hoàn một phần.

## 4. Kiểm thử Sandbox (Testing)

Sử dụng thẻ test của VNPAY để thanh toán trong môi trường Sandbox:

- **Ngân hàng**: NCB
- **Số thẻ**: `9704198526191432198`
- **Tên chủ thẻ**: `NGUYEN VAN A`
- **Ngày phát hành**: `07/15`
- **Mã OTP**: `123456`

## 5. Lưu ý quan trọng

- **Checksum**: Luôn sử dụng `VnpayService` để verify chữ ký để đảm bảo tính an toàn.
- **VND * 100**: Số tiền gửi sang VNPAY phải nhân với 100 (đơn vị xu).
- **IPN Response**: Hàm `ipn()` phải trả về JSON đúng định dạng `{"RspCode":"00", "Message":"Confirm Success"}` để VNPAY ngừng retry.
