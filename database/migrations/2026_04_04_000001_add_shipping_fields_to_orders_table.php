<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('shipping_fee', 15, 2)->default(0)->after('discount_amount');
            $table->string('shipping_provider')->nullable()->after('shipping_fee');
            $table->unsignedBigInteger('shipping_province_id')->nullable()->after('shipping_provider');
            $table->unsignedBigInteger('shipping_district_id')->nullable()->after('shipping_province_id');
            $table->string('shipping_ward_code')->nullable()->after('shipping_district_id');
            $table->string('ghn_order_code')->nullable()->after('shipping_ward_code');
            $table->json('ghn_response')->nullable()->after('ghn_order_code');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_fee',
                'shipping_provider',
                'shipping_province_id',
                'shipping_district_id',
                'shipping_ward_code',
                'ghn_order_code',
                'ghn_response',
            ]);
        });
    }
};
