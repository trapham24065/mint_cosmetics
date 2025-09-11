<?php
/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/11/2025
 * @time 11:18 AM
 */

namespace App\Services\Admin;

use App\Models\ChatbotRule;

class ChatbotRuleService
{

    public function createRule(array $data): ChatbotRule
    {
        return ChatbotRule::create($data);
    }

    public function updateRule(ChatbotRule $rule, array $data): bool
    {
        return $rule->update($data);
    }

    public function deleteRule(ChatbotRule $rule): ?bool
    {
        return $rule->delete();
    }

}
