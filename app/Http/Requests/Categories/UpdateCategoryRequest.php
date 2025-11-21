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

        return [
            'name'            => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($categoryId)],
            'image'           => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp', 'max:1024'],
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
            'name.required'          => 'Please enter the category name.',
            'name.string'            => 'The category name must be text.',
            'name.max'               => 'The category name cannot be longer than 255 characters.',
            'name.unique'            => 'This category name is already taken.',
            'image.image'            => 'The uploaded file must be an image.',
            'image.mimes'            => 'The image must be a file of type: jpg, png, jpeg, webp.',
            'image.max'              => 'The image size cannot exceed 1MB.',
            'active.boolean'         => 'The active status must be true or false.',
            'attribute_ids.array'    => 'The linked attributes data is invalid.',
            'attribute_ids.*.exists' => 'One of the selected attributes is invalid.',
        ];
    }

}
