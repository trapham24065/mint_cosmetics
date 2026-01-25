# Test Scenarios - Hệ Thống Giỏ Hàng

## Chuẩn Bị

1. Đảm bảo database đã chạy migration mới nhất:
```bash
php artisan migrate
```

2. Tạo một số test accounts (nếu chưa có):
- Email: test1@example.com / Password: password123
- Email: test2@example.com / Password: password123

3. Đảm bảo có ít nhất 3-5 sản phẩm trong database

---

## Test Case 1: Guest User Cart (Session)

### Mục tiêu
Kiểm tra giỏ hàng hoạt động đúng cho người dùng chưa đăng nhập

### Các bước
1. Mở trình duyệt ẩn danh (Incognito/Private)
2. Truy cập trang chủ
3. Thêm sản phẩm A vào giỏ hàng
4. Kiểm tra icon giỏ hàng hiển thị số lượng (1)
5. Click vào icon giỏ hàng → Xem aside cart
6. Thêm sản phẩm B vào giỏ hàng
7. Kiểm tra icon giỏ hàng hiển thị số lượng (2)
8. Vào trang `/cart` → Kiểm tra có 2 sản phẩm
9. Cập nhật quantity sản phẩm A từ 1 → 3
10. Kiểm tra subtotal và total cập nhật đúng

### Kết quả mong đợi
- ✅ Icon cart hiển thị đúng số lượng
- ✅ Aside cart hiển thị đúng sản phẩm
- ✅ Cart page hiển thị đúng sản phẩm và giá
- ✅ Cập nhật quantity hoạt động
- ✅ Kiểm tra database: Table `carts` KHÔNG có record nào (vì chưa login)

### Kiểm tra database
```sql
SELECT * FROM carts; -- Không có record nào
```

---

## Test Case 2: Logged In User Cart (Database)

### Mục tiêu
Kiểm tra giỏ hàng lưu vào database cho người dùng đã đăng nhập

### Các bước
1. Đăng nhập với account test1@example.com
2. Thêm sản phẩm C vào giỏ hàng
3. Kiểm tra icon giỏ hàng hiển thị số lượng (1)
4. Thêm sản phẩm D vào giỏ hàng
5. Kiểm tra icon giỏ hàng hiển thị số lượng (2)
6. Vào trang `/cart` → Kiểm tra có 2 sản phẩm
7. **Logout**
8. **Login lại** với cùng account
9. Kiểm tra giỏ hàng vẫn còn 2 sản phẩm C, D

### Kết quả mong đợi
- ✅ Icon cart hiển thị đúng số lượng
- ✅ Cart lưu vào database
- ✅ Sau khi logout và login lại, cart vẫn còn
- ✅ Kiểm tra database: Table `carts` có 2 records

### Kiểm tra database
```sql
SELECT c.*, pv.sku, p.name 
FROM carts c
JOIN product_variants pv ON c.product_variant_id = pv.id
JOIN products p ON pv.product_id = p.id
WHERE c.customer_id = 1; -- ID của test1@example.com
```

---

## Test Case 3: Merge Cart (Session → Database)

### Mục tiêu
Kiểm tra tính năng merge cart khi user login

### Các bước
1. **Logout** (nếu đang login)
2. Mở trình duyệt ẩn danh
3. Thêm sản phẩm E vào giỏ hàng (chưa login)
4. Thêm sản phẩm F vào giỏ hàng (chưa login)
5. Kiểm tra icon cart hiển thị (2)
6. **Login** với account test2@example.com (account này đã có sản phẩm G trong giỏ từ trước)
7. Sau khi login, kiểm tra giỏ hàng có 3 sản phẩm: E, F, G

### Kết quả mong đợi
- ✅ Trước khi login: Cart có 2 sản phẩm E, F (trong session)
- ✅ Sau khi login: Cart có 3 sản phẩm E, F, G (trong database)
- ✅ Session cart đã bị xóa
- ✅ Database có 3 records cho customer test2

### Kiểm tra database
```sql
-- Trước khi login: Không có record nào cho customer test2
SELECT * FROM carts WHERE customer_id = 2;

-- Sau khi login: Có 3 records
SELECT c.*, pv.sku, p.name 
FROM carts c
JOIN product_variants pv ON c.product_variant_id = pv.id
JOIN products p ON pv.product_id = p.id
WHERE c.customer_id = 2;
```

---

## Test Case 4: Merge Cart với Duplicate Product

### Mục tiêu
Kiểm tra merge cart khi có sản phẩm trùng nhau

### Chuẩn bị
1. Login với account test3@example.com
2. Thêm sản phẩm H (variant_id = 10) quantity = 2
3. Logout

### Các bước
1. Chưa login, thêm sản phẩm H (variant_id = 10) quantity = 3
2. Login với account test3@example.com
3. Kiểm tra giỏ hàng có sản phẩm H với quantity = 5 (2 + 3)

### Kết quả mong đợi
- ✅ Quantity được cộng dồn: 2 + 3 = 5
- ✅ Database chỉ có 1 record cho sản phẩm H
- ✅ Không có duplicate record

### Kiểm tra database
```sql
SELECT * FROM carts 
WHERE customer_id = 3 AND product_variant_id = 10;
-- Chỉ có 1 record với quantity = 5
```

---

## Test Case 5: Checkout và Clear Cart

### Mục tiêu
Kiểm tra giỏ hàng bị xóa sau khi checkout

### Các bước
1. Login với account test1@example.com
2. Đảm bảo giỏ hàng có ít nhất 2 sản phẩm
3. Vào trang `/checkout`
4. Điền thông tin và submit form
5. Sau khi tạo order thành công, kiểm tra giỏ hàng đã trống

### Kết quả mong đợi
- ✅ Order được tạo thành công
- ✅ Giỏ hàng trống (icon cart = 0)
- ✅ Database: Table `carts` không còn record nào cho customer này
- ✅ Database: Table `orders` có 1 record mới
- ✅ Database: Table `order_items` có records tương ứng

### Kiểm tra database
```sql
-- Cart đã trống
SELECT * FROM carts WHERE customer_id = 1;

-- Order đã tạo
SELECT * FROM orders WHERE customer_id = 1 ORDER BY id DESC LIMIT 1;

-- Order items
SELECT * FROM order_items WHERE order_id = (
    SELECT id FROM orders WHERE customer_id = 1 ORDER BY id DESC LIMIT 1
);
```

---

## Test Case 6: Unique Constraint

### Mục tiêu
Kiểm tra không thể tạo duplicate cart items

### Các bước
1. Login với account test1@example.com
2. Thêm sản phẩm I (variant_id = 15) quantity = 1
3. Thêm lại sản phẩm I (variant_id = 15) quantity = 2
4. Kiểm tra database chỉ có 1 record với quantity = 3

### Kết quả mong đợi
- ✅ Không có lỗi duplicate key
- ✅ Quantity được cộng dồn
- ✅ Database chỉ có 1 record

### Kiểm tra database
```sql
SELECT * FROM carts 
WHERE customer_id = 1 AND product_variant_id = 15;
-- Chỉ có 1 record với quantity = 3
```

---

## Checklist Tổng Hợp

- [ ] Test Case 1: Guest User Cart (Session) ✅
- [ ] Test Case 2: Logged In User Cart (Database) ✅
- [ ] Test Case 3: Merge Cart (Session → Database) ✅
- [ ] Test Case 4: Merge Cart với Duplicate Product ✅
- [ ] Test Case 5: Checkout và Clear Cart ✅
- [ ] Test Case 6: Unique Constraint ✅

---

## Lưu Ý

1. **Clear cache** trước khi test:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

2. **Kiểm tra log** nếu có lỗi:
```bash
tail -f storage/logs/laravel.log
```

3. **Reset database** nếu cần:
```bash
php artisan migrate:fresh --seed
```

