<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/layout.php';
require_login();
boot();
global $DEFAULT_IMAGES;

$ALLOWED_EXT  = ['jpg','jpeg','png','gif','webp'];
$ALLOWED_MIME = ['image/jpeg','image/png','image/gif','image/webp'];

/** Безопасное удаление файла с диска — только внутри папки uploads */
function safe_unlink(string $relPath): void {
    if (strpos($relPath, 'uploads/') !== 0) return;      // трогаем только загруженные, не assets/
    $abs = realpath(__DIR__ . '/../' . $relPath);
    $base = realpath(UPLOAD_DIR);
    if ($abs && $base && strpos($abs, $base) === 0 && is_file($abs)) {
        @unlink($abs);
    }
}

/** Обработка загруженного файла → возвращает относительный путь 'uploads/..' или '' */
function handle_upload(string $slot): string {
    global $ALLOWED_EXT, $ALLOWED_MIME;
    if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        flash_set('error', 'Файл не загружен (проверь размер — макс. ' . MAX_UPLOAD_MB . ' МБ).');
        return '';
    }
    $f = $_FILES['file'];
    if ($f['size'] > MAX_UPLOAD_MB * 1024 * 1024) {
        flash_set('error', 'Файл слишком большой (макс. ' . MAX_UPLOAD_MB . ' МБ).');
        return '';
    }
    $info = @getimagesize($f['tmp_name']);
    if ($info === false || !in_array($info['mime'], $ALLOWED_MIME, true)) {
        flash_set('error', 'Это не изображение или неподдерживаемый формат (JPG, PNG, GIF, WEBP).');
        return '';
    }
    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $ALLOWED_EXT, true)) $ext = 'jpg';

    if (!is_dir(UPLOAD_DIR)) @mkdir(UPLOAD_DIR, 0755, true);
    $safeSlot = preg_replace('/[^a-z0-9_]/i', '', $slot);
    $name = $safeSlot . '-' . date('Ymd') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
    $dest = UPLOAD_DIR . '/' . $name;

    if (!move_uploaded_file($f['tmp_name'], $dest)) {
        flash_set('error', 'Не удалось сохранить файл. Проверь права на папку uploads/.');
        return '';
    }
    return UPLOAD_URL . '/' . $name;
}

// ── Обработка действий ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $slot   = $_POST['slot'] ?? '';
    $pdo = db();

    if ($action === 'upload' && isset($DEFAULT_IMAGES[$slot])) {
        $path = handle_upload($slot);
        if ($path !== '') {
            if ($DEFAULT_IMAGES[$slot]['multi']) {
                // добавить в конец галереи
                $maxSort = (int)$pdo->query("SELECT COALESCE(MAX(sort),-1) FROM cc_images WHERE slot=" . $pdo->quote($slot))->fetchColumn();
                $ins = $pdo->prepare("INSERT INTO cc_images (slot,path,sort) VALUES (?,?,?)");
                $ins->execute([$slot, $path, $maxSort + 1]);
                flash_set('ok', 'Фото добавлено в галерею.');
            } else {
                // заменить единственное фото слота
                $old = $pdo->prepare("SELECT path FROM cc_images WHERE slot=?");
                $old->execute([$slot]);
                foreach ($old->fetchAll(PDO::FETCH_COLUMN) as $op) safe_unlink($op);
                $pdo->prepare("DELETE FROM cc_images WHERE slot=?")->execute([$slot]);
                $pdo->prepare("INSERT INTO cc_images (slot,path,sort) VALUES (?,?,0)")->execute([$slot, $path]);
                flash_set('ok', 'Фото заменено.');
            }
        }
    }
    elseif ($action === 'delete_id') {
        $id = (int)($_POST['id'] ?? 0);
        $row = $pdo->prepare("SELECT path FROM cc_images WHERE id=?");
        $row->execute([$id]);
        $p = $row->fetchColumn();
        if ($p !== false) {
            $pdo->prepare("DELETE FROM cc_images WHERE id=?")->execute([$id]);
            safe_unlink($p);
            flash_set('ok', 'Фото удалено.');
        }
    }
    elseif ($action === 'delete_slot' && isset($DEFAULT_IMAGES[$slot])) {
        $old = $pdo->prepare("SELECT path FROM cc_images WHERE slot=?");
        $old->execute([$slot]);
        foreach ($old->fetchAll(PDO::FETCH_COLUMN) as $op) safe_unlink($op);
        $pdo->prepare("DELETE FROM cc_images WHERE slot=?")->execute([$slot]);
        flash_set('ok', 'Фото удалено (слот теперь пуст).');
    }

    header('Location: images.php');
    exit;
}

// ── Данные для отображения ──
$pdo = db();
$bySlot = [];
foreach ($pdo->query("SELECT id,slot,path,sort FROM cc_images ORDER BY slot,sort,id") as $r) {
    $bySlot[$r['slot']][] = $r;
}

// сгруппировать слоты по группе
$groups = [];
foreach ($DEFAULT_IMAGES as $slot => $d) {
    $groups[$d['g']][$slot] = $d;
}

admin_header('Фото', 'images.php');
flash_show();
$csrf = csrf_field();
?>
<h1 class="a-h1">Фото и логотипы</h1>
<p class="a-lead">Замени любое изображение, загрузив новое (JPG, PNG, GIF, WEBP, до <?= MAX_UPLOAD_MB ?> МБ). Одиночные фото можно удалить (тогда место останется пустым), в галерею — добавлять и удалять сколько угодно.</p>

<?php foreach ($groups as $g => $slots): ?>
<section class="a-group">
  <h2 class="a-group-t"><?= e($g) ?></h2>
  <div class="img-grid">
  <?php foreach ($slots as $slot => $d):
      $rows = $bySlot[$slot] ?? [];
      $multi = $d['multi'];
  ?>
    <?php if (!$multi): ?>
      <?php $row = $rows[0] ?? null; ?>
      <div class="img-slot">
        <div class="img-slot-l"><?= e($d['l']) ?></div>
        <div class="img-thumb">
          <?php if ($row): ?><img src="../<?= e($row['path']) ?>" alt="">
          <?php else: ?><div class="img-empty"><i class="fa-solid fa-image"></i> нет фото</div><?php endif; ?>
        </div>
        <form method="post" action="images.php" enctype="multipart/form-data" class="img-actions">
          <?= $csrf ?>
          <input type="hidden" name="action" value="upload">
          <input type="hidden" name="slot" value="<?= e($slot) ?>">
          <label class="img-file"><i class="fa-solid fa-upload"></i> Выбрать файл
            <input type="file" name="file" accept="image/*" onchange="this.form.querySelector('.img-go').style.display='inline-flex'">
          </label>
          <button type="submit" class="a-btn sm img-go" style="display:none"><i class="fa-solid fa-check"></i> Заменить</button>
        </form>
        <div class="img-note"><i class="fa-solid fa-lock"></i> Фото можно только заменить</div>
      </div>
    <?php else: ?>
      <div class="img-slot img-slot-multi">
        <div class="img-slot-l"><?= e($d['l']) ?> <span class="img-count"><?= count($rows) ?> шт.</span></div>
        <div class="gal-grid">
          <?php foreach ($rows as $row): ?>
          <div class="gal-cell">
            <img src="../<?= e($row['path']) ?>" alt="">
            <form method="post" action="images.php" onsubmit="return confirm('Удалить это фото из галереи?')">
              <?= $csrf ?>
              <input type="hidden" name="action" value="delete_id">
              <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
              <button type="submit" class="gal-del" title="Удалить"><i class="fa-solid fa-xmark"></i></button>
            </form>
          </div>
          <?php endforeach; ?>
          <form method="post" action="images.php" enctype="multipart/form-data" class="gal-add">
            <?= $csrf ?>
            <input type="hidden" name="action" value="upload">
            <input type="hidden" name="slot" value="<?= e($slot) ?>">
            <label class="gal-add-label"><i class="fa-solid fa-plus"></i><span>Добавить</span>
              <input type="file" name="file" accept="image/*" onchange="this.form.submit()">
            </label>
          </form>
        </div>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>
  </div>
</section>
<?php endforeach; ?>
<?php
admin_footer();
