<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 4/17/2026
 */

namespace App\View\Composers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class HeaderComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view): void
    {
        // Build category tree for megamenu
        $categories = Category::query()
            ->where('active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'parent_id']);

        $categoryTree = $this->buildCategoryTreeWithCounts($categories);

        $view->with([
            'categoryTree' => $categoryTree,
        ]);
    }

    /**
     * Build category tree with product counts.
     *
     * @param Collection $categories
     * @return Collection
     */
    private function buildCategoryTreeWithCounts(Collection $categories): Collection
    {
        $categoriesByParent = $categories->groupBy(static fn(Category $category) => $category->parent_id ?? 0);

        $productCountsByCategory = Product::query()
            ->where('active', true)
            ->selectRaw('category_id, COUNT(*) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        $attachChildren = function (Category $category) use (&$attachChildren, $categoriesByParent): void {
            $children = ($categoriesByParent->get($category->id, collect()))->values();

            $children->each(function (Category $child) use (&$attachChildren): void {
                $attachChildren($child);
            });

            $category->setRelation('children', $children);
        };

        $calculateSubtreeCount = function (Category $category) use (&$calculateSubtreeCount, $productCountsByCategory): int {
            $count = (int)($productCountsByCategory[$category->id] ?? 0);

            foreach ($category->children as $child) {
                $count += $calculateSubtreeCount($child);
            }

            $category->setAttribute('subtree_products_count', $count);

            return $count;
        };

        $rootCategories = ($categoriesByParent->get(0, collect()))->values();

        $rootCategories->each(function (Category $category) use (&$attachChildren): void {
            $attachChildren($category);
        });

        $rootCategories->each(function (Category $category) use (&$calculateSubtreeCount): void {
            $calculateSubtreeCount($category);
        });

        return $rootCategories;
    }
}
