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
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ], [
            'first_name.required' => 'Vui lòng nhập tên của bạn.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'message.required' => 'Vui lòng nhập nội dung liên hệ.',
        ]);

        ContactMessage::create($validated);

        return redirect()
            ->route('contact.index')
            ->with('success', 'Tin nhan cua ban da duoc gui. Mint Cosmetics se phan hoi som nhat co the.');
    }
}
