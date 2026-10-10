<?php
/**
 * Template Name: Revive — Розробка лендінгу
 *
 * Посадкова сторінка «Розробка лендінгу» (UA / EN через Polylang), дочірня до
 * «Розробка сайтів». Створіть сторінку українською (батьківська — «Розробка сайтів»),
 * додайте переклад англійською (батьківська — «Website development») і в обох
 * виберіть цей шаблон (Атрибути сторінки → Шаблон).
 *
 * Title, description і SEO-текст — у масивах нижче ($T['meta_t'], $T['meta_d'], $T['seo']).
 * SEO-текст унизу: якщо в редакторі сторінки є текст — виводиться він, якщо порожній — $T['seo'].
 *
 * @package ICE
 */

wp_enqueue_style(
	'revive-front-fonts',
	'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&family=Manrope:wght@400;500;600;700;800&display=swap',
	array(),
	null
);

$rv_en = function_exists( 'pll_current_language' ) && 'en' === pll_current_language();

/* ============================================================
   ЦІНИ пакетів лендінгу та опцій калькулятора — змінюйте тут,
   решта сторінки (картки, калькулятор, FAQ, розмітка) підхопить.
   ============================================================ */
$rv_wd_prices = array(
	'start'   => 250,  // лендінг-старт (спеціальна ціна)
	'sales'   => 450,  // продаючий лендінг
	'premium' => 800,  // преміум-лендінг
);
$rv_wd_addons = array( // ключ => [UA, EN, ціна $]
	'copy'   => array( 'Продаючий копірайтинг', 'Sales copywriting', 100 ),
	'quiz'   => array( 'Квіз або калькулятор', 'Quiz or calculator', 150 ),
	'lang'   => array( 'Друга мова', 'Second language', 100 ),
	'motion' => array( 'Преміум-анімації', 'Premium animations', 200 ),
	'crm'    => array( 'Інтеграція з CRM', 'CRM integration', 150 ),
	'pay'    => array( 'Онлайн-оплата', 'Online payments', 150 ),
	'ab'     => array( 'Друга версія для A/B-тесту', 'Second version for A/B testing', 150 ),
	'ads'    => array( 'Запуск Google Ads', 'Google Ads launch', 200 ),
);
$rv_m = function ( $k ) use ( $rv_wd_prices ) { return '$' . number_format( (int) $rv_wd_prices[ $k ], 0, '.', ' ' ); };

$T = $rv_en ? array(
	'meta_t' => 'Turnkey Landing Page Development — from ' . $rv_m( 'start' ),
	'meta_d' => 'We build turnkey landing pages from ' . $rv_m( 'start' ) . ': on-brand design, mobile-first layout, enquiry form, basic SEO and analytics. Launch from 5 working days.',
	'chip' => 'Landing page development',
	'h1a' => 'Turnkey landing page development',
	'h1b' => 'that turns clicks into enquiries',
	'hero_result' => '',
	'lead' => 'We design and build landing pages for services, products and ad campaigns — with a strong offer, fast loading and analytics in place from day one.',
	'hero_price' => 'Landing pages from ' . $rv_m( 'start' ),
	'hero_terms' => 'Scope and price agreed before we start',
	'hero_note' => 'Tell us what you are promoting — that is enough to get started.',
	'service_name' => 'Turnkey landing page development',
	'cta1' => 'Get a landing page estimate',
	'cta2' => 'Explore our work',
	'offer_tag' => 'Campaign landing page',
	'stats' => array( array( '120+', 'brands have chosen us' ), array( '12', 'years in web development' ), array( '5', 'working days to launch' ), array( '95%', 'returning clients' ) ),
	'toast1' => 'New enquiry from the landing page',
	'toast2' => 'Mobile experience',
	'toast2_value' => 'Built mobile-first',
	'toast3' => 'SEO foundation',
	'why_chip' => 'Why a landing page',
	'why_t' => 'One page that <em>sells one thing</em>',
	'why' => array( array( 'A clear offer', 'One service or product, one promise and the arguments behind it. Visitors understand what you offer within seconds.' ), array( 'Fast to launch', 'From brief to live page in as little as 5 working days — ideal for testing a new service or a seasonal campaign.' ), array( 'Ready for ads', 'Structure, speed and analytics are set up for Google Ads and social campaigns, so every click can be measured.' ), array( 'Enquiries straight to you', 'Forms, buttons and messengers deliver enquiries to email or Telegram, with CRM integration available.' ) ),
	'of_chip' => 'For one offer or campaign',
	'of_t' => 'A landing page <br>from <em>' . $rv_m( 'start' ) . '</em>',
	'of_lead' => 'Launching a service or testing an ad campaign? We build a dedicated page with one clear offer and a simple way to enquire. Scope is agreed before we start.',
	'of_inc' => array( 'Design in your brand style', 'Desktop and mobile layouts', 'SSL and hosting setup', 'Basic SEO + Search Console', 'Enquiries to email and Telegram', 'GA4 analytics setup' ),
	'of_meta' => array( 'From 5 working days', 'Agreed scope and price in the contract' ),
	'of_cta' => 'Discuss a landing page',
	'pk_chip' => 'Landing page packages',
	'pk_t' => 'Choose the format <em>for your goal</em>',
	'pk' => array( array( 'start', 'Start', 'A quick launch for one service, offer or test campaign.', array( 'Design in your brand style', 'Up to 6 sections', 'Enquiry form + Telegram', 'Basic SEO and GA4' ), 'From 5 working days', true ), array( 'sales', 'Sales landing page', 'A full sales page with offer, proof and objection handling.', array( 'Offer and structure workshop', 'Up to 12 sections', 'Animations and quiz-ready layout', 'Ad and conversion tracking' ), 'From 10 working days', false ), array( 'premium', 'Premium', 'A flagship page for a product launch or a premium brand.', array( 'Custom art direction', 'Advanced animations', 'Integrations and payments', 'A/B-ready versions' ), 'From 3 weeks', false ) ),
	'pk_badge' => 'Special price',
	'pk_pop' => 'Most chosen',
	'pk_from' => 'from',
	'pk_cta' => 'Get an estimate',
	'pk_note' => 'Need a full website instead? We will recommend the right format after a short call.',
	'ca_chip' => 'Budget guide',
	'ca_t' => 'Estimate <em>your landing page</em>',
	'ca_lead' => 'Choose a package and the extras you need. This is a starting estimate; we confirm the scope and final price after discussing your project.',
	'ca_1' => 'Which landing page do you need?',
	'ca_2' => 'Additional features',
	'ca_types' => array( 'start' => array( 'Start', 'Quick launch' ), 'sales' => array( 'Sales landing page', 'Full sales structure' ), 'premium' => array( 'Premium', 'Custom design and integrations' ) ),
	'ca_total' => 'Indicative budget',
	'ca_pay' => 'Payment in two stages',
	'ca_pre' => '50% to start',
	'ca_post' => '50% at launch',
	'ca_inc' => 'Included in every landing page',
	'ca_incl' => array( 'Basic technical SEO', 'Desktop and mobile layouts', 'Analytics setup', 'Enquiries to Telegram' ),
	'ca_cta' => 'Discuss this estimate',
	'ca_note' => 'The final scope, price and timeline are agreed in the contract before work begins.',
	'pr_chip' => 'A clear process',
	'pr_t' => 'From brief to launch <em>in 5 steps</em>',
	'pr' => array( array( 'Brief', 'We learn about your offer, audience and traffic sources, and agree what the page must achieve.', 'Your goal' ), array( 'Structure and copy', 'We plan the sections, key arguments and calls to action. You approve the structure.', 'Page plan' ), array( 'Design', 'Desktop and mobile layouts in your brand style, focused on the offer and the enquiry.', 'Your approval' ), array( 'Build and test', 'We build the page, connect forms and analytics, and test speed and every button.', 'Working preview' ), array( 'Launch', 'We publish on your domain, check enquiries and hand over access.', 'Live landing page' ) ),
	'cs_chip' => 'Selected projects',
	'cs_t' => 'Pages built for <em>results</em>',
	'cs_lead' => 'Selected results from projects combining web development with SEO or advertising. Each result reflects the work shown on its card.',
	'cs_all' => 'Explore the portfolio',
	'gu_chip' => 'Clear terms',
	'gu_t' => 'Know what you are <em>paying for</em>',
	'gu' => array( array( 'Agreed scope and price', 'The price for the agreed work is fixed in the contract. Any extra work is discussed before it starts.' ), array( 'Payment in two stages', '50% to begin and 50% at launch.' ), array( 'The page is yours', 'We hand over the page, admin access and project files.' ), array( 'Easy to edit', 'We show your team how to update text, prices and images.' ) ),
	'fq_chip' => 'Before you decide',
	'fq_t' => 'Your questions, <em>answered</em>',
	'fq' => array( array( 'How much does a landing page cost?', 'Landing pages start at ' . $rv_m( 'start' ) . ' (Start), a sales landing page from ' . $rv_m( 'sales' ) . ' and a premium page from ' . $rv_m( 'premium' ) . '. The final price depends on the number of sections, copy, animations and integrations.' ), array( 'What is included in a landing page from ' . $rv_m( 'start' ) . '?', 'A one-page site in your brand style, desktop and mobile layouts, an enquiry form, SSL and hosting setup, basic SEO, Search Console and GA4. Copywriting, extra languages and integrations are priced separately.' ), array( 'How long does it take?', 'A Start landing page takes from 5 working days, a sales landing page from 10 working days, a premium page from 3 weeks.' ), array( 'Landing page or a full website?', 'A landing page suits one offer or campaign. If you need to present many services or products, a business website or online store is a better fit — we will advise after a short call.' ), array( 'Will the landing page bring enquiries on its own?', 'A landing page needs traffic. We build it for ads and SEO and can launch Google Ads or social campaigns as a separate service.' ), array( 'Can I edit the page myself?', 'Yes. We build on WordPress and show you how to update text, prices and images after launch.' ), array( 'How does payment work?', '50% at the start and 50% at launch. The agreed scope and price go into the contract.' ) ),
	'seo_chip' => 'About landing page development',
	'seo_more' => 'Read more',
	'seo_less' => 'Collapse',
	'seo' => '<h2>Landing page development from ' . $rv_m( 'start' ) . '</h2>
<p>A landing page is a one-page website built around a single offer: a service, a product, an event or an advertising campaign. Unlike a multi-page site, it leads the visitor along one path — from the headline and benefits to proof, answers to objections and a clear call to action. That is why a well-built landing page usually converts better than a regular service page and is the go-to format for Google Ads, Meta and TikTok campaigns.</p>
<h3>What we include</h3>
<ul><li>an offer and page structure based on your audience and competitors;</li><li>design in your brand style, with desktop and mobile layouts;</li><li>enquiry forms with delivery to email and Telegram, plus CRM integration on request;</li><li>basic technical SEO, Search Console and GA4 setup;</li><li>fast loading, SSL and hosting setup.</li></ul>
<h3>When a landing page is the right choice</h3>
<p>Choose a landing page when you are launching a new service, testing demand, running a seasonal promotion or need a dedicated page for paid traffic. If you need to present many services, a case portfolio and a blog, a <a href="' . esc_url( home_url( '/web-development/' ) ) . '">business website</a> will serve you better.</p>
<h3>How much a landing page costs</h3>
<p>Our landing pages start at ' . $rv_m( 'start' ) . '. The final price depends on the number of sections, whether copywriting is needed, animations, quizzes and calculators, integrations and the number of language versions. We agree the scope and fix the price in the contract before work begins, and payment is split 50/50 between the start and the launch.</p>
<h3>Why Revive</h3>
<p>We have been building websites for 12 years and work with brands in 21 countries. We do not stop at design: we connect analytics and can launch advertising, so the landing page is measured by enquiries, not just by how it looks.</p>',
	'fn_chip' => 'Your next step',
	'fn_t' => 'Let’s talk about <em>your offer</em>',
	'fn_lead' => 'Tell us what you want to promote. We’ll suggest a structure and outline the budget and timeline.',
	'fn_or' => 'Or contact us directly',
	'sticky' => 'Landing page from ' . $rv_m( 'start' ),
	'sticky_b' => 'Get an estimate',
	'pp' => 'By sending the form you agree to our <a href="%s">privacy policy</a>.',
	'pp_url' => home_url( '/privacy-policy/' ),
	'form' => '[contact-form-7 id="10" title="cf7#1"]',
	'cases_url' => home_url( '/#rv-cine' ),
) : array(
	'meta_t' => 'Розробка лендінгу під ключ — від ' . $rv_m( 'start' ),
	'meta_d' => 'Створюємо лендінги під ключ від ' . $rv_m( 'start' ) . ': дизайн у стилі бренду, адаптив під телефон, форма заявки, базове SEO та аналітика. Запуск від 5 робочих днів.',
	'chip' => 'Розробка лендінгів',
	'h1a' => 'Розробка лендінгу під ключ,',
	'h1b' => 'що перетворює кліки на заявки',
	'hero_result' => '',
	'lead' => 'Створюємо лендінги для послуг, товарів і рекламних кампаній — з сильною пропозицією, швидким завантаженням і налаштованою аналітикою з першого дня.',
	'hero_price' => 'Лендінги — від ' . $rv_m( 'start' ),
	'hero_terms' => 'Обсяг робіт і ціну погоджуємо до старту',
	'hero_note' => 'Для старту достатньо розповісти, що ви просуваєте.',
	'service_name' => 'Розробка лендінгу під ключ',
	'cta1' => 'Отримати оцінку лендінгу',
	'cta2' => 'Переглянути роботи',
	'offer_tag' => 'Лендінг для реклами',
	'stats' => array( array( '120+', 'брендів обрали нас' ), array( '12', 'років у веброзробці' ), array( '5', 'робочих днів до запуску' ), array( '95%', 'клієнтів повертаються' ) ),
	'toast1' => 'Нова заявка з лендінгу',
	'toast2' => 'Мобільна версія',
	'toast2_value' => 'Спершу — під телефон',
	'toast3' => 'SEO-основа',
	'why_chip' => 'Навіщо лендінг',
	'why_t' => 'Одна сторінка, що <em>продає одне</em>',
	'why' => array( array( 'Чітка пропозиція', 'Одна послуга чи товар, одна обіцянка й аргументи на її користь. Відвідувач за секунди розуміє, що ви пропонуєте.' ), array( 'Швидкий запуск', 'Від брифу до робочої сторінки — від 5 робочих днів. Ідеально для тесту нової послуги чи сезонної акції.' ), array( 'Готовий до реклами', 'Структура, швидкість і аналітика налаштовані під Google Ads і таргет — кожен клік можна виміряти.' ), array( 'Заявки одразу вам', 'Форми, кнопки й месенджери передають заявки на пошту чи в Telegram, за потреби — у CRM.' ) ),
	'of_chip' => 'Для окремої послуги чи реклами',
	'of_t' => 'Лендінг під вашу пропозицію <br>від <em>' . $rv_m( 'start' ) . '</em>',
	'of_lead' => 'Запускаєте послугу або тестуєте рекламну кампанію? Створимо окрему сторінку з чіткою пропозицією та простим шляхом до заявки. Обсяг робіт погоджуємо перед стартом.',
	'of_inc' => array( 'Дизайн у стилі вашого бренду', 'Версії для ПК і телефона', 'SSL і налаштування хостингу', 'Базове SEO + Search Console', 'Заявки на пошту та в Telegram', 'Налаштування аналітики GA4' ),
	'of_meta' => array( 'Від 5 робочих днів', 'Обсяг робіт і ціна — у договорі' ),
	'of_cta' => 'Обговорити лендінг',
	'pk_chip' => 'Пакети лендінгів',
	'pk_t' => 'Оберіть формат <em>під вашу задачу</em>',
	'pk' => array( array( 'start', 'Старт', 'Швидкий запуск для однієї послуги, акції чи тестової кампанії.', array( 'Дизайн у стилі бренду', 'До 6 блоків', 'Форма заявки + Telegram', 'Базове SEO та GA4' ), 'Від 5 робочих днів', true ), array( 'sales', 'Продаючий лендінг', 'Повноцінна продаюча сторінка: оффер, докази, відпрацювання заперечень.', array( 'Опрацювання оферу та структури', 'До 12 блоків', 'Анімації та місце під квіз', 'Відстеження реклами й конверсій' ), 'Від 10 робочих днів', false ), array( 'premium', 'Преміум', 'Флагманська сторінка для запуску продукту чи преміум-бренду.', array( 'Індивідуальний арт-дирекшн', 'Складні анімації', 'Інтеграції та онлайн-оплата', 'Версії для A/B-тестів' ), 'Від 3 тижнів', false ) ),
	'pk_badge' => 'Спецціна',
	'pk_pop' => 'Обирають найчастіше',
	'pk_from' => 'від',
	'pk_cta' => 'Отримати оцінку',
	'pk_note' => 'Потрібен повноцінний сайт? Підкажемо формат після короткої розмови.',
	'ca_chip' => 'Орієнтир за бюджетом',
	'ca_t' => 'Оцініть бюджет <em>вашого лендінгу</em>',
	'ca_lead' => 'Оберіть пакет і потрібні опції. Це попередній розрахунок: обсяг робіт і остаточну суму погодимо після обговорення задачі.',
	'ca_1' => 'Який лендінг вам потрібен?',
	'ca_2' => 'Додаткові можливості',
	'ca_types' => array( 'start' => array( 'Старт', 'Швидкий запуск' ), 'sales' => array( 'Продаючий лендінг', 'Повна продаюча структура' ), 'premium' => array( 'Преміум', 'Індивідуальний дизайн та інтеграції' ) ),
	'ca_total' => 'Орієнтовний бюджет',
	'ca_pay' => 'Оплата у два етапи',
	'ca_pre' => '50% на старті',
	'ca_post' => '50% після запуску',
	'ca_inc' => 'Входить у кожен лендінг',
	'ca_incl' => array( 'Базове технічне SEO', 'Версії для ПК і телефона', 'Налаштування аналітики', 'Заявки в Telegram' ),
	'ca_cta' => 'Обговорити цей розрахунок',
	'ca_note' => 'Обсяг робіт, точну ціну та строки фіксуємо в договорі до початку розробки.',
	'pr_chip' => 'Зрозумілий процес',
	'pr_t' => 'Від брифу до запуску <em>за 5 кроків</em>',
	'pr' => array( array( 'Бриф', 'Розбираємо пропозицію, аудиторію та джерела трафіку. Погоджуємо, що має робити сторінка.', 'Ваша мета' ), array( 'Структура й тексти', 'Плануємо блоки, ключові аргументи й заклики до дії. Погоджуємо структуру.', 'План сторінки' ), array( 'Дизайн', 'Макети для ПК і телефона у стилі бренду, з фокусом на оффер і заявку.', 'Ваше погодження' ), array( 'Розробка й тести', 'Збираємо сторінку, підключаємо форми й аналітику, перевіряємо швидкість і кожну кнопку.', 'Робоча версія' ), array( 'Запуск', 'Публікуємо на вашому домені, перевіряємо заявки, передаємо доступи.', 'Ваш лендінг' ) ),
	'cs_chip' => 'Приклади проєктів',
	'cs_t' => 'Сторінки під <em>результат</em>',
	'cs_lead' => 'Результати проєктів, у яких розробка поєднувалася з SEO чи рекламою. Напрями роботи зазначені в кожному кейсі.',
	'cs_all' => 'Переглянути портфоліо',
	'gu_chip' => 'Прозорі умови',
	'gu_t' => 'Ви розумієте, <em>за що платите</em>',
	'gu' => array( array( 'Узгоджені обсяг і ціна', 'Вартість погоджених робіт фіксуємо в договорі. Додаткові задачі обговорюємо до виконання.' ), array( 'Оплата у два етапи', '50% на старті та 50% після запуску.' ), array( 'Лендінг — ваш', 'Передаємо сторінку, доступи до адмінки та файли проєкту.' ), array( 'Легко редагувати', 'Показуємо, як самостійно змінювати тексти, ціни й зображення.' ) ),
	'fq_chip' => 'Перед початком',
	'fq_t' => 'Відповіді на <em>ваші питання</em>',
	'fq' => array( array( 'Скільки коштує розробка лендінгу?', 'Лендінг «Старт» — від ' . $rv_m( 'start' ) . ', продаючий лендінг — від ' . $rv_m( 'sales' ) . ', преміум — від ' . $rv_m( 'premium' ) . '. Остаточна ціна залежить від кількості блоків, текстів, анімацій та інтеграцій.' ), array( 'Що входить у лендінг від ' . $rv_m( 'start' ) . '?', 'Односторінковий сайт у стилі бренду, версії для ПК і телефона, форма заявки, SSL і налаштування хостингу, базове SEO, підключення Search Console та GA4. Копірайтинг, додаткові мови й інтеграції оцінюємо окремо.' ), array( 'Скільки часу займає розробка?', 'Лендінг «Старт» — від 5 робочих днів, продаючий — від 10 робочих днів, преміум — від 3 тижнів.' ), array( 'Лендінг чи повноцінний сайт?', 'Лендінг підходить для однієї пропозиції чи кампанії. Якщо потрібно показати багато послуг або товарів, краще корпоративний сайт чи інтернет-магазин — підкажемо після короткої розмови.' ), array( 'Чи почне лендінг сам приносити заявки?', 'Лендінгу потрібен трафік. Ми готуємо його під рекламу та SEO і можемо окремо запустити Google Ads чи таргетовану рекламу.' ), array( 'Чи зможу я сам редагувати сторінку?', 'Так. Створюємо лендінги на WordPress і після запуску показуємо, як змінювати тексти, ціни та зображення.' ), array( 'Як відбувається оплата?', '50% на старті та 50% після запуску. Погоджені обсяг робіт і ціну прописуємо в договорі.' ) ),
	'seo_chip' => 'Про розробку лендінгів',
	'seo_more' => 'Читати далі',
	'seo_less' => 'Згорнути',
	'seo' => '<h2>Розробка лендінгу під ключ від ' . $rv_m( 'start' ) . '</h2>
<p>Лендінг — це односторінковий сайт, побудований навколо однієї пропозиції: послуги, товару, події чи рекламної кампанії. На відміну від багатосторінкового сайту, він веде відвідувача одним шляхом — від заголовка й вигод до доказів, відповідей на заперечення та чіткого заклику до дії. Тому якісний лендінг зазвичай конвертує краще за звичайну сторінку послуги й найкраще підходить для реклами в Google Ads, Meta та TikTok.</p>
<h3>Що входить у розробку</h3>
<ul><li>оффер і структура сторінки з урахуванням аудиторії та конкурентів;</li><li>дизайн у стилі вашого бренду, версії для ПК і телефона;</li><li>форми заявок із передачею на пошту та в Telegram, за потреби — інтеграція з CRM;</li><li>базове технічне SEO, підключення Search Console та GA4;</li><li>швидке завантаження, SSL і налаштування хостингу.</li></ul>
<h3>Коли варто замовити лендінг</h3>
<p>Лендінг — правильний вибір, коли ви запускаєте нову послугу, тестуєте попит, проводите сезонну акцію або вам потрібна окрема сторінка під платний трафік. Якщо ж потрібно показати багато послуг, портфоліо кейсів і блог, краще підійде <a href="' . esc_url( home_url( '/uk/rozrobka-sajtiv/' ) ) . '">корпоративний сайт</a>.</p>
<h3>Скільки коштує лендінг</h3>
<p>Вартість розробки лендінгу — від ' . $rv_m( 'start' ) . '. Остаточна ціна залежить від кількості блоків, потреби в копірайтингу, анімацій, квізів і калькуляторів, інтеграцій та кількості мовних версій. Обсяг робіт погоджуємо й фіксуємо ціну в договорі до старту, а оплата ділиться 50/50 — на початку та після запуску.</p>
<h3>Чому Revive</h3>
<p>Ми створюємо сайти 12 років і працюємо з брендами з 21 країни. Ми не зупиняємося на дизайні: підключаємо аналітику й можемо запустити рекламу, тож лендінг оцінюється кількістю заявок, а не лише зовнішнім виглядом.</p>',
	'fn_chip' => 'Наступний крок',
	'fn_t' => 'Обговорімо <em>вашу пропозицію</em>',
	'fn_lead' => 'Розкажіть, що хочете просувати. Запропонуємо структуру й окреслимо бюджет та строки.',
	'fn_or' => 'Або зв’яжіться з нами напряму',
	'sticky' => 'Лендінг від ' . $rv_m( 'start' ),
	'sticky_b' => 'Отримати оцінку',
	'pp' => 'Надсилаючи форму, ви погоджуєтеся з <a href="%s">політикою конфіденційності</a>.',
	'pp_url' => home_url( '/uk/privacy-policy/' ),
	'form' => '[contact-form-7 id="473" title="cf7#1_ua"]',
	'cases_url' => home_url( '/uk/#rv-cine' ),
);

/* title і description сторінки (працює і з Yoast / Rank Math, і без них) */
$rv_meta_t = $T['meta_t'];
$rv_meta_d = $T['meta_d'];
$rv_set_t  = static function () use ( $rv_meta_t ) { return $rv_meta_t; };
$rv_set_d  = static function () use ( $rv_meta_d ) { return $rv_meta_d; };
add_filter( 'pre_get_document_title', $rv_set_t, 999 );
foreach ( array( 'wpseo_title', 'wpseo_opengraph_title', 'wpseo_twitter_title', 'rank_math/frontend/title', 'rank_math/opengraph/facebook/og_title' ) as $rv_h ) add_filter( $rv_h, $rv_set_t, 999 );
foreach ( array( 'wpseo_metadesc', 'wpseo_opengraph_desc', 'wpseo_twitter_description', 'rank_math/frontend/description', 'rank_math/opengraph/facebook/og_description' ) as $rv_h ) add_filter( $rv_h, $rv_set_d, 999 );
if ( ! defined( 'WPSEO_VERSION' ) && ! class_exists( 'RankMath' ) && ! defined( 'AIOSEO_VERSION' ) ) {
	add_action( 'wp_head', static function () use ( $rv_meta_d ) { echo '<meta name="description" content="' . esc_attr( $rv_meta_d ) . '">' . "\n"; }, 1 );
}

/* Кейси — ті самі, що в портфоліо на головній (вебпроєкти). */
$rv_wd_cases = array(
	array( 'Balitecture', 'balitecture.com', '2025', '+260%', 'звернень', 'enquiries', 'Web · Target' ),
	array( 'Nikola service', 'nikola-service.com', '2023', '+355%', 'дзвінків із сайту', 'calls from site', 'Web · Google Ads' ),
	array( 'Ukrzoloto', 'ukrzoloto.ua', '2018', '+480%', 'органічного трафіку', 'organic traffic', 'Web · SEO' ),
	array( 'Fastkava', 'fastkava.com.pl', '2026', '+420%', 'органічного трафіку', 'organic traffic', 'SEO · Web' ),
	array( 'Gotech HVAC', 'gotech-hvac.com', '2025', '+310%', 'органічного трафіку', 'organic traffic', 'Web · SEO' ),
	array( 'Hekaya', 'hekayajewellery.com', '2024', 'x3.1', 'ROAS', 'ROAS', 'Web · Target' ),
);

$rv_wd_channels = array(
	array( 'phone', $rv_en ? 'Phone' : 'Телефон', '+38 (067) 8000-830', 'tel:+380678000830', false ),
	array( 'tg', 'Telegram', '@max8ua', 'https://telegram.me/max8ua', true ),
	array( 'wa', 'WhatsApp', '+38 067 8000 830', 'https://wa.me/380678000830', true ),
);
$rv_wd_icons = array(
	'phone' => '<svg viewBox="0 0 24 24"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/></svg>',
	'tg'    => '<svg viewBox="0 0 24 24"><path d="M21 4L3 11l6 2.2L18 7l-7 7.5V20l3.3-3.6L18 19z"/></svg>',
	'wa'    => '<svg viewBox="0 0 24 24"><path d="M4 20l1.3-3.9A8.5 8.5 0 1 1 8 18.8z"/><path d="M9 8.5c0 3.5 3 6.5 6.5 6.5l1-1.8-2-1-1 1a5 5 0 0 1-2.7-2.7l1-1-1-2z"/></svg>',
	'check' => '<svg viewBox="0 0 24 24"><path d="M4.5 12.5l5 5L19.5 7"/></svg>',
	'arrow' => '<svg viewBox="0 0 16 16"><path d="M4.5 11.5l7-7M6 4.5h5.5V10"/></svg>',
);
$rv_wd_why_icons = array(
	'<svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="6.5"/><path d="M16 16l4.5 4.5"/></svg>',
	'<svg viewBox="0 0 24 24"><path d="M13 2.5L4.5 13.5H11l-1 8 8.5-11H12z"/></svg>',
	'<svg viewBox="0 0 24 24"><path d="M4 18l5-5 4 3 7-8"/><path d="M15 8h5v5"/></svg>',
	'<svg viewBox="0 0 24 24"><path d="M21 4L3 11l6 2.2L18 7l-7 7.5V20l3.3-3.6L18 19z"/></svg>',
);
function rv_wd_money( $n ) { return '$' . number_format( (int) $n, 0, '.', ' ' ); }

/* SEO-текст: контент сторінки з редактора, якщо він є, інакше — стандартний */
$rv_wd_seo_html = '';
if ( have_posts() ) {
	the_post();
	$rv_wd_raw = trim( (string) get_post_field( 'post_content', get_the_ID() ) );
	if ( '' !== $rv_wd_raw ) $rv_wd_seo_html = apply_filters( 'the_content', $rv_wd_raw );
	rewind_posts();
}
if ( '' === trim( wp_strip_all_tags( $rv_wd_seo_html ) ) ) $rv_wd_seo_html = wpautop( $T['seo'] );

get_header();
?>
<main class="rv-wd" id="rv-wd">
<style id="rv-wd-css">
/* ======================================================================
   REVIVE — WEB DEVELOPMENT LANDING
   Той самий візуальний код, що й головна: чорнильний #030032, золото
   #F5C542, Manrope 800 у заголовках, JetBrains Mono у підписах.
   ====================================================================== */
body{background:#030032}
/* в style.css темы есть глобальное main{display:none} — возвращаем страницу */
main.rv-wd{display:block!important}
/* тема задає маркери спискам (1. 2. та крапки) — у блоках сторінки вони не потрібні */
.rv-wd ul,.rv-wd ol,.rv-wd li{list-style:none!important}
.rv-wd li::marker{content:none}
.rv-wd-gu__grid li::before,.rv-wd-gu__grid li::after,.rv-wd-inc li::before,.rv-wd-pk li::before,.rv-wd-ca__incl li::before{content:none!important;display:none!important}
.rv-wd em,.rv-wd i{font-style:normal}
.rv-wd{--gold:#F5C542;--gold2:#FCE7A2;--ink:#030032;--ok:#5BD98A;--line:rgba(255,255,255,.1);
  --ease:cubic-bezier(.16,1,.3,1);--px:clamp(20px,4vw,64px);--h:'Manrope','Montserrat',Arial,sans-serif;--mono:'JetBrains Mono',monospace;
  position:relative;overflow:hidden;background:#030032;color:#fff;font-family:'Inter',Arial,sans-serif}
/* сброс с нулевой специфичностью (:where) — классы блоков всегда сильнее */
:where(.rv-wd) *,:where(.rv-wd) *::before,:where(.rv-wd) *::after{box-sizing:border-box}
:where(.rv-wd) :where(h1,h2,h3,p,ul,ol,figure){margin:0;padding:0}
:where(.rv-wd) :where(ul,ol){list-style:none}
:where(.rv-wd) :where(i,em,b){font-style:normal}
:where(.rv-wd) a{text-decoration:none;color:inherit}
:where(.rv-wd) button{font:inherit;color:inherit;border:0;background:none;cursor:pointer;-webkit-appearance:none;appearance:none}
:where(.rv-wd) svg{display:block}
.rv-wd__sec{position:relative;padding:clamp(52px,6vw,96px) var(--px)}
.rv-wd__in{position:relative;max-width:1320px;margin:0 auto}

/* общие элементы */
.rv-wd__chip{display:inline-flex;align-items:center;gap:.7rem;padding:.5rem 1.05rem;border-radius:100px;border:1px solid rgba(255,255,255,.12);
  background:rgba(255,255,255,.04);font-family:var(--mono);font-size:.64rem;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.82)}
.rv-wd__chip::before{content:"";width:7px;height:7px;border-radius:50%;background:var(--gold);box-shadow:0 0 12px var(--gold)}
.rv-wd__h2{margin-top:18px!important;font-family:var(--h);font-weight:800;font-size:clamp(32px,4vw,58px);line-height:1.04;letter-spacing:-.035em;text-transform:uppercase}
.rv-wd__h2 em{color:transparent;-webkit-text-stroke:1.5px var(--gold)}
.rv-wd__head{display:flex;justify-content:space-between;align-items:flex-end;gap:24px;flex-wrap:wrap;margin-bottom:clamp(34px,4vw,56px)}
.rv-wd__btn{position:relative;overflow:hidden;isolation:isolate;display:inline-flex;align-items:center;justify-content:center;gap:12px;min-height:60px;padding:0 30px;border-radius:999px;
  font-family:'Inter',Arial,sans-serif;font-weight:800;font-size:.84rem;letter-spacing:.08em;text-transform:uppercase;white-space:nowrap;
  transition:transform .35s var(--ease),box-shadow .35s var(--ease),background .3s ease,border-color .3s ease,color .3s ease}
.rv-wd__btn svg{width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:1.9;stroke-linecap:round;stroke-linejoin:round;transition:transform .35s var(--ease)}
.rv-wd__btn:hover svg{transform:translate(2px,-2px)}
.rv-wd__btn--gold{color:var(--ink)!important;background:linear-gradient(135deg,#F5D488 0%,#F5C542 48%,#DFAE3F 100%);box-shadow:0 12px 34px rgba(245,197,66,.3)}
.rv-wd__btn--gold::after{content:"";position:absolute;top:0;bottom:0;left:-60%;width:40%;z-index:-1;background:linear-gradient(100deg,transparent,rgba(255,255,255,.7),transparent);transform:skewX(-20deg);animation:rvWdGlint 5.5s ease-in-out 2s infinite}
.rv-wd__btn--gold:hover{transform:translateY(-2px);box-shadow:0 18px 44px rgba(245,197,66,.42)}
.rv-wd__btn--ghost{border:1px solid rgba(255,255,255,.2);color:#fff!important;background:rgba(255,255,255,.03)}
.rv-wd__btn--ghost:hover{border-color:var(--gold);color:var(--gold)!important}
.rv-wd__btn:focus-visible,.rv-wd a:focus-visible,.rv-wd button:focus-visible{outline:2px solid rgba(245,197,66,.8);outline-offset:3px}
@keyframes rvWdGlint{0%{left:-60%}30%,100%{left:130%}}
.rv-wd__grid-bg{position:absolute;inset:0;pointer-events:none;
  background:linear-gradient(rgba(140,150,255,.045) 1px,transparent 1px) 0 0/80px 80px,linear-gradient(90deg,rgba(140,150,255,.045) 1px,transparent 1px) 0 0/80px 80px;
  -webkit-mask-image:radial-gradient(ellipse 70% 70% at 50% 40%,#000,transparent 80%);mask-image:radial-gradient(ellipse 70% 70% at 50% 40%,#000,transparent 80%)}

/* появление */
.rv-wd-js [data-rv]{opacity:0;transform:translate3d(0,34px,0);transition:opacity .9s ease,transform 1.1s var(--ease);transition-delay:calc(var(--d,0) * 90ms)}
.rv-wd-js [data-rv].is-in{opacity:1;transform:none}

/* ── HERO ─────────────────────────────────────────────────────────── */
.rv-wd-hero{padding-top:clamp(40px,6vw,90px);padding-bottom:clamp(60px,7vw,100px)}
.rv-wd-hero::before{content:"";position:absolute;right:-10%;top:-20%;width:70%;height:120%;pointer-events:none;
  background:radial-gradient(closest-side,rgba(100,108,255,.22),transparent 70%)}
.rv-wd-hero::after{content:"";position:absolute;left:-10%;bottom:-30%;width:60%;height:90%;pointer-events:none;
  background:radial-gradient(closest-side,rgba(245,197,66,.12),transparent 70%)}
.rv-wd-hero .rv-wd__in{display:grid;grid-template-columns:minmax(0,1.05fr) minmax(0,.95fr);gap:clamp(30px,4vw,70px);align-items:center;z-index:1}
.rv-wd-hero h1{margin-top:22px!important;font-family:var(--h);font-weight:800;font-size:clamp(34px,4.3vw,66px);line-height:1.02;letter-spacing:-.04em;text-transform:uppercase}
.rv-wd-hero h1 span{display:block;margin-top:.08em;color:transparent;background:linear-gradient(100deg,#F5D488,#F5C542 40%,#fff3c4 50%,#F5C542 60%,#DFAE3F);
  background-size:220% 100%;-webkit-background-clip:text;background-clip:text;animation:rvWdShine 6s ease-in-out 1s infinite}
@keyframes rvWdShine{0%,60%{background-position:100% 0}90%,100%{background-position:0 0}}
.rv-wd-hero__lead{margin-top:24px!important;max-width:46ch;font-size:clamp(16px,1.2vw,19px);line-height:1.6;color:rgba(255,255,255,.86)}
.rv-wd-hero__cta{display:flex;flex-wrap:wrap;gap:12px;margin-top:34px}
.rv-wd-hero__cta .rv-wd__btn--ghost b{color:var(--gold);margin-left:-4px}
.rv-wd-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:0;margin-top:clamp(36px,4vw,54px);border-top:1px solid var(--line)}
.rv-wd-stats div{padding:18px 14px 0 0}
.rv-wd-stats b{display:block;font-family:var(--h);font-weight:800;font-size:clamp(24px,2.4vw,36px);letter-spacing:-.03em;color:var(--gold);font-variant-numeric:tabular-nums}
.rv-wd-stats span{display:block;margin-top:6px;font-family:var(--mono);font-size:.6rem;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.7);line-height:1.45}

/* визуал: окно браузера + «живые» уведомления */
/* Сцена BUILD с главной, адаптированная под услугу: окно сайта клиента,
   шар-орб, телефон с мобильной версией, курсор «нажимает» ЗАМОВИТИ —
   и через мгновение приходит заявка в Telegram. */
.rv-wd-vis{position:relative;aspect-ratio:1.08;perspective:1300px}
/* ПК: анимация справа крупнее на 15%. Правый край на месте — растёт влево и больше вверх,
   чтобы телефон не наезжал на строку со статистикой. */
@media(min-width:1101px){.rv-wd-hero .rv-wd-vis{scale:1.15;transform-origin:100% 60%}}
.rv-wd-orbit{position:absolute;border:1px solid rgba(245,244,239,.1);border-radius:50%;pointer-events:none;animation:rvWdSpin 22s linear infinite}
.rv-wd-orbit::before{content:"";position:absolute;top:12%;left:12%;width:7px;height:7px;border-radius:50%;background:var(--gold);box-shadow:0 0 14px var(--gold)}
.rv-wd-orbit--one{inset:0 5% 22% 10%;transform:rotate(-14deg)}
.rv-wd-orbit--two{inset:14% 16% 8% 0;animation-direction:reverse;animation-duration:30s}
@keyframes rvWdSpin{to{rotate:360deg}}
/* окно браузера */
.rv-wd-bw{position:absolute;left:0;right:10%;top:10%;overflow:hidden;border:1px solid rgba(245,244,239,.21);border-radius:clamp(16px,2vw,28px);
  background:rgba(12,15,48,.96);box-shadow:0 54px 100px rgba(0,0,20,.5),0 0 80px rgba(100,108,255,.14),inset 0 0 0 1px rgba(255,255,255,.035);
  transform:rotateY(-6deg) rotateX(2deg);transform-origin:50% 100%;animation:rvWdBwFloat 8s ease-in-out infinite alternate}
@keyframes rvWdBwFloat{to{transform:rotateY(-3deg) rotateX(1deg) translateY(-12px)}}
.rv-wd-bw__bar{display:flex;align-items:center;gap:7px;height:38px;padding:0 16px;border-bottom:1px solid var(--line);background:rgba(255,255,255,.025)}
.rv-wd-bw__bar i{width:7px;height:7px;border-radius:50%;background:rgba(245,244,239,.23)}.rv-wd-bw__bar i:first-child{background:var(--gold)}
.rv-wd-bw__bar span{margin-left:auto;font-family:var(--mono);font-size:.62rem;letter-spacing:.04em;color:rgba(255,255,255,.72)}
.rv-wd-bw__screen{position:relative;overflow:hidden;aspect-ratio:1.42;padding:6% 6.5%}
.rv-wd-bw__screen::before{content:"";position:absolute;right:-6%;top:22%;width:58%;aspect-ratio:1;border-radius:50%;
  background:radial-gradient(circle at 34% 28%,#fff7d4 0%,#f5c542 11%,#645fff 37%,#11134c 66%,transparent 70%);box-shadow:0 0 70px rgba(100,108,255,.4);
  animation:rvWdOrb 7s ease-in-out infinite alternate}
.rv-wd-bw__screen::after{content:"";position:absolute;right:4%;top:16%;width:45%;aspect-ratio:1;border:1px solid rgba(245,244,239,.12);border-radius:50%;transform:rotate(-18deg)}
@keyframes rvWdOrb{to{transform:translate(-12px,14px) scale(1.07) rotate(16deg)}}
.rv-wd-bw__nav{position:relative;z-index:2;display:flex;align-items:center;gap:10px}
.rv-wd-bw__nav strong{margin-right:auto;font-family:var(--h);font-weight:800;font-size:clamp(9px,.95vw,14px);letter-spacing:-.01em;white-space:nowrap}
.rv-wd-bw__nav strong em{color:var(--gold)}
.rv-wd-bw__nav i{width:30px;height:3px;border-radius:2px;background:rgba(245,244,239,.23)}
.rv-wd-bw__nav b{position:relative;padding:6px 11px;border-radius:999px;background:var(--gold);color:var(--ink);font-family:var(--mono);font-size:clamp(5px,.5vw,7px);font-weight:700;letter-spacing:.08em}
.rv-wd-bw__nav b::after{content:"";position:absolute;inset:-4px;border-radius:inherit;border:1px solid rgba(245,197,66,.8);opacity:0;animation:rvWdPress 6s ease-out infinite}
@keyframes rvWdPress{0%,33%{opacity:0;transform:scale(.9)}35%{opacity:1;transform:scale(1)}50%,100%{opacity:0;transform:scale(1.6)}}
.rv-wd-bw__hero{position:relative;z-index:2;width:66%;padding-top:clamp(26px,4.4vw,64px)}
.rv-wd-bw__hero p,.rv-wd-bw__hero span{display:block;font-family:var(--mono);font-size:clamp(6px,.6vw,9px);line-height:1.5;letter-spacing:.11em;text-transform:uppercase;color:#fff}
.rv-wd-bw__hero h3{margin:10px 0 16px;font-family:var(--h);font-weight:800;font-size:clamp(24px,3.2vw,50px);line-height:.88;letter-spacing:-.055em;text-transform:uppercase}
.rv-wd-bw__hero h3 em{color:var(--gold)}
.rv-wd-bw__cards{position:absolute;z-index:2;right:5%;bottom:7%;display:flex;gap:7px;width:39%}
.rv-wd-bw__cards i{display:block;flex:1;aspect-ratio:1.25;border:1px solid rgba(245,244,239,.13);border-radius:8px;background:linear-gradient(145deg,rgba(255,255,255,.11),rgba(255,255,255,.025));
  transform-origin:bottom;animation:rvWdCard 2.8s ease-in-out infinite alternate}
.rv-wd-bw__cards i:nth-child(2){animation-delay:.24s}.rv-wd-bw__cards i:nth-child(3){animation-delay:.48s}
@keyframes rvWdCard{from{transform:translateY(5px);opacity:.48}to{transform:translateY(-8px);opacity:1}}
/* курсор: едет к кнопке ЗАМОВИТИ и нажимает её */
.rv-wd-cur{position:absolute;z-index:3;width:30px;height:30px;margin:-15px 0 0 -15px;border-radius:50%;border:1.5px solid rgba(255,248,216,.9);
  box-shadow:0 0 18px rgba(245,197,66,.55);left:50%;top:78%;opacity:0;animation:rvWdCur 6s cubic-bezier(.65,0,.35,1) infinite}
.rv-wd-cur::after{content:"";position:absolute;left:50%;top:50%;width:6px;height:6px;margin:-3px 0 0 -3px;border-radius:50%;background:#fff8d8}
@keyframes rvWdCur{0%{opacity:0;left:48%;top:80%;transform:scale(1)}8%{opacity:1}30%{left:88%;top:8.5%;transform:scale(1)}34%{transform:scale(.7)}38%{transform:scale(1)}
  62%{opacity:1;left:72%;top:52%}74%,100%{opacity:0;left:60%;top:70%}}
/* телефон — мобильная версия того же сайта */
.rv-wd-ph{position:absolute;z-index:4;right:0;bottom:0;width:clamp(96px,11.5vw,160px);aspect-ratio:.49;padding:clamp(15px,1.7vw,24px) clamp(11px,1.2vw,18px);overflow:hidden;
  border:2px solid rgba(245,244,239,.32);border-radius:clamp(20px,2.6vw,34px);
  background:radial-gradient(circle at 70% 48%,rgba(100,108,255,.46),transparent 28%),linear-gradient(160deg,#14185c,#050625 68%);
  box-shadow:0 34px 72px rgba(0,0,18,.55),0 0 44px rgba(100,108,255,.16);transform:rotate(5deg);animation:rvWdPh 5.5s ease-in-out infinite alternate}
@keyframes rvWdPh{to{transform:translateY(-14px) rotate(2deg)}}
.rv-wd-ph__sensor{position:absolute;top:7px;left:50%;width:35%;height:8px;border-radius:10px;background:#030412;transform:translateX(-50%)}
.rv-wd-ph__brand{font-family:var(--h);font-weight:800;font-size:clamp(6px,.62vw,9px);white-space:nowrap}
.rv-wd-ph__brand em{color:var(--gold)}
.rv-wd-ph p{position:relative;z-index:2;margin:clamp(22px,3vw,40px) 0 0!important;font-family:var(--h);font-weight:800;font-size:clamp(13px,1.5vw,22px);line-height:.92;letter-spacing:-.04em;text-transform:uppercase}
.rv-wd-ph p em{color:var(--gold)}
.rv-wd-ph__orb{position:absolute;right:-10%;top:44%;width:86%;aspect-ratio:1;border-radius:50%;background:radial-gradient(circle at 35% 30%,#fff8d8,#f5c542 12%,#635eff 38%,#10144b 67%);
  box-shadow:0 0 38px rgba(100,108,255,.5);animation:rvWdOrb 5.5s ease-in-out infinite alternate-reverse}
.rv-wd-ph__btn{position:absolute;left:12%;right:12%;bottom:7%;padding:7px 6px;border-radius:999px;background:#F5F4EF;color:#060623;font-family:var(--mono);font-size:clamp(5px,.55vw,8px);font-weight:700;text-align:center}
/* вход: окно поднимается, строки проявляются, телефон влетает */
.rv-wd-js .rv-wd-vis .rv-wd-bw{clip-path:inset(10% 8% 10% 8% round 30px);transition:clip-path 1.3s var(--ease) .15s}
.rv-wd-js .rv-wd-vis.is-in .rv-wd-bw{clip-path:inset(-20% -20% -20% -20% round 0)}
.rv-wd-js .rv-wd-vis .rv-wd-bw__nav,.rv-wd-js .rv-wd-vis .rv-wd-bw__hero > *{opacity:0;translate:0 20px;transition:opacity .8s ease,translate 1s var(--ease)}
.rv-wd-js .rv-wd-vis.is-in .rv-wd-bw__nav,.rv-wd-js .rv-wd-vis.is-in .rv-wd-bw__hero > *{opacity:1;translate:0 0}
.rv-wd-js .rv-wd-vis.is-in .rv-wd-bw__nav{transition-delay:.5s}
.rv-wd-js .rv-wd-vis.is-in .rv-wd-bw__hero > :nth-child(1){transition-delay:.62s}
.rv-wd-js .rv-wd-vis.is-in .rv-wd-bw__hero > :nth-child(2){transition-delay:.74s}
.rv-wd-js .rv-wd-vis.is-in .rv-wd-bw__hero > :nth-child(3){transition-delay:.9s}
.rv-wd-js .rv-wd-vis .rv-wd-ph{translate:90px 70px;rotate:14deg;scale:.86;opacity:0;transition:translate 1.3s var(--ease) .4s,rotate 1.3s var(--ease) .4s,scale 1.3s cubic-bezier(.34,1.4,.64,1) .4s,opacity .6s ease .4s}
.rv-wd-js .rv-wd-vis.is-in .rv-wd-ph{translate:0 0;rotate:0deg;scale:1;opacity:1}
/* уведомления */
.rv-wd-toast{position:absolute;z-index:3;display:flex;align-items:center;gap:12px;padding:12px 16px 12px 12px;border-radius:16px;
  background:rgba(12,14,52,.9);border:1px solid rgba(255,255,255,.14);box-shadow:0 20px 50px rgba(0,0,20,.5);-webkit-backdrop-filter:blur(12px);backdrop-filter:blur(12px)}
.rv-wd-toast__ic{display:grid;place-items:center;width:38px;height:38px;border-radius:12px;flex:none}
.rv-wd-toast__ic svg{width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:1.9;stroke-linecap:round;stroke-linejoin:round}
.rv-wd-toast small{display:block;font-family:var(--mono);font-size:.56rem;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.62)}
.rv-wd-toast b{display:block;margin-top:2px;font-family:var(--h);font-weight:800;font-size:.98rem;letter-spacing:-.01em}
.rv-wd-toast--lead{left:-4%;bottom:4%;opacity:0;animation:rvWdToast 6s var(--ease) 1.7s infinite}
.rv-wd-toast--lead .rv-wd-toast__ic{background:rgba(42,171,238,.16);color:#58c5ff}
.rv-wd-toast--speed{left:4%;top:0;animation:rvWdBob 5s ease-in-out infinite alternate}
.rv-wd-toast--speed .rv-wd-toast__ic{background:rgba(91,217,138,.14);color:var(--ok)}
.rv-wd-toast--speed b{color:var(--ok)}
.rv-wd-toast--seo{right:4%;bottom:26%;animation:rvWdBob 6s ease-in-out 1s infinite alternate}
.rv-wd-toast--seo .rv-wd-toast__ic{background:rgba(245,197,66,.14);color:var(--gold)}
@keyframes rvWdBob{to{transform:translateY(-12px)}}
@keyframes rvWdToast{0%{opacity:0;transform:translateY(16px) scale(.96)}8%,70%{opacity:1;transform:none}82%,100%{opacity:0;transform:translateY(-8px)}}

/* ── WHY ──────────────────────────────────────────────────────────── */
.rv-wd-why__grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}
.rv-wd-card{position:relative;overflow:hidden;isolation:isolate;padding:26px 24px 28px;border-radius:24px;border:1px solid rgba(255,255,255,.1);
  background:linear-gradient(160deg,rgba(255,255,255,.06),rgba(255,255,255,.015) 70%);transition:border-color .4s ease,transform .5s var(--ease)}
.rv-wd-card::before{content:"";position:absolute;inset:0;z-index:-1;opacity:0;transition:opacity .4s ease;
  background:radial-gradient(320px circle at var(--mx,50%) var(--my,0%),rgba(245,197,66,.12),transparent 60%)}
.rv-wd-card:hover{border-color:rgba(245,197,66,.35);transform:translateY(-4px)}
.rv-wd-card:hover::before{opacity:1}
.rv-wd-card__ic{display:grid;place-items:center;width:52px;height:52px;border-radius:16px;border:1px solid rgba(245,197,66,.35);background:linear-gradient(145deg,rgba(245,197,66,.2),rgba(245,197,66,.04))}
.rv-wd-card__ic svg{width:22px;height:22px;fill:none;stroke:var(--gold);stroke-width:1.7;stroke-linecap:round;stroke-linejoin:round}
.rv-wd-card__n{position:absolute;top:22px;right:22px;font-family:var(--mono);font-size:.62rem;color:rgba(255,255,255,.4)}
.rv-wd-card h3{margin-top:26px!important;font-family:var(--h);font-weight:800;font-size:clamp(17px,1.35vw,21px);line-height:1.2;letter-spacing:-.015em}
.rv-wd-card p{margin-top:10px!important;font-size:.92rem;line-height:1.55;color:rgba(255,255,255,.76)}

/* ── OFFER ────────────────────────────────────────────────────────── */
.rv-wd-offer__box{position:relative;overflow:hidden;isolation:isolate;display:grid;grid-template-columns:minmax(0,1.1fr) minmax(0,.9fr);gap:clamp(30px,4vw,64px);align-items:center;
  padding:clamp(34px,4.5vw,70px);border-radius:36px;border:1px solid rgba(245,197,66,.28);
  background:radial-gradient(90% 120% at 100% 0%,rgba(245,197,66,.16),transparent 55%),linear-gradient(150deg,#12145a,#070838 70%);
  box-shadow:0 50px 120px rgba(0,0,20,.5),inset 0 1px 0 rgba(255,255,255,.08)}
.rv-wd-offer__box::before{content:"$<?php echo (int) $rv_wd_prices['start']; ?>";position:absolute;z-index:-1;right:-2%;bottom:-18%;font-family:var(--h);font-weight:800;font-size:clamp(160px,22vw,340px);line-height:1;letter-spacing:-.06em;
  color:transparent;-webkit-text-stroke:1px rgba(245,197,66,.14)}
.rv-wd-offer__box::after{content:"";position:absolute;left:10%;right:10%;top:0;height:1px;background:linear-gradient(90deg,transparent,var(--gold),transparent)}
.rv-wd-offer .rv-wd__chip{border-color:rgba(245,197,66,.35);color:var(--gold)}
.rv-wd-offer h2{margin-top:18px!important;font-family:var(--h);font-weight:800;font-size:clamp(34px,4.4vw,68px);line-height:1;letter-spacing:-.04em;text-transform:uppercase}
.rv-wd-offer h2 em{color:var(--gold);text-shadow:0 0 40px rgba(245,197,66,.4)}
.rv-wd-offer__lead{margin-top:20px!important;max-width:46ch;font-size:1.02rem;line-height:1.6;color:rgba(255,255,255,.84)}
.rv-wd-offer__meta{display:flex;flex-wrap:wrap;gap:10px 22px;margin-top:22px}
.rv-wd-offer__meta span{display:inline-flex;align-items:center;gap:8px;font-family:var(--mono);font-size:.68rem;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.8)}
.rv-wd-offer__meta span::before{content:"";width:6px;height:6px;border-radius:50%;background:var(--ok);box-shadow:0 0 8px var(--ok)}
.rv-wd-offer .rv-wd__btn{margin-top:30px}
.rv-wd-inc{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.rv-wd-inc li{display:flex;align-items:center;gap:12px;padding:14px 16px;border-radius:16px;border:1px solid rgba(255,255,255,.1);background:rgba(3,0,50,.5);
  font-size:.92rem;line-height:1.35;-webkit-backdrop-filter:blur(8px);backdrop-filter:blur(8px)}
.rv-wd-inc svg{flex:none;width:18px;height:18px;padding:3px;border-radius:50%;background:var(--gold);fill:none;stroke:var(--ink);stroke-width:3;stroke-linecap:round;stroke-linejoin:round}

/* ── PACKAGES ─────────────────────────────────────────────────────── */
.rv-wd-pk__grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;align-items:stretch}
.rv-wd-pk{position:relative;display:flex;flex-direction:column;padding:30px 28px;border-radius:28px;border:1px solid rgba(255,255,255,.1);
  background:linear-gradient(160deg,rgba(255,255,255,.06),rgba(255,255,255,.015) 70%);transition:transform .5s var(--ease),border-color .4s ease,box-shadow .4s ease}
.rv-wd-pk:hover{transform:translateY(-6px);border-color:rgba(245,197,66,.4);box-shadow:0 30px 70px rgba(0,0,20,.4)}
.rv-wd-pk.is-pop{border-color:rgba(245,197,66,.5);background:radial-gradient(90% 60% at 50% 0%,rgba(245,197,66,.14),transparent 70%),linear-gradient(160deg,rgba(24,26,86,.96),rgba(8,9,42,.98))}
.rv-wd-pk__badge{position:absolute;top:-12px;left:28px;padding:6px 12px;border-radius:99px;background:var(--gold);color:var(--ink);font-family:var(--mono);font-size:.6rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;box-shadow:0 8px 24px rgba(245,197,66,.4)}
.rv-wd-pk__badge--pop{background:#fff}
.rv-wd-pk h3{font-family:var(--h);font-weight:800;font-size:1.5rem;letter-spacing:-.02em}
.rv-wd-pk__d{margin-top:8px!important;min-height:3em;font-size:.92rem;line-height:1.5;color:rgba(255,255,255,.72)}
.rv-wd-pk__price{display:flex;align-items:baseline;gap:8px;margin-top:22px;padding-bottom:22px;border-bottom:1px solid var(--line)}
.rv-wd-pk__price small{font-family:var(--mono);font-size:.66rem;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.62)}
.rv-wd-pk__price b{font-family:var(--h);font-weight:800;font-size:clamp(36px,3.4vw,50px);letter-spacing:-.04em;color:var(--gold);line-height:1}
.rv-wd-pk ul{display:grid;gap:12px;margin:22px 0 26px}
.rv-wd-pk li{display:flex;gap:11px;font-size:.94rem;line-height:1.4}
.rv-wd-pk li svg{flex:none;margin-top:2px;width:16px;height:16px;fill:none;stroke:var(--gold);stroke-width:2.4;stroke-linecap:round;stroke-linejoin:round}
.rv-wd-pk__time{margin-top:auto;margin-bottom:16px;font-family:var(--mono);font-size:.66rem;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.62)}
.rv-wd-pk .rv-wd__btn{width:100%}

/* ── CALCULATOR ───────────────────────────────────────────────────── */
.rv-wd-ca__box{display:grid;grid-template-columns:minmax(0,1.5fr) minmax(300px,.8fr);border-radius:32px;overflow:hidden;border:1px solid rgba(255,255,255,.12);
  background:linear-gradient(160deg,rgba(20,24,72,.94),rgba(7,8,40,.98));box-shadow:0 50px 120px rgba(0,0,20,.45)}
.rv-wd-ca__form{padding:clamp(26px,3.4vw,50px)}
.rv-wd-ca__step{display:flex;align-items:center;gap:12px;margin-bottom:18px;font-family:var(--mono);font-size:.7rem;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.8)}
.rv-wd-ca__step b{display:grid;place-items:center;width:28px;height:28px;border-radius:50%;background:rgba(245,197,66,.14);border:1px solid rgba(245,197,66,.4);color:var(--gold);font-weight:500}
.rv-wd-ca__types{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin:0 0 36px;padding-top:10px}
.rv-wd-ca__type{position:relative;display:flex;flex-direction:column;align-items:flex-start;gap:4px;padding:18px 18px 20px;border-radius:20px;text-align:left;
  border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.035);transition:border-color .3s ease,background .3s ease,transform .35s var(--ease)}
.rv-wd-ca__type:hover{border-color:rgba(245,197,66,.45);transform:translateY(-2px)}
.rv-wd-ca__type::after{content:"";position:absolute;top:18px;right:18px;width:18px;height:18px;border-radius:50%;border:1px solid rgba(255,255,255,.3);transition:all .3s ease}
.rv-wd-ca__type.is-on{border-color:var(--gold);background:radial-gradient(120% 90% at 0% 0%,rgba(245,197,66,.16),transparent 60%),rgba(255,255,255,.04)}
.rv-wd-ca__type.is-on::after{border-color:var(--gold);background:var(--gold);box-shadow:inset 0 0 0 4px #0b0c3e,0 0 12px rgba(245,197,66,.6)}
.rv-wd-ca__type strong{font-family:var(--h);font-weight:800;font-size:1.02rem;letter-spacing:-.01em;padding-right:24px}
.rv-wd-ca__type small{font-size:.8rem;color:rgba(255,255,255,.62)}
.rv-wd-ca__type em{margin-top:10px;font-family:var(--h);font-weight:800;font-size:1.35rem;letter-spacing:-.02em}
.rv-wd-ca__type.is-on em{color:var(--gold)}
.rv-wd-ca__type i{position:absolute;left:18px;top:-10px;padding:4px 9px;border-radius:99px;background:var(--gold);color:var(--ink);font-family:var(--mono);font-size:.54rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase}
.rv-wd-ca__opts{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.rv-wd-ca__opt{display:flex;align-items:center;gap:14px;padding:15px 16px;border-radius:16px;text-align:left;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.03);
  font-size:.92rem;line-height:1.35;transition:border-color .3s ease,background .3s ease}
.rv-wd-ca__opt:hover{border-color:rgba(245,197,66,.4)}
.rv-wd-ca__opt span{flex:none;display:grid;place-items:center;width:22px;height:22px;border-radius:7px;border:1px solid rgba(255,255,255,.3);transition:all .25s ease}
.rv-wd-ca__opt span svg{width:13px;height:13px;fill:none;stroke:var(--ink);stroke-width:3;stroke-linecap:round;stroke-linejoin:round;transform:scale(0);transition:transform .25s cubic-bezier(.34,1.6,.64,1)}
.rv-wd-ca__opt strong{flex:1;font-weight:500}
.rv-wd-ca__opt em{font-family:var(--mono);font-size:.78rem;color:rgba(255,255,255,.7);white-space:nowrap}
.rv-wd-ca__opt.is-on{border-color:rgba(245,197,66,.6);background:rgba(245,197,66,.07)}
.rv-wd-ca__opt.is-on span{background:var(--gold);border-color:var(--gold)}
.rv-wd-ca__opt.is-on span svg{transform:scale(1)}
.rv-wd-ca__opt.is-on em{color:var(--gold)}
.rv-wd-ca__sum{position:relative;display:flex;flex-direction:column;padding:clamp(26px,3vw,44px);border-left:1px solid var(--line);
  background:radial-gradient(100% 60% at 100% 0%,rgba(245,197,66,.12),transparent 60%),rgba(3,0,50,.5)}
.rv-wd-ca__sticky{position:sticky;top:110px}
.rv-wd-ca__lbl{font-family:var(--mono);font-size:.64rem;letter-spacing:.16em;text-transform:uppercase;color:rgba(255,255,255,.7)}
.rv-wd-ca__total{display:flex;align-items:flex-start;gap:4px;margin-top:12px;font-family:var(--h);font-weight:800;letter-spacing:-.05em;line-height:.95;color:var(--gold);text-shadow:0 0 40px rgba(245,197,66,.35)}
.rv-wd-ca__total small{margin-top:.3em;font-size:clamp(20px,1.8vw,28px);color:rgba(245,197,66,.8)}
.rv-wd-ca__total b{font-size:clamp(52px,5vw,78px);font-variant-numeric:tabular-nums}
.rv-wd-ca__total.is-bump{animation:rvWdBump .45s cubic-bezier(.34,1.6,.64,1)}
@keyframes rvWdBump{40%{transform:scale(1.05)}}
.rv-wd-ca__tag{align-self:flex-start;margin-top:14px;padding:7px 13px;border-radius:99px;background:rgba(255,255,255,.07);font-size:.84rem}
.rv-wd-ca__rows{margin-top:26px;padding-top:22px;border-top:1px solid var(--line);display:grid;gap:12px}
.rv-wd-ca__rows div{display:flex;justify-content:space-between;gap:10px;font-size:.98rem;color:rgba(255,255,255,.82)}
.rv-wd-ca__rows b{font-family:var(--h);font-weight:800;color:#fff;font-variant-numeric:tabular-nums}
.rv-wd-ca__incl{margin-top:24px;padding-top:22px;border-top:1px solid var(--line);display:grid;gap:12px}
.rv-wd-ca__incl li{display:flex;align-items:center;gap:12px;font-size:.92rem;color:rgba(255,255,255,.84)}
.rv-wd-ca__incl svg{flex:none;width:18px;height:18px;padding:3px;border-radius:50%;background:rgba(245,197,66,.16);fill:none;stroke:var(--gold);stroke-width:2.6;stroke-linecap:round;stroke-linejoin:round}
.rv-wd-ca__sum .rv-wd__btn{width:100%;margin-top:28px}
.rv-wd-ca__note{margin-top:14px!important;font-size:.8rem;line-height:1.5;color:rgba(255,255,255,.6)}

/* ── PROCESS ──────────────────────────────────────────────────────── */
.rv-wd-pr__list{position:relative;display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:14px}
.rv-wd-pr__list::before{content:"";position:absolute;left:0;right:0;top:27px;height:1px;background:linear-gradient(90deg,rgba(245,197,66,.6),rgba(255,255,255,.1))}
.rv-wd-pr__list li{position:relative;padding-top:64px}
.rv-wd-pr__list li::before{content:attr(data-n);position:absolute;top:0;left:0;display:grid;place-items:center;width:54px;height:54px;border-radius:50%;
  background:#0a0b40;border:1px solid rgba(245,197,66,.45);font-family:var(--mono);font-size:.8rem;color:var(--gold);box-shadow:0 0 0 6px #030032}
.rv-wd-pr__list small{display:block;font-family:var(--mono);font-size:.62rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold)}
.rv-wd-pr__list h3{margin-top:10px!important;font-family:var(--h);font-weight:800;font-size:1.25rem;letter-spacing:-.015em}
.rv-wd-pr__list p{margin-top:8px!important;font-size:.9rem;line-height:1.55;color:rgba(255,255,255,.74)}

/* ── CASES ────────────────────────────────────────────────────────── */
.rv-wd-cs__grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}
.rv-wd-case{position:relative;overflow:hidden;isolation:isolate;display:flex;flex-direction:column;min-height:320px;padding:24px;border-radius:26px;
  border:1px solid rgba(255,255,255,.1);background:linear-gradient(160deg,rgba(255,255,255,.06),rgba(255,255,255,.015) 70%);transition:transform .5s var(--ease),border-color .4s ease}
.rv-wd-case:hover{transform:translateY(-6px);border-color:rgba(245,197,66,.4)}
.rv-wd-case__top{display:flex;justify-content:space-between;gap:10px;font-family:var(--mono);font-size:.62rem;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.66)}
.rv-wd-case__top b{color:var(--gold);font-weight:500}
.rv-wd-case h3{margin-top:18px!important;font-family:var(--h);font-weight:800;font-size:1.6rem;letter-spacing:-.03em}
.rv-wd-case__url{margin-top:4px;font-family:var(--mono);font-size:.72rem;color:rgba(255,255,255,.62)}
.rv-wd-case__spark{position:absolute;z-index:-1;left:0;right:0;top:36%;height:30%;width:100%;overflow:visible;opacity:.9}
.rv-wd-case__spark .ln{fill:none;stroke:var(--gold);stroke-width:2.2;stroke-linecap:round;stroke-linejoin:round;stroke-dasharray:1;stroke-dashoffset:1;filter:drop-shadow(0 0 6px rgba(245,197,66,.6));transition:stroke-dashoffset 1.8s var(--ease) .2s}
.rv-wd-case__spark .ar{fill:url(#rvWdSpark);opacity:0;transition:opacity 1.2s ease .9s}
.rv-wd-case.is-in .rv-wd-case__spark .ln{stroke-dashoffset:0}
.rv-wd-case.is-in .rv-wd-case__spark .ar{opacity:1}
.rv-wd-case__res{margin-top:auto;display:flex;align-items:baseline;gap:12px}
.rv-wd-case__res b{font-family:var(--h);font-weight:800;font-size:clamp(40px,3.6vw,56px);letter-spacing:-.04em;color:var(--gold);text-shadow:0 0 30px rgba(245,197,66,.35);line-height:1}
.rv-wd-case__res span{font-family:var(--mono);font-size:.66rem;letter-spacing:.1em;text-transform:uppercase;max-width:12ch;line-height:1.4}

/* ── GUARANTEES ───────────────────────────────────────────────────── */
.rv-wd-gu__grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:0;border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
.rv-wd-gu__grid li{padding:30px 26px 34px 0}
.rv-wd-gu__grid li + li{padding-left:26px;border-left:1px solid var(--line)}
.rv-wd-gu__grid svg{width:30px;height:30px;padding:6px;border-radius:50%;background:rgba(91,217,138,.12);fill:none;stroke:var(--ok);stroke-width:2.4;stroke-linecap:round;stroke-linejoin:round}
.rv-wd-gu__grid h3{margin-top:18px!important;font-family:var(--h);font-weight:800;font-size:1.15rem;letter-spacing:-.015em}
.rv-wd-gu__grid p{margin-top:8px!important;font-size:.9rem;line-height:1.55;color:rgba(255,255,255,.74)}

/* ── FAQ ──────────────────────────────────────────────────────────── */
.rv-wd-fq .rv-wd__in{display:grid;grid-template-columns:minmax(0,.7fr) minmax(0,1.3fr);gap:clamp(30px,5vw,90px);align-items:start}
.rv-wd-fq__list{display:grid;gap:10px}
.rv-wd-fq details{border-radius:20px;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.035);transition:border-color .3s ease,background .3s ease}
.rv-wd-fq details[open]{border-color:rgba(245,197,66,.4);background:rgba(245,197,66,.05)}
.rv-wd-fq summary{list-style:none;display:flex;justify-content:space-between;align-items:center;gap:16px;padding:20px 22px;cursor:pointer;
  font-family:var(--h);font-weight:700;font-size:1.05rem;letter-spacing:-.01em}
.rv-wd-fq summary::-webkit-details-marker{display:none}
.rv-wd-fq summary i{flex:none;position:relative;width:30px;height:30px;border-radius:50%;border:1px solid rgba(255,255,255,.2);transition:transform .4s var(--ease),background .3s ease,border-color .3s ease}
.rv-wd-fq summary i::before,.rv-wd-fq summary i::after{content:"";position:absolute;left:50%;top:50%;width:11px;height:1.5px;margin:-.75px 0 0 -5.5px;background:#fff}
.rv-wd-fq summary i::after{transform:rotate(90deg)}
.rv-wd-fq details[open] summary i{transform:rotate(45deg);background:var(--gold);border-color:var(--gold)}
.rv-wd-fq details[open] summary i::before,.rv-wd-fq details[open] summary i::after{background:var(--ink)}
.rv-wd-fq details p{padding:0 22px 22px;font-size:.95rem;line-height:1.65;color:rgba(255,255,255,.8)}

/* ── FINAL CTA ────────────────────────────────────────────────────── */
.rv-wd-fn .rv-wd__in{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,.9fr);gap:clamp(30px,5vw,80px);align-items:center}
.rv-wd-fn__lead{margin-top:18px!important;max-width:44ch;font-size:1.02rem;line-height:1.6;color:rgba(255,255,255,.84)}
.rv-wd-fn__or{margin-top:34px!important;font-family:var(--mono);font-size:.64rem;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.6)}
.rv-wd-ch{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;margin-top:14px}
.rv-wd-ch a{display:flex;flex-direction:column;align-items:center;gap:10px;padding:18px 10px;border-radius:18px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.035);text-align:center;
  transition:transform .4s var(--ease),border-color .3s ease}
.rv-wd-ch a:hover{transform:translateY(-3px);border-color:rgba(245,197,66,.5)}
.rv-wd-ch span{display:grid;place-items:center;width:46px;height:46px;border-radius:50%;border:1px solid rgba(245,197,66,.45);background:rgba(245,197,66,.08)}
.rv-wd-ch svg{width:20px;height:20px;fill:none;stroke:var(--gold);stroke-width:1.7;stroke-linecap:round;stroke-linejoin:round}
.rv-wd-ch small{font-family:var(--mono);font-size:.58rem;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.7)}
.rv-wd-ch b{font-size:.84rem;font-weight:600}
.rv-wd-fn__card{position:relative;padding:clamp(24px,3vw,40px);border-radius:30px;border:1px solid rgba(255,255,255,.16);
  background:linear-gradient(160deg,rgba(255,255,255,.1),rgba(255,255,255,.03) 70%);box-shadow:0 40px 90px rgba(0,0,0,.45);-webkit-backdrop-filter:blur(20px);backdrop-filter:blur(20px)}
.rv-wd-fn__card::before{content:"";position:absolute;left:30px;right:30px;top:0;height:1px;background:linear-gradient(90deg,transparent,var(--gold),transparent)}
.rv-wd-fn__card .wpcf7-form p{margin:0 0 12px!important}
.rv-wd-fn__card input[type="text"],.rv-wd-fn__card input[type="tel"],.rv-wd-fn__card input[type="email"],.rv-wd-fn__card textarea{
  width:100%;min-height:62px;padding:0 22px;border-radius:18px;border:1px solid rgba(255,255,255,.16);background:rgba(3,0,50,.55);color:#fff;
  font:inherit;font-size:1rem;-webkit-appearance:none;appearance:none;transition:border-color .3s ease,box-shadow .3s ease}
.rv-wd-fn__card textarea{min-height:110px;padding:16px 22px;resize:vertical}
.rv-wd-fn__card input::placeholder,.rv-wd-fn__card textarea::placeholder{color:rgba(255,255,255,.5)}
.rv-wd-fn__card input:focus,.rv-wd-fn__card textarea:focus{outline:none;border-color:rgba(245,197,66,.6);box-shadow:0 0 0 4px rgba(245,197,66,.12)}
.rv-wd-fn__card input.wpcf7-submit{min-height:62px;margin-top:4px;border:0;border-radius:999px;cursor:pointer;color:#030032;
  font-weight:800;font-size:.9rem;letter-spacing:.08em;text-transform:uppercase;background:linear-gradient(135deg,#F5D488 0%,#F5C542 48%,#DFAE3F 100%);
  box-shadow:0 12px 34px rgba(245,197,66,.3);transition:transform .3s ease,box-shadow .3s ease}
.rv-wd-fn__card input.wpcf7-submit:hover{transform:translateY(-2px);box-shadow:0 18px 44px rgba(245,197,66,.42)}
.rv-wd-fn__card .wpcf7-spinner{position:absolute}
.rv-wd-fn__card .wpcf7-not-valid-tip{margin-top:6px;font-size:.8rem;color:#FFB4B4}
.rv-wd-fn__card .wpcf7-response-output{margin:12px 0 0!important;padding:12px 14px!important;border-radius:14px;font-size:.88rem}
.rv-wd-fn__card .wpcf7-form-control-wrap{display:block;width:100%!important}
.rv-wd-fn__card input.wpcf7-submit{width:100%!important;max-width:none!important}
.rv-wd-fn__pp{margin-top:12px!important;font-size:.78rem;line-height:1.5;color:rgba(255,255,255,.7)}
.rv-wd-fn__pp a{color:var(--gold);border-bottom:1px solid rgba(245,197,66,.5)}

/* липкая панель на телефоне */
.rv-wd-sticky{display:none}

/* ── АДАПТИВ ──────────────────────────────────────────────────────── */
@media(max-width:1100px){
  .rv-wd-hero .rv-wd__in,.rv-wd-offer__box,.rv-wd-fn .rv-wd__in,.rv-wd-fq .rv-wd__in{grid-template-columns:1fr}
  .rv-wd-vis{max-width:620px;width:100%;margin:10px auto 0}
  .rv-wd-why__grid,.rv-wd-gu__grid{grid-template-columns:repeat(2,minmax(0,1fr))}
  .rv-wd-gu__grid li:nth-child(3){padding-left:0;border-left:0}
  .rv-wd-gu__grid li:nth-child(n+3){border-top:1px solid var(--line)}
  .rv-wd-pk__grid,.rv-wd-cs__grid{grid-template-columns:1fr 1fr}
  .rv-wd-ca__box{grid-template-columns:1fr}
  .rv-wd-ca__sum{border-left:0;border-top:1px solid var(--line)}
  .rv-wd-pr__list{grid-template-columns:repeat(3,minmax(0,1fr));row-gap:34px}
  .rv-wd-pr__list::before{display:none}
}
@media(max-width:700px){
  .rv-wd__sec{padding-top:56px;padding-bottom:56px}
  .rv-wd-hero{padding-top:26px}
  .rv-wd-hero h1{font-size:clamp(28px,8.6vw,38px)}
  .rv-wd-hero__cta .rv-wd__btn{width:100%}
  .rv-wd-stats{grid-template-columns:1fr 1fr;row-gap:16px}
  .rv-wd-vis{aspect-ratio:1.12;margin-bottom:-20px}
  .rv-wd-bw{right:14%}
  .rv-wd-ph{width:30vw;max-width:124px;min-width:96px}
  .rv-wd-bw__nav i{display:none}
  .rv-wd-toast{padding:9px 12px 9px 9px;gap:9px;border-radius:13px}
  .rv-wd-toast__ic{width:30px;height:30px;border-radius:9px}
  .rv-wd-toast b{font-size:.84rem}
  .rv-wd-toast--lead{left:0}
  .rv-wd-why__grid,.rv-wd-pk__grid,.rv-wd-cs__grid,.rv-wd-inc,.rv-wd-ca__opts,.rv-wd-ca__types{grid-template-columns:1fr}
  .rv-wd-gu__grid{grid-template-columns:1fr}
  .rv-wd-gu__grid li,.rv-wd-gu__grid li + li{padding:22px 0;border-left:0;border-top:1px solid var(--line)}
  .rv-wd-gu__grid li:first-child{border-top:0}
  .rv-wd-offer__box{padding:28px 20px;border-radius:26px}
  .rv-wd-offer .rv-wd__btn{width:100%}
  .rv-wd-ca__form,.rv-wd-ca__sum{padding:22px 18px}
  .rv-wd-ca__types{gap:10px}
  .rv-wd-ca__type{flex-direction:row;flex-wrap:wrap;align-items:center;padding:16px}
  .rv-wd-ca__type small{flex:1 1 100%;order:2}
  .rv-wd-ca__type em{margin:0 0 0 auto;order:1;font-size:1.15rem;padding-right:30px}
  .rv-wd-ca__type::after{top:50%;margin-top:-9px;top:22px}
  .rv-wd-pr__list{grid-template-columns:1fr;row-gap:0}
  .rv-wd-pr__list li{padding:0 0 26px 74px;min-height:80px}
  .rv-wd-pr__list li::after{content:"";position:absolute;left:27px;top:56px;bottom:0;width:1px;background:rgba(245,197,66,.3)}
  .rv-wd-pr__list li:last-child::after{display:none}
  .rv-wd-fq summary{padding:18px;font-size:.98rem}
  .rv-wd-fq details p{padding:0 18px 18px}
  .rv-wd-sticky{position:fixed;z-index:990;left:12px;right:12px;bottom:calc(12px + env(safe-area-inset-bottom));display:flex;align-items:center;gap:12px;padding:8px 8px 8px 18px;border-radius:999px;
    background:rgba(8,9,46,.92);border:1px solid rgba(245,197,66,.35);box-shadow:0 18px 40px rgba(0,0,0,.5);-webkit-backdrop-filter:blur(14px);backdrop-filter:blur(14px);
    transform:translateY(140%);transition:transform .5s var(--ease)}
  .rv-wd-sticky.is-on{transform:none}
  .rv-wd-sticky span{flex:1;font-family:var(--h);font-weight:800;font-size:.95rem;letter-spacing:-.01em}
  .rv-wd-sticky span b{color:var(--gold)}
  .rv-wd-sticky a{display:inline-flex;align-items:center;min-height:44px;padding:0 20px;border-radius:999px;background:var(--gold);color:var(--ink);font-weight:800;font-size:.74rem;letter-spacing:.08em;text-transform:uppercase}
}

/* ======================================================================
   ТЕЛЕФОН: каруселі (як «Revive у цифрах» на головній) + компактні блоки
   ====================================================================== */
.rv-car-nav,.rv-wd-ca__more{display:none}
@media(max-width:700px){
  .rv-wd__sec{padding-top:44px;padding-bottom:44px}
  .rv-wd__head{margin-bottom:16px;gap:12px}
  .rv-wd__h2{font-size:clamp(26px,7.6vw,32px)}
  .rv-wd__head .rv-wd__btn{display:none}

  /* навігація каруселі */
  .rv-car-nav{display:flex;align-items:center;gap:16px;margin:0 0 14px}
  .rv-car-nav__count{display:flex;align-items:center;gap:6px;font-family:var(--mono);font-size:.8rem;letter-spacing:.08em;color:#fff}
  .rv-car-nav__count b{position:relative;display:grid;place-items:center;width:40px;height:40px;border-radius:50%;border:1px solid rgba(245,197,66,.55);font-weight:500}
  .rv-car-nav__count b::before{content:"";position:absolute;top:5px;left:50%;width:5px;height:5px;margin-left:-2.5px;border-radius:50%;background:var(--gold);box-shadow:0 0 8px var(--gold)}
  .rv-car-nav__count span{opacity:.75}
  .rv-car-nav__bar{position:relative;flex:1;max-width:110px;height:2px;border-radius:2px;background:rgba(255,255,255,.14);overflow:hidden}
  .rv-car-nav__bar i{position:absolute;inset:0;background:var(--gold);transform-origin:left;transform:scaleX(var(--p,.25));transition:transform .45s var(--ease)}
  .rv-car-nav__next{display:inline-flex;align-items:center;gap:8px;margin-left:auto;font-family:var(--mono);font-size:.7rem;letter-spacing:.16em;text-transform:uppercase;color:#fff;line-height:1}
  .rv-car-nav__next svg{width:16px;height:16px;fill:none;stroke:var(--gold);stroke-width:2;stroke-linecap:round;stroke-linejoin:round;animation:rvCarNudge 1.6s ease-in-out infinite}
  @keyframes rvCarNudge{50%{transform:translateX(4px)}}

  /* стрічка */
  .rv-wd [data-rv-car]{display:flex!important;gap:12px;overflow-x:auto;overflow-y:hidden;scroll-snap-type:x mandatory;scroll-padding-left:20px;
    margin:0 -20px;padding:4px 20px 8px;-webkit-overflow-scrolling:touch;overscroll-behavior-x:contain;scrollbar-width:none;border:0!important}
  .rv-wd [data-rv-car]::-webkit-scrollbar{display:none}
  .rv-wd [data-rv-car]::after{content:"";flex:0 0 8px}
  .rv-wd [data-rv-car] > *{flex:0 0 84%;scroll-snap-align:start;scroll-snap-stop:always}
  .rv-wd [data-rv-car]::before{display:none!important}

  /* «Сайт, що продає» */
  .rv-wd-why__grid .rv-wd-card{min-height:220px;padding:22px 20px}
  .rv-wd-why__grid .rv-wd-card h3{margin-top:20px!important}

  /* «Від брифу до запуску» — картки */
  .rv-wd-pr__list li,.rv-wd-pr__list li + li{min-height:200px;padding:84px 20px 22px!important;border-radius:22px;border:1px solid rgba(255,255,255,.1);
    background:linear-gradient(160deg,rgba(255,255,255,.06),rgba(255,255,255,.015) 70%)}
  .rv-wd-pr__list li::before{top:20px;left:20px;box-shadow:none}
  .rv-wd-pr__list li::after{display:none!important}

  /* «Сайти, що працюють» */
  .rv-wd-cs__grid .rv-wd-case{min-height:260px}

  /* «Без сюрпризів» — картки */
  .rv-wd-gu__grid li,.rv-wd-gu__grid li + li{min-height:190px;padding:22px 20px!important;border:1px solid rgba(255,255,255,.1)!important;border-radius:22px;
    background:linear-gradient(160deg,rgba(255,255,255,.06),rgba(255,255,255,.015) 70%)}

  /* HERO компактніше */
  .rv-wd-hero h1{margin-top:16px!important}
  .rv-wd-hero__lead{margin-top:14px!important;font-size:15px}
  .rv-wd-hero__cta{margin-top:22px;gap:10px}
  .rv-wd-hero__cta .rv-wd__btn{min-height:54px}
  .rv-wd-stats{grid-template-columns:repeat(4,minmax(0,1fr));margin-top:24px;column-gap:8px}
  .rv-wd-stats div{padding:14px 0 0}
  .rv-wd-stats b{font-size:22px}
  .rv-wd-stats span{font-size:.5rem;letter-spacing:.06em}

  /* спецпропозиція */
  .rv-wd-offer__box{gap:20px}
  .rv-wd-offer h2{font-size:clamp(30px,9vw,38px)}
  .rv-wd-offer__lead{margin-top:12px!important;font-size:.92rem}
  .rv-wd-offer__meta{margin-top:14px}
  .rv-wd-offer .rv-wd__btn{margin-top:20px}
  .rv-wd-inc{grid-template-columns:1fr 1fr;gap:8px}
  .rv-wd-inc li{padding:10px;gap:8px;font-size:.78rem;border-radius:12px}
  .rv-wd-inc svg{width:15px;height:15px}

  /* формати: компактні картки */
  .rv-wd-pk__grid{gap:18px}
  .rv-wd-pk{padding:22px 18px 18px}
  .rv-wd-pk h3{font-size:1.25rem;padding-right:120px}
  .rv-wd-pk__d{min-height:0;font-size:.84rem}
  .rv-wd-pk__price{position:absolute;top:22px;right:18px;margin:0;padding:0;border:0}
  .rv-wd-pk__price b{font-size:26px}
  .rv-wd-pk ul{grid-template-columns:1fr 1fr;gap:8px 10px;margin:14px 0 14px}
  .rv-wd-pk li{font-size:.78rem;gap:7px}
  .rv-wd-pk li svg{width:13px;height:13px}
  .rv-wd-pk__time{display:none}
  .rv-wd-pk .rv-wd__btn{min-height:48px}

  /* калькулятор: типи в ряд, 4 опції + «Усі опції» */
  .rv-wd-ca__types{grid-template-columns:repeat(3,minmax(0,1fr));gap:8px;margin-bottom:22px}
  .rv-wd-ca__type{flex-direction:column;align-items:flex-start;padding:22px 10px 12px;border-radius:14px}
  .rv-wd-ca__type{min-width:0}
  .rv-wd-ca__type strong{max-width:100%;font-size:.72rem;padding-right:0;line-height:1.2;overflow-wrap:anywhere;hyphens:auto}
  .rv-wd-ca__type em{font-size:.88rem!important}
  .rv-wd-ca__type small{display:none}
  .rv-wd-ca__type em{order:0;margin:6px 0 0;padding:0;font-size:.95rem}
  .rv-wd-ca__type::after{top:8px;right:8px;margin:0;width:14px;height:14px}
  .rv-wd-ca__type i{left:10px;top:-9px;font-size:.48rem}
  .rv-wd-ca__step{margin-bottom:12px}
  .rv-wd-ca__opts{gap:8px}
  .rv-wd-ca__opt{padding:11px 12px;font-size:.84rem;gap:10px}
  .rv-wd-ca__opts:not(.is-all) .rv-wd-ca__opt:nth-child(n+5){display:none}
  .rv-wd-ca__more{display:inline-flex;align-items:center;gap:8px;margin-top:12px;font-family:var(--mono);font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold)}
  .rv-wd-ca__more span{padding:3px 7px;border-radius:99px;background:rgba(245,197,66,.14)}
  .rv-wd-ca__more.is-hidden{display:none}
  .rv-wd-ca__total b{font-size:48px}
  .rv-wd-ca__rows{margin-top:16px;padding-top:14px;gap:8px}
  .rv-wd-ca__incl{display:none}
  .rv-wd-ca__sum .rv-wd__btn{margin-top:18px}

  /* FAQ */
  .rv-wd-fq .rv-wd__in{gap:16px}
  .rv-wd-fq__list{gap:8px}
  .rv-wd-fq summary{padding:14px 16px;font-size:.92rem}
  .rv-wd-fq summary i{width:26px;height:26px}
  .rv-wd-fq details p{padding:0 16px 16px;font-size:.88rem}
}
@media(prefers-reduced-motion:reduce){
  .rv-wd *,.rv-wd *::before,.rv-wd *::after{animation:none!important;transition:none!important}
  .rv-wd-js [data-rv]{opacity:1;transform:none}
  .rv-wd-case__spark .ln{stroke-dashoffset:0}.rv-wd-case__spark .ar{opacity:1}
}

/* Marketing hierarchy: the main action is visible immediately, without reveal delays. */
.rv-wd-hero{padding-top:clamp(24px,3.2vw,46px);padding-bottom:clamp(36px,4vw,64px)}
.rv-wd-hero .rv-wd__in{grid-template-columns:minmax(0,1.22fr) minmax(0,.88fr);align-items:center}
.rv-wd-hero h1{margin-top:18px!important;font-size:clamp(34px,3.7vw,56px);line-height:1.06}
.rv-wd-hero__result{margin-top:18px!important;max-width:34ch;font-family:var(--h);font-size:clamp(20px,1.85vw,26px);font-weight:700;line-height:1.3;letter-spacing:-.02em;text-wrap:balance}
.rv-wd-hero__lead{margin-top:14px!important;max-width:56ch;font-size:16px;line-height:1.55}
.rv-wd-hero__position{display:flex;flex-direction:column;gap:4px;margin-top:18px!important;font-size:13px;line-height:1.4}
.rv-wd-hero__position strong{font-size:15px;font-weight:700;color:var(--gold)}
.rv-wd-hero__position span{color:rgba(255,255,255,.7)}
.rv-wd-hero__cta{margin-top:20px;gap:16px 24px;align-items:center}
.rv-wd-hero__cta .rv-wd__btn{max-width:100%;min-height:58px;padding:12px 28px;white-space:normal;text-align:center;font-size:13px;letter-spacing:.045em;line-height:1.35}
.rv-wd-hero__work{display:inline-flex;align-items:center;gap:8px;min-height:44px;font-size:13px;font-weight:600;color:rgba(255,255,255,.84)!important}
.rv-wd-hero__work svg{width:15px;height:15px;fill:none;stroke:var(--gold);stroke-width:1.8}
.rv-wd-hero__work:hover{color:var(--gold)!important}
.rv-wd-hero__note{margin-top:10px!important;font-size:12px;line-height:1.5;color:rgba(255,255,255,.65)}
.rv-wd-stats{margin-top:26px}
.rv-wd-toast--speed b{max-width:19ch;font-size:.8rem}
.rv-wd-section-lead{max-width:76ch;margin:-16px 0 28px!important;font-size:15px;line-height:1.6;color:rgba(255,255,255,.76)}
.rv-wd-section-note{margin-top:24px!important;max-width:76ch;font-size:15px;line-height:1.6;color:rgba(255,255,255,.76)}
.rv-wd-pk .rv-wd__btn,.rv-wd-ca__sum .rv-wd__btn{white-space:normal;padding:12px 20px;line-height:1.4;text-align:center;font-size:.78rem;letter-spacing:.04em}
#rv-wd-cases,#rv-wd-calc,#rv-wd-request{scroll-margin-top:110px}
#s11 .rv-ct__lead{margin:20px 0 0!important;max-width:47ch;color:rgba(255,255,255,.82);font-size:16px;line-height:1.6}
#s11 .rv-ct__card input.wpcf7-submit{white-space:normal;line-height:1.4;padding:16px 18px;font-size:13px;letter-spacing:.04em}
@media(max-width:1100px){
  .rv-wd-hero .rv-wd__in{grid-template-columns:1fr}
  .rv-wd-hero__copy{max-width:700px}
}
@media(max-width:700px){
  .rv-wd-hero{padding-top:22px;padding-bottom:36px}
  .rv-wd-hero .rv-wd__in{gap:24px}
  .rv-wd-hero .rv-wd__chip{font-size:9px;letter-spacing:.09em;padding:8px 12px;line-height:1.3}
  .rv-wd-hero h1{margin-top:14px!important;font-size:clamp(28px,8.3vw,38px);line-height:1.05}
  .rv-wd-hero__result{margin-top:12px!important;font-size:clamp(18px,4.8vw,22px);line-height:1.3}
  .rv-wd-hero__lead{margin-top:12px!important;font-size:14px;line-height:1.5}
  .rv-wd-hero__position{margin-top:14px!important;font-size:11px}
  .rv-wd-hero__position strong{font-size:13px}
  .rv-wd-hero__cta{margin-top:16px;gap:6px;flex-direction:column;align-items:flex-start}
  .rv-wd-hero__cta .rv-wd__btn{width:100%;min-height:54px;padding:12px 18px;font-size:12px}
  .rv-wd-hero__work{align-self:center;min-height:38px;font-size:12px}
  .rv-wd-hero__note{margin-top:4px!important;font-size:11px;text-align:center}
  .rv-wd-section-lead{margin:0 0 20px!important;font-size:14px}
  .rv-wd-section-note{font-size:14px;margin-top:18px!important}
  .rv-wd-sticky{gap:8px;padding-left:14px}
  .rv-wd-sticky span{font-size:12px;line-height:1.25}
  .rv-wd-sticky a{flex:none;padding:0 14px;font-size:10px;letter-spacing:.03em}
  #s11 .rv-ct__lead{font-size:14px;margin-top:14px!important}
}
@media(max-width:700px) and (max-height:700px){
  .rv-wd-hero{padding-top:16px}
  .rv-wd-hero h1{font-size:clamp(26px,7.6vw,32px);margin-top:10px!important}
  .rv-wd-hero__result{font-size:17px;margin-top:10px!important;line-height:1.25}
  .rv-wd-hero__lead{font-size:13px;line-height:1.45;margin-top:10px!important}
  .rv-wd-hero__position{margin-top:10px!important}
  .rv-wd-hero__cta{margin-top:12px}
}
/* ── SEO TEXT ────────────────────────────────────────────────────── */
.rv-wd-seo__box{position:relative;padding:clamp(26px,3.4vw,48px);border-radius:30px;border:1px solid rgba(255,255,255,.1);background:linear-gradient(160deg,rgba(255,255,255,.045),rgba(255,255,255,.01) 70%)}
.rv-wd-seo__body{position:relative;max-height:340px;overflow:hidden;transition:max-height .9s var(--ease)}
.rv-wd-seo__body::after{content:"";position:absolute;left:0;right:0;bottom:0;height:140px;pointer-events:none;background:linear-gradient(transparent,#07063a);transition:opacity .5s ease}
.rv-wd-seo.is-open .rv-wd-seo__body::after{opacity:0}
.rv-wd-seo__body h2{margin:0 0 16px!important;font-family:var(--h);font-weight:800;font-size:clamp(22px,2.2vw,30px);line-height:1.15;letter-spacing:-.02em;color:#fff}
.rv-wd-seo__body h3{margin:28px 0 10px!important;font-family:var(--h);font-weight:700;font-size:clamp(18px,1.5vw,22px);line-height:1.25;letter-spacing:-.01em;color:#fff}
.rv-wd-seo__body p{margin:0 0 14px!important;font-size:.98rem;line-height:1.7;color:rgba(255,255,255,.8)}
.rv-wd-seo__body strong{color:#fff}
.rv-wd-seo__body a{color:var(--gold);border-bottom:1px solid rgba(245,197,66,.4)}
.rv-wd-seo__body ul,.rv-wd-seo__body ol{display:grid;gap:8px;margin:0 0 16px!important;padding:0!important}
.rv-wd-seo__body li{position:relative;padding-left:22px;font-size:.96rem;line-height:1.6;color:rgba(255,255,255,.8)}
.rv-wd-seo__body ul li::before{content:""!important;display:block!important;position:absolute;left:2px;top:.62em;width:7px;height:7px;border-radius:50%;background:var(--gold)}
.rv-wd-seo__toggle{display:inline-flex;align-items:center;gap:10px;margin-top:18px;font-family:var(--mono);font-size:.72rem;letter-spacing:.14em;text-transform:uppercase;color:var(--gold)}
.rv-wd-seo__toggle i{position:relative;width:26px;height:26px;border-radius:50%;border:1px solid rgba(245,197,66,.5);transition:transform .4s var(--ease)}
.rv-wd-seo__toggle i::before{content:"";position:absolute;left:50%;top:45%;width:7px;height:7px;margin:-4px 0 0 -3.5px;border-right:1.5px solid var(--gold);border-bottom:1.5px solid var(--gold);transform:rotate(45deg)}
.rv-wd-seo.is-open .rv-wd-seo__toggle i{transform:rotate(180deg)}
@media(max-width:700px){.rv-wd-seo__box{padding:22px 18px;border-radius:22px}.rv-wd-seo__body{max-height:280px}}
</style>

<!-- ── HERO ─────────────────────────────────────────────────────────── -->
<section class="rv-wd__sec rv-wd-hero" aria-labelledby="rv-wd-h1">
	<div class="rv-wd__grid-bg" aria-hidden="true"></div>
	<div class="rv-wd__in">
		<div class="rv-wd-hero__copy">
			<p class="rv-wd__chip"><?php echo esc_html( $T['chip'] ); ?></p>
			<h1 id="rv-wd-h1"><?php echo esc_html( $T['h1a'] ); ?> <span><?php echo esc_html( $T['h1b'] ); ?></span></h1>
			<?php if ( '' !== $T['hero_result'] ) : ?><p class="rv-wd-hero__result"><?php echo esc_html( $T['hero_result'] ); ?></p><?php endif; ?>
			<p class="rv-wd-hero__lead"><?php echo esc_html( $T['lead'] ); ?></p>
			<p class="rv-wd-hero__position"><strong><?php echo esc_html( $T['hero_price'] ); ?></strong><span><?php echo esc_html( $T['hero_terms'] ); ?></span></p>
			<div class="rv-wd-hero__cta">
				<a class="rv-wd__btn rv-wd__btn--gold" href="#rv-wd-request" data-rv-estimate><?php echo esc_html( $T['cta1'] ); ?></a>
				<a class="rv-wd-hero__work" href="#rv-wd-cases"><?php echo esc_html( $T['cta2'] ); ?> <?php echo $rv_wd_icons['arrow']; ?></a>
			</div>
			<p class="rv-wd-hero__note"><?php echo esc_html( $T['hero_note'] ); ?></p>
			<div class="rv-wd-stats" data-rv style="--d:4">
				<?php foreach ( $T['stats'] as $rv_s ) : ?>
					<div><b><?php echo esc_html( $rv_s[0] ); ?></b><span><?php echo esc_html( $rv_s[1] ); ?></span></div>
				<?php endforeach; ?>
			</div>
		</div>

		<figure class="rv-wd-vis" aria-hidden="true" data-rv style="--d:2">
			<div class="rv-wd-orbit rv-wd-orbit--one"></div>
			<div class="rv-wd-orbit rv-wd-orbit--two"></div>
			<div class="rv-wd-bw">
				<div class="rv-wd-bw__bar"><i></i><i></i><i></i><span>yourbrand.com</span></div>
				<div class="rv-wd-bw__screen">
					<div class="rv-wd-bw__nav"><strong>YOUR <em>BRAND</em></strong><i></i><i></i><b><?php echo $rv_en ? 'ORDER ↗' : 'ЗАМОВИТИ ↗'; ?></b></div>
					<div class="rv-wd-bw__hero">
						<p><?php echo $rv_en ? 'Your next landing page' : 'Ваш майбутній лендінг'; ?></p>
						<h3><?php echo $rv_en ? 'Built for<br><em>your brand.</em>' : 'На рівні<br><em>вашого бізнесу.</em>'; ?></h3>
						<span><?php echo $rv_en ? 'Design · SEO · Development' : 'Дизайн · SEO · Розробка'; ?></span>
					</div>
					<div class="rv-wd-bw__cards"><i></i><i></i><i></i></div>
					<div class="rv-wd-cur"></div>
				</div>
			</div>
			<div class="rv-wd-ph">
				<div class="rv-wd-ph__sensor"></div>
				<div class="rv-wd-ph__brand">YOUR <em>BRAND</em></div>
				<p><?php echo $rv_en ? 'One tap<br><em>to order.</em>' : 'Заявка<br><em>в 1 дотик.</em>'; ?></p>
				<div class="rv-wd-ph__orb"></div>
				<div class="rv-wd-ph__btn"><?php echo $rv_en ? 'ORDER ↗' : 'ЗАМОВИТИ ↗'; ?></div>
			</div>
			<div class="rv-wd-toast rv-wd-toast--speed">
				<span class="rv-wd-toast__ic"><svg viewBox="0 0 24 24"><path d="M13 2.5L4.5 13.5H11l-1 8 8.5-11H12z"/></svg></span>
				<span><small><?php echo esc_html( $T['toast2'] ); ?></small><b><?php echo esc_html( $T['toast2_value'] ); ?></b></span>
			</div>
			<div class="rv-wd-toast rv-wd-toast--lead">
				<span class="rv-wd-toast__ic"><svg viewBox="0 0 24 24"><path d="M21 4L3 11l6 2.2L18 7l-7 7.5V20l3.3-3.6L18 19z"/></svg></span>
				<span><small>Telegram · <?php echo $rv_en ? 'now' : 'щойно'; ?></small><b><?php echo esc_html( $T['toast1'] ); ?></b></span>
			</div>
		</figure>
	</div>
</section>

<!-- ── WHY ──────────────────────────────────────────────────────────── -->
<section class="rv-wd__sec rv-wd-why">
	<div class="rv-wd__in">
		<div class="rv-wd__head" data-rv>
			<div><p class="rv-wd__chip"><?php echo esc_html( $T['why_chip'] ); ?></p><h2 class="rv-wd__h2"><?php echo wp_kses( $T['why_t'], array( 'em' => array(), 'br' => array() ) ); ?></h2></div>
		</div>
		<div class="rv-wd-why__grid" data-rv-car>
			<?php foreach ( $T['why'] as $rv_i => $rv_w ) : ?>
			<article class="rv-wd-card" data-rv style="--d:<?php echo (int) $rv_i; ?>">
				<span class="rv-wd-card__n"><?php echo esc_html( str_pad( $rv_i + 1, 2, '0', STR_PAD_LEFT ) ); ?></span>
				<span class="rv-wd-card__ic" aria-hidden="true"><?php echo $rv_wd_why_icons[ $rv_i ]; ?></span>
				<h3><?php echo esc_html( $rv_w[0] ); ?></h3>
				<p><?php echo esc_html( $rv_w[1] ); ?></p>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ── PACKAGES ─────────────────────────────────────────────────────── -->
<section class="rv-wd__sec rv-wd-pk-sec">
	<div class="rv-wd__in">
		<div class="rv-wd__head" data-rv>
			<div><p class="rv-wd__chip"><?php echo esc_html( $T['pk_chip'] ); ?></p><h2 class="rv-wd__h2"><?php echo wp_kses( $T['pk_t'], array( 'em' => array() ) ); ?></h2></div>
		</div>
		<div class="rv-wd-pk__grid">
			<?php foreach ( $T['pk'] as $rv_i => $rv_p ) :
				$rv_pop = 'sales' === $rv_p[0]; ?>
			<article class="rv-wd-pk<?php echo $rv_pop ? ' is-pop' : ''; ?>" data-rv style="--d:<?php echo (int) $rv_i; ?>">
				<?php if ( $rv_p[5] ) : ?><span class="rv-wd-pk__badge"><?php echo esc_html( $T['pk_badge'] ); ?></span><?php endif; ?>
				<?php if ( $rv_pop ) : ?><span class="rv-wd-pk__badge rv-wd-pk__badge--pop"><?php echo esc_html( $T['pk_pop'] ); ?></span><?php endif; ?>
				<h3><?php echo esc_html( $rv_p[1] ); ?></h3>
				<p class="rv-wd-pk__d"><?php echo esc_html( $rv_p[2] ); ?></p>
				<div class="rv-wd-pk__price"><small><?php echo esc_html( $T['pk_from'] ); ?></small><b><?php echo esc_html( rv_wd_money( $rv_wd_prices[ $rv_p[0] ] ) ); ?></b></div>
				<ul><?php foreach ( $rv_p[3] as $rv_f ) { echo '<li>' . $rv_wd_icons['check'] . esc_html( $rv_f ) . '</li>'; } ?></ul>
				<p class="rv-wd-pk__time"><?php echo esc_html( $rv_p[4] ); ?></p>
				<a class="rv-wd__btn rv-wd__btn--gold" href="#rv-wd-request" data-rv-plan="landing"><?php echo esc_html( $T['pk_cta'] ); ?> <?php echo $rv_wd_icons['arrow']; ?></a>
			</article>
			<?php endforeach; ?>
		</div>
		<p class="rv-wd-section-note" data-rv><?php echo esc_html( $T['pk_note'] ); ?></p>
	</div>
</section>

<!-- ── OFFER $250 ───────────────────────────────────────────────────── -->
<section class="rv-wd__sec rv-wd-offer" id="rv-wd-offer">
	<div class="rv-wd__in">
		<div class="rv-wd-offer__box" data-rv>
			<div>
				<p class="rv-wd__chip"><?php echo esc_html( $T['of_chip'] ); ?></p>
				<h2><?php echo wp_kses( $T['of_t'], array( 'em' => array(), 'br' => array() ) ); ?></h2>
				<p class="rv-wd-offer__lead"><?php echo esc_html( $T['of_lead'] ); ?></p>
				<div class="rv-wd-offer__meta"><?php foreach ( $T['of_meta'] as $rv_m ) { echo '<span>' . esc_html( $rv_m ) . '</span>'; } ?></div>
				<a class="rv-wd__btn rv-wd__btn--gold" href="#rv-wd-request" data-rv-plan="landing"><?php echo esc_html( $T['of_cta'] ); ?> <?php echo $rv_wd_icons['arrow']; ?></a>
			</div>
			<ul class="rv-wd-inc">
				<?php foreach ( $T['of_inc'] as $rv_x ) : ?><li><?php echo $rv_wd_icons['check']; ?><?php echo esc_html( $rv_x ); ?></li><?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>

<!-- ── CALCULATOR ───────────────────────────────────────────────────── -->
<section class="rv-wd__sec rv-wd-ca" id="rv-wd-calc">
	<div class="rv-wd__in">
		<div class="rv-wd__head" data-rv>
			<div><p class="rv-wd__chip"><?php echo esc_html( $T['ca_chip'] ); ?></p><h2 class="rv-wd__h2"><?php echo wp_kses( $T['ca_t'], array( 'em' => array() ) ); ?></h2></div>
		</div>
		<p class="rv-wd-section-lead" data-rv><?php echo esc_html( $T['ca_lead'] ); ?></p>
		<div class="rv-wd-ca__box" data-rv data-rv-calc>
			<div class="rv-wd-ca__form">
				<p class="rv-wd-ca__step"><b>1</b><?php echo esc_html( $T['ca_1'] ); ?></p>
				<div class="rv-wd-ca__types" role="radiogroup" aria-label="<?php echo esc_attr( $T['ca_1'] ); ?>">
					<?php foreach ( $T['ca_types'] as $rv_k => $rv_t ) : ?>
					<button type="button" class="rv-wd-ca__type<?php echo 'start' === $rv_k ? ' is-on' : ''; ?>" role="radio" aria-checked="<?php echo 'start' === $rv_k ? 'true' : 'false'; ?>"
					        data-type="landing" data-price="<?php echo (int) $rv_wd_prices[ $rv_k ]; ?>" data-label="<?php echo esc_attr( $rv_t[0] ); ?>">
						<?php if ( 'start' === $rv_k ) : ?><i><?php echo esc_html( $T['pk_badge'] ); ?></i><?php endif; ?>
						<strong><?php echo esc_html( $rv_t[0] ); ?></strong><small><?php echo esc_html( $rv_t[1] ); ?></small>
						<em><?php echo esc_html( $T['pk_from'] . ' ' . rv_wd_money( $rv_wd_prices[ $rv_k ] ) ); ?></em>
					</button>
					<?php endforeach; ?>
				</div>
				<p class="rv-wd-ca__step"><b>2</b><?php echo esc_html( $T['ca_2'] ); ?></p>
				<div class="rv-wd-ca__opts">
					<?php foreach ( $rv_wd_addons as $rv_k => $rv_a ) : ?>
					<button type="button" class="rv-wd-ca__opt" aria-pressed="false" data-price="<?php echo (int) $rv_a[2]; ?>">
						<span aria-hidden="true"><?php echo $rv_wd_icons['check']; ?></span>
						<strong><?php echo esc_html( $rv_en ? $rv_a[1] : $rv_a[0] ); ?></strong><em>+<?php echo esc_html( rv_wd_money( $rv_a[2] ) ); ?></em>
					</button>
					<?php endforeach; ?>
				</div>
				<button type="button" class="rv-wd-ca__more" data-ca-more><?php echo $rv_en ? 'Show all options' : 'Усі опції'; ?> <span>+<?php echo count( $rv_wd_addons ) - 4; ?></span></button>
			</div>
			<aside class="rv-wd-ca__sum">
				<div class="rv-wd-ca__sticky">
					<p class="rv-wd-ca__lbl"><?php echo esc_html( $T['ca_total'] ); ?></p>
					<div class="rv-wd-ca__total" aria-live="polite"><small>$</small><b data-calc-total><?php echo (int) $rv_wd_prices['start']; ?></b></div>
					<span class="rv-wd-ca__tag" data-calc-tag><?php echo esc_html( $T['ca_types']['start'][0] ); ?></span>
					<div class="rv-wd-ca__rows">
						<p class="rv-wd-ca__lbl"><?php echo esc_html( $T['ca_pay'] ); ?></p>
						<div><span><?php echo esc_html( $T['ca_pre'] ); ?></span><b data-calc-pre></b></div>
						<div><span><?php echo esc_html( $T['ca_post'] ); ?></span><b data-calc-post></b></div>
					</div>
					<ul class="rv-wd-ca__incl">
						<?php foreach ( $T['ca_incl'] as $rv_x ) : ?><li><?php echo $rv_wd_icons['check']; ?><?php echo esc_html( $rv_x ); ?></li><?php endforeach; ?>
					</ul>
					<a class="rv-wd__btn rv-wd__btn--gold" href="#rv-wd-request" data-calc-cta><?php echo esc_html( $T['ca_cta'] ); ?> <?php echo $rv_wd_icons['arrow']; ?></a>
					<p class="rv-wd-ca__note"><?php echo esc_html( $T['ca_note'] ); ?></p>
				</div>
			</aside>
		</div>
	</div>
</section>

<!-- ── PROCESS ──────────────────────────────────────────────────────── -->
<section class="rv-wd__sec rv-wd-pr">
	<div class="rv-wd__in">
		<div class="rv-wd__head" data-rv>
			<div><p class="rv-wd__chip"><?php echo esc_html( $T['pr_chip'] ); ?></p><h2 class="rv-wd__h2"><?php echo wp_kses( $T['pr_t'], array( 'em' => array() ) ); ?></h2></div>
		</div>
		<ol class="rv-wd-pr__list" data-rv-car>
			<?php foreach ( $T['pr'] as $rv_i => $rv_s ) : ?>
			<li data-n="<?php echo esc_attr( str_pad( $rv_i + 1, 2, '0', STR_PAD_LEFT ) ); ?>" data-rv style="--d:<?php echo (int) $rv_i; ?>">
				<small><?php echo esc_html( $rv_s[2] ); ?></small><h3><?php echo esc_html( $rv_s[0] ); ?></h3><p><?php echo esc_html( $rv_s[1] ); ?></p>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<!-- ── CASES ────────────────────────────────────────────────────────── -->
<section class="rv-wd__sec rv-wd-cs" id="rv-wd-cases">
	<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false"><defs><linearGradient id="rvWdSpark" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#F5C542" stop-opacity=".28"/><stop offset="1" stop-color="#F5C542" stop-opacity="0"/></linearGradient></defs></svg>
	<div class="rv-wd__in">
		<div class="rv-wd__head" data-rv>
			<div><p class="rv-wd__chip"><?php echo esc_html( $T['cs_chip'] ); ?></p><h2 class="rv-wd__h2"><?php echo wp_kses( $T['cs_t'], array( 'em' => array() ) ); ?></h2></div>
			<a class="rv-wd__btn rv-wd__btn--ghost" href="<?php echo esc_url( $T['cases_url'] ); ?>"><?php echo esc_html( $T['cs_all'] ); ?> <?php echo $rv_wd_icons['arrow']; ?></a>
		</div>
		<p class="rv-wd-section-lead" data-rv><?php echo esc_html( $T['cs_lead'] ); ?></p>
		<div class="rv-wd-cs__grid" data-rv-car>
			<?php foreach ( $rv_wd_cases as $rv_i => $rv_c ) : ?>
			<article class="rv-wd-case" data-rv data-rv-case style="--d:<?php echo (int) ( $rv_i % 3 ); ?>">
				<svg class="rv-wd-case__spark" viewBox="0 0 100 50" preserveAspectRatio="none" aria-hidden="true">
					<path class="ar" d="M0,44 L14,40 L28,42 L42,31 L57,27 L71,17 L85,12 L100,4 L100,50 L0,50 Z"/>
					<path class="ln" pathLength="1" d="M0,44 L14,40 L28,42 L42,31 L57,27 L71,17 L85,12 L100,4"/>
				</svg>
				<div class="rv-wd-case__top"><span><b><?php echo esc_html( str_pad( $rv_i + 1, 2, '0', STR_PAD_LEFT ) ); ?></b> / <?php echo esc_html( $rv_c[2] ); ?></span><span><?php echo esc_html( $rv_c[6] ); ?></span></div>
				<h3><?php echo esc_html( $rv_c[0] ); ?></h3>
				<p class="rv-wd-case__url"><?php echo esc_html( $rv_c[1] ); ?></p>
				<div class="rv-wd-case__res"><b><?php echo esc_html( $rv_c[3] ); ?></b><span><?php echo esc_html( $rv_en ? $rv_c[5] : $rv_c[4] ); ?></span></div>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ── КЛІЄНТИ: блок з головної ── -->
<!-- ===== RV TRUST SCENE v1.0: клиенты и партнёрства (идея из devils.inc) ===== -->
<?php
/*
 * Сцена «Партнерства» вместо старых бегущих лент логотипов (section.s9).
 * Логотипы берутся из тех же ACF-полей, что и раньше (internet_magaziny,
 * biznes_sajty): добавили клиента в админке — карточка появится сама.
 *
 * Откат на старый блок: $rv_trust_scene = false.
 */
$rv_trust_scene = true;

/*
 * ЖИВЫЕ ФОТО. Сюда — реальные фото команды, офиса, созвонов, выступлений
 * (JPG/WebP ~1200px по длинной стороне). Они встанут в ближние слоты сцены
 * чёрно-белыми и оживут цветом при наведении. Пока массив пуст — сцена
 * собирается только из логотипов. Не больше 6 фото.
 *
 * array('src' => 'https://www.revive-agency.com/wp-content/uploads/2026/09/team-1.webp', 'alt' => 'Команда Revive Agency'),
 */
$rv_trust_photos = array();

if ($rv_trust_scene) :
	$rv_trust_en = $rv_en;

	/* логотипы из ACF: <a href><img src alt></a>, дубликаты убираем по src */
	$rv_trust_logos = array();
	$rv_trust_seen = array();
	foreach (array('internet_magaziny', 'biznes_sajty') as $rv_trust_field) {
		$rv_trust_html = '';
		/* на сторінці послуги поля ACF лежать на головній — беремо звідти (з урахуванням перекладів Polylang) */
		if (function_exists('get_field')) {
			$rv_trust_front = (int) get_option('page_on_front');
			$rv_trust_ids = array($rv_trust_front);
			if (function_exists('pll_get_post') && $rv_trust_front) {
				foreach (array('uk', 'en') as $rv_trust_l) { $rv_trust_t = pll_get_post($rv_trust_front, $rv_trust_l); if ($rv_trust_t) $rv_trust_ids[] = (int) $rv_trust_t; }
			}
			foreach (array_unique(array_filter($rv_trust_ids)) as $rv_trust_pid) {
				$rv_trust_html = (string) get_field($rv_trust_field, $rv_trust_pid);
				if ($rv_trust_html !== '') break;
			}
		}
		if ($rv_trust_html === '') continue;
		if (! preg_match_all('~(?:<a\b([^>]*)>\s*)?(<img\b[^>]*>)~i', $rv_trust_html, $rv_trust_matches, PREG_SET_ORDER)) continue;
		foreach ($rv_trust_matches as $rv_trust_match) {
			if (! preg_match('~\bsrc=["\']([^"\']+)["\']~i', $rv_trust_match[2], $rv_trust_src)) continue;
			$rv_trust_key = strtolower($rv_trust_src[1]);
			if (isset($rv_trust_seen[$rv_trust_key])) continue;
			$rv_trust_seen[$rv_trust_key] = true;
			$rv_trust_alt = preg_match('~\balt=["\']([^"\']*)["\']~i', $rv_trust_match[2], $rv_trust_a) ? html_entity_decode($rv_trust_a[1], ENT_QUOTES) : '';
			$rv_trust_href = (! empty($rv_trust_match[1]) && preg_match('~\bhref=["\']([^"\']+)["\']~i', $rv_trust_match[1], $rv_trust_h)) ? html_entity_decode($rv_trust_h[1], ENT_QUOTES) : '';
			$rv_trust_logos[] = array('type' => 'logo', 'src' => $rv_trust_src[1], 'alt' => $rv_trust_alt, 'href' => $rv_trust_href);
		}
	}

	/*
	 * Слоты сцены: x%, y%, глубина (1 — дальний, за словом; 3 — ближний),
	 * базовая ширина карточки в px на экране 1440. Мобильная раскладка —
	 * отдельные 8 слотов, остальные карточки на телефоне скрыты.
	 */
	$rv_trust_slots = array(
		/* верхняя зона: два ряда над бегущей строкой */
		/* ширины карточек выровнены: раньше 146–226 px, мелкие «дальние»
		   терялись; теперь 172–208 px, разница видна, но не бросается в глаза */
		array( 7, 11, 2, 192), array(19, 28, 1, 172), array(32, 11, 3, 208), array(45, 28, 1, 172),
		array(57, 11, 2, 192), array(69, 28, 1, 172), array(81, 11, 3, 208), array(93, 28, 1, 172),
		/* нижняя зона: по бокам от круглой кнопки */
		array( 9, 78, 3, 208), array(23, 91, 1, 172), array(34, 74, 2, 192),
		array(66, 74, 2, 192), array(77, 91, 1, 172), array(92, 78, 3, 208),
	);
	/* телефон: бегущей строки нет — 14 логотипов рядами по 3,
	   в середине ряд из двух по бокам от круглой кнопки */
	$rv_trust_mobile = array(
		array(17,  9, 98), array(50, 12, 98), array(83,  9, 98),
		array(17, 29, 98), array(50, 31, 98), array(83, 29, 98),
		array(14, 49, 92), array(86, 49, 92),
		array(17, 69, 98), array(50, 71, 98), array(83, 69, 98),
		array(17, 89, 98), array(50, 91, 98), array(83, 89, 98),
	);
	/* полоса слова (≈38–62% высоты сцены) всегда свободна от карточек;
	   фото занимают ближние слоты, логотипы — оставшиеся по порядку */
	$rv_trust_photo_slots = array(2, 6, 8, 13, 0, 4);

	$rv_trust_items = array();
	$rv_trust_used = array();
	foreach (array_slice($rv_trust_photos, 0, 6) as $rv_trust_i => $rv_trust_photo) {
		if (empty($rv_trust_photo['src'])) continue;
		$rv_trust_slot = $rv_trust_photo_slots[$rv_trust_i];
		$rv_trust_used[$rv_trust_slot] = true;
		$rv_trust_items[] = array_merge(array('type' => 'photo', 'alt' => '', 'href' => ''), $rv_trust_photo, array('slot' => $rv_trust_slot));
	}
	$rv_trust_free = array();
	foreach ($rv_trust_slots as $rv_trust_i => $rv_trust_s) {
		if (! isset($rv_trust_used[$rv_trust_i])) $rv_trust_free[] = $rv_trust_i;
	}
	foreach ($rv_trust_logos as $rv_trust_logo) {
		if (! $rv_trust_free) break;
		$rv_trust_logo['slot'] = array_shift($rv_trust_free);
		$rv_trust_items[] = $rv_trust_logo;
	}
?>
<section class="rv-trust" id="s9" data-rv-trust aria-labelledby="rv-trust-title">
	<header class="rv-trust__head" data-rv-reveal>
		<div>
			<p class="rv-trust__eyebrow" data-rv-item><i aria-hidden="true"></i><?php echo $rv_trust_en ? 'Selected partnerships' : 'Вибрані партнерства'; ?></p>
			<h2 class="rv-trust__title" id="rv-trust-title"><?php echo $rv_trust_en ? 'Selected <em>clients</em>' : 'Вибрані <em>клієнти</em>'; ?></h2>
		</div>
		<p class="rv-trust__lead" data-rv-item>
			<small><?php echo $rv_trust_en ? 'Trusted worldwide' : 'Нам довіряють'; ?></small>
			<?php echo $rv_trust_en ? 'Brands we&rsquo;ve helped move forward — from Kyiv to California.' : 'Бренди, яким ми допомогли рухатися вперед — від Києва до Каліфорнії.'; ?>
		</p>
	</header>

	<div class="rv-trust__stage">
		<div class="rv-trust__word" aria-hidden="true">
			<div class="rv-trust__track">
				<div class="rv-trust__group">
					<span>Partnerships</span><i></i><span class="is-outline">Trust</span><i></i><span>Growth</span><i></i>
				</div>
			</div>
		</div>

		<?php if ($rv_trust_items) : ?>
		<ul class="rv-trust__cloud" aria-label="<?php echo $rv_trust_en ? 'Clients' : 'Клієнти'; ?>">
			<?php foreach ($rv_trust_items as $rv_trust_n => $rv_trust_item) :
				$rv_trust_s = $rv_trust_slots[$rv_trust_item['slot']];
				$rv_trust_m = isset($rv_trust_mobile[$rv_trust_n]) ? $rv_trust_mobile[$rv_trust_n] : null;
				$rv_trust_w = $rv_trust_item['type'] === 'photo' ? round($rv_trust_s[3] * 1.18) : $rv_trust_s[3];
				$rv_trust_style = sprintf('--x:%d%%;--y:%d%%;--d:%d;--w:%d;--f:%.1fs;--fd:-%.1fs', $rv_trust_s[0], $rv_trust_s[1], $rv_trust_s[2], $rv_trust_w, 6 + ($rv_trust_n % 4) * .9, fmod($rv_trust_n * 1.3, 7));
				if ($rv_trust_m) $rv_trust_style .= sprintf(';--mx:%d%%;--my:%d%%;--mw:%d', $rv_trust_m[0], $rv_trust_m[1], $rv_trust_m[2]);
				$rv_trust_tag = $rv_trust_item['href'] !== '' ? 'a' : 'div';
			?>
			<li class="rv-trust__item<?php echo $rv_trust_m ? '' : ' is-desktop-only'; ?>" data-depth="<?php echo (int) $rv_trust_s[2]; ?>" style="<?php echo esc_attr($rv_trust_style); ?>">
				<<?php echo $rv_trust_tag; ?> class="rv-trust__card rv-trust__card--<?php echo esc_attr($rv_trust_item['type']); ?>"<?php if ($rv_trust_tag === 'a') : ?> href="<?php echo esc_url($rv_trust_item['href']); ?>" target="_blank" rel="nofollow noopener"<?php endif; ?>>
					<img src="<?php echo esc_url($rv_trust_item['src']); ?>" alt="<?php echo esc_attr($rv_trust_item['alt']); ?>" loading="lazy" decoding="async"<?php echo $rv_trust_item['type'] === 'logo' ? ' width="150" height="150"' : ''; ?>>
					<?php if ($rv_trust_item['type'] === 'logo' && $rv_trust_item['alt'] !== '') : ?>
						<span class="rv-trust__name" aria-hidden="true"><?php echo esc_html($rv_trust_item['alt']); ?><?php echo $rv_trust_tag === 'a' ? ' ↗︎' : ''; ?></span>
					<?php endif; ?>
				</<?php echo $rv_trust_tag; ?>>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>

		<a class="rv-trust__cta" href="#rv-wd-request">
			<svg class="rv-trust__ring" viewBox="0 0 200 200" aria-hidden="true" focusable="false">
				<defs><path id="rvTrustRing" d="M100,100 m-86,0 a86,86 0 1,1 172,0 a86,86 0 1,1 -172,0"/></defs>
				<text><textPath href="#rvTrustRing" textLength="538" lengthAdjust="spacing">Start a project · Revive Agency · Start a project · Revive Agency ·</textPath></text>
			</svg>
			<span class="rv-trust__cta-core">
				<span><?php echo $rv_trust_en ? 'Become<br>a client' : 'Стати<br>клієнтом'; ?></span>
				<em aria-hidden="true">↗︎</em>
			</span>
		</a>
	</div>
</section>
<script id="rv-trust-scene">
/* REVIVE trust scene v1.0
   · слово-лента реагирует на скорость и направление скролла;
   · карточки разнесены по трём слоям глубины: параллакс от скролла и курсора
     (только ПК), лёгкое «дыхание» на CSS;
   · CTA-кружок притягивается к курсору;
   · всё стоит на паузе, пока сцена вне экрана. */
(function () {
	'use strict';
	var section = document.querySelector('[data-rv-trust]');
	if (!section || !window.requestAnimationFrame) return;
	var stage = section.querySelector('.rv-trust__stage');
	var track = section.querySelector('.rv-trust__track');
	var group = section.querySelector('.rv-trust__group');
	var cta = section.querySelector('.rv-trust__cta');
	if (!stage || !track || !group) return;

	var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
	var doc = document.documentElement;

	/* бесшовная петля: группа повторяется, пока лента не станет шире 2 экранов */
	function fill() {
		var copies = track.querySelectorAll('.rv-trust__group');
		for (var i = copies.length - 1; i >= 1; i--) track.removeChild(copies[i]);
		var width = group.getBoundingClientRect().width || 1;
		var need = Math.max(2, Math.ceil((window.innerWidth * 2) / width) + 1);
		for (var n = 1; n < need; n++) {
			var clone = group.cloneNode(true);
			clone.setAttribute('aria-hidden', 'true');
			track.appendChild(clone);
		}
		return width;
	}

	var unit = fill();
	if (reduced) { section.classList.add('is-static'); return; }

	var live = false;
	var raf = 0;
	var last = 0;
	var x = 0;
	var dir = -1;
	var boost = 0;
	var lastY = window.pageYOffset;
	var stageTop = 0;
	var stageH = 1;
	var px = 0, py = 0, tpx = 0, tpy = 0, sp = 0;
	var written = { px: null, py: null, sp: null };

	/* на телефоне строка скрыта CSS — тогда и цикл ленты не крутится */
	var word = section.querySelector('.rv-trust__word');
	var wordOn = true;
	function measure() {
		wordOn = !!word && window.getComputedStyle(word).display !== 'none';
		var r = stage.getBoundingClientRect();
		stageTop = r.top + window.pageYOffset;
		stageH = r.height || 1;
	}

	function write(name, value) {
		var v = Math.round(value * 1000) / 1000;
		if (written[name] === v) return;
		written[name] = v;
		stage.style.setProperty('--' + name, String(v));
	}

	function frame(now) {
		raf = 0;
		if (!live) return;
		var dt = last ? Math.min(.05, (now - last) / 1000) : .016;
		last = now;

		var y = window.pageYOffset;
		var dy = y - lastY;
		lastY = y;
		var v = (window.RVScroll && window.RVScroll.lenis) ? window.RVScroll.velocity() : dy;
		if (Math.abs(v) > .35) dir = v > 0 ? -1 : 1;
		boost += (Math.min(Math.abs(v), 70) * 11 - boost) * .085;

		if (wordOn) {
			x += dir * (64 + boost) * dt;
			if (x <= -unit) x += unit;
			else if (x > 0) x -= unit;
			track.style.transform = 'translate3d(' + x.toFixed(2) + 'px,0,0)';
		}

		if (fine) {
			px += (tpx - px) * .06;
			py += (tpy - py) * .06;
			var vh = window.innerHeight || doc.clientHeight;
			sp = Math.max(-1, Math.min(1, ((y + vh / 2) - (stageTop + stageH / 2)) / vh));
			write('px', px);
			write('py', py);
			write('sp', sp);
		}
		if (wordOn || fine) raf = window.requestAnimationFrame(frame);
	}

	function play() { if (!raf && live && !document.hidden) { last = 0; raf = window.requestAnimationFrame(frame); } }
	function pause() { if (raf) { window.cancelAnimationFrame(raf); raf = 0; } }

	if ('IntersectionObserver' in window) {
		new IntersectionObserver(function (entries) {
			live = entries[0].isIntersecting;
			section.classList.toggle('is-live', live);
			if (live) { measure(); play(); } else pause();
		}, { rootMargin: '160px 0px 160px 0px' }).observe(section);
	} else {
		live = true;
		section.classList.add('is-live');
		play();
	}
	document.addEventListener('visibilitychange', function () { if (document.hidden) pause(); else play(); });

	var lastWidth = window.innerWidth;
	window.addEventListener('resize', function () {
		measure();
		if (window.innerWidth !== lastWidth) { lastWidth = window.innerWidth; unit = fill(); }
	}, { passive: true });
	window.addEventListener('load', function () { measure(); unit = fill(); });
	if (document.fonts && document.fonts.ready) document.fonts.ready.then(function () { unit = fill(); measure(); }, function () {});

	if (!fine) return;

	stage.addEventListener('pointermove', function (event) {
		var r = stage.getBoundingClientRect();
		tpx = ((event.clientX - r.left) / r.width - .5) * 2;
		tpy = ((event.clientY - r.top) / r.height - .5) * 2;
	}, { passive: true });
	stage.addEventListener('pointerleave', function () { tpx = 0; tpy = 0; });

	if (cta) {
		var ctaFrame = 0;
		var next = null;
		var paint = function () {
			ctaFrame = 0;
			if (!next) return;
			cta.style.setProperty('--mx', next[0] + 'px');
			cta.style.setProperty('--my', next[1] + 'px');
		};
		cta.addEventListener('pointermove', function (event) {
			var r = cta.getBoundingClientRect();
			var dx = event.clientX - (r.left + r.width / 2);
			var dy = event.clientY - (r.top + r.height / 2);
			next = [Math.max(-22, Math.min(22, dx * .3)).toFixed(1), Math.max(-22, Math.min(22, dy * .3)).toFixed(1)];
			if (!ctaFrame) ctaFrame = window.requestAnimationFrame(paint);
		}, { passive: true });
		cta.addEventListener('pointerleave', function () {
			next = ['0', '0'];
			if (!ctaFrame) ctaFrame = window.requestAnimationFrame(paint);
		});
	}
}());
</script>
<style id="rv-trust-fix">
/* ===== КЛИЕНТЫ: правки v2 =====
   1) Логотипы одного визуального веса: скрипт ниже обрезает прозрачные поля
      PNG и задаёт ширину по формуле «равная площадь» — широкие знаки ниже,
      компактные крупнее. До его работы действует прежнее правило.
   2) Дальние карточки больше не тусклые: логотипы на всех — в полную яркость,
      рамка и подложка у дальних подтянуты к остальным.
   3) Бейдж «Стати клієнтом» крупнее: около 220 px на ПК вместо 168 px. */
.rv-trust__card--logo img.is-fit{aspect-ratio:auto;height:auto;max-width:none;max-height:none}
.rv-trust .rv-trust__card img,
.rv-trust__item[data-depth="1"] .rv-trust__card img{opacity:1}
.rv-trust__item[data-depth="1"] .rv-trust__card{
  border-color:rgba(245,244,239,.12);
  background:linear-gradient(155deg,rgba(255,255,255,.07),rgba(255,255,255,.012) 62%),rgba(9,10,54,.94);
  box-shadow:0 26px 52px rgba(0,0,18,.34),inset 0 1px 0 rgba(255,255,255,.07)}
.rv-trust [data-rv-fitting] .rv-trust__card--logo img{opacity:0}

.rv-trust .rv-trust__cta{width:clamp(136px,min(15vw,22vh),220px)}
.rv-trust .rv-trust__cta-core{font-size:13px;gap:10px}
.rv-trust .rv-trust__cta-core em{font-size:22px}
.rv-trust .rv-trust__ring text{font-size:10.5px}
@media (max-width:767px){
  .rv-trust .rv-trust__cta{width:clamp(108px,14vh,118px)}
  .rv-trust .rv-trust__cta-core{font-size:11px}
  .rv-trust .rv-trust__ring text{font-size:11px}
}
</style>
<script id="rv-trust-fix-js">
(function(){
  'use strict';
  var root = document.querySelector('[data-rv-trust]');
  if (!root) return;
  var cloud = root.querySelector('.rv-trust__cloud');
  if (!cloud) return;
  var imgs = [].slice.call(cloud.querySelectorAll('.rv-trust__card--logo img'));
  if (!imgs.length) return;

  /* Обрезка прозрачных полей: возвращает картинку без полей и её пропорции */
  function trim(src, cb){
    var im = new Image();
    im.decoding = 'async';
    im.onload = function(){
      try {
        var w = im.naturalWidth, h = im.naturalHeight;
        var c = document.createElement('canvas'); c.width = w; c.height = h;
        var x = c.getContext('2d'); x.drawImage(im, 0, 0);
        var d = x.getImageData(0, 0, w, h).data, minx = w, miny = h, maxx = -1, maxy = -1;
        for (var y = 0; y < h; y++) for (var i = 0; i < w; i++) {
          if (d[(y * w + i) * 4 + 3] > 16) {
            if (i < minx) minx = i; if (i > maxx) maxx = i;
            if (y < miny) miny = y; if (y > maxy) maxy = y;
          }
        }
        if (maxx < 0) { cb(null); return; }
        var cw = maxx - minx + 1, ch = maxy - miny + 1;
        var o = document.createElement('canvas'); o.width = cw; o.height = ch;
        o.getContext('2d').drawImage(c, minx, miny, cw, ch, 0, 0, cw, ch);
        cb({ url: o.toDataURL('image/png'), ar: cw / ch });
      } catch (e) { cb(null); }
    };
    im.onerror = function(){ cb(null); };
    im.src = src;
  }

  /* Ширина в % от карточки (3:2) по формуле равной площади:
     w = K·√ar, но не шире 72% и не выше 46% высоты карточки. */
  var K = 27;
  function widthFor(ar){
    ar = Math.max(.3, Math.min(ar, 10));
    return Math.min(K * Math.sqrt(ar), 72, 46 * ar / 1.5);
  }

  var started = false;
  function run(){
    if (started) return;
    started = true;
    cloud.setAttribute('data-rv-fitting', '');
    var left = imgs.length;
    function done(){ if (--left <= 0) cloud.removeAttribute('data-rv-fitting'); }
    imgs.forEach(function(img){
      trim(img.getAttribute('src'), function(r){
        if (r) {
          img.removeAttribute('loading');
          img.src = r.url;
          img.style.width = widthFor(r.ar).toFixed(2) + '%';
          img.classList.add('is-fit');
        }
        done();
      });
    });
    /* страховка: логотипы не останутся скрытыми, даже если что-то не загрузилось */
    setTimeout(function(){ cloud.removeAttribute('data-rv-fitting'); }, 3000);
  }

  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function(es){
      if (!es[0].isIntersecting) return;
      io.disconnect(); run();
    }, { rootMargin: '600px 0px' });
    io.observe(root);
  } else {
    run();
  }
})();
</script>
<?php endif; ?>
<!-- ===== /RV TRUST SCENE ===== -->

<!-- ── GUARANTEES ───────────────────────────────────────────────────── -->
<section class="rv-wd__sec rv-wd-gu">
	<div class="rv-wd__in">
		<div class="rv-wd__head" data-rv>
			<div><p class="rv-wd__chip"><?php echo esc_html( $T['gu_chip'] ); ?></p><h2 class="rv-wd__h2"><?php echo wp_kses( $T['gu_t'], array( 'em' => array() ) ); ?></h2></div>
		</div>
		<ul class="rv-wd-gu__grid" data-rv-car>
			<?php foreach ( $T['gu'] as $rv_i => $rv_g ) : ?>
			<li data-rv style="--d:<?php echo (int) $rv_i; ?>"><?php echo $rv_wd_icons['check']; ?><h3><?php echo esc_html( $rv_g[0] ); ?></h3><p><?php echo esc_html( $rv_g[1] ); ?></p></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<!-- ── FAQ ──────────────────────────────────────────────────────────── -->
<section class="rv-wd__sec rv-wd-fq">
	<div class="rv-wd__in">
		<div data-rv><p class="rv-wd__chip"><?php echo esc_html( $T['fq_chip'] ); ?></p><h2 class="rv-wd__h2"><?php echo wp_kses( $T['fq_t'], array( 'em' => array() ) ); ?></h2></div>
		<div class="rv-wd-fq__list">
			<?php foreach ( $T['fq'] as $rv_i => $rv_q ) : ?>
			<details data-rv style="--d:<?php echo (int) $rv_i; ?>"<?php echo 0 === $rv_i ? ' open' : ''; ?>>
				<summary><?php echo esc_html( $rv_q[0] ); ?><i aria-hidden="true"></i></summary>
				<p><?php echo esc_html( $rv_q[1] ); ?></p>
			</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ── SEO-ТЕКСТ: з редактора сторінки або стандартний із $T['seo'] ── -->
<section class="rv-wd__sec rv-wd-seo" id="rv-wd-seo">
	<div class="rv-wd__in">
		<div class="rv-wd-seo__box" data-rv>
			<p class="rv-wd__chip" style="margin-bottom:22px"><?php echo esc_html( $T['seo_chip'] ); ?></p>
			<div class="rv-wd-seo__body" id="rv-wd-seo-body"><?php echo wp_kses_post( $rv_wd_seo_html ); ?></div>
			<button type="button" class="rv-wd-seo__toggle" aria-expanded="false" aria-controls="rv-wd-seo-body" data-more="<?php echo esc_attr( $T['seo_more'] ); ?>" data-less="<?php echo esc_attr( $T['seo_less'] ); ?>"><span><?php echo esc_html( $T['seo_more'] ); ?></span><i aria-hidden="true"></i></button>
		</div>
	</div>
</section>

<!-- ── КОНТАКТИ + ФОРМА: той самий блок, що внизу головної (s11) ── -->
<section class="s11" id="s11">
	<!-- Анимированный фон: видео с Землёй остаётся, слои затемнения — из style.css. -->
	<video class="s11__video rv-lazy-video"
	       data-src="https://d8j0ntlcm91z4.cloudfront.net/user_38xzZboKViGWJOttwIXH07lWA1P/hf_20260613_180732_a54afbf6-b30d-470e-861f-669871f09f67.mp4"
	       preload="none" muted loop playsinline aria-hidden="true" tabindex="-1"></video>
	<span class="rv-wave" aria-hidden="true"></span>
<?php
/* ===== КОНТАКТЫ v2 =====
   Слева — заголовок, каналы связи и офисы с местным временем,
   справа — форма в стеклянной карточке. Контакты заданы здесь
   (раньше приходили из ACF-поля infa_sekczii_11): правятся в массиве ниже. */
$rv_ct_en = function_exists( 'pll_current_language' ) && 'en' === pll_current_language();
$rv_ct = $rv_ct_en ? array(
	'chip'    => $T['fn_chip'],
	'title'   => $T['fn_t'],
	'lead'    => $T['fn_lead'],
	'card'    => 'Get a landing page estimate',
	'online'  => 'Budget & timeline',
	'cardsub' => 'Leave your name and phone number — we’ll call you back and estimate your landing page.',
	'note'    => 'By sending the form you agree to our <a href="%s">privacy policy</a>.',
	'privacy' => home_url( '/privacy-policy/' ),
	'phoneL'  => 'Phone',
	'tgL'     => 'Telegram',
	'waL'     => 'WhatsApp',
	'offices' => 'Offices',
	'now'     => 'local time',
	'kyiv'    => array( 'Kyiv', 'Novopecherskyi Lane 5, Kyiv, Ukraine' ),
	'irvine'  => array( 'Irvine', '2030 Main Street, Irvine, California 92614, USA' ),
	'form'    => '[contact-form-7 id="10" title="cf7#1"]',
) : array(
	'chip'    => $T['fn_chip'],
	'title'   => $T['fn_t'],
	'lead'    => $T['fn_lead'],
	'card'    => 'Отримайте оцінку лендінгу',
	'online'  => 'Бюджет і строки',
	'cardsub' => 'Залиште ім’я та телефон — ми передзвонимо й оцінимо ваш лендінг.',
	'note'    => 'Надсилаючи форму, ви погоджуєтеся з <a href="%s">політикою конфіденційності</a>.',
	'privacy' => home_url( '/uk/privacy-policy/' ),
	'phoneL'  => 'Телефон',
	'tgL'     => 'Telegram',
	'waL'     => 'WhatsApp',
	'offices' => 'Офіси',
	'now'     => 'місцевий час',
	'kyiv'    => array( 'Київ', 'Новопечерський провулок, 5, Київ, Україна' ),
	'irvine'  => array( 'Ірвайн', '2030 Мейн-стріт, Ірвайн, Каліфорнія 92614, США' ),
	'form'    => '[contact-form-7 id="473" title="cf7#1_ua"]',
);
$rv_ct_channels = array(
	array( 'phone', $rv_ct['phoneL'], '+38 (067) 8000-830', 'tel:+380678000830', false ),
	array( 'tg',    $rv_ct['tgL'],    '@max8ua',            'https://telegram.me/max8ua', true ),
	array( 'wa',    $rv_ct['waL'],    '+38 067 8000 830',   'https://wa.me/380678000830', true ),
);
$rv_ct_icons = array(
	'phone' => '<svg viewBox="0 0 24 24"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/></svg>',
	'tg'    => '<svg viewBox="0 0 24 24"><path d="M21 4L3 11l6 2.2L18 7l-7 7.5V20l3.3-3.6L18 19z"/></svg>',
	'wa'    => '<svg viewBox="0 0 24 24"><path d="M4 20l1.3-3.9A8.5 8.5 0 1 1 8 18.8z"/><path d="M9 8.5c0 3.5 3 6.5 6.5 6.5l1-1.8-2-1-1 1a5 5 0 0 1-2.7-2.7l1-1-1-2z"/></svg>',
);
?>
	<div class="wrapper">
		<div class="rv-ct">
			<div class="rv-ct__left">
				<p class="rv-ct__chip"><i></i><?php echo esc_html( $rv_ct['chip'] ); ?></p>
				<h2 class="rv-ct__title"><?php echo wp_kses( $rv_ct['title'], array( 'em' => array() ) ); ?></h2>
				<p class="rv-ct__lead"><?php echo esc_html( $rv_ct['lead'] ); ?></p>

				<ul class="rv-ct__channels">
					<?php foreach ( $rv_ct_channels as $rv_c ) : ?>
					<li>
						<a class="rv-ct__ch" href="<?php echo esc_url( $rv_c[3] ); ?>"<?php echo $rv_c[4] ? ' target="_blank" rel="noopener"' : ''; ?>>
							<span class="rv-ct__ico" aria-hidden="true"><?php echo $rv_ct_icons[ $rv_c[0] ]; // SVG из массива выше ?></span>
							<span class="rv-ct__chtext"><small><?php echo esc_html( $rv_c[1] ); ?></small><b><?php echo esc_html( $rv_c[2] ); ?></b></span>
							<span class="rv-ct__arrow" aria-hidden="true">↗</span>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>

				<div class="rv-ct__offices" aria-label="<?php echo esc_attr( $rv_ct['offices'] ); ?>">
					<?php foreach ( array( array( $rv_ct['kyiv'], 'Europe/Kiev' ), array( $rv_ct['irvine'], 'America/Los_Angeles' ) ) as $rv_o ) : ?>
					<div class="rv-ct__office">
						<p class="rv-ct__city"><span><?php echo esc_html( $rv_o[0][0] ); ?></span><time data-rv-tz="<?php echo esc_attr( $rv_o[1] ); ?>">—:—</time></p>
						<p class="rv-ct__addr"><?php echo esc_html( $rv_o[0][1] ); ?></p>
						<p class="rv-ct__tz"><?php echo esc_html( $rv_ct['now'] ); ?></p>
					</div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="rv-ct__right">
				<div class="rv-ct__card" id="rv-wd-request" tabindex="-1" aria-labelledby="rv-wd-request-title">
					<div class="rv-ct__card-head">
						<p class="rv-ct__card-title" id="rv-wd-request-title"><?php echo esc_html( $rv_ct['card'] ); ?></p>
						<p class="rv-ct__online"><i></i><?php echo esc_html( $rv_ct['online'] ); ?></p>
					</div>
					<p class="rv-ct__card-sub"><?php echo esc_html( $rv_ct['cardsub'] ); ?></p>
					<?php if ( false ) : /* на лендінгу — проста форма CF7: ім'я + телефон */ ?>
					<?php /* квалифицирующая форма (inc/rv-lead.php): тип проекта, бюджет, задача */ ?>
					<?php rv_lead_form( 'card' ); ?>
					<?php else : /* запасной вариант, если модуль не подключён */ ?>
					<div class="s11__form">
						<?php
						$rv_wd_submit_label = static function ( $tag ) use ( $T ) {
							if ( is_array( $tag ) && isset( $tag['basetype'] ) && 'submit' === $tag['basetype'] ) {
								$tag['values'] = array( $T['cta1'] );
							} elseif ( is_object( $tag ) && isset( $tag->basetype ) && 'submit' === $tag->basetype ) {
								$tag->values = array( $T['cta1'] );
							}
							return $tag;
						};
						add_filter( 'wpcf7_form_tag', $rv_wd_submit_label, 20 );
						echo do_shortcode( $rv_ct['form'] );
						remove_filter( 'wpcf7_form_tag', $rv_wd_submit_label, 20 );
						?>
					</div>
					<?php endif; ?>
					<p class="rv-ct__note"><?php echo wp_kses( sprintf( $rv_ct['note'], esc_url( $rv_ct['privacy'] ) ), array( 'a' => array( 'href' => array() ) ) ); ?></p>
				</div>
			</div>
		</div>
	</div>

<style id="rv-ct-css">
section.s11#s11{--gold:#F5C542;--ease:cubic-bezier(.22,1,.36,1);
  display:block!important;min-height:0!important;padding:clamp(72px,10vh,120px) 0 clamp(90px,16vh,190px)!important}
#s11 .rv-ct *,#s11 .rv-ct *::before,#s11 .rv-ct *::after{box-sizing:border-box}
#s11 .rv-ct p,#s11 .rv-ct h2,#s11 .rv-ct ul{margin:0;padding:0}
#s11 .rv-ct ul{list-style:none}
#s11 .rv-ct i,#s11 .rv-ct em{font-style:normal}
#s11 > .wrapper{max-width:1320px!important;padding:0 clamp(20px,4vw,56px)!important}
.rv-ct{display:grid;grid-template-columns:minmax(0,1.05fr) minmax(0,.95fr);gap:clamp(32px,5vw,88px);align-items:start;font-family:'Inter',Arial,sans-serif;color:#fff}

/* — левая колонка — */
.rv-ct__chip{display:inline-flex;align-items:center;gap:.7rem;padding:.45rem 1rem;border-radius:100px;border:1px solid rgba(255,255,255,.16);
  background:rgba(3,0,50,.4);-webkit-backdrop-filter:blur(10px);backdrop-filter:blur(10px);
  font-family:'JetBrains Mono',monospace;font-size:.66rem;letter-spacing:.12em;text-transform:uppercase;color:#fff}
.rv-ct__chip i{width:7px;height:7px;border-radius:50%;background:#5BD98A;box-shadow:0 0 10px #5BD98A;animation:rvCtLive 2s ease-in-out infinite}
.rv-ct__title{margin-top:20px!important;font-family:'Manrope',Arial,sans-serif;font-weight:800;font-size:clamp(34px,3.35vw,58px);line-height:1.06;letter-spacing:-.035em;
  text-transform:uppercase;color:#fff}
.rv-ct__title em{display:block;margin-top:.04em;color:var(--gold);white-space:nowrap}
.rv-ct__lead{margin-top:18px!important;max-width:44ch;font-size:clamp(15px,1.15vw,18px);line-height:1.6;color:#fff}

.rv-ct__channels{width:100%;max-width:none;margin-top:26px!important;border-top:1px solid rgba(255,255,255,.14)}
.rv-ct__channels li{border-bottom:1px solid rgba(255,255,255,.14)}
.rv-ct__ch{position:relative;display:grid;grid-template-columns:48px minmax(0,1fr) 40px;align-items:center;gap:16px;padding:14px 6px;color:#fff!important;text-decoration:none!important;overflow:hidden;isolation:isolate}
.rv-ct__ch::before{content:"";position:absolute;inset:0;z-index:-1;background:linear-gradient(90deg,rgba(245,197,66,.14),rgba(245,197,66,0) 70%);transform:scaleX(0);transform-origin:left;transition:transform .5s var(--ease)}
.rv-ct__ch:hover::before,.rv-ct__ch:focus-visible::before{transform:scaleX(1)}
.rv-ct__ch:focus-visible{outline:2px solid rgba(245,197,66,.8);outline-offset:2px}
.rv-ct__ico{display:grid;place-items:center;width:48px;height:48px;border-radius:50%;border:1px solid rgba(245,197,66,.45);background:rgba(245,197,66,.08);transition:background .35s ease,border-color .35s ease}
.rv-ct__ico svg{width:20px;height:20px;fill:none;stroke:var(--gold);stroke-width:1.7;stroke-linecap:round;stroke-linejoin:round;transition:stroke .35s ease}
.rv-ct__ch:hover .rv-ct__ico{background:var(--gold);border-color:var(--gold)}
.rv-ct__ch:hover .rv-ct__ico svg{stroke:#030032}
.rv-ct__chtext small{display:block;font-family:'JetBrains Mono',monospace;font-size:.62rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold)}
.rv-ct__chtext b{display:block;margin-top:3px;font-family:'Manrope',Arial,sans-serif;font-weight:700;font-size:clamp(18px,1.6vw,24px);letter-spacing:-.01em;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.rv-ct__arrow{justify-self:end;display:grid;place-items:center;width:40px;height:40px;border-radius:50%;border:1px solid rgba(255,255,255,.2);font-size:.95rem;color:#fff;transition:transform .4s var(--ease),background .3s ease,color .3s ease,border-color .3s ease}
.rv-ct__ch:hover .rv-ct__arrow{transform:rotate(45deg);background:#fff;color:#030032;border-color:#fff}

.rv-ct__offices{width:100%;display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:22px}
.rv-ct__office{padding:16px 18px;border-radius:18px;border:1px solid rgba(255,255,255,.12);background:rgba(3,0,50,.38);-webkit-backdrop-filter:blur(10px);backdrop-filter:blur(10px)}
.rv-ct__city{display:flex;justify-content:space-between;align-items:baseline;gap:10px}
.rv-ct__city span{font-family:'Manrope',Arial,sans-serif;font-weight:800;font-size:1.05rem;text-transform:uppercase;letter-spacing:.02em;color:#fff}
.rv-ct__city time{font-family:'JetBrains Mono',monospace;font-size:.95rem;font-weight:500;color:var(--gold);font-variant-numeric:tabular-nums}
.rv-ct__addr{margin-top:6px!important;font-size:.84rem;line-height:1.45;color:#fff}
.rv-ct__tz{margin-top:8px!important;font-family:'JetBrains Mono',monospace;font-size:.58rem;letter-spacing:.12em;text-transform:uppercase;color:#fff;opacity:.7}

/* — ПК и планшет: каналы связи — три плитки с иконками, как на телефоне.
     Номер под подписью оставлен: на ПК по tel: звонок часто не открывается — номер видно сразу. — */
@media(min-width:768px){
  .rv-ct__channels{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;border-top:0}
  .rv-ct__channels li{border:0}
  .rv-ct__ch{grid-template-columns:1fr;justify-items:center;text-align:center;gap:12px;padding:22px 12px 20px;border-radius:22px;
    border:1px solid rgba(255,255,255,.14);background:rgba(3,0,50,.42);-webkit-backdrop-filter:blur(10px);backdrop-filter:blur(10px);
    transition:border-color .35s ease,transform .45s var(--ease),box-shadow .45s var(--ease)}
  .rv-ct__ch:hover,.rv-ct__ch:focus-visible{border-color:rgba(245,197,66,.55);transform:translateY(-4px);box-shadow:0 18px 40px rgba(0,0,0,.35),0 0 30px rgba(245,197,66,.12)}
  .rv-ct__ch::before{background:radial-gradient(80% 70% at 50% 0%,rgba(245,197,66,.16),transparent 70%);transform:none;opacity:0;transition:opacity .45s ease}
  .rv-ct__ch:hover::before,.rv-ct__ch:focus-visible::before{opacity:1;transform:none}
  .rv-ct__ico{width:54px;height:54px}
  .rv-ct__ico svg{width:22px;height:22px}
  .rv-ct__chtext{min-width:0;width:100%}
  .rv-ct__chtext small{font-size:.6rem}
  .rv-ct__chtext b{margin-top:5px;font-size:clamp(13px,1.05vw,15px);font-weight:600;letter-spacing:0;opacity:.85}
  .rv-ct__arrow{display:none}
}

/* — карточка с формой: по центру высоты левой колонки — */
.rv-ct__right{align-self:center}
.rv-ct__card{position:relative;padding:clamp(24px,2.6vw,36px);border-radius:30px;overflow:hidden;
  background:linear-gradient(160deg,rgba(255,255,255,.1),rgba(255,255,255,.03) 70%);border:1px solid rgba(255,255,255,.16);
  box-shadow:0 40px 90px rgba(0,0,0,.45),inset 0 1px 0 rgba(255,255,255,.12);-webkit-backdrop-filter:blur(20px);backdrop-filter:blur(20px)}
.rv-ct__card::before{content:"";position:absolute;left:30px;right:30px;top:0;height:1px;background:linear-gradient(90deg,transparent,var(--gold),transparent)}
.rv-ct__card::after{content:"";position:absolute;right:-30%;top:-40%;width:70%;height:80%;pointer-events:none;background:radial-gradient(circle,rgba(245,197,66,.14),transparent 65%)}
.rv-ct__card-head{display:flex;flex-direction:column-reverse;align-items:flex-start;gap:14px}
.rv-ct__card-title{font-family:'Manrope',Arial,sans-serif;font-weight:800;font-size:clamp(22px,2vw,30px);line-height:1.1;letter-spacing:-.02em;color:#fff}
.rv-ct__online{display:inline-flex;align-items:center;gap:8px;padding:6px 13px 6px 12px;border-radius:99px;border:1px solid rgba(91,217,138,.35);background:rgba(91,217,138,.08);
  font-family:'JetBrains Mono',monospace;font-size:.6rem;letter-spacing:.12em;text-transform:uppercase;color:#5BD98A}
.rv-ct__online i{width:6px;height:6px;border-radius:50%;background:#5BD98A;box-shadow:0 0 8px #5BD98A;animation:rvCtLive 2s ease-in-out infinite}
.rv-ct__card-sub{margin-top:10px!important;font-size:.95rem;line-height:1.55;color:#fff;max-width:46ch}
#s11 .rv-ct__card .s11__form{position:relative;z-index:1;width:100%!important;max-width:none!important;margin:22px 0 0!important;padding:0!important}
#s11 .rv-ct__card .s11__form .wpcf7-form p{margin:0 0 14px!important}
#s11 .rv-ct__card .s11__form .wpcf7-form-control-wrap{width:100%!important;max-width:none!important;margin:0!important}
#s11 .rv-ct__card .s11__form input.wpcf7-submit{width:100%!important;max-width:none!important;margin:4px 0 0!important}
.rv-ct__note{position:relative;z-index:1;margin-top:14px!important;font-size:.78rem;line-height:1.5;color:#fff;opacity:.85}
.rv-ct__note a{color:var(--gold)!important;text-decoration:none!important;border-bottom:1px solid rgba(245,197,66,.5)}

/* отступы плашек: сильнее сброса #s11 .rv-ct p */
#s11 .rv-ct .rv-ct__chip{padding:.5rem 1.05rem .5rem .85rem}
#s11 .rv-ct .rv-ct__online{padding:6px 13px 6px 11px}

@keyframes rvCtLive{50%{opacity:.35}}

/* — планшет — */
@media(max-width:1024px){
  .rv-ct{grid-template-columns:1fr}
  .rv-ct__right{order:2;align-self:stretch}
  .rv-ct__title{font-size:clamp(34px,5.6vw,58px)}
}
/* — телефон: заголовок → форма → каналы → офисы — */
@media(max-width:767px){
  section.s11#s11{padding:56px 0 30vh!important}
  .rv-ct{display:flex;flex-direction:column;gap:0}
  .rv-ct__left{display:contents}
  .rv-ct__chip{order:1;align-self:flex-start}
  .rv-ct__title{order:2;font-size:clamp(28px,8vw,36px)}
  .rv-ct__title em{white-space:normal}
  .rv-ct__lead{order:3;font-size:.95rem}
  .rv-ct__right{order:4;margin-top:22px}
  .rv-ct__channels{order:5}
  .rv-ct__offices{order:6;grid-template-columns:1fr}
  .rv-ct__card{padding:22px 18px;border-radius:24px}
  .rv-ct__ch{grid-template-columns:44px minmax(0,1fr) 36px;gap:12px;padding:12px 2px}
  .rv-ct__ico{width:44px;height:44px}
  .rv-ct__arrow{width:36px;height:36px}
  .rv-ct__chtext b{font-size:18px}
}
/* — телефон, компактно: ~1 экран вместо 2,5 —
   убраны вводный абзац, подзаголовок карточки и офисы (Київ/Ірвайн),
   каналы связи — одной строкой из трёх плиток */
@media(max-width:767px){
  section.s11#s11{padding:40px 0 150px!important} /* снизу — окно, чтобы была видна планета на фоне */
  #s11 .rv-ct .rv-ct__chip{padding:.4rem .9rem .4rem .75rem;font-size:.58rem}
  #s11 .rv-ct .rv-ct__title{margin-top:14px!important;font-size:clamp(26px,7.4vw,32px)}
  .rv-ct__lead,.rv-ct__card-sub,.rv-ct__offices{display:none!important}
  .rv-ct__right{margin-top:32px} /* воздух между заголовком и формой */
  .rv-ct__card{padding:18px 16px 16px;border-radius:22px}
  .rv-ct__card-head{flex-direction:row;align-items:center;justify-content:space-between;gap:10px}
  .rv-ct__card-title{font-size:18px;line-height:1.15}
  #s11 .rv-ct .rv-ct__online{padding:5px 10px 5px 9px;font-size:.52rem;flex:none}
  #s11 .rv-ct__card .s11__form{margin-top:14px!important}
  #s11 .rv-ct__card .s11__form .wpcf7-form p{margin:0 0 10px!important}
  #s11 .rv-ct .rv-ct__note{margin-top:10px!important;font-size:.7rem}
  .rv-ct__channels{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:8px;margin-top:12px!important;border-top:0}
  .rv-ct__channels li{border:0}
  .rv-ct__ch{grid-template-columns:1fr;justify-items:center;gap:6px;padding:10px 4px;border-radius:16px;
    border:1px solid rgba(255,255,255,.14);background:rgba(3,0,50,.42);-webkit-backdrop-filter:blur(10px);backdrop-filter:blur(10px);text-align:center}
  .rv-ct__ico{width:36px;height:36px}
  .rv-ct__ico svg{width:17px;height:17px}
  .rv-ct__chtext small{font-size:.56rem;letter-spacing:.1em;color:#fff}
  .rv-ct__chtext b,.rv-ct__arrow{display:none}
  .rv-ct__ch:active .rv-ct__ico{background:var(--gold);border-color:var(--gold)}
  .rv-ct__ch:active .rv-ct__ico svg{stroke:#030032}
}
@media(prefers-reduced-motion:reduce){.rv-ct__chip i,.rv-ct__online i{animation:none}}
</style>

<script id="rv-ct-js">
/* Местное время офисов — обновляется раз в 20 секунд */
(function(){
  'use strict';
  var els = document.querySelectorAll('#s11 [data-rv-tz]');
  if (!els.length || !window.Intl) return;
  function tick(){
    Array.prototype.forEach.call(els, function(el){
      try {
        el.textContent = new Intl.DateTimeFormat('uk-UA', { hour: '2-digit', minute: '2-digit', hour12: false, timeZone: el.getAttribute('data-rv-tz') }).format(new Date());
      } catch (e) {}
    });
  }
  tick();
  setInterval(tick, 20000);
})();
</script>
</section>
<script>
/* Видео получает src и запускается только при приближении к нижнему блоку. */
(function(){
  'use strict';
  var video = document.querySelector('#s11 .rv-lazy-video[data-src]');
  if (!video) return;

  function loadVideo(){
    if (video.dataset.rvLoaded) return;
    var source = video.getAttribute('data-src');
    if (!source) return;

    video.dataset.rvLoaded = '1';
    video.removeAttribute('data-src');
    video.muted = true;
    video.loop = true;
    video.playsInline = true;
    video.autoplay = true;

    function revealAndPlay(){
      var section = document.getElementById('s11');
      if (section) section.classList.add('rv-video-ready');
      var promise = video.play();
      if (promise && promise.catch) promise.catch(function(){});
    }

    video.addEventListener('canplay', revealAndPlay, { once:true });
    video.src = source;
    video.load();
    if (video.readyState >= 3) revealAndPlay();
  }

  if (!('IntersectionObserver' in window)){
    window.addEventListener('load', loadVideo, { once:true });
    return;
  }

  var observer = new IntersectionObserver(function(entries){
    if (!entries[0].isIntersecting) return;
    loadVideo();
    observer.disconnect();
  }, { rootMargin:'0px 0px 700px 0px', threshold:0 });

  observer.observe(video);
})();
</script>
<script>
/* ============================================================================
   ФУТЕР: сцена отвечает форме
   ----------------------------------------------------------------------------
   Фон перестаёт быть равномерным во времени и получает событие.
     • фокус в поле  → сцена отступает: движение замедляется, яркость падает,
                        внимание уходит на форму;
     • заявка ушла   → снизу вверх один раз проходит волна света.
   Отправку ловим по событию Contact Form 7 (wpcf7mailsent). Если плагин
   почему-то не отдаст событие — есть запасной путь через submit формы.
   ========================================================================= */
(function(){
  'use strict';
  var s11 = document.querySelector('.s11');
  if (!s11) return;

  /* --- фокус: сцена отступает, пока человек печатает --- */
  var fields = s11.querySelectorAll('input, textarea');
  var blurT = null;
  Array.prototype.forEach.call(fields, function(f){
    if (f.type === 'submit') return;
    f.addEventListener('focus', function(){
      clearTimeout(blurT);
      s11.classList.add('is-focus');
    });
    f.addEventListener('blur', function(){
      /* небольшая пауза: переход между полями не должен «моргать» сценой */
      clearTimeout(blurT);
      blurT = setTimeout(function(){
        if (!s11.querySelector(':focus')) s11.classList.remove('is-focus');
      }, 220);
    });
  });

  /* --- отправлено: волна света --- */
  var waveT = null;
  function wave(){
    s11.classList.remove('is-focus');
    s11.classList.remove('is-sent');
    void s11.offsetWidth;              /* перезапуск анимации */
    s11.classList.add('is-sent');
    clearTimeout(waveT);
    waveT = setTimeout(function(){ s11.classList.remove('is-sent'); }, 2200);
  }
  document.addEventListener('wpcf7mailsent', function(e){
    if (s11.contains(e.target) || !document.querySelector('.wpcf7')) wave();
  }, false);

  /* запасной путь, если события CF7 нет */
  var form = s11.querySelector('form');
  if (form) form.addEventListener('submit', function(){ setTimeout(wave, 600); });
})();
</script>

<div class="rv-wd-sticky" id="rv-wd-sticky">
	<span><?php echo wp_kses( preg_replace( '/(\$\d+)/', '<b>$1</b>', esc_html( $T['sticky'] ) ), array( 'b' => array() ) ); ?></span>
	<a href="#rv-wd-request"><?php echo esc_html( $T['sticky_b'] ); ?></a>
</div>

<?php
/* Розмітка для Google: послуга з цінами + FAQ */
$rv_wd_faq_ld = array();
foreach ( $T['fq'] as $rv_q ) {
	$rv_wd_faq_ld[] = array( '@type' => 'Question', 'name' => $rv_q[0], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $rv_q[1] ) );
}
$rv_wd_ld = array(
	'@context' => 'https://schema.org',
	'@graph'   => array(
		array(
			'@type'       => 'Service',
			'name'        => $T['service_name'],
			'description' => $T['lead'],
			'provider'    => array( '@type' => 'Organization', 'name' => 'Revive Agency', 'url' => home_url( '/' ), 'telephone' => '+380678000830' ),
			'areaServed'  => 'Worldwide',
			'offers'      => array(
				array( '@type' => 'Offer', 'name' => $T['pk'][0][1], 'price' => $rv_wd_prices['start'], 'priceCurrency' => 'USD' ),
				array( '@type' => 'Offer', 'name' => $T['pk'][1][1], 'price' => $rv_wd_prices['sales'], 'priceCurrency' => 'USD' ),
				array( '@type' => 'Offer', 'name' => $T['pk'][2][1], 'price' => $rv_wd_prices['premium'], 'priceCurrency' => 'USD' ),
			),
		),
		array( '@type' => 'FAQPage', 'mainEntity' => $rv_wd_faq_ld ),
	),
);
?>
<script type="application/ld+json"><?php echo wp_json_encode( $rv_wd_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?></script>

<script id="rv-wd-js">
(function(){
  'use strict';
  var root = document.getElementById('rv-wd');
  if (!root) return;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var EN = <?php echo $rv_en ? 'true' : 'false'; ?>;

  /* появление блоков */
  var items = root.querySelectorAll('[data-rv]');
  if (!reduce && 'IntersectionObserver' in window) {
    root.classList.add('rv-wd-js');
    var io = new IntersectionObserver(function(es){
      es.forEach(function(e){ if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); } });
    }, { threshold: .14, rootMargin: '0px 0px -40px 0px' });
    items.forEach(function(el){ io.observe(el); });
  } else {
    items.forEach(function(el){ el.classList.add('is-in'); });
  }

  /* свет за курсором на карточках */
  if (!reduce && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
    root.querySelectorAll('.rv-wd-card').forEach(function(c){
      c.addEventListener('pointermove', function(e){
        var r = c.getBoundingClientRect();
        c.style.setProperty('--mx', (e.clientX - r.left) + 'px');
        c.style.setProperty('--my', (e.clientY - r.top) + 'px');
      }, { passive: true });
    });
  }

  /* калькулятор */
  var calc = root.querySelector('[data-rv-calc]');
  if (calc) {
    var types = [].slice.call(calc.querySelectorAll('.rv-wd-ca__type'));
    var opts  = [].slice.call(calc.querySelectorAll('.rv-wd-ca__opt'));
    var elT = calc.querySelector('[data-calc-total]'), elTag = calc.querySelector('[data-calc-tag]');
    var elPre = calc.querySelector('[data-calc-pre]'), elPost = calc.querySelector('[data-calc-post]');
    var box = calc.querySelector('.rv-wd-ca__total');
    var shown = +elT.textContent || 0, raf = 0;
    function fmt(n){ return String(Math.round(n)).replace(/\B(?=(\d{3})+(?!\d))/g, ' '); }
    function money(n){ return '$' + fmt(n); }
    function sum(){
      var t = types.filter(function(b){ return b.classList.contains('is-on'); })[0] || types[0];
      var total = +t.dataset.price;
      opts.forEach(function(o){ if (o.classList.contains('is-on')) total += +o.dataset.price; });
      elTag.textContent = t.dataset.label;
      elPre.textContent = money(total / 2); elPost.textContent = money(total / 2);
      animate(total);
      box.classList.remove('is-bump'); void box.offsetWidth; box.classList.add('is-bump');
    }
    function animate(to){
      cancelAnimationFrame(raf);
      if (reduce) { elT.textContent = fmt(to); shown = to; return; }
      var from = shown, t0 = 0;
      raf = requestAnimationFrame(function f(ts){
        if (!t0) t0 = ts;
        var p = Math.min(1, (ts - t0) / 600), e = 1 - Math.pow(1 - p, 3);
        shown = from + (to - from) * e;
        elT.textContent = fmt(shown);
        if (p < 1) raf = requestAnimationFrame(f); else shown = to;
      });
    }
    types.forEach(function(b){
      b.addEventListener('click', function(){
        types.forEach(function(x){ x.classList.remove('is-on'); x.setAttribute('aria-checked', 'false'); });
        b.classList.add('is-on'); b.setAttribute('aria-checked', 'true'); sum();
      });
    });
    opts.forEach(function(o){
      o.addEventListener('click', function(){
        var on = !o.classList.contains('is-on');
        o.classList.toggle('is-on', on); o.setAttribute('aria-pressed', on ? 'true' : 'false'); sum();
      });
    });
    sum();
  }

  /* выбор тарифа запоминается и подставляется в форму заявки (если в форме есть поле сообщения) */
  function note(){
    var calcBox = root.querySelector('[data-rv-calc]'), txt = '';
    if (calcBox) {
      var t = calcBox.querySelector('.rv-wd-ca__type.is-on');
      var extra = [].slice.call(calcBox.querySelectorAll('.rv-wd-ca__opt.is-on strong')).map(function(s){ return s.textContent; });
      var total = t ? +t.dataset.price : 0;
      calcBox.querySelectorAll('.rv-wd-ca__opt.is-on').forEach(function(o){ total += +o.dataset.price; });
      txt = (EN ? 'Estimate: ' : 'Попередня оцінка: ') + (t ? t.dataset.label : '') + (extra.length ? ' + ' + extra.join(', ') : '') + ' — $' + String(total).replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    }
    return txt;
  }
  root.querySelectorAll('a[href="#rv-wd-request"]').forEach(function(a){
    a.addEventListener('click', function(e){
      if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
      e.preventDefault();
      var destination = document.getElementById('rv-wd-request');
      if (!destination) return;
      var msg = root.querySelectorAll('#rv-wd-request textarea, #rv-wd-request input[name*="message"], #rv-wd-request input[name*="comment"]');
      var t = a.hasAttribute('data-calc-cta') ? note() : (a.dataset.rvPlan ? (a.closest('.rv-wd-pk') ? a.closest('.rv-wd-pk').querySelector('h3').textContent : (EN ? 'Landing page — special price' : 'Лендінг — спецціна')) : '');

      /* Попап с формой оценки (inc/rv-lead.php). Тариф → тип проекта,
         калькулятор → тип, бюджет и расчёт в поле «задача». */
      /* на лендінгу попап з довгою формою вимкнено — кнопки ведуть до простої форми внизу */
      if (false && window.RVLead){
        var o = { task: t };
        if (a.dataset.rvPlan) o.service = a.dataset.rvPlan;
        if (a.hasAttribute('data-calc-cta')){
          var cb = root.querySelector('[data-rv-calc]'), ty = cb && cb.querySelector('.rv-wd-ca__type.is-on');
          if (ty){
            o.service = ty.dataset.type;
            o.total = +ty.dataset.price || 0;
            cb.querySelectorAll('.rv-wd-ca__opt.is-on').forEach(function(x){ o.total += +x.dataset.price || 0; });
          }
        }
        window.RVLead.open((a.textContent || '').replace(/\s+/g, ' ').trim().slice(0, 60), o);
        return;
      }

      if (t) msg.forEach(function(m){ if (!m.value) m.value = t; });
      history.pushState(null, '', '#rv-wd-request');
      window.scrollTo({ top: destination.getBoundingClientRect().top + window.scrollY - 110, behavior: reduce ? 'instant' : 'smooth' });
    });
  });

  /* каруселі на телефоні: лічильник 01 / 04, смужка прогресу, «Гортайте» */
  var ARROW = '<svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
  root.querySelectorAll('[data-rv-car]').forEach(function(track){
    var items = [].slice.call(track.children);
    if (items.length < 2) return;
    var nav = document.createElement('div');
    nav.className = 'rv-car-nav'; nav.setAttribute('aria-hidden', 'true');
    var tot = (items.length < 10 ? '0' : '') + items.length;
    nav.innerHTML = '<span class="rv-car-nav__count"><b>01</b><span>/ ' + tot + '</span></span><span class="rv-car-nav__bar"><i></i></span>' +
      '<button type="button" class="rv-car-nav__next" tabindex="-1">' + (EN ? 'Swipe' : 'Гортайте') + ' ' + ARROW + '</button>';
    track.parentNode.insertBefore(nav, track);
    var cur = nav.querySelector('b'), bar = nav.querySelector('.rv-car-nav__bar i'), raf = 0, last = -1;
    function idx(){
      var step = items[1].offsetLeft - items[0].offsetLeft || 1;
      var i = Math.round(track.scrollLeft / step);
      if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 4) i = items.length - 1;
      return Math.max(0, Math.min(items.length - 1, i));
    }
    function sync(){ raf = 0; var i = idx(); if (i === last) return; last = i;
      cur.textContent = (i < 9 ? '0' : '') + (i + 1); bar.style.setProperty('--p', ((i + 1) / items.length).toFixed(3)); }
    track.addEventListener('scroll', function(){ if (!raf) raf = requestAnimationFrame(sync); }, { passive: true });
    nav.querySelector('button').addEventListener('click', function(){
      var n = (idx() + 1) % items.length;
      track.scrollTo({ left: items[n].offsetLeft - items[0].offsetLeft, behavior: reduce ? 'auto' : 'smooth' });
    });
    sync();
  });

  /* калькулятор на телефоні: «Усі опції» */
  var more = root.querySelector('[data-ca-more]');
  if (more) more.addEventListener('click', function(){
    var g = root.querySelector('.rv-wd-ca__opts'); if (g) g.classList.add('is-all'); more.classList.add('is-hidden');
  });

  /* липкая кнопка на телефоне: после первого экрана и не над формой */
  var sticky = document.getElementById('rv-wd-sticky');
  var hideOver = [document.getElementById('s11'), document.getElementById('rv-wd-calc'), document.getElementById('rv-wd-offer')];
  if (sticky) {
    var tick = 0;
    function over(el){ if (!el) return false; var r = el.getBoundingClientRect(); return r.top < window.innerHeight * .85 && r.bottom > window.innerHeight * .15; }
    function upd(){
      tick = 0;
      /* не спорим с калькулятором, спецпредложением и формой — там своя кнопка */
      var show = window.scrollY > window.innerHeight * .8 && !hideOver.some(over);
      sticky.classList.toggle('is-on', show);
    }
    window.addEventListener('scroll', function(){ if (!tick) tick = requestAnimationFrame(upd); }, { passive: true });
    upd();
  }

  /* SEO-текст: розгорнути / згорнути (текст весь у HTML — для пошуковиків) */
  var seo = root.querySelector('.rv-wd-seo');
  if (seo) {
    var sb = seo.querySelector('.rv-wd-seo__body'), st = seo.querySelector('.rv-wd-seo__toggle');
    if (sb && st) {
      if (sb.scrollHeight <= sb.clientHeight + 40) { sb.style.maxHeight = 'none'; seo.classList.add('is-open'); st.hidden = true; }
      st.addEventListener('click', function(){
        var open = !seo.classList.contains('is-open');
        if (open) { sb.style.maxHeight = sb.scrollHeight + 'px'; setTimeout(function(){ if (seo.classList.contains('is-open')) sb.style.maxHeight = 'none'; }, 900); }
        else { sb.style.maxHeight = sb.scrollHeight + 'px'; void sb.offsetHeight; sb.style.maxHeight = ''; seo.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'start' }); }
        seo.classList.toggle('is-open', open);
        st.setAttribute('aria-expanded', open ? 'true' : 'false');
        st.querySelector('span').textContent = open ? st.dataset.less : st.dataset.more;
      });
    }
  }
})();
</script>
</main>
<?php
get_footer();