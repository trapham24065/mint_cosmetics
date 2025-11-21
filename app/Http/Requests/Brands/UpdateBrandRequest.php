<?php

namespace App\Http\Requests\Brands;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
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
        $brandId = $this->route('brand')->id;
        return [
            'name'      => ['required', 'string', 'max:255', Rule::unique('brands')->ignore($brandId)],
            'logo'      => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp', 'max:1024'],
            'is_active' => ['sometimes', 'boolean'],
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
            'name.required'     => 'Please enter the brand name.',
            'name.string'       => 'The brand name must be text.',
            'name.max'          => 'The brand name cannot be longer than 255 characters.',
            'name.unique'       => 'This brand name is already taken.',
            'logo.image'        => 'The uploaded file must be an image.',
            'logo.mimes'        => 'The logo must be a file of type: jpg, png, jpeg, webp.',
            'logo.max'          => 'The logo size cannot exceed 1MB.',
            'is_active.boolean' => 'The active status must be true or false.',
        ];
    }

}
