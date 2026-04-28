<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/14/2025
 * @time 11:33 PM
 */

namespace App\Http\Controllers\Storefront;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController
{

    public function index()
    {
        return view('storefront.contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['nullable', 'string', 'max:100'],
            'email'      => ['required', 'email', 'lowercase', 'max:255'],
            'message'    => ['required', 'string', 'min:10', 'max:5000'],
        ], [
            'first_name.required' => 'Vui lòng nhập tên của bạn.',
            'email.required'      => 'Vui lòng nhập email.',
            'email.email'         => 'Email không đúng định dạng.',
            'email.lowercase'     => 'Email phải viết thường.',
            'message.required'    => 'Vui lòng nhập nội dung liên hệ.',
            'message.min'         => 'Nội dung liên hệ phải có ít nhất 10 ký tự.',
            'message.max'         => 'Nội dung liên hệ không được vượt quá 5000 ký tự.',
        ]);

        ContactMessage::create($validated);

        return redirect()
            ->route('contact.index')
            ->with('success', 'Tin nhan cua ban da duoc gui. Mint Cosmetics se phan hoi som nhat co the.');
    }
}
