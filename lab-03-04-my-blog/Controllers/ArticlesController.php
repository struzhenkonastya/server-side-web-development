<?php
declare(strict_types=1);

require_once __DIR__ . '/../db.php';

final class ArticlesController
{
    public function __construct(private string $baseUrl = '') {}

    public function show(int $id): void
    {
        try {
            $db = getBlogDatabase();

            $articleQuery = $db->prepare(
                'SELECT id, user_id, title, body, created_at FROM articles WHERE id = :id LIMIT 1'
            );
            $articleQuery->execute(['id' => $id]);
            $article = $articleQuery->fetch();

            if (!$article) {
                http_response_code(404);
                $this->renderMessage('Статья не найдена', 'Статья не найдена');
                return;
            }

            $authorQuery = $db->prepare(
                'SELECT nickname FROM users WHERE id = :user_id LIMIT 1'
            );
            $authorQuery->execute(['user_id' => $article['user_id']]);
            $author = $authorQuery->fetch();
            $nickname = $author['nickname'] ?? 'Автор не найден';

            $baseUrl = $this->baseUrl;
            ob_start();
            require __DIR__ . '/../views/article.php';
            $content = (string) ob_get_clean();
            $title = (string) $article['title'];
            require __DIR__ . '/../views/main.php';
        } catch (PDOException $exception) {
            http_response_code(503);
            $this->renderMessage(
                'Не удалось подключиться к базе данных. Проверь настройки MySQL и импорт таблиц.',
                'Ошибка базы данных'
            );
        }
    }

    private function renderMessage(string $message, string $title): void
    {
        $baseUrl = $this->baseUrl;
        $content = '<p class="message">' . htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</p>';
        require __DIR__ . '/../views/main.php';
    }
}
