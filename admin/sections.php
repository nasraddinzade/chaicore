<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/layout.php';
require_login();
boot();

$ALLOWED_MIME = ['image/jpeg','image/png','image/gif','image/webp'];
$ALLOWED_EXT  = ['jpg','jpeg','png','gif','webp'];

function safe_unlink(string $rel): void {
    if (strpos($rel, 'uploads/') !== 0) return;
    $abs = realpath(__DIR__ . '/../' . $rel); $base = realpath(UPLOAD_DIR);
    if ($abs && $base && strpos($abs, $base) === 0 && is_file($abs)) @unlink($abs);
}
function sec_upload(): string {
    global $ALLOWED_MIME, $ALLOWED_EXT;
    if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) { flash_set('error','Файл не загружен.'); return ''; }
    $f = $_FILES['file'];
    if ($f['size'] > MAX_UPLOAD_MB*1024*1024) { flash_set('error','Файл слишком большой.'); return ''; }
    $info = @getimagesize($f['tmp_name']);
    if ($info === false || !in_array($info['mime'], $ALLOWED_MIME, true)) { flash_set('error','Это не изображение.'); return ''; }
    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION)); if (!in_array($ext,$ALLOWED_EXT,true)) $ext='jpg';
    if (!is_dir(UPLOAD_DIR)) @mkdir(UPLOAD_DIR, 0755, true);
    $name = 'section-' . date('Ymd') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
    if (!move_uploaded_file($f['tmp_name'], UPLOAD_DIR . '/' . $name)) { flash_set('error','Не удалось сохранить файл.'); return ''; }
    return UPLOAD_URL . '/' . $name;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    $pdo = db();
    $secExists = function(int $id) use ($pdo){ $s=$pdo->prepare("SELECT 1 FROM cc_sections WHERE id=?"); $s->execute([$id]); return (bool)$s->fetchColumn(); };

    if ($action === 'add') {
        $max = (int)$pdo->query("SELECT COALESCE(MAX(sort),-1) FROM cc_sections")->fetchColumn();
        $pdo->prepare("INSERT INTO cc_sections (sort,bg,visible,created_at) VALUES (?,0,1,?)")->execute([$max+1, time()]);
        flash_set('ok', 'Раздел добавлен — заполните заголовок и текст ниже.');
    }
    elseif ($action === 'save') {
        foreach (($_POST['sec'] ?? []) as $sid => $data) {
            $sid = (int)$sid; if (!$secExists($sid)) continue;
            $bg = !empty($data['bg']) ? 1 : 0;
            $vis = !empty($data['visible']) ? 1 : 0;
            $pdo->prepare("UPDATE cc_sections SET bg=?, visible=? WHERE id=?")->execute([$bg, $vis, $sid]);
            foreach (['tag','title','body'] as $f) {
                foreach (['az','ru','en'] as $lang) {
                    $val = isset($data[$f][$lang]) ? trim((string)$data[$f][$lang]) : '';
                    upsert_text($pdo, "sec_{$sid}_{$f}", $lang, $val);
                }
            }
        }
        flash_set('ok', 'Разделы сохранены.');
    }
    elseif ($action === 'img' && $secExists($id)) {
        $path = sec_upload();
        if ($path !== '') {
            $old = $pdo->prepare("SELECT path FROM cc_images WHERE slot=?"); $old->execute(["section_{$id}"]);
            foreach ($old->fetchAll(PDO::FETCH_COLUMN) as $op) safe_unlink($op);
            $pdo->prepare("DELETE FROM cc_images WHERE slot=?")->execute(["section_{$id}"]);
            $pdo->prepare("INSERT INTO cc_images (slot,path,sort) VALUES (?,?,0)")->execute(["section_{$id}", $path]);
            flash_set('ok', 'Фото раздела сохранено.');
        }
    }
    elseif ($action === 'img_remove' && $secExists($id)) {
        $old = $pdo->prepare("SELECT path FROM cc_images WHERE slot=?"); $old->execute(["section_{$id}"]);
        foreach ($old->fetchAll(PDO::FETCH_COLUMN) as $op) safe_unlink($op);
        $pdo->prepare("DELETE FROM cc_images WHERE slot=?")->execute(["section_{$id}"]);
        flash_set('ok', 'Фото раздела удалено.');
    }
    elseif (($action === 'up' || $action === 'down') && $secExists($id)) {
        $list = $pdo->query("SELECT id,sort FROM cc_sections ORDER BY sort,id")->fetchAll();
        $idx = null; foreach ($list as $k=>$r) if ((int)$r['id']===$id) { $idx=$k; break; }
        $sw = $action==='up' ? $idx-1 : $idx+1;
        if ($idx!==null && $sw>=0 && $sw<count($list)) {
            $a=$list[$idx]; $b=$list[$sw];
            $u=$pdo->prepare("UPDATE cc_sections SET sort=? WHERE id=?");
            $u->execute([(int)$b['sort'], (int)$a['id']]);
            $u->execute([(int)$a['sort'], (int)$b['id']]);
        }
    }
    elseif ($action === 'delete' && $secExists($id)) {
        $old = $pdo->prepare("SELECT path FROM cc_images WHERE slot=?"); $old->execute(["section_{$id}"]);
        foreach ($old->fetchAll(PDO::FETCH_COLUMN) as $op) safe_unlink($op);
        $pdo->prepare("DELETE FROM cc_images WHERE slot=?")->execute(["section_{$id}"]);
        foreach (['tag','title','body'] as $f) $pdo->prepare("DELETE FROM cc_texts WHERE text_key=?")->execute(["sec_{$id}_{$f}"]);
        $pdo->prepare("DELETE FROM cc_sections WHERE id=?")->execute([$id]);
        flash_set('ok', 'Раздел удалён.');
    }
    header('Location: sections.php');
    exit;
}

$pdo = db();
$sections = $pdo->query("SELECT id,bg,visible FROM cc_sections ORDER BY sort,id")->fetchAll();
function stv(int $id, string $f, string $lang): string { return $GLOBALS['CC']['texts']["sec_{$id}_{$f}"][$lang] ?? ''; }

admin_header('Разделы', 'sections.php');
flash_show();
$csrf = csrf_field();
?>
<h1 class="a-h1">Разделы</h1>
<p class="a-lead">Свои разделы для сайта: заголовок, текст и (по желанию) фото — на трёх языках. Показываются между «Отзывами» и «Контактами». Порядок меняется стрелками, видимость — галочкой.</p>

<section class="a-group">
  <h2 class="a-group-t">Добавить раздел</h2>
  <form method="post" action="sections.php">
    <?= $csrf ?><input type="hidden" name="action" value="add">
    <button type="submit" class="a-btn"><i class="fa-solid fa-plus"></i> Новый раздел</button>
  </form>
</section>

<form method="post" action="sections.php" class="a-form">
  <?= $csrf ?><input type="hidden" name="action" value="save">
  <?php if (!$sections): ?>
    <section class="a-group"><p style="color:var(--text-dim)">Пока нет своих разделов. Добавьте первый выше.</p></section>
  <?php endif; ?>
  <?php foreach ($sections as $pos => $s): $id=(int)$s['id']; $img = has_img("section_{$id}") ? img("section_{$id}") : ''; ?>
    <section class="a-group">
      <div class="a-group-t" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        <span>Раздел #<?= $id ?></span>
        <label class="a-check" style="font-size:.85rem"><input type="checkbox" name="sec[<?= $id ?>][visible]" value="1" <?= $s['visible']?'checked':'' ?>><span>Показывать</span></label>
        <label class="a-check" style="font-size:.85rem"><input type="checkbox" name="sec[<?= $id ?>][bg]" value="1" <?= $s['bg']?'checked':'' ?>><span>Светлее фон</span></label>
        <span style="margin-left:auto;display:inline-flex;gap:6px">
          <button type="button" class="a-btn ghost sm" onclick="document.getElementById('up<?= $id ?>').submit()" <?= $pos===0?'disabled':'' ?>><i class="fa-solid fa-arrow-up"></i></button>
          <button type="button" class="a-btn ghost sm" onclick="document.getElementById('dn<?= $id ?>').submit()" <?= $pos===count($sections)-1?'disabled':'' ?>><i class="fa-solid fa-arrow-down"></i></button>
          <button type="button" class="a-btn ghost sm" onclick="if(confirm('Удалить раздел?'))document.getElementById('del<?= $id ?>').submit()"><i class="fa-solid fa-trash"></i></button>
        </span>
      </div>

      <div class="a-field">
        <label class="a-field-l">Метка (маленький текст над заголовком)</label>
        <div class="a-tri"><?php foreach (['az'=>'AZ','ru'=>'RU','en'=>'EN'] as $lc=>$ln): ?>
          <div class="a-tri-col"><span class="a-lang"><?= $ln ?></span><input type="text" name="sec[<?= $id ?>][tag][<?= $lc ?>]" value="<?= e(stv($id,'tag',$lc)) ?>"></div>
        <?php endforeach; ?></div>
      </div>
      <div class="a-field">
        <label class="a-field-l">Заголовок</label>
        <div class="a-tri"><?php foreach (['az'=>'AZ','ru'=>'RU','en'=>'EN'] as $lc=>$ln): ?>
          <div class="a-tri-col"><span class="a-lang"><?= $ln ?></span><input type="text" name="sec[<?= $id ?>][title][<?= $lc ?>]" value="<?= e(stv($id,'title',$lc)) ?>"></div>
        <?php endforeach; ?></div>
      </div>
      <div class="a-field">
        <label class="a-field-l">Текст</label>
        <div class="a-tri"><?php foreach (['az'=>'AZ','ru'=>'RU','en'=>'EN'] as $lc=>$ln): ?>
          <div class="a-tri-col"><span class="a-lang"><?= $ln ?></span><textarea name="sec[<?= $id ?>][body][<?= $lc ?>]" rows="4"><?= e(stv($id,'body',$lc)) ?></textarea></div>
        <?php endforeach; ?></div>
      </div>
      <div class="a-field a-field-row">
        <label class="a-field-l">Фото (необязательно)</label>
        <?php if ($img): ?><img src="../<?= e($img) ?>" alt="" style="height:70px;border-radius:6px"><?php endif; ?>
        <button type="button" class="a-btn ghost sm" onclick="document.getElementById('secimg<?= $id ?>').click()"><i class="fa-solid fa-upload"></i> <?= $img?'Заменить':'Загрузить' ?></button>
        <?php if ($img): ?><button type="button" class="a-btn ghost sm" onclick="document.getElementById('secimgrm<?= $id ?>').submit()"><i class="fa-solid fa-xmark"></i> Убрать фото</button><?php endif; ?>
      </div>
    </section>
  <?php endforeach; ?>

  <?php if ($sections): ?>
  <div class="a-savebar"><button type="submit" class="a-btn"><i class="fa-solid fa-floppy-disk"></i> Сохранить разделы</button></div>
  <?php endif; ?>
</form>

<!-- скрытые формы: фото, порядок, удаление -->
<?php foreach ($sections as $s): $id=(int)$s['id']; ?>
  <form method="post" action="sections.php" enctype="multipart/form-data" style="display:none"><?= $csrf ?><input type="hidden" name="action" value="img"><input type="hidden" name="id" value="<?= $id ?>"><input type="file" id="secimg<?= $id ?>" name="file" accept="image/*" onchange="this.form.submit()"></form>
  <form method="post" action="sections.php" id="secimgrm<?= $id ?>" style="display:none"><?= $csrf ?><input type="hidden" name="action" value="img_remove"><input type="hidden" name="id" value="<?= $id ?>"></form>
  <form method="post" action="sections.php" id="up<?= $id ?>" style="display:none"><?= $csrf ?><input type="hidden" name="action" value="up"><input type="hidden" name="id" value="<?= $id ?>"></form>
  <form method="post" action="sections.php" id="dn<?= $id ?>" style="display:none"><?= $csrf ?><input type="hidden" name="action" value="down"><input type="hidden" name="id" value="<?= $id ?>"></form>
  <form method="post" action="sections.php" id="del<?= $id ?>" style="display:none"><?= $csrf ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $id ?>"></form>
<?php endforeach; ?>
<?php
admin_footer();
