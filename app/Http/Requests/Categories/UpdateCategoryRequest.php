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
            'active'          => ['sometimes', 'boolean'],
            'attribute_ids'   => ['nullable', 'array'],
            'attribute_ids.*' => ['exists:attributes,id'],
        ];
    }

}
