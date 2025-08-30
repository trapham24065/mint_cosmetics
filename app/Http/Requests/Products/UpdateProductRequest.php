<?php

declare(strict_types=1);
namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
        $productId = $this->route('product')->id;
        $isSimple = $this->input('product_type') === 'simple';

        // Base rules that apply to all types
        $rules = [
            'name'               => ['required', 'string', 'max:255', Rule::unique('products')->ignore($productId)],
            'category_id'        => ['required', 'exists:categories,id'],
            'brand_id'           => ['nullable', 'exists:brands,id'],
            'description'        => ['nullable', 'string', 'max:5000'],
            'image'              => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp', 'max:2048'],
            'list_image'         => ['nullable', 'array'],
            'list_image.*'       => ['image', 'mimes:jpg,png,jpeg,webp', 'max:2048'],
            'active'             => ['sometimes', 'boolean'],
            'product_type'       => ['required', Rule::in(['simple', 'variable'])],
            'deleted_variants'   => ['nullable', 'array'],
            'deleted_variants.*' => ['integer', 'exists:product_variants,id'],
        ];

        if ($isSimple) {
            // Rules specific to a simple product
            $rules['price'] = ['required', 'numeric', 'min:0'];
            $rules['stock'] = ['required', 'integer', 'min:0'];
            $rules['discount_price'] = ['nullable', 'numeric', 'min:0', 'lt:price'];
            // Forbid variable fields if the type is simple
            $rules['variants'] = ['prohibited'];
            $rules['new_variants'] = ['prohibited'];
        } else {
            // Rules specific to a variable product
            // Forbid simple fields if the type is variable
            $rules['price'] = ['prohibited'];
            $rules['stock'] = ['prohibited'];

            $rules['variants'] = ['nullable', 'array'];
            $rules['variants.*.price'] = ['required', 'numeric', 'min:0'];
            $rules['variants.*.stock'] = ['required', 'integer', 'min:0'];
            $rules['variants.*.discount_price'] = ['nullable', 'numeric', 'min:0'];

            $rules['new_variants'] = ['nullable', 'array'];
            $rules['new_variants.*.price'] = ['required', 'numeric', 'min:0'];
            $rules['new_variants.*.stock'] = ['required', 'integer', 'min:0'];
            $rules['new_variants.*.discount_price'] = ['nullable', 'numeric', 'min:0'];
            $rules['new_variants.*.attribute_value_ids'] = ['required', 'string'];
        }

        return $rules;
    }

}
