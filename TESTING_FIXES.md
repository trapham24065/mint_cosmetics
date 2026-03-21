# Hướng dẫn kiểm tra các sửa lỗi

## 1. Kiểm tra trang 403 Error

### Test trên Storefront:
1. Truy cập một URL không tồn tại hoặc không có quyền truy cập
2. Hoặc thêm code test vào `routes/web.php`:
```php
Route::get('/test-403', function () {
    abort(403);
});
```
3. Truy cập: `http://your-domain.com/test-403`
4. Kiểm tra xem trang 403 có hiển thị với layout storefront không
5. Kiểm tra nút "Về Trang Chủ" có hoạt động không

### Test trên Admin Panel:
1. Đăng nhập vào admin panel
2. Thêm code test vào `routes/admin.php`:
```php
Route::get('/test-403', function () {
    abort(403);
})->name('test.403');
```
3. Truy cập: `http://your-domain.com/admin/test-403`
4. Kiểm tra xem trang 403 có hiển thị với layout admin không
5. Kiểm tra nút "Về Dashboard" có hoạt động không

## 2. Kiểm tra chức năng Lock Screen

### Bước 1: Khóa màn hình
1. Đăng nhập vào admin panel
2. Truy cập: `http://your-domain.com/admin/lock-screen`
3. Kiểm tra xem trang lock screen có hiển thị không
4. Kiểm tra xem avatar và tên admin có hiển thị đúng không

### Bước 2: Kiểm tra middleware
1. Sau khi khóa màn hình, thử truy cập bất kỳ trang admin nào khác (ví dụ: `/admin/dashboard`)
2. Hệ thống phải tự động redirect về trang lock screen
3. Bạn không thể truy cập bất kỳ trang admin nào khi màn hình đang bị khóa

### Bước 3: Mở khóa màn hình
1. Ở trang lock screen, nhập mật khẩu admin
2. Click "Unlock"
3. Nếu mật khẩu đúng, bạn sẽ được redirect về dashboard
4. Nếu mật khẩu sai, sẽ hiển thị thông báo lỗi

### Bước 4: Kiểm tra sau khi mở khóa
1. Sau khi mở khóa thành công, thử truy cập các trang admin khác
2. Bạn phải có thể truy cập bình thường mà không bị redirect về lock screen

## 3. Các trường hợp cần kiểm tra

### Lock Screen:
- ✅ Khóa màn hình khi đã đăng nhập
- ✅ Không thể truy cập admin khi màn hình bị khóa
- ✅ Có thể mở khóa với mật khẩu đúng
- ✅ Không thể mở khóa với mật khẩu sai
- ✅ Sau khi mở khóa, có thể truy cập admin bình thường
- ✅ Nếu chưa đăng nhập, redirect về trang login

### Trang 403:
- ✅ Hiển thị đúng layout (admin/storefront) dựa trên context
- ✅ Nút "Quay lại" hoạt động
- ✅ Nút "Về Trang Chủ/Dashboard" hoạt động
- ✅ Animation và styling hiển thị đúng

## 4. Các file đã thay đổi

1. `app/Http/Middleware/CheckLockScreen.php` - Thêm logic kiểm tra lock screen
2. `bootstrap/app.php` - Cấu hình exception handler cho custom error pages
3. `routes/auth.php` - Di chuyển lock screen routes vào đây
4. `routes/admin.php` - Xóa lock screen routes
5. `resources/views/errors/403.blade.php` - Tự động phát hiện context

## 5. Lưu ý

- Sau khi test xong, nhớ xóa các route test đã thêm vào
- Middleware `CheckLockScreen` chỉ áp dụng cho admin routes
- Lock screen routes không bị áp dụng middleware `CheckLockScreen` để tránh vòng lặp vô hạn

