<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/23/2025
 * @time 8:14 PM
 */

declare(strict_types=1);

namespace App\Http\Requests\Categories;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
        // $this->category is the category instance from the route
        $categoryId = $this->route('category')->id;
        $parentId = $this->input('parent_id');

        return [
            'name'            => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
                    ->where(static function ($query) use ($parentId) {
                        $query->where('parent_id', $parentId);
                    })
                    ->ignore($categoryId),
            ],
            'image'           => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp', 'max:1024'],
            'remove_current_image' => ['sometimes', 'boolean'],
            'parent_id'       => [
                'nullable',
                'integer',
                'exists:categories,id',
                Rule::notIn([$categoryId]),
                function (string $attribute, mixed $value, \Closure $fail) use ($categoryId): void {
                    if ($value === null || $value === '') {
                        return;
                    }

                    $category = Category::query()->find($categoryId);
                    if (!$category) {
                        return;
                    }

                    $descendantIds = $category->getDescendantIds();
                    if (in_array((int)$value, $descendantIds, true)) {
                        $fail('Không thể chọn danh mục con làm danh mục cha.');
                    }
                },
            ],
            'active'          => ['sometimes', 'boolean'],
            'attribute_ids'   => ['nullable', 'array'],
            'attribute_ids.*' => ['exists:attributes,id'],
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
            'name.unique'            => 'Tên danh mục này đã tồn tại trong cùng cấp.',
            'image.image'            => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes'            => 'Hình ảnh phải có định dạng: jpg, png, jpeg, webp.',
            'image.max'              => 'Kích thước hình ảnh không được vượt quá 1MB.',
            'parent_id.integer'      => 'Danh mục cha không hợp lệ.',
            'parent_id.exists'       => 'Danh mục cha không tồn tại.',
            'parent_id.not_in'       => 'Không thể chọn chính danh mục hiện tại làm danh mục cha.',
            'active.boolean'         => 'Trạng thái hoạt động phải là đúng hoặc sai.',
            'attribute_ids.array'    => 'Dữ liệu thuộc tính liên kết không hợp lệ.',
            'attribute_ids.*.exists' => 'Một trong các thuộc tính đã chọn không hợp lệ.',
        ];
    }
}
