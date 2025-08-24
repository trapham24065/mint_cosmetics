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

}
