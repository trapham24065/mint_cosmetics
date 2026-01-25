# 🔧 SKU Fixes Summary - Product Create & Update

## 🔴 Các Lỗi Đã Tìm Thấy và Sửa

### 1. **Simple Product Update - THIẾU LƯU SKU**
**File**: `app/Services/Admin/ProductService.php` (Dòng 119-125)

**Vấn đề**: 
Khi update simple product, code chỉ lưu `price`, `stock`, `discount_price` nhưng **KHÔNG lưu SKU** vào variant.

**Trước khi sửa**:
```php
$variant = $product->variants()->firstOrNew([]);
$variant->price = $data['price'];
$variant->stock = $data['stock'];
$variant->discount_price = $data['discount_price'] ?? null;
$variant->save(); // ❌ Thiếu SKU
```

**Sau khi sửa**:
```php
$variant = $product->variants()->firstOrNew([]);
$variant->price = $data['price'];
$variant->stock = $data['stock'];
$variant->discount_price = $data['discount_price'] ?? null;

// ✅ FIX: Lưu SKU cho simple product
if (!empty($data['sku'])) {
    $variant->sku = $data['sku'];
} elseif (!$variant->sku) {
    // Nếu không có SKU và variant chưa có SKU, tự sinh
    $variant->sku = $this->generateSku($product->name);
}

$variant->save();
```

---

### 2. **Variable Product Update - CẤU TRÚC DỮ LIỆU SAI**
**File**: `app/Services/Admin/ProductService.php` (Dòng 144-150)

**Vấn đề**: 
Code đang dùng `foreach ($data['variants'] as $id => $variantData)` nhưng `$id` **KHÔNG phải là ID** của variant, mà là **index** của array (0, 1, 2...).

**Trước khi sửa**:
```php
foreach ($data['variants'] as $id => $variantData) {
    $variant = $product->variants()->find($id); // ❌ $id là index, không phải ID
    if ($variant) {
        $variant->update($variantData);
    }
}
```

**Sau khi sửa**:
```php
foreach ($data['variants'] as $variantData) {
    // ✅ Lấy ID từ trong $variantData, không phải từ key
    $variantId = $variantData['id'] ?? null;
    if ($variantId) {
        $variant = $product->variants()->find($variantId);
        if ($variant) {
            // Cập nhật các trường
            $variant->price = $variantData['price'];
            $variant->stock = $variantData['stock'];
            $variant->discount_price = $variantData['discount_price'] ?? null;
            
            // ✅ Lưu SKU
            if (!empty($variantData['sku'])) {
                $variant->sku = $variantData['sku'];
            } elseif (!$variant->sku) {
                $variant->sku = $this->generateSku($product->name);
            }
            
            $variant->save();
            
            // Sync attribute values
            if (!empty($variantData['attribute_value_ids'])) {
                $valueIds = $this->parseAttributeValueIds($variantData['attribute_value_ids']);
                if (!empty($valueIds)) {
                    $variant->attributeValues()->sync($valueIds);
                }
            }
        }
    }
}
```

---

### 3. **Variable Product Update - THIẾU SKU cho New Variants**
**File**: `app/Services/Admin/ProductService.php` (Dòng 169-177)

**Vấn đề**: 
Khi tạo new variants, nếu không có SKU thì phải tự sinh, nhưng code cũ không làm điều này.

**Trước khi sửa**:
```php
foreach ($data['new_variants'] as $variantData) {
    $newVariant = $product->variants()->create($variantData); // ❌ Thiếu SKU
    if (!empty($variantData['attribute_value_ids'])) {
        $valueIds = explode(',', $variantData['attribute_value_ids']);
        $newVariant->attributeValues()->sync($valueIds);
    }
}
```

**Sau khi sửa**:
```php
foreach ($data['new_variants'] as $variantData) {
    // ✅ FIX: Tự sinh SKU nếu không có
    if (empty($variantData['sku'])) {
        $variantData['sku'] = $this->generateSku($product->name);
    }
    
    $newVariant = $product->variants()->create([
        'price' => $variantData['price'],
        'stock' => $variantData['stock'],
        'discount_price' => $variantData['discount_price'] ?? null,
        'sku' => $variantData['sku'],
    ]);
    
    if (!empty($variantData['attribute_value_ids'])) {
        $valueIds = $this->parseAttributeValueIds($variantData['attribute_value_ids']);
        if (!empty($valueIds)) {
            $newVariant->attributeValues()->sync($valueIds);
        }
    }
}
```

---

### 4. **StoreProductRequest - THIẾU VALIDATION cho SKU của Variable Product**
**File**: `app/Http/Requests/Products/StoreProductRequest.php` (Dòng 60-71)

**Vấn đề**: 
Validation cho variable product không có rule cho SKU.

**Sau khi sửa**:
```php
'variants.*.sku' => [
    'nullable',
    'string',
    'max:255',
    'distinct', // Không được trùng nhau trong cùng 1 request
    Rule::unique('product_variants', 'sku'),
],
```

---

## ✅ Các File Đã Sửa

1. ✅ `app/Services/Admin/ProductService.php`
   - Sửa `updateProduct()` method
   - Thêm logic lưu SKU cho simple product
   - Sửa logic update existing variants (cấu trúc dữ liệu)
   - Thêm logic tự sinh SKU cho new variants

2. ✅ `app/Http/Requests/Products/StoreProductRequest.php`
   - Thêm validation rule cho `variants.*.sku`

---

## 🧪 Cách Test

### Test 1: Create Simple Product
1. Vào `/admin/products/create`
2. Chọn **Simple Product**
3. Nhập tên sản phẩm: "Test Product 1"
4. **Để trống SKU** → Hệ thống tự sinh SKU
5. Submit form
6. Kiểm tra database: `SELECT * FROM product_variants WHERE product_id = [ID vừa tạo]`
7. ✅ Phải có SKU được tự sinh (ví dụ: `TES-X7Z29A`)

### Test 2: Update Simple Product với SKU mới
1. Edit product vừa tạo
2. Thay đổi SKU thành: `CUSTOM-SKU-001`
3. Submit form
4. Kiểm tra database: SKU phải là `CUSTOM-SKU-001`

### Test 3: Create Variable Product
1. Vào `/admin/products/create`
2. Chọn **Variable Product**
3. Tạo 2 variants với attributes khác nhau
4. **Để trống SKU** cho cả 2 variants
5. Submit form
6. Kiểm tra database: Cả 2 variants phải có SKU được tự sinh

### Test 4: Update Variable Product - Sửa Existing Variant
1. Edit variable product vừa tạo
2. Sửa SKU của variant 1 thành: `VAR-001`
3. Submit form
4. Kiểm tra database: Variant 1 phải có SKU là `VAR-001`

### Test 5: Update Variable Product - Thêm New Variant
1. Edit variable product
2. Thêm 1 variant mới
3. **Để trống SKU**
4. Submit form
5. Kiểm tra database: Variant mới phải có SKU được tự sinh

---

## 🎯 Kết Luận

Tất cả các lỗi về SKU đã được sửa:
- ✅ Simple product create/update đều lưu SKU
- ✅ Variable product create/update đều lưu SKU
- ✅ Tự động sinh SKU nếu để trống
- ✅ Validation đầy đủ cho SKU (unique, distinct)

Bạn có thể test ngay bây giờ! 🚀

