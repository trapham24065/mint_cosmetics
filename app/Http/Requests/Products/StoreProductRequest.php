<?php

declare(strict_types=1);

namespace App\Http\Requests\Products;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{

    protected function prepareForValidation(): void
    {
        $variants = $this->input('variants', []);

        $normalizedVariants = [];
        if (is_array($variants)) {
            foreach ($variants as $variant) {
                if (!is_array($variant)) {
                    continue;
                }

                $hasAttributeValues = !empty($variant['attribute_value_ids']);
                $hasPrice = isset($variant['price']) && $variant['price'] !== '';
                $hasSku = isset($variant['sku']) && trim((string)$variant['sku']) !== '';

                if ($hasAttributeValues || $hasPrice || $hasSku) {
                    $normalizedVariants[] = $variant;
                }
            }
        }

        $incomingType = (string)$this->input('product_type', 'simple');
        $hasSimplePayload = $this->filled('price')
            || $this->filled('sku')
            || $this->filled('discount_price')
            || $this->filled('discount_percentage');

        if ($incomingType === 'variable' && empty($normalizedVariants) && $hasSimplePayload) {
            $incomingType = 'simple';
        }

        if (is_array($normalizedVariants)) {
            foreach ($normalizedVariants as $index => $variant) {
                if (!is_array($variant)) {
                    continue;
                }

                $percentage = $variant['discount_percentage'] ?? null;
                if ($percentage === null || $percentage === '') {
                    $normalizedVariants[$index]['discount_percentage'] = 0;
                }
            }
        }

        $simpleDiscountPercentage = $this->input('discount_percentage');

        $this->merge([
            'product_type' => $incomingType,
            'discount_percentage' => ($simpleDiscountPercentage === null || $simpleDiscountPercentage === '') ? 0 : $simpleDiscountPercentage,
            'variants' => $normalizedVariants,
        ]);
    }

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
        $isSimple = $this->input('product_type') === 'simple';

        $rules = [
            // --- General Product Rules ---
            'name'         => ['required', 'string', 'max:255'],
            'category_id'  => [
                'required',
                'exists:categories,id',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($value === null || $value === '') {
                        return;
                    }

                    $hasChildren = Category::query()->where('parent_id', (int)$value)->exists();
                    if ($hasChildren) {
                        $fail('Vui lòng chọn danh mục con cuối cùng để tạo sản phẩm.');
                    }
                },
            ],
            'brand_id'     => ['nullable', 'exists:brands,id'],
            'description'  => 'nullable|string|max:65535',
            'image'        => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp', 'max:2048'],
            'list_image'   => ['nullable', 'string', 'json'],
            'active'       => ['sometimes', 'boolean'],
            'product_type' => ['required', Rule::in(['simple', 'variable'])],

            // --- Rules for Simple Product ---
            'price'        => [
                Rule::requiredIf($this->input('product_type') === 'simple'),
                'nullable',
                'numeric',
                'min:0',
            ],
            'sku'          => [
                'nullable', // Cho phép để trống (để tự sinh)
                'string',
                'max:255',
                'unique:product_variants,sku', // Phải là duy nhất trong bảng product_variants
            ],

            'discount_price'                 => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'discount_percentage'            => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];

        if ($isSimple) {
            // Khi là sản phẩm đơn giản, bỏ validate biến thể để tránh false-positive.
            $rules['variants'] = ['prohibited'];
        } else {
            $rules['variants'] = ['required', 'array', 'min:1'];
            $rules['variants.*.price'] = ['required', 'numeric', 'min:0'];
            $rules['variants.*.discount_price'] = ['nullable', 'numeric', 'min:0', 'lte:variants.*.price'];
            $rules['variants.*.discount_percentage'] = ['nullable', 'numeric', 'min:0', 'max:100'];
            $rules['variants.*.attribute_value_ids'] = ['required', 'string'];
            $rules['variants.*.sku'] = [
                'required',
                'string',
                'max:255',
                'distinct',
                Rule::unique('product_variants', 'sku'),
            ];
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
            'name.required'                             => 'Vui lòng nhập tên sản phẩm.',
            'category_id.required'                      => 'Vui lòng chọn danh mục.',
            'category_id.exists'                        => 'Danh mục đã chọn không hợp lệ.',
            'brand_id.exists'                           => 'Thương hiệu đã chọn không hợp lệ.',
            'image.image'                               => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes'                               => 'Hình ảnh phải có định dạng: jpg, png, jpeg, webp.',
            'image.max'                                 => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'list_image.json'                           => 'Danh sách ảnh thư viện không đúng định dạng JSON.',
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
            'discount_percentage.numeric'               => 'Tỷ lệ chiết khấu phải là một số.',
            'discount_percentage.min'                   => 'Tỷ lệ chiết khấu không được nhỏ hơn 0%.',
            'discount_percentage.max'                   => 'Tỷ lệ chiết khấu không được lớn hơn 100%.',
            'sku.unique'                                => 'SKU này đã tồn tại, vui lòng nhập SKU khác.',
            'sku.max'                                   => 'SKU không được vượt quá 255 ký tự.',
            'sku.string'                                => 'SKU phải là chuỗi ký tự.',

            // Variable Product Messages
            'variants.required'                         => 'Sản phẩm biến thể phải có ít nhất một biến thể.',
            'variants.array'                            => 'Định dạng dữ liệu biến thể không hợp lệ.',
            'variants.min'                              => 'Sản phẩm biến thể phải có ít nhất một biến thể.',
            'variants.prohibited'                       => 'Sản phẩm đơn giản không được chứa dữ liệu biến thể.',
            'variants.*.sku.required'                   => 'Vui lòng nhập SKU cho biến thể.',

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
            'variants.*.discount_percentage.numeric'    => 'Tỷ lệ chiết khấu của biến thể phải là một số.',
            'variants.*.discount_percentage.min'        => 'Tỷ lệ chiết khấu của biến thể không được nhỏ hơn 0%.',
            'variants.*.discount_percentage.max'        => 'Tỷ lệ chiết khấu của biến thể không được lớn hơn 100%.',
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
}
