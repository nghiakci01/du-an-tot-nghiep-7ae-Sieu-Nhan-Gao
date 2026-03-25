# Admin 06. Quản Lý Nhà Cung Cấp

## Mô tả
Module quản lý danh sách các nhà cung cấp sản phẩm cho hệ thống.

---

## Chức Năng

### A6.1 Nhà Cung Cấp (Suppliers)
- **Route:** `GET /admin/suppliers` (resource)
- **Controller:** `Admin\SupplierController`
- **Thông tin:**
  - Tên công ty, người liên hệ
  - Email, số điện thoại, địa chỉ
  - Ghi chú
- **Model:** `Supplier` — Bảng `suppliers`


---

## Phân Quyền
| Hành động | Staff | Admin |
|-----------|-------|-------|
| Quản lý Nhà cung cấp | ❌ | ✅ |
