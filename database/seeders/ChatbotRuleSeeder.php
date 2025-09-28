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
                'keyword' => 'Shipping policy',
                'reply'   => 'We offer free shipping on orders over 500,000 VND.',
            ],
            [
                'keyword' => 'Current promotions',
                'reply'   => 'There is currently a 10% discount on all lipsticks. Check it out!',
            ],
            [
                'keyword' => 'How to return goods?',
                'reply'   => 'You can return the product within 7 days if the label is still intact. Please contact the hotline for support.',
            ],
        ];

        foreach ($rules as $rule) {
            ChatbotRule::create($rule);
        }
    }

}
