<?php

namespace App\Http\Requests\Chatbot;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChatbotRuleRequest extends FormRequest
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
        $ruleId = $this->route('rule')->id;

        return [
            'keyword'   => ['required', 'string', 'max:255', Rule::unique('chatbot_rules')->ignore($ruleId)],
            'reply'     => ['required', 'string', 'max:65535'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Lấy các thông báo lỗi tùy chỉnh.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'keyword.required'  => 'Please enter a keyword.',
            'keyword.string'    => 'Keyword must be text.',
            'keyword.max'       => 'Keyword cannot exceed 255 characters.',
            'keyword.unique'    => 'This keyword already exists.',
            'reply.required'    => 'Please enter a reply.',
            'reply.string'      => 'Reply must be text.',
            'reply.max'         => 'Reply is too long.',
            'is_active.boolean' => 'Invalid active state.',
        ];
    }

    /**
     * Prepare data before validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
// Ensure 'is_active' always exists and is a boolean (0 or 1) for the controller
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

}
