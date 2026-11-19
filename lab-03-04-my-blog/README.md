Лабораторные работы №3 и №4 — «Мой блог»

Дисциплина: Серверная веб-разработка.

Что реализовано

Лабораторная работа №3

В файле `Controllers/PagesController.php` находится экшн `sayBye(string $name)`, который выводит «Пока, имя». В `routes.php` есть маршрут `/bye/имя` для вызова этого метода.

Лабораторная работа №4

В `views/main.php` значение `<title>` передаётся через переменную `$title`. Если значение не передано, используется «Мой блог». В `PagesController::sayHello()` для страницы `/hello/имя` передаётся заголовок «Страница приветствия».

Запуск через XAMPP

1. Скопируйте папку lab-03-04-my-blog в `C:\xampp\htdocs\`.
2. Запустите Apache в XAMPP.
3. Откройте `http://localhost/lab-03-04-my-blog/`.

Ссылки для проверки:

- `http://localhost/lab-03-04-my-blog/` — главная страница, `<title>Мой блог</title>`.
- `http://localhost/lab-03-04-my-blog/hello/Настя` — «Привет, Настя», `<title>Страница приветствия</title>`.
- `http://localhost/lab-03-04-my-blog/hello/username` — отдельная проверка задания №4, тот же заголовок «Страница приветствия».
- `http://localhost/lab-03-04-my-blog/bye/Настя` — «Пока, Настя», заголовок по умолчанию «Мой блог».

Что лежит в файлах

- `index.php` — точка входа и обработка маршрутов.
- `routes.php` — список маршрутов `/`, `/hello/имя`, `/bye/имя`.
- `Controllers/PagesController.php` — методы `index`, `sayHello`, `sayBye`.
- `views/main.php` — общий HTML-шаблон и динамический `<title>`.
- `styles/styles.css` — согласованный дизайн.
- `.htaccess` — перенаправляет адреса без `.php` в `index.php` на Apache.
- `router.php` — запуск через встроенный сервер PHP (не обязателен для XAMPP).

