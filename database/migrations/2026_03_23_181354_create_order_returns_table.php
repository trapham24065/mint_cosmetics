<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_code')->unique(); // Mã trả hàng: RT-20260323-ABCD
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();

            // Thông tin trả hàng
            $table->text('reason'); // Lý do trả hàng
            $table->text('description')->nullable(); // Mô tả chi tiết
            $table->string('status')->default('pending'); // pending, approved, rejected, refunded

            // Thông tin hoàn tiền
            $table->decimal('refund_amount', 15, 2)->default(0); // Số tiền hoàn lại
            $table->string('refund_method')->nullable(); // Phương thức hoàn tiền
            $table->string('refund_transaction_id')->nullable(); // Mã giao dịch hoàn tiền
            $table->timestamp('refunded_at')->nullable(); // Thời gian hoàn tiền

            // Ghi chú admin
            $table->text('admin_note')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete(); // Admin xử lý
            $table->timestamp('processed_at')->nullable(); // Thời gian xử lý

            $table->timestamps();
        });

        // Bảng chi tiết sản phẩm trả
        Schema::create('order_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_return_id')->constrained('order_returns')->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->integer('quantity'); // Số lượng trả
            $table->decimal('refund_price', 15, 2); // Giá hoàn lại cho item này
            $table->text('item_reason')->nullable(); // Lý do trả item này (nếu khác lý do chung)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_return_items');
        Schema::dropIfExists('order_returns');
    }
};
