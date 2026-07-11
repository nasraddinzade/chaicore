<?php
/**
 * config.php — НАСТРОЙКИ ПОДКЛЮЧЕНИЯ
 * ─────────────────────────────────────────────────────────────
 * ⚠️  ЗАПОЛНИ ЭТОТ ФАЙЛ ПЕРЕД ЗАГРУЗКОЙ НА HOSTINGER.
 *
 * 1) В панели Hostinger → «Базы данных MySQL» создай базу и пользователя.
 *    Hostinger покажет: имя базы, имя пользователя, пароль, хост (обычно localhost).
 * 2) Впиши эти данные ниже.
 * 3) Придумай СВОИ логин и пароль для входа в админку (ADMIN_USER / ADMIN_PASS).
 *
 * (Каждая настройка объявлена через defined()||define(), чтобы файл
 *  config.local.php мог переопределить их для локального теста — трогать это
 *  не нужно.)
 */

// ── Движок базы: 'mysql' для Hostinger (по умолчанию) ────────
defined('DB_DRIVER') || define('DB_DRIVER', 'mysql');

// ── База данных MySQL ────────────────────────────────────────
defined('DB_HOST') || define('DB_HOST', 'localhost');            // на Hostinger обычно 'localhost'
defined('DB_NAME') || define('DB_NAME', 'ВПИШИ_ИМЯ_БАЗЫ');       // напр. u123456789_chaicore
defined('DB_USER') || define('DB_USER', 'ВПИШИ_ПОЛЬЗОВАТЕЛЯ');   // напр. u123456789_admin
defined('DB_PASS') || define('DB_PASS', 'ВПИШИ_ПАРОЛЬ_БАЗЫ');    // пароль пользователя базы
defined('DB_CHARSET') || define('DB_CHARSET', 'utf8mb4');

// путь к файлу SQLite (используется только если DB_DRIVER='sqlite')
defined('DB_SQLITE_PATH') || define('DB_SQLITE_PATH', __DIR__ . '/data/chaicore.sqlite');

// ── Вход в админку (придумай свои) ───────────────────────────
defined('ADMIN_USER') || define('ADMIN_USER', 'admin');
defined('ADMIN_PASS') || define('ADMIN_PASS', 'chaicore2024');   // ⚠️ ОБЯЗАТЕЛЬНО смени на свой пароль

// ── Технические настройки ────────────────────────────────────
defined('UPLOAD_DIR') || define('UPLOAD_DIR', __DIR__ . '/uploads');   // папка для загруженных фото
defined('UPLOAD_URL') || define('UPLOAD_URL', 'uploads');             // URL-путь к ней
defined('MAX_UPLOAD_MB') || define('MAX_UPLOAD_MB', 8);              // макс. размер одного фото, МБ
