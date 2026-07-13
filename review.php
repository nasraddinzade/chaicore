<?php
/** review.php — приём отзыва с сайта в очередь на модерацию */
require_once __DIR__ . '/functions.php';
boot();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }

$lang = $_POST['lang'] ?? lang();
if (!in_array($lang, ['az','ru','en'], true)) $lang = 'az';

$name     = mb_substr(trim((string)($_POST['name'] ?? '')), 0, 120);
$location = mb_substr(trim((string)($_POST['location'] ?? '')), 0, 160);
$text     = mb_substr(trim((string)($_POST['text'] ?? '')), 0, 1500);
$rating   = (int)($_POST['rating'] ?? 5);
if ($rating < 1 || $rating > 5) $rating = 5;

// минимальная валидация: нужен осмысленный текст
if (mb_strlen($text) < 3) {
    header('Location: index.php?lang=' . $lang . '#testimonials');
    exit;
}

$pdo = db();
$pdo->prepare("INSERT INTO cc_reviews (name,location,text,rating,lang,status,created_at)
               VALUES (?,?,?,?,?, 'pending', ?)")
    ->execute([$name, $location, $text, $rating, $lang, time()]);

// уведомить администратора в Telegram (если бот настроен)
$tgToken = trim(s_raw('telegram_bot_token'));
$tgChat  = trim(s_raw('telegram_chat_id'));
if ($tgToken !== '' && $tgChat !== '') {
    $stars = str_repeat('⭐', $rating);
    $msg = "📝 Новый отзыв (ждёт подтверждения)\n\n"
         . ($name !== ''     ? "Имя: {$name}\n" : '')
         . ($location !== '' ? "Откуда: {$location}\n" : '')
         . "Оценка: {$stars}\nЯзык: {$lang}\n\n{$text}\n\n"
         . "Подтвердить в админке → Отзывы";
    $payload = http_build_query(['chat_id'=>$tgChat, 'text'=>$msg, 'disable_web_page_preview'=>'true']);
    $api = "https://api.telegram.org/bot{$tgToken}/sendMessage";
    if (function_exists('curl_init')) {
        $ch = curl_init($api);
        curl_setopt_array($ch, [CURLOPT_POST=>true, CURLOPT_POSTFIELDS=>$payload, CURLOPT_RETURNTRANSFER=>true, CURLOPT_TIMEOUT=>12]);
        curl_exec($ch); curl_close($ch);
    } else {
        @file_get_contents($api, false, stream_context_create(['http'=>[
            'method'=>'POST', 'header'=>"Content-Type: application/x-www-form-urlencoded\r\n",
            'content'=>$payload, 'timeout'=>12,
        ]]));
    }
}

header('Location: index.php?lang=' . $lang . '&review=1#testimonials');
exit;
