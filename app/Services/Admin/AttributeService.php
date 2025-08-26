<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/26/2025
 * @time 7:40 PM
 */
declare(strict_types=1);
namespace App\Services\Admin;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\DB;

class AttributeService
{

    /**
     * Create a new attribute along with its values.
     *
     * @param  array  $data  The validated data from the request.
     *
     * @return Attribute The newly created attribute instance.
     * @throws \Exception|\Throwable
     */
    public function createAttribute(array $data): Attribute
    {
        return DB::transaction(function () use ($data) {
            $attribute = Attribute::create(['name' => $data['name']]);

            if (!empty($data['values'])) {
                $valuesToCreate = [];
                foreach ($data['values'] as $value) {
                    if (!empty($value)) {
                        $valuesToCreate[] = ['value' => $value];
                    }
                }
                if (!empty($valuesToCreate)) {
                    $attribute->values()->createMany($valuesToCreate);
                }
            }
            return $attribute;
        });
    }

    /**
     * Update an existing attribute and its values.
     *
     * @param  Attribute  $attribute  The attribute instance to update.
     * @param  array  $data  The validated data from the request.
     *
     * @return Attribute The updated attribute instance.
     * @throws \Exception|\Throwable
     */
    public function updateAttribute(Attribute $attribute, array $data): Attribute
    {
        // Data Integrity Check for deleting values
        $originalValueIds = $attribute->values()->pluck('id')->toArray();
        $submittedValueIds = array_keys($data['values'] ?? []);
        $valueIdsToDelete = array_diff($originalValueIds, $submittedValueIds);

        if (!empty($valueIdsToDelete)) {
            $inUseCount = DB::table('attribute_value_product_variant')
                ->whereIn('attribute_value_id', $valueIdsToDelete)
                ->count();

            if ($inUseCount > 0) {
                // Throw an exception that the controller can catch
                throw new \RuntimeException("Cannot delete one or more values as they are in use by products.");
            }
        }

        // Proceed with the update inside a transaction
        return DB::transaction(function () use ($attribute, $data, $valueIdsToDelete) {
            $attribute->update(['name' => $data['name']]);

            // Update existing values
            if (!empty($data['values'])) {
                foreach ($data['values'] as $id => $valueText) {
                    AttributeValue::where('id', $id)
                        ->where('attribute_id', $attribute->id)
                        ->update(['value' => $valueText]);
                }
            }

            // Delete removed values
            if (!empty($valueIdsToDelete)) {
                AttributeValue::whereIn('id', $valueIdsToDelete)->delete();
            }

            // Create new values
            if (!empty($data['new_values'])) {
                $valuesToCreate = [];
                foreach ($data['new_values'] as $value) {
                    if (!empty($value)) {
                        $valuesToCreate[] = ['value' => $value];
                    }
                }
                if (!empty($valuesToCreate)) {
                    $attribute->values()->createMany($valuesToCreate);
                }
            }

            return $attribute;
        });
    }

    /**
     * Delete an attribute after checking for data integrity.
     *
     * @param  Attribute  $attribute  The attribute to delete.
     *
     * @return bool
     * @throws \Exception
     */
    public function deleteAttribute(Attribute $attribute): bool
    {
        if ($attribute->categories()->exists()) {
            throw new \RuntimeException("Cannot delete '{$attribute->name}'. It is linked to one or more categories.");
        }

        if ($attribute->values()->whereHas('productVariants')->exists()) {
            throw new \RuntimeException(
                "Cannot delete '{$attribute->name}'. Its values are in use by one or more products."
            );
        }

        return $attribute->delete();
    }

}
