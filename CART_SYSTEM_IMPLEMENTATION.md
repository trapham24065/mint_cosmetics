# Hệ Thống Giỏ Hàng (Cart System) - Hoàn Thiện

## Tổng Quan

Hệ thống giỏ hàng đã được hoàn thiện với các tính năng:

1. ✅ **Người dùng đã đăng nhập**: Giỏ hàng lưu vào Database
2. ✅ **Người dùng chưa đăng nhập**: Giỏ hàng lưu vào Session
3. ✅ **Merge Cart**: Tự động gộp giỏ hàng khi đăng nhập
4. ✅ **Persistent Cart**: Giỏ hàng vẫn còn sau khi logout

## Các File Đã Thay Đổi

### 1. CartService (`app/Services/Storefront/CartService.php`)
**Thay đổi chính:**
- Tự động phát hiện user đã login hay chưa
- Lưu vào Database nếu đã login, Session nếu chưa
- Tất cả methods (add, update, remove, getCartContents, count) đều hỗ trợ cả 2 nguồn

**Methods mới:**
- `isLoggedIn()`: Kiểm tra user đã login
- `getCustomerId()`: Lấy customer ID
- `addToDatabase()`: Thêm vào DB
- `addToSession()`: Thêm vào Session
- `getCartFromDatabase()`: Lấy cart từ DB
- `updateQuantitiesInDatabase()`: Cập nhật quantity trong DB
- `updateQuantitiesInSession()`: Cập nhật quantity trong Session
- `removeFromDatabase()`: Xóa từ DB
- `removeFromSession()`: Xóa từ Session
- `clear()`: Xóa toàn bộ cart (cả DB và Session)

### 2. CustomerAuthController (`app/Http/Controllers/Storefront/Auth/CustomerAuthController.php`)
**Thay đổi:**
- Sửa `mergeCartOnLogin()` để khớp với cấu trúc session mới (`cart.items` thay vì `cart`)
- Merge cart từ session vào database khi login/register

### 3. PaymentController (`app/Http/Controllers/Storefront/PaymentController.php`)
**Thay đổi:**
- Sử dụng `$this->cartService->clear()` thay vì `session()->forget('cart')`
- Đảm bảo xóa cả cart trong DB và Session sau khi đặt hàng

### 4. CartHelper (`app/Helpers/CartHelper.php`)
**Thay đổi:**
- `get_cart_count()` giờ lấy từ DB nếu user đã login, Session nếu chưa

### 5. Migration (`database/migrations/2026_01_23_213857_add_unique_constraint_to_carts_table.php`)
**Thêm mới:**
- Unique constraint cho `(customer_id, product_variant_id)` để tránh duplicate

## Luồng Hoạt Động

### Scenario 1: Guest User (Chưa đăng nhập)
1. User thêm sản phẩm vào giỏ → Lưu vào **Session**
2. User xem giỏ hàng → Lấy từ **Session**
3. User cập nhật/xóa → Cập nhật **Session**

### Scenario 2: Logged In User (Đã đăng nhập)
1. User thêm sản phẩm vào giỏ → Lưu vào **Database**
2. User xem giỏ hàng → Lấy từ **Database**
3. User cập nhật/xóa → Cập nhật **Database**
4. User logout → Cart vẫn còn trong **Database**
5. User login lại → Cart hiển thị từ **Database**

### Scenario 3: Guest → Login (Merge Cart)
1. Guest user thêm sản phẩm A, B vào giỏ → Lưu vào **Session**
2. User đăng nhập (đã có sản phẩm C trong DB từ trước)
3. Hệ thống tự động merge:
   - Sản phẩm A, B từ Session → Thêm vào Database
   - Nếu trùng sản phẩm → Cộng dồn quantity
   - Xóa Session cart
4. Kết quả: User có sản phẩm A, B, C trong Database

### Scenario 4: Checkout & Order
1. User checkout → Tạo order từ cart data
2. Sau khi tạo order → `cartService->clear()` xóa cả DB và Session
3. User có giỏ hàng trống

## Cách Test

### Test 1: Guest User Cart
```
1. Mở trình duyệt ẩn danh (Incognito)
2. Thêm sản phẩm vào giỏ hàng
3. Kiểm tra giỏ hàng có hiển thị đúng
4. Đóng trình duyệt → Mở lại → Giỏ hàng bị mất (vì session mất)
```

### Test 2: Logged In User Cart
```
1. Đăng nhập
2. Thêm sản phẩm vào giỏ hàng
3. Kiểm tra database table `carts` có record mới
4. Logout
5. Login lại → Giỏ hàng vẫn còn
```

### Test 3: Merge Cart
```
1. Mở trình duyệt ẩn danh
2. Thêm sản phẩm A vào giỏ (chưa login)
3. Đăng nhập (account đã có sản phẩm B trong giỏ từ trước)
4. Kiểm tra giỏ hàng có cả A và B
5. Kiểm tra database có cả 2 sản phẩm
```

### Test 4: Duplicate Prevention
```
1. Đăng nhập
2. Thêm sản phẩm A (variant ID = 1) quantity = 2
3. Thêm lại sản phẩm A (variant ID = 1) quantity = 3
4. Kiểm tra database chỉ có 1 record với quantity = 5
```

### Test 5: Checkout Clear Cart
```
1. Đăng nhập
2. Thêm sản phẩm vào giỏ
3. Checkout và tạo order
4. Kiểm tra giỏ hàng đã trống
5. Kiểm tra database table `carts` không còn record của user
```

## Database Schema

```sql
CREATE TABLE carts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id BIGINT UNSIGNED NOT NULL,
    product_variant_id BIGINT UNSIGNED NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY carts_customer_variant_unique (customer_id, product_variant_id),
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (product_variant_id) REFERENCES product_variants(id) ON DELETE CASCADE
);
```

## Lưu Ý Quan Trọng

1. **Unique Constraint**: Đảm bảo mỗi customer chỉ có 1 record cho mỗi product variant
2. **Cascade Delete**: Khi xóa customer hoặc product variant, cart items tự động xóa
3. **Session Structure**: Session cart lưu ở `cart.items` (không phải `cart`)
4. **Coupon**: Vẫn lưu trong session (cả logged in và guest)

## Troubleshooting

### Lỗi: Duplicate entry for key 'carts_customer_variant_unique'
- **Nguyên nhân**: Đang cố thêm sản phẩm đã có trong giỏ
- **Giải pháp**: Code đã xử lý bằng cách cộng dồn quantity

### Lỗi: Cart count không đúng
- **Nguyên nhân**: Helper function `get_cart_count()` chưa cập nhật
- **Giải pháp**: Đã cập nhật để lấy từ DB nếu logged in

### Lỗi: Cart không merge khi login
- **Nguyên nhân**: Session structure không khớp
- **Giải pháp**: Đã sửa `mergeCartOnLogin()` để dùng `cart.items`

