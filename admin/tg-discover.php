<?php
/**
 * admin/tg-discover.php — ВРЕМЕННЫЙ диагностический скрипт.
 * ?k=секрет                — найти чаты бота (getUpdates)
 * ?k=...&setchat=1         — записать найденный канал в telegram_chat_id
 * ?k=...&send=1            — отправить прямой тест в найденный канал
 * Токен читается из базы и НЕ выводится. Удаляется после использования.
 */
require_once __DIR__ . '/../functions.php';
boot();

if (($_GET['k'] ?? '') !== 'find-chat-9f3a7c2e') { http_response_code(403); exit('forbidden'); }

header('Content-Type: application/json; charset=utf-8');
$token = trim(s_raw('telegram_bot_token'));
if ($token === '') { exit(json_encode(['error' => 'no token in settings'])); }

function tg_call(string $token, string $method, array $params = []) {
    $url = "https://api.telegram.org/bot{$token}/{$method}";
    $q = http_build_query($params);
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 20,
            CURLOPT_POST => !empty($params), CURLOPT_POSTFIELDS => $q,
        ]);
        $r = curl_exec($ch); curl_close($ch);
    } else {
        $ctx = stream_context_create(['http' => [
            'method' => $params ? 'POST' : 'GET',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $q, 'timeout' => 20,
        ]]);
        $r = @file_get_contents($url, false, $ctx);
    }
    return json_decode((string)$r, true);
}

$data  = tg_call($token, 'getUpdates');
$found = [];
foreach (($data['result'] ?? []) as $u) {
    foreach (['message','channel_post','edited_channel_post','my_chat_member','chat_member'] as $k) {
        if (isset($u[$k]['chat'])) {
            $c = $u[$k]['chat'];
            $found[(string)$c['id']] = trim(($c['title'] ?? $c['username'] ?? $c['first_name'] ?? '?')
                . ' [' . ($c['type'] ?? '') . ']');
        }
    }
}

// выбрать канал среди найденных (или первый)
$target = '';
foreach ($found as $id => $label) { if (strpos($label, '[channel]') !== false) { $target = $id; break; } }
if ($target === '' && $found) $target = (string)array_key_first($found);

$out = [
    'ok'         => $data['ok'] ?? null,
    'db_chat_id' => s_raw('telegram_chat_id'),
    'chats'      => $found,
    'target'     => $target,
];

if (($_GET['setchat'] ?? '') === '1' && $target !== '') {
    upsert_setting(db(), 'telegram_chat_id', $target);
    $out['setchat'] = 'saved ' . $target;
}

if (($_GET['send'] ?? '') === '1' && $target !== '') {
    $res = tg_call($token, 'sendMessage', [
        'chat_id' => $target,
        'text'    => "✅ ChaiCore подключён. Заявки с сайта будут приходить сюда.",
    ]);
    $out['send_ok']  = $res['ok'] ?? false;
    $out['send_err'] = $res['description'] ?? null;
}

echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
