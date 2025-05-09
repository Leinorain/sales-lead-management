<?php

return [
    'settings' => [
        'doctrine' => [
            'dev_mode' => true,
            'cache_dir' => __DIR__ . '/../var/cache/doctrine',
            'metadata_dirs' => [__DIR__ . '/Domain'],
            'connection' => [
                'driver' => 'pdo_mysql',
                'host' => $_ENV['DB_HOST'],
                'port' => $_ENV['DB_PORT'],
                'dbname' => $_ENV['DB_DATABASE'],
                'user' => $_ENV['DB_USERNAME'],
                'password' => $_ENV['DB_PASSWORD'],
                'charset' => 'utf8mb4',
            ],
        ],
    ],
];
