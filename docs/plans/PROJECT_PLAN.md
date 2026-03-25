# KẾ HOẠCH DỰ ÁN: Website Bán Quần Áo (Đồ Án Tốt Nghiệp)

> **Mục tiêu**: Xây dựng một website thương mại điện tử B2C hoàn chỉnh trong vòng 4 tháng.
> **Dựa trên**: [read.md](read.md) (Đặc tả yêu cầu phần mềm - SRS)

## 1. Yêu cầu Kỹ thuật (Technical Stack)

| Thành phần | Công nghệ | Lý do chọn |
| :--- | :--- | :--- |
| **Backend** | **PHP / Laravel 10+** | Mạnh mẽ, chuẩn mực cho đồ án tốt nghiệp, dễ dàng tích hợp với CSDL quan hệ. |
| **Frontend** | **Blade Templates + Bootstrap 5** | Phát triển nhanh, responsive, dễ tích hợp với Laravel. (Thay thế: React/Inertia nếu yêu cầu SPA). |
| **Database** | **MySQL** | Cơ sở dữ liệu quan hệ tiêu chuẩn cho thương mại điện tử. |
| **Server** | **Laragon / Apache hoặc Nginx** | Môi trường phát triển cục bộ (Local). |
| **Công cụ** | Git, Composer, NPM | Quản lý phiên bản và gói phụ thuộc. |

---

## 2. Lộ trình Phát triển (4 Tháng)

### Giai đoạn 1: Nền tảng & Thiết kế (Tháng 1)
**Trọng tâm**: Yêu cầu, Cơ sở dữ liệu, Cài đặt dự án, Xác thực.

- **Tuần 1: Phân tích & Thiết kế**
    - [x] Phân tích SRS (`read.md`).
    - [ ] Chốt sơ đồ Cơ sở dữ liệu (ERD).
    - [ ] Tạo Mockups giao diện (Wireframes).
- **Tuần 2: Cài đặt Dự án**
    - [ ] Cài đặt Laravel, thiết lập Git.
    - [ ] Cấu hình kết nối Cơ sở dữ liệu.
    - [ ] Thiết lập Frontend assets (Bootstrap/CSS).
- **Tuần 3: Triển khai Cơ sở dữ liệu**
    - [ ] Tạo Migrations cho các bảng cốt lõi.
    - [ ] Tạo Models và Relationships.
    - [ ] Seed dữ liệu mẫu để kiểm thử.
- **Tuần 4: Xác thực & Phân quyền**
    - [ ] Triển khai Đăng nhập/Đăng ký (Khách/Người dùng).
    - [ ] Triển khai Guard cho Admin.
    - [ ] Tạo giao diện Admin Dashboard cơ bản.

### Giai đoạn 2: Các Module Cốt lõi - Sản phẩm & Người dùng (Tháng 2)
**Trọng tâm**: Danh mục, Quản lý sản phẩm, Tài khoản người dùng.

- **Tuần 5: Quản lý Danh mục & Sản phẩm (Admin)**
    - [ ] CRUD Danh mục (Cha/Con).
    - [ ] CRUD Sản phẩm (Ảnh, Mô tả, Giá).
    - [ ] Quản lý Biến thể sản phẩm (Size/Màu sắc).
- **Tuần 6: Catalog Công khai (Khách/Người dùng)**
    - [ ] Trang chủ (Sản phẩm nổi bật/mới nhất).
    - [ ] Trang danh sách sản phẩm (Lọc theo Danh mục, Giá).
    - [ ] Trang chi tiết sản phẩm (Xem biến thể, ảnh).
- **Tuần 7: Tìm kiếm & Bộ lọc Nâng cao**
    - [ ] Triển khai chức năng Tìm kiếm.
    - [ ] Bộ lọc bên (Size, Màu sắc, Khoảng giá).
- **Tuần 8: Hồ sơ Người dùng**
    - [ ] Cập nhật Hồ sơ/Mật khẩu.
    - [ ] Quản lý Địa chỉ.

### Giai đoạn 3: Core E-commerce - Giỏ hàng & Đơn hàng (Tháng 3)
**Trọng tâm**: Giỏ hàng, Thanh toán, Xử lý đơn hàng.

- **Tuần 9: Giỏ hàng (Shopping Cart)**
    - [ ] Thêm vào giỏ (Session/Database).
    - [ ] Cập nhật số lượng, Xóa sản phẩm.
    - [ ] Xử lý Biến thể trong giỏ hàng.
- **Tuần 10: Quy trình Thanh toán (Checkout)**
    - [ ] Trang Thanh toán (Chọn địa chỉ).
    - [ ] Phương thức thanh toán (COD, Chuyển khoản giả lập).
    - [ ] Logic tạo Đơn hàng.
- **Tuần 11: Quản lý Đơn hàng (Admin)**
    - [ ] Danh sách đơn hàng (Lọc theo trạng thái).
    - [ ] Cập nhật trạng thái đơn hàng (Chờ xử lý -> Giao hàng -> Hoàn thành).
    - [ ] In Hóa đơn (Tùy chọn).
- **Tuần 12: Lịch sử Đơn hàng của Người dùng**
    - [ ] Danh sách đơn hàng của tôi.
    - [ ] Xem theo dõi đơn hàng.

### Giai đoạn 4: Hoàn thiện, Báo cáo & Kiểm thử (Tháng 4)
**Trọng tâm**: Báo cáo, Hoàn thiện UI, Sửa lỗi, Tài liệu.

- **Tuần 13: Báo cáo & Thống kê**
    - [ ] Biểu đồ Doanh thu (Ngày/Tháng).
    - [ ] Sản phẩm bán chạy nhất.
- **Tuần 14: Kiểm thử & Tối ưu hóa**
    - [ ] Kiểm thử thủ công (Các vai trò người dùng, các trường hợp ngoại lệ).
    - [ ] Sửa lỗi (Bug Fixing).
    - [ ] Tối ưu hóa hiệu năng (Cache ảnh, Truy vấn).
- **Tuần 15: Tài liệu**
    - [ ] Viết Hướng dẫn sử dụng.
    - [ ] Hoàn thiện Báo cáo Tốt nghiệp.
- **Tuần 16: Triển khai & Chuẩn bị Bảo vệ**
    - [ ] Triển khai lên Hosting Demo (tùy chọn).
    - [ ] Chuẩn bị Slide thuyết trình.

---

## 3. Tổng quan Cấu trúc Dữ liệu (Dự thảo)

- **users**: `id`, `name`, `email`, `password`, `role` (admin/user), `phone`, `address`.
- **categories**: `id`, `name`, `slug`, `parent_id`.
- **products**: `id`, `name`, `slug`, `price`, `description`, `category_id`, `image`.
- **product_variants**: `id`, `product_id`, `size`, `color`, `stock_quantity`.
- **orders**: `id`, `user_id`, `total_price`, `status` (pending, processing, shipping, completed, cancelled), `payment_method`, `shipping_address`, `note`.
- **order_items**: `id`, `order_id`, `product_id`, `variant_id`, `quantity`, `price`.
- **reviews**: `id`, `user_id`, `product_id`, `rating`, `comment`.

---

## 4. Các bước tiếp theo

1.  **Chốt Công nghệ**: Bạn muốn dùng **Laravel Blade** (Khuyên dùng vì tốc độ) hay **Laravel + React** (Phức tạp hơn, hiện đại hơn)?
2.  **Duyệt Sơ đồ CSDL**: Chúng ta có nên tiến hành viết kế hoạch migration chi tiết không?
3.  **Bắt đầu Code**: Khởi tạo dự án Laravel.
