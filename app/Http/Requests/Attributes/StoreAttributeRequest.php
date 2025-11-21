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

class StoreAttributeRequest extends FormRequest
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
            'name'     => ['required', 'string', 'max:255', 'unique:attributes,name'],
            'values'   => ['nullable', 'array'], // The list of values can be empty
            'values.*' => ['nullable', 'string', 'max:255'], // Each value in the array
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
            'name.required'   => 'Please enter the attribute name.',
            'name.string'     => 'The attribute name must be text.',
            'name.max'        => 'The attribute name cannot be longer than 255 characters.',
            'name.unique'     => 'This attribute name is already taken.',
            'values.array'    => 'The attribute values must be submitted in the correct format.',
            // Messages for rules applied to each item within the 'values' array
            'values.*.string' => 'Each attribute value must be text.',
            'values.*.max'    => 'Each attribute value cannot be longer than 255 characters.',
        ];
    }

}
