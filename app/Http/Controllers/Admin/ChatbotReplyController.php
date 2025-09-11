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
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'topic'     => ['required', 'string', 'max:255', 'unique:chatbot_replies'],
            'reply'     => ['required', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
        $validated['is_active'] = $request->has('is_active');

        ChatbotReply::create($validated);
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
    public function update(Request $request, ChatbotReply $chatbotReply): RedirectResponse
    {
        $validated = $request->validate([
            'topic'     => [
                'required',
                'string',
                'max:255',
                Rule::unique('chatbot_replies')->ignore($chatbotReply->id),
            ],
            'reply'     => ['required', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
        $validated['is_active'] = $request->has('is_active');

        $chatbotReply->update($validated);
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
    public function storeKeyword(Request $request, ChatbotReply $reply): RedirectResponse
    {
        $validated = $request->validate([
            'keyword' => [
                'required',
                'string',
                'max:255',
                Rule::unique('chatbot_keywords')->where('chatbot_reply_id', $reply->id),
            ],
        ]);

        $reply->keywords()->create($validated);
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
