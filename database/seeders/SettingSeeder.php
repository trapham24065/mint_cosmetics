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
                'label'       => 'Website name',
                'description' => 'Website display name.',
                'sort_order'  => 1,
            ],
            [
                'key'        => 'contact_email',
                'value'      => 'contact@mintcosmetics.com',
                'type'       => 'email',
                'group'      => 'general',
                'label'      => 'Contact email',
                'sort_order' => 2,
            ],
            [
                'key'        => 'contact_phone',
                'value'      => '0123456789',
                'type'       => 'text',
                'group'      => 'general',
                'label'      => 'Phone number',
                'sort_order' => 3,
            ],

            // VietQR Settings
            [
                'key'        => 'vietqr_bank_id',
                'value'      => '970436',
                'type'       => 'select',
                'group'      => 'payment',
                'label'      => 'VietQR Bank',
                'options'    => json_encode(
                    ['970436' => 'Vietcombank', '970422' => 'MB Bank', '970423' => 'TPBank'],
                    JSON_THROW_ON_ERROR
                ),
                'sort_order' => 1,
            ],
            [
                'key'        => 'vietqr_account_no',
                'value'      => '1032850005',
                'type'       => 'text',
                'group'      => 'payment',
                'label'      => 'Account number VietQR',
                'sort_order' => 2,
            ],
            [
                'key'        => 'vietqr_prefix',
                'value'      => 'DH',
                'type'       => 'text',
                'group'      => 'payment',
                'label'      => 'Order prefix',
                'sort_order' => 3,
            ],

            // Mail From Settings
            [
                'key'        => 'mail_from_name',
                'value'      => 'Mint Cosmetics',
                'type'       => 'text',
                'group'      => 'email',
                'label'      => 'Email sender name',
                'sort_order' => 1,
            ],
            [
                'key'        => 'mail_from_address',
                'value'      => 'no-reply@mintcosmetics.com',
                'type'       => 'email',
                'group'      => 'email',
                'label'      => 'Email address',
                'sort_order' => 2,
            ],
            // SMTP configuration (overridable via settings)
            [
                'key'        => 'mail_driver',
                'value'      => 'smtp',
                'type'       => 'select',
                'group'      => 'email',
                'label'      => 'Mail driver',
                'options'    => json_encode([
                    'smtp'      => 'SMTP',
                    'sendmail'  => 'Sendmail',
                    'mailgun'   => 'Mailgun',
                    'ses'       => 'SES',
                    'postmark'  => 'Postmark',
                    'resend'    => 'Resend',
                    'log'       => 'Log',
                    'array'     => 'Array',
                ], JSON_THROW_ON_ERROR),
                'sort_order' => 3,
            ],
            [
                'key'        => 'mail_host',
                'value'      => '127.0.0.1',
                'type'       => 'text',
                'group'      => 'email',
                'label'      => 'SMTP host',
                'sort_order' => 4,
            ],
            [
                'key'        => 'mail_port',
                'value'      => '2525',
                'type'       => 'text',
                'group'      => 'email',
                'label'      => 'SMTP port',
                'sort_order' => 5,
            ],
            [
                'key'        => 'mail_username',
                'value'      => null,
                'type'       => 'text',
                'group'      => 'email',
                'label'      => 'SMTP username',
                'sort_order' => 6,
            ],
            [
                'key'        => 'mail_password',
                'value'      => null,
                'type'       => 'password',
                'group'      => 'email',
                'label'      => 'SMTP password',
                'sort_order' => 7,
            ],
            [
                'key'        => 'mail_encryption',
                'value'      => 'tls',
                'type'       => 'select',
                'group'      => 'email',
                'label'      => 'Encryption',
                'options'    => json_encode(['tls' => 'TLS', 'ssl' => 'SSL', '' => 'None'], JSON_THROW_ON_ERROR),
                'sort_order' => 8,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
