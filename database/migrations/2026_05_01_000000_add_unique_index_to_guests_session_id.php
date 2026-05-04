<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Guest;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Phần DML (data manipulation) giữ trong transaction
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
        });

        // Phần DDL (schema change) để NGOÀI transaction
        Schema::table('guests', function (Blueprint $table) {
            try {
                $table->dropUnique(['session_id']);
            } catch (\Exception $e) {
                // Chưa có index → bỏ qua
            }

            $table->unique('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            // Kiểm tra index đã tồn tại chưa trước khi tạo
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('guests');

            if (!array_key_exists('guests_session_id_unique', $indexes)) {
                $table->unique('session_id');
            }
        });
    }

};
