<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/10/2025
 * @time 11:35 PM
 */

namespace App\Services\Storefront;

use App\Models\ChatbotRule;
use Illuminate\Support\Str;

class ChatbotService
{

    /**
     * Find the best reply from Quick Hints for a given user message.
     * SIMPLIFIED: Only checks Quick Hints (chatbot_rules table).
     * Training Center (keyword matching) has been removed.
     * Conversation history is now handled by musonza/chat package in the controller.
     *
     * @param  string  $userMessage  The message content from the user.
     *
     * @return string|null Returns reply if found, null otherwise
     */
    public function findReply(string $userMessage): ?string
    {
        $userMessageLower = Str::lower($userMessage);

        // Check if the message is a direct match from a Quick Hint (chatbot_rules table)
        $quickHintRule = ChatbotRule::where('keyword', $userMessageLower)
            ->where('is_active', true)
            ->first();

        if ($quickHintRule) {
            return $quickHintRule->reply;
        }

        // No match found - return null (will be handled by controller)
        return null;
    }
}
