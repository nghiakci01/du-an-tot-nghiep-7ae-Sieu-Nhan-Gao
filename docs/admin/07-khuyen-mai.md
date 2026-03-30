# Admin 07. Khuyến Mãi & Lịch Sử Thanh Toán

## Mô tả
Module quản lý mã giảm giá (coupon) và lịch sử giao dịch thanh toán toàn hệ thống.

---

## Chức Năng

### A7.1 Quản Lý Mã Giảm Giá (Coupons)
- **Route:** `GET /admin/coupons` (resource)
- **Controller:** `Admin\CouponController`
- **Thông tin coupon:**
  | Trường | Mô tả |
  |--------|-------|
  | `code` | Mã nhập (VD: SALE50) |
  | `type` | `percentage` (%) hoặc `fixed` (VNĐ cố định) |
  | `value` | Giá trị giảm |
  | `min_order_amount` | Đơn tối thiểu để áp dụng |
  | `max_discount_amount` | Giới hạn giảm tối đa (dùng với %) |
  | `usage_limit` | Tổng lượt dùng tối đa |
  | `start_date` / `end_date` | Thời gian hiệu lực |
  | `is_active` | Bật/tắt |
  | `user_id` | Chỉ dành cho 1 khách hàng cụ thể (tuỳ chọn) |

- **Trạng thái hiển thị:**
  - 🟢 Hoạt động
  - 🔴 Hết hạn
  - 🟡 Không hoạt động
  - ⚫ Hết lượt dùng
  - 🔵 Chưa bắt đầu

- **Tính năng UI:** Nút copy-to-clipboard để sao chép mã nhanh (hoạt động trên HTTP và HTTPS).

---

### A7.2 Lịch Sử Thanh Toán (Payment History)
- **Route:** `GET /admin/payment-history`
- **Controller:** `Admin\PaymentHistoryController@index`
- **Mô tả:** Xem toàn bộ giao dịch thanh toán (VNPAY, COD, chuyển khoản).

---

## Phân Quyền
| Hành động | Staff | Admin |
|-----------|-------|-------|
| Quản lý Coupon | ❌ | ✅ |
| Xem Payment History | ❌ | ✅ |

## Models Liên Quan
- `Coupon` — Bảng `coupons`
