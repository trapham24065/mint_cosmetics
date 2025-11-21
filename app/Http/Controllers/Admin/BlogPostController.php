<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\StoreBlogPostRequest;
use App\Http\Requests\Blog\UpdateBlogPostRequest;
use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = BlogPost::latest()->paginate(15);
        return view('admin.management.blog.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.management.blog.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogPostRequest $request): RedirectResponse
    {
        // Validation is already done by StoreBlogPostRequest
        $validated = $request->validated();

        $validated['is_published'] = $request->has('is_published');
        if ($validated['is_published'] && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('blog_images', 'public');
        }

        BlogPost::create($validated);

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogPost $blogPost): RedirectResponse
    {
        // Usually, admin doesn't need a separate show view, redirect to edit or list.
        return redirect()->route('admin.blog-posts.edit', $blogPost);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogPost $blogPost)
    {
        return view('admin.management.blog.edit', compact('blogPost'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogPostRequest $request, BlogPost $blogPost): RedirectResponse
    {
        // Validation is already done by UpdateBlogPostRequest
        $validated = $request->validated();

        $validated['is_published'] = $request->has('is_published');
        // Update published_at only if it's being published now and wasn't published before
        if ($validated['is_published'] && !$blogPost->is_published) {
            $validated['published_at'] = now();
        } elseif (!$validated['is_published']) {
            $validated['published_at'] = null;
        }

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($blogPost->image) {
                Storage::disk('public')->delete($blogPost->image);
            }
            $validated['image'] = $request->file('image')->store('blog_images', 'public');
        }

        if (!empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['slug']);
        } else {
            if ($blogPost->isDirty('title') || empty($blogPost->slug)) {
                $validated['slug'] = Str::slug($validated['title']);
                $count = BlogPost::where('slug', 'LIKE', $validated['slug'].'%')->where(
                    'id',
                    '!=',
                    $blogPost->id
                )->count();
                if ($count > 0) {
                    $validated['slug'] .= '-'.($count + 1);
                }
            } else {
                unset($validated['slug']);
            }
        }

        $blogPost->update($validated);

        return redirect()->route('blog.index')->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        if ($blogPost->image) {
            Storage::disk('public')->delete($blogPost->image);
        }
        $blogPost->delete();
        return redirect()->route('blog.index')->with('success', 'Blog post deleted successfully.');
    }

    public function getDataForGrid(): JsonResponse
    {
        $posts = BlogPost::latest()->paginate(15);

        $data = $posts->map(function ($post) {
            return [
                'id'           => $post->id,
                'title'        => $post->title,
                'content'      => $post->content,
                'image'        => $post->image,
                'is_published' => $post->is_published,
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
    }

}
