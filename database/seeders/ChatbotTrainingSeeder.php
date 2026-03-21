<?php

namespace Database\Seeders;

use App\Models\ChatbotReply;
use Illuminate\Database\Seeder;

class ChatbotTrainingSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Rule 1: Shipping Policy
        $shippingReply = ChatbotReply::create([
            'topic' => 'Chính sách vận chuyển',
            'reply' => 'Chúng tôi cung cấp dịch vụ giao hàng miễn phí cho tất cả đơn hàng trên 500.000 VND.',
        ]);
        $shippingReply->keywords()->createMany([
            ['keyword' => 'shipping'],
            ['keyword' => 'delivery'],
            ['keyword' => 'ship'],
            ['keyword' => 'freeship'],
        ]);

        // Rule 2: Return Policy
        $returnReply = ChatbotReply::create([
            'topic' => 'Chính sách đổi trả',
            'reply' => 'Bạn có thể trả lại bất kỳ sản phẩm nào trong vòng 7 ngày kể từ ngày mua, với điều kiện sản phẩm còn nguyên trạng. Vui lòng liên hệ đường dây nóng hỗ trợ của chúng tôi để bắt đầu quy trình trả hàng.',
        ]);
        $returnReply->keywords()->createMany([
            ['keyword' => 'return'],
            ['keyword' => 'exchange'],
            ['keyword' => 'refund'],
        ]);
    }

}
