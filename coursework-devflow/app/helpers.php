<?php
declare(strict_types=1);

function h(?string $s): string { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function url(string $path = ''): string { return ($GLOBALS['baseUrl'] ?? '') . '/' . ltrim($path, '/'); }
function csrf(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . h(csrf()) . '">'; }
function verify_csrf(): void {
    if (!is_string($_POST['csrf'] ?? null) || !hash_equals(csrf(), $_POST['csrf'])) {
        http_response_code(419);
        exit('Сессия устарела. Обновите страницу и повторите действие.');
    }
}
function auth_id(): ?int { return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null; }
function user_name(): string { return (string)($_SESSION['user_name'] ?? 'Разработчик'); }
function require_auth(): void {
    if (!auth_id()) { header('Location: ' . url('login'), true, 303); exit; }
}
function flash(string $type, string $message): void { $_SESSION['flash'] = compact('type', 'message'); }
function flash_take(): ?array { $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f; }
function money(int|float $value): string { return number_format((float)$value, 0, ',', ' ') . ' ₽'; }
function render(string $view, array $data = []): void {
    $file = __DIR__ . '/views/' . $view . '.php';
    if (!is_file($file)) { http_response_code(500); exit('Не найден шаблон'); }
    extract($data, EXTR_SKIP);
    ob_start();
    require $file;
    $content = ob_get_clean();
    require __DIR__ . '/views/layout.php';
}
function go(string $path): never { header('Location: ' . url($path), true, 303); exit; }
function validate_email(string $email): bool { return (bool)filter_var($email, FILTER_VALIDATE_EMAIL); }
function post_str(string $key, int $limit = 255): string {
    $val = $_POST[$key] ?? '';
    if (!is_string($val)) return '';
    if (function_exists('mb_substr')) return trim(mb_substr($val, 0, $limit, 'UTF-8'));
    if (preg_match_all('/./us', $val, $matches)) return trim(implode('', array_slice($matches[0], 0, $limit)));
    return '';
}
