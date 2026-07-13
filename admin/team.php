<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/layout.php';
require_login();
boot();

$ALLOWED_MIME = ['image/jpeg','image/png','image/gif','image/webp'];
$ALLOWED_EXT  = ['jpg','jpeg','png','gif','webp'];

function safe_unlink(string $relPath): void {
    if (strpos($relPath, 'uploads/') !== 0) return;
    $abs = realpath(__DIR__ . '/../' . $relPath);
    $base = realpath(UPLOAD_DIR);
    if ($abs && $base && strpos($abs, $base) === 0 && is_file($abs)) @unlink($abs);
}
function team_upload(): string {
    global $ALLOWED_MIME, $ALLOWED_EXT;
    if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        flash_set('error', 'Файл не загружен (макс. ' . MAX_UPLOAD_MB . ' МБ).'); return '';
    }
    $f = $_FILES['file'];
    if ($f['size'] > MAX_UPLOAD_MB * 1024 * 1024) { flash_set('error', 'Файл слишком большой.'); return ''; }
    $info = @getimagesize($f['tmp_name']);
    if ($info === false || !in_array($info['mime'], $ALLOWED_MIME, true)) {
        flash_set('error', 'Это не изображение (JPG, PNG, GIF, WEBP).'); return '';
    }
    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $ALLOWED_EXT, true)) $ext = 'jpg';
    if (!is_dir(UPLOAD_DIR)) @mkdir(UPLOAD_DIR, 0755, true);
    $name = 'team-' . date('Ymd') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
    if (!move_uploaded_file($f['tmp_name'], UPLOAD_DIR . '/' . $name)) {
        flash_set('error', 'Не удалось сохранить файл (права на uploads/).'); return '';
    }
    return UPLOAD_URL . '/' . $name;
}

// ── Действия ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $pdo = db();

    if ($action === 'add') {
        $path = team_upload();
        if ($path !== '') {
            $maxSort = (int)$pdo->query("SELECT COALESCE(MAX(sort),-1) FROM cc_images WHERE slot='team'")->fetchColumn();
            $pdo->prepare("INSERT INTO cc_images (slot,path,sort) VALUES ('team',?,?)")->execute([$path, $maxSort + 1]);
            flash_set('ok', 'Участник добавлен — заполните имя, роль и описание ниже и сохраните.');
        }
    }
    elseif ($action === 'replace') {
        $id = (int)($_POST['id'] ?? 0);
        $path = team_upload();
        if ($path !== '' && $id) {
            $g = $pdo->prepare("SELECT path FROM cc_images WHERE id=? AND slot='team'");
            $g->execute([$id]);
            $op = $g->fetchColumn();
            if ($op !== false) {
                safe_unlink($op);
                $pdo->prepare("UPDATE cc_images SET path=? WHERE id=? AND slot='team'")->execute([$path, $id]);
                flash_set('ok', 'Фото участника заменено.');
            }
        }
    }
    elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $g = $pdo->prepare("SELECT path FROM cc_images WHERE id=? AND slot='team'");
        $g->execute([$id]);
        $op = $g->fetchColumn();
        if ($op !== false) {
            $pdo->prepare("DELETE FROM cc_images WHERE id=? AND slot='team'")->execute([$id]);
            safe_unlink($op);
            foreach (['name','role','desc'] as $f) {
                $pdo->prepare("DELETE FROM cc_texts WHERE text_key=?")->execute(["team_{$id}_{$f}"]);
            }
            flash_set('ok', 'Участник удалён вместе с текстом.');
        }
    }
    elseif ($action === 'savetexts') {
        $ids = [];
        foreach ($pdo->query("SELECT id FROM cc_images WHERE slot='team'") as $r) $ids[(int)$r['id']] = true;
        foreach (($_POST['t'] ?? []) as $id => $fields) {
            $id = (int)$id;
            if (!isset($ids[$id])) continue;
            foreach (['name','role','desc'] as $f) {
                foreach (['az','ru','en'] as $lang) {
                    $val = isset($fields[$f][$lang]) ? trim((string)$fields[$f][$lang]) : '';
                    upsert_text($pdo, "team_{$id}_{$f}", $lang, $val);
                }
            }
        }
        flash_set('ok', 'Тексты команды сохранены.');
    }

    header('Location: team.php');
    exit;
}

// ── Данные ──
$pdo = db();
$members = [];
foreach ($pdo->query("SELECT id,path FROM cc_images WHERE slot='team' ORDER BY sort,id") as $r) {
    $members[] = ['id' => (int)$r['id'], 'path' => $r['path']];
}
function tval(int $id, string $f, string $lang): string {
    return $GLOBALS['CC']['texts']["team_{$id}_{$f}"][$lang] ?? '';
}

admin_header('Команда', 'team.php');
flash_show();
$csrf = csrf_field();
?>
<h1 class="a-h1">Команда</h1>
<p class="a-lead">Добавляйте участников с фото. У каждого — имя, роль и описание на трёх языках (имя можно оставить пустым). Удаление участника убирает и фото, и его текст.</p>

<section class="a-group">
  <h2 class="a-group-t">Добавить участника</h2>
  <form method="post" action="team.php" enctype="multipart/form-data">
    <?= $csrf ?>
    <input type="hidden" name="action" value="add">
    <label class="img-file" style="max-width:340px">
      <i class="fa-solid fa-user-plus"></i> Выбрать фото нового участника
      <input type="file" name="file" accept="image/*" onchange="this.form.submit()">
    </label>
  </form>
</section>

<form method="post" action="team.php" class="a-form">
  <?= $csrf ?>
  <input type="hidden" name="action" value="savetexts">
  <?php if (!$members): ?>
    <section class="a-group"><p style="color:var(--text-dim)">Пока нет участников. Добавьте первого выше.</p></section>
  <?php endif; ?>
  <?php foreach ($members as $m): $id = $m['id']; ?>
    <section class="a-group">
      <div class="tm-card">
        <div class="tm-photo">
          <div class="img-thumb"><img src="../<?= e($m['path']) ?>" alt=""></div>
          <button type="button" class="a-btn ghost sm" onclick="document.getElementById('rep<?= $id ?>').click()"><i class="fa-solid fa-upload"></i> Заменить фото</button>
          <button type="button" class="a-btn ghost sm" onclick="if(confirm('Удалить участника вместе с текстом?'))document.getElementById('del<?= $id ?>').submit()"><i class="fa-solid fa-trash"></i> Удалить участника</button>
        </div>
        <div class="tm-fields">
          <div class="a-field">
            <label class="a-field-l">Имя (можно оставить пустым)</label>
            <div class="a-tri">
              <?php foreach (['az'=>'AZ','ru'=>'RU','en'=>'EN'] as $lc=>$ln): ?>
                <div class="a-tri-col"><span class="a-lang"><?= $ln ?></span>
                  <input type="text" name="t[<?= $id ?>][name][<?= $lc ?>]" value="<?= e(tval($id,'name',$lc)) ?>"></div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="a-field">
            <label class="a-field-l">Роль / должность</label>
            <div class="a-tri">
              <?php foreach (['az'=>'AZ','ru'=>'RU','en'=>'EN'] as $lc=>$ln): ?>
                <div class="a-tri-col"><span class="a-lang"><?= $ln ?></span>
                  <input type="text" name="t[<?= $id ?>][role][<?= $lc ?>]" value="<?= e(tval($id,'role',$lc)) ?>"></div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="a-field">
            <label class="a-field-l">Описание</label>
            <div class="a-tri">
              <?php foreach (['az'=>'AZ','ru'=>'RU','en'=>'EN'] as $lc=>$ln): ?>
                <div class="a-tri-col"><span class="a-lang"><?= $ln ?></span>
                  <textarea name="t[<?= $id ?>][desc][<?= $lc ?>]" rows="3"><?= e(tval($id,'desc',$lc)) ?></textarea></div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </section>
  <?php endforeach; ?>

  <?php if ($members): ?>
  <div class="a-savebar">
    <button type="submit" class="a-btn"><i class="fa-solid fa-floppy-disk"></i> Сохранить тексты команды</button>
  </div>
  <?php endif; ?>
</form>

<!-- скрытые формы для замены фото и удаления (вне основной формы) -->
<?php foreach ($members as $m): $id = $m['id']; ?>
  <form method="post" action="team.php" enctype="multipart/form-data" style="display:none">
    <?= $csrf ?><input type="hidden" name="action" value="replace"><input type="hidden" name="id" value="<?= $id ?>">
    <input type="file" id="rep<?= $id ?>" name="file" accept="image/*" onchange="this.form.submit()">
  </form>
  <form method="post" action="team.php" id="del<?= $id ?>" style="display:none">
    <?= $csrf ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $id ?>">
  </form>
<?php endforeach; ?>
<?php
admin_footer();
