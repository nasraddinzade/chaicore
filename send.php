<?php
/** send.php — приём заявки из формы и отправка на email владельца */
require_once __DIR__ . '/functions.php';
boot();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$to = s_raw('form_to_email', s_raw('contact_email', ''));
$brand = s_raw('brand_name', 'ChaiCore');

// сбор полей (без доверия к содержимому)
function field(string $k): string { return trim((string)($_POST[$k] ?? '')); }

$isNews = !empty($_POST['newsletter']);

if ($isNews) {
    $email = field('email');
    $subject = "[$brand] Подписка на новости";
    $body = "Новая подписка на рассылку:\nEmail: $email\n";
} else {
    $name    = field('name');
    $surname = field('surname');
    $email   = field('email');
    $phone   = field('phone');
    $service = field('service');
    $date    = field('date');
    $guests  = field('guests');
    $message = field('message');

    $subject = "[$brand] Новая заявка на бронирование";
    $body  = "Новая заявка с сайта $brand:\n\n";
    $body .= "Имя:        $name $surname\n";
    $body .= "Email:      $email\n";
    $body .= "Телефон:    $phone\n";
    $body .= "Услуга:     $service\n";
    $body .= "Дата:       $date\n";
    $body .= "Гостей:     $guests\n";
    $body .= "Сообщение:  $message\n";
}

$ok = false;
if ($to !== '' && filter_var($to, FILTER_VALIDATE_EMAIL)) {
    // защита заголовков от инъекций
    $safeFrom = 'no-reply@' . preg_replace('/[^a-z0-9.\-]/i', '', $_SERVER['HTTP_HOST'] ?? 'localhost');
    $headers  = "From: $brand <$safeFrom>\r\n";
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $headers .= "Reply-To: " . str_replace(["\r","\n"], '', $email) . "\r\n";
    }
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $ok = @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, $headers);
}

// ── отправка заявки в Telegram-бота ──
$tgToken = trim(s_raw('telegram_bot_token'));
$tgChat  = trim(s_raw('telegram_chat_id'));
$tgOk = false;
if ($tgToken !== '' && $tgChat !== '') {
    $tgText  = ($isNews ? "📨 " : "🫖 ") . $body;
    $api     = "https://api.telegram.org/bot{$tgToken}/sendMessage";
    $payload = http_build_query([
        'chat_id' => $tgChat,
        'text'    => $tgText,
        'disable_web_page_preview' => 'true',
    ]);
    $resp = false;
    if (function_exists('curl_init')) {
        $ch = curl_init($api);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
        ]);
        $resp = curl_exec($ch);
        curl_close($ch);
    } else {
        $ctx = stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $payload,
            'timeout' => 15,
        ]]);
        $resp = @file_get_contents($api, false, $ctx);
    }
    $tgOk = ($resp !== false && strpos((string)$resp, '"ok":true') !== false);
}

// успех, если заявка ушла хотя бы одним каналом (Telegram или email)
$ok = $ok || $tgOk;

// вернуться на сайт с уведомлением
$lang = lang();
header('Location: index.php?lang=' . $lang . '&sent=' . ($ok ? '1' : '0') . '#contact');
exit;
