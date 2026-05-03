<?php

namespace App\Http\Controllers;

use App\Models\ChatbotRule;
use App\Models\ChatMessageAttachment;
use App\Models\Guest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Musonza\Chat\Facades\ChatFacade;
use Illuminate\Http\JsonResponse;
use App\Services\Storefront\ChatbotService;

class ChatController extends Controller
{

    public function sendMessage(Request $request, ChatbotService $chatbotService): JsonResponse
    {
        $request->validate([
            'message'       => 'nullable|string|max:1000',
            'attachments'   => 'nullable|array|max:5',
            'attachments.*' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'is_quick_hint' => 'sometimes|boolean',
        ]);

        $messageText = trim((string)$request->input('message', ''));
        $attachmentFiles = $request->file('attachments') ?? [];
        $hasAttachments = count($attachmentFiles) > 0;
        $messageBody = $messageText === '' && $hasAttachments ? ' ' : $messageText;

        if ($messageText === '' && !$hasAttachments) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập nội dung hoặc đính kèm ảnh.',
            ], 422);
        }

        $participant = $this->getParticipant();

        // ✅ FIX: lấy conversation đúng cách
        $conversation = $participant->conversations()->latest()->first();

        if (!$conversation) {
            $admin = User::first();
            if (!$admin) {
                return response()->json(['success' => false, 'message' => 'Hệ thống chưa sẵn sàng'], 500);
            }

            $conversation = ChatFacade::createConversation([$participant, $admin])->makeDirect();
        }

        // ✅ Gửi message + sender_id
        $message = ChatFacade::message($messageBody)
            ->from($participant)
            ->to($conversation)
            ->data([
                'sender_id'   => $participant->id,
                'sender_type' => get_class($participant),
            ])
            ->send();

        // attachments
        $attachmentsData = [];
        foreach ($attachmentFiles as $file) {
            $attachmentsData[] = $this->storeAttachment($file, $message->id);
        }

        $botMessage = null;
        $isFromQuickHint = $request->boolean('is_quick_hint');

        // bot reply (giữ nguyên logic cũ)
        if ($isFromQuickHint && $messageText !== '') {
            $admin = User::first();

            if ($admin) {
                $botReplyText = $chatbotService->findReply($messageText);

                if ($botReplyText) {
                    $botMessage = ChatFacade::message($botReplyText)
                        ->from($admin)
                        ->to($conversation)
                        ->data([
                            'sender_id'      => $admin->id,
                            'sender_type'    => get_class($admin),
                            'is_admin_reply' => true,
                            'auto_reply'     => true,
                        ])
                        ->send();
                }
            }
        }

        $messagePayload = $this->formatMessagePayload($message, true, $attachmentsData);

        $botReplyPayload = null;
        if ($botMessage) {
            $botReplyPayload = $this->formatMessagePayload($botMessage, false, []);
            $botReplyPayload['is_admin_reply'] = true;
        }

        return response()->json([
            'success'   => true,
            'message'   => $messagePayload,
            'bot_reply' => $botReplyPayload,
        ]);
    }

    /**
     * API: Get new messages (Polling).
     */
    public function fetchMessages(Request $request): JsonResponse
    {
        $participant = $this->getParticipant();
        $conversation = $participant->conversations()->latest()->first();

        if (!$conversation) {
            return response()->json(['messages' => []]);
        }

        $lastId = (int)$request->input('last_id', 0);

        $messages = $conversation->messages()
            ->with('participation.messageable')
            ->when($lastId > 0, function ($q) use ($lastId) {
                $q->where('id', '>', $lastId);
            })
            ->orderBy('id', 'asc')
            ->get();

        $messageIds = $messages->pluck('id')->all();

        $attachmentsByMessage = ChatMessageAttachment::whereIn('message_id', $messageIds)
            ->get()
            ->groupBy('message_id');

        $formattedMessages = [];

        foreach ($messages as $message) {
            $msgId = $message->id;

            $messageData = $this->decodeMessageData($message->data);

            $isMe = false;

            if ($messageData && isset($messageData['sender_type'], $messageData['sender_id'])) {
                $isMe =
                    $messageData['sender_type'] === get_class($participant) &&
                    (int)$messageData['sender_id'] === (int)$participant->id;
            }
            
            $attachmentsData = collect($attachmentsByMessage->get($msgId))
                ->map(fn($att) => $this->formatAttachment($att))
                ->toArray();

            $payload = $this->formatMessagePayload(
                    $message,
                    $isMe,
                    $attachmentsData ?: null
                ) + [
                    'sender_name' => $isMe ? 'Bạn' : 'Hỗ trợ',
                ];

            // giữ flag bot nếu cần
            if ($messageData && isset($messageData['is_admin_reply'])) {
                $payload['is_admin_reply'] = $messageData['is_admin_reply'];
            }

            $formattedMessages[] = $payload;
        }

        return response()->json(['messages' => $formattedMessages]);
    }

    /**
     * Helper: Decode message data JSON field
     */
    private function decodeMessageData($data): ?array
    {
        if (empty($data)) {
            return null;
        }

        try {
            if (is_array($data)) {
                return $data;
            }
            return json_decode($data, true);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     *
     * Helper: Identify the person chatting.
     */
    private function getParticipant()
    {
        // Check if customer is logged in
        if (Auth::guard('customer')->check()) {
            return Auth::guard('customer')->user();
        }

        // Check if seller/admin is logged in (web guard)
        if (Auth::guard('web')->check()) {
            return Auth::guard('web')->user();
        }

        // Use guest_id from request (stored in client localStorage)
        $guestId = request()->input('guest_id');
        $ipAddress = request()->ip();

        // Require guest_id - don't fall back to IP hash to avoid session confusion
        if (!$guestId) {
            // Generate a new guest_id if not provided (shouldn't happen in normal flow)
            $userAgent = request()->header('User-Agent', '');
            $guestId = 'guest_'.time().'_'.hash('sha256', $ipAddress.$userAgent);
        }

        // Always use session_id for lookup to ensure session continuity
        $guest = Guest::firstOrCreate(
            ['session_id' => $guestId],
            ['name' => 'Khách Vãng Lai', 'ip_address' => $ipAddress]
        );

        // Update IP address on each request (user might move networks)
        if ($guest->ip_address !== $ipAddress) {
            $guest->update(['ip_address' => $ipAddress]);
        }

        return $guest;
    }

    public function getSuggestions(): JsonResponse
    {
        $suggestions = ChatbotRule::where('is_active', true)->pluck('keyword');
        return response()->json($suggestions);
    }

    /**
     * Send a default message when admin is not available.
     * This can be called from frontend after a timeout (e.g., 30 seconds).
     */
    public function sendDefaultMessage(Request $request): JsonResponse
    {
        $participant = $this->getParticipant();
        $admin = User::first();

        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Hệ thống chưa sẵn sàng.'], 500);
        }

        $conversation = $participant->conversations->sortByDesc('updated_at')->first();

        if (!$conversation) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy cuộc trò chuyện nào.'], 404);
        }

        // Gửi tin nhắn mặc định
        $defaultMessage = "Xin lỗi, quản trị viên hiện đang bận. Bạn có thể chọn một trong những câu hỏi thường gặp bên dưới để nhận được câu trả lời ngay lập tức!";
        $botMessage = ChatFacade::message($defaultMessage)
            ->from($admin)
            ->to($conversation)
            ->data([
                'is_admin_reply' => true,
                'auto_reply'     => true,
                'sender_id'      => $admin->id,
                'sender_type'    => get_class($admin),
            ])
            ->send();
        return response()->json([
            'success'   => true,
            'bot_reply' => $this->formatMessagePayload($botMessage, false, null),
        ]);
    }

    /**
     * Helper: Persist an uploaded attachment for a message.
     */
    private function storeAttachment($file, int $messageId): array
    {
        $path = $file->store('chat-attachments/'.date('Y/m'), 'public');

        $attachment = ChatMessageAttachment::create([
            'message_id'    => $messageId,
            'disk'          => 'public',
            'path'          => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type'     => $file->getMimeType(),
            'size_bytes'    => $file->getSize(),
        ]);

        return $this->formatAttachment($attachment);
    }

    /**
     * Helper: Build the JSON payload for an attachment.
     */
    private function formatAttachment(ChatMessageAttachment $attachment): array
    {
        return [
            'id'            => $attachment->id,
            'url'           => $attachment->url,
            'original_name' => $attachment->original_name,
            'mime_type'     => $attachment->mime_type,
            'size_bytes'    => $attachment->size_bytes,
            'is_image'      => $attachment->is_image,
        ];
    }

    /**
     * Helper: Build the JSON payload for a message.
     */
    private function formatMessagePayload($message, bool $isMe, ?array $attachments = null): array
    {
        // Support both single attachment (legacy) and array of attachments
        $formattedAttachments = [];
        if ($attachments) {
            // Check if it's a single attachment (has 'url' key) or array of attachments
            if (isset($attachments['url']) || isset($attachments['is_image'])) {
                // Single attachment
                $formattedAttachments = [$attachments];
            } else {
                // Array of attachments
                $formattedAttachments = $attachments;
            }
        }

        return [
            'id'               => $message->id,
            'body'             => $message->body,
            'created_at'       => $message->created_at->format('H:i'),
            'created_at_raw'   => $message->created_at->toIso8601String(),
            'created_at_date'  => $message->created_at->format('Y-m-d'),
            'created_at_label' => $message->created_at->isToday()
                ? 'Hôm nay'
                : ($message->created_at->isYesterday() ? 'Hôm qua' : $message->created_at->format('d/m/Y')),
            'is_me'            => $isMe,
            'attachments'      => $formattedAttachments,
            'attachment'       => $formattedAttachments[0] ?? null, // For backward compatibility
        ];
    }

}
