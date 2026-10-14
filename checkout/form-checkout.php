<?php
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html__( 'You must be logged in to checkout.', 'woocommerce' );
    return;
}

$val = fn( $k ) => esc_attr( $checkout->get_value( $k ) );

$raw = function_exists( 'get_field' ) ? (string) get_field( 'spisok_ofisov', 'option' ) : '';
$raw = str_replace( "\r", '', $raw );

$offices = array_filter( array_map(
    fn( $line ) => trim( pdp_lang( $line ) ),
    explode( "\n", $raw )
) );
$posted_office = isset( $_POST['office'] ) ? sanitize_text_field( wp_unslash( $_POST['office'] ) ) : '';
$selected_office = in_array( $posted_office, $offices, true ) ? $posted_office : (string) reset( $offices );

// [название, описание, цена справа, текст в итогах]
$delivery = [];
foreach ( (array) ( function_exists( 'get_field' ) ? get_field( 'co_delivery', 'option' ) : [] ) as $row ) {
    if ( empty( $row['key'] ) ) continue;
    $delivery[ $row['key'] ] = [
        pdp_lang( (string) $row['title'] ), pdp_lang( (string) $row['desc'] ),
        pdp_lang( (string) $row['price'] ), pdp_lang( (string) $row['ship'] ),
    ];
}
// самовивіз: замість «З нашого офісу, оплата можлива при отриманні» — попередження про обмеження в офісах
if ( isset( $delivery['pickup'] ) ) {
    $delivery['pickup'][1] = pdp_t( '[:ua]Зверніть увагу! У деяких офісах самовивіз доступний лише для окремих типів номерних знаків.[:ru]Обратите внимание! В некоторых офисах самовывоз доступен только для отдельных типов номерных знаков.' );
}

$s1 = pdp_t( '[:ua]1 сторона[:ru]1 сторона' );
$s2 = pdp_t( '[:ua]2 сторона[:ru]2 сторона' );
$lic = pdp_t( '[:ua]Паспорт або водійське[:ru]Паспорт или водительское' );
$reg = pdp_t( '[:ua]Свідоцтво про реєстрацію[:ru]Свидетельство о регистрации' );
$docs = [
    'license_1' => [ $lic, $s1 ], 'license_2' => [ $lic, $s2 ],
    'reg_1'     => [ $reg, $s1 ], 'reg_2'     => [ $reg, $s2 ],
];

$head = function ( $n, $title, $sub ) {
    echo '<div class="co-card__head"><span class="co-num">' . (int) $n . '</span><div><h2 class="co-card__title">'
        . esc_html( $title ) . '</h2><p class="co-card__sub">' . esc_html( $sub ) . '</p></div></div>';
};
?>
<a class="co-back desk-only" href="<?php echo esc_url( wc_get_cart_url() ); ?>">← <?php echo pdp_t( '[:ua]Повернутись до покупок[:ru]Вернуться к покупкам' ); ?></a>

<div class="co-hero">
  <h1 class="co-hero__title"><?php echo pdp_t( '[:ua]Оформлення замовлення[:ru]Оформление заказа' ); ?></h1>
  <p class="co-hero__text"><?php echo pdp_t( '[:ua]Заповніть дані — менеджер підтвердить номер за документами до початку виготовлення.[:ru]Заполните данные — менеджер подтвердит номер по документам до начала изготовления.' ); ?></p>
</div>

<div class="co-mode" role="tablist" aria-label="<?php echo esc_attr( pdp_t( '[:ua]Тип замовлення[:ru]Тип заказа' ) ); ?>" data-co-mode data-active-mode="full">
  <span class="co-mode__pill" aria-hidden="true"></span>
  <button class="co-mode__btn is-active" type="button" role="tab" aria-selected="true" data-mode="full"><?php echo pdp_t( '[:ua]Детальне замовлення[:ru]Подробный заказ' ); ?></button>
  <button class="co-mode__btn" type="button" role="tab" aria-selected="false" data-mode="quick"><?php echo pdp_t( '[:ua]Замовити <br class="mob-only">швидко[:ru]Заказать <br class="mob-only">быстро' ); ?></button>
</div>

<form name="checkout" method="post" class="checkout woocommerce-checkout co-grid" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" novalidate data-co-form>
  <input type="hidden" name="order_mode" value="full" data-co-mode-input>

  <div class="co-main">

    <!-- 1. Одержувач -->
    <section class="co-card co-card--1">
      <?php $head( 1, pdp_t( '[:ua]Одержувач замовлення[:ru]Получатель заказа' ), pdp_t( '[:ua]На це імʼя оформлюємо доставку[:ru]На это имя оформляем доставку' ) ); ?>
      <div class="co-fields">
        <label class="co-field"><span class="co-label"><b><?php echo pdp_t( '[:ua]Імʼя[:ru]Имя' ); ?></b> <i>*</i></span>
          <input class="co-input" type="text" name="billing_first_name" id="billing_first_name" autocomplete="given-name" value="<?php echo $val( 'billing_first_name' ); ?>" required></label>
        <label class="co-field" data-full-only><span class="co-label"><b><?php echo pdp_t( '[:ua]Прізвище[:ru]Фамилия' ); ?></b> <i>*</i></span>
          <input class="co-input" type="text" name="billing_last_name" id="billing_last_name" autocomplete="family-name" value="<?php echo $val( 'billing_last_name' ); ?>" required></label>
        <label class="co-field co-field--phone"><span class="co-label"><b><?php echo pdp_t( '[:ua]Телефон[:ru]Телефон' ); ?></b> <i>*</i></span>
          <input class="co-input" type="tel" name="billing_phone" id="billing_phone" inputmode="tel" autocomplete="tel" placeholder="+38 (0__) ___-__-__" value="<?php echo $val( 'billing_phone' ); ?>" required data-phone-mask></label>
      </div>
    </section>

    <!-- 2. Фото документів -->
    <section class="co-card co-card--2" data-full-only>
      <?php $head( 2, pdp_t( '[:ua]Фото документів[:ru]Фото документов' ), pdp_t( '[:ua]Не обовʼязково — можна надіслати у Viber[:ru]Не обязательно — можно прислать в Viber' ) ); ?>
      <div class="co-docs">
        <?php foreach ( $docs as $key => [ $title, $side ] ) : ?>
          <label class="co-doc">
            <input type="file" accept="image/jpeg,image/png" data-doc-input hidden>
            <input type="hidden" name="doc_<?php echo esc_attr( $key ); ?>" value="">
            <span class="co-doc__title"><?php echo esc_html( $title ); ?></span>
            <span class="co-doc__drop" data-doc-drop><span data-doc-label><?php echo esc_html( $side ); ?></span></span>
          </label>
        <?php endforeach; ?>
      </div>
      <p class="co-hint desk-only"><?php echo pdp_t( '[:ua]JPG або PNG, до 10 МБ. Замість посвідчення водія підійде паспорт.[:ru]JPG или PNG, до 10 МБ. Вместо водительского удостоверения подойдёт паспорт.' ); ?></p>
      <label class="co-field co-field--comment"><span class="co-label"><b><?php echo pdp_t( '[:ua]Коментар до замовлення —[:ru]Комментарий к заказу —' ); ?></b> <?php echo pdp_t( '[:ua]не обовʼязково[:ru]не обязательно' ); ?></span>
        <textarea class="co-input co-textarea" name="order_comments" id="order_comments" rows="3"><?php echo esc_textarea( $checkout->get_value( 'order_comments' ) ); ?></textarea></label>
    </section>

    <!-- 3. Доставка -->
    <section class="co-card co-card--3" data-full-only>
      <?php $head( 3, pdp_t( '[:ua]Доставка[:ru]Доставка' ), pdp_t( '[:ua]Спосіб отримання замовлення[:ru]Способ получения заказа' ) ); ?>
      <div class="co-opts">
        <?php foreach ( $delivery as $id => [ $title, $desc, $price, $ship ] ) : ?>
          <label class="co-opt<?php echo $id === 'pickup' ? ' co-opt--pickup' : ''; ?>"<?php echo $id === 'pickup' ? ' data-pickup-option' : ''; ?>>
            <input type="radio" name="delivery" value="<?php echo esc_attr( $id ); ?>" data-ship-text="<?php echo esc_attr( $ship ); ?>"<?php checked( $id, 'pickup' ); ?>>
            <span class="co-opt__radio" aria-hidden="true"></span>
            <span class="co-opt__body"><span class="co-opt__title"><?php echo esc_html( $title ); ?></span><span class="co-opt__desc"><?php echo esc_html( $desc ); ?></span><?php if ( $id === 'pickup' && $selected_office ) : ?><span class="co-opt__office<?php echo $posted_office === $selected_office ? '' : ' is-default'; ?>" data-office-line><span class="co-opt__office-placeholder"><?php echo pdp_t( '[:ua]Оберіть офіс[:ru]Выберите офис' ); ?></span><span class="co-opt__office-value"><b><?php echo pdp_t( '[:ua]Офіс:[:ru]Офис:' ); ?></b> <span data-office-choice aria-live="polite"><?php echo esc_html( $selected_office ); ?></span></span><svg class="co-opt__office-arrow" width="12" height="8" viewBox="0 0 12 8" aria-hidden="true"><path d="m1 1.5 5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg></span><?php endif; ?></span>
            <span class="co-opt__price"><?php echo esc_html( $price ); ?></span>
          </label>
        <?php endforeach; ?>
        <?php if ( $offices ) : ?><input type="hidden" name="office" value="<?php echo esc_attr( $selected_office ); ?>" data-office-input><?php endif; ?>
      </div>
      <div class="co-native-ship" data-native-ship>
  <?php
  WC()->cart->calculate_totals();
  do_action( 'woocommerce_review_order_before_shipping' );
  wc_cart_totals_shipping_html();
  do_action( 'woocommerce_review_order_after_shipping' );
  ?>
  <div class="co-np__fields">
    <?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
  </div>
</div>
      <?php if ( $offices ) : ?>
        <div class="co-office-dialog" data-office-dialog role="dialog" aria-modal="true" aria-labelledby="co-office-dialog-title" hidden>
          <div class="co-office-dialog__backdrop" data-office-close></div>
          <div class="co-office-dialog__panel">
            <div class="co-office-dialog__head">
              <div><h3 id="co-office-dialog-title"><?php echo pdp_t( '[:ua]Оберіть офіс[:ru]Выберите офис' ); ?></h3><p><?php echo pdp_t( '[:ua]Самовивіз замовлення[:ru]Самовывоз заказа' ); ?></p></div>
              <button class="co-office-dialog__close" type="button" data-office-close aria-label="<?php echo esc_attr( pdp_t( '[:ua]Закрити[:ru]Закрыть' ) ); ?>">×</button>
            </div>
            <div class="co-office-dialog__list">
              <?php foreach ( $offices as $o ) : ?>
                <button class="co-office-dialog__option" type="button" data-office-value="<?php echo esc_attr( $o ); ?>" aria-pressed="<?php echo $o === $selected_office ? 'true' : 'false'; ?>"><span><?php echo esc_html( $o ); ?></span><span class="co-office-dialog__check" aria-hidden="true">✓</span></button>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </section>

    <!-- 4. Оплата -->
    <section class="co-card co-card--4" data-full-only>
      <?php $head( 4, pdp_t( '[:ua]Оплата[:ru]Оплата' ), pdp_t( '[:ua]Спосіб розрахунку[:ru]Способ расчёта' ) ); ?>
      <?php woocommerce_checkout_payment(); ?>
      <div class="co-opts">
        <label class="co-check">
          <input type="checkbox" name="no_call" value="1"<?php checked( $checkout->get_value( 'no_call' ), '1' ); ?>>
          <span class="co-check__box" aria-hidden="true">✓</span>
          <span class="co-check__body"><span class="co-check__title"><?php echo pdp_t( '[:ua]Не дзвоніть мені[:ru]Не звоните мне' ); ?></span><span class="co-check__desc"><?php echo pdp_t( '[:ua]Перевірте, чи правильно прикріплені фото документів. Якщо знадобиться уточнення — напишемо у Viber.[:ru]Проверьте, правильно ли прикреплены фото документов. Если понадобится уточнение — напишем в Viber.' ); ?></span></span>
        </label>
      </div>
    </section>
  </div>

  <!-- Ваше замовлення -->
  <aside class="co-aside" id="order_review" aria-label="<?php echo esc_attr( pdp_t( '[:ua]Ваше замовлення[:ru]Ваш заказ' ) ); ?>">
    <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
    <div class="co-aside__head">
      <h2 class="co-card__title"><?php echo pdp_t( '[:ua]Ваше замовлення[:ru]Ваш заказ' ); ?></h2>
      <a class="co-aside__edit" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php echo pdp_t( '[:ua]Змінити[:ru]Изменить' ); ?></a>
    </div>

    <?php woocommerce_order_review(); ?>

    <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
    <button class="btn btn--primary co-submit" type="submit" name="woocommerce_checkout_place_order" id="place_order"
            value="<?php echo esc_attr( pdp_t( '[:ua]Оформити замовлення[:ru]Оформить заказ' ) ); ?>"
            data-value="<?php echo esc_attr( pdp_t( '[:ua]Оформити замовлення[:ru]Оформить заказ' ) ); ?>"><?php echo pdp_t( '[:ua]Оформити замовлення[:ru]Оформить заказ' ); ?></button>

    <p class="co-terms"><?php echo pdp_t( '[:ua]Підтверджуючи замовлення, ви приймаєте <br><a href="https://revive-agency.com/znak/polityka-konfidenczijnosti/" target="_blank" rel="noopener">умови угоди користувача</a>[:ru]Подтверждая заказ, вы принимаете <br><a href="https://revive-agency.com/znak/ru/polityka-konfidenczijnosti/" target="_blank" rel="noopener">условия пользовательского соглашения</a>' ); ?></p>
    <ul class="co-perks">
      <li><span aria-hidden="true">✓</span><?php echo pdp_t( '[:ua]Виготовлення за 15 хвилин після підтвердження[:ru]Изготовление за 15 минут после подтверждения' ); ?></li>
      <li><span aria-hidden="true">✓</span><?php echo pdp_t( '[:ua]Гарантія 2 роки на покриття та читабельність[:ru]Гарантия 2 года на покрытие и читаемость' ); ?></li>
      <li><span aria-hidden="true">✓</span><?php echo pdp_t( '[:ua]Голограма та штамп офіційного виробника[:ru]Голограмма и штамп официального производителя' ); ?></li>
    </ul>
    <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
  </aside>
</form>

<script>
(function () {
  'use strict';
  var form = document.querySelector('[data-co-form]');
  if (!form) return;
  var pickup = form.querySelector('[data-pickup-option]');
  var radio = pickup && pickup.querySelector('input[name="delivery"][value="pickup"]');
  var select = form.querySelector('[data-office-input]');
  var dialog = form.querySelector('[data-office-dialog]');
  var choice = pickup && pickup.querySelector('[data-office-choice]');
  if (!pickup || !radio || !select || !dialog) return;
  var options = Array.prototype.slice.call(dialog.querySelectorAll('[data-office-value]'));
  var opener = null;

  function sync() {
    if (choice) choice.textContent = select.value;
    options.forEach(function (option) {
      option.setAttribute('aria-pressed', option.dataset.officeValue === select.value ? 'true' : 'false');
    });
  }
  function close(restoreFocus) {
    if (dialog.hidden) return;
    dialog.hidden = true;
    document.documentElement.classList.remove('co-office-dialog-open');
    if (restoreFocus && opener && opener.isConnected) opener.focus({ preventScroll: true });
  }
  function open() {
    opener = pickup.contains(document.activeElement) ? document.activeElement : radio;
    dialog.hidden = false;
    document.documentElement.classList.add('co-office-dialog-open');
    sync();
    (options.find(function (option) { return option.dataset.officeValue === select.value; }) || options[0] || dialog.querySelector('.co-office-dialog__close')).focus({ preventScroll: true });
  }

  pickup.addEventListener('click', function (event) {
    event.preventDefault();
    if (!radio.checked) {
      radio.checked = true;
      radio.dispatchEvent(new Event('change', { bubbles: true }));
    }
    open();
  });
  dialog.addEventListener('click', function (event) {
    var option = event.target.closest('[data-office-value]');
    if (option) {
      select.value = option.dataset.officeValue;
      var line = pickup.querySelector('[data-office-line]');
      if (line) line.classList.remove('is-default');
      select.dispatchEvent(new Event('change', { bubbles: true }));
      sync();
      close(false);
      var payment = form.querySelector('.co-card--4');
      if (payment) {
        payment.scrollIntoView({ behavior: 'smooth', block: 'start' });
        var heading = payment.querySelector('.co-card__title');
        if (heading) {
          heading.setAttribute('tabindex', '-1');
          heading.focus({ preventScroll: true });
        }
      }
    } else if (event.target.closest('[data-office-close]')) {
      close(true);
    }
  });
  dialog.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') { event.preventDefault(); close(true); return; }
    if (event.key !== 'Tab') return;
    var focusables = [dialog.querySelector('.co-office-dialog__close')].concat(options);
    var first = focusables[0], last = focusables[focusables.length - 1];
    if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
    else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
  });
  select.addEventListener('change', sync);
  sync();
})();
</script>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
