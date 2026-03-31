# PLAN: Hệ thống Quản lý Giao hàng cho Staff (Shipper)

Kế hoạch này chi tiết hóa việc xây dựng module giao hàng, từ quản lý đơn hàng phía Admin đến giao diện thực thi phía Shipper, kèm theo các tính năng nâng cao như Bản đồ và Thông báo.

## User Review Required

> [!IMPORTANT]
> **Thay đổi cấu trúc Cơ sở dữ liệu**:
> - Bảng `orders`: Thêm trường `shipper_id` (foreign key từ `users`) và `delivery_note`.
> - Thêm các trạng thái đơn hàng mới vào `Order` model: `PICKED_UP` (Shipper đã nhận hàng), `DELIVERING` (Đang giao), `DELIVERED_FAILED` (Giao thất bại).
> - Google Maps API: Yêu cầu bạn cung cấp API Key để tích hợp bản đồ.

## Proposed Changes

### [Component] Database & Models
- **[MODIFY] [Order.php](file:///c:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/app/Models/Order.php)**: Thêm hằng số Status và quan hệ `shipper()`.
- **[NEW] [Migration]**: Thêm `shipper_id` và các trường tracking đơn hàng vào bảng `orders`.

### [Component] Shipper Dashboard (Staff)
- **[NEW] [ShipperOrderController.php]**: Xử lý logic nhận đơn, cập nhật trạng thái.
- **[NEW] [index.blade.php]**: Danh sách đơn hàng tối ưu mobile (ID, Khách, Địa chỉ, SĐT, Nút bấm).
- **[NEW] [show.blade.php]**: Chi tiết đơn hàng (Sản phẩm, Tổng tiền, Ghi chú, Bản đồ Maps).

### [Component] Admin Management
- **[MODIFY] [AdminOrderController.php]**: Thêm logic gán shipper cho đơn hàng.
- **[MODIFY] [admin/orders/index.blade.php]**: Thêm cột Shipper, Dropdown chọn, Filter trạng thái giao hàng.

### [Component] Notifications & Emails
- **[NEW] [ShipperAssignedNotification.php]**: Thông báo real-time/database cho Shipper.
- **[NEW] [DeliverySuccessMail.php]**: Mail tự động gửi cho khách khi trạng thái là Completed.

---

## Open Questions

- **Bạn đã có Google Maps API Key chưa?** Nếu chưa, tôi có thể hướng dẫn lấy hoặc sử dụng OpenStreetMap làm phương án thay thế miễn phí.
- **Quy trình gán đơn**: Admin gán hay Shipper tự vào "nhặt" đơn? (Kế hoạch hiện tại hỗ trợ cả hai: Admin gán và Shipper nhấn "Nhận giao").

## Verification Plan

### Edge Case Tests (MANDATORY)
1. **Concurrency**: Đảm bảo 2 shipper không thể nhấn "Nhận giao" cùng 1 lúc cho 1 đơn (Sử dụng DB Lock).
2. **State Validity**: Shipper không được phép cập nhật "Giao thành công" nếu chưa nhấn "Nhận đơn/Đang giao".
3. **Cancellation**: Nếu Admin hoặc Khách hủy đơn khi đang giao, Shipper phải nhận được thông báo ngay lập tức.
4. **Network Loss**: Logic Frontend phải xử lý nếu shipper mất mạng khi đang nhấn cập nhật (Retry logic/Loading state).

### Automated Tests
- Chạy `php artisan test` với Feature test cho Shipper API.
- Kiểm tra email template trên `Mailtrap` hoặc `log`.
