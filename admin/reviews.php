<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/layout.php';
require_login();
boot();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    $pdo = db();

    if ($action === 'approve' && $id) {
        $pdo->prepare("UPDATE cc_reviews SET status='approved' WHERE id=?")->execute([$id]);
        flash_set('ok', 'Отзыв опубликован.');
    } elseif ($action === 'unapprove' && $id) {
        $pdo->prepare("UPDATE cc_reviews SET status='pending' WHERE id=?")->execute([$id]);
        flash_set('ok', 'Отзыв снят с публикации (в очередь).');
    } elseif ($action === 'delete' && $id) {
        $pdo->prepare("DELETE FROM cc_reviews WHERE id=?")->execute([$id]);
        flash_set('ok', 'Отзыв удалён.');
    } elseif ($action === 'add') {
        $name     = mb_substr(trim((string)($_POST['name'] ?? '')), 0, 120);
        $location = mb_substr(trim((string)($_POST['location'] ?? '')), 0, 160);
        $text     = mb_substr(trim((string)($_POST['text'] ?? '')), 0, 1500);
        $rating   = (int)($_POST['rating'] ?? 5); if ($rating < 1 || $rating > 5) $rating = 5;
        $lang     = in_array($_POST['lang'] ?? '', ['az','ru','en'], true) ? $_POST['lang'] : 'az';
        if (mb_strlen($text) >= 3) {
            $pdo->prepare("INSERT INTO cc_reviews (name,location,text,rating,lang,status,created_at) VALUES (?,?,?,?,?, 'approved', ?)")
                ->execute([$name, $location, $text, $rating, $lang, time()]);
            flash_set('ok', 'Отзыв добавлен и опубликован.');
        } else {
            flash_set('error', 'Текст отзыва слишком короткий.');
        }
    }
    header('Location: reviews.php');
    exit;
}

$pdo = db();
$pending  = $pdo->query("SELECT * FROM cc_reviews WHERE status='pending'  ORDER BY created_at DESC, id DESC")->fetchAll();
$approved = $pdo->query("SELECT * FROM cc_reviews WHERE status='approved' ORDER BY lang, id")->fetchAll();

function rv_stars($n){ $n = max(1, min(5, (int)$n)); return str_repeat('★', $n) . str_repeat('☆', 5 - $n); }

admin_header('Отзывы', 'reviews.php');
flash_show();
$csrf = csrf_field();
?>
<h1 class="a-h1">Отзывы</h1>
<p class="a-lead">Отзывы, оставленные на сайте, попадают сюда на модерацию и публикуются только после подтверждения. Можно также добавить отзыв от себя — он опубликуется сразу.</p>

<section class="a-group">
  <h2 class="a-group-t">На модерации <span class="img-count"><?= count($pending) ?></span></h2>
  <?php if (!$pending): ?><p style="color:var(--text-dim)">Новых отзывов нет.</p><?php endif; ?>
  <?php foreach ($pending as $r): ?>
    <div class="rv-item rv-pending">
      <div class="rv-meta"><b><?= e($r['name'] !== '' ? $r['name'] : '(без имени)') ?></b>
        <?php if ($r['location'] !== ''): ?><span><?= e($r['location']) ?></span><?php endif; ?>
        <span class="rv-stars"><?= rv_stars($r['rating']) ?></span>
        <span class="rv-lang"><?= e(strtoupper($r['lang'])) ?></span></div>
      <div class="rv-text"><?= e($r['text']) ?></div>
      <div class="rv-actions">
        <form method="post" action="reviews.php"><?= $csrf ?><input type="hidden" name="action" value="approve"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><button class="a-btn sm"><i class="fa-solid fa-check"></i> Опубликовать</button></form>
        <form method="post" action="reviews.php" onsubmit="return confirm('Удалить отзыв?')"><?= $csrf ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><button class="a-btn ghost sm"><i class="fa-solid fa-trash"></i> Удалить</button></form>
      </div>
    </div>
  <?php endforeach; ?>
</section>

<section class="a-group">
  <h2 class="a-group-t">Добавить отзыв от себя</h2>
  <form method="post" action="reviews.php" class="a-form">
    <?= $csrf ?><input type="hidden" name="action" value="add">
    <div class="a-field a-field-row"><label class="a-field-l">Имя</label><input type="text" name="name" class="a-wide"></div>
    <div class="a-field a-field-row"><label class="a-field-l">Город / компания</label><input type="text" name="location" class="a-wide"></div>
    <div class="a-field a-field-row"><label class="a-field-l">Язык отзыва</label>
      <select name="lang" class="a-num"><option value="az">AZ</option><option value="ru">RU</option><option value="en">EN</option></select></div>
    <div class="a-field a-field-row"><label class="a-field-l">Оценка</label>
      <select name="rating" class="a-num"><option>5</option><option>4</option><option>3</option><option>2</option><option>1</option></select></div>
    <div class="a-field"><label class="a-field-l">Текст отзыва</label><textarea name="text" rows="3" required></textarea></div>
    <button type="submit" class="a-btn"><i class="fa-solid fa-plus"></i> Добавить и опубликовать</button>
  </form>
</section>

<section class="a-group">
  <h2 class="a-group-t">Опубликованные <span class="img-count"><?= count($approved) ?></span></h2>
  <?php if (!$approved): ?><p style="color:var(--text-dim)">Пока нет опубликованных отзывов.</p><?php endif; ?>
  <?php foreach ($approved as $r): ?>
    <div class="rv-item">
      <div class="rv-meta"><b><?= e($r['name'] !== '' ? $r['name'] : '(без имени)') ?></b>
        <?php if ($r['location'] !== ''): ?><span><?= e($r['location']) ?></span><?php endif; ?>
        <span class="rv-stars"><?= rv_stars($r['rating']) ?></span>
        <span class="rv-lang"><?= e(strtoupper($r['lang'])) ?></span></div>
      <div class="rv-text"><?= e($r['text']) ?></div>
      <div class="rv-actions">
        <form method="post" action="reviews.php"><?= $csrf ?><input type="hidden" name="action" value="unapprove"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><button class="a-btn ghost sm"><i class="fa-solid fa-eye-slash"></i> Скрыть</button></form>
        <form method="post" action="reviews.php" onsubmit="return confirm('Удалить отзыв?')"><?= $csrf ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><button class="a-btn ghost sm"><i class="fa-solid fa-trash"></i> Удалить</button></form>
      </div>
    </div>
  <?php endforeach; ?>
</section>
<?php
admin_footer();
