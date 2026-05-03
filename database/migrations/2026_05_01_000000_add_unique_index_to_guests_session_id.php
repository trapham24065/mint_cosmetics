<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Guest;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            $duplicateSessionIds = DB::table('guests')
                ->select('session_id')
                ->whereNotNull('session_id')
                ->groupBy('session_id')
                ->havingRaw('COUNT(*) > 1')
                ->pluck('session_id');

            foreach ($duplicateSessionIds as $sessionId) {
                $guestIds = DB::table('guests')
                    ->where('session_id', $sessionId)
                    ->orderByDesc('id')
                    ->pluck('id')
                    ->all();

                $keepGuestId = array_shift($guestIds);

                if (!$keepGuestId || empty($guestIds)) {
                    continue;
                }

                // Reattach existing chat participation rows so the kept guest retains the full history.
                DB::table('chat_participation')
                    ->where('messageable_type', Guest::class)
                    ->whereIn('messageable_id', $guestIds)
                    ->update([
                        'messageable_id' => $keepGuestId,
                        'updated_at'     => now(),
                    ]);

                DB::table('guests')
                    ->whereIn('id', $guestIds)
                    ->delete();
            }

            Schema::table('guests', function (Blueprint $table) {
                // Prevent duplicate guest rows while preserving chat history.
                $table->unique('session_id');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropUnique(['session_id']);
        });
    }
};
