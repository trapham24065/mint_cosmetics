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
            'code.required'               => 'Please enter the coupon code.',
            'code.string'                 => 'The coupon code must be text.',
            'code.max'                    => 'The coupon code cannot be longer than 255 characters.',
            'code.unique'                 => 'This coupon code is already taken.',
            'type.required'               => 'Please select the coupon type.',
            'type.enum'                   => 'The selected coupon type is invalid.',
            'value.required'              => 'Please enter the coupon value.',
            'value.numeric'               => 'The coupon value must be a number.',
            'value.min'                   => 'The coupon value cannot be negative.',
            'value.between'               => 'The percentage value must be between 0 and 100.',
            'min_purchase_amount.numeric' => 'The minimum purchase amount must be a number.',
            'min_purchase_amount.min'     => 'The minimum purchase amount cannot be negative.',
            'max_uses.integer'            => 'The maximum uses must be a whole number.',
            'max_uses.min'                => 'The maximum uses must be at least 1.',
            'starts_at.required'          => 'Please select the start date.',
            'starts_at.date'              => 'The start date is not a valid date.',
            'expires_at.required'         => 'Please select the expiry date.',
            'expires_at.date'             => 'The expiry date is not a valid date.',
            'expires_at.after'            => 'The expiry date must be after the start date.',
            'is_active.boolean'           => 'The active status must be true or false.',
        ];
    }

}
