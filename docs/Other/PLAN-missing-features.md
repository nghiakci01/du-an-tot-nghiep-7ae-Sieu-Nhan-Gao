# PLAN-missing-features.md - Elite Project Expansion

Bản kế hoạch này chi tiết hóa các tính năng còn thiếu để hoàn thiện dự án tốt nghiệp **Elite**, dựa trên phân tích codebase hiện tại và phản hồi từ người dùng.

---

## 1. PHÂN TÍCH YÊU CẦU

Dự án hiện có: Quản lý kho, VNPay, Sản phẩm/Đơn hàng cơ bản.
Dự án cần bổ sung:
- **Tăng doanh số**: Voucher, Tích điểm.
- **Tăng trải nghiệm**: ZaloPay, Theo dõi đơn hàng khách lạ, Đánh giá, Blog.
- **Quản trị**: Báo cáo trực quan (Biểu đồ) và Xuất dữ liệu.

---

## 2. CHI TIẾT CÁC TÍNH NĂNG

### A. Hệ thống Khuyến mãi (Coupons/Vouchers)
- **Cơ chế**: Tạo mã giảm giá theo số tiền cố định hoặc phần trăm.
- **Giới hạn**: Số lần sử dụng, giá trị đơn hàng tối thiểu, ngày hết hạn.
- **Tích hợp**: Áp dụng mã tại trang Checkout.

### B. Thanh toán & Đơn hàng (ZaloPay + Tracking)
- **ZaloPay**: Tích hợp SDK/API ZaloPay Merchant tương đương VNPay hiện tại.
- **Guest Tracking**: Trang tra cứu đơn hàng bằng **Số điện thoại + Mã đơn hàng (hoặc Email)** dành cho khách vãng lai.

### C. Tương tác & Nội dung
- **Product Reviews**: Khách hàng đã mua có thể để lại bình luận và đánh giá sao (1-5★).
- **News/Blog**: Module bài viết (Danh mục tin, Bài viết tin, Bình luận bài viết).
- **Member Points**: Tích điểm dựa trên giá trị đơn hàng. Điểm dùng để quy đổi thành mã giảm giá hoặc trừ trực tiếp vào đơn hàng.

### D. Quản trị nâng cao (Reports & Charts)
- **Dashboard Widget**: Hiển thị Biểu đồ doanh thu (ngày/tháng), Top sản phẩm bán chạy, Top khách hàng tiềm năng.
- **Data Export**: Cho phép xuất danh sách Đơn hàng, Doanh thu, Khách hàng ra file **Excel (xlsx)** hoặc **PDF**.

---

## 3. LỘ TRÌNH THỰC HIỆN (4 Giai đoạn)

### GIAI ĐOẠN 1: Cơ sở hạ tầng & Khuyến mãi (3-5 ngày)
1. **Model & Migration**: `Coupon`, `Review`, `Post`, `LoyaltyPoint`.
2. **Admin CRUD**: Quản lý Mã giảm giá và Tin tức.
3. **Checkout Integration**: Logic áp dụng Coupon vào đơn hàng.

### GIAI ĐOẠN 2: Thanh toán & Theo dõi (3-4 ngày)
1. **ZaloPay Integration**: Xây dựng Service & Callback handler.
2. **Order Tracking Page**: Tạo giao diện tra cứu công khai (Frontend).
3. **Email Notification**: Gửi thông tin đơn hàng sau khi thanh toán.

### GIAI ĐOẠN 4: Quản trị & Báo cáo (3 ngày)
1. **Statistics Engine**: SQL Queries tính toán dữ liệu báo cáo.
2. **Charts**: Tích hợp ApexCharts vào Admin Dashboard.
3. **Exporting**: Cấu hình Laravel-Excel cho báo cáo.

---

## 4. CÔNG NGHỆ ĐỀ XUẤT
- **Charts**: [ApexCharts](https://apexcharts.com/) (Hiện đại, dễ tùy biến).
- **Exports**: `maatwebsite/excel` (Tiêu chuẩn của Laravel).
- **PDF**: `barryvdh/laravel-dompdf`.

---

## 5. CHECKLIST XÁC MINH
- [ ] Voucher được áp dụng đúng và tính toán giảm giá chính xác.
- [ ] Thanh toán ZaloPay có callback cập nhật trạng thái đơn hàng.
- [ ] Tra cứu đơn hàng trả về đúng thông tin cho khách vãng lai.
- [ ] Biểu đồ Admin hiển thị đúng số liệu thực tế từ DB.

---

[OK] Kế hoạch đã hoàn tất. Bước tiếp theo hãy chạy lệnh `/create` hoặc phản hồi ý kiến của bạn để tôi bắt đầu triển khai.
