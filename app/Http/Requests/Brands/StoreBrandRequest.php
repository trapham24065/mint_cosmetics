<?php

namespace App\Http\Requests\Brands;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
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
            'name'      => ['required', 'string', 'max:255', 'unique:brands,name'],
            'logo'      => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp', 'max:1024'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

}
