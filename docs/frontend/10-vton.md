# 10. Thử Đồ Ảo AI (Virtual Try-On - VTON)

## Mô tả
Tính năng thử đồ ảo sử dụng AI cho phép khách hàng tải ảnh của mình lên và xem sản phẩm quần áo được ghép vào ảnh.  
Tích hợp với **Replicate AI — Model Kolors Virtual Try-On** (`cuiapp/kolors-virtual-try-on`).

---

## Chức Năng

### 10.1 Thử Đồ Ảo
- **Route:** `POST /api/vton/try-on`
- **Controller:** `Frontend\\VtonController@tryOn`
- **Đầu vào:**
  | Field | Yêu cầu | Mô tả |
  |-------|---------|-------|
  | `user_image` | ✅ | Ảnh người dùng (upload, tối đa 10MB) |
  | `product_id` | ✅ | ID sản phẩm cần thử |

- **Đầu ra:** JSON `{ success, image_url, message }`

- **Luồng xử lý chính (API Mode):**
  1. Validate input (định dạng ảnh, product phải tồn tại).
  2. Load ảnh sản phẩm từ storage → chuyển sang base64 Data URI.
  3. Chuyển ảnh người dùng sang base64 Data URI.
  4. Gọi `POST https://api.replicate.com/v1/models/cuiapp/kolors-virtual-try-on/predictions` với `human_image` + `garment_image`.
  5. Poll endpoint kết quả (tối đa 75 lần × 2s = 150s) cho đến khi `status = succeeded`.
  6. Trả về URL ảnh kết quả.

- **Chế Độ Demo (Không cần API token thật):**
  - Khi `REPLICATE_API_TOKEN` trống hoặc có giá trị `demo` / `nhap_api*`
  - Hệ thống mô phỏng 5 giây xử lý → trả về ảnh người dùng như kết quả (báo cáo đồ án)
  - Không tốn phí API

---

## Cấu Hình
- **Biến môi trường:** `REPLICATE_API_TOKEN` (trong `.env`)
- **Thời gian xử lý:** 10–60 giây (tuỳ cold boot của model)
- **Timeout:** 150 giây (75 polls × 2 giây/poll)

## Lưu Ý
- Ảnh người dùng **không được lưu lâu dài** vì lý do quyền riêng tư (chỉ xử lý trong request lifecycle).
- Sản phẩm phải có ảnh đại diện (`product.image`) mới thử đồ được.
- Xử lý lỗi đầy đủ: `failed`, `timeout`, lỗi kết nối đều trả về message thân thiện.
