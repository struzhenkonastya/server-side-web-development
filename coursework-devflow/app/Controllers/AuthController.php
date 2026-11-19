<?php
declare(strict_types=1);
final class AuthController {
    public static function registerForm(): void { if(auth_id()) go('workspace'); render('auth',['title'=>'Регистрация — DevFlow','active'=>'auth','mode'=>'register']); }
    public static function loginForm(): void { if(auth_id()) go('workspace'); render('auth',['title'=>'Вход — DevFlow','active'=>'auth','mode'=>'login']); }
    public static function register(): void {
        verify_csrf(); $name=post_str('name',80); $email=strtolower(post_str('email',191)); $password=(string)($_POST['password']??'');
        if (strlen($name)<2 || !validate_email($email) || strlen($password)<8 || strlen($password)>72) {
            render('auth',['title'=>'Регистрация — DevFlow','active'=>'auth','mode'=>'register','error'=>'Укажите имя, корректную почту и пароль от 8 до 72 символов.']); return;
        }
        try {
            $db=Database::get();
            $check=$db->prepare('SELECT id FROM users WHERE email=?'); $check->execute([$email]);
            if ($check->fetch()) { render('auth',['title'=>'Регистрация — DevFlow','active'=>'auth','mode'=>'register','error'=>'Эта почта уже используется.']); return; }
            $nickname='dev_' . bin2hex(random_bytes(4));
            $q=$db->prepare('INSERT INTO users (name,nickname,email,password_hash) VALUES (?,?,?,?)');
            $q->execute([$name,$nickname,$email,password_hash($password,PASSWORD_DEFAULT)]);
            session_regenerate_id(true); $_SESSION['user_id']=(int)$db->lastInsertId(); $_SESSION['user_name']=$name;
            flash('success','Аккаунт создан! Добавьте свою первую задачу.'); go('workspace');
        } catch(Throwable $e) { error_log($e->getMessage()); render('auth',['title'=>'Регистрация — DevFlow','active'=>'auth','mode'=>'register','error'=>'Не удалось создать аккаунт. Проверьте базу данных.']); }
    }
    public static function login(): void {
        verify_csrf(); $email=strtolower(post_str('email',191)); $password=(string)($_POST['password']??'');
        try {
            $q=Database::get()->prepare('SELECT id,name,password_hash FROM users WHERE email=? LIMIT 1'); $q->execute([$email]); $user=$q->fetch();
            if (!$user || !$user['password_hash'] || !password_verify($password,$user['password_hash'])) {
                render('auth',['title'=>'Вход — DevFlow','active'=>'auth','mode'=>'login','error'=>'Неверная почта или пароль.']); return;
            }
            session_regenerate_id(true); $_SESSION['user_id']=(int)$user['id']; $_SESSION['user_name']=$user['name'];
            go('workspace');
        } catch(Throwable $e) { error_log($e->getMessage()); render('auth',['title'=>'Вход — DevFlow','active'=>'auth','mode'=>'login','error'=>'Не удалось подключиться к базе данных.']); }
    }
    public static function logout(): void { verify_csrf(); unset($_SESSION['user_id'],$_SESSION['user_name']); session_regenerate_id(true); go(''); }
}
