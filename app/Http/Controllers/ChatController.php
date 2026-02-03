<?php

namespace App\Http\Controllers;

use App\Models\ChatbotRule;
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
            'message'       => 'required|string|max:1000',
            'is_quick_hint' => 'sometimes|boolean',
        ]);

        $participant = $this->getParticipant();
        $admin = User::first();

        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'The system is not ready.'], 500);
        }

        $conversation = $participant->conversations->sortByDesc('updated_at')->first();

        if (!$conversation) {
            $participants = [$participant, $admin];
            $conversation = ChatFacade::createConversation($participants)->makeDirect();
        }

        // Lưu tin nhắn của khách hàng
        $message = ChatFacade::message($request->message)
            ->from($participant)
            ->to($conversation)
            ->send();

        $botMessage = null;
        $isFromQuickHint = $request->input('is_quick_hint', false);

        // CHỈ tự động trả lời nếu tin nhắn từ Quick Hint
        if ($isFromQuickHint) {
            $botReplyText = $chatbotService->findReply($request->message);

            if ($botReplyText) {
                $botMessage = ChatFacade::message($botReplyText)
                    ->from($admin)
                    ->to($conversation)
                    ->send();
            }
        }
        // Nếu KHÔNG phải Quick Hint → Chờ admin trả lời (không tự động trả lời)

        return response()->json([
            'success'   => true,
            'message'   => [
                'id'         => $message->id,
                'body'       => $message->body,
                'created_at' => $message->created_at->format('H:i'),
                'is_me'      => true,
            ],
            'bot_reply' => $botMessage ? [
                'id'         => $botMessage->id,
                'body'       => $botMessage->body,
                'created_at' => $botMessage->created_at->format('H:i'),
                'is_me'      => false,
            ] : null,
        ]);
    }

    /**
     * API: Get new messages (Polling).
     */
    public function fetchMessages(Request $request): JsonResponse
    {
        $participant = $this->getParticipant();
        $conversation = $participant->conversations->sortByDesc('updated_at')->first();

        if (!$conversation) {
            return response()->json(['messages' => []]);
        }

        $lastId = $request->input('last_id');

        $query = $conversation->messages()
            ->with('participation.messageable')
            ->orderBy('created_at', 'desc')
            ->limit(20);

        $messages = $query->get();

        $formattedMessages = [];
        foreach ($messages->reverse() as $message) {
            $msgId = data_get($message, 'id');

            if ($lastId && $msgId <= $lastId) {
                continue;
            }

            $senderId = data_get($message, 'participation.messageable_id');
            $senderType = data_get($message, 'participation.messageable_type');

            $isMe = $senderId === $participant->id && $senderType === get_class($participant);

            $formattedMessages[] = [
                'id'          => $msgId,
                'body'        => data_get($message, 'body'),
                'created_at'  => $message->created_at->format('H:i'),
                'is_me'       => $isMe,
                'sender_name' => $isMe ? 'You' : 'Support',
            ];
        }

        return response()->json(['messages' => $formattedMessages]);
    }

    /**
     *
     * Helper: Identify the person chatting.
     */
    private function getParticipant()
    {
        if (Auth::guard('customer')->check()) {
            return Auth::guard('customer')->user();
        }

        $sessionId = Session::getId();

        return Guest::firstOrCreate(
            ['session_id' => $sessionId],
            ['name' => 'Visitors', 'ip_address' => request()->ip()]
        );
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
    public function sendDefaultMessage(): JsonResponse
    {
        $participant = $this->getParticipant();
        $admin = User::first();

        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'The system is not ready.'], 500);
        }

        $conversation = $participant->conversations->sortByDesc('updated_at')->first();

        if (!$conversation) {
            return response()->json(['success' => false, 'message' => 'No conversation found.'], 404);
        }

        // Gửi tin nhắn mặc định
        $defaultMessage = "Sorry, the admin is busy right now. You can choose one of the frequently asked questions below to get an immediate answer!😊";
        $botMessage = ChatFacade::message($defaultMessage)
            ->from($admin)
            ->to($conversation)
            ->send();

        return response()->json([
            'success'   => true,
            'bot_reply' => [
                'id'         => $botMessage->id,
                'body'       => $botMessage->body,
                'created_at' => $botMessage->created_at->format('H:i'),
                'is_me'      => false,
            ],
        ]);
    }

}
