<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreReturnRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('customer')->check();
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:1000'],
            'details' => ['nullable', 'string', 'max:2000'],
            'evidence_images' => ['nullable', 'array', 'max:5'],
            'evidence_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'Vui lòng nhập lý do trả hàng.',
            'evidence_images.max' => 'Bạn chỉ có thể tải lên tối đa 5 ảnh.',
            'evidence_images.*.image' => 'Tệp tải lên phải là hình ảnh hợp lệ.',
            'evidence_images.*.mimes' => 'Ảnh chỉ hỗ trợ định dạng JPG, PNG hoặc WEBP.',
            'evidence_images.*.max' => 'Mỗi ảnh không được vượt quá 4MB.',
        ];
    }
}
