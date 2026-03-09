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
            'keyword.required'  => 'Vui lòng nhập từ khóa.',
            'keyword.string'    => 'Từ khóa phải là văn bản.',
            'keyword.max'       => 'Từ khóa không được vượt quá 255 ký tự.',
            'keyword.unique'    => 'Từ khóa này đã tồn tại.',
            'reply.required'    => 'Vui lòng nhập nội dung phản hồi.',
            'reply.string'      => 'Nội dung phản hồi phải là văn bản.',
            'reply.max'         => 'Nội dung phản hồi quá dài.',
            'is_active.boolean' => 'Trạng thái hoạt động phải là đúng hoặc sai.',
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
