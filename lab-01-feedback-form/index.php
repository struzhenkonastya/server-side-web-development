<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback form — Обратная связь</title>
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
            <section class="content-card" aria-labelledby="form-title">
                <div class="section-tag">Страница 01 / 02</div>
                <h2 id="form-title">Форма обратной связи</h2>
                <p class="intro">Заполните поля ниже, чтобы отправить обращение.</p>

                <form action="https://httpbin.org/post" method="post" accept-charset="UTF-8">
                    <div class="form-field">
                        <label for="username">Имя пользователя <span class="required">*</span></label>
                        <input type="text" id="username" name="username" placeholder="Ваше имя" autocomplete="name" required>
                    </div>

                    <div class="form-field">
                        <label for="email">E-mail пользователя <span class="required">*</span></label>
                        <input type="email" id="email" name="email" placeholder="example@mail.ru" autocomplete="email" required>
                    </div>

                    <div class="form-field">
                        <label for="request_type">Тип обращения <span class="required">*</span></label>
                        <select id="request_type" name="request_type" required>
                            <option value="" selected disabled>Выберите тип обращения</option>
                            <option value="complaint">Жалоба</option>
                            <option value="suggestion">Предложение</option>
                            <option value="gratitude">Благодарность</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="message">Текст обращения <span class="required">*</span></label>
                        <textarea id="message" name="message" rows="5" placeholder="Напишите ваше обращение..." required></textarea>
                    </div>

                    <fieldset class="reply-options">
                        <legend>Вариант ответа</legend>
                        <div class="checkbox-group">
                            <label class="checkbox-label" for="reply_sms">
                                <input id="reply_sms" type="checkbox" name="reply_method[]" value="sms">
                                <span>СМС</span>
                            </label>
                            <label class="checkbox-label" for="reply_email">
                                <input id="reply_email" type="checkbox" name="reply_method[]" value="email">
                                <span>E-mail</span>
                            </label>
                        </div>
                    </fieldset>

                    <button type="submit" class="primary-button">Отправить обращение</button>
                </form>

                <div class="next-page">
                    <span>Хотите посмотреть HTTP-заголовки сервера?</span>
                    <a href="headers.php" class="text-link">Перейти на вторую страницу <span aria-hidden="true">→</span></a>
                </div>
            </section>
        </main>

        <footer class="site-footer">Задание для самостоятельной работы</footer>
    </div>
</body>
</html>
