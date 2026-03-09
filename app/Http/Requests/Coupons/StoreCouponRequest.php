<?php
/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/26/2025
 * @time 12:28 AM
 */

namespace App\Http\Requests\Coupons;

use App\Enums\CouponType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCouponRequest extends FormRequest
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
        $rules = [
            'code'                => ['required', 'string', 'max:255', 'unique:coupons,code'],
            'type'                => ['required', Rule::enum(CouponType::class)],
            'min_purchase_amount' => ['nullable', 'numeric', 'min:0'],
            'max_uses'            => ['nullable', 'integer', 'min:1'],
            'starts_at'           => ['required', 'date'],
            'expires_at'          => ['required', 'date', 'after:starts_at'],
            'is_active'           => ['sometimes', 'boolean'],
        ];

        // --- ADD CONDITIONAL VALIDATION LOGIC ---
        if ($this->input('type') === CouponType::PERCENTAGE->value) {
            //If 'percentage', the value must be between 0 and 100
            $rules['value'] = ['required', 'numeric', 'between:0,100'];
        } else {
            //  If it's 'fixed_amount', the value just needs to be greater than 0
            $rules['value'] = ['required', 'numeric', 'min:0'];
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
            'code.required'               => 'Vui lòng nhập mã giảm giá.',
            'code.string'                 => 'Mã giảm giá phải là văn bản.',
            'code.max'                    => 'Mã giảm giá không được vượt quá 255 ký tự.',
            'code.unique'                 => 'Mã giảm giá này đã tồn tại.',
            'type.required'               => 'Vui lòng chọn loại mã giảm giá.',
            'type.enum'                   => 'Loại mã giảm giá đã chọn không hợp lệ.',
            'value.required'              => 'Vui lòng nhập giá trị mã giảm giá.',
            'value.numeric'               => 'Giá trị mã giảm giá phải là một số.',
            'value.min'                   => 'Giá trị mã giảm giá không được nhỏ hơn 0.',
            'value.between'               => 'Giá trị phần trăm phải nằm trong khoảng từ 0 đến 100.',
            'min_purchase_amount.numeric' => 'Giá trị đơn hàng tối thiểu phải là một số.',
            'min_purchase_amount.min'     => 'Giá trị đơn hàng tối thiểu không được nhỏ hơn 0.',
            'max_uses.integer'            => 'Số lần sử dụng tối đa phải là số nguyên.',
            'max_uses.min'                => 'Số lần sử dụng tối đa phải ít nhất là 1.',
            'starts_at.required'          => 'Vui lòng chọn ngày bắt đầu.',
            'starts_at.date'              => 'Ngày bắt đầu không hợp lệ.',
            'expires_at.required'         => 'Vui lòng chọn ngày hết hạn.',
            'expires_at.date'             => 'Ngày hết hạn không hợp lệ.',
            'expires_at.after'            => 'Ngày hết hạn phải sau ngày bắt đầu.',
            'is_active.boolean'           => 'Trạng thái hoạt động không hợp lệ.',
        ];
    }

}
