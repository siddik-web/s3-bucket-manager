<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AWS S3 Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the AWS S3 settings used by the S3 Bucket Manager.
    |
    */

    'aws' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | S3 Bucket Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the S3 buckets that the S3 Bucket Manager will
    | interact with. You can specify the bucket name, visibility settings,
    | and other bucket-specific configurations.
    |
    */

    'buckets' => [
        'public' => [
            'name' => env('AWS_BUCKET_PUBLIC', 'your-public-bucket-name'),
            'visibility' => 'public-read',
        ],
        'private' => [
            'name' => env('AWS_BUCKET_PRIVATE', 'your-private-bucket-name'),
            'visibility' => 'private',
        ],
    ],
];