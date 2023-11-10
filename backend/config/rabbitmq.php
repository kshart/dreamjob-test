<?php
$host = $_ENV['RABBITMQ_HOST'] ?? '127.0.0.1';
$port = $_ENV['RABBITMQ_PORT'] ?? 5672;
$user = $_ENV['RABBITMQ_USER'] ?? 'test';
$password = $_ENV['RABBITMQ_PASSWORD'] ?? 'test';

return function (string $queueName, int $attempts = 3, int $ttr = 5 * 60) use ($host, $port, $user, $password) {
    return [
        'class' => \yii\queue\amqp_interop\Queue::class,
        'driver' => yii\queue\amqp_interop\Queue::ENQUEUE_AMQP_LIB,
        'as log' => \yii\queue\LogBehavior::class,
        'host' => $host,
        'port' => $port,
        'user' => $user,
        'password' => $password,
        'queueName' => $queueName,
        'exchangeName' => "exchange.$queueName",
        'attempts' => $attempts,
        'ttr' => $ttr,
        // 'on afterError' => function (yii\queue\ExecEvent $event) {
        //     app\components\ConsoleHelper::exception($event->error);
        // },
    ];
};
