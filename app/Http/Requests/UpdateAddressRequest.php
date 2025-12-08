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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'address'    => ['required', 'string', 'max:500'],
            'city'       => ['required', 'string', 'max:255'],
            'phone'      => ['required', 'string', 'regex:/^[0-9]{10,11}$/'],
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
            'first_name.required' => 'Please enter your first name.',
            'last_name.required'  => 'Please enter your last name.',
            'address.required'    => 'Please enter your address.',
            'city.required'       => 'Please enter your city.',
            'phone.required'      => 'Please enter your phone number.',
            'phone.regex'         => 'Phone number format is invalid (10-11 digits).',
        ];
    }

}
