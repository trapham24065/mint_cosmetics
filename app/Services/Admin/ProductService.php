<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/27/2025
 * @time 12:54 AM
 */

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Product;
use App\Models\ProductVariant;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductService
{

    /**
     * Create a new product and its variants.
     */
    public function createProduct(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            // 1. Handle image uploads
            if (!empty($data['image'])) {
                $data['image'] = $data['image']->store('products', 'public');
            }
            if (!empty($data['list_image'])) {
                $galleryPaths = [];
                foreach ($data['list_image'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $galleryPaths[] = $file->store('products/gallery', 'public');
                    }
                }
                $data['list_image'] = $galleryPaths;
            }

            // 2. Create the main Product
            $product = Product::create($data);

            // 3. Create variants based on the product type
            $this->createVariants($product, $data);

            return $product;
        });
    }

    /**
     * Update an existing product with the given data.
     */
    public function updateProduct(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            // Log incoming data for debugging
            Log::info('Updating product', ['product_id' => $product->id, 'data_keys' => array_keys($data)]);

            // 1. Handle main image update
            if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $data['image']->store('products', 'public');
            }

            // 2. Handle gallery images update
            if (!empty($data['list_image'])) {
                // First, delete all old gallery images
                if (is_array($product->list_image)) {
                    foreach ($product->list_image as $oldImage) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
                // Then, store the new gallery images
                $galleryPaths = [];
                foreach ($data['list_image'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $galleryPaths[] = $file->store('products/gallery', 'public');
                    }
                }
                $data['list_image'] = $galleryPaths;
            } else {
                // Remove list_image from data to avoid overwriting with null
                unset($data['list_image']);
            }

            // 3. Update basic product information (always allowed)
            $basicFields = [
                'name',
                'slug',
                'short_description',
                'description',
                'category_id',
                'brand_id',
                'active',
                'is_featured',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'image',
                'list_image',
            ];

            $basicData = array_intersect_key($data, array_flip($basicFields));

            // Only update if there's actual data
            if (!empty($basicData)) {
                $product->update($basicData);
            }

            // 3. Handle Variants based on the submitted product type
            if ($data['product_type'] === 'simple') {
                // For simple products, we ensure there is only one variant.
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

                // Delete all other variants except this one (handles switching from variable to simple)
                $variantsToDelete = $product->variants()->where('id', '!=', $variant->id)->get();
                foreach ($variantsToDelete as $v) {
                    if ($v->orderItems()->exists()) {
                        throw new \RuntimeException(
                            "Cannot switch to simple product because a variant is part of an existing order."
                        );
                    }
                    $v->delete();
                }
            } elseif ($data['product_type'] === 'variable') {
                // If switching from simple to variable, delete the old default variant
                if ($product->variants()->count() === 1 && $product->variants()->first()->attributeValues->isEmpty()) {
                    $product->variants()->delete();
                }

                // Update existing variants
                if (!empty($data['variants'])) {
                    // ✅ FIX: Sử dụng đúng cấu trúc dữ liệu
                    foreach ($data['variants'] as $variantData) {
                        // Lấy ID từ trong $variantData, không phải từ key
                        $variantId = $variantData['id'] ?? null;
                        if ($variantId) {
                            $variant = $product->variants()->find($variantId);
                            if ($variant) {
                                // Cập nhật các trường
                                $variant->price = $variantData['price'];
                                $variant->stock = $variantData['stock'];
                                $variant->discount_price = $variantData['discount_price'] ?? null;

                                // ✅ FIX: Lưu SKU cho variant
                                if (!empty($variantData['sku'])) {
                                    $variant->sku = $variantData['sku'];
                                } elseif (!$variant->sku) {
                                    // Nếu không có SKU và variant chưa có SKU, tự sinh
                                    $variant->sku = $this->generateSku($product->name);
                                }

                                $variant->save();

                                // Sync attribute values nếu có
                                if (!empty($variantData['attribute_value_ids'])) {
                                    $valueIds = $this->parseAttributeValueIds($variantData['attribute_value_ids']);
                                    if (!empty($valueIds)) {
                                        $variant->attributeValues()->sync($valueIds);
                                    }
                                }
                            }
                        }
                    }
                }

                // Delete variants marked for deletion from the form
                if (!empty($data['deleted_variants'])) {
                    foreach ($data['deleted_variants'] as $variantId) {
                        $variantToDelete = ProductVariant::find($variantId);
                        if ($variantToDelete) {
                            if ($variantToDelete->orderItems()->exists()) {
                                throw new Exception(
                                    "Cannot delete variant (ID: {$variantId}) because it is part of an existing order."
                                );
                            }
                            $variantToDelete->delete();
                        }
                    }
                }

                // Create new variants
                if (!empty($data['new_variants'])) {
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
                }
            }

            return $product->fresh(['variants', 'variants.attributeValues']);
        });
    }

    /**
     * Parse attribute value IDs from various formats
     */
    private function parseAttributeValueIds($attributeValueIds): array
    {
        if (empty($attributeValueIds)) {
            return [];
        }

        if (is_string($attributeValueIds)) {
            return array_filter(explode(',', $attributeValueIds));
        }

        if (is_array($attributeValueIds)) {
            return array_filter($attributeValueIds);
        }

        return [];
    }

    /**
     * Create variants for a product based on type
     */
    private function createVariants(Product $product, array $data): void
    {
        if ($data['product_type'] === 'simple') {
            $sku = !empty($data['sku']) ? $data['sku'] : $this->generateSku($product->name);
            $product->variants()->create([
                'price'          => $data['price'] ?? 0,
                'discount_price' => $data['discount_price'] ?? null,
                'stock'          => $data['stock'] ?? 0,
                'sku'            => $sku,
            ]);
        } elseif ($data['product_type'] === 'variable') {
            $allVariantsData = array_merge(
                $data['variants'] ?? [],
                $data['new_variants'] ?? []
            );

            if (empty($allVariantsData)) {
                throw new \RuntimeException('A variable product must have at least one variant.');
            }

            foreach ($allVariantsData as $variantData) {
                if (!empty($variantData['price'])) { // Ensure price exists
                    $sku = !empty($variantData['sku']) ? $variantData['sku'] : $this->generateSku($product->name);
                    $variant = $product->variants()->create([
                        'price'          => $variantData['price'],
                        'discount_price' => $variantData['discount_price'] ?? null,
                        'stock'          => $variantData['stock'] ?? 0,
                        'sku'            => $sku,
                    ]);

                    if (!empty($variantData['attribute_value_ids'])) {
                        $valueIds = $this->parseAttributeValueIds($variantData['attribute_value_ids']);
                        if (!empty($valueIds)) {
                            $variant->attributeValues()->sync($valueIds);
                        }
                    }
                }
            }
        }
    }

    /**
     * Soft delete a product after checking data integrity.
     */
    public function deleteProduct(Product $product): bool
    {
        if ($product->orderItems()->exists()) {
            throw new \RuntimeException('Cannot delete product because it is linked to existing orders.');
        }

        return $product->delete();
    }

    /**
     * Perform a bulk action on multiple products.
     *
     * @param  string  $action  The action to perform (e.g., 'change_status').
     * @param  array  $productIds  The IDs of the products to update.
     * @param  mixed  $value  The value for the action (e.g., true/false for status).
     *
     * @return int The number of affected rows.
     */
    public function bulkUpdate(string $action, array $productIds, mixed $value): int
    {
        if (empty($productIds)) {
            return 0;
        }

        switch ($action) {
            case 'change_status':
                // The update method should return an integer. The error indicates it might be returning a boolean.
                // We will explicitly cast the result to an integer to fix the type error.
                $affectedRows = Product::whereIn('id', $productIds)->update(['active' => (bool)$value]);
                return (int)$affectedRows;

                // Bạn có thể thêm các case khác ở đây trong tương lai, ví dụ: 'bulk_delete'
                // case 'bulk_delete':
                //     return Product::destroy($productIds);
        }

        return 0;
    }

    /**
     * Helper: Sinh mã SKU duy nhất
     */
    private function generateSku(string $productName): string
    {
        // Lấy 3 ký tự đầu của tên sản phẩm, chuyển thành chữ hoa
        $slug = Str::slug($productName);
        $prefix = strtoupper(substr($slug, 0, 3));

        if (strlen($prefix) < 3) {
            $prefix = 'PRO'; // Mặc định nếu tên quá ngắn
        }

        // Vòng lặp để đảm bảo duy nhất
        do {
            $sku = $prefix . '-' . strtoupper(Str::random(6));
        } while (ProductVariant::where('sku', $sku)->exists());

        return $sku;
    }
}
