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
use Illuminate\Support\Facades\Storage;

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

            $data['images'] = !empty($request->input('list_image'))
                ? json_decode($request->input('list_image'), true)
                : [];

            if ($data['product_type'] === 'simple') {
                // Đảm bảo sản phẩm đơn giản có stock bằng 0
                $data['stock'] = 0;
            } else {
                // Nếu là variable, duyệt qua các biến thể và gán stock = 0
                if (isset($data['variants']) && is_array($data['variants'])) {
                    foreach ($data['variants'] as $key => $variant) {
                        $data['variants'][$key]['stock'] = 0;
                    }
                }
            }

            $this->productService->createProduct($data);

            return redirect()->route('admin.products.index')
                ->with('success', ' Sản phẩm đã được tạo thành công.');
        } catch (QueryException $e) {
            $message = $this->getQueryExceptionMessage($e);
            return back()->withInput()->with('error', $message);
        } catch (\Exception $e) {
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
        $product->load(['variants.attributeValues', 'images']);

        $categories = Category::where('active', true)->get();
        $brands = Brand::where('is_active', true)->get();

        // Get all attributes available for the product's current category
        $allAttributesForCategory = $product->category->productAttributes()->with('values')->get();

        // Get a flat array of all attribute value IDs used by this product's variants
        $selectedAttributeValueIds = $product->variants->flatMap(function ($variant) {
            return $variant->attributeValues->pluck('id');
        })->unique()->toArray();

        $galleryImages = [];

        if ($product->images->isNotEmpty()) {
            $galleryImages = $product->images->pluck('path')->filter()->values()->toArray();
        } elseif (!empty($product->list_image)) {
            if (is_array($product->list_image)) {
                $galleryImages = array_values(array_filter($product->list_image));
            } else {
                $decoded = json_decode((string)$product->list_image, true);
                if (is_array($decoded)) {
                    $galleryImages = array_values(array_filter($decoded));
                } elseif (is_string($product->list_image)) {
                    $galleryImages = [(string)$product->list_image];
                }
            }
        }

        $galleryImages = collect($galleryImages)
            ->filter(fn($path) => is_string($path) && trim($path) !== '')
            ->map(function (string $path) {
                $normalized = str_replace('\\', '/', trim($path));
                return preg_replace('#^/?storage/#', '', $normalized) ?? $normalized;
            })
            ->unique()
            ->values()
            ->toArray();

        $galleryImageMeta = collect($galleryImages)->map(function (string $path) {
            $name = basename($path);
            $size = 0;

            if (Storage::disk('public')->exists($path)) {
                $size = (int)Storage::disk('public')->size($path);
            }

            return [
                'path' => $path,
                'name' => $name,
                'size' => $size,
            ];
        })->toArray();

        return view(
            'admin.management.products.edit',
            compact(
                'product',
                'categories',
                'brands',
                'allAttributesForCategory',
                'selectedAttributeValueIds',
                'galleryImages',
                'galleryImageMeta'
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
            $data['images'] = json_decode($request->input('list_image') ?? '[]', true);
            $logKeys = [
                'name',
                'category_id',
                'brand_id',
                'product_type',
                'variants',
                'new_variants',
                'deleted_variants',
                'sku',
                'price',
                'stock',
                'discount_price',
                'active',
            ];
            $logData = array_intersect_key($data, array_flip($logKeys));
            // Ensure we don't accidentally serialize UploadedFile objects; keep only scalars/arrays
            Log::info('Yêu cầu cập nhật sản phẩm quản trị', ['product_id' => $product->id, 'payload' => $logData]);

            $this->productService->updateProduct($product, $data);

            return redirect()->route('admin.products.index')
                ->with('success', ' Sản phẩm đã được cập nhật thành công..');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Let Laravel handle validation exceptions so they are returned as validation errors
            throw $e;
        } catch (QueryException $e) {
            $message = $this->getQueryExceptionMessage($e);
            return back()->withInput()->with('error', $message);
        } catch (\Exception $e) {
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
                    'message' => 'Sản phẩm đã được xóa thành công.',
                ]);
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được xóa thành công.');
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa sản phẩm: ' . $e->getMessage(),
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
            return response()->json(['success' => true, 'message' => "{$count} sản phẩm đã được cập nhật thành công."]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Đã xảy ra lỗi.'], 500);
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

        return response()->json(['error' => 'Tải lên thất bại'], 400);
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
                    ->implode(' / ') ?: 'Mặc định';

                return [
                    'id'   => $variant->id,
                    'text' => "{$variant->sku} - {$attrText}",
                ];
            });

        return response()->json([
            'results' => $variants->values(),
        ]);
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('products', 'public');

            return response()->json([
                'success' => true,
                'path'    => $path,
            ]);
        }

        return response()->json(['error' => 'Upload failed'], 400);
    }
}
