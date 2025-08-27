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

        return [

            'name'                               => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->ignore($productId),
            ],
            'category_id'                        => ['required', 'exists:categories,id'],
            'brand_id'                           => ['nullable', 'exists:brands,id'],
            'description'                        => ['nullable', 'string', 'max:5000'],
            'image'                              => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp', 'max:2048'],
            'list_image'                         => ['nullable', 'array'],
            'list_image.*'                       => ['image', 'mimes:jpg,png,jpeg,webp', 'max:2048'],
            'active'                             => ['sometimes', 'boolean'],
            'product_type'                       => ['required', Rule::in(['simple', 'variable'])],
            // Simple
            'price'                              => [
                Rule::requiredIf($this->input('product_type') === 'simple'),
                'nullable',
                'numeric',
                'min:0',
            ],
            'stock'                              => [
                Rule::requiredIf($this->input('product_type') === 'simple'),
                'nullable',
                'integer',
                'min:0',
            ],
            'discount_price'                     => ['nullable', 'numeric', 'min:0', 'lt:price'],
            // Variable
            'variants'                           => ['nullable', 'array'], // Existing variants
            'variants.*.price'                   => ['required', 'numeric', 'min:0'],
            'variants.*.stock'                   => ['required', 'integer', 'min:0'],
            'new_variants'                       => ['nullable', 'array'], // New variants
            'new_variants.*.price'               => ['required', 'numeric', 'min:0'],
            'new_variants.*.discount_price'      => ['required', 'numeric', 'min:0'],
            'new_variants.*.stock'               => ['required', 'integer', 'min:0'],
            'new_variants.*.attribute_value_ids' => ['required', 'string'],
            'deleted_variants'                   => ['nullable', 'array'], // IDs of variants to delete
            'deleted_variants.*'                 => ['integer', 'exists:product_variants,id'],
        ];
    }

}
