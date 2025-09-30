<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/26/2025
 * @time 11:54 PM
 */
declare(strict_types=1);
namespace App\Http\Controllers\Admin;

use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Models\Product;
use App\Services\Admin\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class ProductController
{

    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        return view('admin.management.products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Fetch data needed for form dropdowns
        $categories = Category::where('active', true)->get();
        $brands = Brand::where('is_active', true)->get();

        return view('admin.management.products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['active'] = $request->has('active');

            $this->productService->createProduct($data);

            return redirect()->route('admin.products.index')
                ->with('success', ' Product created successfully.');
        } catch (\Exception $e) {
            Log::error('Product Creation Failed: '.$e->getMessage());
            return back()->withInput()
                ->with('error', ' Failed to create product. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        $product->load([
            'category',
            'brand',
            'variants.attributeValues.attribute',
        ]);

        return view('admin.management.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        // Eager-load all necessary data for the form
        $product->load(['variants.attributeValues']);

        $categories = Category::where('active', true)->get();
        $brands = Brand::where('is_active', true)->get();

        // Get all attributes available for the product's current category
        $allAttributesForCategory = $product->category->productAttributes()->with('values')->get();

        // Get a flat array of all attribute value IDs used by this product's variants
        $selectedAttributeValueIds = $product->variants->flatMap(function ($variant) {
            return $variant->attributeValues->pluck('id');
        })->unique()->toArray();

        return view(
            'admin.management.products.edit',
            compact(
                'product',
                'categories',
                'brands',
                'allAttributesForCategory',
                'selectedAttributeValueIds'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['active'] = $request->has('active');

            $this->productService->updateProduct($product, $data);

            return redirect()->route('admin.products.index')
                ->with('success', ' Product updated successfully.');
        } catch (\Exception $e) {
            Log::error('Product Update Failed: '.$e->getMessage());
            return back()->withInput()->with('error', 'Failed to update product.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        try {
            $this->productService->deleteProduct($product);

            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Product Deletion Failed: '.$e->getMessage());

            return redirect()->route('admin.products.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Provide data for the Grid.js table via AJAX.
     */
    public function getDataForGrid(Request $request): JsonResponse
    {
        $query = Product::query()->with(['category', 'variants']);

        $paginatedProducts = $query->latest()->get();

        $data = $paginatedProducts->map(function ($product) {
            $firstVariant = $product->variants->first();
            return [
                'id'        => $product->id,
                'image'     => $product->image,
                'name'      => $product->name,
                'price'     => $firstVariant->price ?? null,
                'stock'     => $product->variants->sum('stock'),
                'category'  => $product->category->name ?? 'N/A',
                'is_active' => $product->active,
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Handle bulk actions for products.
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'action'        => ['required', 'string', 'in:change_status'],
            'product_ids'   => ['required', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],
            'value'         => ['required'],
        ]);

        try {
            $count = $this->productService->bulkUpdate(
                $validated['action'],
                $validated['product_ids'],
                $validated['value']
            );
            return response()->json(['success' => true, 'message' => "Successfully updated {$count} products."]);
        } catch (\Exception $e) {
            Log::error('Bulk Product Update Failed: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

}
