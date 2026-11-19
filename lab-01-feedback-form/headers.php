<?php
// Получаем HTTP-заголовки ответа сервера с помощью функции get_headers().
$url = 'https://httpbin.org/get';

// Устанавливаем тайм-аут, чтобы страница не зависала при сетевой ошибке.
$context = stream_context_create([
    'http' => [
        'timeout' => 10,
        'ignore_errors' => true,
    ],
]);

$headers = @get_headers($url, false, $context);

if ($headers === false) {
    $result = "Не удалось получить HTTP-заголовки с адреса: {$url}\n"
        . "Проверьте подключение к интернету и настройки PHP (allow_url_fopen).";
} else {
    $result = implode("\n", $headers);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback form — HTTP-заголовки</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="site-wrapper">
        <header class="site-header">
            <a class="university-logo" href="index.php" aria-label="На главную страницу">
                <img src="logo.png" alt="Московский Политех" width="255" height="71">
            </a>
            <h1>Самостоятельная работа «Feedback form»</h1>
            <div class="header-spacer" aria-hidden="true"></div>
        </header>

        <main class="main-content">
            <section class="content-card" aria-labelledby="headers-title">
                <div class="section-tag">Страница 02 / 02</div>
                <h2 id="headers-title">HTTP-заголовки сервера</h2>
                <p class="intro">Результат работы PHP-функции <code>get_headers()</code> для адреса
                    <a href="https://httpbin.org/get" target="_blank" rel="noopener noreferrer">https://httpbin.org/get</a>.
                </p>

                <label class="output-label" for="headers_result">Ответ сервера:</label>
                <textarea class="headers-output" id="headers_result" rows="16" readonly><?php echo htmlspecialchars($result, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></textarea>

                <div class="page-actions">
                    <a href="index.php" class="primary-button button-as-link">← Вернуться к форме</a>
                    <a href="headers.php" class="secondary-button">Обновить заголовки</a>
                </div>
            </section>
        </main>

        <footer class="site-footer">Задание для самостоятельной работы</footer>
    </div>
</body>
</html>
