<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Underscore
 */

?>

	<footer class="footer">
  <img loading="lazy" decoding="async" class="footer__watermark" src="<?php echo get_template_directory_uri();  ?>/assets/footer-watermark.png" alt="" aria-hidden="true">
  <div class="container footer__inner">
    <div class="footer__about">
      <a class="logo" href="<?php echo site_url(); ?>"><img loading="lazy" decoding="async" src="<?php echo get_template_directory_uri();  ?>/assets/logo-white2.png" width="222" height="49" alt="Спецзнак"></a>
      <?php dynamic_sidebar('footer-1'); ?>
    </div>
    <div class="footer__contacts">
      <h3 class="footer__h desk-only">Контакти</h3>
        <div class="phone-drop f-box f-phone" data-dropdown>
          <button class="f-phone__btn phone-drop__btn" type="button" aria-expanded="false" data-dropdown-toggle>
            <svg class="desk-only-inline" width="18" height="18"><use href="#i-phone"/></svg><span class="phone-drop__num" data-tel="<?php echo get_field('telefon_1', 'options');  ?>"><?php echo get_field('telefon_1', 'options');  ?></span>
            <img class="phone-drop__caret f-phone__caret" src="<?php echo get_template_directory_uri();  ?>/assets/arrow-down.svg" width="5" height="8" alt="">
          </button>
          <div class="f-phone__list phone-drop__list">
           <?php for ($i = 1; $i <= 3; $i++) { 
		        $tel = get_field('telefon_'.$i, 'options');
			      $tel_url = formatPhoneForTel($tel);   ?>
            <a href="tel:<?php echo $tel_url;  ?>"><?php echo $tel;  ?></a>
			    <?php  } ?>
          </div>
      </div>
      <a class="f-box f-mail" href="mailto:<?php echo get_field('email', 'options');  ?>"><svg class="desk-only-inline" width="18" height="18"><use href="#i-mail"/></svg><?php echo get_field('email', 'options');  ?></a>
      <div class="f-social">
        <a href="<?php echo get_field('viber', 'options'); ?>" target="_blank" aria-label="Viber"><img loading="lazy" decoding="async" src="<?php echo get_template_directory_uri();  ?>/assets/ic-viber.svg" width="20" height="20" alt=""><span class="mob-only-inline">Viber</span></a>
        <a href="<?php echo get_field('telegram', 'options'); ?>" target="_blank" aria-label="Telegram"><img loading="lazy" decoding="async" src="<?php echo get_template_directory_uri();  ?>/assets/ic-telegram.svg" width="18" height="18" alt=""><span class="mob-only-inline">Telegram</span></a>
        <a href="<?php echo get_field('whatsapp', 'options'); ?>" target="_blank" aria-label="WhatsApp"><img loading="lazy" decoding="async" src="<?php echo get_template_directory_uri();  ?>/assets/ic-whatsapp.svg" width="18" height="18" alt=""><span class="mob-only-inline">WhatsApp</span></a>
        <a href="<?php echo get_field('instagram', 'options'); ?>" target="_blank" aria-label="Instagram"><svg width="16" height="16"><use href="#i-instagram"/></svg></a>
        <a href="<?php echo get_field('facebook', 'options'); ?>" target="_blank" aria-label="Facebook"><svg width="16" height="16"><use href="#i-facebook"/></svg></a>
        <a href="<?php echo get_field('youtube', 'options'); ?>" target="_blank" aria-label="YouTube"><svg width="16" height="16"><use href="#i-youtube"/></svg></a>
      </div>
    </div>
    <div class="footer__office">
      <h3 class="footer__h"><?php echo qtranxf_useCurrentLanguageIfNotFoundShowAvailable('[:ua]Головний офіс[:ru]Главный офис'); ?></h3>
      <div class="f-box f-office">
        <p><svg width="16" height="16"><use href="#i-pin"/></svg><span><?php echo get_field('glavnyj_ofis', 'options');  ?></span></p>
        <p><svg width="16" height="16"><use href="#i-clock"/></svg><span><?php echo get_field('rasporyadok_raboty', 'options');  ?></span></p>
      </div>
      <a class="footer__branches" href="<?php echo site_url(); ?>/#contacts"><?php echo qtranxf_useCurrentLanguageIfNotFoundShowAvailable('[:ua]Усі філії →[:ru]Все филиалы →'); ?></a>
    </div>
    <nav class="footer__nav" aria-label="Каталог">
      <?php dynamic_sidebar('footer-2'); ?>
    </nav>
    <nav class="footer__nav" aria-label="Інформація">
      <?php dynamic_sidebar('footer-3'); ?>
    </nav>
    <div class="footer__bottom" style="filter: blur(0px); opacity: 1;">
      <p class="footer__copy">© 2008–<?php echo date('Y'); ?> ТОВ «Спецзнак». Всі права захищені.</p>
      <img class="footer__dev" loading="lazy" decoding="async" src="<?php echo esc_url( content_url( '/uploads/2026/10/revive.png' ) ); ?>" alt="Revive Agency" height="35" style="display:block;width:auto;filter:brightness(0) invert(1);opacity:.85">
      <ul class="payments" aria-label="Способи оплати">
        <li aria-label="Mastercard"><svg viewBox="0 0 40 24" width="40" height="24"><circle cx="15" cy="12" r="9" fill="#EB001B"/><circle cx="25" cy="12" r="9" fill="#F79E1B"/><path d="M20 4.6a9 9 0 0 1 0 14.8 9 9 0 0 1 0-14.8Z" fill="#FF5F00"/></svg></li>
        <li aria-label="Visa"><svg viewBox="0 0 48 16" width="44" height="15"><text x="24" y="14" text-anchor="middle" font-family="Arial, sans-serif" font-size="17" font-weight="900" font-style="italic" fill="#1A1F71">VISA</text></svg></li>
        <li aria-label="Apple Pay"><svg viewBox="0 0 48 20" width="44" height="18"><path d="M8.6 5.2c.5-.6.9-1.5.8-2.4-.8 0-1.7.5-2.3 1.1-.5.5-.9 1.4-.8 2.3.9.1 1.8-.4 2.3-1Zm.8 1.3c-1.3-.1-2.3.7-2.9.7-.6 0-1.5-.7-2.5-.7C2.7 6.5 1.5 7.3.9 8.5c-1.4 2.4-.4 5.9 1 7.8.6.9 1.4 2 2.4 1.9 1 0 1.3-.6 2.5-.6s1.5.6 2.5.6c1 0 1.7-1 2.3-1.9.7-1 1-2 1-2.1 0 0-2-.8-2-3 0-1.9 1.5-2.7 1.6-2.8-.9-1.3-2.2-1.4-2.8-1.4Z" fill="#000"/><text x="15" y="16" font-family="Arial, sans-serif" font-size="15" font-weight="500" fill="#000">Pay</text></svg></li>
        <li aria-label="Google Pay"><svg viewBox="0 0 48 20" width="44" height="18"><text x="1" y="16" font-family="Arial, sans-serif" font-size="16" font-weight="700"><tspan fill="#4285F4">G</tspan></text><text x="15" y="16" font-family="Arial, sans-serif" font-size="15" fill="#5F6368">Pay</text></svg></li>
      </ul>
    </div>
  </div>
</footer>



<!-- ============ МЕНЮ КАТАЛОГУ (ПК) ============ -->
<div class="pcmenu" id="pcmenu" data-pcmenu aria-hidden="true">
  <div class="pcmenu__panel">
    <div class="container pcmenu__inner">
      <?php render_pcmenu( 'menu-2' ); ?>
    </div>
  </div>
</div>

<!-- ============ ПОП-АП «ЗАЯВКА» ============ -->
<div class="popup" data-popup="order" role="dialog" aria-modal="true" aria-labelledby="popup-title" aria-hidden="true">
  <div class="popup__overlay" data-popup-close></div>
  <div class="popup__box">
    <button class="popup__close" type="button" aria-label="Закрити" data-popup-close><svg width="22" height="22"><use href="#i-close"/></svg></button>
     <?php if (get_locale()=="uk")
     echo do_shortcode('[contact-form-7 id="840df3a" title="Pop-ap форма"]'); 
     else
     echo do_shortcode('[contact-form-7 id="f4bd7ce" title="Pop-ap форма_ru"]'); ?>
  </div>
</div>

<!-- ============ ПОП-АП «ЗАКАЗ РАМКИ» ============ -->
<div class="popup" data-popup="order2" role="dialog" aria-modal="true" aria-labelledby="popup-title" aria-hidden="true">
  <div class="popup__overlay" data-popup-close></div>
  <div class="popup__box">
    <button class="popup__close" type="button" aria-label="Закрити" data-popup-close><svg width="22" height="22"><use href="#i-close"/></svg></button>
    <?php if (get_locale()=="uk")
    echo do_shortcode('[contact-form-7 id="a7f05b9" title="Pop-ap форма заказа рамки"]'); 
    else
     echo do_shortcode('[contact-form-7 id="a8a2db0" title="Pop-ap форма заказа рамки_ru"]'); ?>
  </div>
</div>

<!-- ============ ПОП-АП «ЗАКАЗ СУВЕНІРНОЇ ПРОДУКЦІЇ» (категорія others) ============ -->
<div class="popup" data-popup="order3" role="dialog" aria-modal="true" aria-labelledby="popup-title" aria-hidden="true">
  <div class="popup__overlay" data-popup-close></div>
  <div class="popup__box">
    <button class="popup__close" type="button" aria-label="Закрити" data-popup-close><svg width="22" height="22"><use href="#i-close"/></svg></button>
    <?php if (get_locale()=="uk")
    echo do_shortcode('[contact-form-7 id="bb87fb1" title="Pop-ap форма заказа суверной продукции - Укр"]');
    else
     echo do_shortcode('[contact-form-7 id="1363fb4" title="Pop-ap форма заказа суверной продукции - Ру"]'); ?>
  </div>
</div>

<!-- ============ МОБІЛЬНЕ МЕНЮ ============ -->
 
<?php render_pcmenu_mobile( 'menu-2', get_pcmenu_card_html() ); ?>

<?php wp_footer(); ?>
</body>
</html>