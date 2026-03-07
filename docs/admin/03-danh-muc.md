# Admin 03. Quản Lý Danh Mục, Màu Sắc & Kích Thước

## Mô tả
Quản lý taxonomy sản phẩm: danh mục phân cấp, bảng màu sắc và kích thước.

---

## Chức Năng

### A3.1 Danh Mục Sản Phẩm
- **Route:** `GET /admin/categories` (resource)
- **Controller:** `Admin\CategoryController`
- **Tính năng:**
  - CRUD danh mục
  - Hỗ trợ danh mục cha – con (parent_id)
  - Upload ảnh đại diện danh mục
  - Slug tự động
- **Model:** `Category` — Bảng `categories`

---

### A3.2 Màu Sắc
- **Route:** `GET /admin/colors` (resource)
- **Controller:** `Admin\ColorController`
- **Tính năng:**
  - Tên màu (`name`) và mã hex (`hex_code`)
  - Xem swatch màu trực quan trong danh sách
- **Model:** `Color` — Bảng `colors`

---

### A3.3 Kích Thước
- **Route:** `GET /admin/sizes` (resource)
- **Controller:** `Admin\SizeController`
- **Tính năng:**
  - Tên kích thước (XS, S, M, L, XL, XXL, số giày,...)
  - Sắp xếp hiển thị
- **Model:** `Size` — Bảng `sizes`

---

## Phân Quyền
| Hành động | Staff | Admin |
|-----------|-------|-------|
| CRUD Danh mục | ✅ | ✅ |
| CRUD Màu sắc | ✅ | ✅ |
| CRUD Kích thước | ✅ | ✅ |
