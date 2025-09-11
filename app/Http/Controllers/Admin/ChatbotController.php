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

class ChatbotController extends Controller
{

    public function index()
    {
        $rules = ChatbotRule::orderBy('id', 'desc')->paginate(15);
        return view('admin.management.chatbot.index', compact('rules'));
    }

    public function create()
    {
        return view('admin.management.chatbot.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'keyword'   => 'required|string|max:255|unique:chatbot_rules',
            'reply'     => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);
        $validated['is_active'] = $request->has('is_active');

        ChatbotRule::create($validated);
        return redirect()->route('admin.chatbot.index')->with('success', 'Rule created successfully.');
    }

    public function edit(ChatbotRule $rule)
    {
        return view('admin.management.chatbot.edit', compact('rule'));
    }

    public function update(Request $request, ChatbotRule $rule)
    {
        $validated = $request->validate([
            'keyword'   => ['required', 'string', 'max:255', Rule::unique('chatbot_rules')->ignore($rule->id)],
            'reply'     => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);
        $validated['is_active'] = $request->has('is_active');

        $rule->update($validated);
        return redirect()->route('admin.chatbot.index')->with('success', 'Rule updated successfully.');
    }

    public function destroy(ChatbotRule $rule)
    {
        $rule->delete();
        return redirect()->route('admin.chatbot.index')->with('success', 'Rule deleted successfully.');
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
