<?php
declare(strict_types=1);
final class ArticleController {
    public static function index(): void {
        try {
            $query = trim((string)($_GET['q'] ?? ''));
            $db = Database::get();
            if ($query !== '') {
                $p = $db->prepare('SELECT a.*,u.nickname FROM articles a JOIN users u ON a.user_id=u.id WHERE a.title LIKE ? OR a.category LIKE ? OR a.summary LIKE ? ORDER BY a.id DESC');
                $search = '%' . substr($query, 0, 60) . '%';
                $p->execute([$search,$search,$search]); $articles = $p->fetchAll();
            } else {
                $articles = $db->query('SELECT a.*,u.nickname FROM articles a JOIN users u ON a.user_id=u.id ORDER BY a.id DESC')->fetchAll();
            }
            render('articles', ['title'=>'База знаний — DevFlow','active'=>'articles','articles'=>$articles,'query'=>$query]);
        } catch (Throwable $e) { error_log($e->getMessage()); render('error',['title'=>'Ошибка базы данных','message'=>'Проверьте MySQL и файл app/config.local.php.']); }
    }
    public static function show(string $slug): void {
        try {
            $db = Database::get();
            $s = $db->prepare('SELECT id,user_id,title,slug,category,minutes,body,created_at FROM articles WHERE slug=? LIMIT 1');
            $s->execute([$slug]); $article = $s->fetch();
            if (!$article) { http_response_code(404); render('error',['title'=>'Статья не найдена','message'=>'Проверьте адрес или вернитесь к базе знаний.']); return; }
            // Второй отдельный запрос — по образцу лабораторной №5.
            $authorQuery = $db->prepare('SELECT nickname FROM users WHERE id=? LIMIT 1');
            $authorQuery->execute([$article['user_id']]);
            $author = $authorQuery->fetch();
            render('article', ['title'=>$article['title'].' — DevFlow','active'=>'articles','article'=>$article,'nickname'=>$author['nickname'] ?? 'Неизвестный автор']);
        } catch (Throwable $e) { error_log($e->getMessage()); render('error',['title'=>'Ошибка базы данных','message'=>'Не удалось получить статью. Проверьте подключение к MySQL.']); }
    }
}
