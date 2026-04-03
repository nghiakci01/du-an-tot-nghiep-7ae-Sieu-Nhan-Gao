<!-- cSpell:disable -->
# Du an Tot nghiep - Sieu Nhan Gao

Website thuong mai dien tu xay dung bang Laravel cho de tai tot nghiep.

## Tong quan

- Backend: Laravel 12, PHP 8.2
- Frontend: Blade, Bootstrap, Vite
- Database local: MySQL / MariaDB
- Tinh nang chinh: mua hang, gio hang, checkout, quan tri admin, blog, chatbot, bao cao

## Tai lieu nhanh

- Huong dan chay du an: [docs/PULL_GUIDE.md](/C:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/docs/PULL_GUIDE.md)
- Checklist demo va bao ve: [docs/DEMO_GUIDE.md](/C:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/docs/DEMO_GUIDE.md)
- Trang thai tinh nang va cac muc chua hoan thien: [docs/PROJECT_STATUS.md](/C:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/docs/PROJECT_STATUS.md)
- Mo ta tai lieu chi tiet: [docs/README.md](/C:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/docs/README.md)

## Chay nhanh

1. Cai dependencies va tao cau hinh:

```bash
composer install
copy .env.example .env
php artisan key:generate
```

1. Cap nhat `.env` cho dung database local.

1. Chay migrate va seed:

```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

1. Cai frontend assets:

```bash
npm install
npm run build
```

1. Chay local:

```bash
composer dev
```

## Tai khoan demo seed san

- Admin: `admin@gmail.com`
- Mat khau: `password`

## Kiem thu

```bash
php artisan test
```

Trang thai hien tai: `55 passed (126 assertions)`.

## Ghi chu demo

- Nen demo cac luong on dinh: trang chu, danh sach san pham, chi tiet san pham, gio hang, checkout COD/chuyen khoan, quan tri banner, quan tri nguoi dung, dashboard bao cao.
- Khong nen demo nhu tinh nang da hoan thien: vi dien tu, kho/stock report, route test thanh toan.
- Checkout hien da co:
  - giao tan noi voi tinh phi ship theo provider noi bo
  - nhan tai cua hang voi phi ship `0`
  - kha nang bat quote GHTK that neu cau hinh `.env` day du

## Bat GHTK that

Neu muon checkout thu quote GHTK that thay vi mock fallback, can dien cac bien sau trong `.env`:

```bash
GHTK_ENABLED=true
GHTK_TOKEN=your-token
GHTK_CLIENT_SOURCE=
GHTK_API_URL=https://services.giaohangtietkiem.vn
GHTK_TRANSPORT=road
SHIPPING_PICKUP_PROVINCE=Ha Noi
SHIPPING_PICKUP_DISTRICT=Quan Hai Ba Trung
SHIPPING_PICKUP_WARD=
SHIPPING_PICKUP_ADDRESS=Kho Elite
```

Luu y:

- Neu thieu token hoac dia chi lay hang, he thong se tu dong fallback ve bang phi ship noi bo de khong vo flow checkout.
- Hien tai GHTK la provider da duoc noi san sang cho quote that; GHN va Viettel Post van dang o che do noi bo/mock.
