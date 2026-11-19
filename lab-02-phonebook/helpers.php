<?php
declare(strict_types=1);

if (!defined('APP_BOOTSTRAPPED')) {
    http_response_code(404);
    exit;
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function currentAction(): string
{
    $action = $_GET['action'] ?? 'view';
    return is_string($action) && in_array($action, ['view', 'add', 'edit', 'delete'], true)
        ? $action
        : 'view';
}

function currentSort(): string
{
    $sort = $_GET['sort'] ?? 'added';
    return is_string($sort) && in_array($sort, ['added', 'surname', 'birth'], true)
        ? $sort
        : 'added';
}

function requestedId(): int
{
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    return is_int($id) && $id > 0 ? $id : 0;
}

function csrfToken(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validCsrf(mixed $token): bool
{
    return is_string($token) && hash_equals(csrfToken(), $token);
}

function phoneDigits(?string $value): ?string
{
    $digits = preg_replace('/\D/', '', $value ?? '');
    if ($digits === null) {
        return null;
    }
    if (strlen($digits) === 10) {
        $digits = '7' . $digits;
    } elseif (strlen($digits) === 11 && $digits[0] === '8') {
        $digits = '7' . substr($digits, 1);
    }
    return strlen($digits) === 11 && $digits[0] === '7' ? $digits : null;
}

function formatPhone(?string $value): string
{
    $raw = trim($value ?? '');
    $digits = phoneDigits($raw);
    if ($digits === null) {
        return $raw;
    }
    return '+7 (' . substr($digits, 1, 3) . ') '
        . substr($digits, 4, 3) . '-'
        . substr($digits, 7, 2) . '-'
        . substr($digits, 9, 2);
}

function inputContact(): array
{
    $fields = ['surname', 'name', 'lastname', 'gender', 'date', 'phone', 'location', 'email', 'comment'];
    $data = [];
    foreach ($fields as $field) {
        $input = $_POST[$field] ?? '';
        $data[$field] = is_string($input) ? trim($input) : '';
    }
    // Сохраняем телефон уже в едином формате, даже при отключённом JavaScript.
    $data['phone'] = formatPhone($data['phone']);
    return $data;
}

function blankContact(): array
{
    return array_fill_keys(['surname', 'name', 'lastname', 'gender', 'date', 'phone', 'location', 'email', 'comment'], '');
}

function contactIsValid(array $data): bool
{
    foreach (['surname', 'name', 'gender', 'date', 'phone', 'location', 'email'] as $field) {
        if (($data[$field] ?? '') === '') {
            return false;
        }
    }
    if (!in_array($data['gender'], ['мужской', 'женский'], true)) {
        return false;
    }
    if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
        return false;
    }
    // Допускается ввод 10 цифр, +7 или 8 с 10 цифрами после префикса.
    if (phoneDigits($data['phone'] ?? '') === null) {
        return false;
    }
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $data['date']);
    return $date !== false && $date->format('Y-m-d') === $data['date'];
}

function contactParams(array $data): array
{
    return [
        ':surname' => $data['surname'],
        ':name' => $data['name'],
        ':lastname' => $data['lastname'],
        ':gender' => $data['gender'],
        ':birth_date' => $data['date'],
        ':phone' => $data['phone'],
        ':location' => $data['location'],
        ':email' => $data['email'],
        ':comment' => $data['comment'],
    ];
}

function alertHtml(string $message, string $type): string
{
    $class = $type === 'success' ? 'success' : 'error';
    return '<div class="alert ' . $class . '" role="status">' . e($message) . '</div>';
}
