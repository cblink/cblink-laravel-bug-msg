<?php

return [
    'notify' => [
        /*
         * 是否每次错误都通知（建议 false，否则可能会轰炸）
         */
        'every' => false,

        /*
         * 分钟单位，该区间同一错误只提醒一次
         */
        'interval' => 5,
    ],

    'cache' => [
        'prefix' => 'notice.exception.',
    ],

    'config' => [
        'debug' => false,
        'base_url' => null,
        'key' => '',
        'token' => '',
    ],
];
