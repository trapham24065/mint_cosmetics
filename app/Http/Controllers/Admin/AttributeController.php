<?php
/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/24/2025
 * @time 5:05 PM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attributes\StoreAttributeRequest;
use App\Http\Requests\Attributes\UpdateAttributeRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{

    /**
     * Display a listing of the attribute.
     */
    public function index(): View
    {
        $attributes = Attribute::with('values')->latest()->paginate(10);
        return view('admin.management.attributes.index', compact('attributes'));
    }

    /**
     * Display the specified attribute with its related data.
     */
    public function show(Attribute $attribute): View
    {
        $attribute->load('categories', 'values');

        $productIds = Product::whereHas('variants.attributeValues', function ($query) use ($attribute) {
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
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();

            // Create the parent attribute
            $attribute = Attribute::create(['name' => $validatedData['name']]);

            // Create attribute values if they exist
            if (!empty($validatedData['values'])) {
                $valuesToCreate = [];
                foreach ($validatedData['values'] as $value) {
                    // Only add non-empty values
                    if (!empty($value)) {
                        $valuesToCreate[] = ['value' => $value];
                    }
                }
                if (!empty($valuesToCreate)) {
                    $attribute->values()->createMany($valuesToCreate);
                }
            }

            DB::commit();

            return redirect()->route('admin.attributes.index')
                ->with('success', 'Attribute and its values created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            // You can log the error here if needed: Log::error($e->getMessage());
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
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();

            $attribute->update(['name' => $validatedData['name']]);

            $submittedValueIds = array_keys($validatedData['values'] ?? []);

            if (!empty($validatedData['values'])) {
                foreach ($validatedData['values'] as $id => $valueText) {
                    AttributeValue::where('id', $id)
                        ->where('attribute_id', $attribute->id) // Security check
                        ->update(['value' => $valueText]);
                }
            }

            $originalValueIds = $attribute->values()->pluck('id')->toArray();
            $valueIdsToDelete = array_diff($originalValueIds, $submittedValueIds);

            if (!empty($valueIdsToDelete)) {
                $inUseCount = DB::table('attribute_value_product_variant')
                    ->whereIn('attribute_value_id', $valueIdsToDelete)
                    ->count();

                if ($inUseCount > 0) {
                    DB::rollBack();
                    return back()->withInput()
                        ->with('error', "Cannot delete one or more values as they are currently in use by products.");
                }

                AttributeValue::whereIn('id', $valueIdsToDelete)->delete();
            }

            if (!empty($validatedData['new_values'])) {
                $valuesToCreate = [];
                foreach ($validatedData['new_values'] as $value) {
                    if (!empty($value)) {
                        $valuesToCreate[] = ['value' => $value];
                    }
                }
                if (!empty($valuesToCreate)) {
                    $attribute->values()->createMany($valuesToCreate);
                }
            }

            DB::commit();

            return redirect()->route('admin.attributes.index')
                ->with('success', 'Attribute updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update attribute. Please try again.');
        }
    }

    /**
     * Remove the specified attribute from storage.
     */
    public function destroy(Attribute $attribute): RedirectResponse
    {
        if ($attribute->categories()->exists()) {
            return redirect()->route('admin.attributes.index')
                ->with('error', "Cannot delete '{$attribute->name}'. It is linked to one or more categories.");
        }

        if ($attribute->values()->whereHas('productVariants')->exists()) {
            return redirect()->route('admin.attributes.index')
                ->with('error', "Cannot delete '{$attribute->name}'. Its values are in use by one or more products.");
        }

        $attribute->delete();

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute deleted successfully.');
    }

}
