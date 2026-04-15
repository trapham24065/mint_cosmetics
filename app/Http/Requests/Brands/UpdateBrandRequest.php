<?php

namespace App\Http\Requests\Brands;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
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
        $brandId = $this->route('brand')->id;
        return [
            'name'      => ['required', 'string', 'max:255', Rule::unique('brands')->ignore($brandId)],
            'logo'      => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp', 'max:1024'],
            'remove_current_logo' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
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
            'name.required'     => 'Vui lòng nhập tên thương hiệu.',
            'name.string'       => 'Tên thương hiệu phải là văn bản.',
            'name.max'          => 'Tên thương hiệu không được vượt quá 255 ký tự.',
            'name.unique'       => 'Tên thương hiệu này đã tồn tại.',
            'logo.image'        => 'Tệp tải lên phải là hình ảnh.',
            'logo.mimes'        => 'Logo phải có định dạng: jpg, png, jpeg, webp.',
            'logo.max'          => 'Kích thước logo không được vượt quá 1MB.',
            'is_active.boolean' => 'Trạng thái hoạt động không hợp lệ.',
        ];
    }
}
