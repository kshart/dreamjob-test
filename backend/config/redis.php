<?php
$host = $_ENV['REDIS_HOST'] ?? '127.0.0.1';
$port = $_ENV['REDIS_PORT'] ?? 6379;

return [
    'cache' => [
        'class' => yii\redis\Cache::class,
        'redis' => [
            'hostname' => $host,
            'port' => $port,
            'database' => 0,
        ],
    ],
    'redis' => [
        'class' => yii\redis\Connection::class,
        'hostname' => $host,
        'port' => $port,
        'database' => 1,
        'retries' => 1,
    ],
];
