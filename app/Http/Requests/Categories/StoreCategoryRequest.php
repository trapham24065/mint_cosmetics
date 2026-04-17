<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/22/2025
 * @time 3:24 PM
 */

declare(strict_types=1);

namespace App\Http\Requests\Categories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
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
        $parentId = $this->input('parent_id');

        return [
            'name'            => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->where(static function ($query) use ($parentId) {
                    $query->where('parent_id', $parentId);
                }),
            ],
            'image'           => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp', 'max:1024'],
            'parent_id'       => ['nullable', 'integer', 'exists:categories,id'],
            'active'          => ['sometimes', 'boolean'],
            'attribute_ids'   => ['nullable', 'array'],
            'attribute_ids.*' => ['exists:attributes,id'], // Ensure every selected ID is a valid attribute
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
            'name.required'          => 'Vui lòng nhập tên danh mục.',
            'name.string'            => 'Tên danh mục phải là văn bản.',
            'name.max'               => 'Tên danh mục không được vượt quá 255 ký tự.',
            'name.unique'            => 'Tên danh mục đã tồn tại trong cùng cấp.',
            'image.image'            => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes'            => 'Hình ảnh phải có định dạng: jpg, png, jpeg, webp.',
            'image.max'              => 'Kích thước hình ảnh không được vượt quá 1MB.',
            'parent_id.integer'      => 'Danh mục cha không hợp lệ.',
            'parent_id.exists'       => 'Danh mục cha không tồn tại.',
            'active.boolean'         => 'Trạng thái hoạt động không hợp lệ.',
            'attribute_ids.array'    => 'Danh sách thuộc tính không hợp lệ.',
            'attribute_ids.*.exists' => 'Một trong các thuộc tính đã chọn không tồn tại.',
        ];
    }
}
