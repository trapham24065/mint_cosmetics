<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/28/2025
 * @time 5:05 PM
 */

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brands\StoreBrandRequest;
use App\Http\Requests\Brands\UpdateBrandRequest;
use App\Models\Brand;
use App\Services\Admin\BrandService;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class BrandController extends Controller
{

    protected BrandService $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function index(): View
    {
        return view('admin.management.brands.index');
    }

    public function create(): View
    {
        return view('admin.management.brands.create');
    }

    public function store(StoreBrandRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active');
            $this->brandService->createBrand($data);
            return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
        } catch (QueryException $e) {
            Log::error('Brand Creation Failed: ' . $e->getMessage());
            $message = $this->getQueryExceptionMessage($e);
            return back()->withInput()->with('error', $message);
        } catch (\Exception $e) {
            Log::error('Brand Creation Failed: ' . $e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(Brand $brand): View
    {
        return view('admin.management.brands.edit', compact('brand'));
    }

    public function update(UpdateBrandRequest $request, Brand $brand): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active');
            $this->brandService->updateBrand($brand, $data);
            return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
        } catch (QueryException $e) {
            Log::error('Brand Update Failed: ' . $e->getMessage());
            $message = $this->getQueryExceptionMessage($e);
            return back()->withInput()->with('error', $message);
        } catch (\Exception $e) {
            Log::error('Brand Update Failed: ' . $e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Brand $brand): RedirectResponse|JsonResponse
    {
        try {
            $this->brandService->deleteBrand($brand);
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Brand deleted successfully.',
                ]);
            }

            return redirect()->route('admin.brands.index')
                ->with('success', 'Brand deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Brand Deletion Failed: ' . $e->getMessage());

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete brand: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->route('admin.brands.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand): View
    {
        // Get paginated products for the given brand
        $products = $brand->products()->latest()->paginate(10);

        return view('admin.management.brands.show', compact('brand', 'products'));
    }

    /**
     * Provide data for the Grid.js table via AJAX.
     */
    public function getDataForGrid(): JsonResponse
    {
        $brands = Brand::latest()->get();

        // Format data for Grid.js
        $data = $brands->map(function ($brand) {
            return [
                'id'        => $brand->id,
                'logo'      => $brand->logo,
                'name'      => $brand->name,
                'slug'      => $brand->slug,
                'is_active' => $brand->is_active,
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
    }
}
