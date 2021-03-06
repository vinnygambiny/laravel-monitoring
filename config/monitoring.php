<?php

return [

    'path' => 'monitoring',

    'middleware' => ['web'],

    'threshold_max_wait_time' => 300, // seconds

    'status_pages' => [],

    'healthchecks' => [
        'key' => env('HEALTHCHECKS_KEY', ''),
    ],

    'aws' => [
        'cloudwatch' => true,

        'config' => [
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID', ''),
                'secret' => env('AWS_SECRET_ACCESS_KEY', ''),
            ],
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'version' => 'latest',
        ],

        'graphs' => [],
    ],

];
