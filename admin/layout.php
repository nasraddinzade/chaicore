<?php
/** admin/layout.php — общий каркас страниц админки */

function admin_header(string $title, string $active = ''): void {
    $nav = [
        'index.php'    => ['Обзор',    'fa-gauge'],
        'texts.php'    => ['Тексты',   'fa-font'],
        'images.php'   => ['Фото',     'fa-image'],
        'team.php'     => ['Команда',  'fa-users'],
        'settings.php' => ['Настройки','fa-sliders'],
    ];
    ?><!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title) ?> — ChaiCore Admin</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="admin.css">
</head>
<body>
<header class="a-top">
  <div class="a-brand"><i class="fa-solid fa-mug-hot"></i> ChaiCore <span>Admin</span></div>
  <nav class="a-nav">
    <?php foreach ($nav as $file => $item): ?>
      <a href="<?= $file ?>" class="<?= $active === $file ? 'on' : '' ?>"><i class="fa-solid <?= $item[1] ?>"></i> <?= $item[0] ?></a>
    <?php endforeach; ?>
  </nav>
  <div class="a-right">
    <a href="../index.php" target="_blank" class="a-view"><i class="fa-solid fa-arrow-up-right-from-square"></i> Сайт</a>
    <a href="logout.php" class="a-logout"><i class="fa-solid fa-right-from-bracket"></i> Выход</a>
  </div>
</header>
<main class="a-main">
<?php
}

function admin_footer(): void {
    ?>
</main>
<script>
  // предпросмотр выбранного файла до загрузки
  document.querySelectorAll('input[type=file]').forEach(inp => {
    inp.addEventListener('change', () => {
      const f = inp.files[0]; if (!f) return;
      const prev = inp.closest('.img-slot')?.querySelector('img');
      if (prev) prev.src = URL.createObjectURL(f);
    });
  });
</script>
</body>
</html>
<?php
}

function flash_show(): void {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $cls = $f['type'] === 'error' ? 'a-flash err' : 'a-flash ok';
        echo '<div class="' . $cls . '"><i class="fa-solid ' .
             ($f['type']==='error'?'fa-triangle-exclamation':'fa-circle-check') . '"></i> ' .
             e($f['msg']) . '</div>';
    }
}
function flash_set(string $type, string $msg): void {
    $_SESSION['flash'] = ['type'=>$type, 'msg'=>$msg];
}
