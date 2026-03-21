# Lock Screen & Error Pages - Hướng dẫn sử dụng

## Chức năng Lock Screen

### Cách sử dụng

1. **Khóa màn hình**:
   - Truy cập: `/admin/lock-screen`
   - Hoặc thêm link vào menu admin:
   ```blade
   <a href="{{ route('admin.lock') }}">
       <i class="fa fa-lock"></i> Khóa màn hình
   </a>
   ```

2. **Mở khóa**:
   - Nhập mật khẩu admin hiện tại
   - Click "Mở khóa"

3. **Đăng xuất từ lock screen**:
   - Click "Đăng nhập" ở dưới form
   - Sẽ logout và redirect về trang login

### Cách hoạt động

- Khi khóa màn hình, session `admin_locked` được set = `true`
- Middleware `CheckLockScreen` kiểm tra session này
- Nếu locked, tất cả requests đến admin panel sẽ bị redirect về lock screen
- Chỉ routes `admin.lock` và `admin.unlock` được phép truy cập khi locked

## Error Pages

### Các trang error đã được cải thiện

1. **403 - Forbidden**: Truy cập bị từ chối
2. **404 - Not Found**: Không tìm thấy trang
3. **500 - Server Error**: Lỗi server

### Tính năng tự động

- **Tự động phát hiện context**: Admin panel hoặc Storefront
- **Layout phù hợp**: Sử dụng layout admin hoặc storefront tùy context
- **Routes phù hợp**: "Về Dashboard" (admin) hoặc "Về Trang Chủ" (storefront)

### Cách test

```php
// Test 403 error
Route::get('/test-403', function () {
    abort(403);
});

// Test 404 error
// Chỉ cần truy cập URL không tồn tại

// Test 500 error
Route::get('/test-500', function () {
    throw new \Exception('Test error');
});
```

## Cấu trúc Files

```
app/Http/Middleware/
└── CheckLockScreen.php          # Middleware kiểm tra lock screen

bootstrap/
└── app.php                      # Cấu hình exception handler

routes/
├── auth.php                     # Lock screen routes
└── admin.php                    # Admin routes (không có lock screen routes)

resources/views/
├── admin/auth/
│   └── lock-screen.blade.php   # Trang lock screen
└── errors/
    ├── 403.blade.php           # Trang 403 error
    ├── 404.blade.php           # Trang 404 error
    └── 500.blade.php           # Trang 500 error
```

## API Routes

- `GET /admin/lock-screen` - Hiển thị trang lock screen
- `POST /admin/unlock` - Xử lý unlock

## Session Keys

- `admin_locked` (boolean) - Trạng thái khóa màn hình

## Middleware

- `CheckLockScreen` - Kiểm tra trạng thái lock screen
- Áp dụng cho tất cả admin routes (trừ lock/unlock)

## Lưu ý

1. Lock screen chỉ áp dụng cho admin panel
2. Không áp dụng cho customer/storefront
3. Mật khẩu unlock là mật khẩu admin hiện tại
4. Khi logout từ lock screen, session sẽ bị xóa hoàn toàn

