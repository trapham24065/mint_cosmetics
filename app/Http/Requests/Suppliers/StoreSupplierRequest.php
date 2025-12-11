<?php

namespace App\Http\Requests\Suppliers;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Assuming any authenticated admin can create suppliers
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
            'name'           => ['required', 'string', 'max:255', 'unique:suppliers,name'],
            'email'          => ['nullable', 'email', 'max:255', 'unique:suppliers,email'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'address'        => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'note'           => ['nullable', 'string'],
            'is_active'      => ['sometimes', 'boolean'],
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
            'name.required'      => 'Please enter the supplier name.',
            'name.string'        => 'The supplier name must be text.',
            'name.max'           => 'The supplier name cannot be longer than 255 characters.',
            'name.unique'        => 'This supplier name already exists.',
            'email.email'        => 'Please enter a valid email address.',
            'email.max'          => 'The email cannot be longer than 255 characters.',
            'email.unique'       => 'This email address is already registered with another supplier.',
            'phone.max'          => 'The phone number cannot be longer than 20 characters.',
            'address.max'        => 'The address cannot be longer than 255 characters.',
            'contact_person.max' => 'The contact person name cannot be longer than 255 characters.',
            'is_active.boolean'  => 'The active status must be true or false.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Ensure 'is_active' is boolean
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

}
