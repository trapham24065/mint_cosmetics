<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/24/2025
 * @time 5:05 PM
 */
declare(strict_types=1);
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attributes\StoreAttributeRequest;
use App\Http\Requests\Attributes\UpdateAttributeRequest;
use App\Models\Attribute;
use App\Models\Product;
use App\Services\Admin\AttributeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AttributeController extends Controller
{

    protected AttributeService $attributeService;

    public function __construct(AttributeService $attributeService)
    {
        $this->attributeService = $attributeService;
    }

    /**
     * Display a listing of the attribute.
     */
    public function index(): View
    {
        return view('admin.management.attributes.index');
    }

    /**
     * Display the specified attribute with its related data.
     */
    public function show(Attribute $attribute): View
    {
        $attribute->load('categories', 'values');

        $productIds = Product::whereHas('variants.attributeValues', static function ($query) use ($attribute) {
            $query->where('attribute_id', $attribute->id);
        })->pluck('id');

        $products = Product::whereIn('id', $productIds)->with('category')->paginate(10);

        return view('admin.management.attributes.show', compact('attribute', 'products'));
    }

    /**
     * Show the form for creating a new attribute.
     */
    public function create(): View
    {
        return view('admin.management.attributes.create');
    }

    /**
     * Store a newly created attribute in storage.
     */
    public function store(StoreAttributeRequest $request): RedirectResponse
    {
        try {
            $this->attributeService->createAttribute($request->validated());

            return redirect()->route('admin.attributes.index')
                ->with('success', 'Attribute and its values created successfully.');
        } catch (\Exception $e) {
            Log::error('Attribute Creation Failed: '.$e->getMessage());

            return back()->withInput()->with('error', 'Failed to create attribute. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified attribute.
     */
    public function edit(Attribute $attribute): View
    {
        // Eager-load values to display in the form
        $attribute->load('values');

        return view('admin.management.attributes.edit', compact('attribute'));
    }

    /**
     * Update the specified attribute in storage.
     */
    public function update(UpdateAttributeRequest $request, Attribute $attribute): RedirectResponse
    {
        try {
            $this->attributeService->updateAttribute($attribute, $request->validated());

            return redirect()->route('admin.attributes.index')
                ->with('success', 'Attribute updated successfully.');
        } catch (\Exception $e) {
            Log::error('Attribute Update Failed: '.$e->getMessage());
            // The service throws the specific error message
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified attribute from storage.
     */

    public function destroy(Attribute $attribute): RedirectResponse|JsonResponse
    {
        try {
            $this->attributeService->deleteAttribute($attribute);
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Attribute deleted successfully.',
                ]);
            }

            return redirect()->route('admin.attributes.index')
                ->with('success', 'Attribute deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Attribute Deletion Failed: '.$e->getMessage());

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete attribute: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->route('admin.attributes.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Provide data for the Grid.js table via AJAX.
     */
    public function getDataForGrid(): JsonResponse
    {
        $attributes = Attribute::with('values')->latest()->get();

        // Format data for Grid.js
        $data = $attributes->map(function ($attribute) {
            return [
                'id'         => $attribute->id,
                'name'       => $attribute->name,
                'values'     => $attribute->values->pluck('value')->implode(', '),
                'created_at' => $attribute->created_at->format('d M, Y'),
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
    }

}
