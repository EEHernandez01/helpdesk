<?php

return [
    'server' => [
        'host' => env('REVERB_HOST', '127.0.0.1'),
        'port' => env('REVERB_PORT', 6001),
        'hostname' => env('REVERB_HOSTNAME'),
        'max_request_size' => env('REVERB_MAX_REQUEST_SIZE', 10_000_000),
    ],

    'apps' => [
        [
            'key' => env('REVERB_APP_KEY', env('PUSHER_APP_KEY')),
            'secret' => env('REVERB_APP_SECRET', env('PUSHER_APP_SECRET')),
            'app_id' => env('REVERB_APP_ID', env('PUSHER_APP_ID')),
            'name' => env('APP_NAME', 'laravel'),
            'allowed_origins' => [env('APP_URL', 'http://localhost')],
        ],
    ],

    'pulse_ingest' => env('REVERB_PULSE_INGEST', false),
];
