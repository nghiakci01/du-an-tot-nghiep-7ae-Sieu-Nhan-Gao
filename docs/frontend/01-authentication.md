# 01. Xác Thực & Quản Lý Phiên (Authentication)

## Mô tả
Module xác thực người dùng, cung cấp các chức năng đăng ký, đăng nhập, đăng xuất, quên/đặt lại mật khẩu và đăng nhập qua mạng xã hội (OAuth).

---

## Chức Năng

### 1.1 Đăng Ký Tài Khoản
- **Route:** `POST /register`
- **Controller:** `Auth\RegisterController`
- **Mô tả:** Người dùng tạo tài khoản mới bằng email và mật khẩu.
- **Đầu vào:**
  - `name` — Họ tên (bắt buộc, tối đa 255 ký tự)
  - `email` — Email hợp lệ, chưa tồn tại trong hệ thống
  - `password` — Mật khẩu (tối thiểu 8 ký tự, xác nhận lần 2)
- **Đầu ra:** Đăng nhập tự động và chuyển hướng về trang chủ.
- **Nghiệp vụ:**
  - Email phải là duy nhất.
  - Mật khẩu được băm bằng bcrypt trước khi lưu.
  - Gửi email xác thực (nếu được cấu hình).

---

### 1.2 Đăng Nhập
- **Route:** `POST /login`
- **Controller:** `Auth\LoginController`
- **Mô tả:** Xác thực người dùng bằng email/mật khẩu.
- **Đầu vào:**
  - `email` — Email đã đăng ký
  - `password` — Mật khẩu
  - `remember` — Ghi nhớ đăng nhập (tùy chọn)
- **Đầu ra:**
  - Thành công: Chuyển hướng về trang trước hoặc trang chủ.
  - Thất bại: Thông báo lỗi "Thông tin đăng nhập không chính xác."
- **Nghiệp vụ:**
  - Giới hạn số lần thử đăng nhập (throttle).
  - Hỗ trợ "Remember Me" bằng cookie.

---

### 1.3 Đăng Xuất
- **Route:** `POST /logout`
- **Mô tả:** Hủy phiên đăng nhập hiện tại.
- **Nghiệp vụ:** Hủy session và xóa cookie "remember me".

---

### 1.4 Quên Mật Khẩu
- **Route:** `GET/POST /password/email`
- **Mô tả:** Gửi liên kết đặt lại mật khẩu qua email.
- **Đầu vào:** `email` — Email đã đăng ký.
- **Đầu ra:** Thông báo đã gửi email (không tiết lộ email có tồn tại không).

---

### 1.5 Đặt Lại Mật Khẩu
- **Route:** `GET/POST /password/reset/{token}`
- **Mô tả:** Cho phép đặt lại mật khẩu bằng token trong email.
- **Đầu vào:**
  - `token` — Token hợp lệ (hết hạn sau 60 phút)
  - `email` — Email của tài khoản
  - `password` — Mật khẩu mới + xác nhận
- **Nghiệp vụ:** Token chỉ dùng được một lần.

---

### 1.6 Đăng Nhập Mạng Xã Hội (OAuth)
- **Route:** `GET /auth/{provider}` và `GET /auth/{provider}/callback`
- **Controller:** `Auth\SocialLoginController`
- **Mô tả:** Đăng nhập/đăng ký bằng tài khoản Google hoặc Facebook.
- **Providers được hỗ trợ:** Google, Facebook (tùy cấu hình `.env`)
- **Nghiệp vụ:**
  - Nếu email đã tồn tại → liên kết với tài khoản hiện có.
  - Nếu email chưa tồn tại → tự động tạo tài khoản mới.
  - Lưu provider & provider_id vào bảng `social_accounts`.

---

## Quyền Hạn
| Hành động | Guest | User | Staff | Admin |
|-----------|-------|------|-------|-------|
| Đăng ký | ✅ | ❌ | ❌ | ❌ |
| Đăng nhập | ✅ | ❌ | ❌ | ❌ |
| Đăng xuất | ❌ | ✅ | ✅ | ✅ |
| Quên mật khẩu | ✅ | ✅ | ✅ | ✅ |

---

## Models Liên Quan
- `User` — Bảng `users`
- `SocialAccount` — Bảng `social_accounts` (provider, provider_id, user_id)
