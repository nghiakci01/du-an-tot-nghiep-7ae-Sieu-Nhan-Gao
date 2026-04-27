# 🎤 KỊCH BẢN DEMO THUYẾT TRÌNH ĐỒ ÁN - SIÊU NHÂN GAO

## 📂 Tổng quan về kịch bản
- **Thời lượng ước tính:** 15 - 20 phút.
- **Người thực hiện:** Cần chuẩn bị 2 trình duyệt (1 cho Khách hàng, 1 cho Admin).
- **Trạng thái chuẩn bị:** Dữ liệu mẫu (Seeding) đầy đủ, đã đăng nhập sẵn tài khoản Admin.

---

## 🏗️ GIAI ĐOẠN 1: GIAO DIỆN & TRẢI NGHIỆM KHÁCH HÀNG (3-5 phút)
*Mục tiêu: Show giao diện đẹp, tốc độ load trang và tính năng tìm kiếm.*

1. **Trang chủ (Landing Page):**
   - Trình bày Banner (`BannerController`), danh mục sản phẩm nổi bật.
   - Giới thiệu độ tương thích thiết bị (Responsive).
2. **Tìm kiếm & Lọc (Discovery):**
   - Sử dụng thanh tìm kiếm để tìm một sản phẩm cụ thể.
   - Sử dụng bộ lọc (Màu sắc, Kích thước, Giá) để thu hẹp kết quả.
   - **Thao tác:** Thêm nhanh một sản phẩm vào Danh sách yêu thích (Wishlist).

---

## 🛒 GIAI ĐOẠN 2: LUỒNG MUA HÀNG (7-10 phút)
*Mục tiêu: Show logic giỏ hàng, khuyến mãi và thanh toán.*

1. **Chi tiết sản phẩm:**
   - Chọn biến thể (Size/Color) -> Giá thay đổi tương ứng.
   - Xem đánh giá của người dùng cũ.
2. **Giỏ hàng (Cart):**
   - Điều chỉnh số lượng, hệ thống cập nhật giá AJAX (không load lại trang).
3. **Áp dụng Mã giảm giá (Discount):**
   - Chọn mã giảm giá có sẵn hoặc nhập mã (`VoucherClaimController`).
   - Giải thích logic: Mã chỉ áp dụng cho đơn hàng trên mức giá tối thiểu.
4. **Thanh toán (Checkout):**
   - Chọn địa chỉ giao hàng (Tích hợp bản đồ/danh mục tỉnh thành).
   - **Điểm nhấn đặc biệt:** Chọn thanh toán bằng **Ví điện tử (Wallet)** của hệ thống.
   - Xác nhận đơn hàng thành công.

---

## 🤖 GIAI ĐOẠN 3: TƯƠNG TÁC THÔNG MINH (3-5 phút)
*Mục tiêu: Show sự khác biệt của dự án so với các web bán hàng thông thường.*

1. **AI Chatbot:**
   - Mở chatbot tư vấn. Nhập các câu hỏi thường gặp (Giá, chính sách bảo hành).
   - Show tính năng "Câu hỏi gợi ý" để khách hàng click nhanh.
2. **Theo dõi đơn hàng (Tracking):**
   - Sau khi đặt hàng, vào ngay mục "Đơn hàng của tôi".
   - Show trạng thái đơn hàng (Đang chờ duyệt).

---

## ⚙️ GIAI ĐOẠN 4: HỆ THỐNG QUẢN TRỊ (ADMIN) (5-7 phút)
*Mục tiêu: Show khả năng xử lý nghiệp vụ của Backend.*

1. **Bảng điều khiển (Dashboard):**
   - Show biểu đồ doanh thu, số lượng đơn hàng, người dùng mới.
   - Giải thích các chỉ số kinh doanh.
2. **Xử lý Đơn hàng:**
   - Tìm đơn vừa đặt ở Giai đoạn 2. Duyệt đơn.
   - Show tính năng **In hóa đơn (PDF)** nếu có.
3. **Cấu hình Chatbot:**
   - Show cách Admin thêm các câu hỏi và câu trả lời tự động cho Robot.
4. **Quản lý Ví (Wallet Management):**
   - Show lịch sử nạp/rút và biến động số dư của người dùng.

---

## 🔄 GIAI ĐOẠN 5: NGHIỆP VỤ NÂNG CAO & KẾT THÚC (3 phút)
*Mục tiêu: Khẳng định tính hoàn thiện của hệ thống.*

1. **Yêu cầu trả hàng (Return Request):**
   - Quay lại giao diện khách hàng, thực hiện yêu cầu hoàn trả đơn hàng cũ.
   - Giải thích quy trình: Khách gửi yêu cầu -> Admin phê duyệt -> Hoàn tiền vào Ví.
2. **Tổng kết:**
   - Tóm tắt các công nghệ sử dụng (Laravel, MySQL, AJAX, Chatbot SDK...).
   - Mời Hội đồng hỏi đáp.

---

### ⚠️ LƯU Ý QUAN TRỌNG:
- **Backup:** Luôn có bản quay màn hình demo (Video) đề phòng sự cố mạng trong lúc thuyết trình.
- **Dữ liệu mượt:** Tránh các sản phẩm tên là "asdasd" hoặc giá "0đ". Hãy dùng hình ảnh và tên thực tế.
