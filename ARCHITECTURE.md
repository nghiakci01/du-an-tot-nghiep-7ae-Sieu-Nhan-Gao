# Đặc tả Cấu trúc Dự án (Architecture Specification)

Dự án này là một hệ thống thương mại điện tử (e-commerce) được xây dựng trên nền tảng **Laravel 12** và **PHP 8.2**. Tài liệu này mô tả chi tiết cấu trúc thư mục, các thành phần cốt lõi và các quy tắc nghiệp vụ chính của hệ thống.

---

## 🚀 Tech Stack
- **Framework**: Laravel 12 (với các tính năng mới nhất).
- **Ngôn ngữ**: PHP 8.2.
- **Cơ sở dữ liệu**: MySQL (hỗ trợ quan hệ thực thể phức tạp).
- **Giao diện**: Blade Templates, Vanilla CSS, Javascript (Vite/Mix).
- **Công cụ bổ trợ**: Carbon (thời gian), Mailtrap/SMTP (email), SweetAlert2 (thông báo).

---

## 📁 Cấu trúc Thư mục Hệ thống

### 1. Controllers (`app/Http/Controllers`)
Chia làm 3 khu vực chính:
- **Admin/**: Quản lý toàn bộ hệ thống (Sản phẩm, Đơn hàng, Dashboard, Người dùng).
- **Frontend/**: Xử lý các luồng của khách hàng (Trang chủ, Giỏ hàng, Đặt hàng, Trang cá nhân).
- **Auth/**: Xử lý Đăng ký, Đăng nhập, Đổi mật khẩu (đồng bộ logic validate giữa các trang).

### 2. Models & Relationships (`app/Models`)
Các Model quan trọng và quan hệ:
- **Order**: Quản lý đơn hàng. Chứa logic `status_badge`, `status_text`.
- **Product**: Sản phẩm chính, hỗ trợ mô tả ngắn (500 ký tự) và dài (5000 ký tự).
- **ProductVariant**: Biến thể sản phẩm.
    - **Quan trọng**: Sử dụng `sizeRelationship()` và `colorRelationship()` thay vì `size/color` do trùng tên cột trong DB.
- **User**: Phân quyền hệ thống. Chỉ còn 2 vai trò: `Admin` và `User` (Đã loại bỏ actor Nhân viên).

### 3. Services (`app/Services`)
Lớp xử lý nghiệp vụ trung gian (Business Logic Layer) để giữ Controller gọn gàng:
- **OrderService**: Quy trình tạo đơn, cập nhật trạng thái, xử lý hoàn kho.
- **CartService**: Quản lý giỏ hàng trong session/database.
- **ConversionTrackingService**: (Đã được tối giản sau khi xóa phễu chuyển đổi cũ).

### 4. Background Tasks (`app/Console/Commands`)
- **CheckUnpaidOrders**: Command định kỳ (`app:check-payment-reminders`) thực hiện:
    - Nhắc nhở lần 1 sau **15 phút**.
    - Nhắc nhở lần 2 sau **30 phút**.
    - Tự động hủy đơn thanh toán online sau **60 phút** nếu chưa thanh toán.

---

## ⚙️ Logic Nghiệp vụ Cốt lõi

### Hệ thống Đơn hàng
- **Quy trình thanh toán**: Hỗ trợ COD và Chuyển khoản ngân hàng.
- **Bảo mật trạng thái**: Admin chỉ được phép **Xem** chi tiết đơn hàng, không được phép chỉnh sửa trực tiếp thông tin đơn hàng để đảm bảo tính minh bạch của dữ liệu.
- **Validate Coupon**: Ngăn chặn việc áp mã giảm giá khiến tổng tiền đơn hàng bị âm.

### Quản lý Sản phẩm
- **Ràng buộc dữ liệu**: Giá gốc và giá khuyến mãi không được vượt quá `99.999.999`.
- **Mô tả**: Sử dụng script Javascript để đếm ký tự thời gian thực tại trang Create/Edit.

### Dashboard Admin
- Tập trung vào 2 chỉ số quan trọng: **Top sản phẩm được yêu thích nhất** (Favorites) và **Danh sách hàng sắp hết** (Low Stock).

---

## 🛠️ Quy tắc Code (Clean Code)
- Tuân thủ chuẩn PSR-12.
- Controller chỉ gọi Service, không viết logic DB trực tiếp.
- Sử dụng Request Classes (`StoreProductRequest`, `UpdateProductRequest`) để validate dữ liệu đầu vào.
