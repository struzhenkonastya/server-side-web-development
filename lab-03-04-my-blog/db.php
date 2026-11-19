<?php
declare(strict_types=1);

function getBlogDatabase(): PDO
{
    $local = __DIR__ . '/config.local.php';
    $example = __DIR__ . '/config.example.php';
    $config = require is_file($local) ? $local : $example;

    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        $config['host'], (int) ($config['port'] ?? 3306), $config['database']);

    return new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}
