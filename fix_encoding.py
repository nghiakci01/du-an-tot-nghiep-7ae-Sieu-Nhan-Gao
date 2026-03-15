import os

file_path = r"d:\laragon1\laragon\www\du-an-tot-nghiep-7ae-Sieu-Nhan-Gao\resources\views\frontend\products\show.blade.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

replacements = {
    "chÆ°a chá» n": "chưa chọn",
    "Ä Ã£ bán": "Đã bán",
    "Ä Ã¡nh giá cao": "Đánh giá cao",
    "vÃ o": "vào",
    "giá»  hÃ ng": "giỏ hàng",
    "giá»\x20 hÃ ng": "giỏ hàng",
    "giá» hÃ ng": "giỏ hàng",
    "Sá»‘ lÆ°á»£ng": "Số lượng",
    "Ä‘Æ°á»£c": "được",
    "Ä‘iá» u chá»‰nh": "điều chỉnh",
    "vá» ": "về",
    "Ä á»“ng Ã½": "Đồng ý",
    "vÃ ": "và",
    "CÃ²n": "Còn",
    "Tháº»": "Thẻ",
    "Ráº¥t khÃ´ng hÃ lÃ²ng": "Rất không hài lòng",
    "KhÃ´ng hÃ lÃ²ng": "Không hài lòng",
    "BÃ¬nh thÆ°á» ng": "Bình thường",
    "HÃ lÃ²ng": "Hài lòng",
    "Ráº¥t hÃ lÃ²ng": "Rất hài lòng",
    "báº¯t buá»™c": "bắt buộc",
    "Ä á» c giá trá»‹": "Đọc giá trị",
    "tá»« select gá»‘c": "từ select gốc",
    "pháº£i chá» n": "phải chọn",
    "tiáº¿p tá»¥c": "tiếp tục",
    "Tá»• há»£p khÃ´ng cÃ³": "Tổ hợp không có",
    "KhÃ´ng tÃ¬m tháº¥y": "Không tìm thấy",
    "vá»›i size": "với size",
    "mÃ u sáº¯c nÃ y": "màu sắc này",
    "Háº¿t hÃ ng": "Hết hàng",
    "lá»±a chá» n nÃ y hiá»‡n đã": "lựa chọn này hiện đã",
    "khÃ¡c": "khác",
    "Chá» n láº¡i": "Chọn lại",
    "yÃªu cáº§u vÆ°á»£t": "yêu cầu vượt",
    "VÆ°á»£t quÃ¡": "Vượt quá",
    "GÃ n": "Gán",
    "Ä ang thêm": "Đang thêm",
    "ThÃ nh cÃ´ng": "Thành công",
    "trÃªn": "trên",
    "mÃ u": "màu",
    "nÃ y": "này",
    "lá»±a chá» n": "lựa chọn",
    "hÃ ng": "hàng",
    "cÃ´ng": "công"
}

# Apply targeted replacements
for k, v in replacements.items():
    content = content.replace(k, v)

# Special cases:
content = content.replace("Ráº¥t khÃ´ng hÃ i lÃ²ng", "Rất không hài lòng")
content = content.replace("KhÃ´ng hÃ i lÃ²ng", "Không hài lòng")
content = content.replace("BÃ¬nh thÆ°á» ng", "Bình thường")
content = content.replace("HÃ i lÃ²ng", "Hài lòng")
content = content.replace("Ráº¥t hÃ i lÃ²ng", "Rất hài lòng")
content = content.replace("ThÃ nh cÃ´ng", "Thành công")
content = content.replace("vÃ o giá»  hÃ ng", "vào giỏ hàng")
content = content.replace("giá»  hÃ ng", "giỏ hàng")
content = content.replace("vÃ ", "và")
content = content.replace("mÃ u sáº¯c nÃ y", "màu sắc này")
content = content.replace("hÃ ng", "hàng")
content = content.replace("CÃ²n", "Còn")
content = content.replace("Ä ang", "Đang")

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Fixes applied.")
