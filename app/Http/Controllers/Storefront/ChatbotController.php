<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\ChatbotRule;
use App\Services\Storefront\ChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{

    public function sendMessage(Request $request, ChatbotService $chatbotService): JsonResponse
    {
        $validated = $request->validate(['message' => 'required|string|max:1000']);
        $sessionId = $request->session()->getId();

        $response = $chatbotService->handleMessage($sessionId, $validated['message']);

        return response()->json($response);
    }

    public function getSuggestions(): JsonResponse
    {
        $suggestions = ChatbotRule::where('is_active', true)->pluck('keyword');
        return response()->json($suggestions);
    }

}
