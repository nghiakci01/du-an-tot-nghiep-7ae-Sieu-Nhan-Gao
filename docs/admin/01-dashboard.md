# Admin 01. Dashboard & Tổng Quan

## Mô tả
Trang tổng quan hệ thống dành cho Admin và Staff, hiển thị số liệu kinh doanh theo thời gian thực.

---

## Chức Năng

### A1.1 Dashboard Chính
- **Route:** `GET /admin/dashboard`
- **Controller:** `Admin\DashboardController@index`
- **Dữ liệu hiển thị:**
  | Chỉ số | Mô tả |
  |--------|-------|
  | Tổng doanh thu | Tổng `final_total` của đơn hoàn thành trong kỳ |
  | Lợi nhuận | Doanh thu trừ giá vốn (Cost Price) |
  | Đơn hàng mới | Số đơn tạo trong kỳ được chọn |
  | Khách hàng mới | Số tài khoản đăng ký mới |
  | Sản phẩm sắp hết hàng | Biến thể có `stock <= threshold` (cảnh báo tồn kho) |
  | Tỷ lệ chuyển đổi | Conversion funnel (View -> Add to Cart -> Checkout -> Success) |

---

### A1.2 Biểu Đồ Doanh Thu (API)
- **Route:** `GET /admin/api/dashboard/revenue`
- **Controller:** `Admin\DashboardController@revenueApi`
- **Đầu vào:** `?period=7d|30d|12m`
- **Đầu ra:** JSON dữ liệu doanh thu theo ngày/tháng để vẽ biểu đồ (Chart.js).

---

### A1.3 Màn Hình Khóa (Lock Screen)
- **Route lock:** `GET /admin/lock`
- **Route unlock:** `POST /admin/unlock`
- **Controller:** `Admin\LockScreenController`
- **Mô tả:** Bảo vệ phiên làm việc bằng màn hình khóa khi rời khỏi máy.
- **Nghiệp vụ:** Yêu cầu nhập lại mật khẩu để mở khóa.

---

## Phân Quyền
| Hành động | Staff | Admin |
|-----------|-------|-------|
| Xem dashboard | ✅ | ✅ |
| Xem biểu đồ doanh thu | ✅ | ✅ |
| Khóa/Mở khóa màn hình | ✅ | ✅ |
