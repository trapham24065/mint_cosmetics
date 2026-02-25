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
        $product = $this->route('product');
        $productId = $product->id;
        $isSimple = $this->input('product_type') === 'simple';

        $rules = [
            'name'                  => ['required', 'string', 'max:255', Rule::unique('products')->ignore($productId)],
            'category_id'           => ['required', 'exists:categories,id'],
            'brand_id'              => ['nullable', 'exists:brands,id'],
            'description'           => ['nullable', 'string', 'max:65535'],
            'image'                 => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp', 'max:2048'],
            'list_image'            => ['nullable', 'array'],
            'list_image.*'          => ['sometimes', 'image', 'mimes:jpg,png,jpeg,webp', 'max:2048'],
            'active'                => ['sometimes', 'boolean'],
            'product_type'          => ['required', Rule::in(['simple', 'variable'])],
            'deleted_variants'      => ['nullable', 'array'],
            'deleted_variants.*'    => ['integer', 'exists:product_variants,id'],
            'selected_attributes'   => ['nullable', 'array'],
            'selected_attributes.*' => ['exists:attributes,id'],
        ];

        if ($isSimple) {
            // Rules cho Simple Product
            $rules['price'] = ['required', 'numeric', 'min:0'];
            $rules['discount_price'] = [
                'nullable',
                'numeric',
                'min:0',
                Rule::when($this->filled('price'), ['lte:price']),
            ];

            $currentVariant = $product->variants->first();
            $variantId = $currentVariant ? $currentVariant->id : null;

            $rules['sku'] = [
                'nullable',
                'string',
                'max:255',
                Rule::unique('product_variants', 'sku')->ignore($variantId),
            ];

            $rules['variants'] = ['prohibited'];
            $rules['new_variants'] = ['prohibited'];
        } else {
            // Rules cho Variable Product
            $rules['price'] = ['prohibited'];
            $rules['sku'] = ['prohibited'];

            // 1. Validate các biến thể CŨ (đang update)
            if ($this->has('variants') && is_array($this->input('variants'))) {
                foreach ($this->input('variants') as $index => $variantData) {
                    $rules["variants.{$index}.id"] = ['required', 'integer', 'exists:product_variants,id'];
                    $rules["variants.{$index}.price"] = ['required', 'numeric', 'min:0'];
                    $rules["variants.{$index}.discount_price"] = ['nullable', 'numeric', 'min:0'];
                    $rules["variants.{$index}.attributes"] = ['nullable', 'array'];

                    $currentVariantId = $variantData['id'] ?? null;

                    $rules["variants.{$index}.sku"] = [
                        'nullable',
                        'string',
                        'max:255',
                        'distinct',
                        Rule::unique('product_variants', 'sku')->ignore($currentVariantId),
                    ];
                }
            }

            if ($this->has('new_variants') && is_array($this->input('new_variants'))) {
                foreach ($this->input('new_variants') as $index => $variantData) {
                    $rules["new_variants.{$index}.price"] = ['required', 'numeric', 'min:0'];
                    $rules["new_variants.{$index}.discount_price"] = ['nullable', 'numeric', 'min:0'];
                    $rules["new_variants.{$index}.attribute_value_ids"] = ['required', 'string'];

                    $rules["new_variants.{$index}.sku"] = [
                        'nullable',
                        'string',
                        'max:255',
                        'distinct',
                        Rule::unique('product_variants', 'sku'),
                    ];
                }
            }
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'                                 => 'Please enter the product name.',
            'name.unique'                                   => 'This product name is already taken.',
            'category_id.required'                          => 'Please select a category.',
            'category_id.exists'                            => 'The selected category is invalid.',
            'brand_id.exists'                               => 'The selected brand is invalid.',
            'image.image'                                   => 'The uploaded file must be an image.',
            'image.mimes'                                   => 'The image must be a file of type: jpg, png, jpeg, webp.',
            'image.max'                                     => 'The image size cannot exceed 2MB.',
            'list_image.*.image'                            => 'One of the gallery files is not a valid image.',
            'list_image.*.mimes'                            => 'Gallery images must be of type: jpg, png, jpeg, webp.',
            'list_image.*.max'                              => 'Each gallery image size cannot exceed 2MB.',
            'product_type.required'                         => 'Please select the product type.',
            'product_type.in'                               => 'Invalid product type selected.',
            'deleted_variants.*.exists'                     => 'One of the variants marked for deletion is invalid.',

            // Simple Product Messages
            'price.required'                                => 'Please enter the product price.',
            'price.numeric'                                 => 'The product price must be a number.',
            'price.min'                                     => 'The product price cannot be negative.',
            'stock.required'                                => 'Please enter the stock quantity.',
            'stock.integer'                                 => 'The stock quantity must be an integer.',
            'stock.min'                                     => 'The stock quantity cannot be negative.',
            'discount_price.numeric'                        => 'The discount price must be a number.',
            'discount_price.min'                            => 'The discount price cannot be negative.',
            'discount_price.lte'                            => 'The discount price must be less than or equal to the original price.',
            'variants.prohibited'                           => 'Variants cannot be added to a simple product.',
            'new_variants.prohibited'                       => 'New variants cannot be added to a simple product.',

            // Variable Product Messages
            'price.prohibited'                              => 'Price cannot be set directly on a variable product.',
            'stock.prohibited'                              => 'Stock cannot be set directly on a variable product.',

            // Messages for fields inside EXISTING variants array (using *)
            'variants.*.id.required'                        => 'Variant ID is missing.',
            'variants.*.id.exists'                          => 'An invalid existing variant was submitted.',
            'variants.*.price.required'                     => 'The variant price is required.',
            'variants.*.price.numeric'                      => 'The variant price must be a number.',
            'variants.*.price.min'                          => 'The variant price cannot be negative.',
            'variants.*.stock.required'                     => 'The variant stock quantity is required.',
            'variants.*.stock.integer'                      => 'The variant stock quantity must be an integer.',
            'variants.*.stock.min'                          => 'The variant stock quantity cannot be negative.',
            'variants.*.discount_price.numeric'             => 'The variant discount price must be a number.',
            'variants.*.discount_price.min'                 => 'The variant discount price cannot be negative.',
            'variants.*.discount_price.lte'                 => 'The variant discount price must be less than or equal to its original price.',
            'variants.*.attribute_value_ids.required'       => 'Each variant must have attributes.',
            'variants.*.attribute_value_ids.array'          => 'Invalid attribute data format for the variant.',
            'variants.*.attribute_value_ids.min'            => 'Each variant must have at least one attribute.',
            'variants.*.attribute_value_ids.*.required'     => 'Attribute value cannot be empty.',
            'variants.*.attribute_value_ids.*.integer'      => 'Invalid attribute value ID.',
            'variants.*.attribute_value_ids.*.exists'       => 'One of the selected attribute values does not exist.',
            'variants.*.sku.unique'                         => 'The SKU for one of the variants is already in use.',

            // Messages for fields inside NEW variants array (using *)
            'new_variants.*.price.required'                 => 'The new variant price is required.',
            'new_variants.*.price.numeric'                  => 'The new variant price must be a number.',
            'new_variants.*.price.min'                      => 'The new variant price cannot be negative.',
            'new_variants.*.stock.required'                 => 'The new variant stock quantity is required.',
            'new_variants.*.stock.integer'                  => 'The new variant stock quantity must be an integer.',
            'new_variants.*.stock.min'                      => 'The new variant stock quantity cannot be negative.',
            'new_variants.*.discount_price.numeric'         => 'The new variant discount price must be a number.',
            'new_variants.*.discount_price.min'             => 'The new variant discount price cannot be negative.',
            'new_variants.*.discount_price.lte'             => 'The new variant discount price must be less than or equal to its original price.',
            'new_variants.*.attribute_value_ids.required'   => 'Each new variant must have attributes.',
            'new_variants.*.attribute_value_ids.array'      => 'Invalid attribute data format for the new variant.',
            'new_variants.*.attribute_value_ids.min'        => 'Each new variant must have at least one attribute.',
            'new_variants.*.attribute_value_ids.*.required' => 'Attribute value cannot be empty for new variant.',
            'new_variants.*.attribute_value_ids.*.integer'  => 'Invalid attribute value ID for new variant.',
            'new_variants.*.attribute_value_ids.*.exists'   => 'One of the selected attribute values for new variant does not exist.',
            'new_variants.*.sku.unique'                     => 'The SKU for one of the new variants is already in use.',
            'variants.*.sku.distinct'                       => 'Duplicate SKUs found in the variants list.',
            'sku.unique'                                    => 'The SKU has already been taken.',
            'new_variants.*.sku.distinct'                   => 'Duplicate SKUs found in the new variants list.',
         
        ];
    }

    /**
     * Get the index of the current variant being validated.
     * Needed for Rule::when inside variants array validation.
     *
     * @param  string  $arrayName  'variants' or 'new_variants'
     *
     * @return int|null
     */
    protected function getVariantIndex(string $arrayName): ?int
    {
        $validator = $this->getValidatorInstance();
        if (!$validator || !method_exists($validator, 'currentRule')) {
            return null;
        }
        $currentRule = $validator->currentRule();
        if (!is_string($currentRule)) {
            return null;
        }

        // Example: 'variants.0.discount_price' or 'new_variants.1.price'
        $keys = explode('.', $currentRule);

        // Check if the first part matches the array name we're interested in
        if (isset($keys[0], $keys[1]) && $keys[0] === $arrayName && is_numeric($keys[1])) {
            return (int)$keys[1];
        }

        return null;
    }

    /**
     * Get the ID of the current variant being validated (for unique rule).
     *
     * @param  string  $arrayName  'variants'
     *
     * @return int|null
     */
    protected function getVariantIdBeingValidated(string $arrayName): ?int
    {
        $index = $this->getVariantIndex($arrayName);
        if ($index === null) {
            return null;
        }
        // Access the input data submitted for this variant
        $variantData = $this->input("{$arrayName}.{$index}");
        return isset($variantData['id']) ? (int)$variantData['id'] : null;
    }

}
