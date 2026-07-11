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

// вернуться на сайт с уведомлением
$lang = lang();
header('Location: index.php?lang=' . $lang . '&sent=' . ($ok ? '1' : '0') . '#contact');
exit;
