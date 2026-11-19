<?php
declare(strict_types=1);

if (!defined('APP_BOOTSTRAPPED')) {
    http_response_code(404);
    exit;
}

// Возвращает HTML таблицы и пагинации по выбранной сортировке и странице.
function viewer(PDO $pdo, string $sort, int $page): string
{
    $orders = [
        'added' => 'id ASC',
        'surname' => 'surname ASC, name ASC, id ASC',
        'birth' => 'birth_date ASC, id ASC',
    ];
    $sort = array_key_exists($sort, $orders) ? $sort : 'added';
    $count = (int) $pdo->query('SELECT COUNT(*) FROM contacts')->fetchColumn();
    $pageCount = max(1, (int) ceil($count / 10));
    $page = max(1, min($page, $pageCount));
    $offset = ($page - 1) * 10;

    $sql = 'SELECT id, surname, name, lastname, gender, birth_date, phone, location, email, comment '
        . 'FROM contacts ORDER BY ' . $orders[$sort] . ' LIMIT :limit OFFSET :offset';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', 10, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    ob_start();
    ?>
    <section class="panel contacts-panel">
        <div class="panel-heading">
            <div class="heading-group">
                <div class="heading-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3"/><path d="M3 20v-2a6 6 0 0 1 12 0v2M17 8h4M17 12h4M17 16h4"/></svg></div>
                <div>
                    <p class="eyebrow">Адресная книга</p>
                    <h2>Все контакты</h2>
                    <p class="panel-subtitle">Личные данные, контакты и заметки</p>
                </div>
            </div>
            <div class="heading-actions">
                <div class="counter"><?= $count ?> <?= $count === 1 ? 'запись' : 'записей' ?></div>
                <a class="add-shortcut" href="index.php?action=add"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Новый контакт</a>
            </div>
        </div>
        <?php if ($count === 0): ?>
            <p class="empty">Здесь пока пусто. Добавьте первый контакт через меню выше.</p>
        <?php else: ?>
            <div class="table-scroll">
                <table class="contacts-table">
                    <thead><tr>
                        <th>№</th><th>Контакт</th><th>Пол</th><th>Дата рождения</th>
                        <th>Телефон</th><th>E-mail</th><th>Адрес</th><th>Комментарий</th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($rows as $i => $row): ?>
                        <?php
                        $surname = (string) $row['surname'];
                        $firstLetter = '';
                        if (preg_match('/^./u', $surname, $m) === 1) {
                            $firstLetter = $m[0];
                        }
                        $birthDate = (string) $row['birth_date'];
                        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $birthDate);
                        $dateDisplay = $date !== false ? $date->format('d.m.Y') : $birthDate;
                        ?>
                        <tr>
                            <td class="num" data-label="№"><?= $offset + $i + 1 ?></td>
                            <td data-label="Контакт">
                                <div class="contact-cell">
                                    <span class="contact-avatar" aria-hidden="true"><?= e($firstLetter) ?></span>
                                    <span>
                                        <span class="contact-primary"><?= e($row['surname'] . ' ' . $row['name']) ?></span>
                                        <span class="contact-secondary"><?= e($row['lastname']) ?></span>
                                    </span>
                                </div>
                            </td>
                            <td data-label="Пол"><span class="contact-gender"><?= e($row['gender']) ?></span></td>
                            <td data-label="Дата рождения"><span class="contact-date"><?= e($dateDisplay) ?></span></td>
                            <td data-label="Телефон"><span class="contact-phone"><?= e(formatPhone($row['phone'])) ?></span></td>
                            <td data-label="E-mail"><span class="contact-email"><?= e($row['email']) ?></span></td>
                            <td data-label="Адрес"><span class="contact-address"><?= e($row['location']) ?></span></td>
                            <td data-label="Комментарий"><span class="contact-comment"><?= nl2br(e($row['comment'])) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="table-footer">
                <p class="table-note">Показано <?= count($rows) ?> из <?= $count ?> · не более 10 на странице</p>
                <?php if ($pageCount > 1): ?>
                    <nav class="pagination" aria-label="Страницы записной книжки">
                        <?php for ($n = 1; $n <= $pageCount; ++$n): ?>
                            <a href="index.php?action=view&amp;sort=<?= e($sort) ?>&amp;page=<?= $n ?>"
                               class="page-link<?= $n === $page ? ' current' : '' ?>"
                               <?= $n === $page ? 'aria-current="page"' : '' ?>><?= $n ?></a>
                        <?php endfor; ?>
                    </nav>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </section>
    <?php
    return (string) ob_get_clean();
}
