<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', static function (Blueprint $table): void {
            $table->unsignedBigInteger('shipping_province_id')->nullable()->after('city');
            $table->unsignedBigInteger('shipping_district_id')->nullable()->after('shipping_province_id');
            $table->string('shipping_ward_code', 50)->nullable()->after('shipping_district_id');
            $table->string('shipping_province_name')->nullable()->after('shipping_ward_code');
            $table->string('shipping_district_name')->nullable()->after('shipping_province_name');
            $table->string('shipping_ward_name')->nullable()->after('shipping_district_name');
        });
    }

    public function down(): void
    {
        Schema::table('customers', static function (Blueprint $table): void {
            $table->dropColumn([
                'shipping_province_id',
                'shipping_district_id',
                'shipping_ward_code',
                'shipping_province_name',
                'shipping_district_name',
                'shipping_ward_name',
            ]);
        });
    }
};
