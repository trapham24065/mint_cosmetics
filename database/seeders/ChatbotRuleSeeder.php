<?php

namespace Database\Seeders;

use App\Models\ChatbotRule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatbotRuleSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rules = [

            [
                'keyword' => 'Chính sách vận chuyển',
                'reply'   => 'Chúng tôi cung cấp dịch vụ vận chuyển miễn phí cho đơn hàng trên 500.000 VND.',
            ],
            [
                'keyword' => 'Khuyến mãi hiện tại',
                'reply'   => 'Hiện đang có chương trình giảm giá 10% cho tất cả các loại son môi. Hãy xem ngay!',
            ],
            [
                'keyword' => 'Cách thức đổi trả hàng?',
                'reply'   => 'Bạn có thể đổi trả sản phẩm trong vòng 7 ngày nếu tem nhãn còn nguyên vẹn. Vui lòng liên hệ hotline để được hỗ trợ.',
            ],
        ];

        foreach ($rules as $rule) {
            ChatbotRule::create($rule);
        }
    }

}
