# 10. Thử Đồ Ảo AI (Virtual Try-On - VTON)

## Mô tả
Tính năng thử đồ ảo sử dụng AI cho phép khách hàng tải ảnh của mình lên và xem sản phẩm quần áo được ghép vào ảnh.

---

## Chức Năng

### 10.1 Thử Đồ Ảo
- **Route:** `POST /api/vton/try-on`
- **Controller:** `Frontend\VtonController@tryOn`
- **Đầu vào:**
  - `person_image` — Ảnh người dùng (upload file)
  - `cloth_image` — Ảnh sản phẩm quần áo (URL hoặc upload)
  - `product_id` — ID sản phẩm (tùy chọn)
- **Đầu ra:** URL ảnh đã ghép thử đồ.
- **Nghiệp vụ:**
  1. Nhận và lưu tạm ảnh người dùng.
  2. Lấy ảnh sản phẩm từ database.
  3. Gọi API VTON bên ngoài (third-party AI service).
  4. Trả về ảnh kết quả dạng base64 hoặc URL.

---

## Lưu Ý
- Tính năng xử lý ảnh phụ thuộc vào API bên thứ ba.
- Thời gian xử lý có thể từ 5–30 giây.
- Ảnh người dùng **không được lưu lâu dài** vì lý do quyền riêng tư.
