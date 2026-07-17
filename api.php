<?php
/**
 * api.php — read-only headless JSON API для Next.js-фронтенда.
 * Отдаёт весь публичный контент сайта (тексты, настройки, фото, команда,
 * отзывы, пользовательские разделы). Правится всё через существующую PHP-админку.
 */
require_once __DIR__ . '/functions.php';
boot();

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');            // публичный контент
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Cache-Control: public, max-age=30');
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') { http_response_code(204); exit; }

$pdo  = db();
$host = 'https://' . ($_SERVER['HTTP_HOST'] ?? 'chaicore.az') . '/';

/** относительный путь картинки → абсолютный URL на бэкенде */
function asset(?string $p): ?string {
    global $host;
    if ($p === null || $p === '') return null;
    if (preg_match('~^https?://~', $p)) return $p;
    return $host . ltrim((string)$p, '/');
}
/** {az,ru,en} из загруженных текстов */
function tri(string $key): array {
    $t = $GLOBALS['CC']['texts'][$key] ?? [];
    return ['az' => $t['az'] ?? '', 'ru' => $t['ru'] ?? '', 'en' => $t['en'] ?? ''];
}

// ── тексты ──
$texts = [];
foreach ($GLOBALS['CC']['texts'] as $key => $langs) {
    $texts[$key] = ['az' => $langs['az'] ?? '', 'ru' => $langs['ru'] ?? '', 'en' => $langs['en'] ?? ''];
}

// ── настройки (без служебных и чувствительных) ──
$settings = $GLOBALS['CC']['settings'];
foreach (['_installed','_team_migrated','_reviews_migrated','_content_ver',
          'telegram_bot_token','telegram_chat_id','form_to_email'] as $k) {
    unset($settings[$k]);
}

// ── фото по слотам (абсолютные URL) ──
$images = [];
foreach ($GLOBALS['CC']['images'] as $slot => $paths) {
    $images[$slot] = array_map('asset', $paths);
}

// ── команда ──
$team = [];
foreach (team_members() as $m) {
    $id = $m['id'];
    $team[] = [
        'id'    => $id,
        'photo' => asset($m['path']),
        'name'  => tri("team_{$id}_name"),
        'role'  => tri("team_{$id}_role"),
        'desc'  => tri("team_{$id}_desc"),
    ];
}

// ── отзывы (одобренные, по языкам) ──
$reviews = ['az' => [], 'ru' => [], 'en' => []];
foreach ($pdo->query("SELECT name,location,text,rating,lang FROM cc_reviews WHERE status='approved' ORDER BY id") as $r) {
    $lang = in_array($r['lang'], ['az','ru','en'], true) ? $r['lang'] : 'az';
    $reviews[$lang][] = [
        'name'     => $r['name'],
        'location' => $r['location'],
        'text'     => $r['text'],
        'rating'   => (int)$r['rating'],
    ];
}

// ── пользовательские разделы (видимые) ──
$sections = [];
foreach (custom_sections() as $cs) {
    $id = $cs['id'];
    $sections[] = [
        'id'    => $id,
        'bg'    => $cs['bg'],
        'tag'   => tri("sec_{$id}_tag"),
        'title' => tri("sec_{$id}_title"),
        'body'  => tri("sec_{$id}_body"),
        'image' => has_img("section_{$id}") ? asset($GLOBALS['CC']['images']["section_{$id}"][0]) : null,
    ];
}

echo json_encode([
    'texts'    => $texts,
    'settings' => $settings,
    'images'   => $images,
    'team'     => $team,
    'reviews'  => $reviews,
    'sections' => $sections,
], JSON_UNESCAPED_UNICODE);
