<?php
declare(strict_types=1);
final class HomeController {
    public static function index(): void {
        $articles = []; $taskStats = ['total'=>0,'done'=>0]; $dbReady = false;
        try {
            $db = Database::get(); $dbReady = true;
            $articles = $db->query('SELECT a.slug,a.category,a.minutes,a.title,a.summary,u.nickname FROM articles a JOIN users u ON u.id=a.user_id ORDER BY a.id DESC LIMIT 3')->fetchAll();
            if (auth_id()) {
                $q = $db->prepare('SELECT COUNT(*) total, COALESCE(SUM(completed),0) done FROM tasks WHERE user_id=?');
                $q->execute([auth_id()]); $taskStats = $q->fetch() ?: $taskStats;
            }
        } catch (Throwable $e) { error_log('DevFlow home: ' . $e->getMessage()); }
        render('home', ['title'=>'Главная — DevFlow', 'active'=>'home', 'articles'=>$articles, 'taskStats'=>$taskStats, 'dbReady'=>$dbReady]);
    }
}
