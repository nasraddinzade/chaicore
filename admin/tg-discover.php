<?php
/**
 * admin/tg-discover.php — ВРЕМЕННЫЙ диагностический скрипт.
 * Находит Chat ID из последних апдейтов бота (getUpdates).
 * Токен читается из базы и НЕ выводится. Доступ — по секрету в URL.
 * ⚠️ Удаляется сразу после использования.
 */
require_once __DIR__ . '/../functions.php';
boot();

if (($_GET['k'] ?? '') !== 'find-chat-9f3a7c2e') {
    http_response_code(403);
    exit('forbidden');
}

$token = trim(s_raw('telegram_bot_token'));
header('Content-Type: application/json; charset=utf-8');
if ($token === '') { exit(json_encode(['error' => 'В настройках не задан telegram_bot_token'])); }

$api = "https://api.telegram.org/bot{$token}/getUpdates";
$resp = false;
if (function_exists('curl_init')) {
    $ch = curl_init($api);
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 20]);
    $resp = curl_exec($ch);
    curl_close($ch);
} else {
    $resp = @file_get_contents($api);
}

$data  = json_decode((string)$resp, true);
$found = [];
foreach (($data['result'] ?? []) as $u) {
    foreach (['message','channel_post','edited_channel_post','my_chat_member','chat_member'] as $k) {
        if (isset($u[$k]['chat'])) {
            $c = $u[$k]['chat'];
            $found[(string)$c['id']] = trim(($c['title'] ?? $c['username'] ?? $c['first_name'] ?? '?')
                . '  [' . ($c['type'] ?? '') . ']');
        }
    }
}

echo json_encode([
    'ok'          => $data['ok'] ?? null,
    'error'       => $data['description'] ?? null,
    'updates_cnt' => count($data['result'] ?? []),
    'chats'       => $found,   // id => "название [тип]"
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
