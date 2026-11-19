<?php
$escape = static fn($value): string => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<div class="article-detail">
    <span class="pill">Статья №<?= (int) $article['id'] ?></span>
    <h2><?= $escape($article['title']) ?></h2>
    <p class="author-label">Автор: <strong><?= $escape($nickname) ?></strong></p>
    <p class="article-body"><?= nl2br($escape($article['body'])) ?></p>
    <a class="article-link" href="<?= $escape($baseUrl) ?>/">← Вернуться к статьям</a>
</div>
