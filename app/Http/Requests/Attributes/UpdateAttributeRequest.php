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
            'name.required'       => 'Please enter the attribute name.',
            'name.string'         => 'The attribute name must be text.',
            'name.max'            => 'The attribute name cannot be longer than 255 characters.',
            'name.unique'         => 'This attribute name is already taken.',
            'values.array'        => 'The existing attribute values must be submitted in the correct format.',
            // Messages for rules applied to each item within the 'values' array (existing)
            'values.*.required'   => 'The value for an existing attribute value cannot be empty.',
            'values.*.string'     => 'Each existing attribute value must be text.',
            'values.*.max'        => 'Each existing attribute value cannot be longer than 255 characters.',
            // Messages for rules applied to each item within the 'new_values' array
            'new_values.array'    => 'The new attribute values must be submitted in the correct format.',
            'new_values.*.string' => 'Each new attribute value must be text.',
            'new_values.*.max'    => 'Each new attribute value cannot be longer than 255 characters.',
        ];
    }

}
