<?php
/**
 * Plugin Name: Revive — створення сторінок (одноразово)
 * Description: Створює сторінки інтернет-магазинів і лендінгу (UA + EN), шаблони, батьківські сторінки та зв'язки Polylang. Уже наявні сторінки пропускає.
 *
 * Як запустити: покладіть файл у wp-content/mu-plugins/, увійдіть в адмінку як адміністратор
 * і відкрийте  /wp-admin/?rv_create_pages=1   Після запуску ВИДАЛІТЬ цей файл.
 */

add_action( 'admin_init', function () {
	if ( empty( $_GET['rv_create_pages'] ) || ! current_user_can( 'manage_options' ) ) return;

	/* [заголовок, слаг, слаг батьківської сторінки або ''] */
	$pages = array(
		array( 'tpl' => 'page-ecommerce-development.php', 'uk' => array( 'Розробка інтернет-магазину під ключ', 'rozrobka-internet-magazinu-pid-klyuch', '' ), 'en' => array( 'Turnkey online store development', 'turnkey-online-store-development', '' ) ),
		array( 'tpl' => 'page-ecommerce-development.php', 'uk' => array( 'Розробка інтернет-магазину одягу', 'rozrobka-internet-magazinu-odyagu', '' ), 'en' => array( 'Clothing online store development', 'clothing-online-store-development', '' ) ),
		array( 'tpl' => 'page-ecommerce-development.php', 'uk' => array( 'Розробка інтернет-магазину косметики', 'rozrobka-internet-magazinu-kosmetiki', '' ), 'en' => array( 'Cosmetics online store development', 'cosmetics-online-store-development', '' ) ),
		array( 'tpl' => 'page-ecommerce-development.php', 'uk' => array( 'Розробка інтернет-магазину автозапчастин', 'rozrobka-internet-magazinu-avtozapchastin', '' ), 'en' => array( 'Auto Parts online store development', 'auto-parts-online-store-development', '' ) ),
		array( 'tpl' => 'page-ecommerce-development.php', 'uk' => array( 'Розробка інтернет-магазину меблів', 'rozrobka-internet-magazinu-mebliv', '' ), 'en' => array( 'Furniture online store development', 'furniture-online-store-development', '' ) ),
		array( 'tpl' => 'page-ecommerce-development.php', 'uk' => array( 'Розробка інтернет-магазину електроніки', 'rozrobka-internet-magazinu-elektroniki', '' ), 'en' => array( 'Electronics online store development', 'electronics-online-store-development', '' ) ),
		array( 'tpl' => 'page-ecommerce-development.php', 'uk' => array( 'Розробка інтернет-магазину продуктів', 'rozrobka-internet-magazinu-produktiv', '' ), 'en' => array( 'Grocery online store development', 'grocery-online-store-development', '' ) ),
		array( 'tpl' => 'page-ecommerce-development.php', 'uk' => array( 'Розробка інтернет-магазину товарів для дому', 'rozrobka-internet-magazinu-tovariv-dlya-domu', '' ), 'en' => array( 'Home Goods online store development', 'home-goods-online-store-development', '' ) ),
		array( 'tpl' => 'page-ecommerce-development.php', 'uk' => array( 'Розробка інтернет-магазину взуття', 'rozrobka-internet-magazinu-vzuttya', '' ), 'en' => array( 'Shoe online store development', 'shoe-online-store-development', '' ) ),
		array( 'tpl' => 'page-ecommerce-development.php', 'uk' => array( 'Розробка інтернет-магазину зоотоварів', 'rozrobka-internet-magazinu-zootovariv', '' ), 'en' => array( 'Pet Supplies online store development', 'pet-supplies-online-store-development', '' ) ),
		array( 'tpl' => 'page-ecommerce-development.php', 'uk' => array( 'Розробка інтернет-магазину будівельних матеріалів', 'rozrobka-internet-magazinu-budivelnih-materialiv', '' ), 'en' => array( 'Building Materials online store development', 'building-materials-online-store-development', '' ) ),
		array( 'tpl' => 'page-ecommerce-development.php', 'uk' => array( 'Розробка інтернет-магазину спортивних товарів', 'rozrobka-internet-magazinu-sportivnih-tovariv', '' ), 'en' => array( 'Sporting Goods online store development', 'sporting-goods-online-store-development', '' ) ),
		array( 'tpl' => 'page-ecommerce-development.php', 'uk' => array( 'Розробка інтернет-магазину дитячих товарів', 'rozrobka-internet-magazinu-dityachih-tovariv', '' ), 'en' => array( 'Kids’ Products online store development', 'kids-products-online-store-development', '' ) ),
		array( 'tpl' => 'page-ecommerce-development.php', 'uk' => array( 'Розробка інтернет-магазину медичних товарів', 'rozrobka-internet-magazinu-medichnih-tovariv', '' ), 'en' => array( 'Medical Supplies online store development', 'medical-supplies-online-store-development', '' ) ),
		array( 'tpl' => 'page-ecommerce-development.php', 'uk' => array( 'Розробка інтернет-магазину парфумерії', 'rozrobka-internet-magazinu-parfumeriyi', '' ), 'en' => array( 'Perfume online store development', 'perfume-online-store-development', '' ) ),
		array( 'tpl' => 'page-ecommerce-development.php', 'uk' => array( 'Розробка інтернет-магазину ювелірних виробів', 'rozrobka-internet-magazinu-yuvelirnih-virobiv', '' ), 'en' => array( 'Jewellery online store development', 'jewellery-online-store-development', '' ) ),
		array( 'tpl' => 'page-landing-development.php', 'uk' => array( 'Розробка лендінгу під ключ', 'rozrobka-lendingu', 'rozrobka-sajtiv' ), 'en' => array( 'Turnkey landing page development', 'landing-page-development', 'web-development' ) ),
	);

	$find = function ( $slug, $parent_slug ) {
		$path = $parent_slug ? $parent_slug . '/' . $slug : $slug;
		$p = get_page_by_path( $path, OBJECT, 'page' );
		return ( $p && 'trash' !== $p->post_status ) ? $p : null;
	};
	$log = array();
	foreach ( $pages as $row ) {
		$ids = array();
		foreach ( array( 'uk', 'en' ) as $lang ) {
			list( $title, $slug, $parent_slug ) = $row[ $lang ];
			$parent_id = 0;
			if ( $parent_slug ) {
				$parent = $find( $parent_slug, '' );
				if ( ! $parent ) { $log[] = "❌ $lang: немає батьківської сторінки «{$parent_slug}» — пропущено «{$title}»"; continue; }
				$parent_id = $parent->ID;
			}
			$page = $find( $slug, $parent_slug );
			if ( $page ) {
				$id = $page->ID;
				$log[] = "• $lang: вже є — {$title} (ID $id)";
			} else {
				$id = wp_insert_post( array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_title'   => $title,
					'post_name'    => $slug,
					'post_content' => '',
					'post_parent'  => $parent_id,
				), true );
				if ( is_wp_error( $id ) ) { $log[] = "❌ $lang: помилка — {$title}: " . $id->get_error_message(); continue; }
				$real = get_post_field( 'post_name', $id );
				$log[] = ( $real === $slug ? '✅' : '⚠️' ) . " $lang: створено — {$title} → /{$real}/" . ( $real === $slug ? '' : " (очікувався «{$slug}», слаг зайнятий)" );
			}
			update_post_meta( $id, '_wp_page_template', $row['tpl'] );
			if ( function_exists( 'pll_set_post_language' ) ) pll_set_post_language( $id, $lang );
			$ids[ $lang ] = (int) $id;
		}
		if ( 2 === count( $ids ) && function_exists( 'pll_save_post_translations' ) ) pll_save_post_translations( $ids );
	}
	flush_rewrite_rules( false );

	wp_die( '<h2>Revive: сторінки</h2><p>' . implode( '<br>', array_map( 'esc_html', $log ) ) . '</p><p><b>Готово. Тепер видаліть файл wp-content/mu-plugins/rv-create-pages.php.</b></p>', 'Revive: сторінки', array( 'response' => 200 ) );
} );
