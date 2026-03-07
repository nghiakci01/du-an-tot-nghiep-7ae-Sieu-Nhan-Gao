# 📚 Tài Liệu Đặc Tả Dự Án Website bán quần áo nam Elite

> **Dự án:** Elite — Hệ thống thương mại điện tử thời trang nam  
> **Công nghệ:** Laravel 10+, MySQL, Bootstrap, Blade Templates, Gemini AI  
> **Cập nhật:** 06/03/2026

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
│   ├── 10-vton.md
│   └── 11-lien-he.md
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
    └── 11-he-thong.md
```

---

## 🌐 Frontend — Chức Năng Khách Hàng

| File | Mô tả |
|------|-------|
| [01-authentication.md](frontend/01-authentication.md) | Đăng ký, đăng nhập, OAuth (Google/Facebook), quên mật khẩu |
| [02-trang-chu.md](frontend/02-trang-chu.md) | Trang chủ, tin tức, blog |
| [03-san-pham.md](frontend/03-san-pham.md) | Danh sách sản phẩm, chi tiết, tìm kiếm, đánh giá |
| [04-gio-hang.md](frontend/04-gio-hang.md) | Giỏ hàng, áp mã coupon |
| [05-thanh-toan.md](frontend/05-thanh-toan.md) | Checkout, VNPAY, chuyển khoản ngân hàng |
| [06-tai-khoan.md](frontend/06-tai-khoan.md) | Tài khoản cá nhân, lịch sử đơn hàng |
| [07-wishlist.md](frontend/07-wishlist.md) | Danh sách yêu thích |
| [08-chatbot-ai.md](frontend/08-chatbot-ai.md) | Chatbot AI Gemini tư vấn sản phẩm (RAG) |
| [09-theo-doi-don-hang.md](frontend/09-theo-doi-don-hang.md) | Tra cứu đơn hàng cho khách vãng lai |
| [10-vton.md](frontend/10-vton.md) | Thử đồ ảo bằng AI (Virtual Try-On) |
| [11-lien-he.md](frontend/11-lien-he.md) | Form liên hệ |

---

## 🔧 Admin Panel — Chức Năng Quản Trị

| File | Mô tả |
|------|-------|
| [01-dashboard.md](admin/01-dashboard.md) | Tổng quan, biểu đồ doanh thu, lock screen |
| [02-san-pham.md](admin/02-san-pham.md) | CRUD sản phẩm, biến thể, gallery ảnh |
| [03-danh-muc.md](admin/03-danh-muc.md) | Danh mục, màu sắc, kích thước |
| [04-don-hang.md](admin/04-don-hang.md) | Quản lý đơn hàng, cập nhật trạng thái, in hóa đơn |
| [05-nguoi-dung.md](admin/05-nguoi-dung.md) | Quản lý tài khoản và phân quyền |
| [06-kho-hang.md](admin/06-kho-hang.md) | Nhà cung cấp, kho hàng, phiếu nhập/xuất |
| [07-khuyen-mai.md](admin/07-khuyen-mai.md) | Coupon giảm giá, điểm tích lũy |
| [08-blog.md](admin/08-blog.md) | Bài viết và danh mục bài viết |
| [09-chatbot.md](admin/09-chatbot.md) | Quản lý chat, cài đặt AI Gemini |
| [10-bao-cao.md](admin/10-bao-cao.md) | Xuất báo cáo Excel/PDF |
| [11-he-thong.md](admin/11-he-thong.md) | Cài đặt hệ thống, banner, ngân hàng, audit log |

---

## 🔐 Ma Trận Phân Quyền

| Vai trò | Mô tả |
|---------|-------|
| **Guest** | Khách vãng lai — xem sản phẩm, mua hàng không cần đăng nhập |
| **User** | Khách hàng đã đăng ký — đầy đủ tính năng mua hàng, wishlist |
| **Staff** | Nhân viên — quản lý sản phẩm, đơn hàng, blog, chat |
| **Admin** | Quản trị viên — toàn quyền hệ thống |

## 🏗️ Kiến Trúc Tổng Quan

```
Elite E-Commerce (Laravel 10+)
├── Frontend (Khách hàng)     → routes/web.php + Controllers/Frontend/
├── Admin Panel               → /admin prefix + Controllers/Admin/
├── API Layer                 → Controllers/Api/ (Chatbot)
├── Services                  → Services/ChatService (Gemini AI)
└── Database                  → 33 Models, MySQL
```
