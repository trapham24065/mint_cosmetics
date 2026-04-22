<?php

declare(strict_types=1);

namespace App\Http\Requests\Products;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{

    protected function prepareForValidation(): void
    {
        $variants = $this->input('variants', []);
        $newVariants = $this->input('new_variants', []);
        $deletedVariants = $this->input('deleted_variants', []);

        $normalizedDeletedVariantIds = [];
        if (is_array($deletedVariants)) {
            $normalizedDeletedVariantIds = array_values(array_unique(array_map(
                static fn($id) => (int)$id,
                array_filter($deletedVariants, static fn($id) => is_numeric($id) && (int)$id > 0)
            )));
        }

        $normalizedVariants = [];
        if (is_array($variants)) {
            foreach ($variants as $variant) {
                if (!is_array($variant)) {
                    continue;
                }

                $hasId = !empty($variant['id']);
                $hasAttributes = !empty($variant['attributes']) || !empty($variant['attribute_value_ids']);
                $hasPrice = isset($variant['price']) && $variant['price'] !== '';
                $hasSku = isset($variant['sku']) && trim((string)$variant['sku']) !== '';

                $variantId = isset($variant['id']) && is_numeric($variant['id']) ? (int)$variant['id'] : null;
                if ($variantId !== null && in_array($variantId, $normalizedDeletedVariantIds, true)) {
                    continue;
                }

                if ($hasId || $hasAttributes || $hasPrice || $hasSku) {
                    $normalizedVariants[] = $variant;
                }
            }
        }

        $normalizedNewVariants = [];
        if (is_array($newVariants)) {
            foreach ($newVariants as $variant) {
                if (!is_array($variant)) {
                    continue;
                }

                $hasAttributes = !empty($variant['attributes']) || !empty($variant['attribute_value_ids']);
                $hasPrice = isset($variant['price']) && $variant['price'] !== '';
                $hasSku = isset($variant['sku']) && trim((string)$variant['sku']) !== '';

                if ($hasAttributes || $hasPrice || $hasSku) {
                    $normalizedNewVariants[] = $variant;
                }
            }
        }

        $incomingType = (string)$this->input('product_type', 'simple');
        $hasSimplePayload = $this->filled('price')
            || $this->filled('sku')
            || $this->filled('discount_price')
            || $this->filled('discount_percentage');

        if (
            $incomingType === 'variable'
            && empty($normalizedVariants)
            && empty($normalizedNewVariants)
            && $hasSimplePayload
        ) {
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

        if (is_array($normalizedNewVariants)) {
            foreach ($normalizedNewVariants as $index => $variant) {
                if (!is_array($variant)) {
                    continue;
                }

                $percentage = $variant['discount_percentage'] ?? null;
                if ($percentage === null || $percentage === '') {
                    $normalizedNewVariants[$index]['discount_percentage'] = 0;
                }
            }
        }

        $simpleDiscountPercentage = $this->input('discount_percentage');

        $this->merge([
            'product_type' => $incomingType,
            'discount_percentage' => ($simpleDiscountPercentage === null || $simpleDiscountPercentage === '') ? 0 : $simpleDiscountPercentage,
            'variants' => $normalizedVariants,
            'new_variants' => $normalizedNewVariants,
            'deleted_variants' => $normalizedDeletedVariantIds,
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
        $product = $this->route('product');
        $productId = $product->id;
        $isSimple = $this->input('product_type') === 'simple';

        $rules = [
            'name'                  => ['required', 'string', 'max:255', Rule::unique('products')->ignore($productId)],
            'category_id'           => [
                'required',
                'exists:categories,id',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($value === null || $value === '') {
                        return;
                    }

                    $product = $this->route('product');
                    $currentCategoryId = $product ? (int)$product->category_id : null;
                    $selectedCategoryId = (int)$value;

                    // Cho phép giữ nguyên danh mục cũ (legacy) khi danh mục đó đã có danh mục con.
                    // Chỉ bắt buộc danh mục lá khi người dùng đổi sang danh mục khác.
                    if ($currentCategoryId !== null && $selectedCategoryId === $currentCategoryId) {
                        return;
                    }

                    $hasChildren = Category::query()->where('parent_id', $selectedCategoryId)->exists();
                    if ($hasChildren) {
                        $fail('Vui lòng chọn danh mục con cuối cùng để cập nhật sản phẩm.');
                    }
                },
            ],
            'brand_id'              => ['nullable', 'exists:brands,id'],
            'description'           => ['nullable', 'string', 'max:65535'],
            'image'                 => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp', 'max:2048'],
            'remove_current_image'  => ['sometimes', 'boolean'],
            'list_image'            => ['nullable', 'string', 'json'],
            'active'                => ['sometimes', 'boolean'],
            'product_type'          => ['required', Rule::in(['simple', 'variable'])],
            'deleted_variants'      => ['nullable', 'array'],
            'deleted_variants.*'    => [
                'integer',
                Rule::exists('product_variants', 'id')->where(static function ($query) use ($productId) {
                    $query->where('product_id', $productId);
                }),
            ],
            'selected_attributes'   => ['nullable', 'array'],
            'selected_attributes.*' => ['exists:attributes,id'],
        ];

        if ($isSimple) {
            // Rules cho Simple Product
            $rules['price'] = ['required', 'numeric', 'min:0'];
            $rules['discount_percentage'] = ['nullable', 'numeric', 'min:0', 'max:100'];
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
                    $rules["variants.{$index}.discount_price"] = ['nullable', 'numeric', 'min:0', "lte:variants.{$index}.price"];
                    $rules["variants.{$index}.discount_percentage"] = ['nullable', 'numeric', 'min:0', 'max:100'];
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
                    $rules["new_variants.{$index}.discount_price"] = ['nullable', 'numeric', 'min:0', "lte:new_variants.{$index}.price"];
                    $rules["new_variants.{$index}.discount_percentage"] = ['nullable', 'numeric', 'min:0', 'max:100'];
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

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if ($this->input('product_type') !== 'variable') {
                return;
            }

            $existingVariants = $this->input('variants', []);
            $newVariants = $this->input('new_variants', []);

            $existingCount = is_array($existingVariants) ? count($existingVariants) : 0;
            $newCount = is_array($newVariants) ? count($newVariants) : 0;

            if (($existingCount + $newCount) < 1) {
                $validator->errors()->add('variants', 'Sản phẩm biến thể phải có ít nhất một biến thể.');
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'                                 => 'Vui lòng nhập tên sản phẩm.',
            'name.unique'                                   => 'Tên sản phẩm này đã tồn tại.',
            'category_id.required'                          => 'Vui lòng chọn danh mục.',
            'category_id.exists'                            => 'Danh mục đã chọn không hợp lệ.',
            'brand_id.exists'                               => 'Thương hiệu đã chọn không hợp lệ.',
            'image.image'                                   => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes'                                   => 'Hình ảnh phải có định dạng: jpg, png, jpeg, webp.',
            'image.max'                                     => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'list_image.json'                               => 'Danh sách ảnh thư viện không đúng định dạng JSON.',
            'product_type.required'                         => 'Vui lòng chọn loại sản phẩm.',
            'product_type.in'                               => 'Loại sản phẩm đã chọn không hợp lệ.',
            'deleted_variants.*.exists'                     => 'Một trong các biến thể được đánh dấu xóa không hợp lệ.',

            // Simple Product Messages
            'price.required'                                => 'Vui lòng nhập giá sản phẩm.',
            'price.numeric'                                 => 'Giá sản phẩm phải là một số.',
            'price.min'                                     => 'Giá sản phẩm không được nhỏ hơn 0.',
            'stock.required'                                => 'Vui lòng nhập số lượng tồn kho.',
            'stock.integer'                                 => 'Số lượng tồn kho phải là số nguyên.',
            'stock.min'                                     => 'Số lượng tồn kho không được nhỏ hơn 0.',
            'discount_price.numeric'                        => 'Giá giảm phải là một số.',
            'discount_price.min'                            => 'Giá giảm không được nhỏ hơn 0.',
            'discount_price.lte'                            => 'Giá giảm phải nhỏ hơn hoặc bằng giá gốc.',
            'discount_percentage.numeric'                   => 'Tỷ lệ chiết khấu phải là một số.',
            'discount_percentage.min'                       => 'Tỷ lệ chiết khấu không được nhỏ hơn 0%.',
            'discount_percentage.max'                       => 'Tỷ lệ chiết khấu không được lớn hơn 100%.',
            'variants.prohibited'                           => 'Không thể thêm biến thể cho sản phẩm đơn.',
            'new_variants.prohibited'                       => 'Không thể thêm biến thể mới cho sản phẩm đơn.',

            // Variable Product Messages
            'price.prohibited'                              => 'Không thể đặt giá trực tiếp cho sản phẩm có biến thể.',
            'stock.prohibited'                              => 'Không thể đặt tồn kho trực tiếp cho sản phẩm có biến thể.',

            // Messages for fields inside EXISTING variants array
            'variants.*.id.required'                        => 'Thiếu ID của biến thể.',
            'variants.*.id.exists'                          => 'Biến thể hiện tại không hợp lệ.',
            'variants.*.price.required'                     => 'Giá của biến thể là bắt buộc.',
            'variants.*.price.numeric'                      => 'Giá của biến thể phải là một số.',
            'variants.*.price.min'                          => 'Giá của biến thể không được nhỏ hơn 0.',
            'variants.*.stock.required'                     => 'Số lượng tồn kho của biến thể là bắt buộc.',
            'variants.*.stock.integer'                      => 'Số lượng tồn kho của biến thể phải là số nguyên.',
            'variants.*.stock.min'                          => 'Số lượng tồn kho của biến thể không được nhỏ hơn 0.',
            'variants.*.discount_price.numeric'             => 'Giá giảm của biến thể phải là một số.',
            'variants.*.discount_price.min'                 => 'Giá giảm của biến thể không được nhỏ hơn 0.',
            'variants.*.discount_price.lte'                 => 'Giá giảm của biến thể phải nhỏ hơn hoặc bằng giá gốc của nó.',
            'variants.*.discount_percentage.numeric'        => 'Tỷ lệ chiết khấu của biến thể phải là một số.',
            'variants.*.discount_percentage.min'            => 'Tỷ lệ chiết khấu của biến thể không được nhỏ hơn 0%.',
            'variants.*.discount_percentage.max'            => 'Tỷ lệ chiết khấu của biến thể không được lớn hơn 100%.',
            'variants.*.attribute_value_ids.required'       => 'Mỗi biến thể phải có thuộc tính.',
            'variants.*.attribute_value_ids.array'          => 'Định dạng dữ liệu thuộc tính của biến thể không hợp lệ.',
            'variants.*.attribute_value_ids.min'            => 'Mỗi biến thể phải có ít nhất một thuộc tính.',
            'variants.*.attribute_value_ids.*.required'     => 'Giá trị thuộc tính không được để trống.',
            'variants.*.attribute_value_ids.*.integer'      => 'ID giá trị thuộc tính không hợp lệ.',
            'variants.*.attribute_value_ids.*.exists'       => 'Một trong các giá trị thuộc tính đã chọn không tồn tại.',
            'variants.*.sku.unique'                         => 'SKU của một trong các biến thể đã tồn tại.',

            // Messages for fields inside NEW variants array
            'new_variants.*.price.required'                 => 'Giá của biến thể mới là bắt buộc.',
            'new_variants.*.price.numeric'                  => 'Giá của biến thể mới phải là một số.',
            'new_variants.*.price.min'                      => 'Giá của biến thể mới không được nhỏ hơn 0.',
            'new_variants.*.stock.required'                 => 'Số lượng tồn kho của biến thể mới là bắt buộc.',
            'new_variants.*.stock.integer'                  => 'Số lượng tồn kho của biến thể mới phải là số nguyên.',
            'new_variants.*.stock.min'                      => 'Số lượng tồn kho của biến thể mới không được nhỏ hơn 0.',
            'new_variants.*.discount_price.numeric'         => 'Giá giảm của biến thể mới phải là một số.',
            'new_variants.*.discount_price.min'             => 'Giá giảm của biến thể mới không được nhỏ hơn 0.',
            'new_variants.*.discount_price.lte'             => 'Giá giảm của biến thể mới phải nhỏ hơn hoặc bằng giá gốc.',
            'new_variants.*.discount_percentage.numeric'    => 'Tỷ lệ chiết khấu của biến thể mới phải là một số.',
            'new_variants.*.discount_percentage.min'        => 'Tỷ lệ chiết khấu của biến thể mới không được nhỏ hơn 0%.',
            'new_variants.*.discount_percentage.max'        => 'Tỷ lệ chiết khấu của biến thể mới không được lớn hơn 100%.',
            'new_variants.*.attribute_value_ids.required'   => 'Mỗi biến thể mới phải có thuộc tính.',
            'new_variants.*.attribute_value_ids.array'      => 'Định dạng dữ liệu thuộc tính của biến thể mới không hợp lệ.',
            'new_variants.*.attribute_value_ids.min'        => 'Mỗi biến thể mới phải có ít nhất một thuộc tính.',
            'new_variants.*.attribute_value_ids.*.required' => 'Giá trị thuộc tính của biến thể mới không được để trống.',
            'new_variants.*.attribute_value_ids.*.integer'  => 'ID giá trị thuộc tính của biến thể mới không hợp lệ.',
            'new_variants.*.attribute_value_ids.*.exists'   => 'Một trong các giá trị thuộc tính của biến thể mới không tồn tại.',
            'new_variants.*.sku.unique'                     => 'SKU của một trong các biến thể mới đã tồn tại.',
            'variants.*.sku.distinct'                       => 'Phát hiện SKU bị trùng trong danh sách biến thể.',
            'sku.unique'                                    => 'SKU này đã tồn tại.',
            'new_variants.*.sku.distinct'                   => 'Phát hiện SKU bị trùng trong danh sách biến thể mới.',
        ];
    }
}
