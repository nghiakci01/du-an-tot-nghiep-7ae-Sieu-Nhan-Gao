# Trang thai du an

Tai lieu nay tong hop nhanh cac muc da on, cac muc can canh bao khi demo, va cac phan chua hoan thien du test hien tai da xanh.

## Da on dinh cho demo

- Trang chu va danh sach san pham
- Chi tiet san pham va bien the
- Gio hang
- Checkout co kiem tra ton kho
- Dat hang COD
- Dat hang chuyen khoan
- Dashboard admin
- Quan ly banner
- Quan ly nguoi dung
- Bao cao xuat Excel/PDF
- Phan quyen guest, user, admin cho khu vuc admin

## Da xac minh bang test

- `php artisan test`
- Ket qua hien tai: `55 passed (126 assertions)`

## Chua hoan thien hoac can canh bao

1. Shipping provider da co GHN tich hop va co co che fallback.
Chi tiet: [GhnShippingProvider.php](/C:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/app/Services/Shipping/GhnShippingProvider.php), [GhtkShippingProvider.php](/C:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/app/Services/Shipping/GhtkShippingProvider.php), [ViettelPostShippingProvider.php](/C:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/app/Services/Shipping/ViettelPostShippingProvider.php), [ShippingService.php](/C:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/app/Services/Shipping/ShippingService.php)
Trang thai:
- GHN la provider uu tien dau tien; co the goi live quote neu `.env` co token, shop id, va dia chi kho hop le.
- Neu cau hinh thieu hoac API loi, he thong tu fallback ve bang phi noi bo.
- GHTK va Viettel Post van giu vai tro provider noi bo/mock du phong.

2. Kho / stock report chua co man hinh that.
Chi tiet: [web.php](/C:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/routes/web.php):191
Trang thai: route tra ve chuoi `Stock Report Page (Coming Soon)`.

3. Wallet dang bi tat trong route.
Chi tiet: [web.php](/C:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/routes/web.php):92, [web.php](/C:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/routes/web.php):200
Trang thai: docs va view co nhac den wallet, nhung route dang comment out.

4. Route test thanh toan dang ton tai trong web route.
Chi tiet: [web.php](/C:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/routes/web.php):283
Trang thai: phuc vu dev/test, nen khoa hoac gioi han moi truong truoc khi dua production.

5. README va huong dan cu bi hong encoding va sai link.
Trang thai: da duoc cap nhat trong lan chinh sua nay.

6. Tich hop dich vu ngoai phu thuoc `.env`.
Chi tiet: [.env.example](/C:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/.env.example)
Anh huong: mail, VNPAY, social login, chatbot AI se khong demo on dinh neu chua cau hinh.

## Rui ro thuc te ngoai pham vi test

- Build front-end va du lieu demo phu thuoc chat luong seed.
- Cac callback ben thu ba can domain local dung.
- Cac route test neu bo quen co the gay nham lan khi demo hoac deploy.
- Tai lieu cu trong `docs/` co noi dung tham khao tot, nhung co the khong con dong bo 100 phan tram voi route dang tat.

## Goi y uu tien neu con thoi gian

1. Khoa route `test-payment` theo `app()->environment('local')`.
2. An hoan toan menu wallet neu route da tat.
3. Chuyen stock report tu placeholder sang trang thong bao ro rang hoac tam an menu.
4. Neu muon demo ship that, uu tien kiem tra GHN sandbox/production token va dia chi kho hang.
