<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/25/2025
 * @time 4:56 PM
 */

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\StoreCategoryRequest;
use App\Http\Requests\Categories\UpdateCategoryRequest;
use App\Models\Attribute;
use App\Models\Category;
use App\Services\Admin\CategoryService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CategoryController extends Controller
{

    /**
     * The service for handling category logic.
     */
    protected CategoryService $categoryService;

    /**
     * Inject the service.
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the categories.
     * (No business logic here, so it remains unchanged)
     */
    public function index(): View
    {
        $categories = Category::latest()->paginate(10);
        $totalCategories = Category::count();
        $latestCategories = Category::latest()->take(4)->get();

        return view(
            'admin.management.categories.index',
            compact(
                'categories',
                'totalCategories',
                'latestCategories'
            )
        );
    }

    /**
     * Show the form for creating a new category.
     * (No business logic here, so it remains unchanged)
     */
    public function create(): View
    {
        $attributes = Attribute::all();
        // Get all categories with eager loaded parent chain
        $parentCategories = Category::with('parent.parent.parent.parent.parent')
            ->get()
            ->map(function ($category) {
                // Build and SET hierarchy name on model
                $name = $category->name;
                $parent = $category->parent;
                while ($parent) {
                    $name = $parent->name.' > '.$name;
                    $parent = $parent->parent;
                }
                $category->hierarchy_name = $name;
                return $category;
            })
            ->sortBy(function ($category) {
                return $category->hierarchy_name;
            })
            ->values();

        return view('admin.management.categories.create', compact('attributes', 'parentCategories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['active'] = $request->has('active'); // Prepare data for the service

            $this->categoryService->createCategory($data);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Danh mục đã được tạo thành công.');
        } catch (QueryException $e) {
            Log::error('Category Creation Failed: '.$e->getMessage());
            $message = $this->getQueryExceptionMessage($e);
            return back()->withInput()->with('error', $message);
        } catch (Exception $e) {
            Log::error('Category Creation Failed: '.$e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        $category->load('productAttributes');
        $productsCount = $category->products()->count();

        return view('admin.management.categories.show', compact('category', 'productsCount'));
    }

    /**
     * Show the form for editing the specified category.
     * (No business logic here, so it remains unchanged)
     */
    public function edit(Category $category): View
    {
        $attributes = Attribute::all();
        $selectedAttributeIds = $category->productAttributes()->pluck('attribute_id')->toArray();
        $excludedIds = array_merge([$category->id], $category->getDescendantIds());
        // Get all categories (excluding this category and its descendants) with eager loaded parent chain
        $parentCategories = Category::query()
            ->with('parent.parent.parent.parent.parent')
            ->whereNotIn('id', $excludedIds)
            ->get()
            ->map(function ($cat) {
                // Build and SET hierarchy name on model
                $name = $cat->name;
                $parent = $cat->parent;
                while ($parent) {
                    $name = $parent->name.' > '.$name;
                    $parent = $parent->parent;
                }
                $cat->hierarchy_name = $name;
                return $cat;
            })
            ->sortBy(function ($cat) {
                return $cat->hierarchy_name;
            })
            ->values();

        return view(
            'admin.management.categories.edit',
            compact(
                'category',
                'attributes',
                'selectedAttributeIds',
                'parentCategories'
            )
        );
    }

    /**
     * Update the specified category in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['active'] = $request->has('active'); // Prepare data for the service

            $this->categoryService->updateCategory($category, $data);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Danh mục đã được cập nhật thành công.');
        } catch (QueryException $e) {
            Log::error('Category Update Failed: '.$e->getMessage());
            $message = $this->getQueryExceptionMessage($e);
            return back()->withInput()->with('error', $message);
        } catch (Exception $e) {
            Log::error('Category Update Failed: '.$e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified category from storage.
     */

    public function destroy(Category $category): RedirectResponse|JsonResponse
    {
        try {
            $this->categoryService->deleteCategory($category);
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Danh mục đã được xóa thành công.',
                ]);
            }

            return redirect()->route('admin.categories.index')
                ->with('success', 'Danh mục đã được xóa thành công.');
        } catch (\Exception $e) {
            Log::error('Category Deletion Failed: '.$e->getMessage());

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete category: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->route('admin.categories.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Get attributes for a specific category.
     * This is used for an AJAX request from the product create/edit form.
     */
    public function getAttributes(Category $category): JsonResponse
    {
        // Attributes are inherited from the selected category and its ancestors.
        $category->load('parent');
        $sourceCategoryIds = $category->getAncestorsAndSelfIds();

        $attributes = Attribute::query()
            ->whereHas('categories', function ($query) use ($sourceCategoryIds) {
                $query->whereIn('categories.id', $sourceCategoryIds);
            })
            ->with('values')
            ->distinct()
            ->get();

        return response()->json($attributes);
    }

    /**
     * Provide data for the Grid.js table via AJAX.
     */
    public function getDataForGrid(): JsonResponse
    {
        $orders = Category::latest()->get();

        // Format data for Grid.js
        $data = $orders->map(function ($order) {
            return [
                'id'         => $order->id,
                'image'      => $order->image,
                'name'       => $order->name,
                'slug'       => $order->slug,
                'is_active'  => $order->active,
                'created_at' => $order->created_at->format('d/m/Y'),
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
    }

    public function getProductsDataForGrid(Category $category): JsonResponse
    {
        $products = $category->products()->latest()->get();

        $data = $products->map(function ($product) {
            return [
                'id'       => $product->id,
                'image'    => $product->image,
                'name'     => $product->name,
                'active'   => (bool)$product->active,
                'show_url' => route('admin.products.show', $product),
            ];
        });

        return response()->json(['data' => $data]);
    }

    /**
     * Handle bulk actions for categories.
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'action'         => ['required', 'string', 'in:change_status'],
            'category_ids'   => ['required', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'value'          => ['required'],
        ]);

        try {
            $count = $this->categoryService->bulkUpdate(
                $validated['action'],
                $validated['category_ids'],
                $validated['value']
            );
            return response()->json(['success' => true, 'message' => "{$count} danh mục đã được cập nhật thành công."]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Đã xảy ra lỗi.'], 500);
        }
    }

}
