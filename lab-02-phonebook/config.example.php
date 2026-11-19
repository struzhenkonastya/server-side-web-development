<?php
declare(strict_types=1);

if (!defined('APP_BOOTSTRAPPED')) {
    http_response_code(404);
    exit;
}

return [
    'host' => '127.0.0.1',
    'database' => 'phonebook',
    'username' => 'root',
    'password' => '',
];
