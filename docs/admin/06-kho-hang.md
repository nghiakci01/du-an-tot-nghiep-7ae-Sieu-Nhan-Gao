# Admin 06. Quản Lý Kho Hàng

## Mô tả
Module quản lý chuỗi cung ứng: nhà cung cấp, kho hàng và phiếu nhập/xuất kho. Chỉ Admin mới truy cập.

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

### A6.2 Kho Hàng (Warehouses)
- **Route:** `GET /admin/warehouses` (resource)
- **Controller:** `Admin\WarehouseController`
- **Thông tin:**
  - Tên kho, địa chỉ
  - Người phụ trách
- **Model:** `Warehouse` — Bảng `warehouses`

---

### A6.3 Phiếu Nhập/Xuất Kho (Inventory Vouchers)
- **Route:** `GET /admin/vouchers` (resource)
- **Controller:** `Admin\InventoryVoucherController`
- **Loại phiếu:**
  - `import` — Phiếu nhập hàng (từ nhà cung cấp)
  - `export` — Phiếu xuất hàng
- **Thông tin phiếu:**
  - Nhà cung cấp, kho, ngày lập, ghi chú
  - Chi tiết dòng: Biến thể sản phẩm (SKU, tên) + số lượng + đơn giá
- **Nghiệp vụ Hoàn thành phiếu:**
  - **Route:** `POST /admin/vouchers/{id}/complete`
  - Khi phiếu nhập được duyệt → tự động cộng tồn kho biến thể tương ứng.
  - Khi phiếu xuất được duyệt → trừ tồn kho.

---

### A6.4 Tìm Kiếm Biến Thể (AJAX)
- **Route:** `GET /admin/api/variants/search`
- **Controller:** `Admin\InventoryVoucherController@variantsSearch`
- **Mô tả:** Gợi ý tìm kiếm biến thể sản phẩm theo SKU hoặc tên khi lập phiếu.

---

## Phân Quyền
| Hành động | Staff | Admin |
|-----------|-------|-------|
| Xem & Lập phiếu | ❌ | ✅ |
| Duyệt phiếu | ❌ | ✅ |
| CRUD Nhà cung cấp/Kho | ❌ | ✅ |
