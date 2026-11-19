<?php
declare(strict_types=1);

if (!defined('APP_BOOTSTRAPPED')) {
    http_response_code(404);
    exit;
}

function renderAdd(PDO $pdo): string
{
    $values = blankContact();
    $notification = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $values = inputContact();
        if (!validCsrf($_POST['csrf_token'] ?? null) || !contactIsValid($values)) {
            $notification = alertHtml('Ошибка: запись не добавлена', 'error');
        } else {
            try {
                $sql = 'INSERT INTO contacts (surname, name, lastname, gender, birth_date, phone, location, email, comment) '
                    . 'VALUES (:surname, :name, :lastname, :gender, :birth_date, :phone, :location, :email, :comment)';
                $pdo->prepare($sql)->execute(contactParams($values));
                $notification = alertHtml('Запись добавлена', 'success');
                $values = blankContact();
            } catch (PDOException $e) {
                error_log($e->getMessage());
                $notification = alertHtml('Ошибка: запись не добавлена', 'error');
            }
        }
    }

    ob_start();
    ?>
    <section class="panel form-panel">
        <p class="eyebrow">Новый контакт</p>
        <h2>Добавление записи</h2>
        <?= $notification ?>
        <form name="form_add" method="post" action="index.php?action=add" class="contact-form">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <div class="form-grid">
                <label>Фамилия <span>*</span><input type="text" name="surname" maxlength="100" required value="<?= e($values['surname']) ?>" placeholder="Иванов"></label>
                <label>Имя <span>*</span><input type="text" name="name" maxlength="100" required value="<?= e($values['name']) ?>" placeholder="Иван"></label>
                <label>Отчество <input type="text" name="lastname" maxlength="100" value="<?= e($values['lastname']) ?>" placeholder="Иванович"></label>
                <label>Пол <span>*</span><select name="gender" required>
                    <option value="">Выберите пол</option>
                    <option value="мужской" <?= $values['gender'] === 'мужской' ? 'selected' : '' ?>>Мужской</option>
                    <option value="женский" <?= $values['gender'] === 'женский' ? 'selected' : '' ?>>Женский</option>
                </select></label>
                <label>Дата рождения <span>*</span><input type="date" name="date" required value="<?= e($values['date']) ?>"></label>
                <label>Телефон <span>*</span><input type="tel" name="phone" data-phone-mask inputmode="tel" autocomplete="tel" maxlength="18" required value="<?= e($values['phone']) ?>" placeholder="+7 (900) 000-00-00"></label>
                <label class="full">Адрес <span>*</span><input type="text" name="location" maxlength="255" required value="<?= e($values['location']) ?>" placeholder="Город, улица, дом"></label>
                <label class="full">Email <span>*</span><input type="email" name="email" maxlength="254" required value="<?= e($values['email']) ?>" placeholder="example@example.com"></label>
                <label class="full">Комментарий <textarea name="comment" rows="4" placeholder="Дополнительная информация"><?= e($values['comment']) ?></textarea></label>
            </div>
            <div class="form-actions"><span>* Обязательные поля</span><button class="form-btn" type="submit">Добавить запись</button></div>
        </form>
    </section>
    <?php
    return (string) ob_get_clean();
}
