<?php
declare(strict_types=1);

if (!defined('APP_BOOTSTRAPPED')) {
    http_response_code(404);
    exit;
}

function renderDelete(PDO $pdo): string
{
    $notification = '';
    $id = requestedId();
    if ($id > 0) {
        $token = $_GET['token'] ?? null;
        if (!validCsrf($token)) {
            $notification = alertHtml('Ошибка: запись не удалена', 'error');
        } else {
            try {
                $stmt = $pdo->prepare('SELECT surname FROM contacts WHERE id = :id');
                $stmt->execute([':id' => $id]);
                $row = $stmt->fetch();
                if ($row === false) {
                    $notification = alertHtml('Ошибка: запись не удалена', 'error');
                } else {
                    $del = $pdo->prepare('DELETE FROM contacts WHERE id = :id');
                    $del->execute([':id' => $id]);
                    $notification = $del->rowCount() === 1
                        ? alertHtml('Запись с фамилией ' . $row['surname'] . ' удалена', 'success')
                        : alertHtml('Ошибка: запись не удалена', 'error');
                }
            } catch (PDOException $e) {
                error_log($e->getMessage());
                $notification = alertHtml('Ошибка: запись не удалена', 'error');
            }
        }
    }

    $contacts = $pdo->query('SELECT id, surname, name, lastname FROM contacts '
        . 'ORDER BY surname ASC, name ASC, id ASC')->fetchAll();
    ob_start();
    ?>
    <section class="panel form-panel">
        <p class="eyebrow">Управление контактами</p>
        <h2>Удаление записи</h2>
        <?= $notification ?>
        <?php if ($contacts === []): ?>
            <p class="empty">Записей для удаления нет.</p>
        <?php else: ?>
            <p class="field-hint">Нажмите на фамилию и инициалы, чтобы удалить запись. Действие необратимо.</p>
            <div class="delete-list">
                <?php foreach ($contacts as $contact): ?>
                    <?php
                    $initialName = $contact['name'] === '' ? '' : mb_first_letter($contact['name']) . '.';
                    $initialMiddle = $contact['lastname'] === '' ? '' : mb_first_letter($contact['lastname']) . '.';
                    $label = trim($contact['surname'] . ' ' . $initialName . $initialMiddle);
                    ?>
                    <a class="delete-link" href="index.php?action=delete&amp;id=<?= (int) $contact['id'] ?>&amp;token=<?= e(csrfToken()) ?>">
                        <span><?= e($label) ?></span><span aria-hidden="true">Удалить</span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
    <?php
    return (string) ob_get_clean();
}

function mb_first_letter(string $value): string
{
    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, 1, 'UTF-8');
    }
    preg_match('/^./u', $value, $matches);
    return $matches[0] ?? '';
}
