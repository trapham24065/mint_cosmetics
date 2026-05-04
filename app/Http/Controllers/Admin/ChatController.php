<?php

/**
 * @project mint_cosmetics
 * @author M397
 * @email m397.dev@gmail.com
 * @date 12/3/2025
 * @time 10:18 PM
 */

namespace App\Http\Controllers\Admin;

use App\Models\ChatMessageAttachment;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Musonza\Chat\Facades\ChatFacade as Chat;
use Musonza\Chat\Models\Conversation;

class ChatController extends Controller
{

    public function index(Request $request)
    {
        /** @var User|null $admin */
        $admin = Auth::user();
        $isAdmin = $admin instanceof User && $admin->isAdmin();

        if ($isAdmin) {
            // Admin can monitor all customer conversations.
            $conversations = Conversation::query()
                ->with('participants.messageable')
                ->orderBy('updated_at', 'desc')
                ->get();
        } else {
            $conversations = $admin instanceof User
                ? $admin->conversations()
                    ->with('participants.messageable')
                    ->orderBy('updated_at', 'desc')
                    ->get()
                : collect();
        }

        $currentConversation = null;
        $messages = [];

        if ($request->has('conversation_id')) {
            $conversationId = (int)$request->conversation_id;
            $currentConversation = $conversations->firstWhere('id', $conversationId);

            if ($currentConversation && $admin instanceof User && $isAdmin && !$this->isParticipant(
                    $admin,
                    $currentConversation
                )) {
                $admin->conversations()->syncWithoutDetaching([$currentConversation->id]);
            }

            if ($currentConversation) {
                $messages = $currentConversation->messages()
                    ->with('participation.messageable')
                    ->orderBy('created_at', 'desc')
                    ->limit(50)
                    ->get()
                    ->reverse();

                $attachmentsByMessage = ChatMessageAttachment::whereIn('message_id', $messages->pluck('id')->all())
                    ->get()
                    ->groupBy('message_id');
            }
        }

        $attachmentsByMessage = $attachmentsByMessage ?? collect();

        return view(
            'admin.management.chat.index',
            compact('conversations', 'currentConversation', 'messages', 'attachmentsByMessage')
        );
    }

    public function reply(Request $request, $conversationId)
    {
        $request->validate([
            'message'       => 'nullable|string|max:1000',
            'attachments'   => 'nullable|array|max:5',
            'attachments.*' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ], [
            'attachments.max'     => 'Bạn chỉ có thể tải lên tối đa 5 ảnh.',
            'attachments.*.image' => 'Tệp đính kèm phải là hình ảnh.',
            'attachments.*.mimes' => 'Chỉ hỗ trợ ảnh JPG, PNG hoặc WEBP.',
            'attachments.*.max'   => 'Mỗi ảnh không được vượt quá 5MB.',
            'message.max'         => 'Nội dung không được vượt quá 1000 ký tự.',
        ]);

        $admin = Auth::user();
        $conversation = Chat::conversations()->getById($conversationId);

        if (!$admin instanceof User || !$conversation) {
            return response()->json(['success' => false], 404);
        }

        if (!$this->isParticipant($admin, $conversation)) {
            $admin->conversations()->syncWithoutDetaching([$conversation->id]);
        }

        $messageText = trim($request->input('message', ''));
        $files = $request->file('attachments') ?? [];
        $messageBody = $messageText === '' && count($files) ? ' ' : $messageText;

        // ✅ QUAN TRỌNG: gắn sender_id
        $message = Chat::message($messageBody)
            ->from($admin)
            ->to($conversation)
            ->data([
                'sender_id'   => $admin->id,
                'sender_type' => get_class($admin),
            ])
            ->send();

        $attachments = [];
        foreach ($files as $file) {
            $attachments[] = $this->storeAttachment($file, $message->id);
        }

        return response()->json([
            'success' => true,
            'message' => $this->formatMessagePayload($message, true, 'You', $attachments),
        ]);
    }

    // API để polling tin nhắn mới cho Admin
    public function fetchMessages(Request $request, $conversationId)
    {
        $admin = Auth::user();
        $conversation = Chat::conversations()->getById($conversationId);
        $conversation->unsetRelation('messages');
        if (!$admin instanceof User || !$conversation) {
            return response()->json(['messages' => []]);
        }

        if (!$this->isParticipant($admin, $conversation)) {
            $admin->conversations()->syncWithoutDetaching([$conversation->id]);
        }

        $lastId = (int)$request->input('last_id', 0);

        $messages = $conversation->messages()
            ->with('participation.messageable')
            ->when($lastId > 0, fn($q) => $q->where('id', '>', $lastId))
            ->orderBy('id', 'asc')
            ->get();

        $attachmentsByMessage = ChatMessageAttachment::whereIn('message_id', $messages->pluck('id'))
            ->get()
            ->groupBy('message_id');

        $formattedMessages = [];

        foreach ($messages as $message) {
            $messageData = $this->decodeMessageData($message->data);

            $isMe = false;

            if ($messageData && isset($messageData['sender_type'])) {
                $isMe = $messageData['sender_type'] === \App\Models\User::class;
            } else {
                // Fallback: nếu không có data, check qua participation relationship
                $messageable = optional($message->participation)->messageable;
                $isMe = $messageable instanceof \App\Models\User;
            }
            $attachments = collect($attachmentsByMessage->get($message->id))
                ->map(fn($att) => $this->formatAttachment($att))
                ->toArray();

            $formattedMessages[] = $this->formatMessagePayload(
                $message,
                $isMe,
                $isMe ? 'You' : 'Customer',
                $attachments
            );
        }

        return response()->json(['messages' => $formattedMessages]);
    }

    private function isAdminUser(?User $user): bool
    {
        return $user instanceof User && $user->isAdmin();
    }

    private function isSystemMessage($message): bool
    {
        return data_get($message, 'data.system_message') === 'admin_busy';
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

    private function isParticipant(User $user, Conversation $conversation): bool
    {
        return $conversation->participants()
            ->where('messageable_id', $user->id)
            ->where('messageable_type', get_class($user))
            ->exists();
    }

    private function canAccessConversation(User $user, Conversation $conversation): bool
    {
        if ($this->isAdminUser($user)) {
            return true;
        }

        return $this->isParticipant($user, $conversation);
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
    private function formatMessagePayload($message, bool $isMe, string $senderName, $attachments = null): array
    {
        $createdAt = $message->created_at instanceof \Carbon\Carbon
            ? $message->created_at
            : \Carbon\Carbon::parse($message->created_at);

        // Handle both single attachment (legacy) and array of attachments
        $formattedAttachments = [];
        if ($attachments) {
            if (is_array($attachments)) {
                // Check if it's an array of attachment objects or an array of arrays
                if (!empty($attachments) && is_array($attachments[0])) {
                    // Array of attachments (already formatted)
                    $formattedAttachments = $attachments;
                } elseif (!empty($attachments) && isset($attachments['url'])) {
                    // Single attachment (has url key)
                    $formattedAttachments = [$attachments];
                }
            }
        }

        return [
            'id'               => $message->id,
            'body'             => $message->body,
            'created_at'       => $createdAt->format('H:i'),
            'created_at_raw'   => $createdAt->toIso8601String(),
            'created_at_date'  => $createdAt->format('Y-m-d'),
            'created_at_label' => $createdAt->isToday()
                ? 'Hôm nay'
                : ($createdAt->isYesterday() ? 'Hôm qua' : $createdAt->format('d/m/Y')),
            'is_me'            => $isMe,
            'sender_name'      => $senderName,
            'attachments'      => $formattedAttachments,
            'attachment'       => $formattedAttachments[0] ?? null, // For backward compatibility
        ];
    }

}
