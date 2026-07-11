<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/layout.php';
require_login();
boot();

$pdo = db();
$nTexts = (int)$pdo->query("SELECT COUNT(*) FROM cc_texts")->fetchColumn();
$nImgs  = (int)$pdo->query("SELECT COUNT(*) FROM cc_images")->fetchColumn();
$nGal   = count(imgs('gallery'));

admin_header('Обзор', 'index.php');
flash_show();
?>
<h1 class="a-h1">Обзор</h1>
<p class="a-lead">Отсюда меняется весь контент сайта — тексты на трёх языках, фотографии, логотипы и размеры шрифтов. Изменения появляются на сайте сразу после сохранения.</p>

<div class="a-cards">
  <a class="a-card" href="texts.php">
    <div class="a-card-ic"><i class="fa-solid fa-font"></i></div>
    <div class="a-card-t">Тексты</div>
    <div class="a-card-d">Все надписи на AZ / RU / EN</div>
  </a>
  <a class="a-card" href="images.php">
    <div class="a-card-ic"><i class="fa-solid fa-image"></i></div>
    <div class="a-card-t">Фото и логотипы</div>
    <div class="a-card-d">Замена, загрузка и удаление изображений</div>
  </a>
  <a class="a-card" href="settings.php">
    <div class="a-card-ic"><i class="fa-solid fa-sliders"></i></div>
    <div class="a-card-t">Настройки</div>
    <div class="a-card-d">Шрифты, контакты, соцсети, цифры</div>
  </a>
</div>

<div class="a-stats">
  <div class="a-stat"><b><?= $nTexts ?></b><span>текстовых записей</span></div>
  <div class="a-stat"><b><?= $nImgs ?></b><span>изображений всего</span></div>
  <div class="a-stat"><b><?= $nGal ?></b><span>фото в галерее</span></div>
</div>

<div class="a-note">
  <i class="fa-solid fa-shield-halved"></i>
  <div>
    <b>Безопасность.</b> Логин и пароль задаются в файле <code>config.php</code> (строки <code>ADMIN_USER</code> / <code>ADMIN_PASS</code>).
    Смени пароль по умолчанию перед публикацией сайта.
  </div>
</div>
<?php
admin_footer();
