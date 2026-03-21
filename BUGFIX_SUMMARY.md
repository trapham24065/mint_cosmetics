# Tóm tắt sửa lỗi - Lock Screen & 403 Error Page

## Vấn đề đã phát hiện

### 1. Trang 403 không hoạt động
- **Nguyên nhân**: Laravel chưa được cấu hình để sử dụng custom error pages
- **Triệu chứng**: Khi có lỗi 403, Laravel hiển thị trang lỗi mặc định thay vì trang custom
- **Vấn đề phụ**: Trang 403 chỉ sử dụng layout storefront, không phù hợp khi lỗi xảy ra trong admin panel

### 2. Chức năng màn hình khóa không hoạt động
- **Nguyên nhân**: Middleware `CheckLockScreen` chỉ có `return $next($request)` mà không có logic kiểm tra
- **Triệu chứng**: Vẫn có thể truy cập dashboard dù chưa nhập mật khẩu sau khi khóa màn hình
- **Vấn đề phụ**: Lock screen routes nằm trong `routes/admin.php` bị áp dụng middleware `CheckLockScreen`, tạo vòng lặp vô hạn

## Giải pháp đã áp dụng

### 1. Sửa Middleware CheckLockScreen
**File**: `app/Http/Middleware/CheckLockScreen.php`

**Thay đổi**:
- Thêm logic kiểm tra session `admin_locked`
- Nếu màn hình bị khóa và route không phải `admin.lock` hoặc `admin.unlock`, redirect về lock screen
- Chỉ áp dụng cho user đã đăng nhập

**Code mới**:
```php
public function handle(Request $request, Closure $next): Response
{
    if (Auth::guard('web')->check()) {
        if (session('admin_locked') === true) {
            if (!$request->routeIs('admin.lock') && !$request->routeIs('admin.unlock')) {
                return redirect()->route('admin.lock');
            }
        }
    }
    return $next($request);
}
```

### 2. Cấu hình Exception Handler
**File**: `bootstrap/app.php`

**Thay đổi**:
- Thêm logic render custom error pages trong `withExceptions()`
- Kiểm tra xem view `errors.{statusCode}` có tồn tại không
- Nếu có, render view đó với status code tương ứng

**Code mới**:
```php
->withExceptions(function (Exceptions $exceptions): void {
    $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
        $statusCode = $e->getStatusCode();
        if (view()->exists("errors.{$statusCode}")) {
            return response()->view("errors.{$statusCode}", [], $statusCode);
        }
    });
})
```

### 3. Di chuyển Lock Screen Routes
**File cũ**: `routes/admin.php`
**File mới**: `routes/auth.php`

**Lý do**:
- Routes trong `routes/admin.php` bị áp dụng middleware `CheckLockScreen`
- Điều này tạo vòng lặp vô hạn: lock screen route → middleware kiểm tra → redirect về lock screen → ...
- Di chuyển vào `routes/auth.php` với middleware `auth.admin` (không có `CheckLockScreen`)

**Routes đã di chuyển**:
```php
Route::get('lock-screen', [LockScreenController::class, 'lock'])->name('lock');
Route::post('unlock', [LockScreenController::class, 'unlock'])->name('unlock');
```

### 4. Cải thiện trang 403
**File**: `resources/views/errors/403.blade.php`

**Thay đổi**:
- Thêm logic PHP để phát hiện context (admin/storefront)
- Tự động chọn layout phù hợp
- Tự động chọn route home phù hợp
- Cập nhật text button phù hợp với context

**Code mới**:
```php
@php
$isAdmin = request()->is('admin/*') || request()->is('admin');
$layout = $isAdmin ? 'admin.layouts.app' : 'storefront.layouts.app';
$homeRoute = $isAdmin ? 'admin.dashboard' : 'home';
@endphp

@extends($layout)
```

## Kết quả

### Lock Screen
✅ Middleware kiểm tra session `admin_locked` đúng cách
✅ Không thể truy cập admin khi màn hình bị khóa
✅ Có thể khóa và mở khóa màn hình bình thường
✅ Không có vòng lặp vô hạn

### Trang 403
✅ Hiển thị trang 403 custom khi có lỗi
✅ Tự động phát hiện context (admin/storefront)
✅ Hiển thị layout phù hợp
✅ Nút "Về Trang Chủ/Dashboard" hoạt động đúng

## Files đã thay đổi

1. `app/Http/Middleware/CheckLockScreen.php` - Thêm logic kiểm tra
2. `bootstrap/app.php` - Cấu hình exception handler
3. `routes/auth.php` - Thêm lock screen routes
4. `routes/admin.php` - Xóa lock screen routes và import không dùng
5. `resources/views/errors/403.blade.php` - Tự động phát hiện context
6. `resources/views/errors/404.blade.php` - Tự động phát hiện context (bonus)
7. `resources/views/errors/500.blade.php` - Tự động phát hiện context (bonus)

## Hướng dẫn test

Xem file `TESTING_FIXES.md` để biết chi tiết cách test các tính năng đã sửa.

