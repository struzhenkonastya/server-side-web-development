<?php
declare(strict_types=1);
final class TaskController {
    public static function index(): void {
        require_auth();
        try {
            $db=Database::get();
            $q=$db->prepare('SELECT id,title,priority,completed,created_at FROM tasks WHERE user_id=? ORDER BY completed ASC,id DESC'); $q->execute([auth_id()]);
            $tasks=$q->fetchAll();
            $s=$db->prepare('SELECT total_price,project_type,created_at FROM estimates WHERE user_id=? ORDER BY id DESC LIMIT 3'); $s->execute([auth_id()]);
            $history=$s->fetchAll();
            $done=count(array_filter($tasks,fn($t)=>(bool)$t['completed']));
            render('workspace',['title'=>'Мои задачи — DevFlow','active'=>'workspace','tasks'=>$tasks,'done'=>$done,'history'=>$history]);
        } catch(Throwable $e) { error_log($e->getMessage()); render('error',['title'=>'Ошибка базы данных','message'=>'Не удалось прочитать задачи. Проверьте подключение к MySQL.']); }
    }
    public static function create(): void {
        require_auth(); verify_csrf();
        $title=post_str('title',160); $priority=post_str('priority',10);
        if (strlen($title)<3 || !in_array($priority,['low','medium','high'],true)) { flash('error','Введите название задачи длиной не менее 3 символов.'); go('workspace'); }
        try { $q=Database::get()->prepare('INSERT INTO tasks (user_id,title,priority) VALUES (?,?,?)'); $q->execute([auth_id(),$title,$priority]); flash('success','Задача добавлена.'); }
        catch(Throwable $e) { error_log($e->getMessage()); flash('error','Не удалось сохранить задачу.'); }
        go('workspace');
    }
    public static function toggle(string $id): void {
        require_auth(); verify_csrf();
        if (!ctype_digit($id)) go('workspace');
        try { $q=Database::get()->prepare('UPDATE tasks SET completed=1-completed WHERE id=? AND user_id=?'); $q->execute([(int)$id,auth_id()]); }
        catch(Throwable $e) { error_log($e->getMessage()); flash('error','Не удалось изменить задачу.'); }
        go('workspace');
    }
    public static function delete(string $id): void {
        require_auth(); verify_csrf();
        if (!ctype_digit($id)) go('workspace');
        try { $q=Database::get()->prepare('DELETE FROM tasks WHERE id=? AND user_id=?'); $q->execute([(int)$id,auth_id()]); flash('success','Задача удалена.'); }
        catch(Throwable $e) { error_log($e->getMessage()); flash('error','Не удалось удалить задачу.'); }
        go('workspace');
    }
}
