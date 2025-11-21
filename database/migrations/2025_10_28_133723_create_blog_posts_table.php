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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // Unique slug for SEO-friendly URLs
            $table->text('content');          // Post content (can store HTML)
            $table->string('image')->nullable(); // Featured image path
            $table->boolean('is_published')->default(false); // Status: published or draft
            $table->timestamp('published_at')->nullable(); // Optional: date when it was published
            // $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Optional: Link to author (admin user)
            // $table->foreignId('blog_category_id')->nullable()->constrained('blog_categories'); // Optional: Link to category
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }

};
