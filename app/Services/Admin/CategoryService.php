<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/26/2025
 * @time 8:03 PM
 */
declare(strict_types=1);
namespace App\Services\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryService
{

    /**
     * Create a new category and sync its attributes.
     */
    public function createCategory(array $data): Category
    {
        return DB::transaction(function () use ($data) {
            if (!empty($data['image'])) {
                $data['image'] = $data['image']->store('categories', 'public');
            }
            $category = Category::create($data);

            if (isset($data['attribute_ids'])) {
                $category->productAttributes()->sync($data['attribute_ids']);
            }

            return $category;
        });
    }

    /**
     * Update an existing category and its attributes after performing integrity checks.
     *
     * @throws \Exception
     */
    public function updateCategory(Category $category, array $data): Category
    {
        $newAttributeIds = $data['attribute_ids'] ?? [];
        $currentAttributeIds = $category->productAttributes()->pluck('attribute_id')->toArray();
        $attributesToBeRemoved = array_diff($currentAttributeIds, $newAttributeIds);

        if (!empty($attributesToBeRemoved)) {
            $conflictCount = Product::where('category_id', $category->id)
                ->whereHas('variants.attributeValues.attribute', function ($query) use ($attributesToBeRemoved) {
                    $query->whereIn('attributes.id', $attributesToBeRemoved);
                })
                ->count();

            if ($conflictCount > 0) {
                throw new \RuntimeException(
                    "Cannot remove some attributes because {$conflictCount} products in this category are currently using them."
                );
            }
        }
        if (!empty($data['image'])) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $data['image']->store('categories', 'public');
        }

        $category->update($data);
        $category->productAttributes()->sync($newAttributeIds);

        return $category;
    }

    /**
     * Delete a category after checking for data integrity.
     *
     * @throws \Exception
     */
    public function deleteCategory(Category $category): bool
    {
        if ($category->products()->exists()) {
            throw new \RuntimeException("Cannot delete '{$category->name}'. It is assigned to products.");
        }
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        return $category->delete();
    }

}
