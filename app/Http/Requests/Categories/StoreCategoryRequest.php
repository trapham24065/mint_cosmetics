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
        return [
            'name'            => ['required', 'string', 'max:255', 'unique:categories,name'],
            'active'          => ['sometimes', 'boolean'],
            'attribute_ids'   => ['nullable', 'array'],
            'attribute_ids.*' => ['exists:attributes,id'], // Ensure every selected ID is a valid attribute
        ];
    }

}
