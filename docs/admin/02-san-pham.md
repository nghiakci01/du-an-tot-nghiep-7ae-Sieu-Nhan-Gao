# Admin 02. Quản Lý Sản Phẩm

## Mô tả
Module quản lý toàn bộ catalog sản phẩm: CRUD sản phẩm, biến thể (màu + size), ảnh gallery và tồn kho.

---

## Chức Năng

### A2.1 Danh Sách Sản Phẩm
- **Route:** `GET /admin/products`
- **Hiển thị:** Bảng danh sách với cột: Ảnh, Tên, Danh mục, Giá, Trạng thái, Tồn kho tổng, Hành động.
- **Bộ lọc:** Theo danh mục, trạng thái hoạt động.
- **Tìm kiếm:** Theo tên sản phẩm.

---

### A2.2 Thêm Sản Phẩm Mới
- **Route:** `GET/POST /admin/products/create`
- **Controller:** `Admin\ProductController@create` / `store`
- **Thông tin sản phẩm:**
  | Trường | Bắt buộc | Mô tả |
  |--------|----------|-------|
  | name | ✅ | Tên sản phẩm |
  | slug | ✅ | URL thân thiện (tự tạo từ name) |
  | category_id | ✅ | Danh mục |
  | brand_id | ❌ | Thương hiệu |
  | price | ✅ | Giá gốc |
  | sale_price | ❌ | Giá khuyến mãi |
  | short_description | ❌ | Mô tả ngắn |
  | description | ❌ | Mô tả chi tiết (rich text) |
  | image | ✅ | Ảnh đại diện chính |
  | is_active | ✅ | Đang bán hay ẩn |
  | is_featured | ❌ | Hiện trên trang chủ |
  | tags | ❌ | Thẻ tag (many-to-many) |

---

### A2.3 Biến Thể Sản Phẩm (Variants)
- **Mô tả:** Mỗi sản phẩm có thể có nhiều biến thể theo tổ hợp Màu sắc + Kích thước.
- **Thông tin biến thể:**
  - `color_id` — Màu sắc
  - `size_id` — Kích thước
  - `stock` — Số lượng tồn kho
  - `price_override` — Ghi đè giá (nếu khác giá base)
  - `sku` — Mã SKU riêng
- **Nghiệp vụ:** Mỗi tổ hợp màu+size là duy nhất trong 1 sản phẩm.

---

### A2.4 Gallery Ảnh
- **Mô tả:** Upload nhiều ảnh phụ cho sản phẩm (thể hiện các góc nhìn khác nhau).
- **Route xóa:** `DELETE /admin/products/gallery/{image}`
- **Nghiệp vụ:**
  - Ảnh được lưu vào `storage/app/public/products/`.
  - Có thể kéo thả để sắp xếp thứ tự (`sort_order`).

---

### A2.5 Sửa Sản Phẩm
- **Route:** `GET/PUT /admin/products/{id}/edit`
- **Nghiệp vụ:** Cập nhật tất cả thông tin, biến thể và ảnh gallery.

---

### A2.6 Xóa Sản Phẩm
- **Route:** `DELETE /admin/products/{id}`
- **Nghiệp vụ:**
  - Kiểm tra sản phẩm có đơn hàng đang xử lý không trước khi xóa.
  - Xóa mềm (soft delete) hoặc xóa cứng tuỳ cấu hình.

---

## Phân Quyền
| Hành động | Staff | Admin |
|-----------|-------|-------|
| Xem danh sách | ✅ | ✅ |
| Thêm/Sửa | ✅ | ✅ |
| Xóa | ✅ | ✅ |

## Models Liên Quan
- `Product`, `ProductVariant`, `ProductImage`
- `Category`, `Brand`, `Color`, `Size`, `Tag`
