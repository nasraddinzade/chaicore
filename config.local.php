<?php
/**
 * config.local.php — ЛОКАЛЬНЫЙ ТЕСТ (SQLite).
 * Активируется ТОЛЬКО на localhost / встроенном сервере PHP.
 * На реальном хостинге (Hostinger) этот файл ничего не делает —
 * там работает MySQL из config.php. Поэтому он безопасен, даже если
 * случайно попадёт в загрузку (но лучше его не загружать).
 */
$__local = (php_sapi_name() === 'cli-server')
    || in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true);

if ($__local) {
    define('DB_DRIVER', 'sqlite');
    define('DB_SQLITE_PATH', __DIR__ . '/data/chaicore.sqlite');
    define('ADMIN_USER', 'admin');
    define('ADMIN_PASS', 'test123');
}
