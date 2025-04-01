<?php
return [
    'google' => [
        'callback' => env('GOOGLE_CALLBACK_URL'),
        'keys' => [
            'id' => env('GOOGLE_CLIENT_ID'),
            'secret' => env('GOOGLE_CLIENT_SECRET'),
        ],
        'scope' => 'https://www.googleapis.com/auth/youtube https://www.googleapis.com/auth/youtube.upload',
        'authorize_url_parameters' => [
            'approval_prompt' => 'force', // Yêu cầu Google trả về refresh_token
            'access_type' => 'offline',  // Đảm bảo nhận được refresh_token
        ],
    ],
];
