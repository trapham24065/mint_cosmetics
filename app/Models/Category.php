<?php

/**
 * @project mint_cosmetics
 *
 * @author PhamTra
 *
 * @email trapham24065@gmail.com
 *
 * @date 8/22/2025
 *
 * @time 3:24 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{

    protected $fillable = [
        'name',
        'slug',
        'image',
        'parent_id',
        'active',
    ];

    protected $casts = [
        'parent_id' => 'integer',
        'active'    => 'boolean',
    ];

    protected $appends = [
        'hierarchy_name',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function scopeLeaf(Builder $query): Builder
    {
        return $query->whereDoesntHave('children');
    }

    public function getAncestorsAndSelfIds(): array
    {
        $ids = [];
        $current = $this;

        while ($current) {
            if (in_array($current->id, $ids, true)) {
                break;
            }

            $ids[] = $current->id;
            $current = $current->parent;
        }

        return $ids;
    }

    public function getDescendantIds(): array
    {
        $descendantIds = [];
        $queue = $this->children()->pluck('id')->all();

        while (!empty($queue)) {
            $currentId = array_shift($queue);
            if ($currentId === null || in_array($currentId, $descendantIds, true)) {
                continue;
            }

            $descendantIds[] = $currentId;

            $childIds = self::query()
                ->where('parent_id', $currentId)
                ->pluck('id')
                ->all();

            foreach ($childIds as $childId) {
                if (!in_array($childId, $descendantIds, true)) {
                    $queue[] = $childId;
                }
            }
        }

        return $descendantIds;
    }

    public function products(): HasMany|Category
    {
        return $this->hasMany(Product::class);
    }

    /**
     * A category can have multiple product attributes.
     */
    public function productAttributes(): BelongsToMany
    {
        return $this->belongsToMany(
            Attribute::class,
            'attribute_category',
            'category_id',
            'attribute_id'
        );
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(static function (Category $category) {
            $category->slug = self::generateUniqueSlug($category->name);
        });

        static::updating(static function (Category $category) {
            if ($category->isDirty('name')) {
                $category->slug = self::generateUniqueSlug($category->name, $category->id);
            }
        });
    }

    protected static function generateUniqueSlug(string $name, ?int $exceptId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (self::query()->where('slug', $slug)->when($exceptId, function ($query) use ($exceptId) {
            return $query->where('id', '!=', $exceptId);
        })->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    public function getHierarchyNameAttribute()
    {
        $name = $this->name;
        $parent = $this->parent;

        while ($parent) {
            $name = $parent->name.' > '.$name;
            $parent = $parent->parent;
        }

        return $name;
    }

}
