# Admin 05. Quản Lý Người Dùng

## Mô tả
Module quản lý tài khoản người dùng trong hệ thống (Staff + Admin). Chỉ Admin mới được truy cập.

---

## Chức Năng

### A5.1 Danh Sách Người Dùng
- **Route:** `GET /admin/users`
- **Controller:** `Admin\UserController@index`
- **Hiển thị:** Danh sách đầy đủ tài khoản, vai trò, ngày tạo, lần đăng nhập cuối.

---

### A5.2 Tạo Tài Khoản Nhân Viên
- **Route:** `GET/POST /admin/users/create`
- **Đầu vào:**
  - `name`, `email`, `password`
  - `role` — `admin` hoặc `staff`
- **Nghiệp vụ:** Email phải là duy nhất trong hệ thống.

---

### A5.3 Sửa & Phân Quyền
- **Route:** `GET/PUT /admin/users/{id}/edit`
- **Mô tả:** Cập nhật thông tin và thay đổi vai trò người dùng.
- **Nghiệp vụ:** Admin không thể hạ quyền chính mình.

---

### A5.4 Xóa Tài Khoản
- **Route:** `DELETE /admin/users/{id}`
- **Nghiệp vụ:** Không thể xóa tài khoản đang đăng nhập.

---

## Phân Quyền
| Hành động | Staff | Admin |
|-----------|-------|-------|
| Truy cập module | ❌ | ✅ |
| CRUD Users | ❌ | ✅ |

## Models Liên Quan
- `User` — Bảng `users` (name, email, password, role, avatar, phone, address)
