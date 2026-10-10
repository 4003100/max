# -*- coding: utf-8 -*-
"""Збирає шаблони сторінок послуг із page-landing-development.php + services_data.py.
Запуск: python3 tools/build_services.py  (з кореня репозиторію)"""
import os, sys, re
sys.path.insert(0, os.path.dirname(__file__))
from services_data import COMMON, SERVICES

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
BASE = open(os.path.join(ROOT, 'page-landing-development.php'), encoding='utf-8').read()

def php(v, ind=1):
    if isinstance(v, bool): return 'true' if v else 'false'
    if isinstance(v, (int, float)): return str(v)
    if isinstance(v, str): return "'" + v.replace('\\', '\\\\').replace("'", "\\'") + "'"
    if isinstance(v, (list, tuple)): return 'array( ' + ', '.join(php(x, ind) for x in v) + ' )'
    if isinstance(v, dict): return 'array( ' + ', '.join(php(k) + ' => ' + php(x, ind) for k, x in v.items()) + ' )'
    raise TypeError(type(v))

def T_block(d):
    return 'array(\n' + ''.join('\t' + php(k) + ' => ' + php(v) + ',\n' for k, v in d.items()) + ')'

def head(sv):
    uk = dict(COMMON['uk']); uk.update(sv['uk'])
    en = dict(COMMON['en']); en.update(sv['en'])
    for lang, d in (('uk', uk), ('en', en)):
        d['unit'] = sv.get('unit', {}).get(lang, '')
        d['pp_url'] = '/privacy-policy/' if lang == 'en' else '/uk/privacy-policy/'
        d['cases_url'] = '/#rv-cine' if lang == 'en' else '/uk/#rv-cine'
        d['form'] = '[contact-form-7 id="10" title="cf7#1"]' if lang == 'en' else '[contact-form-7 id="473" title="cf7#1_ua"]'
    addons = ''.join("\t%s => array( %s, %s, %d ),\n" % (php(k), php(a), php(b), p) for k, a, b, p in sv['addons'])
    pr = sv['prices']
    return f"""<?php
/**
 * Template Name: Revive — {sv['name']}
 *
 * Посадкова сторінка послуги «{sv['name']}» (UA / EN через Polylang).
 * UA-слаг: {sv['slugs']['uk'][0]} · EN-слаг: {sv['slugs']['en'][0]}
 * Створіть сторінку українською, додайте переклад англійською і в обох виберіть цей шаблон.
 *
 * Ціни — у $rv_wd_prices і $rv_wd_addons нижче; {{P1}} {{P2}} {{P3}} у текстах підставляються автоматично.
 * Title / description — $T['meta_t'] / $T['meta_d']. SEO-текст — $T['seo'] (або текст із редактора сторінки).
 * Згенеровано tools/build_services.py — правки текстів краще вносити в tools/services_data.py.
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

/* ЦІНИ пакетів і опцій калькулятора — змінюйте тут */
$rv_wd_prices = array(
	'start'   => {pr['start']},
	'sales'   => {pr['sales']},
	'premium' => {pr['premium']},
);
$rv_wd_addons = array( // ключ => [UA, EN, ціна $]
{addons});

$T = $rv_en ? {T_block(en)} : {T_block(uk)};

/* підставляємо ціни {{P1}} {{P2}} {{P3}} і повні URL */
$rv_pm = static function ( $n ) {{ return '$' . number_format( (int) $n, 0, '.', ' ' ); }};
$rv_fill = array( '{{P1}}' => $rv_pm( $rv_wd_prices['start'] ), '{{P2}}' => $rv_pm( $rv_wd_prices['sales'] ), '{{P3}}' => $rv_pm( $rv_wd_prices['premium'] ) );
array_walk_recursive( $T, static function ( &$v ) use ( $rv_fill ) {{ if ( is_string( $v ) ) $v = strtr( $v, $rv_fill ); }} );
foreach ( array( 'pp_url', 'cases_url' ) as $rv_k ) $T[ $rv_k ] = home_url( $T[ $rv_k ] );
$rv_case_tag = {php(sv['case_tag'])};

/* title і description сторінки (працює і з Yoast / Rank Math, і без них) */
$rv_meta_t = $T['meta_t'];
$rv_meta_d = $T['meta_d'];
$rv_set_t  = static function () use ( $rv_meta_t ) {{ return $rv_meta_t; }};
$rv_set_d  = static function () use ( $rv_meta_d ) {{ return $rv_meta_d; }};
add_filter( 'pre_get_document_title', $rv_set_t, 999 );
foreach ( array( 'wpseo_title', 'wpseo_opengraph_title', 'wpseo_twitter_title', 'rank_math/frontend/title', 'rank_math/opengraph/facebook/og_title' ) as $rv_h ) add_filter( $rv_h, $rv_set_t, 999 );
foreach ( array( 'wpseo_metadesc', 'wpseo_opengraph_desc', 'wpseo_twitter_description', 'rank_math/frontend/description', 'rank_math/opengraph/facebook/og_description' ) as $rv_h ) add_filter( $rv_h, $rv_set_d, 999 );
if ( ! defined( 'WPSEO_VERSION' ) && ! class_exists( 'RankMath' ) && ! defined( 'AIOSEO_VERSION' ) ) {{
	add_action( 'wp_head', static function () use ( $rv_meta_d ) {{ echo '<meta name="description" content="' . esc_attr( $rv_meta_d ) . '">' . "\\n"; }}, 1 );
}}
"""

def cut(s, start_marker, end_marker):
    a = s.index(start_marker); b = s.index(end_marker, a + 1)
    return s[:a] + s[b:]

def body():
    s = BASE[BASE.index('/* Кейси — ті самі'):]
    def rep(o, n, c=1):
        nonlocal s
        assert s.count(o) == c, (o[:60], s.count(o)); s = s.replace(o, n)
    # кейси: спершу релевантні послузі
    rep("$rv_wd_channels = array(", """usort( $rv_wd_cases, static function ( $a, $b ) use ( $rv_case_tag ) {
	return (int) ( false !== strpos( $b[6], $rv_case_tag ) ) - (int) ( false !== strpos( $a[6], $rv_case_tag ) );
} );
$rv_wd_cases = array_slice( $rv_wd_cases, 0, 3 ); /* 3 найрелевантніші — один ряд, один екран */

$rv_wd_channels = array(""")
    # менше тексту: прибираємо блоки «спецпропозиція» і «гарантії»
    s = cut(s, '<!-- ── OFFER $250', '<!-- ── CALCULATOR')
    s = cut(s, '<!-- ── GUARANTEES', '<!-- ── FAQ')
    s = cut(s, '<!-- ── КЛІЄНТИ: блок з головної', '<!-- ── FAQ')
    # hero: нотатка — лише якщо є
    rep("""<p class="rv-wd-hero__note"><?php echo esc_html( $T['hero_note'] ); ?></p>""",
        """<?php if ( '' !== $T['hero_note'] ) : ?><p class="rv-wd-hero__note"><?php echo esc_html( $T['hero_note'] ); ?></p><?php endif; ?>""")
    # hero-візуал під послугу
    rep('<span>yourbrand.com</span>', "<span><?php echo esc_html( $T['vis'][0] ); ?></span>")
    rep("<?php echo $rv_en ? 'Your next landing page' : 'Ваш майбутній лендінг'; ?>", "<?php echo esc_html( $T['vis'][1] ); ?>")
    rep("<?php echo $rv_en ? 'Built for<br><em>your brand.</em>' : 'На рівні<br><em>вашого бізнесу.</em>'; ?>", "<?php echo wp_kses( $T['vis'][2], array( 'br' => array(), 'em' => array() ) ); ?>")
    rep("<?php echo $rv_en ? 'Design · SEO · Development' : 'Дизайн · SEO · Розробка'; ?>", "<?php echo esc_html( $T['vis'][3] ); ?>")
    rep("<?php echo $rv_en ? 'One tap<br><em>to order.</em>' : 'Заявка<br><em>в 1 дотик.</em>'; ?>", "<?php echo wp_kses( $T['vis'][4], array( 'br' => array(), 'em' => array() ) ); ?>")
    rep("<?php echo $rv_en ? 'ORDER ↗' : 'ЗАМОВИТИ ↗'; ?>", "<?php echo esc_html( $T['vis'][5] ); ?>", 2)
    # ціна «/міс» для щомісячних послуг
    rep("""<b><?php echo esc_html( rv_wd_money( $rv_wd_prices[ $rv_p[0] ] ) ); ?></b></div>""",
        """<b><?php echo esc_html( rv_wd_money( $rv_wd_prices[ $rv_p[0] ] ) ); ?></b><?php if ( $T['unit'] ) : ?><small><?php echo esc_html( $T['unit'] ); ?></small><?php endif; ?></div>""")
    rep("""<em><?php echo esc_html( $T['pk_from'] . ' ' . rv_wd_money( $rv_wd_prices[ $rv_k ] ) ); ?></em>""",
        """<em><?php echo esc_html( $T['pk_from'] . ' ' . rv_wd_money( $rv_wd_prices[ $rv_k ] ) . $T['unit'] ); ?></em>""")
    # примітка під пакетами — лише якщо є
    rep("""<p class="rv-wd-section-note" data-rv><?php echo esc_html( $T['pk_note'] ); ?></p>""",
        """<?php if ( '' !== $T['pk_note'] ) : ?><p class="rv-wd-section-note" data-rv><?php echo esc_html( $T['pk_note'] ); ?></p><?php endif; ?>""")
    # «Усі опції +N»: на телефоні показуємо 3 опції
    rep("count( $rv_wd_addons ) - 4", "count( $rv_wd_addons ) - 3")
    # компактніші секції: кожен блок — в один екран
    css = """
/* ── сторінки послуг: компактні секції, кожен блок — в один екран ── */
.rv-wd .rv-wd__sec:not(.rv-wd-hero){padding-top:clamp(44px,7vh,84px)!important;padding-bottom:clamp(44px,7vh,84px)!important}
.rv-wd-pk__price small:last-child{margin-left:2px;opacity:.7}
.rv-wd-pr__list{grid-template-columns:repeat(4,minmax(0,1fr))}
.rv-wd .rv-wd-ca.rv-wd__sec{padding-top:clamp(32px,5vh,60px)!important;padding-bottom:clamp(32px,5vh,60px)!important}
.rv-wd-cs .rv-wd-section-lead,.rv-wd-ca .rv-wd-section-lead{display:none}
@media(max-width:1100px){.rv-wd-pr__list{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:700px){.rv-wd .rv-wd__sec:not(.rv-wd-hero){padding-top:40px!important;padding-bottom:40px!important}.rv-wd-pr__list{grid-template-columns:1fr}.rv-wd-pk__d,.rv-wd-ca__note,.rv-wd-pk__time,.rv-wd-ca__rows,.rv-wd-pk-sec .rv-wd-section-note{display:none}.rv-wd-pk-sec .rv-wd__head{margin-bottom:18px!important}.rv-wd-ca__opts:not(.is-all) .rv-wd-ca__opt:nth-child(n+4){display:none}}
"""
    i = s.index('</style>'); s = s[:i] + css + s[i:]
    # лендінгові формулювання в спільних частинах
    rep("'Лендінг — спецціна'", "'Пакет'") if "'Лендінг — спецціна'" in s else None
    return s

def main():
    out = []
    for sv in SERVICES:
        src = head(sv) + '\n' + body()
        path = os.path.join(ROOT, sv['file'])
        open(path, 'w', encoding='utf-8').write(src)
        out.append(path)
    print('\n'.join(out))

if __name__ == '__main__':
    main()
