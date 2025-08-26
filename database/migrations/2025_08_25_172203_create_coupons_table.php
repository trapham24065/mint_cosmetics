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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type'); // 'fixed_amount' or 'percentage'
            $table->decimal('value', 15, 2); // The discount value
            $table->decimal('min_purchase_amount', 15, 2)->nullable(); // Minimum order value to apply coupon
            $table->unsignedInteger('max_uses')->nullable(); // Max times it can be used
            $table->unsignedInteger('times_used')->default(0);
            $table->timestamp('starts_at')->nullable(); // Start date
            $table->timestamp('expires_at')->nullable(); // End date
            $table->boolean('is_active')->default(true); // Default status = 1
            $table->timestamps(); // Handles created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }

};
