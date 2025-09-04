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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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
        return view('admin.management.categories.create', compact('attributes'));
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
                ->with('success', 'Category created successfully.');
        } catch (Exception $e) {
            Log::error('Category Creation Failed: '.$e->getMessage());
            return back()->withInput()->with('error', 'Failed to create category.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        $category->load('productAttributes');

        $products = $category->products()->latest()->paginate(10);

        return view('admin.management.categories.show', compact('category', 'products'));
    }

    /**
     * Show the form for editing the specified category.
     * (No business logic here, so it remains unchanged)
     */
    public function edit(Category $category): View
    {
        $attributes = Attribute::all();
        $selectedAttributeIds = $category->productAttributes()->pluck('attribute_id')->toArray();

        return view(
            'admin.management.categories.edit',
            compact(
                'category',
                'attributes',
                'selectedAttributeIds'
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
                ->with('success', 'Category updated successfully.');
        } catch (Exception $e) {
            Log::error('Category Update Failed: '.$e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        try {
            $this->categoryService->deleteCategory($category);
            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully.');
        } catch (Exception $e) {
            Log::error('Category Deletion Failed: '.$e->getMessage());
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
        // Eager-load the values for each attribute
        $attributes = $category->productAttributes()->with('values')->get();

        return response()->json($attributes);
    }

}
