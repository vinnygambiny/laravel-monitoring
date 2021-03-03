<?php

return [

    'path' => 'monitoring',

    'middleware' => ['web'],

    'status_pages' => [],

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
