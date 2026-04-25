<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('is_public_visible')->default(true)->after('is_approved');
            $table->string('hidden_reason')->nullable()->after('is_public_visible');
            $table->timestamp('hidden_at')->nullable()->after('hidden_reason');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['is_public_visible', 'hidden_reason', 'hidden_at']);
        });
    }
};
