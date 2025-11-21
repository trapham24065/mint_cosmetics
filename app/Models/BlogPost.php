<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class BlogPost extends Model
{

    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'is_published',
        'published_at',
        // 'user_id',
        // 'blog_category_id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Optional: Automatically generate slug when title is set
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
                // Ensure slug uniqueness (simple version)
                $count = static::where('slug', 'LIKE', $post->slug.'%')->count();
                if ($count > 0) {
                    $post->slug .= '-'.($count + 1);
                }
            }
        });

        static::updating(static function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) { // Only update slug if it wasn't manually set
                $post->slug = Str::slug($post->title);
                $count = static::where('slug', 'LIKE', $post->slug.'%')->where('id', '!=', $post->id)->count();
                if ($count > 0) {
                    $post->slug .= '-'.($count + 1);
                }
            }
        });
    }

    // Optional: Relationship to author (Admin User model)
    // public function author()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }

    // Optional: Relationship to Blog Category model
    // public function category()
    // {
    //     return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    // }

}
