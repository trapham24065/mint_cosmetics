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
            'topic' => 'Shipping Policy',
            'reply' => 'We offer free shipping for all orders over 500,000 VND.',
        ]);
        $shippingReply->keywords()->createMany([
            ['keyword' => 'shipping'],
            ['keyword' => 'delivery'],
            ['keyword' => 'ship'],
            ['keyword' => 'freeship'],
        ]);

        // Rule 2: Return Policy
        $returnReply = ChatbotReply::create([
            'topic' => 'Return Policy',
            'reply' => 'You can return any item within 7 days of purchase, provided it is in its original condition. Please contact our support hotline to initiate a return.',
        ]);
        $returnReply->keywords()->createMany([
            ['keyword' => 'return'],
            ['keyword' => 'exchange'],
            ['keyword' => 'refund'],
        ]);
    }

}
