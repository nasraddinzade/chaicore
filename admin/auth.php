<?php
/** admin/auth.php — сессия, вход, защита, CSRF */
require_once __DIR__ . '/../functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in(): bool {
    return !empty($_SESSION['cc_admin']);
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

/** CSRF-токен */
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}
function csrf_field(): string {
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}
function csrf_check(): void {
    $ok = isset($_POST['csrf']) && is_string($_POST['csrf'])
        && hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf']);
    if (!$ok) {
        http_response_code(400);
        die('Ошибка проверки безопасности (CSRF). Обнови страницу и попробуй ещё раз.');
    }
}

/** Проверка логина/пароля */
function attempt_login(string $user, string $pass): bool {
    $okUser = hash_equals(ADMIN_USER, $user);
    $okPass = hash_equals(ADMIN_PASS, $pass);
    if ($okUser && $okPass) {
        session_regenerate_id(true);
        $_SESSION['cc_admin'] = true;
        return true;
    }
    return false;
}
