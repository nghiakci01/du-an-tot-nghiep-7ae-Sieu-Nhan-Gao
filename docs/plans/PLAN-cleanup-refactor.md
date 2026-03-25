# PLAN-cleanup-refactor.md

## 1. Tổng quan (Overview)
Dự án `elite` (Laravel) hiện đang có rất nhiều tệp kịch bản (scripts) và tệp nhật ký (logs) tạm thời nằm trực tiếp tại thư mục gốc. Việc này làm rối cấu trúc dự án và khó quản lý. Mục tiêu của kế hoạch này là dọn dẹp thư mục gốc và chuẩn hóa mã nguồn bằng các công cụ có sẵn.

**Loại dự án**: WEB (Laravel)

## 2. Tiêu chí thành công (Success Criteria)
- [ ] Thư mục gốc sạch sẽ, chỉ còn các tệp tiêu chuẩn của Laravel và các tệp cấu hình cần thiết.
- [ ] Mã nguồn được định dạng thống nhất bằng `Laravel Pint`.
- [ ] Các tính năng cốt lõi (Admin, Checkout, Product) vẫn hoạt động bình thường.

## 3. Tech Stack
- **Laravel Pint**: Dùng để fix style code (đã có trong `composer.json`).
- **PHP Artisan**: Dùng để dọn dẹp cache và kiểm tra hệ thống.

## 4. Danh sách tệp dự kiến xóa (File Structure Cleanup)

### Tệp văn bản và Nhật ký (.txt):
- `banners_check_final.txt`, `banners_check_v3.txt`, `banners_check_v3_utf8.txt`, `composer_diff.txt`, `composer_install_output.txt`, `composer_require_final_utf8.txt`, `composer_update_output.txt`, `content_main.txt`, `content_modals.txt`, `current_branch.txt`, `db_check.txt`, `db_tables_list.txt`, `dir_output.txt`, `final_migrate_output.txt`, `git_status_check.txt`, `migration_output.txt`, `pricing-audit-report.txt`, `product-audit-report.txt`, `product_images_report.txt`, `test_output.txt`, v.v.

### Tệp kịch bản tạm (.php):
- `audit-products.php`, `check-columns.php`, `check-prices.php`, `check-products.php`, `check-variants.php`, `check_table.php`, `clean-missing-images.php`, `clear-opcache.php`, `debug_images.php`, `detailed-price-check.php`, `direct-db-check.php`, `list-products.php`, `populate_gallery.php`, `pricing-audit.php`, `quick-audit.php`, `robust_check_table.php`, `simulate-view.php`, `test-product-model.php`.

### Các tệp khác:
- `rewrite.py`, `update-all-views.ps1`, `update-view.ps1`.

## 5. Phân công Task (Task Breakdown)

| Task ID | Tên Task | Agent | Kỹ năng | Ưu tiên | Phụ thuộc | INPUT → OUTPUT → VERIFY |
|---------|----------|-------|---------|---------|-----------|-------------------------|
| T1 | Xóa tệp rác thư mục gốc | `orchestrator` | `bash-linux` / `powershell` | P0 | None | Danh sách tệp rác → Tệp bị xóa → `ls` không còn thấy tệp rác |
| T2 | Chạy Laravel Pint | `backend-specialist` | `clean-code` | P1 | None | Toàn bộ `app/` → Code được format → `vendor/bin/pint --test` trả về success |
| T3 | Dọn dẹp Cache Laravel | `backend-specialist` | `server-management` | P2 | T1, T2 | `php artisan optimize:clear` → Cache được dọn dẹp → Server chạy bình thường |

## 6. Phase X: Xác minh cuối cùng (Final Verification)
- [ ] Chạy `php artisan test` (nếu có test case).
- [ ] Kiểm tra thủ công giao diện Admin Panel.
- [ ] Kiểm tra thủ công quy trình đặt hàng (Checkout).
- [ ] Đảm bảo không còn tệp lạ xuất hiện khi chạy server.

---
## ✅ PHASE X COMPLETE
- Lint: [ ]
- Security: [ ]
- Build: [ ]
- Date: 
