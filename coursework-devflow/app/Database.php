<?php
declare(strict_types=1);
final class Database {
    private static ?PDO $connection = null;
    public static function get(): PDO {
        if (self::$connection !== null) return self::$connection;
        $file = __DIR__ . '/config.local.php';
        if (!is_file($file)) throw new RuntimeException('Не найден app/config.local.php. Скопируйте app/config.example.php и укажите параметры MySQL.');
        $c = require $file;
        foreach (['host', 'database', 'username', 'password'] as $key) {
            if (!is_array($c) || !array_key_exists($key, $c)) throw new RuntimeException('В конфигурации отсутствует ' . $key);
        }
        $host = (string)$c['host']; $dbname = (string)$c['database'];
        $port = (int)($c['port'] ?? 3306);
        return self::$connection = new PDO(
            "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4",
            (string)$c['username'], (string)$c['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
             PDO::ATTR_EMULATE_PREPARES => false]
        );
    }
}
