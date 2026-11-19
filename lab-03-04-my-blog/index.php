<?php
declare(strict_types=1);

require_once __DIR__ . '/Controllers/PagesController.php';

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
$baseUrl = $scriptDir === '/' || $scriptDir === '.' ? '' : rtrim($scriptDir, '/');
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if ($baseUrl !== '' && ($requestPath === $baseUrl || str_starts_with($requestPath, $baseUrl . '/'))) {
    $requestPath = substr($requestPath, strlen($baseUrl)) ?: '/';
}
$path = '/' . trim($requestPath, '/');

$controller = new PagesController($baseUrl);
$routes = require __DIR__ . '/routes.php';
foreach ($routes as $route) {
    if (preg_match($route['pattern'], $path, $matches)) {
        $parameters = [];
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $parameters[] = rawurldecode($value);
            }
        }
        $controller->{$route['method']}(...$parameters);
        exit;
    }
}
http_response_code(404);
$controller->notFound();
