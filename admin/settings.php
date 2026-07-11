<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/layout.php';
require_login();
boot();
global $DEFAULT_SETTINGS;

// ── Сохранение ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $posted = $_POST['s'] ?? [];
    $pdo = db();
    foreach ($posted as $key => $val) {
        if (!isset($DEFAULT_SETTINGS[$key])) continue;
        upsert_setting($pdo, $key, trim((string)$val));
    }
    flash_set('ok', 'Настройки сохранены.');
    header('Location: settings.php');
    exit;
}

// группировка
$groups = [];
foreach ($DEFAULT_SETTINGS as $key => $d) {
    $groups[$d['g']][$key] = $d;
}

admin_header('Настройки', 'settings.php');
flash_show();
?>
<h1 class="a-h1">Настройки</h1>
<p class="a-lead">Размеры шрифтов задаются в процентах: 100 — обычный, 120 — крупнее, 90 — мельче. «Общий масштаб» меняет размер всего текста сразу.</p>

<form method="post" action="settings.php" class="a-form">
  <?= csrf_field() ?>
  <?php foreach ($groups as $g => $items): ?>
    <section class="a-group">
      <h2 class="a-group-t"><?= e($g) ?></h2>
      <?php foreach ($items as $key => $d):
        $v = $GLOBALS['CC']['settings'][$key] ?? ($d['default'] ?? '');
        $type = $d['t'] ?? 'text';
      ?>
      <div class="a-field a-field-row">
        <label class="a-field-l"><?= e($d['l']) ?></label>
        <?php if ($type === 'area'): ?>
          <textarea name="s[<?= e($key) ?>]" rows="3" class="a-wide"><?= e($v) ?></textarea>
        <?php elseif ($type === 'number'): ?>
          <input type="number" name="s[<?= e($key) ?>]" value="<?= e($v) ?>" min="50" max="200" step="1" class="a-num">
        <?php elseif ($type === 'color'): ?>
          <input type="color" name="s[<?= e($key) ?>]" value="<?= e($v) ?>">
        <?php else: ?>
          <input type="text" name="s[<?= e($key) ?>]" value="<?= e($v) ?>" class="a-wide">
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </section>
  <?php endforeach; ?>

  <div class="a-savebar">
    <button type="submit" class="a-btn"><i class="fa-solid fa-floppy-disk"></i> Сохранить настройки</button>
  </div>
</form>
<?php
admin_footer();
