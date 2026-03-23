<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/11/2025
 * @time 11:19 AM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Chatbot\StoreChatbotRuleRequest;
use App\Http\Requests\Chatbot\UpdateChatbotRuleRequest;
use Illuminate\View\View;

class ChatbotController extends Controller
{

    public function index(): View
    {
        $rules = ChatbotRule::orderBy('id', 'desc')->paginate(15);
        return view('admin.management.chatbot.index', compact('rules'));
    }

    public function create(): View
    {
        return view('admin.management.chatbot.create');
    }

    public function store(StoreChatbotRuleRequest $request): RedirectResponse
    {
        ChatbotRule::create($request->validated());

        return redirect()->route('admin.chatbot.index')
            ->with('success', 'Quy tắc đã được tạo thành công.');
    }

    public function edit(ChatbotRule $chatbot)
    {
        return view('admin.management.chatbot.edit', compact('chatbot'));
    }

    public function update(UpdateChatbotRuleRequest $request, ChatbotRule $chatbot): RedirectResponse
    {
        $chatbot->update($request->validated());
        return redirect()->route('admin.chatbot.index')->with('success', 'Quy tắc đã được cập nhật thành công.');
    }

    public function destroy(ChatbotRule $rule): RedirectResponse|JsonResponse
    {
        try {
            $rule->delete();
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Quy tắc chatbot đã được xóa thành công.',
                ]);
            }

            return redirect()->route('admin.chatbot.index')
                ->with('success', 'Quy tắc đã được xóa thành công.');
        } catch (\Exception $e) {
            Log::error('Xóa quy tắc Chatbot không thành công: ' . $e->getMessage());

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete category: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->route('admin.chatbot.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Provide data for the Grid.js table via AJAX.
     */
    public function getDataForGrid(): JsonResponse
    {
        $chat_bot_rules = ChatbotRule::orderBy('id', 'desc')->get();

        // Format data for Grid.js
        $data = $chat_bot_rules->map(function ($chat_bot_rule) {
            return [
                'id'        => $chat_bot_rule->id,
                'keyword'   => $chat_bot_rule->keyword,
                'reply'     => $chat_bot_rule->reply,
                'is_active' => $chat_bot_rule->is_active,
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
    }
}
