<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/26/2025
 * @time 12:28 AM
 */
declare(strict_types=1);
namespace App\Http\Requests\Coupons;

use App\Enums\CouponType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCouponRequest extends FormRequest
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
        $couponId = $this->route('coupon')->id;

        $rules = [
            'code'                => ['required', 'string', 'max:255', Rule::unique('coupons')->ignore($couponId)],
            'type'                => ['required', Rule::enum(CouponType::class)],
            'min_purchase_amount' => ['nullable', 'numeric', 'min:0'],
            'max_uses'            => ['nullable', 'integer', 'min:1'],
            'starts_at'           => ['required', 'date'],
            'expires_at'          => ['required', 'date', 'after:starts_at'],
            'is_active'           => ['sometimes', 'boolean'],
        ];

        if ($this->input('type') === CouponType::PERCENTAGE->value) {
            $rules['value'] = ['required', 'numeric', 'between:0,100'];
        } else {
            $rules['value'] = ['required', 'numeric', 'min:0'];
        }

        return $rules;
    }

}
