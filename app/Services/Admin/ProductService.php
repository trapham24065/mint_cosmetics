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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{

    public function createProduct(array $data): Product
    {
        return DB::transaction(static function () use ($data) {
            // 1. Handle image uploads (unchanged)
            if (!empty($data['image'])) {
                $data['image'] = $data['image']->store('products', 'public');
            }
            if (!empty($data['list_image'])) {
                $galleryPaths = [];
                foreach ($data['list_image'] as $file) {
                    $galleryPaths[] = $file->store('products/gallery', 'public');
                }
                $data['list_image'] = $galleryPaths;
            }

            // 2. Create the main Product
            $product = Product::create($data);

            // 3. Check product type and create variants accordingly
            if ($data['product_type'] === 'simple') {
                // Create only ONE variant for a simple product
                $product->variants()->create([
                    'price'          => $data['price'],
                    'discount_price' => $data['discount_price'] ?? null,
                    'stock'          => $data['stock'],
                ]);
            } elseif ($data['product_type'] === 'variable' && !empty($data['variants'])) {
                // Create MULTIPLE variants for a variable product
                foreach ($data['variants'] as $variantData) {
                    $variant = $product->variants()->create([
                        'price'          => $variantData['price'],
                        'discount_price' => $variantData['discount_price'] ?? null,
                        'stock'          => $variantData['stock'],
                    ]);

                    if (!empty($variantData['attribute_value_ids'])) {
                        $valueIds = explode(',', $variantData['attribute_value_ids']);
                        $variant->attributeValues()->sync($valueIds);
                    }
                }
            }

            return $product;
        });
    }

    /**
     * Update an existing product with the given data.
     *
     * @param  Product  $product  The product to update.
     * @param  array  $data  The new data for the product.
     *
     * @return Product The updated product.
     * @throws \Exception|\Throwable If there is an error during the update process.
     */
    public function updateProduct(Product $product, array $data): Product
    {
        return DB::transaction(static function () use ($product, $data) {
            // 1. Handle main image update
            if (!empty($data['image'])) {
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
                    // Ensure we are working with an uploaded file object
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $galleryPaths[] = $file->store('products/gallery', 'public');
                    }
                }
                // Update the data array with the new paths
                $data['list_image'] = $galleryPaths;
            }
            // 2. Update the main Product details
            $product->update($data);

            // 3. Data Integrity Check: Ensure no variants are in existing orders before deleting them
            if ($product->variants()->whereHas('orderItems')->exists()) {
                throw new \RuntimeException("Cannot update variants because one or more are part of existing orders.");
            }

            // 4. Create a clean slate: Delete all old variants
            $product->variants()->delete();

            // 5. Re-create variants based on the new product type (using the same logic as create)
            if ($data['product_type'] === 'simple') {
                $product->variants()->create([
                    'price'          => $data['price'],
                    'discount_price' => $data['discount_price'] ?? null,
                    'stock'          => $data['stock'],
                ]);
            } elseif ($data['product_type'] === 'variable') {
                // Combine existing edited variants and newly generated variants
                $allVariantsData = array_merge($data['variants'] ?? [], $data['new_variants'] ?? []);
                if (empty($allVariantsData)) {
                    throw new \RuntimeException('A variable product must have at least one variant.');
                }

                foreach ($allVariantsData as $variantData) {
                    $variant = $product->variants()->create($variantData);
                    if (!empty($variantData['attribute_value_ids'])) {
                        $valueIds = explode(',', $variantData['attribute_value_ids']);
                        $variant->attributeValues()->sync($valueIds);
                    }
                }
            }

            return $product->fresh(['variants']);
        });
    }

    /**
     * Soft delete a product after checking data integrity.
     *
     * @param  Product  $product  The product to delete.
     *
     * @return bool
     * @throws \Exception
     */
    public function deleteProduct(Product $product): bool
    {
        // DATA INTEGRITY CHECK: Check if the product is part of any existing order items.
        // The orderItems() relationship must be defined in the Product model.
        if ($product->orderItems()->exists()) {
            // If it exists in orders, throw an exception with the specific error message.
            throw new \RuntimeException('Cannot delete product because it is linked to existing orders.');
        }

        // If the check passes, perform the soft delete.
        return $product->delete();
    }

}
