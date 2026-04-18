# Huong dan chay du an sau khi pull code

Tai lieu nay danh cho thanh vien moi pull code ve may local va can chay du an nhanh, dung, it loi nhat.

## Yeu cau

- PHP 8.2+
- Composer
- Node.js + npm
- MySQL hoac MariaDB
- Laragon hoac moi truong local tuong duong

## Buoc 1: Cai dat dependencies

```bash
composer install
npm install
```

## Buoc 2: Tao file moi truong

```bash
copy .env.example .env
php artisan key:generate
```

Can kiem tra lai cac bien sau trong `.env`:

- `APP_URL`
- `DB_CONNECTION`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- `MAIL_*`
- `VNPAY_*`
- `GOOGLE_*`
- `FACEBOOK_*`
- `GEMINI_API_KEY`
- `FEATURE_WALLET_ENABLED`
- `FEATURE_STOCK_REPORT_ENABLED`
- `FEATURE_DEV_PAYMENT_ROUTES`
- `GHTK_*`
- `SHIPPING_PICKUP_*`

## Buoc 3: Tao database va nap du lieu mau

Tao database rong truoc trong MySQL, sau do chay:

```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

Tai khoan admin seed san:

- Email: `admin@gmail.com`
- Mat khau: `password`

## Buoc 4: Build frontend

Neu chi can chay nhanh:

```bash
npm run build
```

Neu can sua giao dien trong qua trinh phat trien:

```bash
npm run dev
```

## Buoc 5: Chay local

```bash
composer dev
```

Lenh nay se chay cung luc:

- `php artisan serve`
- `php artisan queue:listen --tries=1`
- `php artisan pail --timeout=0`
- `npm run dev`

## Buoc 6: Kiem tra nhanh sau khi chay

1. Mo trang chu.
2. Dang nhap admin bang tai khoan seed san.
3. Vao `/admin/dashboard`.
4. Mo trang san pham, them vao gio hang.
5. Thu checkout COD hoac chuyen khoan.
6. Neu can test ship that, cau hinh `GHTK_TOKEN` va bo `GHTK_ENABLED=true`.

## Lenh huu ich

```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan test
```

## Loi thuong gap

1. Loi ket noi database:
Cap nhat lai thong tin `DB_*` trong `.env`, sau do chay `php artisan config:clear`.

2. Anh khong hien:
Chay `php artisan storage:link`.

3. Giao dien khong cap nhat:
Chay `npm run dev` hoac `npm run build`, sau do `php artisan optimize:clear`.

4. Loi callback thanh toan hoac dang nhap xa hoi:
Kiem tra `APP_URL` va cac bien `VNPAY_*`, `GOOGLE_*`, `FACEBOOK_*`.

5. Chatbot khong phan hoi:
Kiem tra `GEMINI_API_KEY` va cau hinh chatbot trong admin.

6. GHTK khong tra phi ship that:
Kiem tra `GHTK_TOKEN`, `GHTK_API_URL`, `SHIPPING_PICKUP_PROVINCE`, `SHIPPING_PICKUP_DISTRICT`.
Neu thieu mot trong cac gia tri nay, he thong se tu fallback ve bang phi noi bo.
