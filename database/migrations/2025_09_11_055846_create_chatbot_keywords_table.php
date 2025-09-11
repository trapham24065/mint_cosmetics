<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chatbot_keywords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chatbot_reply_id')->constrained('chatbot_replies')->cascadeOnDelete();
            $table->string('keyword');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_keywords');
    }

};
