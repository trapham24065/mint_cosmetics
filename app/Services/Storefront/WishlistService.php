<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/11/2025
 * @time 11:11 PM
 */
declare(strict_types=1);
namespace App\Services\Storefront;

use App\Models\Product;
use Illuminate\Support\Collection;

class WishlistService
{

    public function getIds(): array
    {
        return session()->get('wishlist', []);
    }

    public function getContents(): Collection
    {
        $productIds = $this->getIds();
        if (empty($productIds)) {
            return collect();
        }
        return Product::whereIn('id', $productIds)->with('variants')->get();
    }

    public function toggle(int $productId): array
    {
        $wishlist = $this->getIds();
        $status = '';
        $addedItem = null;

        if (in_array($productId, $wishlist, true)) {
            // If exists, remove it
            $wishlist = array_diff($wishlist, [$productId]);
            $status = 'removed';
        } else {
            // If not, add it
            $wishlist[] = $productId;
            $status = 'added';

            $product = Product::find($productId);
            if ($product) {
                $addedItem = [
                    'product_name' => $product->name,
                    'slug'         => $product->slug,
                    'image'        => $product->image,
                ];
            }
        }

        session()->put('wishlist', array_values($wishlist));

        return [
            'success'   => true,
            'status'    => $status,
            'count'     => count($wishlist),
            'addedItem' => $addedItem,
        ];
    }

}
