<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactReply;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactMessageController extends Controller
{

    public function index(Request $request): View
    {
        $query = ContactMessage::query()->with('processedBy')->latest();

        if ($request->filled('status')) {
            $status = $request->string('status')->toString();
            if ($status === 'processed') {
                $query->whereNotNull('processed_at');
            } elseif ($status === 'pending') {
                $query->whereNull('processed_at');
            }
        }

        if ($request->filled('keyword')) {
            $keyword = $request->string('keyword')->toString();
            $query->where(function ($q) use ($keyword) {
                $q->where('first_name', 'like', "%{$keyword}%")
                    ->orWhere('last_name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('message', 'like', "%{$keyword}%");
            });
        }

        $messages = $query->paginate(15)->withQueryString();

        return view('admin.management.contacts.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage): View
    {
        $contactMessage->load('processedBy');

        return view('admin.management.contacts.show', compact('contactMessage'));
    }

    public function markProcessed(ContactMessage $contactMessage): RedirectResponse
    {
        if ($contactMessage->processed_at !== null) {
            return back()->with('success', 'Liên hệ này đã được xử lý trước đó.');
        }

        $contactMessage->update([
            'processed_at' => now(),
            'processed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Đã đánh dấu liên hệ là đã xử lý.');
    }

    public function replyContact(ContactMessage $contactMessage, Request $request): RedirectResponse
    {
        $request->validate([
            'reply_message' => ['required', 'string', 'min:10', 'max:5000'],
        ], [
            'reply_message.required' => 'Vui lòng nhập nội dung phản hồi.',
            'reply_message.min' => 'Nội dung phản hồi phải tối thiểu 10 ký tự.',
            'reply_message.max' => 'Nội dung phản hồi không được vượt quá 5000 ký tự.',
        ]);

        try {
            Mail::to($contactMessage->email)->send(new ContactReply($contactMessage, $request->string('reply_message')->toString()));

            // Mark as processed if not already
            if ($contactMessage->processed_at === null) {
                $contactMessage->update([
                    'processed_at' => now(),
                    'processed_by' => Auth::id(),
                ]);
            }

            return back()->with('success', 'Phản hồi đã được gửi thành công.');
        } catch (\Exception $e) {
            return back()->withErrors(['reply_message' => 'Có lỗi khi gửi email: ' . $e->getMessage()]);
        }
    }

    public function getDataForGrid(): \Illuminate\Http\JsonResponse
    {
        $messages = \App\Models\ContactMessage::latest()->get();

        $data = $messages->map(function ($message) {
            $statusKey = $message->processed_at ? 'processed' : 'pending';

            return [
                'id'         => $message->id,
                'name'       => trim($message->first_name . ' ' . $message->last_name) ?: 'N/A',
                'email'      => $message->email,
                'message'    => \Illuminate\Support\Str::limit($message->message, 80),
                'status'     => $statusKey,
                'status_label' => $message->processed_at ? 'Đã xử lý' : 'Chưa xử lý',
                'created_at' => $message->created_at->format('d/m/Y H:i'),
                'action'     => route('admin.contacts.show', $message),
            ];
        });

        return response()->json(['data' => $data]);
    }
}
