<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/28/2025
 * @time 6:00 PM
 */
declare(strict_types=1);
namespace App\Services\Admin;

use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BrandService
{

    public function createBrand(array $data): Brand
    {
        return DB::transaction(function () use ($data) {
            if (!empty($data['logo'])) {
                $data['logo'] = $data['logo']->store('brands', 'public');
            }
            return Brand::create($data);
        });
    }

    public function updateBrand(Brand $brand, array $data): bool
    {
        return DB::transaction(function () use ($brand, $data) {
            if (!empty($data['logo'])) {
                if ($brand->logo) {
                    Storage::disk('public')->delete($brand->logo);
                }
                $data['logo'] = $data['logo']->store('brands', 'public');
            }
            return $brand->update($data);
        });
    }

    public function deleteBrand(Brand $brand): bool
    {
        if ($brand->products()->exists()) {
            throw new \Exception("Cannot delete '{$brand->name}' because it is linked to products.");
        }

        return DB::transaction(function () use ($brand) {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            return $brand->delete();
        });
    }

}
