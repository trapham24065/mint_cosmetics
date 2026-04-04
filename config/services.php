<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack'   => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'ghn' => [
        'base_url'         => env('GHN_BASE_URL', 'https://dev-online-gateway.ghn.vn/shiip/public-api'),
        'token'            => env('GHN_TOKEN'),
        'shop_id'          => env('GHN_SHOP_ID'),
        'from_district_id' => env('GHN_FROM_DISTRICT_ID'),
        'from_ward_code'   => env('GHN_FROM_WARD_CODE'),
        'from_name'        => env('GHN_FROM_NAME', env('APP_NAME', 'Mint Cosmetics')),
        'from_phone'       => env('GHN_FROM_PHONE'),
        'from_address'     => env('GHN_FROM_ADDRESS', env('APP_NAME', 'Mint Cosmetics')),
        'service_type_id'  => env('GHN_SERVICE_TYPE_ID', 2),
        'verify_ssl'       => env('GHN_VERIFY_SSL', false),
    ],
    'panther' => [
        'chrome_driver' => env('PANTHER_CHROME_DRIVER', 'drivers/chromedriver.exe'),
    ],
];
