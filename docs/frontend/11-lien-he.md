# 11. Liên Hệ (Contact)

## Mô tả
Trang liên hệ dành cho khách hàng gửi thắc mắc, phản hồi hoặc yêu cầu hỗ trợ tới shop.

---

## Chức Năng

### 11.1 Trang Liên Hệ
- **Route:** `GET /contact`
- **Controller:** `Frontend\ContactController@index`
- **Mô tả:** Hiển thị thông tin liên hệ (địa chỉ, điện thoại, email) và form gửi tin nhắn.

---

### 11.2 Gửi Tin Nhắn
- **Route:** `POST /contact`
- **Controller:** `Frontend\ContactController@send`
- **Đầu vào:**
  - `name` — Họ tên người gửi
  - `email` — Email liên hệ
  - `subject` — Tiêu đề
  - `message` — Nội dung tin nhắn
- **Đầu ra:** Thông báo gửi thành công.
- **Nghiệp vụ:**
  - Lưu vào bảng `contact_messages`.
  - Gửi email thông báo cho admin (nếu cấu hình mail).
  - Gửi email xác nhận cho khách hàng.

---

## Admin Xử Lý Tin Nhắn Liên Hệ
- **Route xem:** `GET /admin/contact-messages`
- **Route phản hồi:** `POST /admin/contact-messages/{id}/reply`
- **Nghiệp vụ:** Admin có thể đọc và reply qua email trực tiếp từ panel.

---

## Models Liên Quan
- `ContactMessage` — Bảng `contact_messages` (name, email, subject, message, replied_at)
