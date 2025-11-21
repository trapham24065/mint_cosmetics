<?php

namespace App\Http\Requests\Chatbot;

use Illuminate\Foundation\Http\FormRequest;

class StoreChatbotRuleRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Assuming any authenticated admin can create chatbot rules
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
            'keyword'   => ['required', 'string', 'max:255', 'unique:chatbot_rules,keyword'],
            'reply'     => ['required', 'string', 'max:65535'], // Max length for TEXT type
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
            'keyword.required'  => 'Please enter the keyword.',
            'keyword.string'    => 'The keyword must be text.',
            'keyword.max'       => 'The keyword cannot be longer than 255 characters.',
            'keyword.unique'    => 'This keyword is already taken.',
            'reply.required'    => 'Please enter the reply message.',
            'reply.string'      => 'The reply message must be text.',
            'reply.max'         => 'The reply message is too long.',
            'is_active.boolean' => 'The active status must be true or false.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Ensure 'is_active' is present and boolean (0 or 1) for the controller
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

}
