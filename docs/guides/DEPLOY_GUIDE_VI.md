# Hướng dẫn Triển khai Lên Server Lên Hosting (Deployment Guide)

Dự án này đã được tối ưu hóa toàn bộ code và build sẵn file Frontend (Vite). Bạn chỉ cần làm theo các bước dưới đây để chạy thật trên Internet! 🚀

---

## Tùy chọn 1: Triển khai lên Shared Hosting (cPanel / DirectAdmin)
*Đây là lựa chọn phổ biến nhất cho sink viên và các dự án nhỏ lẻ.*

### Bước 1: Chuẩn bị Source Code
Vì bạn đã chạy `npm run build`, bạn **KHÔNG CẦN** đưa thư mục `node_modules` lên Hosting.
1. Nén toàn bộ thư mục dự án này thành file `source.zip`.
2. **Lưu ý bỏ qua (không nén) các thư mục/file sau để file ZIP nhẹ nhất:**
   - `node_modules/`
   - `vendor/` (Bắt buộc phải chạy lại lệnh composer trên host, hoặc nếu host không có SSH, bạn có thể nén luôn thư mục vendor này từ localhost lên nhưng sẽ mất thời gian tải).
   - `.git/`

### Bước 2: Upload lên Hosting
1. Đăng nhập vào cPanel, mở **File Manager**.
2. Tìm đến thư mục gốc của tên miền, ví dụ `public_html` (hoặc `tên-miền.com`).
3. Upload `source.zip` và giải nén (Extract) ra tay.

### Bước 3: Cấu hình `public` (Rất quan trọng trên Shared Host)
Trong Laravel, thư mục chạy web là `/public`, nhưng trên Hosting mặc định là `/public_html`.
1. Copy toàn bộ nội dung của thư mục `/public` ra ngoài `/public_html`.
2. Sửa file `index.php` (vừa copy ra) thành:
   ```php
   require __DIR__.'/../bootstrap/autoload.php';
   $app = require_once __DIR__.'/../bootstrap/app.php';
   ```
*(Lưu ý: Tuỳ thuộc cấu trúc ổ đĩa host của bạn mà đường dẫn trên có thể thay đổi một chút, hãy tra cứu Google "Deploy Laravel on cPanel" nếu gặp khó).*

### Bước 4: Tạo Cơ sở dữ liệu (Database) & Cấu hình `.env`
1. Vào mục **MySQL Databases** trên Host, tạo 1 Database mới, 1 User mới, đính kèm User đó vào Database (Check All Privileges).
2. Tìm file `.env` trên File Manager, chuột phải chọn Edit.
3. Sửa thông tin:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=tên_db_vừa_tạo
   DB_USERNAME=user_vừa_tạo
   DB_PASSWORD=mật_khẩu_vừa_tạo
   ```
4. Cuối cùng, Import file DB từ máy bạn (`database_export.sql` hoặc export qua phpMyAdmin) lên phpMyAdmin của Hosting.

### Bước 5: Symlink Storage (Chỉ chạy 1 lần)
Nếu Host có terminal, chạy: `php artisan storage:link`.
Nếu Host KHÔNG CÓ terminal, tạo file `symlink.php` ở public_html với code:
```php
<?php
symlink(__DIR__.'/storage/app/public', __DIR__.'/public/storage');
echo 'Symlink Success';
?>
```
Sau đó truy cập: `domain.com/symlink.php` trên trình duyệt.

---

## Tùy chọn 2: Triển khai lên Máy chủ ảo (VPS Ubuntu + Nginx)
*Lựa chọn này dành cho dân Pro muốn Server chạy siêu nhanh, và có quyền Terminal.*

1. Đẩy code lên GitHub/GitLab.
2. SSH vào VPS và Clone code xuống thư mục rễ ví dụ `/var/www/html/elite`.
3. Chạy các lệnh cài đặt trên VPS:
```bash
cp .env.example .env
# Edit .env thông tin database
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan storage:link
php artisan migrate --force
```
4. Cấu hình Nginx Document Root trỏ thẳng vào `/var/www/html/elite/public`.
5. Reset quyền (Permissions):
```bash
sudo chown -R www-data:www-data /var/www/html/elite
sudo chmod -R 775 /var/www/html/elite/storage
sudo chmod -R 775 /var/www/html/elite/bootstrap/cache
```
6. Tối ưu cực đại trên Production:
```bash
php artisan optimize
php artisan view:cache
```

Chúc bạn deploy thành công và đạt điểm xuất sắc trong ngày bảo vệ đồ án! 🎉
