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

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\Admin\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;

class ProductController extends Controller
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
        } catch (QueryException $e) {
            Log::error('Product Creation Failed', ['exception' => $e]);
            $message = $this->getQueryExceptionMessage($e);
            return back()->withInput()->with('error', $message);
        } catch (\Exception $e) {
            Log::error('Product Creation Failed', ['exception' => $e]);
            return back()->withInput()
                ->with('error', $e->getMessage());
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

            // Normalize active flag and log a safe subset of incoming payload for debugging
            $data['active'] = $request->has('active');

            $logKeys = ['name', 'category_id', 'brand_id', 'product_type', 'variants', 'new_variants', 'deleted_variants', 'sku', 'price', 'stock', 'discount_price', 'active'];
            $logData = array_intersect_key($data, array_flip($logKeys));
            // Ensure we don't accidentally serialize UploadedFile objects; keep only scalars/arrays
            Log::info('Admin Product Update Request', ['product_id' => $product->id, 'payload' => $logData]);

            $this->productService->updateProduct($product, $data);

            return redirect()->route('admin.products.index')
                ->with('success', ' Product updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Let Laravel handle validation exceptions so they are returned as validation errors
            throw $e;
        } catch (QueryException $e) {
            Log::error('Product Update Failed', ['exception' => $e]);
            $message = $this->getQueryExceptionMessage($e);
            return back()->withInput()->with('error', $message);
        } catch (\Exception $e) {
            Log::error('Product Update Failed', ['exception' => $e]);
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse|JsonResponse
    {
        try {
            $this->productService->deleteProduct($product);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product deleted successfully.',
                ]);
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Product Deletion Failed', ['exception' => $e]);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete product: ' . $e->getMessage(),
                ], 500);
            }

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
            Log::error('Bulk Product Update Failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    public function uploadTinyMCEImage(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('products/descriptions', $filename, 'public');

            return response()->json([
                'location' => asset('storage/' . $path),
            ]);
        }

        return response()->json(['error' => 'Upload failed'], 400);
    }

    /**
     * Search product variants by SKU (for AJAX autocomplete).
     * Returns JSON array suitable for Select2.
     */
    public function searchVariants(Request $request): JsonResponse
    {
        $query = $request->query('q', '');
        $limit = (int)$request->query('limit', 50);

        $variants = ProductVariant::query()
            ->with(['product', 'attributeValues.attribute'])
            ->where('sku', 'like', "%{$query}%")
            ->limit($limit)
            ->get()
            ->map(function ($variant) {
                $attrText = $variant->attributeValues
                    ->map(fn($val) => $val->value)
                    ->implode(' / ') ?: 'Default';

                return [
                    'id'   => $variant->id,
                    'text' => "{$variant->sku} - {$attrText}",
                ];
            });

        return response()->json([
            'results' => $variants->values(),
        ]);
    }
}
