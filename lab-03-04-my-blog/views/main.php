<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Мой блог', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/styles/styles.css?v=20261119-lab5">
</head>
<body>
<header class="topbar">
    <div class="topbar-inner">
        <a class="brand" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/"><span class="brand-symbol">Б</span><span>Мой блог</span></a>
        <nav class="top-nav" aria-label="Навигация">
            <a href="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/">Главная</a>
            <a href="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/hello/Настя">Приветствие</a>
            <a href="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/bye/Настя">Прощание</a>
            <a href="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/articles/1">Статьи</a>
        </nav>
    </div>
</header>
<div class="page">
    <section class="hero">
        <div class="eyebrow">Серверная веб-разработка</div>
        <h1><?= htmlspecialchars($title ?? 'Мой блог', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h1>
        <p class="subtitle">Учебный проект: маршруты, PHP-шаблоны и авторы статей.</p>
    </section>
    <main class="content-panel"><?= $content ?></main>
</div>
<footer class="footer">Лабораторные работы №3, №4 и №5 · Мой блог</footer>
</body>
</html>
