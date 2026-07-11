<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/layout.php';
require_login();
boot();
global $DEFAULT_TEXTS;

// ── Сохранение ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $posted = $_POST['t'] ?? [];
    $pdo = db();
    $count = 0;
    foreach ($posted as $key => $langs) {
        if (!isset($DEFAULT_TEXTS[$key])) continue;         // только известные ключи
        foreach (['az','ru','en'] as $lang) {
            $val = isset($langs[$lang]) ? trim((string)$langs[$lang]) : '';
            upsert_text($pdo, $key, $lang, $val);
            $count++;
        }
    }
    flash_set('ok', "Тексты сохранены ($count записей).");
    header('Location: texts.php' . (isset($_POST['_group']) ? '#g'.md5($_POST['_group']) : ''));
    exit;
}

// ── Отрисовка ──
// группируем ключи
$groups = [];
foreach ($DEFAULT_TEXTS as $key => $d) {
    $groups[$d['g']][$key] = $d;
}

admin_header('Тексты', 'texts.php');
flash_show();
?>
<h1 class="a-h1">Тексты</h1>
<p class="a-lead">Каждая надпись — на трёх языках. Пустое поле «Имя» у мастеров / авторов отзывов означает, что имя на сайте не показывается.</p>

<div class="a-toc">
  <?php foreach (array_keys($groups) as $g): ?>
    <a href="#g<?= md5($g) ?>"><?= e($g) ?></a>
  <?php endforeach; ?>
</div>

<form method="post" action="texts.php" class="a-form">
  <?= csrf_field() ?>
  <?php foreach ($groups as $g => $items): ?>
    <section class="a-group" id="g<?= md5($g) ?>">
      <h2 class="a-group-t"><?= e($g) ?></h2>
      <?php foreach ($items as $key => $d):
        $cur = $GLOBALS['CC']['texts'][$key] ?? [];
        $area = ($d['t'] ?? 'text') === 'area';
      ?>
      <div class="a-field">
        <label class="a-field-l"><?= e($d['l']) ?></label>
        <div class="a-tri">
          <?php foreach (['az'=>'AZ','ru'=>'RU','en'=>'EN'] as $lc => $ln):
            $v = $cur[$lc] ?? ''; ?>
          <div class="a-tri-col">
            <span class="a-lang"><?= $ln ?></span>
            <?php if ($area): ?>
              <textarea name="t[<?= e($key) ?>][<?= $lc ?>]" rows="3"><?= e($v) ?></textarea>
            <?php else: ?>
              <input type="text" name="t[<?= e($key) ?>][<?= $lc ?>]" value="<?= e($v) ?>">
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </section>
  <?php endforeach; ?>

  <div class="a-savebar">
    <button type="submit" class="a-btn"><i class="fa-solid fa-floppy-disk"></i> Сохранить все тексты</button>
  </div>
</form>
<?php
admin_footer();
