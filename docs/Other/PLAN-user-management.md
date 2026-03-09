# PLAN: Quản lý Người dùng (User Management)

Dựa trên tài liệu [read.md](file:///c:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/read.md), hệ thống quản lý người dùng sẽ được triển khai như sau:

## 1. Actor & Quyền hạn (Role-Based Access Control)

| Actor | Vai trò trong hệ thống | Quyền hạn chính (Dựa trên read.md) |
|-------|-----------------------|------------------------------------|
| **Admin** | Quản trị viên (`admin`) | Toàn quyền (Quản lý hệ thống, User, Sản phẩm, Danh mục, Thống kê). |
| **Nhân viên** | Nhân viên vận hành (`staff`) | Quản lý kho, Xử lý đơn hàng (Cập nhật trạng thái đơn). |
| **Khách hàng** | Người dùng đã đăng ký (`user`) | Quản lý thông tin cá nhân, Đặt hàng, Theo dõi đơn, Đánh giá sản phẩm. |
| **Guest** | Khách vãng lai | Xem sản phẩm, Tìm kiếm. (Chưa có tài khoản). |

> [!NOTE]
> Vai trò **Shipper** đã được loại bỏ khỏi hệ thống tài khoản theo yêu cầu.

## 2. Thay đổi Cấu trúc Dữ liệu

Cập nhật migration `users` để hỗ trợ vai trò `staff`:
- `role`: `enum('admin', 'staff', 'user')` (Mặc định: `user`).

## 3. Chức năng Quản lý cho Admin (FR-18)

### Giao diện Danh sách Người dùng
- Hiển thị bảng: ID, Tên, Email, Số điện thoại, Vai trò, Ngày tạo.
- Bộ lọc theo Vai trò (Admin/Staff/User).

### CRUD Hành động
- **Thêm mới**: Cho phép Admin tạo tài khoản cho Nhân viên (Staff).
- **Chỉnh sửa**: Cập nhật thông tin cá nhân và thay đổi Vai trò.
- **Xóa/Khóa**: 
    - Chặn Admin tự xóa chính mình.
    - Cảnh báo nếu người dùng có lịch sử đơn hàng.

## 4. Kế hoạch triển khai

### Giai đoạn 1: Database & Model
- [ ] Tạo migration cập nhật cột `role` trong bảng `users`.
- [ ] Cập nhật Model `User` (hằng số Role, checks: `isAdmin()`, `isStaff()`).

### Giai đoạn 2: Backend (Admin)
- [ ] Tạo `UserController` với đầy đủ các phương thức CRUD.
- [ ] Xây dựng Request Validation (kiểm tra email unique, định dạng phone).

### Giai đoạn 3: UI (Blade)
- [ ] Tích hợp Menu "Quản lý Người dùng" vào Sidebar.
- [ ] Hoàn thiện các trang: Index, Create, Edit.

### Giai đoạn 4: Kiểm thử
- [ ] Kiểm tra phân quyền: Nhân viên không được vào trang quản lý User.
- [ ] Kiểm tra tính chính xác của các thông báo tiếng Việt.
