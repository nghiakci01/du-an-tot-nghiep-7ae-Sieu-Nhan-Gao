# Admin 12. Theo Dõi Giỏ Hàng Bỏ Rơi & Tỷ Lệ Chuyển Đổi *(MỚI)*

## Mô tả
Module phân tích hành vi mua hàng của khách: theo dõi các giỏ hàng bị bỏ dở, tỷ lệ quay trở lại (recovery rate), và số liệu conversion funnel. Hỗ trợ Admin đưa ra quyết định marketing chính xác hơn.

---

## Chức Năng

### A12.1 Theo Dõi Giỏ Hàng Bỏ Rơi (Cart Abandonment Tracking)
- **Model:** `CartAbandonment` — Bảng `cart_abandonments`
- **Service:** `ConversionTrackingService`
- **Dữ liệu ghi lại:**

| Trường | Mô tả |
|--------|-------|
| `user_id` | ID người dùng (null nếu là guest) |
| `session_id` | Session ID cho guest tracking |
| `cart_data` | JSON snapshot toàn bộ giỏ hàng |
| `cart_total` | Tổng giá trị giỏ hàng tại thời điểm bỏ |
| `item_count` | Số lượng sản phẩm trong giỏ |
| `status` | `abandoned` / `recovered` |
| `abandoned_at` | Thời điểm ghi nhận bỏ giỏ |
| `recovered_at` | Thời điểm khách quay lại hoàn tất |

- **Luồng hoạt động:**
  1. Khi user thêm vào giỏ nhưng không checkout → `trackAbandonment()` ghi nhận
  2. Khi user sau đó quay lại và hoàn tất đơn → `markRecovered()` cập nhật status

---

### A12.2 Conversion Funnel Statistics
- **Service Method:** `ConversionTrackingService::getFunnelStats($period)`
- **Hỗ trợ period:** `7d`, `30d`, `90d`
- **Số liệu trả về:**

| Chỉ số | Mô tả |
|--------|-------|
| `total_carts_tracked` | Tổng số giỏ hàng được theo dõi |
| `abandoned_carts` | Số giỏ hàng bị bỏ |
| `recovered_carts` | Số giỏ hàng đã phục hồi |
| `orders_placed` | Tổng đơn hàng đã đặt |
| `orders_completed` | Đơn hàng hoàn thành |
| `total_revenue` | Tổng doanh thu trong kỳ |
| `avg_order_value` | Giá trị đơn hàng trung bình |
| `abandoned_value` | Tổng giá trị giỏ hàng bị bỏ (doanh thu tiềm năng mất) |
| `cart_to_order_rate` | Tỷ lệ chuyển đổi từ giỏ → đơn hàng (%) |
| `daily_orders` | Xu hướng đơn hàng theo ngày |

---

## Phân Quyền
| Hành động | Staff | Admin |
|-----------|-------|-------|
| Xem Cart Abandonment | ❌ | ✅ |
| Xem Conversion Funnel | ❌ | ✅ |

## Models Liên Quan
- `CartAbandonment` — Bảng `cart_abandonments`
- `Order` — Dùng để tính toán conversion rate
