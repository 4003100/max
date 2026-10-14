<?php
/**
 * «Не дзвоніть мені» у checkout: зберігає вибір клієнта в замовленні
 * і показує його в адмінці WooCommerce та в листі адміністратору.
 * Вставити в functions.php теми (без першого рядка <?php) або підключити через require.
 */
defined( 'ABSPATH' ) || exit;

// Збереження: 'yes' — не дзвонити, 'no' — можна дзвонити.
add_action( 'woocommerce_checkout_create_order', function ( $order ) {
    $order->update_meta_data( '_no_call', ! empty( $_POST['no_call'] ) ? 'yes' : 'no' );
} );

function co_no_call_label( $order ) {
    $v = $order->get_meta( '_no_call' );
    if ( $v === '' ) return '';
    return $v === 'yes' ? '❌ Не дзвонити — писати у Viber' : '📞 Можна дзвонити';
}

// Сторінка замовлення в адмінці — під адресою оплати.
add_action( 'woocommerce_admin_order_data_after_billing_address', function ( $order ) {
    $label = co_no_call_label( $order );
    if ( $label ) echo '<p><strong>Дзвінок клієнту:</strong><br>' . esc_html( $label ) . '</p>';
} );

// Колонка у списку замовлень (класичний список і HPOS).
$co_no_call_col = function ( $cols ) {
    $cols['co_no_call'] = 'Дзвінок';
    return $cols;
};
add_filter( 'manage_edit-shop_order_columns', $co_no_call_col );
add_filter( 'manage_woocommerce_page_wc-orders_columns', $co_no_call_col );

$co_no_call_cell = function ( $col, $order ) {
    if ( $col !== 'co_no_call' ) return;
    $order = is_a( $order, 'WC_Order' ) ? $order : wc_get_order( $order );
    if ( ! $order ) return;
    $v = $order->get_meta( '_no_call' );
    echo $v === 'yes' ? '❌ Не дзвонити' : ( $v === 'no' ? '📞 Дзвонити' : '—' );
};
add_action( 'manage_shop_order_posts_custom_column', $co_no_call_cell, 10, 2 );
add_action( 'manage_woocommerce_page_wc-orders_custom_column', $co_no_call_cell, 10, 2 );

// Лист адміністратору про нове замовлення.
add_filter( 'woocommerce_email_order_meta_fields', function ( $fields, $sent_to_admin, $order ) {
    $label = co_no_call_label( $order );
    if ( $sent_to_admin && $label ) {
        $fields['co_no_call'] = [ 'label' => 'Дзвінок клієнту', 'value' => $label ];
    }
    return $fields;
}, 10, 3 );
