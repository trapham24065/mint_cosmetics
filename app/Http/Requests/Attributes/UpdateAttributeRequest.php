<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/24/2025
 * @time 3:24 PM
 */
declare(strict_types=1);
namespace App\Http\Requests\Attributes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttributeRequest extends FormRequest
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
        $attributeId = $this->route('attribute')->id;

        return [
            'name'         => ['required', 'string', 'max:255', Rule::unique('attributes')->ignore($attributeId)],
            'values'       => ['nullable', 'array'],
            'values.*'     => ['required', 'string', 'max:255'],
            'new_values'   => ['nullable', 'array'],
            'new_values.*' => ['nullable', 'string', 'max:255'],
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
            'name.required' => 'Vui lòng nhập tên thuộc tính.',
            'name.string'   => 'Tên thuộc tính phải là dạng văn bản.',
            'name.max'      => 'Tên thuộc tính không được vượt quá 255 ký tự.',
            'name.unique'   => 'Tên thuộc tính này đã tồn tại.',

            'values.array'        => 'Các giá trị thuộc tính hiện có phải được gửi đúng định dạng.',

            // Messages for rules applied to each item within the 'values' array (existing)
            'values.*.required'   => 'Giá trị của một thuộc tính hiện có không được để trống.',
            'values.*.string'     => 'Mỗi giá trị thuộc tính hiện có phải là dạng văn bản.',
            'values.*.max'        => 'Mỗi giá trị thuộc tính hiện có không được vượt quá 255 ký tự.',

            // Messages for rules applied to each item within the 'new_values' array
            'new_values.array'    => 'Các giá trị thuộc tính mới phải được gửi đúng định dạng.',
            'new_values.*.string' => 'Mỗi giá trị thuộc tính mới phải là dạng văn bản.',
            'new_values.*.max'    => 'Mỗi giá trị thuộc tính mới không được vượt quá 255 ký tự.',
        ];
    }

}
