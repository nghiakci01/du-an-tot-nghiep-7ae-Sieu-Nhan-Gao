# Checklist demo va bao ve

Tai lieu nay giup nhom demo theo luong ngan, de hieu, tranh dung vao cac tinh nang dang de ngo.

## Muc tieu buoi demo

- Cho thay duoc bai toan website thuong mai dien tu hoan chinh.
- Cho thay duoc 2 vai tro ro rang: khach hang va admin.
- Chon luong demo on dinh, co du lieu seed san, it phu thuoc dich vu ngoai.

## Chuan bi truoc khi demo

1. Chay `php artisan migrate:fresh --seed`.
2. Chay `php artisan storage:link`.
3. Chay `npm run build` hoac `composer dev`.
4. Dang nhap san tai khoan admin:
Email `admin@gmail.com`, mat khau `password`.
5. Kiem tra nhanh:
Trang chu, shop, gio hang, checkout, admin dashboard.

## Luong demo de xuat

### Luong 1: Khach hang

1. Vao trang chu:
nhan manh banner, san pham noi bat, san pham moi, flash sale, tin tuc.
2. Vao trang shop:
loc theo danh muc, gia, mau, size, thu sap xep.
3. Vao chi tiet san pham:
xem bien the, anh, danh gia, san pham lien quan.
4. Them vao wishlist hoac gio hang.
5. Vao gio hang:
doi so luong, ap coupon neu co.
6. Vao checkout:
chon san pham da tick, nhap thong tin, xem phi ship va tong tien.
7. Chot don voi COD hoac chuyen khoan:
day la 2 luong an toan nhat de demo.
8. Vao trang thanh cong:
cho thay ma don, thong tin thanh toan, huong dan chuyen khoan.
9. Neu can:
demo tra cuu don hang cho guest hoac tai khoan nguoi dung.

### Luong 2: Admin

1. Dang nhap admin.
2. Mo dashboard:
thong ke doanh thu, don hang, khach hang, top san pham, bo loc theo ngay.
3. Vao quan ly banner:
tao, sua, an/hien banner.
4. Vao quan ly nguoi dung:
xem danh sach, chi tiet, loc theo vai tro.
5. Vao quan ly san pham:
cho thay san pham co bien the mau/size.
6. Vao quan ly don hang:
xem don vua tao, trang thai thanh toan, in don.
7. Vao bao cao:
xuat Excel/PDF neu can.

## Luong nen uu tien

- Shop -> Product detail -> Cart -> Checkout -> Success
- Admin login -> Dashboard -> Users -> Banners -> Orders -> Reports

## Tinh nang nen tranh demo nhu "da hoan thien"

- Vi dien tu:
route dang bi tat.
- Kho / stock report:
hien chi la trang placeholder.
- Shipping provider thuc te:
GHN, GHTK, ViettelPost moi co khung service.
- Route test thanh toan:
chi phuc vu phat trien, khong nen trinh bay nhu chuc nang chinh thuc.
- Dang nhap xa hoi:
chi demo khi da cau hinh callback that tren may demo.
- Chatbot AI:
chi demo khi da co `GEMINI_API_KEY` va du lieu cau hinh phu hop.

## Cau hoi de thay co hoi va cach tra loi

- Vi sao chon Laravel:
vi de tach ro frontend/backend theo MVC, phu hop CRUD, auth, queue, mail, test.
- Diem noi bat cua de tai:
luong mua hang hoan chinh, phan quyen admin, bao cao, chatbot, quan ly noi dung.
- Tinh nang nao da test:
gio hang, checkout, admin banner, admin user, admin report, middleware phan quyen.
- Tinh nang nao chua dong goi xong:
shipping provider thuc te, stock report, wallet, route test can khoa truoc production.

## Chot buoi demo

- Nhan manh bai toan thuc te da giai quyet.
- Nhac den bo test dang xanh.
- Neu can noi ve huong mo rong:
tich hop shipping API that, kho hoan chinh, wallet, khoa route test cho production.
