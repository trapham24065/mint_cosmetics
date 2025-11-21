<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotKeyword;
use App\Models\ChatbotReply;
use App\Models\ChatbotRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Http\Requests\Chatbot\StoreChatbotKeywordRequest;

use App\Http\Requests\Chatbot\StoreChatbotReplyRequest;

use App\Http\Requests\Chatbot\UpdateChatbotReplyRequest;

// Import Update Reply Request
class ChatbotReplyController extends Controller
{

    /**
     * Display a listing of the chatbot replies.
     */
    public function index(): View
    {
        $replies = ChatbotReply::with('keywords')->latest()->paginate(15);
        return view('admin.management.chatbot.replies.index', compact('replies'));
    }

    /**
     * Show the form for creating a new chatbot reply.
     */
    public function create(): View
    {
        return view('admin.management.chatbot.replies.create');
    }

    /**
     * Store a newly created chatbot reply in storage.
     */
    public function store(StoreChatbotReplyRequest $request): RedirectResponse
    {
        ChatbotReply::create($request->validated());
        return redirect()->route('admin.chatbot-replies.index')->with('success', 'Reply created successfully.');
    }

    /**
     * Show the form for editing the specified chatbot reply.
     */
    public function edit(ChatbotReply $chatbotReply): View
    {
        $chatbotReply->load('keywords');
        return view('admin.management.chatbot.replies.edit', ['reply' => $chatbotReply]);
    }

    /**
     * Update the specified chatbot reply in storage.
     */
    public function update(UpdateChatbotReplyRequest $request, ChatbotReply $chatbotReply): RedirectResponse
    {
        $chatbotReply->update($request->validated());

        return redirect()->route('admin.chatbot-replies.edit', $chatbotReply)->with(
            'success',
            'Reply updated successfully.'
        );
    }

    /**
     * Remove the specified chatbot reply from storage.
     */
    public function destroy(ChatbotReply $chatbotReply): RedirectResponse
    {
        $chatbotReply->delete();
        return redirect()->route('admin.chatbot-replies.index')->with('success', 'Reply deleted successfully.');
    }

    /**
     * Store a new keyword for a reply.
     */
    public function storeKeyword(StoreChatbotKeywordRequest $request, ChatbotReply $reply): RedirectResponse
    {
        $reply->keywords()->create($request->validated());
        return back()->with('success', 'Keyword added successfully.');
    }

    /**
     * Delete a specific keyword.
     */
    public function destroyKeyword(ChatbotKeyword $keyword): RedirectResponse
    {
        $keyword->delete();
        return back()->with('success', 'Keyword deleted successfully.');
    }

    /**
     * Provide data for the Grid.js table via AJAX.
     */
    public function getDataForGrid(): JsonResponse
    {
        $chat_bot_reply = ChatbotReply::with('keywords')->latest()->get();

        // Format data for Grid.js

        $data = $chat_bot_reply->map(function ($chat_bot_reply) {
            return [
                'id'        => $chat_bot_reply->id,
                'topic'     => $chat_bot_reply->topic,
                'reply'     => $chat_bot_reply->reply,
                'keywords'  => $chat_bot_reply->keywords->pluck('keyword')->implode(', '),
                'is_active' => $chat_bot_reply->is_active,
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
    }

}
