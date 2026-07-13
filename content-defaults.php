<?php
/**
 * content-defaults.php
 * ─────────────────────────────────────────────────────────────
 * ЕДИНЫЙ ИСТОЧНИК КОНТЕНТА.
 * Отсюда база наполняется значениями по умолчанию при первом запуске,
 * и по этому же файлу админка автоматически строит формы редактирования.
 *
 * TEXTS   — тексты на 3 языках (az/ru/en). Редактируются в «Тексты».
 * IMAGES  — слоты для фото/логотипов. Редактируются в «Фото».
 * SETTINGS— одиночные значения (телефон, соцсети, размеры шрифтов…).
 *
 * Ключи менять НЕ нужно — их использует шаблон index.php.
 * Значения ('az'/'ru'/'en'/'default') — это лишь стартовые данные;
 * после установки всё правится через админку.
 */

/* ═══════════════════ ТЕКСТЫ (3 языка) ═══════════════════
   Формат: 'ключ' => ['g'=>группа, 'l'=>подпись, 't'=>'text'|'area', az, ru, en]  */
$DEFAULT_TEXTS = [

  /* ── Навигация / общее ── */
  'logo_sub'      => ['g'=>'Шапка','l'=>'Подпись под лого','t'=>'text','az'=>'Çay Mədəniyyəti','ru'=>'Чайная Культура','en'=>'Tea Culture'],
  'nav_about'     => ['g'=>'Меню','l'=>'Пункт: О нас','t'=>'text','az'=>'Haqqımızda','ru'=>'О нас','en'=>'About'],
  'nav_philosophy'=> ['g'=>'Меню','l'=>'Пункт: Философия','t'=>'text','az'=>'Fəlsəfə','ru'=>'Философия','en'=>'Philosophy'],
  'nav_services'  => ['g'=>'Меню','l'=>'Пункт: Услуги','t'=>'text','az'=>'Xidmətlər','ru'=>'Услуги','en'=>'Services'],
  'nav_gallery'   => ['g'=>'Меню','l'=>'Пункт: Галерея','t'=>'text','az'=>'Qalereya','ru'=>'Галерея','en'=>'Gallery'],
  'nav_contact'   => ['g'=>'Меню','l'=>'Пункт: Контакты','t'=>'text','az'=>'Əlaqə','ru'=>'Контакты','en'=>'Contact'],

  /* ── Hero ── */
  'hero_eyebrow'  => ['g'=>'Главный экран','l'=>'Надзаголовок','t'=>'text','az'=>'Azərbaycan Çay Mədəniyyəti','ru'=>'Азербайджанская Чайная Культура','en'=>'Azerbaijani Tea Culture'],
  'hero_title'    => ['g'=>'Главный экран','l'=>'Заголовок (лого-текст)','t'=>'text','az'=>'ÇAYCORE','ru'=>'ЧАЙCORE','en'=>'CHAICORE'],
  'hero_subtitle' => ['g'=>'Главный экран','l'=>'Подзаголовок','t'=>'text','az'=>'Ənənə · Fəlsəfə · Ritual','ru'=>'Традиция · Философия · Ритуал','en'=>'Tradition · Philosophy · Ritual'],
  'hero_desc'     => ['g'=>'Главный экран','l'=>'Описание','t'=>'area','az'=>'Şərq çay ənənələri ilə Azərbaycan qonaqpərvərliyinin unikal sintezi — çayın ətrafında doğan mədəniyyət, tarix və hekayə.','ru'=>'Уникальный синтез восточных чайных традиций и азербайджанского гостеприимства — культура, история и истории, рождённые вокруг чая.','en'=>'A unique synthesis of Eastern tea traditions and Azerbaijani hospitality — culture, history and stories born around a cup of tea.'],
  'hero_btn1'     => ['g'=>'Главный экран','l'=>'Кнопка 1','t'=>'text','az'=>'Mərasimi Kəşf Et','ru'=>'Открыть Церемонию','en'=>'Discover Ceremony'],
  'hero_btn2'     => ['g'=>'Главный экран','l'=>'Кнопка 2','t'=>'text','az'=>'Rezervasiya','ru'=>'Забронировать','en'=>'Book a Session'],
  'hero_scroll'   => ['g'=>'Главный экран','l'=>'Надпись «прокрутить»','t'=>'text','az'=>'Scroll','ru'=>'Вниз','en'=>'Scroll'],

  /* ── Красная полоса ── */
  'band_text'     => ['g'=>'Красная полоса','l'=>'Текст','t'=>'text','az'=>'Çay mərasimləri, dequstasiyalar, master-klaslar və korporativ tədbirlər','ru'=>'Чайные церемонии, дегустации, мастер-классы и корпоративные мероприятия','en'=>'Tea ceremonies, tastings, master classes and corporate events'],
  'band_cta'      => ['g'=>'Красная полоса','l'=>'Ссылка','t'=>'text','az'=>'Rezervasiya et →','ru'=>'Забронировать →','en'=>'Book Now →'],

  /* ── О нас ── */
  'about_tag'     => ['g'=>'О нас','l'=>'Метка секции','t'=>'text','az'=>'Bizim Haqqımızda','ru'=>'О Нас','en'=>'About Us'],
  'about_title'   => ['g'=>'О нас','l'=>'Заголовок','t'=>'text','az'=>'Çayın Arxasındakı Hekayə','ru'=>'История За Чашкой Чая','en'=>'The Story Behind the Cup'],
  'about_p1'      => ['g'=>'О нас','l'=>'Абзац 1','t'=>'area','az'=>'ChaiCore — Azərbaycanın çay içmə ənənələrinə əsaslanan, Çin çay mədəniyyətinin fəlsəfəsi və estetikası ilə ilhamlanan yeni nəsil mədəni layihəsidir.','ru'=>'ChaiCore — культурный проект нового поколения, основанный на традициях азербайджанского чаепития и вдохновлённый философией и эстетикой китайской чайной культуры.','en'=>'ChaiCore is a next-generation cultural project rooted in Azerbaijani tea-drinking traditions, inspired by the philosophy and aesthetics of Chinese tea culture.'],
  'about_p2'      => ['g'=>'О нас','l'=>'Абзац 2','t'=>'area','az'=>'Layihə Şərq çay ənənələri ilə Azərbaycan qonaqpərvərliyinin unikal sintezini təqdim edərək, qonaqlara Azərbaycanın çay tarixini, mədəniyyətini və ritualını fərqli bir təcrübə ilə tanıdır.','ru'=>'Проект представляет уникальный синтез восточных чайных традиций и азербайджанского гостеприимства, знакомя гостей с историей, культурой и ритуалом азербайджанского чая.','en'=>'The project presents a unique synthesis of Eastern tea traditions and Azerbaijani hospitality, immersing guests in the history, culture and rituals of Azerbaijani tea.'],
  'about_p3'      => ['g'=>'О нас','l'=>'Абзац 3','t'=>'area','az'=>'Hər bir mərasim çayın tarixini, arxiv faktlarını, dəmləmə xüsusiyyətlərini, çaynik və armudu stəkanın mənasını əhatə edən maraqlı hekayələrlə müşayiət olunur.','ru'=>'Каждая церемония сопровождается интересными историями, охватывающими историю чая, архивные факты, особенности заваривания и значение армуды-стакана.','en'=>'Each ceremony is accompanied by captivating narratives covering the history of tea, archival facts, brewing techniques and the symbolism of the armudu glass.'],
  'about_btn'     => ['g'=>'О нас','l'=>'Кнопка','t'=>'text','az'=>'Xidmətlər','ru'=>'Услуги','en'=>'Our Services'],
  'stat1_label'   => ['g'=>'О нас · Цифры','l'=>'Подпись цифры 1','t'=>'text','az'=>'Keçirilmiş Mərasim','ru'=>'Церемоний Проведено','en'=>'Ceremonies Held'],
  'stat2_label'   => ['g'=>'О нас · Цифры','l'=>'Подпись цифры 2','t'=>'text','az'=>'Çay Növü','ru'=>'Видов Чая','en'=>'Tea Varieties'],
  'stat3_label'   => ['g'=>'О нас · Цифры','l'=>'Подпись цифры 3','t'=>'text','az'=>'Tərəfdaş Otel','ru'=>'Отелей-Партнёров','en'=>'Partner Hotels'],
  'stat4_label'   => ['g'=>'О нас · Цифры','l'=>'Подпись цифры 4','t'=>'text','az'=>'İl Təcrübə','ru'=>'Года Опыта','en'=>'Years Experience'],

  /* ── Философия ── */
  'phil_tag'      => ['g'=>'Философия','l'=>'Метка секции','t'=>'text','az'=>'Dəyərlərimiz','ru'=>'Наши Ценности','en'=>'Our Values'],
  'phil_title'    => ['g'=>'Философия','l'=>'Заголовок','t'=>'text','az'=>'Çay Fəlsəfəmiz','ru'=>'Наша Чайная Философия','en'=>'Our Tea Philosophy'],
  'phil_lead'     => ['g'=>'Философия','l'=>'Подзаголовок','t'=>'area','az'=>'Çay yalnız içki deyil — o, mədəniyyətin, fəlsəfənin və ünsiyyətin ayrılmaz hissəsidir.','ru'=>'Чай — не просто напиток, он является неотъемлемой частью культуры, философии и общения.','en'=>'Tea is not merely a beverage — it is an inseparable part of culture, philosophy and communication.'],
  'phil1_title'   => ['g'=>'Философия · Карточки','l'=>'Карточка 1 — заголовок','t'=>'text','az'=>'Tarix','ru'=>'История','en'=>'History'],
  'phil1_text'    => ['g'=>'Философия · Карточки','l'=>'Карточка 1 — текст','t'=>'area','az'=>'Çayın Azərbaycana gəlişindən bugünə qədər əsrlik yolunu kəşf edirik, qədim mənbələri gündəmə gətiririk.','ru'=>'Исследуем вековой путь чая в Азербайджане, поднимая древние источники и архивные факты.','en'=>'We explore the centuries-long journey of tea in Azerbaijan, surfacing ancient sources and archival facts.'],
  'phil2_title'   => ['g'=>'Философия · Карточки','l'=>'Карточка 2 — заголовок','t'=>'text','az'=>'Ritual','ru'=>'Ритуал','en'=>'Ritual'],
  'phil2_text'    => ['g'=>'Философия · Карточки','l'=>'Карточка 2 — текст','t'=>'area','az'=>'Hər dəmləmə bir ritualdır — düzgün istilik, vaxt, qab seçimi hamısı mükəmməl fincanı yaradır.','ru'=>'Каждое заваривание — ритуал: правильная температура, время и посуда создают идеальную чашку.','en'=>'Every brew is a ritual — the right temperature, time and vessel combine to create the perfect cup.'],
  'phil3_title'   => ['g'=>'Философия · Карточки','l'=>'Карточка 3 — заголовок','t'=>'text','az'=>'Qonaqpərvərlik','ru'=>'Гостеприимство','en'=>'Hospitality'],
  'phil3_text'    => ['g'=>'Философия · Карточки','l'=>'Карточка 3 — текст','t'=>'area','az'=>'Azərbaycan qonaqpərvərliyi çay süfrəsindən başlayır. Hər qonağa layiq olduğu diqqəti göstəririk.','ru'=>'Азербайджанское гостеприимство начинается с чайного стола. Каждому гостю — заслуженное внимание.','en'=>'Azerbaijani hospitality begins at the tea table. Every guest receives the attention they deserve.'],
  'phil4_title'   => ['g'=>'Философия · Карточки','l'=>'Карточка 4 — заголовок','t'=>'text','az'=>'Təbiət','ru'=>'Природа','en'=>'Nature'],
  'phil4_text'    => ['g'=>'Философия · Карточки','l'=>'Карточка 4 — текст','t'=>'area','az'=>'Azərbaycan torpağında yetişən çay yarpaqları xüsusi bir dad daşıyır. Yerli mənşəyi önə çəkirik.','ru'=>'Листья чая, выращенные на азербайджанской земле, несут особый вкус. Продвигаем местное происхождение.','en'=>'Tea leaves grown on Azerbaijani soil carry a distinct flavour. We champion local provenance.'],
  'phil5_title'   => ['g'=>'Философия · Карточки','l'=>'Карточка 5 — заголовок','t'=>'text','az'=>'Maarifləndirmə','ru'=>'Просвещение','en'=>'Education'],
  'phil5_text'    => ['g'=>'Философия · Карточки','l'=>'Карточка 5 — текст','t'=>'area','az'=>'Mədəni-maarifləndirici tədbirlər vasitəsilə çay haqqında bilgiləri geniş auditoriyaya çatdırırıq.','ru'=>'Через культурно-просветительские мероприятия распространяем знания о чае широкой аудитории.','en'=>'Through cultural events, we spread knowledge about tea to a wide audience.'],
  'phil6_title'   => ['g'=>'Философия · Карточки','l'=>'Карточка 6 — заголовок','t'=>'text','az'=>'Mədəni Körpü','ru'=>'Культурный Мост','en'=>'Cultural Bridge'],
  'phil6_text'    => ['g'=>'Философия · Карточки','l'=>'Карточка 6 — текст','t'=>'area','az'=>'Azərbaycanda Çinlə qədim çay əlaqələrini nümayiş etdirərək Şərqlə körpü qururuq.','ru'=>'Демонстрируя древние чайные связи Азербайджана с Китаем, строим мост с Востоком.','en'=>'By showcasing Azerbaijan\'s ancient tea links with China, we build a bridge to the East.'],

  /* ── Услуги ── */
  'svc_tag'       => ['g'=>'Услуги','l'=>'Метка секции','t'=>'text','az'=>'Nə Təklif Edirik','ru'=>'Что Мы Предлагаем','en'=>'What We Offer'],
  'svc_title'     => ['g'=>'Услуги','l'=>'Заголовок','t'=>'text','az'=>'Xidmətlərimiz','ru'=>'Наши Услуги','en'=>'Our Services'],
  'svc_lead'      => ['g'=>'Услуги','l'=>'Подзаголовок','t'=>'area','az'=>'Özəl tədbirlərdən korporativ proqramlara qədər — hər format üçün mükəmməl çay təcrübəsi.','ru'=>'От частных мероприятий до корпоративных программ — идеальный чайный опыт для любого формата.','en'=>'From intimate private events to large corporate programmes — the perfect tea experience for every format.'],
  'svc1_title'    => ['g'=>'Услуги · Карточки','l'=>'Услуга 1 — заголовок','t'=>'text','az'=>'Çay Mərasimləri','ru'=>'Чайные Церемонии','en'=>'Tea Ceremonies'],
  'svc1_text'     => ['g'=>'Услуги · Карточки','l'=>'Услуга 1 — текст','t'=>'area','az'=>'Azərbaycan çay ritualını əhatə edən tam immersiv mərasimlər. Tarix, fəlsəfə və zövq bir arada.','ru'=>'Полностью иммерсивные церемонии, охватывающие азербайджанский чайный ритуал.','en'=>'Fully immersive ceremonies encompassing the Azerbaijani tea ritual. History, philosophy and flavour in one.'],
  'svc2_title'    => ['g'=>'Услуги · Карточки','l'=>'Услуга 2 — заголовок','t'=>'text','az'=>'Çay Dequstasiyaları','ru'=>'Чайные Дегустации','en'=>'Tea Tastings'],
  'svc2_text'     => ['g'=>'Услуги · Карточки','l'=>'Услуга 2 — текст','t'=>'area','az'=>'Müxtəlif Azərbaycan çay sortlarını müqayisəli şəkildə dadmaq imkanı. Hər yudumda fərq hiss edilir.','ru'=>'Сравнительная дегустация различных сортов азербайджанского чая.','en'=>'Comparative tasting of various Azerbaijani tea varieties. The difference is felt in every sip.'],
  'svc3_title'    => ['g'=>'Услуги · Карточки','l'=>'Услуга 3 — заголовок','t'=>'text','az'=>'Master-Klaslar','ru'=>'Мастер-Классы','en'=>'Master Classes'],
  'svc3_text'     => ['g'=>'Услуги · Карточки','l'=>'Услуга 3 — текст','t'=>'area','az'=>'Çayın düzgün dəmlənməsi, qab seçimi, su temperaturu üzrə praktiki dərslər.','ru'=>'Практические занятия по правильному завариванию чая, выбору посуды и температуре воды.','en'=>'Practical sessions on correct brewing technique, vessel selection and water temperature.'],
  'svc4_title'    => ['g'=>'Услуги · Карточки','l'=>'Услуга 4 — заголовок','t'=>'text','az'=>'Səyyar Mərasimlər','ru'=>'Выездные Церемонии','en'=>'Mobile Ceremonies'],
  'svc4_text'     => ['g'=>'Услуги · Карточки','l'=>'Услуга 4 — текст','t'=>'area','az'=>'Otel, korporativ və özəl tədbirlər üçün sizin lokasiyanıza gəlirik. Bütün avadanlıqlar bizdədir.','ru'=>'Приезжаем на ваше место. Всё оборудование с нами.','en'=>'We come to your location for hotel, corporate and private events. All equipment provided.'],
  'svc5_title'    => ['g'=>'Услуги · Карточки','l'=>'Услуга 5 — заголовок','t'=>'text','az'=>'Turist Proqramları','ru'=>'Туристические Программы','en'=>'Tourist Programmes'],
  'svc5_text'     => ['g'=>'Услуги · Карточки','l'=>'Услуга 5 — текст','t'=>'area','az'=>'Xarici turistlər üçün xüsusi tur paketləri. Azərbaycan çay tarixini üç dildə təqdim edirik.','ru'=>'Специальные туристические пакеты. Представляем историю чая на трёх языках.','en'=>'Special tour packages for international visitors in three languages.'],
  'svc6_title'    => ['g'=>'Услуги · Карточки','l'=>'Услуга 6 — заголовок','t'=>'text','az'=>'Çay Məhsulları','ru'=>'Чайная Продукция','en'=>'Tea Products'],
  'svc6_text'     => ['g'=>'Услуги · Карточки','l'=>'Услуга 6 — текст','t'=>'area','az'=>'Seçilmiş çaylar, çay dəstləri, armudu stəkanlar və tematik hədiyyə paketlərinin satışı.','ru'=>'Продажа отборных чаёв, наборов, армуды-стаканов и подарочных наборов.','en'=>'Sale of curated teas, tea sets, armudu glasses and themed gift packages.'],

  /* ── Церемония ── */
  'cer_tag'       => ['g'=>'Церемония','l'=>'Метка секции','t'=>'text','az'=>'Mərasim Axışı','ru'=>'Ход Церемонии','en'=>'Ceremony Flow'],
  'cer_title'     => ['g'=>'Церемония','l'=>'Заголовок','t'=>'text','az'=>'Çay Mərasimi Necə Keçir?','ru'=>'Как Проходит Чайная Церемония?','en'=>'How Does the Tea Ceremony Work?'],
  'cer_lead'      => ['g'=>'Церемония','l'=>'Подзаголовок','t'=>'area','az'=>'Hər seans 4 hissədən ibarətdir — tarixi hekayə, dəmləmə rituali, dequstasiya və söhbət.','ru'=>'Каждая сессия состоит из 4 частей — повествование, ритуал заваривания, дегустация и беседа.','en'=>'Each session consists of 4 parts — historical storytelling, brewing ritual, tasting and conversation.'],
  'cer_badge'     => ['g'=>'Церемония','l'=>'Плашка на фото','t'=>'text','az'=>'Tam İmmersiv Təcrübə','ru'=>'Полное Погружение','en'=>'Full Immersion Experience'],
  'step1_title'   => ['g'=>'Церемония · Шаги','l'=>'Шаг 1 — заголовок','t'=>'text','az'=>'Tarixi Giriş','ru'=>'Историческое Вступление','en'=>'Historical Introduction'],
  'step1_text'    => ['g'=>'Церемония · Шаги','l'=>'Шаг 1 — текст','t'=>'area','az'=>'Azərbaycanda çayın tarixi, Çinlə əlaqəsi, armudu stəkanın doğulması haqqında canlı hekayə.','ru'=>'Живой рассказ об истории чая в Азербайджане, его связи с Китаем и рождении армуды.','en'=>'A live narrative about the history of tea in Azerbaijan, its connection to China and the birth of the armudu glass.'],
  'step2_title'   => ['g'=>'Церемония · Шаги','l'=>'Шаг 2 — заголовок','t'=>'text','az'=>'Dəmləmə Rituali','ru'=>'Ритуал Заваривания','en'=>'Brewing Ritual'],
  'step2_text'    => ['g'=>'Церемония · Шаги','l'=>'Шаг 2 — текст','t'=>'area','az'=>'Düzgün su temperaturu, çaynik istiləndirmə, dəmləmə müddəti — hər addımı birlikdə edirik.','ru'=>'Правильная температура, прогрев чайника, время заваривания — каждый шаг вместе.','en'=>'Correct water temperature, warming the teapot, steeping time — every step done together.'],
  'step3_title'   => ['g'=>'Церемония · Шаги','l'=>'Шаг 3 — заголовок','t'=>'text','az'=>'Dequstasiya','ru'=>'Дегустация','en'=>'Tasting'],
  'step3_text'    => ['g'=>'Церемония · Шаги','l'=>'Шаг 3 — текст','t'=>'area','az'=>'Müxtəlif çay sortlarını dadır, armudu stəkandan içmənin sirrini öyrənirik.','ru'=>'Пробуем разные сорта чая, узнаём секрет питья из армуды-стакана.','en'=>'Sampling various tea varieties, learning the secret of drinking from an armudu glass.'],
  'step4_title'   => ['g'=>'Церемония · Шаги','l'=>'Шаг 4 — заголовок','t'=>'text','az'=>'Söhbət & Suallar','ru'=>'Беседа & Вопросы','en'=>'Conversation & Q&A'],
  'step4_text'    => ['g'=>'Церемония · Шаги','l'=>'Шаг 4 — текст','t'=>'area','az'=>'Qonaqlarla ünsiyyət, suallar, tövsiyələr və hədiyyə olaraq çay seçimi.','ru'=>'Общение с гостями, рекомендации и выбор чая в подарок.','en'=>'Engaging with guests, recommendations and selecting a tea as a gift.'],

  /* ── Команда ── */
  'team_tag'      => ['g'=>'Команда','l'=>'Метка секции','t'=>'text','az'=>'Komandamız','ru'=>'Наша Команда','en'=>'Our Team'],
  'team_title'    => ['g'=>'Команда','l'=>'Заголовок','t'=>'text','az'=>'Çay Ustalarımız','ru'=>'Наши Чайные Мастера','en'=>'Our Tea Masters'],
  /* Участники команды (имя/роль/описание) теперь динамические — управляются
     в админке на странице «Команда» (admin/team.php). Ключи вида team_{id}_name. */

  /* ── Галерея ── */
  'gal_tag'       => ['g'=>'Галерея','l'=>'Метка секции','t'=>'text','az'=>'Vizual','ru'=>'Визуальное','en'=>'Visual'],
  'gal_title'     => ['g'=>'Галерея','l'=>'Заголовок','t'=>'text','az'=>'Qalereya','ru'=>'Галерея','en'=>'Gallery'],

  /* ── Отзывы ── */
  'testi_tag'     => ['g'=>'Отзывы','l'=>'Метка секции','t'=>'text','az'=>'Rəylər','ru'=>'Отзывы','en'=>'Reviews'],
  'testi_title'   => ['g'=>'Отзывы','l'=>'Заголовок','t'=>'text','az'=>'Qonaqlarımız Nə Deyir','ru'=>'Что Говорят Наши Гости','en'=>'What Our Guests Say'],
  /* Сами отзывы теперь динамические (таблица cc_reviews, модерация в админке).
     Ниже — тексты формы «оставить отзыв» на сайте. */
  'testi_lead'    => ['g'=>'Отзывы','l'=>'Подзаголовок секции','t'=>'area','az'=>'Qonaqlarımızın təəssüratları — siz də öz rəyinizi buraxa bilərsiniz.','ru'=>'Впечатления наших гостей — вы тоже можете оставить свой отзыв.','en'=>'What our guests say — and you can leave your own review too.'],
  'review_cta'    => ['g'=>'Отзывы · Форма','l'=>'Кнопка «оставить отзыв»','t'=>'text','az'=>'Rəy yaz','ru'=>'Оставить отзыв','en'=>'Leave a review'],
  'review_form_title'=>['g'=>'Отзывы · Форма','l'=>'Заголовок формы','t'=>'text','az'=>'Rəyiniz','ru'=>'Ваш отзыв','en'=>'Your review'],
  'review_f_name' => ['g'=>'Отзывы · Форма','l'=>'Поле: Имя','t'=>'text','az'=>'Adınız','ru'=>'Ваше имя','en'=>'Your name'],
  'review_f_place'=> ['g'=>'Отзывы · Форма','l'=>'Поле: Город/компания','t'=>'text','az'=>'Şəhər / şirkət (istəyə görə)','ru'=>'Город / компания (необязательно)','en'=>'City / company (optional)'],
  'review_f_text' => ['g'=>'Отзывы · Форма','l'=>'Поле: Текст отзыва','t'=>'text','az'=>'Rəyiniz','ru'=>'Ваш отзыв','en'=>'Your review'],
  'review_f_rating'=>['g'=>'Отзывы · Форма','l'=>'Поле: Оценка','t'=>'text','az'=>'Qiymət','ru'=>'Оценка','en'=>'Rating'],
  'review_submit' => ['g'=>'Отзывы · Форма','l'=>'Кнопка отправки','t'=>'text','az'=>'Göndər','ru'=>'Отправить','en'=>'Send'],
  'review_thanks' => ['g'=>'Отзывы · Форма','l'=>'Сообщение после отправки','t'=>'text','az'=>'Təşəkkürlər! Rəyiniz yoxlanışdan sonra görünəcək.','ru'=>'Спасибо! Отзыв появится после проверки.','en'=>'Thank you! Your review will appear after approval.'],

  /* ── Контакты ── */
  'contact_tag'   => ['g'=>'Контакты','l'=>'Метка секции','t'=>'text','az'=>'Bizimlə Əlaqə','ru'=>'Свяжитесь С Нами','en'=>'Get In Touch'],
  'contact_title' => ['g'=>'Контакты','l'=>'Заголовок','t'=>'text','az'=>'Rezervasiya & Əlaqə','ru'=>'Бронирование & Контакты','en'=>'Booking & Contact'],
  'ci_address_label'=>['g'=>'Контакты','l'=>'Подпись «Адрес»','t'=>'text','az'=>'Ünvan','ru'=>'Адрес','en'=>'Address'],
  'ci_address_val'=> ['g'=>'Контакты','l'=>'Адрес (значение)','t'=>'text','az'=>'Bakı, Azərbaycan','ru'=>'Баку, Азербайджан','en'=>'Baku, Azerbaijan'],
  'ci_phone_label'=> ['g'=>'Контакты','l'=>'Подпись «Телефон»','t'=>'text','az'=>'Telefon','ru'=>'Телефон','en'=>'Phone'],
  'form_title'    => ['g'=>'Контакты · Форма','l'=>'Заголовок формы','t'=>'text','az'=>'Rezervasiya Formu','ru'=>'Форма Бронирования','en'=>'Booking Form'],
  'f_name'        => ['g'=>'Контакты · Форма','l'=>'Поле: Имя','t'=>'text','az'=>'Ad','ru'=>'Имя','en'=>'Name'],
  'f_surname'     => ['g'=>'Контакты · Форма','l'=>'Поле: Фамилия','t'=>'text','az'=>'Soyad','ru'=>'Фамилия','en'=>'Surname'],
  'f_phone'       => ['g'=>'Контакты · Форма','l'=>'Поле: Телефон','t'=>'text','az'=>'Telefon','ru'=>'Телефон','en'=>'Phone'],
  'f_service'     => ['g'=>'Контакты · Форма','l'=>'Поле: Тип услуги','t'=>'text','az'=>'Xidmət Növü','ru'=>'Тип Услуги','en'=>'Service Type'],
  'f_date'        => ['g'=>'Контакты · Форма','l'=>'Поле: Дата','t'=>'text','az'=>'Tarix','ru'=>'Дата','en'=>'Date'],
  'f_guests'      => ['g'=>'Контакты · Форма','l'=>'Поле: Кол-во гостей','t'=>'text','az'=>'Qonaq Sayı','ru'=>'Кол-во Гостей','en'=>'Guests'],
  'f_message'     => ['g'=>'Контакты · Форма','l'=>'Поле: Сообщение','t'=>'text','az'=>'Mesaj','ru'=>'Сообщение','en'=>'Message'],
  'f_send'        => ['g'=>'Контакты · Форма','l'=>'Кнопка отправки','t'=>'text','az'=>'Göndər','ru'=>'Отправить','en'=>'Send'],
  'opt_ceremony'  => ['g'=>'Контакты · Форма','l'=>'Опция: Церемония','t'=>'text','az'=>'Çay Mərasimi','ru'=>'Чайная Церемония','en'=>'Tea Ceremony'],
  'opt_tasting'   => ['g'=>'Контакты · Форма','l'=>'Опция: Дегустация','t'=>'text','az'=>'Dequstasiya','ru'=>'Дегустация','en'=>'Tasting'],
  'opt_masterclass'=>['g'=>'Контакты · Форма','l'=>'Опция: Мастер-класс','t'=>'text','az'=>'Master-Klas','ru'=>'Мастер-Класс','en'=>'Master Class'],
  'opt_corporate' => ['g'=>'Контакты · Форма','l'=>'Опция: Корпоратив','t'=>'text','az'=>'Korporativ Tədbir','ru'=>'Корпоративное Мероприятие','en'=>'Corporate Event'],
  'map_label'     => ['g'=>'Контакты','l'=>'Подпись под картой (если карта не задана)','t'=>'text','az'=>'Xəritə','ru'=>'Карта','en'=>'Map'],

  /* ── Футер ── */
  'footer_about'  => ['g'=>'Футер','l'=>'Описание бренда','t'=>'area','az'=>'Azərbaycan çay mədəniyyətini qorumaq, inkişaf etdirmək və müasir formada təqdim etmək — missiyamız budur.','ru'=>'Сохранять, развивать и представлять азербайджанскую чайную культуру в современной форме — наша миссия.','en'=>'Preserving, developing and presenting Azerbaijani tea culture in a modern form — this is our mission.'],
  'footer_nav_title'  =>['g'=>'Футер','l'=>'Заголовок «Навигация»','t'=>'text','az'=>'Keçidlər','ru'=>'Навигация','en'=>'Navigation'],
  'footer_svc_title'  =>['g'=>'Футер','l'=>'Заголовок «Услуги»','t'=>'text','az'=>'Xidmətlər','ru'=>'Услуги','en'=>'Services'],
  'footer_svc1'   => ['g'=>'Футер','l'=>'Услуга-ссылка 1','t'=>'text','az'=>'Çay Mərasimi','ru'=>'Чайная Церемония','en'=>'Tea Ceremony'],
  'footer_svc2'   => ['g'=>'Футер','l'=>'Услуга-ссылка 2','t'=>'text','az'=>'Dequstasiya','ru'=>'Дегустация','en'=>'Tasting'],
  'footer_svc3'   => ['g'=>'Футер','l'=>'Услуга-ссылка 3','t'=>'text','az'=>'Master-Klas','ru'=>'Мастер-Класс','en'=>'Master Class'],
  'footer_svc4'   => ['g'=>'Футер','l'=>'Услуга-ссылка 4','t'=>'text','az'=>'Korporativ','ru'=>'Корпоратив','en'=>'Corporate'],
  'footer_news_title' =>['g'=>'Футер','l'=>'Заголовок «Новости»','t'=>'text','az'=>'Xəbərlər','ru'=>'Новости','en'=>'Newsletter'],
  'footer_news_text'  =>['g'=>'Футер','l'=>'Текст новостей','t'=>'area','az'=>'Yeni mərasimlər haqqında ilk siz xəbərdar olun.','ru'=>'Первыми узнавайте о новых церемониях и событиях.','en'=>'Be the first to know about new ceremonies and events.'],
  'footer_copy'   => ['g'=>'Футер','l'=>'Копирайт (после года)','t'=>'text','az'=>'Bütün hüquqlar qorunur.','ru'=>'Все права защищены.','en'=>'All rights reserved.'],
];

/* ═══════════════════ ФОТО / ЛОГОТИПЫ ═══════════════════
   Формат: 'ключ' => ['g'=>группа,'l'=>подпись,'multi'=>bool,'default'=>путь|[пути]]  */
$DEFAULT_IMAGES = [
  'logo_nav'     => ['g'=>'Логотипы','l'=>'Логотип в шапке','multi'=>false,'default'=>'assets/photo_2026-04-11 20.21.39 1.png'],
  'logo_footer'  => ['g'=>'Логотипы','l'=>'Логотип в футере','multi'=>false,'default'=>'assets/photo_2026-04-11 20.21.39 1.png'],
  'hero_logo'    => ['g'=>'Главный экран','l'=>'Большое лого (hero)','multi'=>false,'default'=>'assets/Group 13-1.png'],
  'hero_bg'      => ['g'=>'Главный экран','l'=>'Фон hero','multi'=>false,'default'=>'assets/image 2.png'],
  'about_main'   => ['g'=>'О нас','l'=>'Фото — основное','multi'=>false,'default'=>'assets/freepik__design-a-minimalistic-symbolic-culturally-rich-log__93620 1.png'],
  'about_accent' => ['g'=>'О нас','l'=>'Фото — акцент','multi'=>false,'default'=>'assets/freepik__design-a-symbolic-culturally-rich-logo-for-a-tea-b__93599 1.png'],
  'about_badge'  => ['g'=>'О нас','l'=>'Значок (дракон)','multi'=>false,'default'=>'assets/Mask group-1.png'],
  'ceremony_img' => ['g'=>'Церемония','l'=>'Фото церемонии','multi'=>false,'default'=>'assets/freepik_img2-the-half-glass-remai_2764317764 1.png'],
  /* Фото участников команды — динамический слот 'team' (управляется в admin/team.php),
     поэтому здесь его нет. Стартовые участники задаются в $DEFAULT_TEAM ниже. */
  'gallery'      => ['g'=>'Галерея','l'=>'Фотографии галереи','multi'=>true,'default'=>[
      'assets/image 2.png',
      'assets/ChatGPT Image 30 апр. 2026 г., 19_53_33 1.png',
      'assets/ChatGPT Image 30 апр. 2026 г., 19_54_23 1.png',
      'assets/ChatGPT Image Apr 30, 2026, 08_34_54 PM 1.png',
      'assets/WhatsApp Image 2026-05-08 at 18.21.01 2.png',
      'assets/image 3.png',
      'assets/WhatsApp Image 2026-05-08 at 18.21.01 4.png',
  ]],
];

/* ═══════════════════ НАСТРОЙКИ (одиночные значения) ═══════════════════
   Формат: 'ключ' => ['g'=>группа,'l'=>подпись,'t'=>тип,'default'=>значение]
   Типы: text, number, color, url, area  */
$DEFAULT_SETTINGS = [
  /* Бренд / общее */
  'brand_name'   => ['g'=>'Общее','l'=>'Название бренда','t'=>'text','default'=>'ChaiCore'],
  'site_title'   => ['g'=>'Общее','l'=>'Заголовок вкладки (SEO)','t'=>'text','default'=>'ChaiCore — Azərbaycan Çay Mədəniyyəti'],
  'default_lang' => ['g'=>'Общее','l'=>'Язык по умолчанию (az/ru/en)','t'=>'text','default'=>'az'],
  'copyright_year'=>['g'=>'Общее','l'=>'Год в копирайте','t'=>'text','default'=>'2024'],

  /* Размеры шрифтов (в процентах, 100 = обычный) */
  'font_scale'   => ['g'=>'Размеры шрифтов','l'=>'Общий масштаб текста, % (весь сайт)','t'=>'number','default'=>'100'],
  'scale_hero'   => ['g'=>'Размеры шрифтов','l'=>'Заголовок hero, %','t'=>'number','default'=>'100'],
  'scale_section'=> ['g'=>'Размеры шрифтов','l'=>'Заголовки секций, %','t'=>'number','default'=>'100'],
  'scale_mobile' => ['g'=>'Размеры шрифтов','l'=>'Доп. масштаб на телефонах, %','t'=>'number','default'=>'100'],

  /* Показ разделов на телефонах (галочка = показывать раздел на мобильных) */
  'mobile_show_about'        => ['g'=>'Разделы на телефонах','l'=>'О нас','t'=>'bool','default'=>'1'],
  'mobile_show_philosophy'   => ['g'=>'Разделы на телефонах','l'=>'Философия','t'=>'bool','default'=>'0'],
  'mobile_show_services'     => ['g'=>'Разделы на телефонах','l'=>'Услуги','t'=>'bool','default'=>'1'],
  'mobile_show_ceremony'     => ['g'=>'Разделы на телефонах','l'=>'Церемония (ход мероприятия)','t'=>'bool','default'=>'0'],
  'mobile_show_team'         => ['g'=>'Разделы на телефонах','l'=>'Команда (Чайные мастера)','t'=>'bool','default'=>'0'],
  'mobile_show_gallery'      => ['g'=>'Разделы на телефонах','l'=>'Галерея','t'=>'bool','default'=>'0'],
  'mobile_show_testimonials' => ['g'=>'Разделы на телефонах','l'=>'Отзывы','t'=>'bool','default'=>'0'],
  'mobile_show_contact'      => ['g'=>'Разделы на телефонах','l'=>'Контакты','t'=>'bool','default'=>'1'],

  /* Цифры «О нас» */
  'stat1_num'    => ['g'=>'О нас · Цифры','l'=>'Цифра 1','t'=>'text','default'=>'500+'],
  'stat2_num'    => ['g'=>'О нас · Цифры','l'=>'Цифра 2','t'=>'text','default'=>'20+'],
  'stat3_num'    => ['g'=>'О нас · Цифры','l'=>'Цифра 3','t'=>'text','default'=>'50+'],
  'stat4_num'    => ['g'=>'О нас · Цифры','l'=>'Цифра 4','t'=>'text','default'=>'3'],

  /* Контакты */
  'contact_phone'    => ['g'=>'Контакты','l'=>'Телефон','t'=>'text','default'=>'+994 XX XXX XX XX'],
  'contact_email'    => ['g'=>'Контакты','l'=>'Email','t'=>'text','default'=>'info@chaicore.az'],
  'contact_instagram'=> ['g'=>'Контакты','l'=>'Instagram (@ник)','t'=>'text','default'=>'@chaicore'],
  'map_embed'    => ['g'=>'Контакты','l'=>'Google Maps — код <iframe> (вставить embed)','t'=>'area','default'=>''],

  /* Соцсети — ссылки */
  'soc_instagram'=> ['g'=>'Соцсети','l'=>'Instagram — ссылка','t'=>'url','default'=>'#'],
  'soc_facebook' => ['g'=>'Соцсети','l'=>'Facebook — ссылка','t'=>'url','default'=>'#'],
  'soc_whatsapp' => ['g'=>'Соцсети','l'=>'WhatsApp — ссылка','t'=>'url','default'=>'#'],
  'soc_telegram' => ['g'=>'Соцсети','l'=>'Telegram — ссылка','t'=>'url','default'=>'#'],
  'soc_youtube'  => ['g'=>'Соцсети','l'=>'YouTube — ссылка','t'=>'url','default'=>'#'],
  'soc_tiktok'   => ['g'=>'Соцсети','l'=>'TikTok — ссылка','t'=>'url','default'=>'#'],

  /* Форма — куда слать заявки */
  'form_to_email'=> ['g'=>'Форма заявки','l'=>'Email для заявок (куда приходят брони)','t'=>'text','default'=>'info@chaicore.az'],

  /* Telegram — заявки прилетают в бота */
  'telegram_bot_token'=> ['g'=>'Telegram (заявки в бота)','l'=>'Токен бота (из BotFather)','t'=>'text','default'=>''],
  'telegram_chat_id'  => ['g'=>'Telegram (заявки в бота)','l'=>'Chat ID (куда слать заявки)','t'=>'text','default'=>''],
];

/* ═══════════════════ СТАРТОВЫЕ УЧАСТНИКИ КОМАНДЫ ═══════════════════
   Используются только при первой установке (для наполнения). Дальше команда
   правится через админку. name пустой = имя на сайте не показывается. */
$DEFAULT_TEAM = [
  [
    'photo' => 'assets/WhatsApp Image 2026-05-08 at 18.21.01 1.png',
    'name'  => ['az'=>'','ru'=>'','en'=>''],
    'role'  => ['az'=>'Baş Çay Ustası','ru'=>'Главный Чайный Мастер','en'=>'Head Tea Master'],
    'desc'  => ['az'=>'Azərbaycan çay mədəniyyəti üzrə ekspert. 3+ il təcrübə.','ru'=>'Эксперт по азербайджанской чайной культуре. 3+ лет опыта.','en'=>'Expert in Azerbaijani tea culture. 3+ years of experience.'],
  ],
  [
    'photo' => 'assets/WhatsApp Image 2026-05-08 at 18.21.01 3.png',
    'name'  => ['az'=>'','ru'=>'','en'=>''],
    'role'  => ['az'=>'Çay Mərasimi Ustası','ru'=>'Мастер Чайных Церемоний','en'=>'Ceremony Specialist'],
    'desc'  => ['az'=>'Tarixi hekayələndirmə və çay dequstasiyası üzrə mütəxəssis.','ru'=>'Специалист по историческому повествованию и дегустации чая.','en'=>'Specialist in historical storytelling and tea tasting.'],
  ],
  [
    'photo' => 'assets/WhatsApp Image 2026-05-08 at 18.21.01 4.png',
    'name'  => ['az'=>'','ru'=>'','en'=>''],
    'role'  => ['az'=>'Çay Dequstasiya Mütəxəssisi','ru'=>'Специалист по Дегустации','en'=>'Tasting Specialist'],
    'desc'  => ['az'=>'Çay sortları və dəmləmə texnikası üzrə dərin biliyə sahibdir.','ru'=>'Обладает глубокими знаниями сортов чая и техники заваривания.','en'=>'Deep expertise in tea varieties and brewing techniques.'],
  ],
];

/* ═══════════════════ СТАРТОВЫЕ ОТЗЫВЫ ═══════════════════
   Используются при первой установке. Дальше отзывы приходят с сайта
   (с модерацией) или добавляются админом. Для каждого создаётся по одному
   одобренному отзыву на каждый язык (az/ru/en). */
$DEFAULT_REVIEWS = [
  [
    'name'     => ['az'=>'','ru'=>'','en'=>''],
    'location' => ['az'=>'Bakı, Azərbaycan','ru'=>'Баку, Азербайджан','en'=>'Baku, Azerbaijan'],
    'text'     => ['az'=>'"ChaiCore mərasimi həyatımda gördüyüm ən maraqlı mədəni təcrübələrdən biri idi. Çay haqqında bu qədər dərin bilgiləri heç yerdə almamışdım."','ru'=>'«Церемония ChaiCore стала одним из самых интересных культурных впечатлений в моей жизни. Таких глубоких знаний о чае я нигде не получал.»','en'=>'"The ChaiCore ceremony was one of the most fascinating cultural experiences of my life. I have never gained such deep knowledge about tea anywhere else."'],
  ],
  [
    'name'     => ['az'=>'','ru'=>'','en'=>''],
    'location' => ['az'=>'Korporativ Müştəri','ru'=>'Корпоративный Клиент','en'=>'Corporate Client'],
    'text'     => ['az'=>'"Korporativ tədbirimiz üçün ChaiCore seçdik. Qonaqlarımız heyran qaldı — tamamilə fərqli, mənalı bir proqram oldu."','ru'=>'«Мы выбрали ChaiCore для нашего корпоративного мероприятия. Гости были в восхищении — это была совершенно иная, осмысленная программа.»','en'=>'"We chose ChaiCore for our corporate event. Our guests were amazed — it was an entirely different, meaningful programme."'],
  ],
  [
    'name'     => ['az'=>'','ru'=>'','en'=>''],
    'location' => ['az'=>'Beynəlxalq Turist','ru'=>'Международный Турист','en'=>'International Tourist'],
    'text'     => ['az'=>'"Turist olaraq Bakıya gəldim, ChaiCore mərasimi isə Azərbaycanı anlamağıma ən çox kömək edən şey oldu."','ru'=>'«Я приехал как турист в Баку, и церемония ChaiCore помогла мне понять Азербайджан лучше всего.»','en'=>'"I came to Baku as a tourist, and the ChaiCore ceremony was the single thing that helped me understand Azerbaijan the most."'],
  ],
];
