# Admin 10. Báo Cáo & Thống Kê

## Mô tả
Module xuất báo cáo doanh thu và đơn hàng dưới dạng file Excel và PDF.

---

## Chức Năng

### A10.1 Xuất Báo Cáo Đơn Hàng (Excel)
- **Route:** `GET /admin/reports/orders/excel`
- **Controller:** `Admin\ReportController@exportOrdersExcel`
- **Mô tả:** Tải xuống file Excel chứa tất cả đơn hàng với đầy đủ thông tin.
- **Công nghệ:** Laravel Excel (Maatwebsite)
- **Nội dung file:**
  - Mã đơn hàng, ngày tạo, khách hàng
  - Tổng tiền, giảm giá, phí ship, tổng thanh toán
  - Phương thức thanh toán, trạng thái
  - Địa chỉ giao hàng

---

### A10.2 Xuất Báo Cáo Doanh Thu (PDF)
- **Route:** `GET /admin/reports/revenue/pdf`
- **Controller:** `Admin\ReportController@exportRevenuePDF`
- **Mô tả:** Tải xuống file PDF báo cáo doanh thu theo tháng/năm.
- **Công nghệ:** DomPDF / Snappy PDF
- **Nội dung:**
  - Biểu đồ doanh thu
  - Bảng tổng hợp theo ngày/tháng
  - Top sản phẩm bán chạy

---

## Phân Quyền
| Hành động | Staff | Admin |
|-----------|-------|-------|
| Xuất Excel đơn hàng | ✅ | ✅ |
| Xuất PDF doanh thu | ✅ | ✅ |
