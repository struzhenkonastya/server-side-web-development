<?php
declare(strict_types=1);

final class PagesController
{
    public function __construct(private string $baseUrl = '') {}

    public function index(): void
    {
        $content = '<article><span class="pill">Статья 1</span><h2>Добро пожаловать в блог</h2><p>Всем привет, это текст первой статьи.</p></article>'
                 . '<article><span class="pill">Статья 2</span><h2>Вторая статья</h2><p>Всем привет, это текст второй статьи.</p></article>';
        $this->render($content);
    }

    public function sayHello(string $name): void
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $this->render('<span class="pill">Приветствие</span><p class="message">Привет, ' . $safeName . '</p><p class="note">Маршрут /hello/имя передаёт имя в контроллер.</p>', 'Страница приветствия');
    }

    public function sayBye(string $name): void
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $this->render('<span class="pill">Прощание</span><p class="message">Пока, ' . $safeName . '</p><p class="note">Экшн sayBye() запущен через маршрут /bye/имя.</p>');
    }

    public function notFound(): void
    {
        $this->render('<span class="pill">404</span><p class="message">Страница не найдена</p>');
    }

    private function render(string $content, ?string $title = null): void
    {
        $baseUrl = $this->baseUrl;
        require __DIR__ . '/../views/main.php';
    }
}
