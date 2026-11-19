<?php
declare(strict_types=1);

if (!defined('APP_BOOTSTRAPPED')) {
    http_response_code(404);
    exit;
}

function renderEdit(PDO $pdo): string
{
    $contacts = $pdo->query('SELECT id, surname, name, lastname, gender, birth_date, phone, location, email, comment '
        . 'FROM contacts ORDER BY surname ASC, name ASC, id ASC')->fetchAll();
    $chosenId = requestedId();
    $current = null;
    foreach ($contacts as $contact) {
        if ((int) $contact['id'] === $chosenId) {
            $current = $contact;
            break;
        }
    }
    if ($current === null && $contacts !== []) {
        $current = $contacts[0];
    }

    $notification = '';
    $values = $current === null ? blankContact() : [
        'surname' => $current['surname'],
        'name' => $current['name'],
        'lastname' => $current['lastname'],
        'gender' => $current['gender'],
        'date' => $current['birth_date'],
        'phone' => formatPhone($current['phone']),
        'location' => $current['location'],
        'email' => $current['email'],
        'comment' => $current['comment'],
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $current !== null) {
        $values = inputContact();
        if (!validCsrf($_POST['csrf_token'] ?? null) || !contactIsValid($values)) {
            $notification = alertHtml('Ошибка: запись не изменена', 'error');
        } else {
            try {
                $params = contactParams($values);
                $params[':id'] = (int) $current['id'];
                $sql = 'UPDATE contacts SET surname=:surname, name=:name, lastname=:lastname, gender=:gender, '
                    . 'birth_date=:birth_date, phone=:phone, location=:location, email=:email, comment=:comment WHERE id=:id';
                $pdo->prepare($sql)->execute($params);
                $notification = alertHtml('Запись изменена', 'success');
                // После редактирования обновим список ссылок, чтобы показать новое ФИО.
                $contacts = $pdo->query('SELECT id, surname, name, lastname, gender, birth_date, phone, location, email, comment '
                    . 'FROM contacts ORDER BY surname ASC, name ASC, id ASC')->fetchAll();
            } catch (PDOException $e) {
                error_log($e->getMessage());
                $notification = alertHtml('Ошибка: запись не изменена', 'error');
            }
        }
    }

    ob_start();
    ?>
    <section class="panel form-panel">
        <p class="eyebrow">Изменение данных</p>
        <h2>Редактирование записи</h2>
        <?php if ($current === null): ?>
            <p class="empty">Нет записей для редактирования. Сначала добавьте контакт.</p>
        <?php else: ?>
            <p class="field-hint">Выберите контакт из списка ниже. Список отсортирован по фамилии, затем по имени.</p>
            <nav class="contact-picker" aria-label="Выбрать контакт для редактирования">
                <?php foreach ($contacts as $contact): ?>
                    <a class="person-link<?= (int) $contact['id'] === (int) $current['id'] ? ' currentRow' : '' ?>"
                       href="index.php?action=edit&amp;id=<?= (int) $contact['id'] ?>"
                       <?= (int) $contact['id'] === (int) $current['id'] ? 'aria-current="page"' : '' ?>>
                        <?= e($contact['surname'] . ' ' . $contact['name']) ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <?= $notification ?>
            <form method="post" action="index.php?action=edit&amp;id=<?= (int) $current['id'] ?>" class="contact-form">
                <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                <div class="form-grid">
                    <label>Фамилия <span>*</span><input type="text" name="surname" maxlength="100" required value="<?= e($values['surname']) ?>"></label>
                    <label>Имя <span>*</span><input type="text" name="name" maxlength="100" required value="<?= e($values['name']) ?>"></label>
                    <label>Отчество <input type="text" name="lastname" maxlength="100" value="<?= e($values['lastname']) ?>"></label>
                    <label>Пол <span>*</span><select name="gender" required>
                        <option value="">Выберите пол</option>
                        <option value="мужской" <?= $values['gender'] === 'мужской' ? 'selected' : '' ?>>Мужской</option>
                        <option value="женский" <?= $values['gender'] === 'женский' ? 'selected' : '' ?>>Женский</option>
                    </select></label>
                    <label>Дата рождения <span>*</span><input type="date" name="date" required value="<?= e($values['date']) ?>"></label>
                    <label>Телефон <span>*</span><input type="tel" name="phone" data-phone-mask inputmode="tel" autocomplete="tel" maxlength="18" required value="<?= e($values['phone']) ?>"></label>
                    <label class="full">Адрес <span>*</span><input type="text" name="location" maxlength="255" required value="<?= e($values['location']) ?>"></label>
                    <label class="full">Email <span>*</span><input type="email" name="email" maxlength="254" required value="<?= e($values['email']) ?>"></label>
                    <label class="full">Комментарий <textarea name="comment" rows="4"><?= e($values['comment']) ?></textarea></label>
                </div>
                <div class="form-actions"><span>* Обязательные поля</span><button class="form-btn" type="submit">Сохранить изменения</button></div>
            </form>
        <?php endif; ?>
    </section>
    <?php
    return (string) ob_get_clean();
}
