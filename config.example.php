<?php
/**
 * config.example.php — ШАБЛОН НАСТРОЕК (без реальных паролей).
 * ─────────────────────────────────────────────────────────────
 * Это образец для GitHub. Реальные данные хранятся в config.php,
 * который НЕ попадает в репозиторий (см. .gitignore).
 *
 * Чтобы поднять сайт с нуля: скопируй этот файл в config.php
 * и впиши свои данные базы MySQL и пароль от админки.
 */

// ── Движок базы: 'mysql' для Hostinger ───────────────────────
defined('DB_DRIVER') || define('DB_DRIVER', 'mysql');

// ── База данных MySQL (данные из панели Hostinger) ───────────
defined('DB_HOST') || define('DB_HOST', 'localhost');
defined('DB_NAME') || define('DB_NAME', 'ВПИШИ_ИМЯ_БАЗЫ');       // напр. u838968544_chaicore12
defined('DB_USER') || define('DB_USER', 'ВПИШИ_ПОЛЬЗОВАТЕЛЯ');   // напр. u838968544_chaicore
defined('DB_PASS') || define('DB_PASS', 'ВПИШИ_ПАРОЛЬ_БАЗЫ');
defined('DB_CHARSET') || define('DB_CHARSET', 'utf8mb4');

// путь к файлу SQLite (используется только если DB_DRIVER='sqlite')
defined('DB_SQLITE_PATH') || define('DB_SQLITE_PATH', __DIR__ . '/data/chaicore.sqlite');

// ── Вход в админку (придумай свои) ───────────────────────────
defined('ADMIN_USER') || define('ADMIN_USER', 'admin');
defined('ADMIN_PASS') || define('ADMIN_PASS', 'ПРИДУМАЙ_ПАРОЛЬ'); // ⚠️ смени на свой

// ── Технические настройки ────────────────────────────────────
defined('UPLOAD_DIR') || define('UPLOAD_DIR', __DIR__ . '/uploads');
defined('UPLOAD_URL') || define('UPLOAD_URL', 'uploads');
defined('MAX_UPLOAD_MB') || define('MAX_UPLOAD_MB', 8);
