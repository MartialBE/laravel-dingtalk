<?php



return [
    'defaults' => [
        /**
         * 使用缓存
         */
        'use_laravel_cache' => true,
    ],

    'log' => [
        'default' => env('APP_DEBUG', false) ? 'dev' : 'prod', // 默认使用的 channel，生产环境可以改为下面的 prod
        'channels' => [
            // 测试环境
            'dev' => [
                'driver' => 'single',
                'path'   => storage_path('logs/dingtalk.log'),
                'level'  => 'debug',
            ],
            // 生产环境
            'prod' => [
                'driver' => 'daily',
                'path'   => storage_path('logs/dingtalk.log'),
                'level'  => 'info',
            ],
        ],
    ],
    'ding_config' => [
        'protocol' => 'https',
        'regionId' => 'central',
    ],

    'work_bot' => [
        'default' => [
            'app_key'    => '',
            'app_secret' => '',
        ]
    ],
    'custom_bot' => [
        'default' => [
            'access_token' => '',
            'secret'       => '',
        ]
    ]
];
