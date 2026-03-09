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
            'sku'                            => [
                'nullable', // Cho phép để trống (để tự sinh)
                'string',
                'max:255',
                'unique:product_variants,sku', // Phải là duy nhất trong bảng product_variants
            ],
            //            'stock'                          => [
            //                Rule::requiredIf($this->input('product_type') === 'simple'),
            //                'nullable',
            //                'integer',
            //                'min:0',
            //            ],
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
            // ✅ FIX: Thêm validation cho SKU của variable product
            'variants.*.sku'                 => [
                'nullable',
                'string',
                'max:255',
                'distinct', // Không được trùng nhau trong cùng 1 request
                Rule::unique('product_variants', 'sku'),
            ],
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
            'name.required'                             => 'Vui lòng nhập tên sản phẩm.',
            'category_id.required'                      => 'Vui lòng chọn danh mục.',
            'category_id.exists'                        => 'Danh mục đã chọn không hợp lệ.',
            'brand_id.exists'                           => 'Thương hiệu đã chọn không hợp lệ.',
            'image.image'                               => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes'                               => 'Hình ảnh phải có định dạng: jpg, png, jpeg, webp.',
            'image.max'                                 => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'list_image.*.image'                        => 'Một trong các tệp trong thư viện ảnh không phải là hình ảnh hợp lệ.',
            'list_image.*.mimes'                        => 'Ảnh trong thư viện phải có định dạng: jpg, png, jpeg, webp.',
            'list_image.*.max'                          => 'Mỗi ảnh trong thư viện không được vượt quá 2MB.',
            'product_type.required'                     => 'Vui lòng chọn loại sản phẩm.',
            'product_type.in'                           => 'Loại sản phẩm đã chọn không hợp lệ.',

            // Simple Product Messages
            'price.required'                            => 'Vui lòng nhập giá sản phẩm.',
            'price.numeric'                             => 'Giá sản phẩm phải là một số.',
            'price.min'                                 => 'Giá sản phẩm không được nhỏ hơn 0.',
            'stock.required'                            => 'Vui lòng nhập số lượng tồn kho.',
            'stock.integer'                             => 'Số lượng tồn kho phải là số nguyên.',
            'stock.min'                                 => 'Số lượng tồn kho không được nhỏ hơn 0.',
            'discount_price.numeric'                    => 'Giá giảm phải là một số.',
            'discount_price.min'                        => 'Giá giảm không được nhỏ hơn 0.',
            'discount_price.lte'                        => 'Giá giảm phải nhỏ hơn hoặc bằng giá gốc.',

            // Variable Product Messages
            'variants.required'                         => 'Sản phẩm biến thể phải có ít nhất một biến thể.',
            'variants.array'                            => 'Định dạng dữ liệu biến thể không hợp lệ.',
            'variants.min'                              => 'Sản phẩm biến thể phải có ít nhất một biến thể.',

            // Messages for fields inside variants array
            'variants.*.price.required'                 => 'Giá của biến thể là bắt buộc.',
            'variants.*.price.numeric'                  => 'Giá của biến thể phải là một số.',
            'variants.*.price.min'                      => 'Giá của biến thể không được nhỏ hơn 0.',
            'variants.*.stock.required'                 => 'Số lượng tồn kho của biến thể là bắt buộc.',
            'variants.*.stock.integer'                  => 'Số lượng tồn kho của biến thể phải là số nguyên.',
            'variants.*.stock.min'                      => 'Số lượng tồn kho của biến thể không được nhỏ hơn 0.',
            'variants.*.discount_price.numeric'         => 'Giá giảm của biến thể phải là một số.',
            'variants.*.discount_price.min'             => 'Giá giảm của biến thể không được nhỏ hơn 0.',
            'variants.*.discount_price.lte'             => 'Giá giảm của biến thể phải nhỏ hơn hoặc bằng giá gốc của nó.',
            'variants.*.attribute_value_ids.required'   => 'Mỗi biến thể phải có ít nhất một thuộc tính.',
            'variants.*.attribute_value_ids.array'      => 'Định dạng dữ liệu thuộc tính của biến thể không hợp lệ.',
            'variants.*.attribute_value_ids.min'        => 'Mỗi biến thể phải có ít nhất một thuộc tính.',
            'variants.*.attribute_value_ids.*.required' => 'Giá trị thuộc tính không được để trống.',
            'variants.*.attribute_value_ids.*.integer'  => 'Giá trị thuộc tính không hợp lệ.',
            'variants.*.attribute_value_ids.*.exists'   => 'Một trong các giá trị thuộc tính đã chọn không tồn tại.',
            'variants.*.sku.unique'                     => 'Một trong các SKU của biến thể đã tồn tại.',
            'variants.*.sku.distinct'                   => 'Phát hiện SKU bị trùng trong danh sách biến thể.',
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
