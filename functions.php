<?php
/**
 * functions.php — ядро CMS
 * Подключение к БД, авто-установка (создание таблиц + первичное наполнение),
 * загрузка контента в память и функции-хелперы для шаблона.
 */

// локальный override (только для тестов; на хостинг не загружается)
if (file_exists(__DIR__ . '/config.local.php')) require_once __DIR__ . '/config.local.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/content-defaults.php';

// ── глобальный кэш загруженного контента ──
$GLOBALS['CC'] = ['texts'=>[], 'settings'=>[], 'images'=>[], 'lang'=>'az'];

/* ───────────── Движок базы ───────────── */
function db_driver(): string {
    return defined('DB_DRIVER') ? DB_DRIVER : 'mysql';
}

/* ───────────── Подключение к базе ───────────── */
function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $opts = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            if (db_driver() === 'sqlite') {
                $dir = dirname(DB_SQLITE_PATH);
                if (!is_dir($dir)) @mkdir($dir, 0755, true);
                $pdo = new PDO('sqlite:' . DB_SQLITE_PATH, null, null, $opts);
                $pdo->exec('PRAGMA journal_mode=WAL');
                $pdo->exec('PRAGMA foreign_keys=ON');
            } else {
                $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET;
                $pdo = new PDO($dsn, DB_USER, DB_PASS, $opts);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            die('<div style="font-family:sans-serif;max-width:640px;margin:60px auto;padding:24px;
                 border:1px solid #A6142F;border-radius:8px;background:#fff8f0;color:#333">
                 <h2 style="color:#A6142F">Нет подключения к базе данных</h2>
                 <p>Проверь данные в файле <b>config.php</b> (DB_NAME, DB_USER, DB_PASS, DB_HOST).</p>
                 <p style="color:#888;font-size:13px">Тех. детали: '.htmlspecialchars($e->getMessage()).'</p></div>');
        }
    }
    return $pdo;
}

/* ───────────── SQL-помощники для двух движков ───────────── */
/** INSERT, который молча пропускает дубликаты по первичному ключу */
function sql_insert_ignore(): string {
    return db_driver() === 'sqlite' ? 'INSERT OR IGNORE INTO' : 'INSERT IGNORE INTO';
}
/** Upsert для текстов (ключ text_key+lang) */
function upsert_text(PDO $pdo, string $key, string $lang, string $val): void {
    if (db_driver() === 'sqlite') {
        $q = "INSERT INTO cc_texts (text_key,lang,content) VALUES (?,?,?)
              ON CONFLICT(text_key,lang) DO UPDATE SET content=excluded.content";
    } else {
        $q = "INSERT INTO cc_texts (text_key,lang,content) VALUES (?,?,?)
              ON DUPLICATE KEY UPDATE content = VALUES(content)";
    }
    $pdo->prepare($q)->execute([$key, $lang, $val]);
}
/** Upsert для настроек (ключ skey) */
function upsert_setting(PDO $pdo, string $key, string $val): void {
    if (db_driver() === 'sqlite') {
        $q = "INSERT INTO cc_settings (skey,svalue) VALUES (?,?)
              ON CONFLICT(skey) DO UPDATE SET svalue=excluded.svalue";
    } else {
        $q = "INSERT INTO cc_settings (skey,svalue) VALUES (?,?)
              ON DUPLICATE KEY UPDATE svalue = VALUES(svalue)";
    }
    $pdo->prepare($q)->execute([$key, $val]);
}

/* ───────────── Установка: таблицы + первичные данные ───────────── */
function install_if_needed(): void {
    $pdo = db();
    if (db_driver() === 'sqlite') {
        $pdo->exec("CREATE TABLE IF NOT EXISTS cc_texts (
            text_key TEXT NOT NULL, lang TEXT NOT NULL, content TEXT,
            PRIMARY KEY (text_key, lang))");
        $pdo->exec("CREATE TABLE IF NOT EXISTS cc_settings (
            skey TEXT NOT NULL PRIMARY KEY, svalue TEXT)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS cc_images (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            slot TEXT NOT NULL, path TEXT NOT NULL, sort INTEGER NOT NULL DEFAULT 0)");
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_images_slot ON cc_images(slot)");
    } else {
        $pdo->exec("CREATE TABLE IF NOT EXISTS cc_texts (
            text_key VARCHAR(64) NOT NULL, lang VARCHAR(5) NOT NULL, content TEXT,
            PRIMARY KEY (text_key, lang)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $pdo->exec("CREATE TABLE IF NOT EXISTS cc_settings (
            skey VARCHAR(64) NOT NULL PRIMARY KEY, svalue TEXT) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $pdo->exec("CREATE TABLE IF NOT EXISTS cc_images (
            id INT AUTO_INCREMENT PRIMARY KEY, slot VARCHAR(64) NOT NULL,
            path VARCHAR(255) NOT NULL, sort INT NOT NULL DEFAULT 0,
            INDEX (slot)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    // маркер: установлено ли уже (чтобы не пересоздавать данные после ручного удаления)
    $done = $pdo->query("SELECT svalue FROM cc_settings WHERE skey='_installed'")->fetchColumn();
    if (!$done) {
        seed_defaults($pdo);
        upsert_setting($pdo, '_installed', '1');
    }

    // одноразовая миграция старой команды (team1_photo…) → динамический слот 'team'
    migrate_team($pdo);
}

/* Перенос старой фиксированной команды (team1_photo/team1_role…) в динамический
   слот 'team' с текстами team_{id}_*. Выполняется один раз (маркер _team_migrated). */
function migrate_team(PDO $pdo): void {
    $done = $pdo->query("SELECT svalue FROM cc_settings WHERE skey='_team_migrated'")->fetchColumn();
    if ($done) return;

    for ($n = 1; $n <= 20; $n++) {
        $sel = $pdo->prepare("SELECT path FROM cc_images WHERE slot=? ORDER BY id LIMIT 1");
        $sel->execute(["team{$n}_photo"]);
        $path = $sel->fetchColumn();
        if ($path === false) continue;

        $pdo->prepare("INSERT INTO cc_images (slot,path,sort) VALUES ('team',?,?)")->execute([$path, $n]);
        $newId = (int)$pdo->lastInsertId();

        foreach (['name','role','desc'] as $f) {
            foreach (['az','ru','en'] as $lang) {
                $g = $pdo->prepare("SELECT content FROM cc_texts WHERE text_key=? AND lang=?");
                $g->execute(["team{$n}_{$f}", $lang]);
                $val = $g->fetchColumn();
                if ($val !== false) upsert_text($pdo, "team_{$newId}_{$f}", $lang, (string)$val);
            }
            $pdo->prepare("DELETE FROM cc_texts WHERE text_key=?")->execute(["team{$n}_{$f}"]);
        }
        $pdo->prepare("DELETE FROM cc_images WHERE slot=?")->execute(["team{$n}_photo"]);
    }
    upsert_setting($pdo, '_team_migrated', '1');
}

/* Заполнение таблиц значениями по умолчанию из content-defaults.php */
function seed_defaults(PDO $pdo): void {
    global $DEFAULT_TEXTS, $DEFAULT_SETTINGS, $DEFAULT_IMAGES, $DEFAULT_TEAM;
    $ins = sql_insert_ignore();

    $t = $pdo->prepare("$ins cc_texts (text_key,lang,content) VALUES (?,?,?)");
    foreach ($DEFAULT_TEXTS as $key => $d) {
        foreach (['az','ru','en'] as $lang) {
            $t->execute([$key, $lang, $d[$lang] ?? '']);
        }
    }

    $s = $pdo->prepare("$ins cc_settings (skey,svalue) VALUES (?,?)");
    foreach ($DEFAULT_SETTINGS as $key => $d) {
        $s->execute([$key, $d['default'] ?? '']);
    }

    $i = $pdo->prepare("INSERT INTO cc_images (slot,path,sort) VALUES (?,?,?)");
    foreach ($DEFAULT_IMAGES as $slot => $d) {
        $paths = $d['multi'] ? $d['default'] : [$d['default']];
        $sort = 0;
        foreach ($paths as $p) { $i->execute([$slot, $p, $sort++]); }
    }

    // стартовые участники команды: слот 'team' + тексты team_{id}_name/role/desc
    $sort = 0;
    foreach ($DEFAULT_TEAM as $m) {
        $i->execute(['team', $m['photo'], $sort++]);
        $id = (int)$pdo->lastInsertId();
        foreach (['name','role','desc'] as $f) {
            foreach (['az','ru','en'] as $lang) {
                $t->execute(["team_{$id}_{$f}", $lang, $m[$f][$lang] ?? '']);
            }
        }
    }
}

/* ───────────── Загрузка контента в память ───────────── */
function load_content(): void {
    $pdo = db();
    foreach ($pdo->query("SELECT text_key,lang,content FROM cc_texts") as $r) {
        $GLOBALS['CC']['texts'][$r['text_key']][$r['lang']] = $r['content'];
    }
    foreach ($pdo->query("SELECT skey,svalue FROM cc_settings") as $r) {
        $GLOBALS['CC']['settings'][$r['skey']] = $r['svalue'];
    }
    foreach ($pdo->query("SELECT slot,path FROM cc_images ORDER BY slot,sort,id") as $r) {
        $GLOBALS['CC']['images'][$r['slot']][] = $r['path'];
    }
}

/* ───────────── Определение языка ───────────── */
function resolve_lang(): void {
    $allowed = ['az','ru','en'];
    $lang = $GLOBALS['CC']['settings']['default_lang'] ?? 'az';
    if (isset($_COOKIE['cc_lang']) && in_array($_COOKIE['cc_lang'], $allowed, true)) {
        $lang = $_COOKIE['cc_lang'];
    }
    if (isset($_GET['lang']) && in_array($_GET['lang'], $allowed, true)) {
        $lang = $_GET['lang'];
        setcookie('cc_lang', $lang, time()+3600*24*365, '/');
    }
    if (!in_array($lang, $allowed, true)) $lang = 'az';
    $GLOBALS['CC']['lang'] = $lang;
}

/* ───────────── Единая точка инициализации ───────────── */
function boot(): void {
    install_if_needed();
    load_content();
    resolve_lang();
}

/* ═══════════ ХЕЛПЕРЫ ДЛЯ ШАБЛОНА ═══════════ */

/** экранирование HTML */
function e($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

/** текущий язык */
function lang(): string { return $GLOBALS['CC']['lang']; }

/** текст по ключу (текущий язык, откат на az → пустая строка). Экранированный. */
function t(string $key, ?string $lang = null): string {
    $lang = $lang ?? $GLOBALS['CC']['lang'];
    $v = $GLOBALS['CC']['texts'][$key][$lang] ?? ($GLOBALS['CC']['texts'][$key]['az'] ?? '');
    return e($v);
}

/** текст по ключу БЕЗ экранирования (для случаев, где нужен сырой) */
function t_raw(string $key, ?string $lang = null): string {
    $lang = $lang ?? $GLOBALS['CC']['lang'];
    return $GLOBALS['CC']['texts'][$key][$lang] ?? ($GLOBALS['CC']['texts'][$key]['az'] ?? '');
}

/** настройка по ключу (экранированная) */
function s(string $key, string $default = ''): string {
    return e($GLOBALS['CC']['settings'][$key] ?? $default);
}
/** настройка сырая */
function s_raw(string $key, string $default = ''): string {
    return $GLOBALS['CC']['settings'][$key] ?? $default;
}

/** первый (или единственный) путь фото слота, экранированный для src */
function img(string $slot, string $fallback = ''): string {
    $p = $GLOBALS['CC']['images'][$slot][0] ?? $fallback;
    return e($p);
}

/** все фото слота (массив путей, сырой) */
function imgs(string $slot): array {
    return $GLOBALS['CC']['images'][$slot] ?? [];
}

/** есть ли хоть одно фото в слоте */
function has_img(string $slot): bool {
    return !empty($GLOBALS['CC']['images'][$slot]);
}

/** участники команды: [ ['id'=>int,'path'=>string], … ] по порядку */
function team_members(): array {
    $rows = [];
    foreach (db()->query("SELECT id,path FROM cc_images WHERE slot='team' ORDER BY sort,id") as $r) {
        $rows[] = ['id' => (int)$r['id'], 'path' => $r['path']];
    }
    return $rows;
}
