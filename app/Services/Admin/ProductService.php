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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            if ($data['product_type'] === 'simple') {
                $product->variants()->create([
                    'price'          => $data['price'],
                    'discount_price' => $data['discount_price'] ?? null,
                    'stock'          => $data['stock'],
                ]);
            } elseif ($data['product_type'] === 'variable' && !empty($data['variants'])) {
                foreach ($data['variants'] as $variantData) {
                    $variant = $product->variants()->create($variantData);
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
        return DB::transaction(function () use ($product, $data) {
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
                    if ($file instanceof UploadedFile) {
                        $galleryPaths[] = $file->store('products/gallery', 'public');
                    }
                }
                // Update the data array with the new paths
                $data['list_image'] = $galleryPaths;
            }

            // 3. Update basic product information (always allowed)
            $basicFields = ['name', 'description', 'category_id', 'brand_id', 'active', 'image', 'list_image'];
            $basicData = array_intersect_key($data, array_flip($basicFields));
            $product->update($basicData);

            // 4. Handle variants update with a smart integrity check
            $hasOrderedVariants = $product->variants()->whereHas('orderItems')->exists();

            if (!$hasOrderedVariants) {
                // No variants in orders - full update allowed
                $product->variants()->delete();
                $this->createVariants($product, $data);
            } else {
                // Some variants in orders - selective update
                $this->updateVariantsSelectively($product, $data);
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

    /**
     * Selectively update variants when some are in orders
     */
    public function updateVariantsSelectively(Product $product, array $data): void
    {
        $existingVariants = $product->variants;
        $variantsInOrders = $existingVariants->filter(function ($variant) {
            return $variant->orderItems()->exists();
        });

        if ($data['product_type'] === 'simple') {
            // For simple products, just update the existing variant
            $variant = $existingVariants->first();
            if ($variant) {
                // Allow updating price, stock, discount_price for existing variants
                $variant->update([
                    'price'          => $data['price'],
                    'discount_price' => $data['discount_price'] ?? null,
                    'stock'          => $data['stock'],
                ]);
            }
        } elseif ($data['product_type'] === 'variable') {
            $allVariantsData = array_merge($data['variants'] ?? [], $data['new_variants'] ?? []);

            // Delete only variants that are NOT in any orders
            $variantsNotInOrders = $existingVariants->reject(function ($variant) {
                return $variant->orderItems()->exists();
            });

            foreach ($variantsNotInOrders as $variant) {
                $variant->delete();
            }

            // Update existing variants that are in orders (only safe fields)
            foreach ($variantsInOrders as $existingVariant) {
                // Find matching variant data by ID if provided
                $matchingData = collect($allVariantsData)->first(function ($variantData) use ($existingVariant) {
                    return isset($variantData['id']) && $variantData['id'] === $existingVariant->id;
                });

                if ($matchingData) {
                    // Only update safe fields that don't affect order integrity
                    $safeFields = ['stock', 'price', 'discount_price'];
                    $safeData = array_intersect_key($matchingData, array_flip($safeFields));
                    $existingVariant->update($safeData);
                }
            }

            // Create new variants that don't have IDs
            $newVariantsData = collect($allVariantsData)->filter(function ($variantData) {
                return !isset($variantData['id']);
            });

            foreach ($newVariantsData as $variantData) {
                $variant = $product->variants()->create($variantData);
                if (!empty($variantData['attribute_value_ids'])) {
                    $valueIds = explode(',', $variantData['attribute_value_ids']);
                    $variant->attributeValues()->sync($valueIds);
                }
            }
        }
    }

    /**
     * Create variants for a product based on type
     */
    public function createVariants(Product $product, array $data): void
    {
        if ($data['product_type'] === 'simple') {
            $product->variants()->create([
                'price'          => $data['price'],
                'discount_price' => $data['discount_price'] ?? null,
                'stock'          => $data['stock'],
            ]);
        } elseif ($data['product_type'] === 'variable') {
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
    }

}
