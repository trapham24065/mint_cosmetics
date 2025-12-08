<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Musonza\Chat\Facades\ChatFacade;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\Storefront\ChatbotService;

class ChatController extends Controller
{

    /**
     *
     * Show chat interface.
     */
    public function index(): View|RedirectResponse
    {
        $participant = $this->getParticipant();

        $admin = User::first();

        if (!$admin) {
            return redirect()->route('home')->with('error', 'The system is not ready.');
        }

        $conversation = $participant->conversations->sortByDesc('updated_at')->first();

        $messages = [];
        if ($conversation) {
            $messages = $conversation->messages()
                ->with('participation.messageable')
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get()
                ->reverse();
        }
        return view('storefront.chat.index', compact('conversation', 'messages', 'participant'));
    }

    public function sendMessage(Request $request, ChatbotService $chatbotService): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
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

        $message = ChatFacade::message($request->message)
            ->from($participant)
            ->to($conversation)
            ->send();

        $serviceResponse = $chatbotService->handleMessage(Session::getId(), $request->message);

        $botReplyText = null;
        if (!empty($serviceResponse['reply'])) {
            $botReplyText = $serviceResponse['reply'];
        }

        $botMessage = null;
        if ($botReplyText) {
            $botMessage = ChatFacade::message($botReplyText)
                ->from($admin)
                ->to($conversation)
                ->send();
        }

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

}
