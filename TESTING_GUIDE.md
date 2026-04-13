# Hướng dẫn Kiểm thử Toàn diện (Full Testing Guide)

Tài liệu này hướng dẫn cách kiểm thử tất cả các luồng nghiệp vụ chính của hệ thống để đảm bảo tính ổn định và chính xác của dữ liệu.

---

## 1. Luồng Khách hàng (Frontend Flow)

### A. Tài khoản & Bảo mật
- **Kiểm tra Đăng ký/Đăng nhập**: Đảm bảo logic validate thống nhất. Mật khẩu phải đủ độ phức tạp.
- **Quên mật khẩu**: Kiểm tra luồng gửi email và link đặt lại mật khẩu. Logic đặt lại mật khẩu phải khớp với logic đăng ký.

### B. Mua sắm & Thanh toán
- **Giỏ hàng**: Thêm/Sửa/Xóa sản phẩm, cập nhật số lượng tồn kho theo thời gian thực.
- **Mã giảm giá (Voucher)**: 
    - Test trường hợp áp mã giảm giá có giá trị lớn hơn tổng tiền (Hệ thống phải giữ mức tối thiểu 0đ hoặc báo lỗi, không được âm).
- **Checkout**: 
    - Chọn COD: Đơn hàng về trạng thái `Chờ xác nhận`.
    - Chọn Chuyển khoản (Bank): Phải nhận được email nhắc nhở nếu chưa thanh toán.

### C. Luồng Nhắc nhở & Tự động hủy (QUAN TRỌNG)
Để test luồng này mà không cần đợi 60 phút, hãy thực hiện các bước sau:
1. Tạo một đơn hàng với phương thức BANK.
2. Sử dụng script giả lập thời gian: `php tests/TestOrderReminders.php` (hoặc chỉnh sửa `created_at` trong database về thời điểm cách đây 16p, 31p, 61p).
3. Chạy lệnh: `php artisan app:check-payment-reminders`.
4. **Kỳ vọng**: 
    - 15p: Email nhắc nhở lần 1, `reminder_step` = 1.
    - 30p: Email nhắc nhở lần 2, `reminder_step` = 2.
    - 60p: Trạng thái đơn cập nhật `cancelled`, gửi email thông báo hủy.

---

## 2. Luồng Quản trị (Admin Flow)

### A. Dashboard & Thống kê
- **Top Favorites**: Kiểm tra danh sách sản phẩm được yêu thích nhất có hiển thị đúng thứ tự không.
- **Low Stock**: Kiểm tra hệ thống có liệt kê đúng các sản phẩm có số lượng < 10 (hoặc ngưỡng cấu hình) không.

### B. Quản lý Sản phẩm
- **Validate Giá**: Thử nhập giá gốc hoặc giá KM > `99.999.999`. Hệ thống phải báo lỗi.
- **Đếm bộ đếm ký tự**: Tại trang Create/Edit, nhập nội dung vào Mô tả ngắn (Max 500) và Mô tả dài (Max 5000), quan sát bộ đếm Javascript có hoạt động đúng không.

### C. Quản lý Đơn hàng
- **Hành động**: Kiểm tra nút **Sửa** đã bị ẩn hoàn toàn tại danh sách.
- **Chi tiết**: Chỉ được phép **Xem** và cập nhật **Trạng thái** (ví dụ: Chờ xác nhận -> Đã xác nhận).

---

## 3. Kiểm tra Biên & Lỗi hệ thống (Edge Cases)

| Kịch bản | Mong đợi |
| :--- | :--- |
| Nhập giá sản phẩm bằng 0 | Hệ thống cho phép hoặc báo lỗi tùy cấu hình (thường là > 0). |
| Đơn hàng đã hoàn thành nhưng chưa đánh giá | Phải hiển thị thông báo "Hãy đánh giá để nhận ưu đãi" tại trang chủ. |
| Link xem chi tiết trong Email đang giao hàng | Click vào link phải ra đúng trang `order/detail`, không được lỗi 404. |
| User thường cố gắng truy cập `/admin` | Phải bị chặn và redirect về trang chủ/login. |
| Truy cập biến thể sai (`size_id` vs `size`) | Hệ thống phải sử dụng quan hệ `sizeRelationship` để lấy tên chuẩn, không bị lỗi SQL. |

---

## 4. Script Hỗ trợ Test
Các script nằm trong thư mục `tests/`:
- `php tests/TestOrderReminders.php`: Test tự động nhắc nhở/hủy đơn.
- `php artisan tinker`: Dùng để kiểm tra nhanh các quan hệ Eloquent (ví dụ: `App\Models\ProductVariant::first()->sizeRelationship`).
