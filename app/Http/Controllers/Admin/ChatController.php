<?php
/**
 * @project mint_cosmetics
 * @author M397
 * @email m397.dev@gmail.com
 * @date 12/3/2025
 * @time 10:18 PM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Musonza\Chat\Facades\ChatFacade as Chat;
use Musonza\Chat\Models\Conversation;

class ChatController extends Controller
{

    public function index(Request $request)
    {
        $admin = Auth::user();

        //$conversations = $admin->conversations->sortByDesc('updated_at');
        $conversations = $admin->conversations()
            ->with('participants.messageable') // <-- QUAN TRỌNG: Tải thông tin Customer/Guest
            ->orderBy('updated_at', 'desc')
            ->get();
        $currentConversation = null;
        $messages = [];

        if ($request->has('conversation_id')) {
            $conversationId = $request->conversation_id;
            $currentConversation = Chat::conversations()->getById($conversationId);

            if ($currentConversation) {
                $messages = $currentConversation->messages()
                    ->with('participation.messageable')
                    ->orderBy('created_at', 'desc')
                    ->limit(50)
                    ->get()
                    ->reverse();
            }
        }

        return view('admin.management.chat.index', compact('conversations', 'currentConversation', 'messages'));
    }

    public function reply(Request $request, $conversationId): \Illuminate\Http\JsonResponse
    {
        $request->validate(['message' => 'required|string']);

        $admin = Auth::user();
        $conversation = Chat::conversations()->getById($conversationId);

        // Gửi tin nhắn
        $message = Chat::message($request->message)
            ->from($admin)
            ->to($conversation)
            ->send();

        return response()->json([
            'success' => true,
            'message' => [
                'id'          => $message->id,
                'body'        => $message->body,
                'created_at'  => $message->created_at->format('H:i'),
                'sender_name' => 'You',
                'is_me'       => true,
            ],
        ]);
    }

    // API để polling tin nhắn mới cho Admin
    public function fetchMessages(Request $request, $conversationId)
    {
        $conversation = Chat::conversations()->getById($conversationId);
        $lastId = $request->input('last_id');

        $query = $conversation->messages()
            ->with('participation.messageable')
            ->orderBy('created_at', 'desc')
            ->limit(20);

        $messages = $query->get();

        $formattedMessages = [];
        foreach ($messages->reverse() as $message) {
            // Lấy ID tin nhắn an toàn
            $messageId = data_get($message, 'id');

            if ($lastId && $messageId <= $lastId) {
                continue;
            }

            // --- SỬA LỖI: Dùng data_get toàn diện ---
            // Thay vì cố gắng lấy model sender, ta lấy thông tin ID và Type từ participation
            // Cách này an toàn cho cả Object và Array
            $senderId = data_get($message, 'participation.messageable_id');
            $senderType = data_get($message, 'participation.messageable_type');

            // Kiểm tra xem có phải là Admin hiện tại không
            $isMe = $senderId == Auth::id() && $senderType == get_class(Auth::user());

            // Lấy body và created_at an toàn
            $body = data_get($message, 'body');
            $createdAt = data_get($message, 'created_at');
            $timeDisplay = $createdAt instanceof \Carbon\Carbon ? $createdAt->format('H:i') : \Carbon\Carbon::parse(
                $createdAt
            )->format('H:i');

            // Lấy tên người gửi (nếu không phải mình)
            $senderName = 'Customer';
            if (!$isMe) {
                // Thử lấy tên từ quan hệ messageable nếu có
                $senderName = data_get($message, 'participation.messageable.name') ??
                    data_get($message, 'sender.name') ??
                    'Customer';
            } else {
                $senderName = 'You';
            }

            $formattedMessages[] = [
                'id'          => $messageId,
                'body'        => $body,
                'created_at'  => $timeDisplay,
                'is_me'       => $isMe,
                'sender_name' => $senderName,
            ];
        }

        return response()->json(['messages' => $formattedMessages]);
    }

}
