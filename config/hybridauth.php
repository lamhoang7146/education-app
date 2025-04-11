<?php
return [
    'google' => [
        'callback' => env('GOOGLE_CALLBACK_URL'),
        'keys' => [
            'id' => env('GOOGLE_CLIENT_ID'),
            'secret' => env('GOOGLE_CLIENT_SECRET'),
        ],
        'scope' => 'https://www.googleapis.com/auth/drive https://www.googleapis.com/auth/drive.file',
        'authorize_url_parameters' => [
            'approval_prompt' => 'force',
            'access_type' => 'offline',
        ],
    ],
];

