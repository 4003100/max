<?php
/**
 * Template Name: Revive — Розробка інтернет-магазину
 *
 * Посадкова сторінка «Розробка інтернет-магазину під ключ» (UA / EN через Polylang).
 * Один шаблон на обидві мови: створіть сторінку українською, додайте переклад
 * англійською і в обох виберіть цей шаблон (Атрибути сторінки → Шаблон).
 *
 * SEO-текст унизу сторінки: якщо в редакторі сторінки є текст — виводиться він,
 * якщо редактор порожній — стандартний текст із масиву $T['seo'] нижче.
 *
 * НІШЕВІ СТОРІНКИ (одяг, косметика…): той самий шаблон. Створіть сторінку зі слагом
 * із масиву $rv_niche_data, оберіть цей шаблон — H1, title, description і SEO-текст
 * ніші підставляться автоматично.
 *
 * Усі тексти, ціни та кейси — у масивах нижче. Стилі та скрипти — всередині файлу.
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
   ЦІНИ ПАКЕТІВ і опцій калькулятора — змінюйте тут,
   решта сторінки (картки, калькулятор, FAQ, розмітка) підхопить.
   ============================================================ */
$rv_wd_prices = array(
	'start'    => 2000,
	'business' => 3000,
	'pro'      => 4000,
);
$rv_wd_addons = array( // ключ => [UA, EN, ціна $]
	'crm'     => array( 'Інтеграція з CRM (KeyCRM, SalesDrive, Bitrix24)', 'CRM integration (KeyCRM, SalesDrive, Bitrix24)', 400 ),
	'feeds'   => array( 'Фіди для Google Merchant, Rozetka, Prom', 'Feeds for Google Merchant and marketplaces', 250 ),
	'import'  => array( 'Імпорт товарів з Excel / XML постачальників', 'Product import from Excel / supplier XML', 300 ),
	'lang'    => array( 'Друга мова магазину', 'Second store language', 300 ),
	'erp'     => array( 'Синхронізація з 1С / BAS (залишки, ціни)', 'ERP sync (stock and prices)', 600 ),
	'account' => array( 'Кабінет покупця + бонусна програма', 'Customer account + loyalty programme', 500 ),
	'copy'    => array( 'SEO-тексти для категорій', 'SEO copy for categories', 300 ),
	'migrate' => array( 'Перенесення з OpenCart / Prom / Shopify + 301-редиректи', 'Migration from another platform + 301 redirects', 500 ),
	'prro'    => array( 'Фіскалізація чеків (Checkbox / ПРРО)', 'Receipt fiscalisation', 200 ),
	'motion'  => array( 'Преміум-анімації', 'Premium animations', 300 ),
);

$T = $rv_en ? array(
	'chip' => 'Turnkey online store · WooCommerce',
	'h1a' => 'Turnkey online store development',
	'h1b' => 'built to sell',
	'lead' => 'We plan, design, build and launch online stores on WooCommerce: catalogue and filters, a fast checkout, online payments, shipping, CRM and an SEO-ready category structure — one project, one team.',
	'hero_price' => 'Turnkey online stores from ' . '$' . number_format( $rv_wd_prices['start'], 0, '.', ' ' ),
	'hero_terms' => 'Fixed price in the contract · 70/30 payment',
	'hero_note' => 'Tell us what you sell and roughly how many products — that is enough for an estimate.',
	'service_name' => 'Turnkey online store development',
	'cta1' => 'Get a store estimate',
	'cta2' => 'See e-commerce projects',
	'stats' => array( array( '120+', 'brands have chosen us' ), array( '12', 'years in web development' ), array( '21', 'countries' ), array( '95%', 'returning clients' ) ),
	'vis_url' => 'yourstore.com',
	'vis_cart' => 'CART (2) ↗',
	'vis_kicker' => 'New collection',
	'vis_h' => 'Choose.<br><em>Buy.</em>',
	'vis_sub' => 'Delivery · Payment · Returns',
	'vis_ph' => 'Pay<br><em>in 1 tap.</em>',
	'vis_ph_btn' => 'PAY ↗',
	'toast1' => 'New order #1024',
	'toast1_s' => 'Telegram · now',
	'toast2' => 'Checkout',
	'toast2_value' => '3 steps to purchase',
	'toast3' => 'Payment received',
	'toast3_value' => '+$89',
	'pn_chip' => 'What we fix before launch',
	'pn_t' => 'Where online stores <em>lose money</em>',
	'pn_lead' => 'Most stores do not lose sales because of the product — they lose them on the way to the order. We design the path from first visit to payment so that nothing gets in the way.',
	'pn_l1' => 'Problem',
	'pn_l2' => 'How we solve it',
	'pn' => array(
		array( 'Customers can’t find the product', 'Clear categories, filters by the attributes that matter in your niche and search with suggestions — the right product in two or three clicks.' ),
		array( 'They abandon the cart at checkout', 'A one-page checkout, guest purchase, address and branch autocomplete and only the fields that are really needed.' ),
		array( 'The store is slow on mobile', 'Mobile-first layouts, optimised images and caching. We check Core Web Vitals before launch.' ),
		array( 'Managers drown in manual work', 'Orders go straight to CRM, shipping labels are created automatically and customers get status updates without calls.' ),
		array( 'Google doesn’t show the store', 'An SEO structure for categories and filters, Product markup, clean URLs and a Google Merchant feed from day one.' ),
	),
	'of_chip' => 'Turnkey online store',
	'of_t' => 'A store ready to sell <br>from <em>' . '$' . number_format( $rv_wd_prices['start'], 0, '.', ' ' ) . '</em>',
	'of_lead' => 'Not a template with a logo swapped in: a store designed around your range, customers and processes. Every store includes:',
	'of_alt_t' => 'Need a different scope?',
	'of_alt' => 'We’ll estimate your project individually: match the features to your goals and budget, and launch in stages if needed — starting with what drives sales.',
	'of_inc' => array( 'Custom design: desktop + mobile', 'Catalogue with filters and search', 'Cart and fast checkout', 'Online payments + Apple / Google Pay', 'Shipping integration', 'WooCommerce admin + training', 'SEO structure + Product markup', 'GA4 e-commerce + Meta Pixel' ),
	'of_meta' => array( 'Launch from 3 weeks', 'Price and timeline fixed in the contract' ),
	'of_cta' => 'Get my personal estimate',
	'pk_chip' => 'Packages',
	'pk_t' => 'A store for <em>your stage of growth</em>',
	'pk' => array(
		array( 'start', 'Start', 'To launch online sales or move from marketplaces and Instagram to your own store.', array( 'Custom design', 'Catalogue up to 500 products', 'Payments + shipping', 'SEO structure for categories', 'GA4 e-commerce analytics' ), 'From 3 weeks', true ),
		array( 'business', 'Business', 'For a store that is growing its range, advertising and order volume.', array( 'Everything in Start', 'Catalogue up to 10,000 products', 'Smart filters and search', 'CRM integration', 'Imports and marketplace feeds' ), 'From 4 weeks', false ),
		array( 'pro', 'Pro / B2B', 'For large catalogues, wholesale, several warehouses, languages and currencies.', array( 'Everything in Business', 'ERP sync for stock and prices', 'Wholesale prices + client account', 'Multi-language and multi-currency', 'Custom functionality' ), 'From 5 weeks', false ),
	),
	'pk_badge' => 'Quick start',
	'pk_pop' => 'Best value',
	'pk_from' => 'from',
	'pk_cta' => 'Get an estimate',
	'pk_note' => 'Already have a store on OpenCart, Shopify or a marketplace? We’ll assess what can be migrated, plan redirects and keep your search traffic in mind.',
	'ca_chip' => 'Budget guide',
	'ca_t' => 'Estimate <em>your store</em>',
	'ca_lead' => 'Choose a package and the extras you need. This is a starting estimate: we confirm the scope, integrations and final price after discussing your project.',
	'ca_1' => 'Which package fits?',
	'ca_2' => 'Additional integrations and features',
	'ca_types' => array( 'start' => array( 'Start', 'Up to 500 products' ), 'business' => array( 'Business', 'Up to 10,000 products' ), 'pro' => array( 'Pro / B2B', 'Large catalogue, wholesale' ) ),
	'ca_default' => 'business',
	'ca_total' => 'Indicative budget',
	'ca_pay' => 'Payment in two stages',
	'ca_pre' => '70% at the start of the project',
	'ca_post' => '30% at launch',
	'ca_inc' => 'Included in every store',
	'ca_incl' => array( 'Mobile-first design', 'Payments + shipping', 'Basic SEO + Product markup', 'GA4 e-commerce' ),
	'ca_cta' => 'Discuss this estimate',
	'ca_note' => 'The final scope, price and timeline are agreed in the contract before work begins.',
	'in_chip' => 'Integrations',
	'in_t' => 'We connect everything <em>you work with</em>',
	'in_lead' => 'Payments, shipping, CRM, accounting and marketing — the store becomes part of your sales system, not a separate island.',
	'in' => array(
		array( 'Payments', array( 'LiqPay', 'WayForPay', 'monobank', 'Stripe', 'PayPal', 'Apple Pay', 'Google Pay' ) ),
		array( 'Shipping', array( 'Nova Poshta', 'Ukrposhta', 'Meest', 'Pickup points', 'Courier delivery' ) ),
		array( 'CRM and accounting', array( 'KeyCRM', 'SalesDrive', 'Bitrix24', '1C / BAS', 'Checkbox' ) ),
		array( 'Marketing', array( 'Google Merchant', 'GA4', 'Meta Pixel', 'Rozetka', 'Prom', 'Hotline', 'eSputnik' ) ),
	),
	'pr_chip' => 'A clear process',
	'pr_t' => 'From idea to <em>first order</em>',
	'pr' => array(
		array( 'Analysis and structure', 'We study your range, competitors and search demand. We plan categories, filters and the product page.', 'Store map' ),
		array( 'Prototype and design', 'Catalogue, product page, cart and checkout for desktop and mobile — in your brand style.', 'Your approval' ),
		array( 'Development and integrations', 'We build on WooCommerce and connect payments, shipping, CRM and product import.', 'Working store' ),
		array( 'Content and testing', 'We load products, place test orders and payments and check speed and emails.', 'Ready to sell' ),
		array( 'Launch and growth', 'Launch on your domain, analytics, indexing and training for your team.', 'First orders' ),
	),
	'cs_chip' => 'E-commerce projects',
	'cs_t' => 'Stores that <em>bring results</em>',
	'cs_lead' => 'Selected results from online store projects where development was combined with SEO or advertising. The work involved is shown on each card.',
	'cs_all' => 'Explore the portfolio',
	'ni_chip' => 'By niche',
	'ni_t' => 'Online stores <em>for your niche</em>',
	'ni_lead' => 'Every niche has its own buying logic: sizes and colours, VIN search, wholesale prices or certificates. We design the catalogue and checkout around how your customers choose.',
	'gu_chip' => 'Clear terms',
	'gu_t' => 'You know what <em>you are paying for</em>',
	'gu' => array(
		array( 'Fixed price in the contract', 'We fix the scope and price before work begins. Any additional work is discussed and approved before it starts.' ),
		array( 'Payment in two stages', '70% at the start of the project and 30% at launch. No hidden fees or platform subscriptions from us.' ),
		array( 'The store is fully yours', 'We hand over the code, admin access, domain and hosting. No lock-in to our agency.' ),
		array( 'Tested before launch', 'We place test orders, payments and shipments and check emails and analytics before going live.' ),
	),
	'fq_chip' => 'Before you decide',
	'fq_t' => 'Questions about <em>online store development</em>',
	'fq' => array(
		array( 'How much does turnkey online store development cost?', 'The Start package is from ' . '$' . number_format( $rv_wd_prices['start'], 0, '.', ' ' ) . ', Business from ' . '$' . number_format( $rv_wd_prices['business'], 0, '.', ' ' ) . ', Pro / B2B from ' . '$' . number_format( $rv_wd_prices['pro'], 0, '.', ' ' ) . '. The final price depends on catalogue size, integrations, languages and custom features. We fix it in the contract before starting.' ),
		array( 'How long does it take to build an online store?', 'Start takes from 3 weeks, Business from 4 weeks and Pro / B2B from 5 weeks. The schedule also depends on how quickly product data and approvals are ready.' ),
		array( 'Why WooCommerce and not Shopify or a SaaS builder?', 'With WooCommerce the store belongs to you: no monthly platform fee or sales commission, full control over SEO and any integration you need. If your task is better served by another platform, we will say so.' ),
		array( 'Can you migrate my store without losing SEO?', 'Yes. We move products, categories and customers, set up 301 redirects and check indexing after launch. Migration is scoped separately; rankings also depend on search engines and competitors.' ),
		array( 'Which payment and shipping methods can you connect?', 'Card payments and Apple / Google Pay via LiqPay, WayForPay, monobank or Stripe, PayPal, cash on delivery, Nova Poshta, Ukrposhta, Meest and courier delivery. Other services can be connected on request.' ),
		array( 'Who will add the products?', 'We import products from Excel, XML or your accounting system, or your team adds them in the admin. Initial content upload can be included in the project.' ),
		array( 'Will I be able to manage the store myself?', 'Yes. The WooCommerce admin lets you change products, prices, stock and orders. After launch we train your team and hand over a short guide.' ),
		array( 'What happens after launch?', 'We check orders, payments and analytics after going live. Ongoing support, SEO and Google Ads can be agreed separately — our team handles them too.' ),
		array( 'How does payment work?', '70% at the start of the project and 30% at launch. The agreed scope and price go into the contract. We work with clients in Ukraine and internationally.' ),
	),
	'seo_chip' => 'About e-commerce development',
	'seo_more' => 'Read more',
	'seo_less' => 'Collapse',
	'seo' => '<h2>Turnkey online store development: what you get</h2>
<p>An online store is not just a website with a cart. It is a sales channel that has to attract traffic, help customers choose, accept payments and hand orders to your team without manual work. That is why at Revive Agency we approach <strong>online store development</strong> as a business project — from analysing your range and demand to the first orders and analytics.</p>
<h3>What a turnkey store includes</h3>
<ul>
<li>analysis of your niche, competitors and search demand;</li>
<li>catalogue structure, filters and SEO-friendly URLs;</li>
<li>custom design for desktop and mobile;</li>
<li>a product page that answers questions and builds trust;</li>
<li>cart and checkout, online payments and shipping;</li>
<li>CRM, accounting and marketplace integrations;</li>
<li>GA4 e-commerce analytics and Meta Pixel;</li>
<li>launch, testing and training for your team.</li>
</ul>
<h3>Why WooCommerce</h3>
<p>We build stores on WordPress + WooCommerce — the most widely used e-commerce platform in the world. It has no monthly platform fee or sales commission, works with local payment and shipping services and gives full control over SEO. The store, code and data belong to you.</p>
<h3>How much does an online store cost</h3>
<p>The price of a turnkey online store starts from $' . number_format( $rv_wd_prices['start'], 0, '.', ' ' ) . ' and depends on catalogue size, integrations, the number of languages and custom features. We fix the scope and price in the contract before work starts, and payment is split 70/30 — 70% at the start of the project and 30% at launch.</p>
<h3>A store built for growth</h3>
<p>An SEO-ready structure lets you promote categories and filters in Google, product feeds let you run Google Shopping and marketplace campaigns, and analytics shows where customers drop off. Our team also handles SEO and Google Ads, so the store can start bringing orders right after launch.</p>',
	'fn_chip' => 'Your next step',
	'fn_t' => 'Let’s plan <em>your store</em>',
	'fn_lead' => 'Tell us what you sell, how many products you have and which services you already use. We’ll recommend a package and outline the budget and timeline.',
	'fn_or' => 'Or contact us directly',
	'ct_card' => 'Get your store estimate',
	'ct_online' => 'Budget & timeline',
	'ct_sub' => 'Leave your name and phone number. We’ll call you back, clarify the task and give you a budget and timeline.',
	'sticky' => 'Online store from $' . $rv_wd_prices['start'],
	'sticky_b' => 'Get an estimate',
	'pp' => 'By sending the form you agree to our <a href="%s">privacy policy</a>.',
	'pp_url' => home_url( '/privacy-policy/' ),
	'form' => '[contact-form-7 id="10" title="cf7#1"]',
	'cases_url' => home_url( '/#rv-cine' ),
	'lead_task' => 'Online store — Start package',
) : array(
	'chip' => 'Інтернет-магазин під ключ · WooCommerce',
	'h1a' => 'Розробка інтернет-магазину під ключ,',
	'h1b' => 'який продає',
	'lead' => 'Проєктуємо, розробляємо й запускаємо інтернет-магазини на WooCommerce: каталог із фільтрами, швидке оформлення, онлайн-оплата, Нова Пошта, CRM та SEO-структура категорій — одним проєктом, однією командою.',
	'hero_price' => 'Інтернет-магазин під ключ — від ' . '$' . number_format( $rv_wd_prices['start'], 0, '.', ' ' ),
	'hero_terms' => 'Фіксована ціна в договорі · оплата 70/30',
	'hero_note' => 'Для оцінки достатньо розповісти, що продаєте, і приблизну кількість товарів.',
	'service_name' => 'Розробка інтернет-магазину під ключ',
	'cta1' => 'Розрахувати вартість магазину',
	'cta2' => 'Кейси інтернет-магазинів',
	'stats' => array( array( '120+', 'брендів обрали нас' ), array( '12', 'років у веброзробці' ), array( '21', 'країна світу' ), array( '95%', 'клієнтів повертаються' ) ),
	'vis_url' => 'yourstore.com.ua',
	'vis_cart' => 'КОШИК (2) ↗',
	'vis_kicker' => 'Нова колекція',
	'vis_h' => 'Обирай.<br><em>Купуй.</em>',
	'vis_sub' => 'Доставка · Оплата · Повернення',
	'vis_ph' => 'Оплата<br><em>в 1 дотик.</em>',
	'vis_ph_btn' => 'ОПЛАТИТИ ↗',
	'toast1' => 'Нове замовлення #1024',
	'toast1_s' => 'Telegram · щойно',
	'toast2' => 'Оформлення',
	'toast2_value' => '3 кроки до покупки',
	'toast3' => 'Оплату отримано',
	'toast3_value' => '+₴2 450',
	'pn_chip' => 'Що виправляємо ще до запуску',
	'pn_t' => 'Де інтернет-магазини <em>втрачають гроші</em>',
	'pn_lead' => 'Здебільшого продажі втрачаються не через товар, а на шляху до замовлення. Ми проєктуємо цей шлях — від першого візиту до оплати — так, щоб покупцю ніщо не заважало.',
	'pn_l1' => 'Проблема',
	'pn_l2' => 'Як ми це вирішуємо',
	'pn' => array(
		array( 'Покупець не знаходить товар', 'Зрозумілі категорії, фільтри за важливими для вашої ніші характеристиками й пошук із підказками — потрібний товар за 2–3 кліки.' ),
		array( 'Кидає кошик на оформленні', 'Оформлення на одній сторінці, покупка без реєстрації, автопідказки міст і відділень Нової Пошти, лише потрібні поля.' ),
		array( 'Магазин повільний на телефоні', 'Мобільна версія в пріоритеті, оптимізовані зображення й кешування. Перевіряємо Core Web Vitals до запуску.' ),
		array( 'Менеджери тонуть у ручній роботі', 'Замовлення одразу в CRM, ТТН створюються автоматично, а покупець отримує статуси без дзвінків.' ),
		array( 'Магазину немає в Google', 'SEO-структура категорій і фільтрів, мікророзмітка Product, чисті URL та фід Google Merchant з першого дня.' ),
	),
	'of_chip' => 'Інтернет-магазин під ключ',
	'of_t' => 'Магазин, готовий продавати, <br>від <em>' . '$' . number_format( $rv_wd_prices['start'], 0, '.', ' ' ) . '</em>',
	'of_lead' => 'Не шаблон із заміненим логотипом, а магазин, спроєктований під ваш асортимент, покупців і процеси. У кожен магазин входить:',
	'of_alt_t' => 'Потрібен інший обсяг?',
	'of_alt' => 'Порахуємо проєкт персонально: підберемо функціонал під ваші задачі й бюджет, а за потреби запустимо поетапно — спочатку те, що приносить продажі.',
	'of_inc' => array( 'Індивідуальний дизайн: ПК + мобільна', 'Каталог із фільтрами та пошуком', 'Кошик і швидке оформлення', 'Онлайн-оплата + Apple / Google Pay', 'Нова Пошта: відділення та ТТН', 'Адмінка WooCommerce + навчання', 'SEO-структура + мікророзмітка', 'GA4 e-commerce + Meta Pixel' ),
	'of_meta' => array( 'Запуск від 3 тижнів', 'Ціна й строки — у договорі' ),
	'of_cta' => 'Порахувати мій проєкт',
	'pk_chip' => 'Пакети',
	'pk_t' => 'Магазин під <em>ваш етап росту</em>',
	'pk' => array(
		array( 'start', 'Старт', 'Щоб запустити продажі онлайн або перейти з Prom чи Instagram на власний магазин.', array( 'Індивідуальний дизайн', 'Каталог до 500 товарів', 'Оплата + Нова Пошта', 'SEO-структура категорій', 'Аналітика GA4 e-commerce' ), 'Від 3 тижнів', true ),
		array( 'business', 'Бізнес', 'Для магазину, що розширює асортимент, рекламу й кількість замовлень.', array( 'Усе з пакета «Старт»', 'Каталог до 10 000 товарів', 'Розумні фільтри та пошук', 'Інтеграція з CRM', 'Імпорт і фіди для маркетплейсів' ), 'Від 4 тижнів', false ),
		array( 'pro', 'Pro / B2B', 'Для великих каталогів, опту, кількох складів, мов і валют.', array( 'Усе з пакета «Бізнес»', 'Синхронізація з 1С / BAS', 'Оптові ціни + кабінет клієнта', 'Мультимовність і мультивалютність', 'Індивідуальний функціонал' ), 'Від 5 тижнів', false ),
	),
	'pk_badge' => 'Швидкий старт',
	'pk_pop' => 'Оптимальний вибір',
	'pk_from' => 'від',
	'pk_cta' => 'Отримати оцінку',
	'pk_note' => 'Уже маєте магазин на OpenCart, Prom чи Shopify? Оцінимо, що можна перенести, сплануємо редиректи й збережемо пошуковий трафік.',
	'ca_chip' => 'Орієнтир за бюджетом',
	'ca_t' => 'Розрахуйте <em>ваш магазин</em>',
	'ca_lead' => 'Оберіть пакет і потрібні опції. Це попередній розрахунок: обсяг робіт, інтеграції та остаточну суму погодимо після обговорення задачі.',
	'ca_1' => 'Який пакет вам підходить?',
	'ca_2' => 'Додаткові інтеграції та можливості',
	'ca_types' => array( 'start' => array( 'Старт', 'До 500 товарів' ), 'business' => array( 'Бізнес', 'До 10 000 товарів' ), 'pro' => array( 'Pro / B2B', 'Великий каталог, опт' ) ),
	'ca_default' => 'business',
	'ca_total' => 'Орієнтовний бюджет',
	'ca_pay' => 'Оплата у два етапи',
	'ca_pre' => '70% на початку проєкту',
	'ca_post' => '30% після запуску',
	'ca_inc' => 'Входить у кожен магазин',
	'ca_incl' => array( 'Мобільна версія', 'Оплата + Нова Пошта', 'Базове SEO + мікророзмітка', 'GA4 e-commerce' ),
	'ca_cta' => 'Обговорити цей розрахунок',
	'ca_note' => 'Обсяг робіт, точну ціну та строки фіксуємо в договорі до початку розробки.',
	'in_chip' => 'Інтеграції',
	'in_t' => 'Підключаємо все, <em>з чим ви працюєте</em>',
	'in_lead' => 'Оплата, доставка, CRM, облік і маркетинг — магазин стає частиною вашої системи продажів, а не окремим островом.',
	'in' => array(
		array( 'Оплата', array( 'LiqPay', 'WayForPay', 'monobank', 'Apple Pay', 'Google Pay', 'Накладений платіж', 'Оплата частинами' ) ),
		array( 'Доставка', array( 'Нова Пошта', 'Укрпошта', 'Meest', 'Самовивіз', 'Кур’єрська доставка' ) ),
		array( 'CRM та облік', array( 'KeyCRM', 'SalesDrive', 'Bitrix24', '1С / BAS', 'Checkbox (ПРРО)' ) ),
		array( 'Маркетинг', array( 'Google Merchant', 'GA4', 'Meta Pixel', 'Rozetka', 'Prom', 'Hotline', 'eSputnik' ) ),
	),
	'pr_chip' => 'Зрозумілий процес',
	'pr_t' => 'Від ідеї до <em>першого замовлення</em>',
	'pr' => array(
		array( 'Аналітика і структура', 'Вивчаємо асортимент, конкурентів і пошуковий попит. Плануємо категорії, фільтри та картку товару.', 'Карта магазину' ),
		array( 'Прототип і дизайн', 'Каталог, картка товару, кошик і оформлення для ПК та телефона — у стилі вашого бренду.', 'Ваше погодження' ),
		array( 'Розробка та інтеграції', 'Збираємо магазин на WooCommerce, підключаємо оплату, доставку, CRM та імпорт товарів.', 'Робочий магазин' ),
		array( 'Наповнення і тести', 'Завантажуємо товари, робимо тестові замовлення й оплати, перевіряємо швидкість і листи.', 'Готовий до продажів' ),
		array( 'Запуск і зростання', 'Запуск на вашому домені, аналітика, індексація та навчання вашої команди.', 'Перші замовлення' ),
	),
	'cs_chip' => 'Кейси e-commerce',
	'cs_t' => 'Магазини, що <em>дають результат</em>',
	'cs_lead' => 'Результати проєктів інтернет-магазинів, у яких розробка поєднувалася з SEO чи рекламою. Напрями роботи зазначені в кожному кейсі.',
	'cs_all' => 'Переглянути портфоліо',
	'ni_chip' => 'За нішами',
	'ni_t' => 'Інтернет-магазини <em>для вашої ніші</em>',
	'ni_lead' => 'У кожної ніші своя логіка покупки: розміри й кольори, пошук за VIN, оптові ціни чи сертифікати. Ми проєктуємо каталог і оформлення під те, як обирають саме ваші покупці.',
	'gu_chip' => 'Прозорі умови',
	'gu_t' => 'Ви розумієте, <em>за що платите</em>',
	'gu' => array(
		array( 'Фіксована ціна в договорі', 'Обсяг робіт і вартість фіксуємо до старту. Додаткові задачі обговорюємо й погоджуємо до їх виконання.' ),
		array( 'Оплата у два етапи', '70% на початку проєкту та 30% після запуску. Без прихованих платежів і абонплати за платформу.' ),
		array( 'Магазин повністю ваш', 'Передаємо код, доступи до адмінки, домену й хостингу. Без прив’язки до нашої агенції.' ),
		array( 'Тестуємо до запуску', 'Робимо тестові замовлення, оплати й відправки, перевіряємо листи та аналітику перед стартом продажів.' ),
	),
	'fq_chip' => 'Перед початком',
	'fq_t' => 'Питання про <em>розробку магазину</em>',
	'fq' => array(
		array( 'Скільки коштує розробка інтернет-магазину під ключ?', 'Пакет «Старт» — від ' . '$' . number_format( $rv_wd_prices['start'], 0, '.', ' ' ) . ', «Бізнес» — від ' . '$' . number_format( $rv_wd_prices['business'], 0, '.', ' ' ) . ', «Pro / B2B» — від ' . '$' . number_format( $rv_wd_prices['pro'], 0, '.', ' ' ) . '. Остаточна вартість залежить від розміру каталогу, інтеграцій, кількості мов та індивідуального функціоналу. Фіксуємо її в договорі до старту.' ),
		array( 'Скільки часу займає розробка інтернет-магазину?', '«Старт» — від 3 тижнів, «Бізнес» — від 4 тижнів, «Pro / B2B» — від 5 тижнів. Графік також залежить від готовності даних про товари та швидкості погоджень.' ),
		array( 'Чому WooCommerce, а не Хорошоп, Prom чи Shopify?', 'На WooCommerce магазин належить вам: без щомісячної абонплати за платформу й відсотка з продажів, з повним контролем над SEO та будь-якими інтеграціями з українськими сервісами. Якщо вашу задачу краще вирішить інша платформа — скажемо чесно.' ),
		array( 'Чи можна перенести магазин без втрати SEO?', 'Так. Переносимо товари, категорії та клієнтів, налаштовуємо 301-редиректи й перевіряємо індексацію після запуску. Перенесення оцінюємо окремо; позиції також залежать від пошукових систем і конкурентів.' ),
		array( 'Які оплати й доставки ви підключаєте?', 'Оплата карткою та Apple / Google Pay через LiqPay, WayForPay чи monobank, накладений платіж, оплата частинами; доставка Новою Поштою, Укрпоштою, Meest, кур’єром і самовивіз. Інші сервіси підключаємо на запит.' ),
		array( 'Хто наповнюватиме магазин товарами?', 'Імпортуємо товари з Excel, XML постачальників або 1С / BAS, або ваша команда додає їх через адмінку. Початкове наповнення можна включити в проєкт.' ),
		array( 'Чи зможу я сам керувати магазином?', 'Так. В адмінці WooCommerce ви змінюєте товари, ціни, залишки й обробляєте замовлення. Після запуску навчаємо вашу команду та передаємо коротку інструкцію.' ),
		array( 'Що буде після запуску?', 'Після старту перевіряємо замовлення, оплати й аналітику. Подальшу підтримку, SEO-просування та рекламу Google Ads можемо узгодити окремо — цим теж займається наша команда.' ),
		array( 'Як відбувається оплата?', '70% на початку проєкту та 30% після запуску. Погоджені обсяг робіт і ціну прописуємо в договорі. Працюємо з клієнтами в Україні та за кордоном.' ),
	),
	'seo_chip' => 'Про розробку інтернет-магазинів',
	'seo_more' => 'Читати далі',
	'seo_less' => 'Згорнути',
	'seo' => '<h2>Розробка інтернет-магазину під ключ: що ви отримуєте</h2>
<p>Інтернет-магазин — це не просто сайт із кошиком. Це канал продажів, який має залучати трафік, допомагати покупцю обрати товар, приймати оплату й передавати замовлення вашій команді без ручної роботи. Тому в Revive Agency ми підходимо до <strong>розробки інтернет-магазину</strong> як до бізнес-проєкту — від аналізу асортименту й попиту до перших замовлень і аналітики.</p>
<h3>Що входить у створення інтернет-магазину під ключ</h3>
<ul>
<li>аналіз ніші, конкурентів і пошукового попиту;</li>
<li>структура каталогу, фільтри та SEO-дружні URL;</li>
<li>індивідуальний дизайн для ПК і смартфонів;</li>
<li>картка товару, яка відповідає на запитання й викликає довіру;</li>
<li>кошик і оформлення замовлення, онлайн-оплата та доставка;</li>
<li>інтеграції з CRM, 1С / BAS і маркетплейсами;</li>
<li>аналітика GA4 e-commerce та Meta Pixel;</li>
<li>запуск, тестування й навчання вашої команди.</li>
</ul>
<h3>Чому ми розробляємо магазини на WooCommerce</h3>
<p>Ми створюємо інтернет-магазини на WordPress + WooCommerce — найпоширенішій e-commerce платформі у світі. Вона не має щомісячної абонплати й комісії з продажів, легко інтегрується з українськими сервісами (Нова Пошта, LiqPay, WayForPay, monobank, Checkbox) і дає повний контроль над SEO. Магазин, код і дані належать вам.</p>
<h3>Скільки коштує розробка інтернет-магазину</h3>
<p>Вартість інтернет-магазину під ключ — від $' . number_format( $rv_wd_prices['start'], 0, '.', ' ' ) . '. Ціна залежить від кількості товарів, інтеграцій, мов і індивідуального функціоналу. Обсяг робіт і вартість фіксуємо в договорі до початку роботи, оплата у два етапи: 70% на початку проєкту та 30% після запуску.</p>
<h3>Магазин, готовий до росту</h3>
<p>SEO-структура дозволяє просувати категорії та фільтри в Google, фіди товарів — запускати Google Shopping і продавати на Rozetka та Prom, а аналітика показує, де покупці йдуть із сайту. Наша команда також займається SEO-просуванням і рекламою Google Ads, тож магазин може почати приносити замовлення одразу після запуску.</p>',
	'fn_chip' => 'Наступний крок',
	'fn_t' => 'Сплануймо <em>ваш магазин</em>',
	'fn_lead' => 'Розкажіть, що продаєте, скільки товарів і якими сервісами вже користуєтеся. Запропонуємо пакет і окреслимо бюджет та строки запуску.',
	'fn_or' => 'Або зв’яжіться з нами напряму',
	'ct_card' => 'Отримайте оцінку магазину',
	'ct_online' => 'Бюджет і строки',
	'ct_sub' => 'Залиште ім’я та телефон — передзвонимо, уточнимо задачу й назвемо бюджет і строки запуску.',
	'sticky' => 'Магазин під ключ від $' . $rv_wd_prices['start'],
	'sticky_b' => 'Розрахувати',
	'pp' => 'Надсилаючи форму, ви погоджуєтеся з <a href="%s">політикою конфіденційності</a>.',
	'pp_url' => home_url( '/uk/privacy-policy/' ),
	'form' => '[contact-form-7 id="473" title="cf7#1_ua"]',
	'cases_url' => home_url( '/uk/#rv-cine' ),
	'lead_task' => 'Інтернет-магазин — пакет «Старт»',
);

/* Кейси інтернет-магазинів (з портфоліо на головній). */
$rv_wd_cases = array(
	array( 'Ukrzoloto', 'ukrzoloto.ua', '2018', '+480%', 'органічного трафіку', 'organic traffic', 'E-com · SEO' ),
	array( 'Fastkava', 'fastkava.com.pl', '2026', '+420%', 'органічного трафіку', 'organic traffic', 'E-com · SEO' ),
	array( 'Hekaya', 'hekayajewellery.com', '2024', 'x3.1', 'ROAS', 'ROAS', 'E-com · Target' ),
);

/* ============================================================
   НІШІ: одна сторінка-шаблон на всі напрями.
   Шаблон сам визначає нішу за слагом сторінки (UA-слаг або EN-слаг,
   або UA-переклад Polylang) і підставляє H1, title, description,
   назву послуги та SEO-текст. Решта сторінки — без змін.
   Ціни в текстах підтягуються з $rv_wd_prices: {P1} {P2} {P3}.
   ============================================================ */
$rv_niche_data = array(	array(
		'uk_slug' => 'rozrobka-internet-magazinu-odyagu', 'en_slug' => 'clothing-online-store-development', 'icon' => 'shirt',
		'uk' => array(
			'card' => 'Магазин одягу', 'cd' => 'Розмірні сітки, кольори, образи, обмін і повернення',
			'h1a' => 'Розробка інтернет-магазину одягу', 'h1b' => 'під ключ, що продає',
			'service' => 'Розробка інтернет-магазину одягу під ключ',
			'title' => 'Розробка інтернет-магазину одягу — від {P1T}',
			'desc' => 'Створюємо інтернет-магазини одягу на WooCommerce: розмірні сітки, фільтри за кольором і розміром, образи, оплата й Нова Пошта. Від $2000, запуск від 3 тижнів.',
			'seo' => '<h2>Розробка інтернет-магазину одягу під ключ</h2>
<p>Онлайн-продажі одягу виграє той, хто прибирає сумніви покупця: чи підійде розмір, як річ виглядає наживо, як повернути, якщо не сяде. Ми проєктуємо магазин одягу так, щоб відповідь на ці питання була на картці товару, а оформлення займало хвилину.</p>
<h3>Що ми враховуємо в інтернет-магазині одягу</h3>
<ul>
<li>розмірні сітки й підказка «який розмір обрати» для кожної категорії;</li>
<li>варіації товару: колір, розмір, зріст — з окремими фото й залишками;</li>
<li>фільтри за розміром, кольором, тканиною, сезоном і ціною;</li>
<li>образи й «з цим купують», щоб збільшити середній чек;</li>
<li>прозорі умови обміну й повернення прямо на картці товару;</li>
<li>колекції, розпродажі та промокоди для сезонних кампаній.</li>
</ul>
<h3>Інтеграції та маркетинг</h3>
<p>Для fashion-магазину важливі синхронізація залишків по розмірах з 1С / BAS або CRM, фід для Google Shopping і Meta Ads з усіма варіаціями, а також автоматичні ТТН Нової Пошти й оплата частинами.</p>
<h3>Скільки коштує розробка інтернет-магазину одягу</h3>
<p>Вартість — від {P1} (пакет «Старт»), від {P2} («Бізнес») та від {P3} («Pro / B2B»). Запуск — від 3 тижнів. Розробляємо на WooCommerce: без абонплати за платформу й комісії з продажів, магазин і дані належать вам. Обсяг робіт і ціну фіксуємо в договорі, оплата — 70% на початку проєкту та 30% після запуску.</p>',
		),
		'en' => array(
			'card' => 'Clothing store', 'cd' => 'Size charts, colours, looks, exchanges and returns',
			'h1a' => 'Clothing online store development', 'h1b' => 'turnkey, built to sell',
			'service' => 'Clothing online store development',
			'title' => 'Clothing Online Store Development — from {P1T}',
			'desc' => 'We build clothing online stores on WooCommerce: size charts, colour and size filters, outfits, fast checkout and shipping. From $2000, launch from 3 weeks.',
			'seo' => '<h2>Clothing online store development</h2>
<p>Fashion e-commerce is won by the store that removes doubts: will it fit, what does it look like in real life, how do I return it. We design clothing stores so the product page answers these questions and checkout takes a minute.</p>
<h3>What we build into a clothing online store</h3>
<ul>
<li>size charts and a “which size should I choose” hint for every category;</li>
<li>product variations — colour, size, length — with their own photos and stock;</li>
<li>filters by size, colour, fabric, season and price;</li>
<li>outfits and “complete the look” to raise the average order value;</li>
<li>clear exchange and return terms right on the product page;</li>
<li>collections, sales and promo codes for seasonal campaigns.</li>
</ul>
<h3>Integrations and marketing</h3>
<p>A fashion store needs stock sync by size with your ERP or CRM, a Google Shopping and Meta Ads feed with every variation, automatic shipping labels and pay-in-instalments options.</p>
<h3>How much does a clothing online store cost</h3>
<p>Prices start from {P1} (Start), {P2} (Business) and {P3} (Pro / B2B). Launch from 3 weeks. We build on WooCommerce: no platform subscription or sales commission, and the store and data belong to you. Scope and price are fixed in the contract; payment is 70% at the start of the project and 30% at launch.</p>',
		),
	),
	array(
		'uk_slug' => 'rozrobka-internet-magazinu-kosmetiki', 'en_slug' => 'cosmetics-online-store-development', 'icon' => 'drop',
		'uk' => array(
			'card' => 'Магазин косметики', 'cd' => 'Склад і об’єм, набори, підбір за типом шкіри, лояльність',
			'h1a' => 'Розробка інтернет-магазину косметики', 'h1b' => 'під ключ, що продає',
			'service' => 'Розробка інтернет-магазину косметики під ключ',
			'title' => 'Розробка інтернет-магазину косметики — від {P1T}',
			'desc' => 'Розробка інтернет-магазину косметики на WooCommerce: фільтри за типом шкіри, склад і об’єм, набори, бонусна програма, оплата та Нова Пошта. Від $2000.',
			'seo' => '<h2>Розробка інтернет-магазину косметики під ключ</h2>
<p>Косметику купують очима й довірою. Покупець хоче бачити склад, об’єм, для якого типу шкіри засіб і відгуки тих, хто вже спробував. Ми будуємо магазин косметики, у якому легко підібрати засіб, повернутися за повторною покупкою й зібрати набір.</p>
<h3>Що ми враховуємо в інтернет-магазині косметики</h3>
<ul>
<li>фільтри за типом шкіри й волосся, проблемою, брендом і активними компонентами;</li>
<li>склад, об’єм, спосіб застосування й сертифікати на картці товару;</li>
<li>набори, подарункові бокси й мініатюри;</li>
<li>відгуки з фото та рейтинг товару;</li>
<li>бонусна програма, накопичувальні знижки й нагадування про повторну покупку;</li>
<li>підбірки «рутина догляду» замість звичайного каталогу.</li>
</ul>
<h3>Інтеграції та маркетинг</h3>
<p>Підключаємо імпорт від дистриб’юторів, email- і Viber-розсилки через eSputnik, фід для Google Shopping, Rozetka та Prom, а також Meta Pixel для ретаргетингу — у косметиці повторні продажі дають основний прибуток.</p>
<h3>Скільки коштує розробка інтернет-магазину косметики</h3>
<p>Вартість — від {P1} (пакет «Старт»), від {P2} («Бізнес») та від {P3} («Pro / B2B»). Запуск — від 3 тижнів. Розробляємо на WooCommerce: без абонплати за платформу й комісії з продажів, магазин і дані належать вам. Обсяг робіт і ціну фіксуємо в договорі, оплата — 70% на початку проєкту та 30% після запуску.</p>',
		),
		'en' => array(
			'card' => 'Cosmetics store', 'cd' => 'Ingredients, sizes, bundles, skin-type finder, loyalty',
			'h1a' => 'Cosmetics online store development', 'h1b' => 'turnkey, built to sell',
			'service' => 'Cosmetics online store development',
			'title' => 'Cosmetics Online Store Development — from {P1T}',
			'desc' => 'Cosmetics online store development on WooCommerce: skin-type filters, ingredients and sizes, bundles, loyalty programme, payments and shipping. From $2000.',
			'seo' => '<h2>Cosmetics online store development</h2>
<p>Cosmetics are bought with the eyes and with trust. Customers want to see ingredients, size, which skin type a product suits and reviews from people who tried it. We build cosmetics stores where choosing is easy and coming back for a refill is effortless.</p>
<h3>What we build into a cosmetics online store</h3>
<ul>
<li>filters by skin and hair type, concern, brand and active ingredients;</li>
<li>ingredients, size, how to use and certificates on the product page;</li>
<li>bundles, gift boxes and travel sizes;</li>
<li>photo reviews and product ratings;</li>
<li>loyalty points, tiered discounts and refill reminders;</li>
<li>“skincare routine” collections instead of a plain catalogue.</li>
</ul>
<h3>Integrations and marketing</h3>
<p>We connect distributor imports, email and messenger campaigns, Google Shopping and marketplace feeds, and Meta Pixel for retargeting — in cosmetics, repeat purchases drive most of the profit.</p>
<h3>How much does a cosmetics online store cost</h3>
<p>Prices start from {P1} (Start), {P2} (Business) and {P3} (Pro / B2B). Launch from 3 weeks. We build on WooCommerce: no platform subscription or sales commission, and the store and data belong to you. Scope and price are fixed in the contract; payment is 70% at the start of the project and 30% at launch.</p>',
		),
	),
	array(
		'uk_slug' => 'rozrobka-internet-magazinu-avtozapchastin', 'en_slug' => 'auto-parts-online-store-development', 'icon' => 'gear',
		'uk' => array(
			'card' => 'Магазин автозапчастин', 'cd' => 'Пошук за VIN, авто й артикулом, аналоги, прайси постачальників',
			'h1a' => 'Розробка інтернет-магазину автозапчастин', 'h1b' => 'під ключ, що продає',
			'service' => 'Розробка інтернет-магазину автозапчастин під ключ',
			'title' => 'Розробка інтернет-магазину автозапчастин — від {P1T}',
			'desc' => 'Інтернет-магазин автозапчастин під ключ: пошук за VIN, маркою й артикулом, аналоги, імпорт прайсів постачальників, 1С / BAS, Нова Пошта. Від $2000.',
			'seo' => '<h2>Розробка інтернет-магазину автозапчастин під ключ</h2>
<p>У магазині автозапчастин покупець шукає не «гарний товар», а точну деталь для свого авто. Тому головне — пошук: за VIN, маркою, моделлю, роком і артикулом, з аналогами й наявністю на складах. Ми розробляємо магазини, де потрібна запчастина знаходиться за кілька секунд.</p>
<h3>Що ми враховуємо в інтернет-магазині автозапчастин</h3>
<ul>
<li>підбір за маркою, моделлю, роком і модифікацією авто — «мій гараж»;</li>
<li>пошук за артикулом і OEM-номером з аналогами (кроси);</li>
<li>імпорт і автоматичне оновлення прайсів кількох постачальників;</li>
<li>наявність і строки доставки з різних складів;</li>
<li>оптові ціни для СТО й кабінет постійного клієнта;</li>
<li>великий каталог — десятки тисяч позицій без втрати швидкості.</li>
</ul>
<h3>Інтеграції та маркетинг</h3>
<p>Інтегруємо магазин з 1С / BAS, прайсами постачальників у Excel / XML / API, Новою Поштою й накладеним платежем, а також налаштовуємо SEO-сторінки під запити на кшталт «колодки на Skoda Octavia» — це основний безкоштовний трафік у ніші.</p>
<h3>Скільки коштує розробка інтернет-магазину автозапчастин</h3>
<p>Вартість — від {P1} (пакет «Старт»), від {P2} («Бізнес») та від {P3} («Pro / B2B»). Запуск — від 3 тижнів. Розробляємо на WooCommerce: без абонплати за платформу й комісії з продажів, магазин і дані належать вам. Обсяг робіт і ціну фіксуємо в договорі, оплата — 70% на початку проєкту та 30% після запуску.</p>',
		),
		'en' => array(
			'card' => 'Auto parts store', 'cd' => 'Search by VIN, car and part number, cross-references',
			'h1a' => 'Auto Parts online store development', 'h1b' => 'turnkey, built to sell',
			'service' => 'Auto Parts online store development',
			'title' => 'Auto Parts Online Store Development — from {P1T}',
			'desc' => 'Turnkey auto parts online store: search by VIN, make and part number, cross-references, supplier price imports, ERP sync and shipping. From $2000.',
			'seo' => '<h2>Auto Parts online store development</h2>
<p>Auto parts shoppers are not browsing — they need the exact part for their car. Search is everything: by VIN, make, model, year and part number, with cross-references and stock across warehouses. We build stores where the right part is found in seconds.</p>
<h3>What we build into a auto parts online store</h3>
<ul>
<li>vehicle selector by make, model, year and engine — “my garage”;</li>
<li>search by part and OEM number with cross-references;</li>
<li>automatic price imports from several suppliers;</li>
<li>stock and delivery times across warehouses;</li>
<li>trade prices for garages and a returning-customer account;</li>
<li>large catalogues — tens of thousands of SKUs without slowing down.</li>
</ul>
<h3>Integrations and marketing</h3>
<p>We integrate the store with your ERP, supplier price lists (Excel / XML / API), shipping and cash on delivery, and build SEO pages for searches like “brake pads for Skoda Octavia” — the main source of free traffic in this niche.</p>
<h3>How much does a auto parts online store cost</h3>
<p>Prices start from {P1} (Start), {P2} (Business) and {P3} (Pro / B2B). Launch from 3 weeks. We build on WooCommerce: no platform subscription or sales commission, and the store and data belong to you. Scope and price are fixed in the contract; payment is 70% at the start of the project and 30% at launch.</p>',
		),
	),
	array(
		'uk_slug' => 'rozrobka-internet-magazinu-mebliv', 'en_slug' => 'furniture-online-store-development', 'icon' => 'sofa',
		'uk' => array(
			'card' => 'Магазин меблів', 'cd' => 'Конфігуратор, тканини й розміри, доставка з підйомом',
			'h1a' => 'Розробка інтернет-магазину меблів', 'h1b' => 'під ключ, що продає',
			'service' => 'Розробка інтернет-магазину меблів під ключ',
			'title' => 'Розробка інтернет-магазину меблів — від {P1T}',
			'desc' => 'Розробка інтернет-магазину меблів: конфігуратор розмірів і тканин, фото в інтер’єрі, розрахунок доставки й збирання, оплата частинами. Від $2000, від 3 тижнів.',
			'seo' => '<h2>Розробка інтернет-магазину меблів під ключ</h2>
<p>Меблі — дорога й обдумана покупка. Людина хоче побачити диван у різних тканинах, перевірити розміри під свою кімнату й зрозуміти, скільки коштуватиме доставка з підйомом. Ми проєктуємо меблевий магазин так, щоб усі ці відповіді були на сайті, а не в листуванні з менеджером.</p>
<h3>Що ми враховуємо в інтернет-магазині меблів</h3>
<ul>
<li>конфігуратор: розміри, матеріали, тканини й колір з перерахунком ціни;</li>
<li>фото в інтер’єрі, креслення з розмірами та 3D-перегляд;</li>
<li>розрахунок доставки, підйому на поверх і збирання;</li>
<li>оплата частинами й кредит для дорогих позицій;</li>
<li>колекції та комплекти для кімнати;</li>
<li>форма «замовити за індивідуальними розмірами».</li>
</ul>
<h3>Інтеграції та маркетинг</h3>
<p>Підключаємо оплату частинами, CRM для обробки довгих угод, доставку власною службою або перевізником і рекламні фіди — меблевий магазин частіше продає після 2–3 візитів, тому важливі ретаргетинг і аналітика.</p>
<h3>Скільки коштує розробка інтернет-магазину меблів</h3>
<p>Вартість — від {P1} (пакет «Старт»), від {P2} («Бізнес») та від {P3} («Pro / B2B»). Запуск — від 3 тижнів. Розробляємо на WooCommerce: без абонплати за платформу й комісії з продажів, магазин і дані належать вам. Обсяг робіт і ціну фіксуємо в договорі, оплата — 70% на початку проєкту та 30% після запуску.</p>',
		),
		'en' => array(
			'card' => 'Furniture store', 'cd' => 'Configurator, fabrics and sizes, delivery and assembly',
			'h1a' => 'Furniture online store development', 'h1b' => 'turnkey, built to sell',
			'service' => 'Furniture online store development',
			'title' => 'Furniture Online Store Development — from {P1T}',
			'desc' => 'Furniture online store development: size and fabric configurator, interior photos, delivery and assembly pricing, pay in instalments. From $2000, from 3 weeks.',
			'seo' => '<h2>Furniture online store development</h2>
<p>Furniture is an expensive, considered purchase. People want to see the sofa in different fabrics, check dimensions against their room and know what delivery with assembly will cost. We design furniture stores that answer all of this on the site, not in chats with a manager.</p>
<h3>What we build into a furniture online store</h3>
<ul>
<li>configurator: dimensions, materials, fabrics and colour with live pricing;</li>
<li>interior photos, dimension drawings and 3D view;</li>
<li>delivery, carry-up and assembly pricing;</li>
<li>instalments and credit for higher-priced items;</li>
<li>collections and room sets;</li>
<li>a “custom size order” form.</li>
</ul>
<h3>Integrations and marketing</h3>
<p>We connect instalment payments, a CRM for long sales cycles, own or third-party delivery and ad feeds — furniture usually sells after 2–3 visits, so retargeting and analytics matter.</p>
<h3>How much does a furniture online store cost</h3>
<p>Prices start from {P1} (Start), {P2} (Business) and {P3} (Pro / B2B). Launch from 3 weeks. We build on WooCommerce: no platform subscription or sales commission, and the store and data belong to you. Scope and price are fixed in the contract; payment is 70% at the start of the project and 30% at launch.</p>',
		),
	),
	array(
		'uk_slug' => 'rozrobka-internet-magazinu-elektroniki', 'en_slug' => 'electronics-online-store-development', 'icon' => 'chip',
		'uk' => array(
			'card' => 'Магазин електроніки', 'cd' => 'Характеристики, порівняння, гарантія, оплата частинами',
			'h1a' => 'Розробка інтернет-магазину електроніки', 'h1b' => 'під ключ, що продає',
			'service' => 'Розробка інтернет-магазину електроніки під ключ',
			'title' => 'Розробка інтернет-магазину електроніки — від {P1T}',
			'desc' => 'Інтернет-магазин електроніки та техніки під ключ: фільтри за характеристиками, порівняння товарів, гарантія, оплата частинами, Rozetka і Hotline. Від $2000.',
			'seo' => '<h2>Розробка інтернет-магазину електроніки під ключ</h2>
<p>Електроніку обирають за характеристиками й ціною, а купують там, де довіряють. Покупець порівнює десятки моделей, шукає гарантію й зручну оплату. Ми створюємо магазини електроніки з розумними фільтрами, порівнянням і чесною інформацією про гарантію та доставку.</p>
<h3>Що ми враховуємо в інтернет-магазині електроніки</h3>
<ul>
<li>фільтри за технічними характеристиками для кожної категорії;</li>
<li>порівняння товарів і таблиці характеристик;</li>
<li>гарантія, сервісні центри й умови повернення на картці;</li>
<li>оплата частинами та кредит від банків;</li>
<li>акції, комплекти й аксесуари до товару;</li>
<li>швидкий пошук за моделлю й артикулом.</li>
</ul>
<h3>Інтеграції та маркетинг</h3>
<p>Налаштовуємо фіди для Hotline, Rozetka, Prom і Google Shopping, синхронізацію цін і залишків з постачальниками та 1С / BAS, а також фіскалізацію чеків — у ніші електроніки ціни змінюються щодня.</p>
<h3>Скільки коштує розробка інтернет-магазину електроніки</h3>
<p>Вартість — від {P1} (пакет «Старт»), від {P2} («Бізнес») та від {P3} («Pro / B2B»). Запуск — від 3 тижнів. Розробляємо на WooCommerce: без абонплати за платформу й комісії з продажів, магазин і дані належать вам. Обсяг робіт і ціну фіксуємо в договорі, оплата — 70% на початку проєкту та 30% після запуску.</p>',
		),
		'en' => array(
			'card' => 'Electronics store', 'cd' => 'Specs, comparison, warranty, instalments',
			'h1a' => 'Electronics online store development', 'h1b' => 'turnkey, built to sell',
			'service' => 'Electronics online store development',
			'title' => 'Electronics Online Store Development — from {P1T}',
			'desc' => 'Turnkey electronics online store: spec filters, product comparison, warranty, instalments, marketplace and price-comparison feeds. From $2000.',
			'seo' => '<h2>Electronics online store development</h2>
<p>Electronics are chosen by specs and price, but bought where people trust the seller. Shoppers compare dozens of models and look for warranty and easy payment. We build electronics stores with smart filters, comparison and honest warranty and delivery information.</p>
<h3>What we build into a electronics online store</h3>
<ul>
<li>spec-based filters for every category;</li>
<li>product comparison and spec tables;</li>
<li>warranty, service centres and return terms on the product page;</li>
<li>instalments and bank credit;</li>
<li>deals, bundles and matching accessories;</li>
<li>fast search by model and part number.</li>
</ul>
<h3>Integrations and marketing</h3>
<p>We set up feeds for price-comparison sites, marketplaces and Google Shopping, price and stock sync with suppliers and your ERP, and receipt fiscalisation — electronics prices change daily.</p>
<h3>How much does a electronics online store cost</h3>
<p>Prices start from {P1} (Start), {P2} (Business) and {P3} (Pro / B2B). Launch from 3 weeks. We build on WooCommerce: no platform subscription or sales commission, and the store and data belong to you. Scope and price are fixed in the contract; payment is 70% at the start of the project and 30% at launch.</p>',
		),
	),
	array(
		'uk_slug' => 'rozrobka-internet-magazinu-produktiv', 'en_slug' => 'grocery-online-store-development', 'icon' => 'basket',
		'uk' => array(
			'card' => 'Магазин продуктів', 'cd' => 'Вагові товари, слоти доставки, повторні замовлення',
			'h1a' => 'Розробка інтернет-магазину продуктів', 'h1b' => 'під ключ, що продає',
			'service' => 'Розробка інтернет-магазину продуктів під ключ',
			'title' => 'Розробка інтернет-магазину продуктів — від {P1T}',
			'desc' => 'Розробка інтернет-магазину продуктів харчування: вагові товари, вибір часу доставки, мінімальна сума замовлення, повторні замовлення, онлайн-оплата. Від $2000.',
			'seo' => '<h2>Розробка інтернет-магазину продуктів під ключ</h2>
<p>Продукти купують часто й швидко, тому магазин має працювати як звичка: зайшов, повторив минуле замовлення, обрав час доставки. Ми розробляємо продуктові магазини з продуманим кошиком, ваговими товарами й зонами доставки.</p>
<h3>Що ми враховуємо в інтернет-магазині продуктів</h3>
<ul>
<li>вагові й штучні товари з точним перерахунком суми;</li>
<li>вибір дня й часу доставки, зони та мінімальна сума замовлення;</li>
<li>«повторити замовлення» і списки покупок;</li>
<li>склад, калорійність, термін придатності на картці;</li>
<li>заміни товарів, якщо чогось немає в наявності;</li>
<li>акції дня й персональні пропозиції.</li>
</ul>
<h3>Інтеграції та маркетинг</h3>
<p>Інтегруємо магазин з обліковою системою й залишками в реальному часі, кур’єрською доставкою, онлайн-оплатою з холдуванням суми для вагових товарів і фіскалізацією чеків через Checkbox.</p>
<h3>Скільки коштує розробка інтернет-магазину продуктів</h3>
<p>Вартість — від {P1} (пакет «Старт»), від {P2} («Бізнес») та від {P3} («Pro / B2B»). Запуск — від 3 тижнів. Розробляємо на WooCommerce: без абонплати за платформу й комісії з продажів, магазин і дані належать вам. Обсяг робіт і ціну фіксуємо в договорі, оплата — 70% на початку проєкту та 30% після запуску.</p>',
		),
		'en' => array(
			'card' => 'Grocery store', 'cd' => 'Weighted items, delivery slots, repeat orders',
			'h1a' => 'Grocery online store development', 'h1b' => 'turnkey, built to sell',
			'service' => 'Grocery online store development',
			'title' => 'Grocery Online Store Development — from {P1T}',
			'desc' => 'Grocery online store development: weighted items, delivery time slots, minimum order value, repeat orders and online payments. From $2000.',
			'seo' => '<h2>Grocery online store development</h2>
<p>Groceries are bought often and quickly, so the store should feel like a habit: open it, repeat last week’s order, pick a delivery slot. We build grocery stores with a smart cart, weighted products and delivery zones.</p>
<h3>What we build into a grocery online store</h3>
<ul>
<li>weighted and unit products with precise totals;</li>
<li>delivery day and time slots, zones and minimum order value;</li>
<li>“repeat order” and shopping lists;</li>
<li>ingredients, nutrition and shelf life on the product page;</li>
<li>substitutions when an item is out of stock;</li>
<li>daily deals and personal offers.</li>
</ul>
<h3>Integrations and marketing</h3>
<p>We integrate the store with your accounting system and real-time stock, courier delivery, card pre-authorisation for weighted items and receipt fiscalisation.</p>
<h3>How much does a grocery online store cost</h3>
<p>Prices start from {P1} (Start), {P2} (Business) and {P3} (Pro / B2B). Launch from 3 weeks. We build on WooCommerce: no platform subscription or sales commission, and the store and data belong to you. Scope and price are fixed in the contract; payment is 70% at the start of the project and 30% at launch.</p>',
		),
	),
	array(
		'uk_slug' => 'rozrobka-internet-magazinu-tovariv-dlya-domu', 'en_slug' => 'home-goods-online-store-development', 'icon' => 'home',
		'uk' => array(
			'card' => 'Товари для дому', 'cd' => 'Широкий асортимент, підбірки за кімнатами, комплекти',
			'h1a' => 'Розробка інтернет-магазину товарів для дому', 'h1b' => 'під ключ, що продає',
			'service' => 'Розробка інтернет-магазину товарів для дому під ключ',
			'title' => 'Розробка інтернет-магазину товарів для дому — від {P1T}',
			'desc' => 'Інтернет-магазин товарів для дому під ключ: зручний каталог для тисяч позицій, підбірки за кімнатами, комплекти, Нова Пошта та маркетплейси. Від $2000.',
			'seo' => '<h2>Розробка інтернет-магазину товарів для дому під ключ</h2>
<p>Товари для дому — це тисячі дрібних позицій: посуд, текстиль, декор, зберігання, побутова хімія. Покупцю важливо швидко знайти потрібне й докласти ще кілька речей у кошик. Ми будуємо магазини, у яких великий асортимент не плутає, а допомагає продавати більше.</p>
<h3>Що ми враховуємо в інтернет-магазині товарів для дому</h3>
<ul>
<li>каталог за кімнатами й сценаріями: кухня, ванна, спальня, зберігання;</li>
<li>фільтри за матеріалом, кольором, розміром і брендом;</li>
<li>комплекти й «купують разом» для збільшення чека;</li>
<li>безкоштовна доставка від певної суми з підказкою в кошику;</li>
<li>швидкий імпорт тисяч товарів від постачальників;</li>
<li>сезонні підбірки та подарункові ідеї.</li>
</ul>
<h3>Інтеграції та маркетинг</h3>
<p>Підключаємо імпорт XML-фідів постачальників, вивантаження на Rozetka, Prom і Epicentr, Google Shopping, Нову Пошту й Укрпошту — для широкого асортименту маркетплейси дають додатковий канал продажів.</p>
<h3>Скільки коштує розробка інтернет-магазину товарів для дому</h3>
<p>Вартість — від {P1} (пакет «Старт»), від {P2} («Бізнес») та від {P3} («Pro / B2B»). Запуск — від 3 тижнів. Розробляємо на WooCommerce: без абонплати за платформу й комісії з продажів, магазин і дані належать вам. Обсяг робіт і ціну фіксуємо в договорі, оплата — 70% на початку проєкту та 30% після запуску.</p>',
		),
		'en' => array(
			'card' => 'Home goods', 'cd' => 'Wide range, room-based collections, sets',
			'h1a' => 'Home Goods online store development', 'h1b' => 'turnkey, built to sell',
			'service' => 'Home Goods online store development',
			'title' => 'Home Goods Online Store Development — from {P1T}',
			'desc' => 'Turnkey home goods online store: a catalogue built for thousands of items, room-based collections, sets, shipping and marketplace feeds. From $2000.',
			'seo' => '<h2>Home Goods online store development</h2>
<p>Home goods mean thousands of small items: kitchenware, textiles, decor, storage, cleaning. Shoppers want to find what they need fast and add a few more things to the cart. We build stores where a large range helps sell more instead of confusing.</p>
<h3>What we build into a home goods online store</h3>
<ul>
<li>catalogue by room and scenario: kitchen, bathroom, bedroom, storage;</li>
<li>filters by material, colour, size and brand;</li>
<li>sets and “frequently bought together” to raise order value;</li>
<li>free-shipping threshold with a hint in the cart;</li>
<li>fast imports of thousands of supplier products;</li>
<li>seasonal collections and gift ideas.</li>
</ul>
<h3>Integrations and marketing</h3>
<p>We connect supplier XML imports, marketplace exports, Google Shopping and national postal services — with a wide range, marketplaces become an additional sales channel.</p>
<h3>How much does a home goods online store cost</h3>
<p>Prices start from {P1} (Start), {P2} (Business) and {P3} (Pro / B2B). Launch from 3 weeks. We build on WooCommerce: no platform subscription or sales commission, and the store and data belong to you. Scope and price are fixed in the contract; payment is 70% at the start of the project and 30% at launch.</p>',
		),
	),
	array(
		'uk_slug' => 'rozrobka-internet-magazinu-vzuttya', 'en_slug' => 'shoe-online-store-development', 'icon' => 'shoe',
		'uk' => array(
			'card' => 'Магазин взуття', 'cd' => 'Розміри й устілка в см, примірка, обмін без проблем',
			'h1a' => 'Розробка інтернет-магазину взуття', 'h1b' => 'під ключ, що продає',
			'service' => 'Розробка інтернет-магазину взуття під ключ',
			'title' => 'Розробка інтернет-магазину взуття — від {P1T}',
			'desc' => 'Розробка інтернет-магазину взуття: таблиці розмірів і довжина устілки, фільтри за сезоном і матеріалом, примірка при отриманні, обмін. Від $2000, від 3 тижнів.',
			'seo' => '<h2>Розробка інтернет-магазину взуття під ключ</h2>
<p>Головний страх покупця взуття онлайн — не вгадати з розміром. Тому магазин взуття має показувати довжину устілки, особливості колодки й чесно пояснювати умови обміну. Ми розробляємо магазини, де розмір обирають упевнено, а повернень стає менше.</p>
<h3>Що ми враховуємо в інтернет-магазині взуття</h3>
<ul>
<li>таблиці розмірів з довжиною устілки в сантиметрах для кожної моделі;</li>
<li>позначки «маломірить / великомірить» і поради щодо повноти;</li>
<li>фільтри за розміром, сезоном, матеріалом і типом підошви;</li>
<li>наявність розмірів у реальному часі;</li>
<li>примірка при отриманні на Новій Пошті й простий обмін;</li>
<li>догляд і аксесуари до кожної пари.</li>
</ul>
<h3>Інтеграції та маркетинг</h3>
<p>Синхронізуємо залишки по розмірах з 1С / BAS або CRM, налаштовуємо Google Shopping і Meta-фіди з варіаціями, накладений платіж і контроль повернень — так магазин взуття не продає розмір, якого вже немає.</p>
<h3>Скільки коштує розробка інтернет-магазину взуття</h3>
<p>Вартість — від {P1} (пакет «Старт»), від {P2} («Бізнес») та від {P3} («Pro / B2B»). Запуск — від 3 тижнів. Розробляємо на WooCommerce: без абонплати за платформу й комісії з продажів, магазин і дані належать вам. Обсяг робіт і ціну фіксуємо в договорі, оплата — 70% на початку проєкту та 30% після запуску.</p>',
		),
		'en' => array(
			'card' => 'Shoe store', 'cd' => 'Sizes and insole length, try-on, easy exchanges',
			'h1a' => 'Shoe online store development', 'h1b' => 'turnkey, built to sell',
			'service' => 'Shoe online store development',
			'title' => 'Shoe Online Store Development — from {P1T}',
			'desc' => 'Shoe online store development: size tables and insole length, season and material filters, try-on on delivery, easy exchanges. From $2000, from 3 weeks.',
			'seo' => '<h2>Shoe online store development</h2>
<p>The main fear when buying shoes online is getting the size wrong. A shoe store should show insole length and fit and clearly explain exchanges. We build stores where customers choose sizes with confidence and returns go down.</p>
<h3>What we build into a shoe online store</h3>
<ul>
<li>size tables with insole length in centimetres for each model;</li>
<li>“runs small / runs large” labels and width advice;</li>
<li>filters by size, season, material and sole type;</li>
<li>real-time size availability;</li>
<li>try-on on delivery and simple exchanges;</li>
<li>care products and accessories for every pair.</li>
</ul>
<h3>Integrations and marketing</h3>
<p>We sync size-level stock with your ERP or CRM, set up Google Shopping and Meta feeds with variations, cash on delivery and return tracking — so the store never sells a size that is already gone.</p>
<h3>How much does a shoe online store cost</h3>
<p>Prices start from {P1} (Start), {P2} (Business) and {P3} (Pro / B2B). Launch from 3 weeks. We build on WooCommerce: no platform subscription or sales commission, and the store and data belong to you. Scope and price are fixed in the contract; payment is 70% at the start of the project and 30% at launch.</p>',
		),
	),
	array(
		'uk_slug' => 'rozrobka-internet-magazinu-zootovariv', 'en_slug' => 'pet-supplies-online-store-development', 'icon' => 'paw',
		'uk' => array(
			'card' => 'Зоотовари', 'cd' => 'Підбір корму, підписка на повторні покупки, вагові фасування',
			'h1a' => 'Розробка інтернет-магазину зоотоварів', 'h1b' => 'під ключ, що продає',
			'service' => 'Розробка інтернет-магазину зоотоварів під ключ',
			'title' => 'Розробка інтернет-магазину зоотоварів — від {P1T}',
			'desc' => 'Інтернет-магазин зоотоварів під ключ: підбір корму за твариною й віком, підписка на повторну доставку, різні фасування, бонуси та Нова Пошта. Від $2000.',
			'seo' => '<h2>Розробка інтернет-магазину зоотоварів під ключ</h2>
<p>Власники тварин купують корм регулярно й залишаються з магазином роками, якщо їм зручно. Ми розробляємо магазини зоотоварів, де легко підібрати корм під вид, вік і особливості тварини, а повторне замовлення робиться в один клік.</p>
<h3>Що ми враховуємо в інтернет-магазині зоотоварів</h3>
<ul>
<li>підбір за твариною, породою, віком і особливими потребами;</li>
<li>різні фасування й вагові корми з перерахунком ціни;</li>
<li>підписка «доставляти кожні N тижнів»;</li>
<li>профіль улюбленця в кабінеті покупця;</li>
<li>бонусна програма й накопичувальні знижки;</li>
<li>поради ветеринара та статті для SEO-трафіку.</li>
</ul>
<h3>Інтеграції та маркетинг</h3>
<p>Підключаємо регулярні платежі для підписки, нагадування через email і Viber, імпорт від дистриб’юторів кормів, Google Shopping і маркетплейси — повторні покупки тут головне джерело прибутку.</p>
<h3>Скільки коштує розробка інтернет-магазину зоотоварів</h3>
<p>Вартість — від {P1} (пакет «Старт»), від {P2} («Бізнес») та від {P3} («Pro / B2B»). Запуск — від 3 тижнів. Розробляємо на WooCommerce: без абонплати за платформу й комісії з продажів, магазин і дані належать вам. Обсяг робіт і ціну фіксуємо в договорі, оплата — 70% на початку проєкту та 30% після запуску.</p>',
		),
		'en' => array(
			'card' => 'Pet supplies', 'cd' => 'Food finder, subscriptions, pack sizes',
			'h1a' => 'Pet Supplies online store development', 'h1b' => 'turnkey, built to sell',
			'service' => 'Pet Supplies online store development',
			'title' => 'Pet Supplies Online Store Development — from {P1T}',
			'desc' => 'Turnkey pet supplies online store: food finder by pet and age, repeat-delivery subscriptions, pack sizes, loyalty points and shipping. From $2000.',
			'seo' => '<h2>Pet Supplies online store development</h2>
<p>Pet owners buy food regularly and stay with a store for years if it is convenient. We build pet supply stores where choosing food by species, age and needs is easy and reordering takes one click.</p>
<h3>What we build into a pet supplies online store</h3>
<ul>
<li>finder by species, breed, age and special needs;</li>
<li>pack sizes and weighted food with live pricing;</li>
<li>“deliver every N weeks” subscriptions;</li>
<li>pet profile in the customer account;</li>
<li>loyalty points and tiered discounts;</li>
<li>vet tips and articles that bring SEO traffic.</li>
</ul>
<h3>Integrations and marketing</h3>
<p>We connect recurring payments for subscriptions, email and messenger reminders, distributor imports, Google Shopping and marketplaces — repeat purchases are the main profit source here.</p>
<h3>How much does a pet supplies online store cost</h3>
<p>Prices start from {P1} (Start), {P2} (Business) and {P3} (Pro / B2B). Launch from 3 weeks. We build on WooCommerce: no platform subscription or sales commission, and the store and data belong to you. Scope and price are fixed in the contract; payment is 70% at the start of the project and 30% at launch.</p>',
		),
	),
	array(
		'uk_slug' => 'rozrobka-internet-magazinu-budivelnih-materialiv', 'en_slug' => 'building-materials-online-store-development', 'icon' => 'brick',
		'uk' => array(
			'card' => 'Будівельні матеріали', 'cd' => 'Калькулятори витрат, фасування, опт і самовивіз зі складу',
			'h1a' => 'Розробка інтернет-магазину будівельних матеріалів', 'h1b' => 'під ключ, що продає',
			'service' => 'Розробка інтернет-магазину будівельних матеріалів під ключ',
			'title' => 'Розробка інтернет-магазину будівельних матеріалів — від {P1T}',
			'desc' => 'Розробка інтернет-магазину будматеріалів: калькулятори витрат, одиниці виміру й фасування, оптові ціни, доставка вантажним транспортом і самовивіз. Від $2000.',
			'seo' => '<h2>Розробка інтернет-магазину будівельних матеріалів під ключ</h2>
<p>Будматеріали купують «під об’єкт»: потрібно порахувати кількість, зрозуміти ціну за м² чи мішок і домовитися про доставку важкого вантажу. Ми розробляємо магазини будматеріалів, які рахують за клієнта й приймають оптові замовлення без дзвінків.</p>
<h3>Що ми враховуємо в інтернет-магазині будівельних матеріалів</h3>
<ul>
<li>калькулятори витрат: плитка, фарба, суміші, утеплювач;</li>
<li>різні одиниці виміру — м², м³, мішок, палета — з перерахунком;</li>
<li>оптові ціни та кабінет для будівельних компаній і прорабів;</li>
<li>розрахунок доставки вантажним транспортом і самовивіз зі складу;</li>
<li>рахунок для оплати з ПДВ для юросіб;</li>
<li>технічні характеристики й сертифікати на матеріали.</li>
</ul>
<h3>Інтеграції та маркетинг</h3>
<p>Інтегруємо магазин з 1С / BAS, залишками на кількох складах, рахунками для юросіб і CRM для менеджерів — у будматеріалах значна частина продажів іде через опт і повторні замовлення.</p>
<h3>Скільки коштує розробка інтернет-магазину будівельних матеріалів</h3>
<p>Вартість — від {P1} (пакет «Старт»), від {P2} («Бізнес») та від {P3} («Pro / B2B»). Запуск — від 3 тижнів. Розробляємо на WooCommerce: без абонплати за платформу й комісії з продажів, магазин і дані належать вам. Обсяг робіт і ціну фіксуємо в договорі, оплата — 70% на початку проєкту та 30% після запуску.</p>',
		),
		'en' => array(
			'card' => 'Building materials', 'cd' => 'Quantity calculators, packaging, wholesale and pickup',
			'h1a' => 'Building Materials online store development', 'h1b' => 'turnkey, built to sell',
			'service' => 'Building Materials online store development',
			'title' => 'Building Materials Online Store Development — from {P1T}',
			'desc' => 'Building materials online store development: quantity calculators, units and packaging, trade prices, freight delivery and warehouse pickup. From $2000.',
			'seo' => '<h2>Building Materials online store development</h2>
<p>Building materials are bought for a project: you need to calculate quantities, understand the price per m² or bag and arrange heavy delivery. We build building-materials stores that do the maths for the customer and accept trade orders without phone calls.</p>
<h3>What we build into a building materials online store</h3>
<ul>
<li>quantity calculators for tiles, paint, mixes and insulation;</li>
<li>multiple units — m², m³, bag, pallet — with conversion;</li>
<li>trade prices and an account for builders and contractors;</li>
<li>freight delivery pricing and warehouse pickup;</li>
<li>VAT invoices for companies;</li>
<li>technical specs and certificates for materials.</li>
</ul>
<h3>Integrations and marketing</h3>
<p>We integrate the store with your ERP, multi-warehouse stock, company invoicing and CRM for sales managers — a big share of building-material sales comes from trade and repeat orders.</p>
<h3>How much does a building materials online store cost</h3>
<p>Prices start from {P1} (Start), {P2} (Business) and {P3} (Pro / B2B). Launch from 3 weeks. We build on WooCommerce: no platform subscription or sales commission, and the store and data belong to you. Scope and price are fixed in the contract; payment is 70% at the start of the project and 30% at launch.</p>',
		),
	),
	array(
		'uk_slug' => 'rozrobka-internet-magazinu-sportivnih-tovariv', 'en_slug' => 'sporting-goods-online-store-development', 'icon' => 'ball',
		'uk' => array(
			'card' => 'Спортивні товари', 'cd' => 'Підбір за видом спорту, розміри, комплекти й тренажери',
			'h1a' => 'Розробка інтернет-магазину спортивних товарів', 'h1b' => 'під ключ, що продає',
			'service' => 'Розробка інтернет-магазину спортивних товарів під ключ',
			'title' => 'Розробка інтернет-магазину спортивних товарів — від {P1T}',
			'desc' => 'Інтернет-магазин спортивних товарів під ключ: каталог за видами спорту, розміри й варіації, тренажери з доставкою, оплата частинами, Нова Пошта. Від $2000.',
			'seo' => '<h2>Розробка інтернет-магазину спортивних товарів під ключ</h2>
<p>Спорттовари — це і одяг з розмірами, і великогабаритні тренажери, і дрібні аксесуари. Покупець шукає за видом спорту й рівнем підготовки. Ми розробляємо магазини, у яких спортсмен швидко збирає свій комплект, а великі покупки зручно оплатити частинами.</p>
<h3>Що ми враховуємо в інтернет-магазині спортивних товарів</h3>
<ul>
<li>каталог за видами спорту: біг, фітнес, велоспорт, єдиноборства, туризм;</li>
<li>розміри й варіації для одягу, взуття та інвентарю;</li>
<li>комплекти «все для старту» за видом спорту;</li>
<li>доставка й збирання тренажерів, оплата частинами;</li>
<li>фільтри за рівнем, брендом і призначенням;</li>
<li>відгуки й огляди від спортсменів.</li>
</ul>
<h3>Інтеграції та маркетинг</h3>
<p>Підключаємо оплату частинами, фіди для Google Shopping і маркетплейсів, синхронізацію залишків з постачальниками й email-розсилки під сезони — спорттовари добре продаються через сезонні кампанії.</p>
<h3>Скільки коштує розробка інтернет-магазину спортивних товарів</h3>
<p>Вартість — від {P1} (пакет «Старт»), від {P2} («Бізнес») та від {P3} («Pro / B2B»). Запуск — від 3 тижнів. Розробляємо на WooCommerce: без абонплати за платформу й комісії з продажів, магазин і дані належать вам. Обсяг робіт і ціну фіксуємо в договорі, оплата — 70% на початку проєкту та 30% після запуску.</p>',
		),
		'en' => array(
			'card' => 'Sporting goods', 'cd' => 'Sport-based finder, sizes, sets and equipment',
			'h1a' => 'Sporting Goods online store development', 'h1b' => 'turnkey, built to sell',
			'service' => 'Sporting Goods online store development',
			'title' => 'Sporting Goods Online Store Development — from {P1T}',
			'desc' => 'Turnkey sporting goods online store: catalogue by sport, sizes and variations, fitness equipment delivery, instalments and shipping. From $2000.',
			'seo' => '<h2>Sporting Goods online store development</h2>
<p>Sporting goods range from sized apparel to bulky equipment and small accessories. Shoppers search by sport and level. We build stores where athletes put together their kit quickly and big purchases are easy to pay in instalments.</p>
<h3>What we build into a sporting goods online store</h3>
<ul>
<li>catalogue by sport: running, fitness, cycling, martial arts, outdoor;</li>
<li>sizes and variations for apparel, footwear and gear;</li>
<li>“starter kit” sets for each sport;</li>
<li>equipment delivery and assembly, instalments;</li>
<li>filters by level, brand and purpose;</li>
<li>reviews and athlete recommendations.</li>
</ul>
<h3>Integrations and marketing</h3>
<p>We connect instalments, Google Shopping and marketplace feeds, supplier stock sync and seasonal email campaigns — sporting goods sell well through seasonal promotions.</p>
<h3>How much does a sporting goods online store cost</h3>
<p>Prices start from {P1} (Start), {P2} (Business) and {P3} (Pro / B2B). Launch from 3 weeks. We build on WooCommerce: no platform subscription or sales commission, and the store and data belong to you. Scope and price are fixed in the contract; payment is 70% at the start of the project and 30% at launch.</p>',
		),
	),
	array(
		'uk_slug' => 'rozrobka-internet-magazinu-dityachih-tovariv', 'en_slug' => 'kids-products-online-store-development', 'icon' => 'baby',
		'uk' => array(
			'card' => 'Дитячі товари', 'cd' => 'Підбір за віком, безпека й сертифікати, подарунки',
			'h1a' => 'Розробка інтернет-магазину дитячих товарів', 'h1b' => 'під ключ, що продає',
			'service' => 'Розробка інтернет-магазину дитячих товарів під ключ',
			'title' => 'Розробка інтернет-магазину дитячих товарів — від {P1T}',
			'desc' => 'Розробка інтернет-магазину дитячих товарів: підбір за віком і статтю, сертифікати безпеки, подарункове пакування, бонуси, оплата й Нова Пошта. Від $2000.',
			'seo' => '<h2>Розробка інтернет-магазину дитячих товарів під ключ</h2>
<p>Батьки обирають дитячі товари уважно: вік, безпечні матеріали, сертифікати, відгуки інших батьків. А ще часто купують подарунки. Ми розробляємо магазини дитячих товарів, яким довіряють і до яких повертаються, поки дитина росте.</p>
<h3>Що ми враховуємо в інтернет-магазині дитячих товарів</h3>
<ul>
<li>підбір за віком, статтю й етапом розвитку;</li>
<li>матеріали, сертифікати безпеки й вікові обмеження на картці;</li>
<li>подарункове пакування й листівка до замовлення;</li>
<li>ідеї подарунків за бюджетом і приводом;</li>
<li>кабінет з профілем дитини та нагадуваннями;</li>
<li>бонусна програма для постійних покупців.</li>
</ul>
<h3>Інтеграції та маркетинг</h3>
<p>Налаштовуємо email-розсилки за віком дитини, бонусну програму, фіди для маркетплейсів і Google Shopping, оплату частинами для дорогих позицій (коляски, автокрісла) і доставку Новою Поштою.</p>
<h3>Скільки коштує розробка інтернет-магазину дитячих товарів</h3>
<p>Вартість — від {P1} (пакет «Старт»), від {P2} («Бізнес») та від {P3} («Pro / B2B»). Запуск — від 3 тижнів. Розробляємо на WooCommerce: без абонплати за платформу й комісії з продажів, магазин і дані належать вам. Обсяг робіт і ціну фіксуємо в договорі, оплата — 70% на початку проєкту та 30% після запуску.</p>',
		),
		'en' => array(
			'card' => 'Kids’ products', 'cd' => 'Age-based finder, safety certificates, gifts',
			'h1a' => 'Kids’ Products online store development', 'h1b' => 'turnkey, built to sell',
			'service' => 'Kids’ Products online store development',
			'title' => 'Kids’ Products Online Store Development — from {P1T}',
			'desc' => 'Kids’ products online store development: age and gender finder, safety certificates, gift wrapping, loyalty points, payments and shipping. From $2000.',
			'seo' => '<h2>Kids’ Products online store development</h2>
<p>Parents choose kids’ products carefully: age, safe materials, certificates and reviews from other parents. They also buy a lot of gifts. We build kids’ stores that parents trust and keep coming back to as their child grows.</p>
<h3>What we build into a kids’ products online store</h3>
<ul>
<li>finder by age, gender and development stage;</li>
<li>materials, safety certificates and age limits on the product page;</li>
<li>gift wrapping and a card with the order;</li>
<li>gift ideas by budget and occasion;</li>
<li>account with a child profile and reminders;</li>
<li>loyalty programme for returning customers.</li>
</ul>
<h3>Integrations and marketing</h3>
<p>We set up age-based email campaigns, loyalty points, marketplace and Google Shopping feeds, instalments for big-ticket items like strollers and car seats, and shipping.</p>
<h3>How much does a kids’ products online store cost</h3>
<p>Prices start from {P1} (Start), {P2} (Business) and {P3} (Pro / B2B). Launch from 3 weeks. We build on WooCommerce: no platform subscription or sales commission, and the store and data belong to you. Scope and price are fixed in the contract; payment is 70% at the start of the project and 30% at launch.</p>',
		),
	),
	array(
		'uk_slug' => 'rozrobka-internet-magazinu-medichnih-tovariv', 'en_slug' => 'medical-supplies-online-store-development', 'icon' => 'cross',
		'uk' => array(
			'card' => 'Медичні товари', 'cd' => 'Сертифікати, інструкції, вимоги до контенту й довіра',
			'h1a' => 'Розробка інтернет-магазину медичних товарів', 'h1b' => 'під ключ, що продає',
			'service' => 'Розробка інтернет-магазину медичних товарів під ключ',
			'title' => 'Розробка інтернет-магазину медичних товарів — від {P1T}',
			'desc' => 'Інтернет-магазин медичних товарів і медтехніки під ключ: сертифікати й інструкції, коректний контент, B2B-ціни для клінік, оплата й доставка. Від $2000.',
			'seo' => '<h2>Розробка інтернет-магазину медичних товарів під ключ</h2>
<p>У медичній ніші магазин продає довірою: покупцю потрібні сертифікати, інструкції, точні характеристики й відчуття, що за сайтом стоїть надійна компанія. Ми розробляємо магазини медичних товарів і медтехніки з акуратною подачею інформації та урахуванням вимог до реклами в цій сфері.</p>
<h3>Що ми враховуємо в інтернет-магазині медичних товарів</h3>
<ul>
<li>сертифікати, реєстраційні документи й інструкції до кожного товару;</li>
<li>точні технічні характеристики й призначення без зайвих обіцянок;</li>
<li>B2B-ціни та кабінет для клінік, аптек і лабораторій;</li>
<li>рахунки для юридичних осіб і закупівля партіями;</li>
<li>консультація фахівця перед покупкою;</li>
<li>конфіденційна упаковка й доставка.</li>
</ul>
<h3>Інтеграції та маркетинг</h3>
<p>Інтегруємо магазин з 1С / BAS, CRM для B2B-продажів і доставкою. Окремо враховуємо обмеження Google Ads для медичних товарів і будуємо SEO-структуру, щоб магазин отримував стабільний органічний трафік.</p>
<h3>Скільки коштує розробка інтернет-магазину медичних товарів</h3>
<p>Вартість — від {P1} (пакет «Старт»), від {P2} («Бізнес») та від {P3} («Pro / B2B»). Запуск — від 3 тижнів. Розробляємо на WooCommerce: без абонплати за платформу й комісії з продажів, магазин і дані належать вам. Обсяг робіт і ціну фіксуємо в договорі, оплата — 70% на початку проєкту та 30% після запуску.</p>',
		),
		'en' => array(
			'card' => 'Medical supplies', 'cd' => 'Certificates, instructions, content rules and trust',
			'h1a' => 'Medical Supplies online store development', 'h1b' => 'turnkey, built to sell',
			'service' => 'Medical Supplies online store development',
			'title' => 'Medical Supplies Online Store Development — from {P1T}',
			'desc' => 'Turnkey medical supplies online store: certificates and instructions, compliant content, trade prices for clinics, payments and shipping. From $2000.',
			'seo' => '<h2>Medical Supplies online store development</h2>
<p>In the medical niche, a store sells through trust: customers need certificates, instructions, accurate specs and the sense of a reliable company behind the site. We build medical supplies stores with careful content and attention to advertising restrictions in this field.</p>
<h3>What we build into a medical supplies online store</h3>
<ul>
<li>certificates, registration documents and instructions for each product;</li>
<li>accurate specs and intended use without overpromising;</li>
<li>trade prices and an account for clinics, pharmacies and labs;</li>
<li>company invoices and bulk purchasing;</li>
<li>specialist consultation before buying;</li>
<li>discreet packaging and delivery.</li>
</ul>
<h3>Integrations and marketing</h3>
<p>We integrate the store with your ERP, a CRM for B2B sales and shipping. We also account for Google Ads restrictions on medical products and build an SEO structure that brings steady organic traffic.</p>
<h3>How much does a medical supplies online store cost</h3>
<p>Prices start from {P1} (Start), {P2} (Business) and {P3} (Pro / B2B). Launch from 3 weeks. We build on WooCommerce: no platform subscription or sales commission, and the store and data belong to you. Scope and price are fixed in the contract; payment is 70% at the start of the project and 30% at launch.</p>',
		),
	),
	array(
		'uk_slug' => 'rozrobka-internet-magazinu-parfumeriyi', 'en_slug' => 'perfume-online-store-development', 'icon' => 'bottle',
		'uk' => array(
			'card' => 'Парфумерія', 'cd' => 'Ноти аромату, пробники й мініатюри, подарунки',
			'h1a' => 'Розробка інтернет-магазину парфумерії', 'h1b' => 'під ключ, що продає',
			'service' => 'Розробка інтернет-магазину парфумерії під ключ',
			'title' => 'Розробка інтернет-магазину парфумерії — від {P1T}',
			'desc' => 'Розробка інтернет-магазину парфумерії: фільтри за нотами й сімейством ароматів, пробники та розпив, гарантія оригінальності, подарункове пакування. Від $2000.',
			'seo' => '<h2>Розробка інтернет-магазину парфумерії під ключ</h2>
<p>Аромат неможливо понюхати через екран, тому магазин парфумерії має продавати описом, атмосферою й довірою до оригінальності. Ми розробляємо парфумерні магазини з підбором за нотами, пробниками й преміальною подачею, яка виправдовує ціну.</p>
<h3>Що ми враховуємо в інтернет-магазині парфумерії</h3>
<ul>
<li>фільтри за нотами, сімейством аромату, сезоном і стійкістю;</li>
<li>піраміда аромату й «схожі аромати» на картці;</li>
<li>пробники, мініатюри й розпив з окремими цінами;</li>
<li>гарантія оригінальності та інформація про постачання;</li>
<li>подарункове пакування й набори;</li>
<li>програма лояльності для постійних клієнтів.</li>
</ul>
<h3>Інтеграції та маркетинг</h3>
<p>Підключаємо фіди для Google Shopping і Meta Ads, email-розсилки до свят, оплату частинами для дорогих ароматів і синхронізацію залишків — у парфумерії пік продажів припадає на свята.</p>
<h3>Скільки коштує розробка інтернет-магазину парфумерії</h3>
<p>Вартість — від {P1} (пакет «Старт»), від {P2} («Бізнес») та від {P3} («Pro / B2B»). Запуск — від 3 тижнів. Розробляємо на WooCommerce: без абонплати за платформу й комісії з продажів, магазин і дані належать вам. Обсяг робіт і ціну фіксуємо в договорі, оплата — 70% на початку проєкту та 30% після запуску.</p>',
		),
		'en' => array(
			'card' => 'Perfume store', 'cd' => 'Fragrance notes, samples and minis, gifts',
			'h1a' => 'Perfume online store development', 'h1b' => 'turnkey, built to sell',
			'service' => 'Perfume online store development',
			'title' => 'Perfume Online Store Development — from {P1T}',
			'desc' => 'Perfume online store development: filters by notes and fragrance family, samples and decants, authenticity guarantee, gift wrapping. From $2000.',
			'seo' => '<h2>Perfume online store development</h2>
<p>You cannot smell a fragrance through a screen, so a perfume store has to sell through description, atmosphere and trust in authenticity. We build perfume stores with note-based discovery, samples and premium presentation that justifies the price.</p>
<h3>What we build into a perfume online store</h3>
<ul>
<li>filters by notes, fragrance family, season and longevity;</li>
<li>fragrance pyramid and “similar scents” on the product page;</li>
<li>samples, minis and decants with their own prices;</li>
<li>authenticity guarantee and sourcing information;</li>
<li>gift wrapping and sets;</li>
<li>loyalty programme for returning customers.</li>
</ul>
<h3>Integrations and marketing</h3>
<p>We connect Google Shopping and Meta Ads feeds, holiday email campaigns, instalments for premium fragrances and stock sync — perfume sales peak around holidays.</p>
<h3>How much does a perfume online store cost</h3>
<p>Prices start from {P1} (Start), {P2} (Business) and {P3} (Pro / B2B). Launch from 3 weeks. We build on WooCommerce: no platform subscription or sales commission, and the store and data belong to you. Scope and price are fixed in the contract; payment is 70% at the start of the project and 30% at launch.</p>',
		),
	),
	array(
		'uk_slug' => 'rozrobka-internet-magazinu-yuvelirnih-virobiv', 'en_slug' => 'jewellery-online-store-development', 'icon' => 'gem',
		'uk' => array(
			'card' => 'Ювелірні вироби', 'cd' => 'Проба, вага, розміри, преміальна подача й довіра',
			'h1a' => 'Розробка інтернет-магазину ювелірних виробів', 'h1b' => 'під ключ, що продає',
			'service' => 'Розробка інтернет-магазину ювелірних виробів під ключ',
			'title' => 'Розробка інтернет-магазину ювелірних виробів — від {P1T}',
			'desc' => 'Інтернет-магазин ювелірних виробів під ключ: проба, вага й вставки, підбір розміру каблучки, оплата частинами, страхування доставки. Від $2000.',
			'seo' => '<h2>Розробка інтернет-магазину ювелірних виробів під ключ</h2>
<p>Прикраси купують емоційно, але дорого — тому ювелірний магазин має виглядати бездоганно й знімати будь-які сумніви щодо якості. Ми розробляємо ювелірні магазини з преміальною подачею, детальними характеристиками й зручним підбором розміру.</p>
<h3>Що ми враховуємо в інтернет-магазині ювелірних виробів</h3>
<ul>
<li>проба, метал, вага, вставки й характеристики каменів;</li>
<li>підбір розміру каблучки та браслета з інструкцією;</li>
<li>макрофото, відео й обертання прикраси на 360°;</li>
<li>колекції, подарунки за приводом і бюджетом;</li>
<li>оплата частинами та страхування доставки;</li>
<li>гравіювання й індивідуальні замовлення.</li>
</ul>
<h3>Інтеграції та маркетинг</h3>
<p>Підключаємо оплату частинами, CRM для консультацій, Google Shopping і Meta Ads з якісними фото, а також SEO-структуру під запити за типом прикраси, металом і каменем — у ювелірній ніші це стабільний безкоштовний трафік.</p>
<h3>Скільки коштує розробка інтернет-магазину ювелірних виробів</h3>
<p>Вартість — від {P1} (пакет «Старт»), від {P2} («Бізнес») та від {P3} («Pro / B2B»). Запуск — від 3 тижнів. Розробляємо на WooCommerce: без абонплати за платформу й комісії з продажів, магазин і дані належать вам. Обсяг робіт і ціну фіксуємо в договорі, оплата — 70% на початку проєкту та 30% після запуску.</p>',
		),
		'en' => array(
			'card' => 'Jewellery', 'cd' => 'Metal, weight, sizes, premium presentation and trust',
			'h1a' => 'Jewellery online store development', 'h1b' => 'turnkey, built to sell',
			'service' => 'Jewellery online store development',
			'title' => 'Jewellery Online Store Development — from {P1T}',
			'desc' => 'Turnkey jewellery online store: metal, weight and stones, ring size guide, premium design, instalments and insured delivery. From $2000.',
			'seo' => '<h2>Jewellery online store development</h2>
<p>Jewellery is an emotional but expensive purchase, so the store must look flawless and remove any doubts about quality. We build jewellery stores with premium presentation, detailed specs and an easy size guide.</p>
<h3>What we build into a jewellery online store</h3>
<ul>
<li>metal, purity, weight, stones and their characteristics;</li>
<li>ring and bracelet size guide with instructions;</li>
<li>macro photos, video and 360° view;</li>
<li>collections and gifts by occasion and budget;</li>
<li>instalments and insured delivery;</li>
<li>engraving and custom orders.</li>
</ul>
<h3>Integrations and marketing</h3>
<p>We connect instalments, a CRM for consultations, Google Shopping and Meta Ads with quality imagery, and an SEO structure for searches by jewellery type, metal and stone — a steady source of free traffic.</p>
<h3>How much does a jewellery online store cost</h3>
<p>Prices start from {P1} (Start), {P2} (Business) and {P3} (Pro / B2B). Launch from 3 weeks. We build on WooCommerce: no platform subscription or sales commission, and the store and data belong to you. Scope and price are fixed in the contract; payment is 70% at the start of the project and 30% at launch.</p>',
		),
	),
);

/* визначаємо нішу поточної сторінки */
$rv_pid   = (int) get_queried_object_id();
$rv_slugs = array( (string) get_post_field( 'post_name', $rv_pid ) );
if ( function_exists( 'pll_get_post' ) ) {
	$rv_uk_id = pll_get_post( $rv_pid, 'uk' );
	if ( $rv_uk_id ) $rv_slugs[] = (string) get_post_field( 'post_name', $rv_uk_id );
}
$rv_niche = null;
foreach ( $rv_niche_data as $rv_n ) {
	if ( array_intersect( $rv_slugs, array( $rv_n['uk_slug'], $rv_n['en_slug'] ) ) ) { $rv_niche = $rv_n; break; }
}
$rv_pfill = static function ( $s ) use ( $rv_wd_prices ) {
	$m = static function ( $n ) { return '$' . number_format( (int) $n, 0, '.', ' ' ); };
	return str_replace(
		array( '{P1T}', '{P1}', '{P2}', '{P3}' ),
		array( '$' . (int) $rv_wd_prices['start'], $m( $rv_wd_prices['start'] ), $m( $rv_wd_prices['business'] ), $m( $rv_wd_prices['pro'] ) ),
		$s
	);
};
if ( $rv_niche ) {
	$rv_nl = $rv_niche[ $rv_en ? 'en' : 'uk' ];
	$T['h1a']          = $rv_nl['h1a'];
	$T['h1b']          = $rv_nl['h1b'];
	$T['service_name'] = $rv_nl['service'];
	$T['seo']          = $rv_pfill( $rv_nl['seo'] );
	/* title і description ніші (працює і з Yoast / Rank Math, і без них) */
	$rv_meta_t = $rv_pfill( $rv_nl['title'] );
	$rv_meta_d = $rv_nl['desc'];
	$rv_set_t  = static function () use ( $rv_meta_t ) { return $rv_meta_t; };
	$rv_set_d  = static function () use ( $rv_meta_d ) { return $rv_meta_d; };
	add_filter( 'pre_get_document_title', $rv_set_t, 999 );
	foreach ( array( 'wpseo_title', 'wpseo_opengraph_title', 'wpseo_twitter_title', 'rank_math/frontend/title', 'rank_math/opengraph/facebook/og_title' ) as $rv_h ) add_filter( $rv_h, $rv_set_t, 999 );
	foreach ( array( 'wpseo_metadesc', 'wpseo_opengraph_desc', 'wpseo_twitter_description', 'rank_math/frontend/description', 'rank_math/opengraph/facebook/og_description' ) as $rv_h ) add_filter( $rv_h, $rv_set_d, 999 );
	if ( ! defined( 'WPSEO_VERSION' ) && ! class_exists( 'RankMath' ) && ! defined( 'AIOSEO_VERSION' ) ) {
		add_action( 'wp_head', static function () use ( $rv_meta_d ) { echo '<meta name="description" content="' . esc_attr( $rv_meta_d ) . '">' . "\n"; }, 1 );
	}
}

/* картки ніш (блок «Інтернет-магазини для вашої ніші»): кожна картка — посилання на сторінку ніші */
$rv_wd_niche_self = $rv_niche ? $rv_niche['uk_slug'] : '';
$rv_wd_niches = array();
foreach ( $rv_niche_data as $rv_n ) {
	$rv_wd_niches[] = array( $rv_n['uk_slug'], $rv_n['uk']['card'], $rv_n['en']['card'], $rv_n['uk']['cd'], $rv_n['en']['cd'], $rv_n['icon'], $rv_n['en_slug'] );
}
$rv_wd_niche_icons = array(
	'shirt'  => '<svg viewBox="0 0 24 24"><path d="M8 3l-5 3 2 4 2-1v12h10V9l2 1 2-4-5-3a4 4 0 0 1-8 0z"/></svg>',
	'drop'   => '<svg viewBox="0 0 24 24"><path d="M12 3s6 6.5 6 11a6 6 0 0 1-12 0c0-4.5 6-11 6-11z"/></svg>',
	'gem'    => '<svg viewBox="0 0 24 24"><path d="M6 3h12l4 6-10 12L2 9z"/><path d="M2 9h20M12 21L8 9l4-6 4 6z"/></svg>',
	'gear'   => '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.9 4.9l2.1 2.1M17 17l2.1 2.1M4.9 19.1L7 17M17 7l2.1-2.1"/></svg>',
	'sofa'   => '<svg viewBox="0 0 24 24"><path d="M4 11V8a3 3 0 0 1 3-3h10a3 3 0 0 1 3 3v3"/><path d="M2 13a2 2 0 0 1 4 0v2h12v-2a2 2 0 0 1 4 0v5H2zM5 18v2M19 18v2"/></svg>',
	'brick'  => '<svg viewBox="0 0 24 24"><path d="M3 5h18v14H3zM3 9.7h18M3 14.3h18M9 5v4.7M15 5v4.7M6 9.7v4.6M12 9.7v4.6M18 9.7v4.6M9 14.3V19M15 14.3V19"/></svg>',
	'cross'  => '<svg viewBox="0 0 24 24"><path d="M9 3h6v6h6v6h-6v6H9v-6H3V9h6z"/></svg>',
	'chip'   => '<svg viewBox="0 0 24 24"><rect x="6" y="6" width="12" height="12" rx="2"/><rect x="9.5" y="9.5" width="5" height="5" rx="1"/><path d="M9 3v3M15 3v3M9 18v3M15 18v3M3 9h3M3 15h3M18 9h3M18 15h3"/></svg>',
	'basket' => '<svg viewBox="0 0 24 24"><path d="M3 10h18l-2 10H5z"/><path d="M8 10l3-6M16 10l-3-6M9 14v3M15 14v3M12 14v3"/></svg>',
	'home'   => '<svg viewBox="0 0 24 24"><path d="M3 11l9-7 9 7"/><path d="M5 10v10h14V10M10 20v-6h4v6"/></svg>',
	'shoe'   => '<svg viewBox="0 0 24 24"><path d="M3 17V7h4l2 4c3 0 7 1 10 3a3 3 0 0 1 2 3H3z"/><path d="M3 20h18M10 11l1.5-2M13 11.5l1.5-2"/></svg>',
	'paw'    => '<svg viewBox="0 0 24 24"><ellipse cx="12" cy="16" rx="4.5" ry="4"/><circle cx="6" cy="10" r="2"/><circle cx="18" cy="10" r="2"/><circle cx="9.5" cy="5.5" r="2"/><circle cx="14.5" cy="5.5" r="2"/></svg>',
	'ball'   => '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 3c3 3 3 15 0 18M3 12h18M5.5 5.5c4 2 9 2 13 0M5.5 18.5c4-2 9-2 13 0"/></svg>',
	'baby'   => '<svg viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M6 21c0-4 2.7-7 6-7s6 3 6 7"/><path d="M10.5 7h.01M13.5 7h.01M10.5 9c.8.6 2.2.6 3 0"/></svg>',
	'bottle' => '<svg viewBox="0 0 24 24"><rect x="5" y="9" width="14" height="12" rx="3"/><path d="M9 9V6h6v3M10 6V3h4v3M9 14h6"/></svg>',
	'box'    => '<svg viewBox="0 0 24 24"><path d="M3 7l9-4 9 4v10l-9 4-9-4z"/><path d="M3 7l9 4 9-4M12 11v10"/></svg>',
	'cart'   => '<svg viewBox="0 0 24 24"><path d="M3 4h2l2.5 11h11L21 7H6.5"/><circle cx="9" cy="19.5" r="1.5"/><circle cx="17" cy="19.5" r="1.5"/></svg>',
);
/* посилання на нішеві сторінки — прямі URL: UA → /uk/{uk_slug}/, EN → /{en_slug}/; поточна сторінка — без посилання */
$rv_wd_niche_url = static function ( $uk_slug, $en_slug ) use ( $rv_wd_niche_self, $rv_en ) {
	if ( $uk_slug === $rv_wd_niche_self ) return '';
	return $rv_en ? home_url( '/' . $en_slug . '/' ) : home_url( '/uk/' . $uk_slug . '/' );
};

$rv_wd_icons = array(
	'phone' => '<svg viewBox="0 0 24 24"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/></svg>',
	'tg'    => '<svg viewBox="0 0 24 24"><path d="M21 4L3 11l6 2.2L18 7l-7 7.5V20l3.3-3.6L18 19z"/></svg>',
	'wa'    => '<svg viewBox="0 0 24 24"><path d="M4 20l1.3-3.9A8.5 8.5 0 1 1 8 18.8z"/><path d="M9 8.5c0 3.5 3 6.5 6.5 6.5l1-1.8-2-1-1 1a5 5 0 0 1-2.7-2.7l1-1-1-2z"/></svg>',
	'check' => '<svg viewBox="0 0 24 24"><path d="M4.5 12.5l5 5L19.5 7"/></svg>',
	'arrow' => '<svg viewBox="0 0 16 16"><path d="M4.5 11.5l7-7M6 4.5h5.5V10"/></svg>',
	'x'     => '<svg viewBox="0 0 24 24"><path d="M7 7l10 10M17 7L7 17"/></svg>',
	'right' => '<svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>',
);
$rv_wd_in_icons = array(
	'<svg viewBox="0 0 24 24"><rect x="3" y="5.5" width="18" height="13" rx="2.5"/><path d="M3 10h18M7 15h4"/></svg>',
	'<svg viewBox="0 0 24 24"><path d="M3 7l9-4 9 4v10l-9 4-9-4z"/><path d="M3 7l9 4 9-4M12 11v10"/></svg>',
	'<svg viewBox="0 0 24 24"><path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/></svg>',
	'<svg viewBox="0 0 24 24"><path d="M4 18l5-5 4 3 7-8"/><path d="M15 8h5v5"/></svg>',
);
if ( ! function_exists( 'rv_wd_money' ) ) {
	function rv_wd_money( $n ) { return '$' . number_format( (int) $n, 0, '.', ' ' ); }
}

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
<main class="rv-wd rv-ec" id="rv-wd">
<style id="rv-wd-css">
/* ======================================================================
   REVIVE — E-COMMERCE DEVELOPMENT LANDING
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

/* HERO В ОДИН ЕКРАН: ПК і телефон (висоту шапки теми задає --rv-hdr) */
.rv-ec{--rv-hdr:90px}
.rv-ec .rv-wd-hero{display:flex;align-items:center;min-height:calc(100svh - var(--rv-hdr));padding-top:clamp(20px,3.5vh,44px);padding-bottom:clamp(24px,4vh,52px)}
.rv-ec .rv-wd-hero > .rv-wd__in{width:100%}
.rv-ec .rv-wd-hero h1{font-size:clamp(34px,min(3.7vw,6.2vh),58px)}
.rv-ec .rv-wd-hero__lead{margin-top:clamp(14px,2.4vh,24px)!important;font-size:clamp(15px,min(1.15vw,2vh),18px)}
.rv-ec .rv-wd-hero__position{margin-top:clamp(14px,2.2vh,22px)!important}
.rv-ec .rv-wd-hero__cta{margin-top:clamp(16px,2.6vh,28px)}
.rv-ec .rv-wd-stats{margin-top:clamp(18px,3vh,34px)}
.rv-ec .rv-wd-vis{max-height:calc(100svh - var(--rv-hdr) - 60px)}
@media(min-width:1101px){
  .rv-ec .rv-wd-hero .rv-wd-vis{scale:1;width:min(100%,calc((100svh - var(--rv-hdr) - 60px) * 1.08));margin-left:auto}
}
@media(min-width:1101px) and (max-height:820px){
  .rv-ec .rv-wd-hero__note{display:none}
  .rv-ec .rv-wd-stats div{padding-top:12px}
  .rv-ec .rv-wd-stats b{font-size:26px}
}
@media(max-width:1100px){
  .rv-ec .rv-wd-hero{display:block;min-height:0}
}
@media(max-width:700px){
  /* телефон: текст + кнопка + цифри в першому екрані, анімація — фоном праворуч */
  .rv-ec{--rv-hdr:70px}
  .rv-ec .rv-wd-hero{display:flex;flex-direction:column;justify-content:center;min-height:calc(100svh - var(--rv-hdr));padding-top:18px;padding-bottom:24px}
  .rv-ec .rv-wd-hero .rv-wd__in{gap:0}
  .rv-ec .rv-wd-hero__copy{position:relative;z-index:2}
  .rv-ec .rv-wd-hero .rv-wd-vis{display:none!important} /* телефон: без фонової анімації за текстом */
  .rv-ec .rv-wd-hero .rv-wd-toast{display:none}
  .rv-ec .rv-wd-hero h1{font-size:clamp(28px,min(8.4vw,4.9vh),38px)}
  .rv-ec .rv-wd-hero__lead{font-size:14px;line-height:1.5}
  .rv-ec .rv-wd-hero__note{display:none}
  .rv-ec .rv-wd-hero__cta{gap:4px}
  .rv-ec .rv-wd-stats{margin-top:20px}
}

/* ======================================================================
   E-COMMERCE: доповнення до базових стилів сторінки послуги
   ====================================================================== */
.rv-ec .rv-wd-int__grid li::before,.rv-ec .rv-wd-ni__grid > li::before{content:none!important;display:none!important}

/* тема додає спискам відступ зліва — у блоках сторінки він не потрібен */
.rv-ec .rv-wd-ni__grid,.rv-ec .rv-wd-int__grid ul,.rv-ec .rv-wd-int__grid,.rv-ec .rv-wd-fq__list{padding:0!important;margin-left:0!important;margin-right:0!important;width:100%}
.rv-ec .rv-wd-ni__grid > li{margin:0!important;padding:0!important;min-width:0}

/* hero: вітрина магазину замість лендінгу */
.rv-ec .rv-wd-bw__cards{width:44%;gap:8px}
.rv-ec .rv-wd-bw__cards i{position:relative;aspect-ratio:.82;background:linear-gradient(170deg,rgba(255,255,255,.14) 0 52%,rgba(255,255,255,.03) 52%)}
.rv-ec .rv-wd-bw__cards i::before{content:"";position:absolute;left:12%;right:30%;bottom:26%;height:3px;border-radius:2px;background:rgba(255,255,255,.35)}
.rv-ec .rv-wd-bw__cards i::after{content:"";position:absolute;left:12%;width:38%;bottom:12%;height:5px;border-radius:3px;background:var(--gold);box-shadow:0 0 10px rgba(245,197,66,.5)}
.rv-ec .rv-wd-bw__nav b{white-space:nowrap}
.rv-wd-toast--pay{right:2%;top:6%;opacity:0;animation:rvWdToast 6s var(--ease) 2.6s infinite}
.rv-wd-toast--pay .rv-wd-toast__ic{background:rgba(245,197,66,.14);color:var(--gold)}
.rv-wd-toast--pay b{color:var(--gold)}

.rv-wd-offer__alt{max-width:52ch;margin-top:22px!important;padding:14px 18px;border-radius:16px;border:1px solid rgba(245,197,66,.28);border-left:3px solid var(--gold);
  background:rgba(245,197,66,.06);font-size:.94rem;line-height:1.55;color:rgba(255,255,255,.86)}
.rv-wd-offer__alt b{display:block;margin-bottom:3px;font-family:var(--h);font-weight:800;color:var(--gold)}
@media(max-width:700px){.rv-ec .rv-wd-offer__alt{margin-top:14px!important;padding:12px 14px;font-size:.84rem}}

/* ── PAIN → SOLUTION ─────────────────────────────────────────────── */
.rv-wd-pain__list{display:grid;gap:12px}
.rv-wd-pain__row{position:relative;overflow:hidden;isolation:isolate;display:grid;grid-template-columns:54px minmax(0,.85fr) 44px minmax(0,1.3fr);align-items:center;gap:clamp(14px,2vw,28px);
  padding:22px clamp(18px,2.4vw,30px);border-radius:24px;border:1px solid rgba(255,255,255,.1);background:linear-gradient(160deg,rgba(255,255,255,.055),rgba(255,255,255,.012) 70%);
  transition:border-color .4s ease,transform .5s var(--ease)}
.rv-wd-pain__row::before{content:"";position:absolute;z-index:-1;top:0;bottom:0;left:42%;right:0;background:linear-gradient(90deg,rgba(245,197,66,.09),transparent 70%);
  transform:scaleX(0);transform-origin:left;transition:transform 1.2s var(--ease) .35s}
.rv-wd-pain__row.is-in::before,.rv-wd:not(.rv-wd-js) .rv-wd-pain__row::before{transform:scaleX(1)}
.rv-wd-pain__row:hover{border-color:rgba(245,197,66,.35);transform:translateX(4px)}
.rv-wd-pain__n{font-family:var(--mono);font-size:.72rem;color:rgba(255,255,255,.45)}
.rv-wd-pain__p small,.rv-wd-pain__s small{display:block;margin-bottom:6px;font-family:var(--mono);font-size:.58rem;letter-spacing:.14em;text-transform:uppercase}
.rv-wd-pain__p small{color:#FF9B9B}
.rv-wd-pain__s small{color:var(--ok)}
.rv-wd-pain__p h3{display:flex;align-items:center;gap:10px;font-family:var(--h);font-weight:800;font-size:clamp(17px,1.4vw,21px);line-height:1.2;letter-spacing:-.015em}
.rv-wd-pain__p h3 svg{flex:none;width:20px;height:20px;padding:4px;border-radius:50%;background:rgba(255,120,120,.12);fill:none;stroke:#FF9B9B;stroke-width:2.6;stroke-linecap:round}
.rv-wd-pain__arr{display:grid;place-items:center;width:44px;height:44px;border-radius:50%;border:1px solid rgba(245,197,66,.4);background:rgba(245,197,66,.06)}
.rv-wd-pain__arr svg{width:18px;height:18px;fill:none;stroke:var(--gold);stroke-width:2;stroke-linecap:round;stroke-linejoin:round;animation:rvCarNudge 1.8s ease-in-out infinite}
.rv-wd-pain__s p{font-size:.95rem;line-height:1.55;color:rgba(255,255,255,.84)}
@keyframes rvCarNudge{50%{transform:translateX(4px)}}

/* ── INTEGRATIONS ────────────────────────────────────────────────── */
.rv-wd-int__marq{display:grid;gap:12px;margin:0 calc(var(--px) * -1) clamp(28px,3vw,44px);
  -webkit-mask-image:linear-gradient(90deg,transparent,#000 10%,#000 90%,transparent);mask-image:linear-gradient(90deg,transparent,#000 10%,#000 90%,transparent)}
.rv-wd-int__track{display:flex;gap:12px;width:max-content;animation:rvWdMarq 46s linear infinite}
.rv-wd-int__track--rev{animation-direction:reverse;animation-duration:54s}
.rv-wd-int__marq:hover .rv-wd-int__track{animation-play-state:paused}
.rv-wd-int__track span{display:inline-flex;align-items:center;gap:10px;padding:14px 22px;border-radius:999px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.035);
  font-family:var(--h);font-weight:700;font-size:1rem;white-space:nowrap;transition:border-color .3s ease,color .3s ease}
.rv-wd-int__track span::before{content:"";width:6px;height:6px;border-radius:50%;background:var(--gold);box-shadow:0 0 8px var(--gold)}
.rv-wd-int__track span:hover{border-color:rgba(245,197,66,.5);color:var(--gold)}
@keyframes rvWdMarq{to{transform:translateX(-50%)}}
.rv-wd-int__grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}
.rv-wd-int__grid > li{padding:24px 22px 26px;border-radius:24px;border:1px solid rgba(255,255,255,.1);background:linear-gradient(160deg,rgba(255,255,255,.06),rgba(255,255,255,.015) 70%)}
.rv-wd-int__grid h3{display:flex;align-items:center;gap:12px;font-family:var(--h);font-weight:800;font-size:1.12rem;letter-spacing:-.015em}
.rv-wd-int__grid h3 span{display:grid;place-items:center;width:42px;height:42px;border-radius:13px;border:1px solid rgba(245,197,66,.35);background:linear-gradient(145deg,rgba(245,197,66,.2),rgba(245,197,66,.04))}
.rv-wd-int__grid h3 svg{width:19px;height:19px;fill:none;stroke:var(--gold);stroke-width:1.7;stroke-linecap:round;stroke-linejoin:round}
.rv-wd-int__grid ul{display:flex;flex-wrap:wrap;gap:6px;margin-top:16px}
.rv-wd-int__grid ul li{padding:6px 11px;border-radius:99px;background:rgba(255,255,255,.06);font-size:.82rem;color:rgba(255,255,255,.86)}

/* ── NICHES ──────────────────────────────────────────────────────── */
.rv-wd-ni__grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
.rv-wd-ni__card{position:relative;overflow:hidden;isolation:isolate;display:grid;grid-template-columns:52px minmax(0,1fr);gap:4px 16px;align-items:start;height:100%;
  padding:22px 22px 24px;border-radius:24px;border:1px solid rgba(255,255,255,.1);background:linear-gradient(160deg,rgba(255,255,255,.055),rgba(255,255,255,.012) 70%);
  transition:border-color .4s ease,transform .5s var(--ease),background .4s ease}
.rv-wd-ni__card::after{content:"";position:absolute;z-index:-1;right:-30%;top:-60%;width:70%;aspect-ratio:1;border-radius:50%;background:radial-gradient(closest-side,rgba(245,197,66,.16),transparent);
  opacity:0;transition:opacity .5s ease}
a.rv-wd-ni__card:hover{border-color:rgba(245,197,66,.45);transform:translateY(-4px)}
a.rv-wd-ni__card:hover::after{opacity:1}
.rv-wd-ni__ic{grid-row:span 2;display:grid;place-items:center;width:52px;height:52px;border-radius:16px;border:1px solid rgba(245,197,66,.35);background:linear-gradient(145deg,rgba(245,197,66,.2),rgba(245,197,66,.04))}
.rv-wd-ni__ic svg{width:22px;height:22px;fill:none;stroke:var(--gold);stroke-width:1.6;stroke-linecap:round;stroke-linejoin:round}
.rv-wd-ni__card h3{display:flex;align-items:center;justify-content:space-between;gap:10px;font-family:var(--h);font-weight:800;font-size:1.08rem;letter-spacing:-.015em}
.rv-wd-ni__card h3 svg{flex:none;width:15px;height:15px;fill:none;stroke:var(--gold);stroke-width:1.9;stroke-linecap:round;stroke-linejoin:round;transition:transform .35s var(--ease)}
a.rv-wd-ni__card:hover h3 svg{transform:translate(2px,-2px)}
.rv-wd-ni__card p{font-size:.88rem;line-height:1.5;color:rgba(255,255,255,.72)}

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

@media(max-width:1100px){
  .rv-wd-int__grid{grid-template-columns:repeat(2,minmax(0,1fr))}
  .rv-wd-ni__grid{grid-template-columns:1fr 1fr}
  .rv-wd-pain__row{grid-template-columns:40px minmax(0,1fr) 40px minmax(0,1.2fr)}
}
@media(max-width:700px){
  .rv-ec .rv-wd-toast--pay{display:none}
  .rv-wd-pain__row{grid-template-columns:1fr;gap:12px;padding:20px 18px}
  .rv-wd-pain__row::before{left:0;top:52%}
  .rv-wd-pain__n{position:absolute;top:20px;right:18px}
  .rv-wd-pain__arr{width:34px;height:34px;transform:rotate(90deg)}
  .rv-wd-pain__p h3{padding-right:28px}
  .rv-wd-int__track span{padding:11px 16px;font-size:.88rem}
  .rv-wd-int__grid > li{min-height:0;padding:20px 18px}
  .rv-wd-ni__grid{grid-template-columns:1fr;gap:10px}
  .rv-wd-ni__card{padding:18px;grid-template-columns:44px minmax(0,1fr)}
  .rv-wd-ni__ic{width:44px;height:44px;border-radius:13px}
  .rv-wd-seo__box{padding:22px 18px;border-radius:22px}
  .rv-wd-seo__body{max-height:280px}
  .rv-ec .rv-wd-pk h3{padding-right:110px}
}
/* ======================================================================
   КОМПАКТНІСТЬ: менші відступи між блоками (ПК) і «блок = один екран» (телефон)
   ====================================================================== */
.rv-ec .rv-wd__sec{padding-top:clamp(36px,3.6vw,60px);padding-bottom:clamp(36px,3.6vw,60px)}
.rv-ec .rv-wd__head{margin-bottom:clamp(22px,2.4vw,38px)}
.rv-ec .rv-wd-section-lead{margin:-8px 0 22px!important}
.rv-ec .rv-wd-int__marq{margin-bottom:clamp(20px,2.4vw,32px)}
@media(max-width:700px){
  .rv-ec .rv-wd__sec{padding-top:44px;padding-bottom:44px}
  .rv-ec .rv-wd__head{margin-bottom:14px}
  .rv-ec .rv-wd__h2{margin-top:12px!important;font-size:clamp(24px,7vw,30px)}
  /* другорядний текст на телефоні прибираємо */
  .rv-ec .rv-wd-section-lead,.rv-ec .rv-wd-section-note,
  .rv-ec .rv-wd-offer__lead,.rv-ec .rv-wd-offer__meta,
  .rv-ec .rv-wd-ca__note,.rv-ec .rv-wd-ca__tag,
  .rv-ec .rv-wd-int__grid,.rv-ec .rv-wd-int__grid ~ .rv-car-nav,
  .rv-ec .rv-wd-ni__card p,.rv-ec .rv-wd-pain__p small,.rv-ec .rv-wd-pain__s small,.rv-ec .rv-wd-pain__arr{display:none!important}
  .rv-ec .rv-wd-int .rv-car-nav{display:none}
  /* проблеми → рішення: компактні картки в каруселі */
  .rv-ec .rv-wd-pain__row{gap:8px;padding:18px;min-height:0}
  .rv-ec .rv-wd-pain__row::before{top:46%}
  .rv-ec .rv-wd-pain__s p{font-size:.88rem;line-height:1.5}
  /* оффер */
  .rv-ec .rv-wd-offer__box{padding:22px 16px;gap:14px}
  .rv-ec .rv-wd-offer h2{font-size:clamp(26px,7.6vw,32px)}
  .rv-ec .rv-wd-offer .rv-wd__btn{margin-top:14px;min-height:52px}
  .rv-ec .rv-wd-inc li{padding:9px;font-size:.74rem}
  /* пакети — карусель, бейджі не обрізаються */
  .rv-ec .rv-wd-pk__grid[data-rv-car]{padding-top:16px!important}
  .rv-ec .rv-wd-pk__d{display:none}
  /* калькулятор */
  .rv-ec .rv-wd-ca__form,.rv-ec .rv-wd-ca__sum{padding:16px 14px}
  .rv-ec .rv-wd-ca__opts:not(.is-all) .rv-wd-ca__opt:nth-child(n+4){display:none}
  .rv-ec .rv-wd-ca__more span{display:none}
  .rv-ec .rv-wd-ca__sum{flex-direction:row}
  .rv-ec .rv-wd-ca__sticky{display:grid;grid-template-columns:auto 1fr;align-items:center;gap:6px 14px;width:100%}
  .rv-ec .rv-wd-ca__sticky .rv-wd-ca__lbl{grid-column:1/-1}
  .rv-ec .rv-wd-ca__total{margin:0}
  .rv-ec .rv-wd-ca__total b{font-size:40px}
  .rv-ec .rv-wd-ca__sum .rv-wd__btn{grid-column:1/-1;margin-top:8px}
  .rv-ec .rv-wd-ca__rows{grid-column:1/-1;margin-top:10px;padding-top:10px;gap:6px}
  .rv-ec .rv-wd-ca__rows div{font-size:.86rem}
  /* інтеграції — лише стрічка */
  .rv-ec .rv-wd-int__marq{margin-bottom:0}
  /* ніші — дві колонки, лише назви */
  .rv-ec .rv-wd-ni__grid{grid-template-columns:1fr 1fr;gap:8px}
  .rv-ec .rv-wd-ni__grid > li:last-child:nth-child(odd){grid-column:1/-1}
  .rv-ec .rv-wd-ni__grid > li:last-child:nth-child(odd) .rv-wd-ni__card{flex-direction:row;align-items:center}
  .rv-ec .rv-wd-ni__card{display:flex;flex-direction:column;align-items:flex-start;gap:10px;padding:14px 12px;border-radius:18px}
  .rv-ec .rv-wd-ni__ic{width:36px;height:36px;border-radius:11px}
  .rv-ec .rv-wd-ni__ic svg{width:17px;height:17px}
  .rv-ec .rv-wd-ni__card h3{font-size:.86rem;line-height:1.25}
  /* FAQ, SEO */
  .rv-ec .rv-wd-fq summary{padding:13px 14px;font-size:.88rem}
  .rv-ec .rv-wd-seo__body{max-height:200px}
  .rv-ec .rv-wd-seo__box .rv-wd__chip{margin-bottom:14px!important}
}
@media(prefers-reduced-motion:reduce){
  .rv-wd-int__track{animation:none!important}
  .rv-wd-int__marq{overflow-x:auto}
  .rv-wd-pain__row::before{transform:scaleX(1)}
}
</style>


<!-- ── HERO ─────────────────────────────────────────────────────────── -->
<section class="rv-wd__sec rv-wd-hero" aria-labelledby="rv-wd-h1">
	<div class="rv-wd__grid-bg" aria-hidden="true"></div>
	<div class="rv-wd__in">
		<div class="rv-wd-hero__copy">
			<p class="rv-wd__chip"><?php echo esc_html( $T['chip'] ); ?></p>
			<h1 id="rv-wd-h1"><?php echo esc_html( $T['h1a'] ); ?> <span><?php echo esc_html( $T['h1b'] ); ?></span></h1>
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
				<div class="rv-wd-bw__bar"><i></i><i></i><i></i><span><?php echo esc_html( $T['vis_url'] ); ?></span></div>
				<div class="rv-wd-bw__screen">
					<div class="rv-wd-bw__nav"><strong>YOUR <em>STORE</em></strong><i></i><i></i><b><?php echo esc_html( $T['vis_cart'] ); ?></b></div>
					<div class="rv-wd-bw__hero">
						<p><?php echo esc_html( $T['vis_kicker'] ); ?></p>
						<h3><?php echo wp_kses( $T['vis_h'], array( 'em' => array(), 'br' => array() ) ); ?></h3>
						<span><?php echo esc_html( $T['vis_sub'] ); ?></span>
					</div>
					<div class="rv-wd-bw__cards"><i></i><i></i><i></i></div>
					<div class="rv-wd-cur"></div>
				</div>
			</div>
			<div class="rv-wd-ph">
				<div class="rv-wd-ph__sensor"></div>
				<div class="rv-wd-ph__brand">YOUR <em>STORE</em></div>
				<p><?php echo wp_kses( $T['vis_ph'], array( 'em' => array(), 'br' => array() ) ); ?></p>
				<div class="rv-wd-ph__orb"></div>
				<div class="rv-wd-ph__btn"><?php echo esc_html( $T['vis_ph_btn'] ); ?></div>
			</div>
			<div class="rv-wd-toast rv-wd-toast--speed">
				<span class="rv-wd-toast__ic"><svg viewBox="0 0 24 24"><path d="M13 2.5L4.5 13.5H11l-1 8 8.5-11H12z"/></svg></span>
				<span><small><?php echo esc_html( $T['toast2'] ); ?></small><b><?php echo esc_html( $T['toast2_value'] ); ?></b></span>
			</div>
			<div class="rv-wd-toast rv-wd-toast--pay">
				<span class="rv-wd-toast__ic"><svg viewBox="0 0 24 24"><rect x="3" y="5.5" width="18" height="13" rx="2.5"/><path d="M3 10h18M7 15h4"/></svg></span>
				<span><small><?php echo esc_html( $T['toast3'] ); ?></small><b><?php echo esc_html( $T['toast3_value'] ); ?></b></span>
			</div>
			<div class="rv-wd-toast rv-wd-toast--lead">
				<span class="rv-wd-toast__ic"><svg viewBox="0 0 24 24"><path d="M21 4L3 11l6 2.2L18 7l-7 7.5V20l3.3-3.6L18 19z"/></svg></span>
				<span><small><?php echo esc_html( $T['toast1_s'] ); ?></small><b><?php echo esc_html( $T['toast1'] ); ?></b></span>
			</div>
		</figure>
	</div>
</section>

<!-- ── PAIN → SOLUTION ──────────────────────────────────────────────── -->
<section class="rv-wd__sec rv-wd-pain">
	<div class="rv-wd__in">
		<div class="rv-wd__head" data-rv>
			<div><p class="rv-wd__chip"><?php echo esc_html( $T['pn_chip'] ); ?></p><h2 class="rv-wd__h2"><?php echo wp_kses( $T['pn_t'], array( 'em' => array() ) ); ?></h2></div>
		</div>
		<p class="rv-wd-section-lead" data-rv><?php echo esc_html( $T['pn_lead'] ); ?></p>
		<ol class="rv-wd-pain__list" data-rv-car>
			<?php foreach ( $T['pn'] as $rv_i => $rv_p ) : ?>
			<li class="rv-wd-pain__row" data-rv style="--d:<?php echo (int) $rv_i; ?>">
				<span class="rv-wd-pain__n"><?php echo esc_html( str_pad( $rv_i + 1, 2, '0', STR_PAD_LEFT ) ); ?></span>
				<div class="rv-wd-pain__p"><small><?php echo esc_html( $T['pn_l1'] ); ?></small><h3><?php echo $rv_wd_icons['x']; ?><?php echo esc_html( $rv_p[0] ); ?></h3></div>
				<span class="rv-wd-pain__arr" aria-hidden="true"><?php echo $rv_wd_icons['right']; ?></span>
				<div class="rv-wd-pain__s"><small><?php echo esc_html( $T['pn_l2'] ); ?></small><p><?php echo esc_html( $rv_p[1] ); ?></p></div>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<!-- ── OFFER: магазин під ключ ─────────────────────────────────────── -->
<section class="rv-wd__sec rv-wd-offer" id="rv-wd-offer">
	<div class="rv-wd__in">
		<div class="rv-wd-offer__box" data-rv>
			<div>
				<p class="rv-wd__chip"><?php echo esc_html( $T['of_chip'] ); ?></p>
				<h2><?php echo wp_kses( $T['of_t'], array( 'em' => array(), 'br' => array() ) ); ?></h2>
				<p class="rv-wd-offer__lead"><?php echo esc_html( $T['of_lead'] ); ?></p>
				<div class="rv-wd-offer__meta"><?php foreach ( $T['of_meta'] as $rv_m ) { echo '<span>' . esc_html( $rv_m ) . '</span>'; } ?></div>
				<p class="rv-wd-offer__alt"><b><?php echo esc_html( $T['of_alt_t'] ); ?></b> <?php echo esc_html( $T['of_alt'] ); ?></p>
				<a class="rv-wd__btn rv-wd__btn--gold" href="#rv-wd-request" data-rv-plan="shop"><?php echo esc_html( $T['of_cta'] ); ?> <?php echo $rv_wd_icons['arrow']; ?></a>
			</div>
			<ul class="rv-wd-inc">
				<?php foreach ( $T['of_inc'] as $rv_x ) : ?><li><?php echo $rv_wd_icons['check']; ?><?php echo esc_html( $rv_x ); ?></li><?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>

<!-- ── PACKAGES ─────────────────────────────────────────────────────── -->
<section class="rv-wd__sec rv-wd-pk-sec">
	<div class="rv-wd__in">
		<div class="rv-wd__head" data-rv>
			<div><p class="rv-wd__chip"><?php echo esc_html( $T['pk_chip'] ); ?></p><h2 class="rv-wd__h2"><?php echo wp_kses( $T['pk_t'], array( 'em' => array() ) ); ?></h2></div>
		</div>
		<div class="rv-wd-pk__grid" data-rv-car>
			<?php foreach ( $T['pk'] as $rv_i => $rv_p ) :
				$rv_pop = 'business' === $rv_p[0]; ?>
			<article class="rv-wd-pk<?php echo $rv_pop ? ' is-pop' : ''; ?>" data-rv style="--d:<?php echo (int) $rv_i; ?>">
				<?php if ( $rv_p[5] ) : ?><span class="rv-wd-pk__badge"><?php echo esc_html( $T['pk_badge'] ); ?></span><?php endif; ?>
				<?php if ( $rv_pop ) : ?><span class="rv-wd-pk__badge rv-wd-pk__badge--pop"><?php echo esc_html( $T['pk_pop'] ); ?></span><?php endif; ?>
				<h3><?php echo esc_html( $rv_p[1] ); ?></h3>
				<p class="rv-wd-pk__d"><?php echo esc_html( $rv_p[2] ); ?></p>
				<div class="rv-wd-pk__price"><small><?php echo esc_html( $T['pk_from'] ); ?></small><b><?php echo esc_html( rv_wd_money( $rv_wd_prices[ $rv_p[0] ] ) ); ?></b></div>
				<ul><?php foreach ( $rv_p[3] as $rv_f ) { echo '<li>' . $rv_wd_icons['check'] . esc_html( $rv_f ) . '</li>'; } ?></ul>
				<p class="rv-wd-pk__time"><?php echo esc_html( $rv_p[4] ); ?></p>
				<a class="rv-wd__btn rv-wd__btn--gold" href="#rv-wd-request" data-rv-plan="shop"><?php echo esc_html( $T['pk_cta'] ); ?> <?php echo $rv_wd_icons['arrow']; ?></a>
			</article>
			<?php endforeach; ?>
		</div>
		<p class="rv-wd-section-note" data-rv><?php echo esc_html( $T['pk_note'] ); ?></p>
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
					<?php foreach ( $T['ca_types'] as $rv_k => $rv_t ) : $rv_on = $T['ca_default'] === $rv_k; ?>
					<button type="button" class="rv-wd-ca__type<?php echo $rv_on ? ' is-on' : ''; ?>" role="radio" aria-checked="<?php echo $rv_on ? 'true' : 'false'; ?>"
					        data-type="shop" data-pkg="<?php echo esc_attr( $rv_k ); ?>" data-price="<?php echo (int) $rv_wd_prices[ $rv_k ]; ?>" data-label="<?php echo esc_attr( $rv_t[0] ); ?>">
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
					<div class="rv-wd-ca__total" aria-live="polite"><small>$</small><b data-calc-total><?php echo (int) $rv_wd_prices[ $T['ca_default'] ]; ?></b></div>
					<span class="rv-wd-ca__tag" data-calc-tag><?php echo esc_html( $T['ca_types'][ $T['ca_default'] ][0] ); ?></span>
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

<!-- ── INTEGRATIONS ─────────────────────────────────────────────────── -->
<section class="rv-wd__sec rv-wd-int">
	<div class="rv-wd__in">
		<div class="rv-wd__head" data-rv>
			<div><p class="rv-wd__chip"><?php echo esc_html( $T['in_chip'] ); ?></p><h2 class="rv-wd__h2"><?php echo wp_kses( $T['in_t'], array( 'em' => array() ) ); ?></h2></div>
		</div>
		<p class="rv-wd-section-lead" data-rv><?php echo esc_html( $T['in_lead'] ); ?></p>
		<?php
		$rv_in_all = array();
		foreach ( $T['in'] as $rv_g ) { $rv_in_all = array_merge( $rv_in_all, $rv_g[1] ); }
		$rv_in_half = (int) ceil( count( $rv_in_all ) / 2 );
		$rv_in_rows = array( array_slice( $rv_in_all, 0, $rv_in_half ), array_slice( $rv_in_all, $rv_in_half ) );
		?>
		<div class="rv-wd-int__marq" aria-hidden="true">
			<?php foreach ( $rv_in_rows as $rv_ri => $rv_row ) : ?>
			<div class="rv-wd-int__track<?php echo $rv_ri ? ' rv-wd-int__track--rev' : ''; ?>">
				<?php for ( $rv_rep = 0; $rv_rep < 2; $rv_rep++ ) { foreach ( $rv_row as $rv_name ) { echo '<span>' . esc_html( $rv_name ) . '</span>'; } } ?>
			</div>
			<?php endforeach; ?>
		</div>
		<ul class="rv-wd-int__grid" data-rv-car>
			<?php foreach ( $T['in'] as $rv_i => $rv_g ) : ?>
			<li data-rv style="--d:<?php echo (int) $rv_i; ?>">
				<h3><span aria-hidden="true"><?php echo $rv_wd_in_icons[ $rv_i ]; ?></span><?php echo esc_html( $rv_g[0] ); ?></h3>
				<ul><?php foreach ( $rv_g[1] as $rv_name ) { echo '<li>' . esc_html( $rv_name ) . '</li>'; } ?></ul>
			</li>
			<?php endforeach; ?>
		</ul>
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

<!-- ── NICHES ───────────────────────────────────────────────────────── -->
<section class="rv-wd__sec rv-wd-ni">
	<div class="rv-wd__in">
		<div class="rv-wd__head" data-rv>
			<div><p class="rv-wd__chip"><?php echo esc_html( $T['ni_chip'] ); ?></p><h2 class="rv-wd__h2"><?php echo wp_kses( $T['ni_t'], array( 'em' => array() ) ); ?></h2></div>
		</div>
		<p class="rv-wd-section-lead" data-rv><?php echo esc_html( $T['ni_lead'] ); ?></p>
		<ul class="rv-wd-ni__grid">
			<?php foreach ( $rv_wd_niches as $rv_i => $rv_n ) :
				$rv_n_url = $rv_wd_niche_url( $rv_n[0], $rv_n[6] );
				$rv_n_tag = $rv_n_url ? 'a' : 'div'; ?>
			<li data-rv style="--d:<?php echo (int) ( $rv_i % 3 ); ?>">
				<<?php echo $rv_n_tag; ?> class="rv-wd-ni__card"<?php echo $rv_n_url ? ' href="' . esc_url( $rv_n_url ) . '"' : ''; ?>>
					<span class="rv-wd-ni__ic" aria-hidden="true"><?php echo $rv_wd_niche_icons[ $rv_n[5] ]; ?></span>
					<h3><?php echo esc_html( $rv_en ? $rv_n[2] : $rv_n[1] ); ?><?php echo $rv_n_url ? $rv_wd_icons['arrow'] : ''; ?></h3>
					<p><?php echo esc_html( $rv_en ? $rv_n[4] : $rv_n[3] ); ?></p>
				</<?php echo $rv_n_tag; ?>>
			</li>
			<?php endforeach; ?>
		</ul>
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
/* ===== КОНТАКТЫ v2 — 1:1 з головної (проста форма CF7: ім’я + телефон) =====
   Слева — заголовок, каналы связи и офисы с местным временем,
   справа — форма в стеклянной карточке. Контакты заданы здесь
   (раньше приходили из ACF-поля infa_sekczii_11): правятся в массиве ниже. */
$rv_ct_en = function_exists( 'pll_current_language' ) && 'en' === pll_current_language();
$rv_ct = $rv_ct_en ? array(
	'chip'    => $T['fn_chip'],
	'title'   => $T['fn_t'],
	'lead'    => $T['fn_lead'],
	'card'    => $T['ct_card'],
	'online'  => $T['ct_online'],
	'cardsub' => $T['ct_sub'],
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
	'card'    => $T['ct_card'],
	'online'  => $T['ct_online'],
	'cardsub' => $T['ct_sub'],
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
				<div class="rv-ct__card" id="rv-wd-request" tabindex="-1">
					<div class="rv-ct__card-head">
						<p class="rv-ct__card-title"><?php echo esc_html( $rv_ct['card'] ); ?></p>
						<p class="rv-ct__online"><i></i><?php echo esc_html( $rv_ct['online'] ); ?></p>
					</div>
					<p class="rv-ct__card-sub"><?php echo esc_html( $rv_ct['cardsub'] ); ?></p>
					<div class="s11__form">
						<?php echo do_shortcode( $rv_ct['form'] ); ?>
					</div>
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

<?php
/* =====================================================================
   ПОПАП ЗАЯВКИ (власний, замість #win1 теми)
   Форма — Contact Form 7 з полями: телефон, ім’я, [hidden rv-source].
   Вкажіть ID форм нижче (можна одну на обидві мови — плейсхолдери й
   кнопку скрипт перекладе сам).
   ===================================================================== */
/* ID форм попапу: вставте сюди ID нової форми CF7 (UA і EN). Поки порожньо — береться форма «impl». */
$rv_pm_form_id = $rv_en ? '2053358' : '2053358';
$rv_pm_form = '[contact-form-7 id="' . ( $rv_pm_form_id ? $rv_pm_form_id : '838cecb' ) . '"]';
$rv_pm = $rv_en ? array(
	'chip'    => 'Budget & timeline',
	'title'   => 'Get your <em>store estimate</em>',
	'sub'     => 'Leave your name and phone — we’ll call back, clarify the task and give you a budget and timeline.',
	'picked'  => 'You selected',
	'name'    => 'Your name',
	'phone'   => 'Phone / Telegram',
	'msg'     => 'Comment: what you sell, number of products (optional)',
	'send'    => 'Get my estimate',
	'pp'      => 'By sending the form you agree to our <a href="%s">privacy policy</a>.',
	'pp_url'  => home_url( '/privacy-policy/' ),
	'trust'   => array( 'Reply within one business day', 'Fixed price in the contract', '70/30 payment' ),
	'ok_t'    => 'Request sent!',
	'ok_p'    => 'We’ll call you back soon. Meanwhile, take a look at our e-commerce projects.',
	'ok_b'    => 'View cases',
	'close'   => 'Close',
) : array(
	'chip'    => 'Бюджет і строки',
	'title'   => 'Отримайте <em>оцінку магазину</em>',
	'sub'     => 'Залиште ім’я та телефон — передзвонимо, уточнимо задачу й назвемо бюджет і строки запуску.',
	'picked'  => 'Ви обрали',
	'name'    => 'Ваше ім’я',
	'phone'   => 'Телефон / Telegram',
	'msg'     => 'Коментар: що продаєте, скільки товарів (необов’язково)',
	'send'    => 'Отримати оцінку',
	'pp'      => 'Надсилаючи форму, ви погоджуєтеся з <a href="%s">політикою конфіденційності</a>.',
	'pp_url'  => home_url( '/uk/privacy-policy/' ),
	'trust'   => array( 'Відповідь протягом робочого дня', 'Фіксована ціна в договорі', 'Оплата 70/30' ),
	'ok_t'    => 'Заявку надіслано!',
	'ok_p'    => 'Скоро передзвонимо. А поки — подивіться наші кейси інтернет-магазинів.',
	'ok_b'    => 'Переглянути кейси',
	'close'   => 'Закрити',
);
?>
<div class="rv-pm" id="rv-pm" role="dialog" aria-modal="true" aria-labelledby="rv-pm-title" aria-hidden="true" hidden>
	<div class="rv-pm__back" data-pm-close></div>
	<div class="rv-pm__box" tabindex="-1">
		<span class="rv-pm__handle" aria-hidden="true"></span>
		<button type="button" class="rv-pm__x" data-pm-close aria-label="<?php echo esc_attr( $rv_pm['close'] ); ?>"><svg viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg></button>


		<div class="rv-pm__main">
			<div class="rv-pm__form-wrap" data-pm-form>
				<p class="rv-pm__chip"><i></i><?php echo esc_html( $rv_pm['chip'] ); ?></p>
				<h2 class="rv-pm__title" id="rv-pm-title"><?php echo wp_kses( $rv_pm['title'], array( 'em' => array() ) ); ?></h2>
				<p class="rv-pm__sub"><?php echo esc_html( $rv_pm['sub'] ); ?></p>
				<p class="rv-pm__picked" data-pm-picked hidden><small><?php echo esc_html( $rv_pm['picked'] ); ?></small><b></b></p>


				<div class="rv-pm__cf7"><?php echo do_shortcode( $rv_pm_form ); ?></div>
				<p class="rv-pm__pp"><?php echo wp_kses( sprintf( $rv_pm['pp'], esc_url( $rv_pm['pp_url'] ) ), array( 'a' => array( 'href' => array() ) ) ); ?></p>
				<ul class="rv-pm__trust"><?php foreach ( $rv_pm['trust'] as $rv_x ) { echo '<li><svg viewBox="0 0 24 24"><path d="M4.5 12.5l5 5L19.5 7"/></svg>' . esc_html( $rv_x ) . '</li>'; } ?></ul>
			</div>

			<div class="rv-pm__ok" data-pm-ok hidden>
				<div class="rv-pm__ok-ic"><svg viewBox="0 0 52 52"><circle cx="26" cy="26" r="24"/><path d="M15 27l7 7 15-16"/></svg></div>
				<h2><?php echo esc_html( $rv_pm['ok_t'] ); ?></h2>
				<p><?php echo esc_html( $rv_pm['ok_p'] ); ?></p>
				<a class="rv-pm__ok-btn" href="#rv-wd-cases" data-pm-close><?php echo esc_html( $rv_pm['ok_b'] ); ?> ↗</a>
				<div class="rv-pm__confetti" aria-hidden="true"><?php for ( $rv_k = 0; $rv_k < 18; $rv_k++ ) echo '<i style="--k:' . $rv_k . '"></i>'; ?></div>
			</div>
		</div>
	</div>
</div>

<style id="rv-pm-css">
.rv-pm{--gold:#F5C542;--ink:#030032;--ok:#5BD98A;--ease:cubic-bezier(.16,1,.3,1);--h:'Manrope','Montserrat',Arial,sans-serif;--mono:'JetBrains Mono',monospace;
  position:fixed;inset:0;z-index:100000;display:grid;place-items:center;padding:20px;font-family:'Inter',Arial,sans-serif;color:#fff}
.rv-pm[hidden]{display:none}
.rv-pm *,.rv-pm *::before,.rv-pm *::after{box-sizing:border-box}
.rv-pm :where(h2,p,ol,ul){margin:0;padding:0}
.rv-pm ol,.rv-pm li{list-style:none}
.rv-pm li::before{content:none}
.rv-pm em,.rv-pm i{font-style:normal}
.rv-pm button{font:inherit;color:inherit;border:0;background:none;cursor:pointer}
.rv-pm svg{display:block}
.rv-pm__back{position:absolute;inset:0;background:rgba(2,1,30,.72);-webkit-backdrop-filter:blur(10px) saturate(1.2);backdrop-filter:blur(10px) saturate(1.2);opacity:0;transition:opacity .45s ease}
.rv-pm.is-open .rv-pm__back{opacity:1}
.rv-pm__box{position:relative;display:grid;grid-template-columns:minmax(0,1fr);width:min(640px,100%);max-height:calc(100dvh - 40px);overflow:hidden;
  border-radius:32px;border:1px solid rgba(255,255,255,.14);background:linear-gradient(160deg,#10124f,#06062e 70%);
  box-shadow:0 60px 140px rgba(0,0,20,.7),0 0 0 1px rgba(245,197,66,.08),inset 0 1px 0 rgba(255,255,255,.1);
  opacity:0;transform:translateY(30px) scale(.96);transition:opacity .5s var(--ease),transform .7s var(--ease)}
.rv-pm.is-open .rv-pm__box{opacity:1;transform:none}
.rv-pm__box::before{content:"";position:absolute;left:12%;right:12%;top:0;height:1px;z-index:3;background:linear-gradient(90deg,transparent,var(--gold),transparent)}
.rv-pm__box:focus{outline:none}
.rv-pm__handle{display:none}
.rv-pm__x{position:absolute;z-index:5;top:16px;right:16px;display:grid;place-items:center;width:42px;height:42px;border-radius:50%;border:1px solid rgba(255,255,255,.18);background:rgba(255,255,255,.04);
  transition:transform .4s var(--ease),background .3s ease,border-color .3s ease}
.rv-pm__x svg{width:16px;height:16px;fill:none;stroke:#fff;stroke-width:2;stroke-linecap:round}
.rv-pm__x:hover{transform:rotate(90deg);background:var(--gold);border-color:var(--gold)}
.rv-pm__x:hover svg{stroke:var(--ink)}

/* вміст */
.rv-pm__box::after{content:"";position:absolute;z-index:0;right:-25%;top:-35%;width:70%;aspect-ratio:1;pointer-events:none;background:radial-gradient(closest-side,rgba(245,197,66,.16),transparent)}
.rv-pm__chip{display:inline-flex;align-items:center;gap:8px;margin-bottom:16px!important;padding:7px 14px;border-radius:99px;border:1px solid rgba(91,217,138,.35);background:rgba(91,217,138,.08);
  font-family:var(--mono);font-size:.62rem;letter-spacing:.12em;text-transform:uppercase;color:var(--ok)}
.rv-pm__chip i{width:6px;height:6px;border-radius:50%;background:var(--ok);box-shadow:0 0 8px var(--ok);animation:rvPmLive 1.8s ease-in-out infinite}
@keyframes rvPmLive{50%{opacity:.3}}

.rv-pm__main{position:relative;z-index:1;overflow-y:auto;padding:38px 38px 30px;-webkit-overflow-scrolling:touch;overscroll-behavior:contain}
.rv-pm__title{padding-right:44px;font-family:var(--h);font-weight:800;font-size:clamp(28px,3vw,40px);line-height:1.02;letter-spacing:-.035em;text-transform:uppercase}
.rv-pm__title em{display:block;color:transparent;background:linear-gradient(100deg,#F5D488,#F5C542 40%,#fff3c4 50%,#F5C542 60%,#DFAE3F);background-size:220% 100%;-webkit-background-clip:text;background-clip:text;animation:rvPmShine 5s ease-in-out 1s infinite}
@keyframes rvPmShine{0%,55%{background-position:100% 0}85%,100%{background-position:0 0}}
.rv-pm__sub{margin-top:12px!important;font-size:.95rem;line-height:1.55;color:rgba(255,255,255,.78)}
.rv-pm__picked{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-top:16px!important;padding:11px 14px;border-radius:14px;border:1px dashed rgba(245,197,66,.45);background:rgba(245,197,66,.07)}
.rv-pm__picked[hidden]{display:none}
.rv-pm__picked small{font-family:var(--mono);font-size:.58rem;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.65)}
.rv-pm__picked b{font-family:var(--h);font-weight:700;font-size:.92rem;color:var(--gold)}

/* поля CF7 */
.rv-pm__cf7{margin-top:20px}
.rv-pm__cf7 form{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.rv-pm__cf7 form > *{grid-column:1/-1;min-width:0}
.rv-pm__cf7 form > .rv-pm-half{grid-column:auto}
.rv-pm__cf7 form > p,.rv-pm__cf7 form > div:not(.wpcf7-response-output){margin:0!important}
.rv-pm__cf7 br{display:none}
.rv-pm__cf7 .wpcf7-form-control-wrap{position:relative;display:block;width:100%}
.rv-pm__cf7 input[type="text"],.rv-pm__cf7 input[type="tel"],.rv-pm__cf7 input[type="email"]{
  display:block;width:100%!important;height:60px;margin:0!important;padding:0 20px 0 52px!important;border-radius:18px!important;border:1px solid rgba(255,255,255,.16)!important;
  background:rgba(3,0,50,.6) no-repeat 20px 50%/18px!important;color:#fff!important;font:500 1rem 'Inter',Arial,sans-serif!important;box-shadow:none!important;
  -webkit-appearance:none;appearance:none;transition:border-color .3s ease,box-shadow .3s ease,background-color .3s ease}
.rv-pm__cf7 input[type="tel"]{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23F5C542' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2'/%3E%3C/svg%3E")!important}
.rv-pm__cf7 input[type="text"]{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23F5C542' stroke-width='1.8' stroke-linecap='round'%3E%3Ccircle cx='12' cy='8' r='4'/%3E%3Cpath d='M4 21c1.5-4 4.5-6 8-6s6.5 2 8 6'/%3E%3C/svg%3E")!important}
.rv-pm__cf7 textarea{display:block;width:100%!important;height:110px;min-height:90px;margin:0!important;padding:16px 20px!important;border-radius:18px!important;border:1px solid rgba(255,255,255,.16)!important;
  background:rgba(3,0,50,.6)!important;color:#fff!important;font:500 .98rem/1.5 'Inter',Arial,sans-serif!important;box-shadow:none!important;resize:vertical;transition:border-color .3s ease,box-shadow .3s ease}
.rv-pm__cf7 textarea:focus{outline:none!important;border-color:rgba(245,197,66,.7)!important;box-shadow:0 0 0 4px rgba(245,197,66,.14)!important}
.rv-pm__cf7 textarea::placeholder{color:rgba(255,255,255,.5)!important;opacity:1}
.rv-pm__cf7 input::placeholder{color:rgba(255,255,255,.5)!important;opacity:1}
.rv-pm__cf7 input:focus{outline:none!important;border-color:rgba(245,197,66,.7)!important;box-shadow:0 0 0 4px rgba(245,197,66,.14)!important;background-color:rgba(3,0,50,.85)!important}
.rv-pm__cf7 .wpcf7-not-valid{border-color:rgba(255,120,120,.7)!important}
.rv-pm__cf7 .wpcf7-not-valid-tip{margin-top:6px;padding-left:4px;font-size:.78rem;color:#FFB4B4}
.rv-pm__cf7 input[type="submit"],.rv-pm__cf7 button[type="submit"]{position:relative;display:block;width:100%!important;max-width:none!important;height:62px;margin:4px 0 0!important;
  border:0!important;border-radius:999px!important;cursor:pointer;color:var(--ink)!important;-webkit-text-fill-color:var(--ink)!important;
  font:800 .9rem 'Inter',Arial,sans-serif!important;letter-spacing:.08em!important;text-transform:uppercase;
  background:linear-gradient(135deg,#F5D488 0%,#F5C542 48%,#DFAE3F 100%)!important;box-shadow:0 14px 36px rgba(245,197,66,.35)!important;
  background-size:200% 100%!important;transition:transform .3s var(--ease),box-shadow .3s ease,background-position .6s ease}
.rv-pm__cf7 input[type="submit"]:hover{transform:translateY(-2px);box-shadow:0 20px 46px rgba(245,197,66,.48)!important;background-position:100% 0!important}
.rv-pm__cf7 input[type="submit"]:disabled{opacity:.6;cursor:wait}
.rv-pm__cf7 .wpcf7-spinner{position:absolute;right:20px;margin-top:-42px}
.rv-pm__cf7 .wpcf7-response-output{margin:4px 0 0!important;padding:10px 14px!important;border-radius:12px!important;border:1px solid rgba(255,180,180,.4)!important;font-size:.84rem;color:#FFD2D2}
.rv-pm__cf7 form.sent .wpcf7-response-output{display:none}
.rv-pm__pp{margin-top:12px!important;font-size:.8rem;line-height:1.5;color:rgba(255,255,255,.7)}
.rv-pm__pp a{color:var(--gold)!important;text-decoration:none!important;border-bottom:1px solid rgba(245,197,66,.5)}
.rv-pm__trust{display:flex;flex-wrap:wrap;gap:8px 18px;margin-top:18px!important;padding-top:16px!important;border-top:1px solid rgba(255,255,255,.1)}
.rv-pm__trust li{display:flex;align-items:center;gap:7px;font-size:.84rem;color:rgba(255,255,255,.82)}
.rv-pm__trust svg{width:15px;height:15px;fill:none;stroke:var(--ok);stroke-width:2.6;stroke-linecap:round;stroke-linejoin:round}

/* поява елементів форми */
.rv-pm__form-wrap > *{opacity:0;transform:translateY(14px);transition:opacity .6s ease,transform .8s var(--ease)}
.rv-pm.is-open .rv-pm__form-wrap > *{opacity:1;transform:none}
.rv-pm.is-open .rv-pm__form-wrap > :nth-child(2){transition-delay:.06s}
.rv-pm.is-open .rv-pm__form-wrap > :nth-child(3){transition-delay:.1s}
.rv-pm.is-open .rv-pm__form-wrap > :nth-child(4){transition-delay:.14s}
.rv-pm.is-open .rv-pm__form-wrap > :nth-child(5){transition-delay:.2s}
.rv-pm.is-open .rv-pm__form-wrap > :nth-child(n+6){transition-delay:.26s}

/* успіх */
.rv-pm__ok{position:relative;display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100%;padding:30px 10px;text-align:center}
.rv-pm__ok[hidden]{display:none}
.rv-pm__ok-ic svg{width:88px;height:88px;fill:none;stroke-linecap:round;stroke-linejoin:round}
.rv-pm__ok-ic circle{stroke:var(--ok);stroke-width:2;stroke-dasharray:151;stroke-dashoffset:151;animation:rvPmDraw .8s var(--ease) forwards}
.rv-pm__ok-ic path{stroke:var(--ok);stroke-width:3;stroke-dasharray:40;stroke-dashoffset:40;animation:rvPmDraw .5s var(--ease) .55s forwards}
@keyframes rvPmDraw{to{stroke-dashoffset:0}}
.rv-pm__ok h2{margin-top:22px!important;font-family:var(--h);font-weight:800;font-size:2rem;letter-spacing:-.03em;text-transform:uppercase}
.rv-pm__ok p{margin-top:10px!important;max-width:34ch;font-size:.98rem;line-height:1.55;color:rgba(255,255,255,.8)}
.rv-pm__ok-btn{margin-top:24px;display:inline-flex;align-items:center;height:52px;padding:0 26px;border-radius:99px;border:1px solid rgba(255,255,255,.22);color:#fff!important;text-decoration:none!important;font-weight:700;font-size:.82rem;letter-spacing:.06em;text-transform:uppercase;transition:border-color .3s ease,color .3s ease}
.rv-pm__ok-btn:hover{border-color:var(--gold);color:var(--gold)!important}
.rv-pm__confetti{position:absolute;inset:0;pointer-events:none;overflow:hidden}
.rv-pm__confetti i{position:absolute;left:50%;top:32%;width:8px;height:12px;border-radius:2px;background:var(--gold);opacity:0;
  animation:rvPmConf 1.6s cubic-bezier(.2,.8,.3,1) calc(var(--k) * 25ms) forwards}
.rv-pm__confetti i:nth-child(3n){background:#fff;width:6px;height:6px;border-radius:50%}
.rv-pm__confetti i:nth-child(4n){background:var(--ok)}
@keyframes rvPmConf{0%{opacity:1;transform:translate(0,0) rotate(0)}100%{opacity:0;transform:translate(calc((var(--k) - 9) * 26px),calc(140px + (var(--k) * 7px % 60px))) rotate(calc(var(--k) * 70deg))}}

/* планшет / телефон: шторка знизу */
@media(max-width:820px){
  .rv-pm{place-items:end center;padding:0}
  .rv-pm__box{grid-template-columns:1fr;width:100%;max-height:92dvh;border-radius:26px 26px 0 0;border-bottom:0;transform:translateY(100%);transition:opacity .3s ease,transform .6s var(--ease)}
  .rv-pm.is-open .rv-pm__box{transform:translateY(var(--drag,0px))}
  .rv-pm__handle{display:block;position:absolute;z-index:6;left:50%;top:9px;width:44px;height:5px;margin-left:-22px;border-radius:9px;background:rgba(255,255,255,.28)}
  .rv-pm__main{padding:30px 18px calc(22px + env(safe-area-inset-bottom))}
  .rv-pm__x{top:14px;right:14px;width:38px;height:38px}
  .rv-pm__title{font-size:clamp(26px,7.4vw,32px)}
  .rv-pm__chip{margin-bottom:12px!important}
  .rv-pm__sub{font-size:.88rem}
  .rv-pm__cf7{margin-top:16px}
  .rv-pm__cf7 input[type="text"],.rv-pm__cf7 input[type="tel"]{height:56px}
  .rv-pm__cf7 form{grid-template-columns:1fr}
  .rv-pm__cf7 form > .rv-pm-half{grid-column:1/-1}
  .rv-pm__cf7 textarea{height:84px}
  .rv-pm__trust{gap:6px 14px}
  .rv-pm__trust li{font-size:.76rem}
}
@media(max-width:820px) and (max-height:700px){ .rv-pm__sub,.rv-pm__trust{display:none} }
@media(prefers-reduced-motion:reduce){ .rv-pm *,.rv-pm *::before,.rv-pm *::after{animation:none!important;transition:none!important} }
html.rv-pm-lock,html.rv-pm-lock body{overflow:hidden!important}
</style>

<script id="rv-pm-js">
(function(){
  'use strict';
  var pm = document.getElementById('rv-pm');
  if (!pm) return;
  document.body.appendChild(pm); /* поза main: без обрізання й чужих z-index */
  var EN = <?php echo $rv_en ? 'true' : 'false'; ?>;
  var L = <?php echo wp_json_encode( array( 'name' => $rv_pm['name'], 'phone' => $rv_pm['phone'], 'msg' => $rv_pm['msg'], 'send' => $rv_pm['send'] ), JSON_UNESCAPED_UNICODE ); ?>;
  var box = pm.querySelector('.rv-pm__box'), formWrap = pm.querySelector('[data-pm-form]'), ok = pm.querySelector('[data-pm-ok]');
  var picked = pm.querySelector('[data-pm-picked]'), cf = pm.querySelector('.rv-pm__cf7');
  var lastFocus = null, detail = '', closeT = 0;

  /* плейсхолдери й кнопка — мовою сторінки */
  cf.querySelectorAll('input[type="tel"], input[name*="tel"], input[name*="phone"]').forEach(function(i){ i.placeholder = L.phone; i.setAttribute('autocomplete', 'tel'); i.setAttribute('inputmode', 'tel'); });
  cf.querySelectorAll('input[type="text"]').forEach(function(i){ if (!/tel|phone/.test(i.name)) { i.placeholder = L.name; i.setAttribute('autocomplete', 'name'); } });
  cf.querySelectorAll('textarea').forEach(function(t){ t.placeholder = L.msg; });
  cf.querySelectorAll('input[type="submit"]').forEach(function(b){ b.value = L.send; });
  /* ім’я першим, телефон другим */
  (function(){
    var f = cf.querySelector('form'); if (!f) return;
    var n = f.querySelector('input[type="text"]:not([name*="tel"]):not([name*="phone"])'), t = f.querySelector('input[type="tel"], input[name*="tel"], input[name*="phone"]');
    if (!n || !t) return;
    var nb = n.closest('p') || n.closest('.wpcf7-form-control-wrap'), tb = t.closest('p') || t.closest('.wpcf7-form-control-wrap');
    if (nb && tb && nb.parentNode === tb.parentNode && (tb.compareDocumentPosition(nb) & Node.DOCUMENT_POSITION_FOLLOWING)) tb.parentNode.insertBefore(nb, tb);
    /* ім’я і телефон — в один рядок (ПК) */
    [nb, tb].forEach(function(w){ while (w && w.parentNode !== f) w = w.parentNode; if (w) w.classList.add('rv-pm-half'); });
  })();

  function sync(){
    var d = (EN ? 'popup' : 'попап') + (detail ? ' · ' + detail : '');
    if (window.RVEcSetSource) window.RVEcSetSource(d);
  }
  function open(d, pickedText){
    clearTimeout(closeT);
    detail = d || '';
    if (pickedText) { picked.hidden = false; picked.querySelector('b').textContent = pickedText; } else { picked.hidden = true; }
    formWrap.hidden = false; ok.hidden = true;
    lastFocus = document.activeElement;
    pm.hidden = false; pm.setAttribute('aria-hidden', 'false');
    document.documentElement.classList.add('rv-pm-lock');
    sync();
    requestAnimationFrame(function(){ requestAnimationFrame(function(){ pm.classList.add('is-open'); }); });
    setTimeout(function(){ var f = cf.querySelector('input[type="text"], input[type="tel"]'); if (f && window.matchMedia('(hover: hover)').matches) f.focus(); else box.focus(); }, 380);
  }
  function close(){
    pm.classList.remove('is-open');
    pm.setAttribute('aria-hidden', 'true');
    box.style.removeProperty('--drag');
    closeT = setTimeout(function(){ pm.hidden = true; document.documentElement.classList.remove('rv-pm-lock'); }, 450);
    if (lastFocus && lastFocus.focus) lastFocus.focus();
  }
  window.RVEcModal = { open: open, close: close };

  pm.querySelectorAll('[data-pm-close]').forEach(function(el){
    el.addEventListener('click', function(e){
      if (el.tagName === 'A') { close(); return; }
      e.preventDefault(); close();
    });
  });
  document.addEventListener('keydown', function(e){
    if (pm.hidden) return;
    if (e.key === 'Escape') close();
    if (e.key === 'Tab') { /* фокус лишається в попапі */
      var f = [].slice.call(box.querySelectorAll('a[href], button:not([disabled]), input:not([type="hidden"]):not([disabled])')).filter(function(x){ return x.offsetParent; });
      if (!f.length) return;
      if (e.shiftKey && document.activeElement === f[0]) { e.preventDefault(); f[f.length - 1].focus(); }
      else if (!e.shiftKey && document.activeElement === f[f.length - 1]) { e.preventDefault(); f[0].focus(); }
    }
  });

  /* телефон: шторку можна стягнути вниз */
  var sy = 0, dy = 0, drag = false;
  pm.querySelector('.rv-pm__handle').parentNode.addEventListener('touchstart', function(e){
    var main = pm.querySelector('.rv-pm__main');
    if (window.innerWidth > 820 || (main.scrollTop > 0 && !e.target.closest('.rv-pm__handle'))) return;
    drag = true; sy = e.touches[0].clientY; dy = 0;
  }, { passive: true });
  box.addEventListener('touchmove', function(e){
    if (!drag) return;
    dy = Math.max(0, e.touches[0].clientY - sy);
    box.style.transition = 'none'; box.style.setProperty('--drag', dy + 'px');
  }, { passive: true });
  box.addEventListener('touchend', function(){
    if (!drag) return; drag = false; box.style.transition = '';
    if (dy > 110) close(); else box.style.setProperty('--drag', '0px');
  });

  /* успішна відправка — екран подяки */
  document.addEventListener('wpcf7mailsent', function(e){
    if (!pm.contains(e.target)) return;
    formWrap.hidden = true; ok.hidden = false;
    var c = ok.querySelector('.rv-pm__confetti'); c.innerHTML = c.innerHTML; /* перезапуск анімації */
  }, false);

  /* посилання теми на #win1 на цій сторінці відкривають цей попап */
  document.addEventListener('click', function(e){
    var l = e.target.closest && e.target.closest('a[href$="#win1"]');
    if (!l || e.metaKey || e.ctrlKey) return;
    e.preventDefault(); e.stopPropagation();
    open((EN ? 'button «' : 'кнопка «') + (l.textContent || '').replace(/\s+/g, ' ').trim() + '»');
  }, true);
  if (location.hash === '#win1') { history.replaceState(null, '', location.pathname + location.search); open(''); }
})();
</script>

<div class="rv-wd-sticky" id="rv-wd-sticky">
	<span><?php echo wp_kses( preg_replace( '/(\$\d+)/', '<b>$1</b>', esc_html( $T['sticky'] ) ), array( 'b' => array() ) ); ?></span>
	<a href="#rv-wd-request"><?php echo esc_html( $T['sticky_b'] ); ?></a>
</div>

<?php
/* Розмітка для Google: послуга з цінами пакетів + FAQ */
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
			'serviceType' => $T['service_name'],
			'offers'      => array(
				array( '@type' => 'Offer', 'name' => $T['pk'][0][1], 'price' => $rv_wd_prices['start'], 'priceCurrency' => 'USD' ),
				array( '@type' => 'Offer', 'name' => $T['pk'][1][1], 'price' => $rv_wd_prices['business'], 'priceCurrency' => 'USD' ),
				array( '@type' => 'Offer', 'name' => $T['pk'][2][1], 'price' => $rv_wd_prices['pro'], 'priceCurrency' => 'USD' ),
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
      elPre.textContent = money(total * .7); elPost.textContent = money(total - Math.round(total * .7));
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
  /* ДЖЕРЕЛО ЗАЯВКИ: у кожну форму CF7 на сторінці (футер і попап) додаємо
     приховане поле rv-source — сторінка, кнопка, пакет чи розрахунок калькулятора.
     У лист воно потрапляє через фільтр у functions.php (див. коментар у кінці файлу). */
  var SRC_BASE = <?php echo wp_json_encode( $T['service_name'] . ( $rv_en ? ' (EN)' : ' (UA)' ), JSON_UNESCAPED_UNICODE ); ?>;
  function setSource(detail){
    var v = SRC_BASE + (detail ? ' · ' + detail : '') + ' · ' + location.href.split('#')[0];
    document.querySelectorAll('form.wpcf7-form, form').forEach(function(f){
      if (!f.querySelector('input[type="tel"], input[name*="tel"], input[name*="phone"]')) return;
      var h = f.querySelector('input[name="rv-source"]');
      if (!h) { h = document.createElement('input'); h.type = 'hidden'; h.name = 'rv-source'; f.appendChild(h); }
      h.value = v; h.defaultValue = v;
    });
  }
  var SRC_DEF = EN ? 'form on the page' : 'форма на сторінці';
  setSource(SRC_DEF);
  /* попап виводиться у футері теми — після цього скрипта, тож дописуємо поле ще раз */
  document.addEventListener('DOMContentLoaded', function(){ setSource(SRC_DEF); });
  window.RVEcSetSource = setSource;
  /* кнопки відкривають власний попап сторінки (#rv-pm); запасний варіант — попап теми #win1 */
  function openPopup(detail, picked){
    if (window.RVEcModal) { window.RVEcModal.open(detail, picked); return true; }
    if (!document.getElementById('win1')) return false;
    if (location.hash === '#win1') { history.replaceState(null, '', location.pathname + location.search); }
    location.hash = 'win1';
    return true;
  }

  root.querySelectorAll('a[href="#rv-wd-request"]').forEach(function(a){
    a.addEventListener('click', function(e){
      if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
      e.preventDefault();
      var destination = document.getElementById('rv-wd-request');
      var msg = root.querySelectorAll('#rv-wd-request textarea, #rv-wd-request input[name*="message"], #rv-wd-request input[name*="comment"]');
      var pk = a.closest('.rv-wd-pk');
      var t = a.hasAttribute('data-calc-cta') ? note() : (a.dataset.rvPlan ? (pk ? (EN ? 'Package: ' : 'Пакет: ') + pk.querySelector('h3').textContent + ' — ' + (EN ? 'from ' : 'від ') + pk.querySelector('.rv-wd-pk__price b').textContent : <?php echo wp_json_encode( $T['lead_task'], JSON_UNESCAPED_UNICODE ); ?>) : '');

      var btn = (a.textContent || '').replace(/\s+/g, ' ').trim();
      var det = (EN ? 'button «' : 'кнопка «') + btn + '»' + (t ? ' · ' + t : '');
      setSource(det);
      if (openPopup(det, t)) return;

      /* Попап с формой оценки (inc/rv-lead.php). Тариф → тип проекта,
         калькулятор → тип, бюджет и расчёт в поле «задача». */
      if (window.RVLead){
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

      if (!destination) return;
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
  /* FAQ на телефоні: усі питання згорнуті, щоб блок вміщався в екран */
  if (window.matchMedia('(max-width: 700px)').matches) root.querySelectorAll('.rv-wd-fq details[open]').forEach(function(d){ d.removeAttribute('open'); });

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
/*
 * ДЖЕРЕЛО ЗАЯВКИ В ЛИСТІ. Додайте один раз у functions.php теми:
 *
 * add_filter( 'wpcf7_mail_components', function ( $c ) {
 *     $src = isset( $_POST['rv-source'] ) ? sanitize_text_field( wp_unslash( $_POST['rv-source'] ) ) : '';
 *     if ( '' === $src ) { $ref = wp_get_raw_referer(); $src = $ref ? esc_url_raw( $ref ) : ''; }
 *     if ( '' !== $src ) $c['body'] .= "\n\nДжерело заявки: " . $src;
 *     return $c;
 * } );
 */
get_footer();
