<?php
require_once __DIR__ . '/auth.php';

if (is_logged_in()) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $u = trim($_POST['user'] ?? '');
    $p = (string)($_POST['pass'] ?? '');
    if (attempt_login($u, $p)) {
        header('Location: index.php');
        exit;
    }
    $error = 'Неверный логин или пароль.';
}
?><!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Вход — ChaiCore Admin</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="admin.css">
</head>
<body class="a-login-body">
<form class="a-login" method="post" action="login.php">
  <div class="a-login-logo"><i class="fa-solid fa-mug-hot"></i></div>
  <h1>ChaiCore <span>Admin</span></h1>
  <p class="a-login-sub">Панель управления сайтом</p>
  <?php if ($error): ?><div class="a-flash err"><i class="fa-solid fa-triangle-exclamation"></i> <?= e($error) ?></div><?php endif; ?>
  <?= csrf_field() ?>
  <label>Логин</label>
  <input type="text" name="user" autofocus required>
  <label>Пароль</label>
  <input type="password" name="pass" required>
  <button type="submit"><i class="fa-solid fa-right-to-bracket"></i> Войти</button>
</form>
</body>
</html>
