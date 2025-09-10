<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key'         => 'site_name',
                'value'       => 'Mint Cosmetics',
                'type'        => 'text',
                'group'       => 'general',
                'label'       => 'Tên Website',
                'description' => 'Tên hiển thị của website.',
                'sort_order'  => 1,
            ],
            [
                'key'        => 'contact_email',
                'value'      => 'contact@mintcosmetics.com',
                'type'       => 'email',
                'group'      => 'general',
                'label'      => 'Email liên hệ',
                'sort_order' => 2,
            ],
            [
                'key'        => 'contact_phone',
                'value'      => '0123456789',
                'type'       => 'text',
                'group'      => 'general',
                'label'      => 'Số điện thoại',
                'sort_order' => 3,
            ],

            // VietQR Settings
            [
                'key'        => 'vietqr_bank_id',
                'value'      => '970436',
                'type'       => 'select',
                'group'      => 'payment',
                'label'      => 'Ngân hàng VietQR',
                'options'    => json_encode(['970436' => 'Vietcombank', '970422' => 'MB Bank', '970423' => 'TPBank']),
                'sort_order' => 1,
            ],
            [
                'key'        => 'vietqr_account_no',
                'value'      => '1032850005',
                'type'       => 'text',
                'group'      => 'payment',
                'label'      => 'Số tài khoản VietQR',
                'sort_order' => 2,
            ],
            [
                'key'        => 'vietqr_prefix',
                'value'      => 'DH',
                'type'       => 'text',
                'group'      => 'payment',
                'label'      => 'Tiền tố đơn hàng',
                'sort_order' => 3,
            ],

            // Mail From Settings
            [
                'key'        => 'mail_from_name',
                'value'      => 'Mint Cosmetics',
                'type'       => 'text',
                'group'      => 'email',
                'label'      => 'Tên người gửi Email',
                'sort_order' => 1,
            ],
            [
                'key'        => 'mail_from_address',
                'value'      => 'no-reply@mintcosmetics.com',
                'type'       => 'email',
                'group'      => 'email',
                'label'      => 'Địa chỉ Email gửi đi',
                'sort_order' => 2,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }

}
