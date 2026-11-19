<?php
declare(strict_types=1);

define('APP_BOOTSTRAPPED', true);
session_start();

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/menu.php';
require_once __DIR__ . '/viewer.php';
require_once __DIR__ . '/add.php';
require_once __DIR__ . '/edit.php';
require_once __DIR__ . '/delete.php';

$action = currentAction();
$sort = currentSort();
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
$page = is_int($page) && $page > 0 ? $page : 1;
$error = '';
$content = '';

try {
    $pdo = db();
    $content = match ($action) {
        'add' => renderAdd($pdo),
        'edit' => renderEdit($pdo),
        'delete' => renderDelete($pdo),
        default => viewer($pdo, $sort, $page),
    };
} catch (Throwable $e) {
    error_log('Phonebook: ' . $e->getMessage());
    http_response_code(500);
    $error = 'Не удалось подключиться к базе данных или выполнить запрос. Проверьте MySQL, базу phonebook и настройки подключения.';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Лабораторная работа №2: записная книжка на PHP и MySQL">
    <title>Записная книжка — Серверная веб-разработка</title>
    <link rel="stylesheet" href="style.css">
    <script src="phone-mask.js" defer></script>
</head>
<body>
    <header class="site-header">
        <div class="header-inner">
            <div class="brand">
                <div class="brand-mark" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 3v18M12 8h4M12 12h4M12 16h3"/></svg>
                </div>
                <div>
                    <span class="brand-kicker">Серверная веб-разработка</span>
                    <h1>Записная книжка</h1>
                </div>
            </div>
            <div class="lab-chip">Учебный проект <b>№ 02</b></div>
        </div>
    </header>
    <main class="container">
        <div class="page-intro">
            <div>
                <p class="intro-kicker">Лабораторная работа №2</p>
                <h2>Управление контактами</h2>
                <p class="intro-text">Просматривайте, добавляйте и редактируйте записи в одном удобном месте.</p>
            </div>
            <div class="tech-stamp"><span class="tech-dot"></span>PHP  ·  MySQL  ·  Серверная веб-разработка</div>
        </div>
        <div class="navigation-shell">
            <?= menu() ?>
        </div>
        <?php if ($error !== ''): ?>
            <section class="panel"><h2>Ошибка подключения</h2><?= alertHtml($error, 'error') ?></section>
        <?php else: ?>
            <?= $content ?>
        <?php endif; ?>
    </main>
    <footer class="site-footer">Лабораторная работа №2 · Записная книжка · PHP + MySQL</footer>
</body>
</html>
