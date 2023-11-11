<?php

$dsn = $_ENV['DB_DSN'] ?? 'pgsql:host=localhost;dbname=test';
$username = $_ENV['DB_USERNAME'] ?? 'test';
$password = $_ENV['DB_PASSWORD'] ?? 'test';

return [
    'class' => 'yii\db\Connection',
    'dsn' => $dsn,
    'username' => $username,
    'password' => $password,
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];
