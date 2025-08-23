<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{

    /**
     * Display a listing of the categories.
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
     */
    public function create(): View
    {
        $attributes = Attribute::all();

        // FIXED: Return the 'create' view
        return view('admin.management.categories.create', compact('attributes'));
    }

    /**
     * Store a newly created category in storage.
     */
    /**
     * Store a newly created category in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        // The request is already validated by StoreCategoryRequest
        $validatedData = $request->validated();

        // Create the category first
        $category = Category::create($validatedData);

        // Attach attributes if they are provided
        if (isset($validatedData['attribute_ids'])) {
            $category->productAttributes()->sync($validatedData['attribute_ids']);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): View
    {
        // FIXED: Return the 'edit' view and pass the category data
        return view('admin.management.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

}
