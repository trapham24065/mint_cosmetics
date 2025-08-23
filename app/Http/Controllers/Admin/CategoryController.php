<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\StoreCategoryRequest;
use App\Http\Requests\Categories\UpdateCategoryRequest;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
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
        $validatedData['active'] = $request->has('active');
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
        // Fetch all available attributes for the select input
        $attributes = Attribute::all();

        // Get an array of IDs for the attributes already linked to this category
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
        $validatedData = $request->validated();
        $newAttributeIds = $validatedData['attribute_ids'] ?? [];

        // START: Add logic to check for in-use attributes before updating
        $currentAttributeIds = $category->productAttributes()->pluck('attribute_id')->toArray();
        $attributesToBeRemoved = array_diff($currentAttributeIds, $newAttributeIds);

        if (!empty($attributesToBeRemoved)) {
            $conflictingProductCount = Product::where('category_id', $category->id)
                ->whereHas('variants.attributeValues.attribute', function ($query) use ($attributesToBeRemoved) {
                    $query->whereIn('attributes.id', $attributesToBeRemoved);
                })
                ->count();

            if ($conflictingProductCount > 0) {
                return back()->withInput()->withErrors([
                    'attribute_ids' => "Cannot remove some attributes because {$conflictingProductCount} products in this category are currently using them.",
                ]);
            }
        }
        // END: Check logic
        $validatedData['active'] = $request->has('active');

        // If check passes, proceed with the update
        $category->update($validatedData);
        $category->productAttributes()->sync($newAttributeIds);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Check if the category has any associated products.
        if ($category->products()->count() > 0) {
            // If it does, prevent deletion and return with an error message.
            return redirect()->route('admin.categories.index')
                ->with('error', "Cannot delete '{$category->name}'. It is assigned to products.");
        }

        // If no products are associated, proceed with deletion.
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

}
