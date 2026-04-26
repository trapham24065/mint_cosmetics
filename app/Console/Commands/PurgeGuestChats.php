<?php

namespace App\Console\Commands;

use App\Models\Guest;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Musonza\Chat\ConfigurationManager;
use Musonza\Chat\Models\Conversation;

class PurgeGuestChats extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'chat:purge-guest-chats {--days=7 : Retention period in days before guest chats are removed}';

    /**
     * The console command description.
     */
    protected $description = 'Delete guest conversations that have been inactive for too long';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = max(1, (int) $this->option('days'));
        $cutoff = Carbon::now()->subDays($days);

        $guestConversationIds = DB::table(ConfigurationManager::PARTICIPATION_TABLE)
            ->join(ConfigurationManager::CONVERSATIONS_TABLE, ConfigurationManager::CONVERSATIONS_TABLE . '.id', '=', ConfigurationManager::PARTICIPATION_TABLE . '.conversation_id')
            ->where(ConfigurationManager::PARTICIPATION_TABLE . '.messageable_type', Guest::class)
            ->where(function ($query) use ($cutoff) {
                $query->where(ConfigurationManager::CONVERSATIONS_TABLE . '.updated_at', '<', $cutoff)
                    ->orWhereNull(ConfigurationManager::CONVERSATIONS_TABLE . '.updated_at');
            })
            ->distinct()
            ->pluck(ConfigurationManager::PARTICIPATION_TABLE . '.conversation_id');

        $purgedConversations = 0;
        $purgedGuests = 0;

        if ($guestConversationIds->isNotEmpty()) {
            DB::beginTransaction();

            try {
                $purgedConversations = Conversation::query()
                    ->whereIn('id', $guestConversationIds)
                    ->delete();

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();

                Log::error('Failed to purge guest conversations', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $purgedGuests = Guest::query()
            ->whereDoesntHave('conversations')
            ->where(function ($query) use ($cutoff) {
                $query->where('updated_at', '<', $cutoff)
                    ->orWhereNull('updated_at');
            })
            ->delete();

        $this->info("Purged {$purgedConversations} guest conversation(s) and {$purgedGuests} guest record(s) older than {$days} day(s).");

        return self::SUCCESS;
    }
}
