# 📚 Tài Liệu Đặc Tả Dự Án Website bán quần áo nam Elite

> **Dự án:** Elite — Hệ thống thương mại điện tử thời trang nam  
> **Công nghệ:** Laravel 10+, MySQL, Bootstrap, Blade Templates, Gemini AI  
> **Cập nhật:** 20/03/2026

---

## 📁 Cấu Trúc Thư Mục

```
docs/
├── README.md               ← Bạn đang ở đây
├── frontend/               ← Đặc tả chức năng phía khách hàng
│   ├── 01-authentication.md
│   ├── 02-trang-chu.md
│   ├── 03-san-pham.md
│   ├── 04-gio-hang.md
│   ├── 05-thanh-toan.md
│   ├── 06-tai-khoan.md
│   ├── 07-wishlist.md
│   ├── 08-chatbot-ai.md
│   ├── 09-theo-doi-don-hang.md

│   ├── 11-lien-he.md
│   └── 12-kiem-tra-ton-kho-thanh-toan.md  ← MỚI
└── admin/                  ← Đặc tả chức năng Admin Panel
    ├── 01-dashboard.md
    ├── 02-san-pham.md
    ├── 03-danh-muc.md
    ├── 04-don-hang.md
    ├── 05-nguoi-dung.md
    ├── 06-kho-hang.md
    ├── 07-khuyen-mai.md
    ├── 08-blog.md
    ├── 09-chatbot.md
    ├── 10-bao-cao.md
    ├── 11-he-thong.md
    └── 12-cart-abandonment.md  ← MỚI
```

---

## 🌐 Frontend — Chức Năng Khách Hàng

| File | Mô tả |
|------|-------|
| [01-authentication.md](frontend/01-authentication.md) | Đăng ký, đăng nhập, OAuth (Google/Facebook), quên mật khẩu |
| [02-trang-chu.md](frontend/02-trang-chu.md) | Trang chủ, tin tức, blog, chuyển đổi ngôn ngữ |
| [03-san-pham.md](frontend/03-san-pham.md) | Danh sách sản phẩm, chi tiết, tìm kiếm, đánh giá, AJAX gợi ý |
| [04-gio-hang.md](frontend/04-gio-hang.md) | Giỏ hàng, áp mã coupon, theo dõi abandonment |
| [05-thanh-toan.md](frontend/05-thanh-toan.md) | Checkout, VNPAY, chuyển khoản QR ngân hàng |
| [06-tai-khoan.md](frontend/06-tai-khoan.md) | Tài khoản cá nhân, lịch sử đơn hàng, điểm thưởng |
| [07-wishlist.md](frontend/07-wishlist.md) | Danh sách yêu thích |
| [08-chatbot-ai.md](frontend/08-chatbot-ai.md) | Chatbot AI Gemini tư vấn sản phẩm (RAG) |
| [09-theo-doi-don-hang.md](frontend/09-theo-doi-don-hang.md) | Tra cứu đơn hàng cho khách vãng lai |

| [11-lien-he.md](frontend/11-lien-he.md) | Form liên hệ với email reply |
| [12-kiem-tra-ton-kho-thanh-toan.md](frontend/12-kiem-tra-ton-kho-thanh-toan.md) | Đặc tả kiểm tra tồn kho thời gian thực khi thanh toán — **MỚI** |

---

## 🔧 Admin Panel — Chức Năng Quản Trị

| File | Mô tả |
|------|-------|
| [01-dashboard.md](admin/01-dashboard.md) | Tổng quan, biểu đồ doanh thu, conversion funnel, lock screen |
| [02-san-pham.md](admin/02-san-pham.md) | CRUD sản phẩm, biến thể, gallery, giá sale theo ngày |
| [03-danh-muc.md](admin/03-danh-muc.md) | Danh mục, màu sắc, kích thước, thương hiệu, tags |
| [04-don-hang.md](admin/04-don-hang.md) | Quản lý đơn hàng, xác nhận chuyển khoản, VNPAY refund, in hóa đơn |
| [05-nguoi-dung.md](admin/05-nguoi-dung.md) | Quản lý tài khoản và phân quyền |
| [06-kho-hang.md](admin/06-kho-hang.md) | Quản lý danh sách nhà cung cấp |
| [07-khuyen-mai.md](admin/07-khuyen-mai.md) | Quản lý mã giảm giá (Coupon) và lịch sử thanh toán |
| [08-blog.md](admin/08-blog.md) | Bài viết và danh mục bài viết |
| [09-chatbot.md](admin/09-chatbot.md) | Quản lý chat, cài đặt AI Gemini, câu hỏi gợi ý |
| [10-bao-cao.md](admin/10-bao-cao.md) | Xuất báo cáo Excel/PDF, lịch sử thanh toán |
| [11-he-thong.md](admin/11-he-thong.md) | Cài đặt hệ thống, banner, cài đặt ngân hàng QR, audit log, thông báo |
| [12-cart-abandonment.md](admin/12-cart-abandonment.md) | Theo dõi giỏ hàng bỏ rơi & tỷ lệ chuyển đổi — **MỚI** |

---

## 🔐 Ma Trận Phân Quyền

| Vai trò | Mô tả |
|---------|-------|
| **Guest** | Khách vãng lai — xem sản phẩm, mua hàng, tra cứu đơn hàng |
| **User** | Khách hàng đã đăng ký — đầy đủ tính năng mua hàng, wishlist, điểm thưởng |
| **Staff** | Nhân viên — quản lý sản phẩm, đơn hàng, blog, chat |
| **Admin** | Quản trị viên — toàn quyền hệ thống |

---

## 🏗️ Kiến Trúc Tổng Quan

```
Elite E-Commerce (Laravel 10+)
├── Frontend (Khách hàng)     → routes/web.php + Controllers/Frontend/ (11 controllers)
├── Admin Panel               → /admin prefix + Controllers/Admin/ (26 controllers)
├── API Layer                 → Controllers/Api/ (3 controllers)
├── Services/                 → ChatService, VnpayService, OrderService, ReportService,
│                                ConversionTrackingService, ReturnService, ShippingService
└── Database                  → 31 Models, MySQL (82 migrations)
```

## 📦 Danh Sách Models (31 Models)

| Nhóm | Models |
2. | **Người dùng** | `User`, `SocialAccount` |
3. | **Sản phẩm** | `Product`, `ProductVariant`, `ProductImage`, `Category`, `Brand`, `Tag`, `Size`, `Color` |
4. | **Đơn hàng** | `Order`, `OrderItem`, `OrderHistory`, `OrderReturnRequest` *(mới)* |
5. | **Giỏ hàng** | `CartAbandonment` |
6. | **Khuyến mãi** | `Coupon` |
7. | **Kho hàng** | `Supplier` |
8. | **Thanh toán** | `BankSetting`, `UserBankAccount` *(mới)* |
9. | **Chatbot** | `ChatSession`, `ChatMessage`, `ChatbotSetting`, `ChatbotSuggestedQuestion` |
10. | **Blog** | `Post`, `PostCategory` |
11. | **Hệ thống** | `Setting`, `Banner`, `AuditLog`, `ContactMessage`, `Review`, `Wishlist` |
