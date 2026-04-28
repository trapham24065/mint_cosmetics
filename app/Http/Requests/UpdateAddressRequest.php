<?php

/**
 * @project mint_cosmetics
 * @author M397
 * @email m397.dev@gmail.com
 * @date 12/1/2025
 * @time 9:06 PM
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateAddressRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard('customer')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:2', 'max:35', 'regex:/^(?=.*\\p{L})[\\p{L}\\s]+$/u'],
            'last_name'  => ['required', 'string', 'min:2', 'max:35', 'regex:/^(?=.*\\p{L})[\\p{L}\\s]+$/u'],
            'address'    => ['required', 'string', 'max:500'],
            'city'       => ['nullable', 'string', 'max:255'],
            'phone'      => ['required', 'string', 'regex:/^[0-9]{10,11}$/'],
            'shipping_province_id' => ['required', 'integer'],
            'shipping_district_id' => ['required', 'integer'],
            'shipping_ward_code' => ['required', 'string', 'max:50'],
            'shipping_province_name' => ['required', 'string', 'max:255'],
            'shipping_district_name' => ['required', 'string', 'max:255'],
            'shipping_ward_name' => ['required', 'string', 'max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $city = implode(', ', array_filter([
            $this->input('shipping_ward_name'),
            $this->input('shipping_district_name'),
            $this->input('shipping_province_name'),
        ]));

        if ($city !== '') {
            $this->merge(['city' => $city]);
        }
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'Vui lòng nhập tên.',
            'first_name.min' => 'Tên phải có ít nhất 2 ký tự.',
            'first_name.max' => 'Tên không được vượt quá 35 ký tự.',
            'first_name.regex' => 'Tên chỉ được chứa chữ cái và khoảng trắng, không chứa ký tự đặc biệt.',
            'last_name.required'  => 'Vui lòng nhập họ.',
            'last_name.min'  => 'Họ phải có ít nhất 2 ký tự.',
            'last_name.max'  => 'Họ không được vượt quá 35 ký tự.',
            'last_name.regex' => 'Họ chỉ được chứa chữ cái và khoảng trắng, không chứa ký tự đặc biệt.',
            'address.required'    => 'Vui lòng nhập địa chỉ.',
            'phone.required'      => 'Vui lòng nhập số điện thoại.',
            'phone.regex'         => 'Định dạng số điện thoại không hợp lệ (10-11 chữ số).',
            'shipping_province_id.required' => 'Vui lòng chọn tỉnh/thành phố.',
            'shipping_district_id.required' => 'Vui lòng chọn quận/huyện.',
            'shipping_ward_code.required' => 'Vui lòng chọn phường/xã.',
            'shipping_province_name.required' => 'Vui lòng chọn tỉnh/thành phố.',
            'shipping_district_name.required' => 'Vui lòng chọn quận/huyện.',
            'shipping_ward_name.required' => 'Vui lòng chọn phường/xã.',
        ];
    }
}
