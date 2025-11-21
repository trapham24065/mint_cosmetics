<?php

declare(strict_types=1);
namespace App\Http\Requests\Products;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            // --- General Product Rules ---
            'name'                           => ['required', 'string', 'max:255'],
            'category_id'                    => ['required', 'exists:categories,id'],
            'brand_id'                       => ['nullable', 'exists:brands,id'],
            'description'                    => 'nullable|string|max:65535',
            'image'                          => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp', 'max:2048'],
            'list_image'                     => ['nullable', 'array'],
            'list_image.*'                   => ['image', 'mimes:jpg,png,jpeg,webp', 'max:2048'],
            'active'                         => ['sometimes', 'boolean'],
            'product_type'                   => ['required', Rule::in(['simple', 'variable'])],

            // --- Rules for Simple Product ---
            'price'                          => [
                Rule::requiredIf($this->input('product_type') === 'simple'),
                'nullable',
                'numeric',
                'min:0',
            ],
            'stock'                          => [
                Rule::requiredIf($this->input('product_type') === 'simple'),
                'nullable',
                'integer',
                'min:0',
            ],
            'discount_price'                 => ['nullable', 'numeric', 'min:0', 'lte:price'],

            // --- Rules for Variable Product ---
            'variants'                       => [
                Rule::requiredIf($this->input('product_type') === 'variable'),
                'nullable',
                'array',
                'min:1',
            ],
            'variants.*.price'               => ['required', 'numeric', 'min:0'],
            'variants.*.stock'               => ['required', 'integer', 'min:0'],
            'variants.*.discount_price'      => ['nullable', 'numeric', 'min:0'],
            'variants.*.attribute_value_ids' => ['required', 'string'],
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
            'name.required'                             => 'Please enter the product name.',
            'category_id.required'                      => 'Please select a category.',
            'category_id.exists'                        => 'The selected category is invalid.',
            'brand_id.exists'                           => 'The selected brand is invalid.',
            'image.image'                               => 'The uploaded file must be an image.',
            'image.mimes'                               => 'The image must be a file of type: jpg, png, jpeg, webp.',
            'image.max'                                 => 'The image size cannot exceed 2MB.',
            'list_image.*.image'                        => 'One of the gallery files is not a valid image.',
            'list_image.*.mimes'                        => 'Gallery images must be of type: jpg, png, jpeg, webp.',
            'list_image.*.max'                          => 'Each gallery image size cannot exceed 2MB.',
            'product_type.required'                     => 'Please select the product type.',
            'product_type.in'                           => 'Invalid product type selected.',

            // Simple Product Messages
            'price.required'                            => 'Please enter the product price.',
            'price.numeric'                             => 'The product price must be a number.',
            'price.min'                                 => 'The product price cannot be negative.',
            'stock.required'                            => 'Please enter the stock quantity.',
            'stock.integer'                             => 'The stock quantity must be an integer.',
            'stock.min'                                 => 'The stock quantity cannot be negative.',
            'discount_price.numeric'                    => 'The discount price must be a number.',
            'discount_price.min'                        => 'The discount price cannot be negative.',
            'discount_price.lte'                        => 'The discount price must be less than or equal to the original price.',

            // Variable Product Messages
            'variants.required'                         => 'A variable product must have at least one variant.',
            'variants.array'                            => 'Invalid variant data format.',
            'variants.min'                              => 'A variable product must have at least one variant.',

            // Messages for fields inside variants array (using *)
            'variants.*.price.required'                 => 'The variant price is required.',
            'variants.*.price.numeric'                  => 'The variant price must be a number.',
            'variants.*.price.min'                      => 'The variant price cannot be negative.',
            'variants.*.stock.required'                 => 'The variant stock quantity is required.',
            'variants.*.stock.integer'                  => 'The variant stock quantity must be an integer.',
            'variants.*.stock.min'                      => 'The variant stock quantity cannot be negative.',
            'variants.*.discount_price.numeric'         => 'The variant discount price must be a number.',
            'variants.*.discount_price.min'             => 'The variant discount price cannot be negative.',
            'variants.*.discount_price.lte'             => 'The variant discount price must be less than or equal to its original price.',
            'variants.*.attribute_value_ids.required'   => 'Each variant must have at least one attribute.',
            'variants.*.attribute_value_ids.array'      => 'Invalid attribute data format for the variant.',
            'variants.*.attribute_value_ids.min'        => 'Each variant must have at least one attribute.',
            'variants.*.attribute_value_ids.*.required' => 'Attribute value cannot be empty.',
            'variants.*.attribute_value_ids.*.integer'  => 'Invalid attribute value.',
            'variants.*.attribute_value_ids.*.exists'   => 'One of the selected attribute values does not exist.',
        ];
    }

    /**
     * Get the index of the current variant being validated.
     * Helper function needed for Rule::when inside variants array.
     * Note: This relies on internal validation structure and might need adjustment
     * if Laravel changes how it provides context for array validation.
     */
    protected function getVariantIndex(): ?int
    {
        // Try to safely get the current rule being validated
        $validator = $this->getValidatorInstance();
        if (!$validator || !method_exists($validator, 'currentRule')) {
            return null;
        }
        $currentRule = $validator->currentRule();
        if (!is_string($currentRule)) {
            return null;
        }

        $keys = explode('.', $currentRule);
        // Example: 'variants.0.discount_price' -> keys[1] would be '0'
        return isset($keys[1]) && is_numeric($keys[1]) ? (int)$keys[1] : null;
    }

}
