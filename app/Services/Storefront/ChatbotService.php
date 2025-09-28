<?php
/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/10/2025
 * @time 11:35 PM
 */

namespace App\Services\Storefront;

use App\Models\ChatbotKeyword;
use App\Models\ChatbotReply;
use App\Models\ChatbotRule;
use App\Models\Conversation;
use Illuminate\Support\Str;

class ChatbotService
{

    /**
     * Handle an incoming message from a user.
     *
     * @param  string  $sessionId  The user's session ID.
     * @param  string  $userMessage  The message content from the user.
     *
     * @return array The bot's reply.
     */
    public function handleMessage(string $sessionId, string $userMessage): array
    {
        $conversation = Conversation::firstOrCreate(['session_id' => $sessionId]);
        $conversation->messages()->create(['content' => $userMessage, 'sender' => 'user']);

        $botReply = $this->findBestReply($userMessage);

        $conversation->messages()->create(['content' => $botReply, 'sender' => 'bot']);
        return ['reply' => $botReply];
    }

    /**
     * Find the best reply from the knowledge base for a given user message.
     *
     * @param  string  $userMessage
     *
     * @return string
     */
    private function findBestReply(string $userMessage): string
    {
        $userMessageLower = Str::lower($userMessage);
        $defaultReply = "I'm sorry, I don't understand the question. You can try one of the suggestions or contact our support team.";

        // --- NEW, IMPROVED LOGIC ---

        // 1. First, check if the message is a direct match from a Quick Hint (chatbot_rules table).
        // This is the highest priority.
        $quickHintRule = ChatbotRule::where('keyword', $userMessageLower)->where('is_active', true)->first();
        if ($quickHintRule) {
            return $quickHintRule->reply;
        }

        // 2. If it's not a quick hint, try to find a direct match for the entire phrase in the keyword table.
        $directKeywordMatch = ChatbotKeyword::where('keyword', $userMessageLower)->with('reply')->first();
        if ($directKeywordMatch && $directKeywordMatch->reply?->is_active) {
            return $directKeywordMatch->reply->reply;
        }

        // 3. If no direct match, fall back to splitting the message into words and "voting".
        $keywords = preg_split('/[\s,.;?]+/', $userMessageLower);
        $foundKeywords = ChatbotKeyword::whereIn('keyword', $keywords)->get();

        if ($foundKeywords->isNotEmpty()) {
            $replyIds = $foundKeywords->pluck('chatbot_reply_id');
            $bestReplyId = $replyIds->mode()[0] ?? null;

            if ($bestReplyId) {
                $reply = ChatbotReply::find($bestReplyId);
                if ($reply && $reply->is_active) {
                    return $reply->reply;
                }
            }
        }

        // 4. If no match is found anywhere, return the default reply.
        return $defaultReply;
    }

}
