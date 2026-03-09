<?php

namespace App\Http\Requests\Chatbot;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChatbotReplyRequest extends FormRequest
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
        // Get the reply ID from the route model binding
        // Ensure your route parameter name matches 'chatbotReply' or update accordingly
        $replyId = $this->route('chatbotReply')->id;

        return [
            'topic'     => [
                'required',
                'string',
                'max:255',
                Rule::unique('chatbot_replies')->ignore($replyId),
            ],
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
            'topic.required'    => 'Vui lòng nhập chủ đề.',
            'topic.string'      => 'Chủ đề phải là văn bản.',
            'topic.max'         => 'Chủ đề không được vượt quá 255 ký tự.',
            'topic.unique'      => 'Chủ đề này đã tồn tại.',
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
    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

}

