<?php
/**
 * ÇAYCORE — сайт (PHP-клиент).
 * © 2026 ÇAYCORE. Лицензия MIT — см. LICENSE.
 */
require_once __DIR__ . '/functions.php';
boot();
$L = lang();
// множители размеров шрифта (проценты → доля)
$fs  = max(50, min(200, (int)s_raw('font_scale','100')))    / 100;
$fsh = max(50, min(200, (int)s_raw('scale_hero','100')))    / 100;
$fss = max(50, min(200, (int)s_raw('scale_section','100'))) / 100;
$fsm = max(50, min(200, (int)s_raw('scale_mobile','100')))  / 100;

// разделы, скрытые на телефонах (управляется галочками в админке).
// если настройки ещё нет в базе — берём дефолт из content-defaults.php
$mobileSections = ['about','philosophy','services','ceremony','team','gallery','testimonials','contact'];
$mobileHidden = [];
foreach ($mobileSections as $sec) {
    $key = "mobile_show_$sec";
    $def = $DEFAULT_SETTINGS[$key]['default'] ?? '1';
    if (s_raw($key, $def) !== '1') $mobileHidden[] = $sec;
}

// цвета сайта из настроек → CSS-переменные (перекрывают styles.css)
$colorMap = [
    'col_navy'=>'--navy','col_navy2'=>'--navy-2','col_navy3'=>'--navy-3',
    'col_crimson'=>'--crimson','col_crimson2'=>'--crimson-2',
    'col_orange'=>'--orange','col_orange2'=>'--orange-2','col_orangedim'=>'--orange-dim',
    'col_cream'=>'--cream','col_cream2'=>'--cream-2',
    'col_text'=>'--text','col_textdim'=>'--text-dim',
];
$colorCss = '';
foreach ($colorMap as $skey => $cssvar) {
    $val = s_raw($skey, $DEFAULT_SETTINGS[$skey]['default'] ?? '');
    if (preg_match('/^#[0-9a-fA-F]{6}$/', $val)) $colorCss .= "$cssvar:$val;";
}
?>
<!DOCTYPE html>
<html lang="<?= e($L) ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= s('site_title','ChaiCore') ?></title>

  <link rel="icon" type="image/png" sizes="32x32" href="favicon-32.png" />
  <link rel="icon" type="image/png" sizes="256x256" href="favicon.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Raleway:wght@300;400;500;600;700&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Inter:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
  <link rel="stylesheet" href="styles.css" />

  <!-- динамические размеры шрифтов из админки -->
  <style>
    :root{
      --font-scale: <?= $fs ?>;
      --scale-hero: <?= $fsh ?>;
      --scale-section: <?= $fss ?>;
      --scale-mobile: <?= $fsm ?>;
      <?= $colorCss ?>
    }
    html{ font-size: calc(100% * var(--font-scale)); }
    .hero-title{ font-size: calc(clamp(3.2rem, 9vw, 6.5rem) * var(--scale-hero)); }
    .section-title{ font-size: calc(clamp(1.8rem, 4vw, 2.8rem) * var(--scale-section)); }
    @media (max-width: 580px){
      html{ font-size: calc(100% * var(--font-scale) * var(--scale-mobile)); }
<?php foreach ($mobileHidden as $sec): ?>
      #<?= $sec ?>, [data-sec="<?= $sec ?>"]{ display: none !important; }
<?php endforeach; ?>
    }
  </style>
</head>
<body>

<?php if (isset($_GET['sent'])): $ok = $_GET['sent'] === '1'; ?>
<div id="cc-toast" style="position:fixed;top:84px;left:50%;transform:translateX(-50%);z-index:2000;
  padding:14px 26px;border-radius:8px;font-family:'Raleway',sans-serif;font-size:.9rem;color:#fff;
  box-shadow:0 8px 30px rgba(0,0,0,.5);background:<?= $ok ? '#3a7d5b' : '#A6142F' ?>;">
  <?php if ($ok): ?>
    <?= $L==='ru' ? 'Спасибо! Заявка отправлена.' : ($L==='en' ? 'Thank you! Your request was sent.' : 'Təşəkkürlər! Müraciətiniz göndərildi.') ?>
  <?php else: ?>
    <?= $L==='ru' ? 'Не удалось отправить. Попробуйте позже или свяжитесь напрямую.' : ($L==='en' ? 'Could not send. Please try later or contact us directly.' : 'Göndərilmədi. Sonra yenidən cəhd edin.') ?>
  <?php endif; ?>
</div>
<script>setTimeout(()=>{var t=document.getElementById('cc-toast');if(t)t.style.display='none';},5000);</script>
<?php endif; ?>

<?php if (isset($_GET['review'])): ?>
<div id="cc-toast2" style="position:fixed;top:84px;left:50%;transform:translateX(-50%);z-index:2000;
  padding:14px 26px;border-radius:8px;font-family:'Raleway',sans-serif;font-size:.9rem;color:#fff;
  box-shadow:0 8px 30px rgba(0,0,0,.5);background:#3a7d5b;text-align:center;max-width:90%;">
  <?= t('review_thanks') ?>
</div>
<script>setTimeout(()=>{var t=document.getElementById('cc-toast2');if(t)t.style.display='none';},6000);</script>
<?php endif; ?>

<!-- ════════════════ NAVBAR ════════════════ -->
<nav id="navbar">
  <div class="nav-inner">
    <a href="#hero" class="nav-logo">
      <img src="<?= img('logo_nav') ?>" alt="<?= s('brand_name') ?>" class="nav-logo-img" />
      <div>
        <span class="nav-logo-sub active"><?= t('logo_sub') ?></span>
      </div>
    </a>

    <ul class="nav-links">
      <li data-sec="about"><a href="#about"><?= t('nav_about') ?></a></li>
      <li data-sec="philosophy"><a href="#philosophy"><?= t('nav_philosophy') ?></a></li>
      <li data-sec="services"><a href="#services"><?= t('nav_services') ?></a></li>
      <li class="nav-extra" data-sec="ceremony"><a href="#ceremony"><?= t('nav_ceremony') ?></a></li>
      <li class="nav-extra" data-sec="team"><a href="#team"><?= t('nav_team') ?></a></li>
      <li data-sec="gallery"><a href="#gallery"><?= t('nav_gallery') ?></a></li>
      <li class="nav-extra" data-sec="testimonials"><a href="#testimonials"><?= t('nav_testimonials') ?></a></li>
      <li data-sec="contact"><a href="#contact"><?= t('nav_contact') ?></a></li>
    </ul>

    <div class="nav-right">
      <div class="lang-switch">
        <button class="lang-btn <?= $L==='az'?'active':'' ?>" onclick="setLang('az')">AZ</button>
        <button class="lang-btn <?= $L==='ru'?'active':'' ?>" onclick="setLang('ru')">RU</button>
        <button class="lang-btn <?= $L==='en'?'active':'' ?>" onclick="setLang('en')">EN</button>
      </div>
      <div class="nav-toggle" onclick="toggleNav()"><span></span><span></span><span></span></div>
    </div>
  </div>
</nav>

<!-- ════════════════ HERO ════════════════ -->
<section id="hero">
  <div class="hero-bg-img">
    <img src="<?= img('hero_bg') ?>" alt="background" />
  </div>
  <div class="hero-overlay"></div>
  <div class="particles" id="particles"></div>

  <div class="hero-content">
    <div class="hero-logo-wrap">
      <img src="<?= img('hero_logo') ?>" alt="<?= s('brand_name') ?> Logo" />
    </div>
    <div>
      <p class="hero-eyebrow"><?= t('hero_eyebrow') ?></p>
      <h1 class="hero-title"><?= t('hero_title') ?></h1>
      <p class="hero-subtitle"><?= t('hero_subtitle') ?></p>
      <div class="ornament"><i class="fa-solid fa-leaf"></i></div>
      <p class="hero-desc"><?= t('hero_desc') ?></p>
      <div class="hero-actions">
        <a href="#ceremony" data-sec="ceremony" class="btn btn-crimson"><i class="fa-solid fa-mug-hot"></i> <?= t('hero_btn1') ?></a>
        <a href="#contact" data-sec="contact" class="btn btn-outline"><i class="fa-solid fa-calendar"></i> <?= t('hero_btn2') ?></a>
      </div>
    </div>
  </div>
  <div class="hero-scroll"><span><?= t('hero_scroll') ?></span><i class="fa-solid fa-chevron-down"></i></div>
</section>

<!-- CRIMSON BAND -->
<div class="crimson-band">
  <div class="band-inner">
    <p class="band-text"><?= t('band_text') ?></p>
    <a href="#contact" class="band-cta"><?= t('band_cta') ?></a>
  </div>
</div>

<!-- ════════════════ ABOUT ════════════════ -->
<section id="about">
  <div class="container">
    <div class="about-grid">

      <div class="about-imgs" data-aos="fade-right">
        <div class="about-img-main">
          <img src="<?= img('about_main') ?>" alt="<?= s('brand_name') ?>" />
        </div>
        <div class="about-img-accent">
          <img src="<?= img('about_accent') ?>" alt="<?= s('brand_name') ?>" />
        </div>
        <?php if (has_img('about_badge')): ?>
        <div class="about-badge">
          <img src="<?= img('about_badge') ?>" alt="Dragon" />
        </div>
        <?php endif; ?>
      </div>

      <div class="about-text" data-aos="fade-left">
        <span class="section-tag"><?= t('about_tag') ?></span>
        <h2 class="section-title"><?= t('about_title') ?></h2>
        <div class="ornament"><i class="fa-solid fa-leaf"></i></div>
        <p><?= t('about_p1') ?></p>
        <p><?= t('about_p2') ?></p>
        <p><?= t('about_p3') ?></p>
        <a href="#services" class="btn btn-orange"><i class="fa-solid fa-arrow-right"></i> <?= t('about_btn') ?></a>
        <div class="about-stats">
          <div class="stat-card"><div class="stat-num"><?= s('stat1_num') ?></div><div class="stat-label"><?= t('stat1_label') ?></div></div>
          <div class="stat-card"><div class="stat-num"><?= s('stat2_num') ?></div><div class="stat-label"><?= t('stat2_label') ?></div></div>
          <div class="stat-card"><div class="stat-num"><?= s('stat3_num') ?></div><div class="stat-label"><?= t('stat3_label') ?></div></div>
          <div class="stat-card"><div class="stat-num"><?= s('stat4_num') ?></div><div class="stat-label"><?= t('stat4_label') ?></div></div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ════════════════ PHILOSOPHY ════════════════ -->
<section id="philosophy">
  <div class="container">
    <div class="section-header centered" data-aos="fade-up">
      <span class="section-tag"><?= t('phil_tag') ?></span>
      <h2 class="section-title"><?= t('phil_title') ?></h2>
      <div class="ornament"><i class="fa-solid fa-yin-yang"></i></div>
      <p class="section-lead"><?= t('phil_lead') ?></p>
    </div>

    <div class="phil-grid">
      <?php
      $phil_icons = ['fa-scroll','fa-spa','fa-handshake','fa-seedling','fa-book-open','fa-globe-asia'];
      for ($n=1; $n<=6; $n++):
        $delay = [0,80,160,0,80,160][$n-1];
      ?>
      <div class="phil-card" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
        <div class="phil-icon"><i class="fa-solid <?= $phil_icons[$n-1] ?>"></i></div>
        <h3><?= t("phil{$n}_title") ?></h3>
        <p><?= t("phil{$n}_text") ?></p>
      </div>
      <?php endfor; ?>
    </div>
  </div>
</section>

<!-- ════════════════ SERVICES ════════════════ -->
<section id="services">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-tag"><?= t('svc_tag') ?></span>
      <h2 class="section-title"><?= t('svc_title') ?></h2>
      <div class="ornament"><i class="fa-solid fa-mug-hot"></i></div>
      <p class="section-lead"><?= t('svc_lead') ?></p>
    </div>

    <div class="services-grid">
      <?php
      $svc_icons = ['fa-fire-flame-curved','fa-wine-glass','fa-chalkboard-user','fa-car-side','fa-users','fa-store'];
      for ($n=1; $n<=6; $n++):
        $delay = [0,50,100,150,0,50][$n-1];
      ?>
      <div class="svc-card" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
        <div class="svc-icon"><i class="fa-solid <?= $svc_icons[$n-1] ?>"></i></div>
        <div class="svc-body">
          <h3><?= t("svc{$n}_title") ?></h3>
          <p><?= t("svc{$n}_text") ?></p>
        </div>
      </div>
      <?php endfor; ?>
    </div>
  </div>
</section>

<!-- ════════════════ CEREMONY ════════════════ -->
<section id="ceremony">
  <div class="container">
    <div class="ceremony-inner">

      <div data-aos="fade-right">
        <div class="ceremony-img">
          <img src="<?= img('ceremony_img') ?>" alt="Ceremony" />
          <div class="ceremony-img-badge">
            <i class="fa-solid fa-star"></i>
            <span><?= t('cer_badge') ?></span>
          </div>
        </div>
      </div>

      <div data-aos="fade-left">
        <div>
          <span class="section-tag"><?= t('cer_tag') ?></span>
          <h2 class="section-title"><?= t('cer_title') ?></h2>
          <div class="ornament"><i class="fa-solid fa-mug-hot"></i></div>
          <p class="section-lead"><?= t('cer_lead') ?></p>
        </div>
        <div class="ceremony-steps">
          <?php for ($n=1; $n<=4; $n++): ?>
          <div class="step">
            <div class="step-num">0<?= $n ?></div>
            <div class="step-body"><h4><?= t("step{$n}_title") ?></h4><p><?= t("step{$n}_text") ?></p></div>
          </div>
          <?php endfor; ?>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ════════════════ TEAM ════════════════ -->
<section id="team">
  <div class="container">
    <div class="section-header centered" data-aos="fade-up">
      <span class="section-tag"><?= t('team_tag') ?></span>
      <h2 class="section-title"><?= t('team_title') ?></h2>
      <div class="ornament"><i class="fa-solid fa-user-tie"></i></div>
    </div>

    <div class="team-grid">
      <?php foreach (team_members() as $idx => $m):
        $id = $m['id'];
        $delay = ($idx % 3) * 100;
      ?>
      <div class="team-card" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
        <div class="team-photo">
          <img src="<?= e($m['path']) ?>" alt="Tea Master" />
        </div>
        <div class="team-info">
          <?php if (t_raw("team_{$id}_name") !== ''): ?><h3 class="team-name"><?= t("team_{$id}_name") ?></h3><?php endif; ?>
          <div class="team-role"><?= t("team_{$id}_role") ?></div>
          <div class="team-divider"></div>
          <p class="team-desc"><?= t("team_{$id}_desc") ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════ GALLERY ════════════════ -->
<section id="gallery">
  <div class="container">
    <div class="section-header centered" data-aos="fade-up">
      <span class="section-tag"><?= t('gal_tag') ?></span>
      <h2 class="section-title"><?= t('gal_title') ?></h2>
      <div class="ornament"><i class="fa-solid fa-images"></i></div>
    </div>

    <div class="gallery-grid" data-aos="fade-up">
      <?php foreach (imgs('gallery') as $idx => $gp):
        $cls = $idx === 0 ? 'g-item wide' : 'g-item';
        $style = $idx === 0 ? ' style="grid-row:span 2;"' : '';
      ?>
      <div class="<?= $cls ?>"<?= $style ?>>
        <img src="<?= e($gp) ?>" alt="Gallery" />
        <div class="g-overlay"><i class="fa-solid fa-magnifying-glass-plus"></i></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════ TESTIMONIALS ════════════════ -->
<section id="testimonials">
  <div class="container">
    <div class="section-header centered" data-aos="fade-up">
      <span class="section-tag"><?= t('testi_tag') ?></span>
      <h2 class="section-title"><?= t('testi_title') ?></h2>
      <div class="ornament"><i class="fa-solid fa-star"></i></div>
      <p class="section-lead"><?= t('testi_lead') ?></p>
    </div>

    <?php $reviews = approved_reviews($L); ?>
    <?php if ($reviews): ?>
    <div class="testi-grid">
      <?php foreach ($reviews as $idx => $rv): $delay = ($idx % 3) * 100; $rating = max(1, min(5, (int)$rv['rating'])); ?>
      <div class="testi-card" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
        <div class="testi-stars"><?php for ($s=0; $s<$rating; $s++): ?><i class="fa-solid fa-star"></i><?php endfor; ?></div>
        <p class="testi-text"><?= e($rv['text']) ?></p>
        <div class="testi-author">
          <div class="testi-av"><i class="fa-solid fa-user"></i></div>
          <div>
            <?php if (trim((string)$rv['name']) !== ''): ?><div class="testi-name"><?= e($rv['name']) ?></div><?php endif; ?>
            <?php if (trim((string)$rv['location']) !== ''): ?><div class="testi-role"><?= e($rv['location']) ?></div><?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="review-cta-wrap" data-aos="fade-up">
      <button type="button" class="btn btn-outline" onclick="document.getElementById('review-form').classList.toggle('open')">
        <i class="fa-solid fa-pen"></i> <?= t('review_cta') ?>
      </button>
    </div>

    <form id="review-form" class="review-form" method="post" action="review.php">
      <input type="hidden" name="lang" value="<?= e($L) ?>">
      <h3><?= t('review_form_title') ?></h3>
      <div class="form-row">
        <div class="form-group">
          <label><?= t('review_f_name') ?></label>
          <input type="text" name="name" maxlength="120" placeholder="— — —" required>
        </div>
        <div class="form-group">
          <label><?= t('review_f_place') ?></label>
          <input type="text" name="location" maxlength="160" placeholder="— — —">
        </div>
      </div>
      <div class="form-group">
        <label><?= t('review_f_rating') ?></label>
        <select name="rating">
          <option value="5">★★★★★</option>
          <option value="4">★★★★</option>
          <option value="3">★★★</option>
          <option value="2">★★</option>
          <option value="1">★</option>
        </select>
      </div>
      <div class="form-group">
        <label><?= t('review_f_text') ?></label>
        <textarea name="text" maxlength="1500" placeholder="— — —" required></textarea>
      </div>
      <button type="submit" class="btn btn-crimson" style="justify-content:center">
        <span><?= t('review_submit') ?></span> <i class="fa-solid fa-paper-plane"></i>
      </button>
    </form>
  </div>
</section>

<!-- ════════════════ CUSTOM SECTIONS (конструктор) ════════════════ -->
<?php foreach (custom_sections() as $cs): $id = $cs['id'];
  $csTag = t("sec_{$id}_tag"); $csTitle = t("sec_{$id}_title"); $csBodyRaw = t_raw("sec_{$id}_body");
  if (trim($csTitle) === '' && trim($csBodyRaw) === '' && !has_img("section_{$id}")) continue;
?>
<section id="sec-<?= $id ?>" class="custom-section" style="background: var(<?= $cs['bg'] ? '--navy-2' : '--navy' ?>);">
  <div class="container">
    <?php if (trim($csTag) !== '' || trim($csTitle) !== ''): ?>
    <div class="section-header centered" data-aos="fade-up">
      <?php if (trim($csTag) !== ''): ?><span class="section-tag"><?= $csTag ?></span><?php endif; ?>
      <?php if (trim($csTitle) !== ''): ?><h2 class="section-title"><?= $csTitle ?></h2><?php endif; ?>
      <div class="ornament"><i class="fa-solid fa-leaf"></i></div>
    </div>
    <?php endif; ?>
    <?php if (has_img("section_{$id}")): ?>
    <div class="cs-image" data-aos="fade-up"><img src="<?= img("section_{$id}") ?>" alt=""></div>
    <?php endif; ?>
    <?php if (trim($csBodyRaw) !== ''): ?>
    <div class="cs-body" data-aos="fade-up"><?= nl2br(t("sec_{$id}_body")) ?></div>
    <?php endif; ?>
  </div>
</section>
<?php endforeach; ?>

<!-- ════════════════ CONTACT ════════════════ -->
<section id="contact">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-tag"><?= t('contact_tag') ?></span>
      <h2 class="section-title"><?= t('contact_title') ?></h2>
      <div class="ornament"><i class="fa-solid fa-envelope"></i></div>
    </div>

    <div class="contact-grid">
      <div data-aos="fade-right">
        <div class="contact-items">
          <div class="contact-item">
            <div class="ci-icon"><i class="fa-solid fa-location-dot"></i></div>
            <div>
              <div class="ci-label"><?= t('ci_address_label') ?></div>
              <div class="ci-val"><?= t('ci_address_val') ?></div>
            </div>
          </div>
          <div class="contact-item">
            <div class="ci-icon"><i class="fa-solid fa-phone"></i></div>
            <div>
              <div class="ci-label"><?= t('ci_phone_label') ?></div>
              <div class="ci-val"><?= s('contact_phone') ?></div>
            </div>
          </div>
          <div class="contact-item">
            <div class="ci-icon"><i class="fa-solid fa-envelope"></i></div>
            <div>
              <div class="ci-label">Email</div>
              <div class="ci-val"><?= s('contact_email') ?></div>
            </div>
          </div>
          <div class="contact-item">
            <div class="ci-icon"><i class="fa-brands fa-instagram"></i></div>
            <div>
              <div class="ci-label">Instagram</div>
              <div class="ci-val"><?= s('contact_instagram') ?></div>
            </div>
          </div>
        </div>
        <div class="social-row">
          <a href="<?= s('soc_instagram','#') ?>" class="soc-btn" target="_blank" rel="noopener"><i class="fa-brands fa-instagram"></i></a>
          <a href="<?= s('soc_facebook','#') ?>" class="soc-btn" target="_blank" rel="noopener"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="<?= s('soc_whatsapp','#') ?>" class="soc-btn" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i></a>
          <a href="<?= s('soc_telegram','#') ?>" class="soc-btn" target="_blank" rel="noopener"><i class="fa-brands fa-telegram"></i></a>
          <a href="<?= s('soc_youtube','#') ?>" class="soc-btn" target="_blank" rel="noopener"><i class="fa-brands fa-youtube"></i></a>
          <a href="<?= s('soc_tiktok','#') ?>" class="soc-btn" target="_blank" rel="noopener"><i class="fa-brands fa-tiktok"></i></a>
        </div>
        <?php $map = s_raw('map_embed'); ?>
        <?php if (trim($map) !== ''): ?>
        <div class="map-ph" style="padding:0;overflow:hidden;border-style:solid;"><?= $map ?></div>
        <?php else: ?>
        <div class="map-ph">
          <i class="fa-solid fa-map-location-dot"></i>
          <span><?= t('map_label') ?></span>
        </div>
        <?php endif; ?>
      </div>

      <div data-aos="fade-left">
        <form class="contact-form" method="post" action="send.php">
          <h3><?= t('form_title') ?></h3>
          <div class="form-row">
            <div class="form-group">
              <label><?= t('f_name') ?></label>
              <input type="text" name="name" placeholder="— — —" required />
            </div>
            <div class="form-group">
              <label><?= t('f_surname') ?></label>
              <input type="text" name="surname" placeholder="— — —" />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" placeholder="— — —" />
            </div>
            <div class="form-group">
              <label><?= t('f_phone') ?></label>
              <input type="tel" name="phone" placeholder="— — —" required />
            </div>
          </div>
          <div class="form-group">
            <label><?= t('f_service') ?></label>
            <select name="service">
              <option><?= t('opt_ceremony') ?></option>
              <option><?= t('opt_tasting') ?></option>
              <option><?= t('opt_masterclass') ?></option>
              <option><?= t('opt_corporate') ?></option>
            </select>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label><?= t('f_date') ?></label>
              <input type="date" name="date" />
            </div>
            <div class="form-group">
              <label><?= t('f_guests') ?></label>
              <input type="number" name="guests" min="1" placeholder="1" />
            </div>
          </div>
          <div class="form-group">
            <label><?= t('f_message') ?></label>
            <textarea name="message" placeholder="— — —"></textarea>
          </div>
          <button type="submit" class="btn btn-crimson" style="width:100%;justify-content:center;">
            <span><?= t('f_send') ?></span>
            <i class="fa-solid fa-paper-plane"></i>
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- ════════════════ FOOTER ════════════════ -->
<footer>
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="footer-logo">
          <img src="<?= img('logo_footer') ?>" alt="<?= s('brand_name') ?>" />
          <span class="nav-logo-text"><?= s('brand_name') ?></span>
        </div>
        <p><?= t('footer_about') ?></p>
      </div>
      <div class="footer-col">
        <h4><?= t('footer_nav_title') ?></h4>
        <ul>
          <li><a href="#about"><?= t('nav_about') ?></a></li>
          <li><a href="#philosophy"><?= t('nav_philosophy') ?></a></li>
          <li><a href="#services"><?= t('nav_services') ?></a></li>
          <li><a href="#gallery"><?= t('nav_gallery') ?></a></li>
          <li><a href="#contact"><?= t('nav_contact') ?></a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4><?= t('footer_svc_title') ?></h4>
        <ul>
          <li><a href="#services"><?= t('footer_svc1') ?></a></li>
          <li><a href="#services"><?= t('footer_svc2') ?></a></li>
          <li><a href="#services"><?= t('footer_svc3') ?></a></li>
          <li><a href="#services"><?= t('footer_svc4') ?></a></li>
        </ul>
      </div>
      <div class="footer-col footer-newsletter">
        <h4><?= t('footer_news_title') ?></h4>
        <p><?= t('footer_news_text') ?></p>
        <form class="footer-form" method="post" action="send.php">
          <input type="hidden" name="newsletter" value="1" />
          <input type="email" name="email" placeholder="Email" />
          <button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
        </form>
      </div>
    </div>
    <div class="footer-bottom">
      <span class="footer-copy">© <?= s('copyright_year') ?> <?= s('brand_name') ?>. <?= t('footer_copy') ?></span>
      <div class="social-row" style="margin:0">
        <a href="<?= s('soc_instagram','#') ?>" class="soc-btn" style="width:34px;height:34px" target="_blank" rel="noopener"><i class="fa-brands fa-instagram"></i></a>
        <a href="<?= s('soc_facebook','#') ?>" class="soc-btn" style="width:34px;height:34px" target="_blank" rel="noopener"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="<?= s('soc_telegram','#') ?>" class="soc-btn" style="width:34px;height:34px" target="_blank" rel="noopener"><i class="fa-brands fa-telegram"></i></a>
      </div>
    </div>
  </div>
</footer>

<!-- ════════════════ SCRIPTS ════════════════ -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
  // смена языка = перезагрузка с ?lang=xx (сохраняем текущий якорь прокрутки)
  function setLang(l){ location.href = '?lang=' + l + location.hash; }

  // Navbar scroll
  window.addEventListener('scroll', () => {
    document.getElementById('navbar').classList.toggle('scrolled', scrollY > 60);
  });

  // Mobile nav
  function toggleNav(){ document.body.classList.toggle('nav-mobile-open'); }
  document.querySelectorAll('.nav-links a').forEach(a =>
    a.addEventListener('click', () => document.body.classList.remove('nav-mobile-open'))
  );

  // AOS
  AOS.init({ duration: 700, once: true, offset: 70 });

  // Particles
  const pc = document.getElementById('particles');
  for (let i = 0; i < 24; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.cssText = `left:${Math.random()*100}%;animation-duration:${8+Math.random()*12}s;animation-delay:${Math.random()*10}s;width:${1+Math.random()*3}px;height:${1+Math.random()*3}px;opacity:${Math.random()*.5}`;
    pc.appendChild(p);
  }
</script>
</body>
</html>
