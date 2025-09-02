<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('shipping_address');
            $table->decimal('subtotal', 12, 0);
            $table->decimal('shipping_fee', 12, 0);
            $table->decimal('total', 12, 0);
            $table->enum('payment_method', ['bank_transfer', 'qr_code', 'cod']);
            $table->enum('status', ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled']);
            $table->text('qr_code')->nullable();
            $table->json('payment_info')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};