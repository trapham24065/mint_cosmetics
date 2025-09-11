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
        Schema::create('chatbot_replies', function (Blueprint $table) {
            $table->id();
            $table->string('topic'); // A name for the reply topic, e.g., "Shipping Policy"
            $table->text('reply');   // The bot's actual response
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_replies');
    }

};
