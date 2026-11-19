<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(['httponly'=>true, 'samesite'=>'Lax', 'secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off']);
    session_start();
}
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!is_file($autoload)) {
    http_response_code(503);
    echo '<!doctype html><html lang="ru"><meta charset="utf-8"><title>DevFlow — установка</title><body style="font:18px system-ui;background:#f5f6fa;padding:8%"><h1>Требуется установка фреймворка</h1><p>В корне проекта выполните <code>composer install</code>, чтобы установить Flight PHP.</p></body></html>';
    exit;
}
require $autoload;
require __DIR__ . '/../app/helpers.php';
require __DIR__ . '/../app/Database.php';
foreach (glob(__DIR__ . '/../app/Controllers/*Controller.php') as $controller) require $controller;
$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php')), '/.');
$GLOBALS['baseUrl'] = $base === '/' ? '' : $base;
Flight::set('flight.base_url', $GLOBALS['baseUrl'] ?: '/');
Flight::set('flight.log_errors', true);
Flight::route('GET /', function () { HomeController::index(); });
Flight::route('GET /articles', function () { ArticleController::index(); });
Flight::route('GET /articles/@slug', function ($slug) { ArticleController::show($slug); });
Flight::route('GET /calculator', function () { CalculatorController::index(); });
Flight::route('POST /calculator', function () { CalculatorController::calculate(); });
Flight::route('GET /contact', function () { ContactController::index(); });
Flight::route('POST /contact', function () { ContactController::send(); });
Flight::route('GET /register', function () { AuthController::registerForm(); });
Flight::route('POST /register', function () { AuthController::register(); });
Flight::route('GET /login', function () { AuthController::loginForm(); });
Flight::route('POST /login', function () { AuthController::login(); });
Flight::route('POST /logout', function () { AuthController::logout(); });
Flight::route('GET /workspace', function () { TaskController::index(); });
Flight::route('POST /tasks', function () { TaskController::create(); });
Flight::route('POST /tasks/@id/toggle', function ($id) { TaskController::toggle($id); });
Flight::route('POST /tasks/@id/delete', function ($id) { TaskController::delete($id); });
Flight::map('notFound', function () { http_response_code(404); render('error', ['title'=>'Страница не найдена','message'=>'Возможно, ссылка устарела.']); });
Flight::start();
