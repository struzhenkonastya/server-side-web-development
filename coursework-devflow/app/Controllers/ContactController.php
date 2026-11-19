<?php
declare(strict_types=1);
final class ContactController {
    public static function index(): void { render('contact', ['title'=>'Обратная связь — DevFlow','active'=>'contact']); }
    public static function send(): void {
        verify_csrf();
        $name=post_str('name',100); $email=post_str('email',191); $subject=post_str('subject',100); $message=post_str('message',2000);
        if (trim((string)($_POST['website'] ?? '')) !== '') { go('contact'); }
        if (strlen($name)<2 || !validate_email($email) || strlen($message)<10 || !in_array($subject,['Вопрос по проекту','Предложение','Сотрудничество','Другое'],true)) {
            render('contact',['title'=>'Обратная связь — DevFlow','active'=>'contact','error'=>'Укажите имя, корректную почту, тему и сообщение не короче 10 символов.']); return;
        }
        if (!empty($_SESSION['last_contact']) && time()-$_SESSION['last_contact']<30) {
            render('contact',['title'=>'Обратная связь — DevFlow','active'=>'contact','error'=>'Повторная отправка доступна через 30 секунд.']); return;
        }
        try {
            $q=Database::get()->prepare('INSERT INTO feedback (name,email,subject,message) VALUES (?,?,?,?)');
            $q->execute([$name,$email,$subject,$message]);
            $_SESSION['last_contact']=time(); flash('success','Сообщение отправлено и сохранено в базе данных.'); go('contact');
        } catch (Throwable $e) { error_log($e->getMessage()); render('contact',['title'=>'Ошибка отправки — DevFlow','active'=>'contact','error'=>'Не удалось сохранить сообщение. Проверьте подключение к базе данных.']); }
    }
}
