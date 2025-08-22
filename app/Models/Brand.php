<?php
/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/22/2025
 * @time 3:24 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{

    use HasFactory;

    protected $fillable = ['name', 'slug', 'logo', 'is_active'];

    /**
     * A brand can have many products.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

}
