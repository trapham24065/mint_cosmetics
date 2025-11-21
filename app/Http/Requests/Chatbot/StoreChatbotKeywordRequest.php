<?php

namespace App\Http\Requests\Chatbot;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreChatbotKeywordRequest extends FormRequest
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
        // Get the reply ID from the route parameters
        // Ensure your route parameter name for the reply matches 'reply' or update accordingly
        $replyId = $this->route('reply')->id;

        return [
            'keyword' => [
                'required',
                'string',
                'max:255',
                // Ensure keyword is unique for this specific reply
                Rule::unique('chatbot_keywords')->where('chatbot_reply_id', $replyId),
            ],
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
            'keyword.required' => 'Please enter the keyword.',
            'keyword.string'   => 'The keyword must be text.',
            'keyword.max'      => 'The keyword cannot be longer than 255 characters.',
            'keyword.unique'   => 'This keyword already exists for this reply.',
        ];
    }

}

