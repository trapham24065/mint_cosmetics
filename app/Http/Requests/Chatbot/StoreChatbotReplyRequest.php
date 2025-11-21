<?php

namespace App\Http\Requests\Chatbot;

use Illuminate\Foundation\Http\FormRequest;

class StoreChatbotReplyRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Assuming admin authorization is handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'topic'     => ['required', 'string', 'max:255', 'unique:chatbot_replies,topic'],
            'reply'     => ['required', 'string', 'max:65535'],
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
            'topic.required'    => 'Please enter the topic.',
            'topic.string'      => 'The topic must be text.',
            'topic.max'         => 'The topic cannot be longer than 255 characters.',
            'topic.unique'      => 'This topic already exists.',
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
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

}
