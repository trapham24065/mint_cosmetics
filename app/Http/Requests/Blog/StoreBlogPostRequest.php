<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlogPostRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255', 'unique:blog_posts,title'],
            'content'      => 'nullable|string|max:65535',
            'image'        => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp', 'max:2048'],
            'is_published' => ['sometimes', 'boolean'],
            // Add rules for slug if you allow it on creation (usually not needed)
            // 'slug'         => ['nullable', 'string', 'max:255', 'unique:blog_posts,slug'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required'   => 'Vui lòng nhập tiêu đề bài viết.',
            'title.unique'     => 'Tiêu đề bài viết đã tồn tại.',
            'content.required' => 'Vui lòng nhập nội dung bài viết.',
            'image.image'      => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes'      => 'Hình ảnh phải thuộc loại: jpg, png, jpeg, webp.',
            'image.max'        => 'Kích thước hình ảnh không được vượt quá 2MB.',
        ];
    }

}
