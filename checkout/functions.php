<?php
/**
 * Underscore functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Underscore
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function underscore_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Underscore, use a find and replace
		* to change 'underscore' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'underscore', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'underscore' ),
		)
	);
	register_nav_menus(
		array(
			'menu-2' => esc_html__( 'Secondary', 'underscore' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'underscore_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'underscore_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function underscore_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'underscore_content_width', 640 );
}
add_action( 'after_setup_theme', 'underscore_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function underscore_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 1', 'underscore' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Add widgets here.', 'underscore' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
    register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 2', 'underscore' ),
			'id'            => 'footer-2',
			'description'   => esc_html__( 'Add widgets here.', 'underscore' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="footer__h">',
			'after_title'   => '</h3>',
		)
	);
    register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 3', 'underscore' ),
			'id'            => 'footer-3',
			'description'   => esc_html__( 'Add widgets here.', 'underscore' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="footer__h">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'underscore_widgets_init' );

add_filter('wpcf7_autop_or_not', '__return_false');

/**
 * Enqueue scripts and styles.
 */
function underscore_scripts() {
	wp_enqueue_style( 'underscore-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'underscore-style', 'rtl', 'replace' );

	wp_register_style('style-css', get_template_directory_uri() . '/css/style.css', false, filemtime( get_template_directory() . '/css/style.css' ));
	wp_enqueue_style('style-css');

	wp_enqueue_script( 'underscore-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

     wp_enqueue_script(	'mask-js', get_template_directory_uri() . '/js/jquery.maskedinput.js', array(),	_S_VERSION,	true );

	wp_enqueue_script(	'main-js', get_template_directory_uri() . '/js/main.js', array(),	filemtime( get_template_directory() . '/js/main.js' ),	true );

   

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'underscore_scripts' );



/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}


// Отключает новый редактор блоков в WordPress (Гутенберг).
if( 'disable_gutenberg' ){
	add_filter( 'use_block_editor_for_post_type', '__return_false', 100 );

	remove_action( 'wp_enqueue_scripts', 'wp_common_block_scripts_and_styles' );

	add_action( 'admin_init', function(){
		remove_action( 'admin_notices', [ 'WP_Privacy_Policy_Content', 'notice' ] );
		add_action( 'edit_form_after_title', [ 'WP_Privacy_Policy_Content', 'notice' ] );
	} );
}
# Отключает редактор блоков для виджетов WordPress
add_filter( 'use_widgets_block_editor', '__return_false' );

add_action('acf/init', 'my_acf_op_init');
function my_acf_op_init() {

    // Check function exists.
    if( function_exists('acf_add_options_page') ) {
        // Register options page.
        $option_page = acf_add_options_page(array(
            'page_title'    => __('Опции'),
            'menu_title'    => __('Опции'),
            'menu_slug'     => 'theme-general-settings',
            'capability'    => 'edit_posts',
            'redirect'      => false
        ));
    }
}

function formatPhoneForTel($phone) {
    // Убираем всё, кроме цифр
    $digits = preg_replace('/\D+/', '', $phone);

    if (strpos($digits, '38') === 0) {
        return $digits;
    }

    if (strpos($phone, '+') === 0) {
        return $digits;
    }
    
    return $digits;
}

function render_lang_switcher( $variant = 'desktop' ) {
    if ( ! function_exists( 'qtranxf_getSortedLanguages' ) ) {
        return;
    }

    $languages    = qtranxf_getSortedLanguages();
    $current_lang = qtranxf_getLanguage();

    if ( empty( $languages ) || count( $languages ) < 2 ) {
        return;
    }

    $wrapper_class = 'lang-drop';
    $button_class  = 'lang';

    if ( 'mobile' === $variant ) {
        $wrapper_class = 'lang-drop lang-drop--up';
        $button_class  = 'mmenu__lang';
    }
    ?>
    <div class="<?php echo esc_attr( $wrapper_class ); ?>" data-dropdown data-lang>
        <button class="<?php echo esc_attr( $button_class ); ?>" type="button" aria-haspopup="listbox" aria-expanded="false" data-dropdown-toggle>
            <span data-lang-current><?php echo esc_html( strtoupper( $current_lang ) ); ?></span>
            <img<?php echo ( 'mobile' === $variant ) ? ' loading="lazy" decoding="async"' : ''; ?> src="<?php echo esc_url( get_template_directory_uri() . '/assets/lang-caret.svg' ); ?>" width="8" height="5" alt="">
        </button>
        <ul class="lang-drop__list" role="listbox">
            <?php foreach ( $languages as $lang ) : ?>
                <?php
                $is_active = ( $lang === $current_lang );
                $url = qtranxf_convertURL( '', $lang, false, true ); ?>				
                <li>
                    <a href="<?php echo esc_url( $url ); ?>"
                       role="option"
                       aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                       class="<?php echo $is_active ? 'is-active' : ''; ?>"
                       data-lang-set="<?php echo esc_attr( strtoupper( $lang ) ); ?>">
                        <?php echo esc_html( strtoupper( $lang ) ); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
}

function get_pcmenu_card_html() {
    $viber_url   = get_field( 'viber', 'option' );
    $telegram_url = get_field( 'telegram', 'option' );
    $whatsapp_url = get_field( 'whatsapp', 'option' );   
    ob_start();
    ?>
    <div class="mmenu__card">
        <div class="mmenu__card-row">
            <div class="mm-phone" data-dropdown>
                <button class="btn btn--primary mmenu__phone" type="button" aria-expanded="false" data-dropdown-toggle>
                    <?php echo get_field('telefon_1', 'options'); ?>
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/arrow-down.svg' ); ?>" width="5" height="8" alt="" class="mm-phone__caret">
                </button>
                <ul class="mm-phone__list">
                    <?php for ($i = 1; $i <= 3; $i++) { 
                            $tel = get_field('telefon_'.$i, 'options');
			      			$tel_url = formatPhoneForTel($tel);   ?>
                            <li><a href="tel:<?php echo $tel_url;  ?>"><?php echo $tel;  ?></a></li>
					<?php } ?>                  
                </ul>
            </div>
            <?php render_lang_switcher( 'mobile' ); ?>
        </div>

        <div class="mmenu__card-row">
            <?php if ( $viber_url ) : ?>
                <a class="msg-btn msg-btn--soft" href="<?php echo esc_url( $viber_url ); ?>">
                    <img loading="lazy" decoding="async" src="<?php echo esc_url( get_template_directory_uri() . '/assets/ic-viber.svg' ); ?>" width="16" height="16" alt=""><span>Viber</span>
                </a>
            <?php endif; ?>

            <?php if ( $telegram_url ) : ?>
                <a class="msg-btn msg-btn--soft" href="<?php echo esc_url( $telegram_url ); ?>">
                    <img loading="lazy" decoding="async" src="<?php echo esc_url( get_template_directory_uri() . '/assets/ic-telegram.svg' ); ?>" width="16" height="16" alt=""><span>Telegram</span>
                </a>
            <?php endif; ?>

            <?php if ( $whatsapp_url ) : ?>
                <a class="msg-btn msg-btn--soft" href="<?php echo esc_url( $whatsapp_url ); ?>">
                    <img loading="lazy" decoding="async" src="<?php echo esc_url( get_template_directory_uri() . '/assets/ic-whatsapp.svg' ); ?>" width="16" height="16" alt=""><span>WhatsApp</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Walker, который:
 * - на глубине 0 без детей рисует <a>;
 * - на глубине 0 с детьми рисует <button data-pcsub="slug">;
 * - на глубине 1 копит пункты в буфер по каждому slug вместо вывода в общий $output.
 */
if ( ! class_exists( 'PC_Menu_Walker' ) ) {
    class PC_Menu_Walker extends Walker_Nav_Menu {

        public $top_items = array(); // [ ['title','url','slug'=>null|string,'is_close'=>bool] ]
        public $submenus  = array(); // slug => ['title' => '', 'items' => [ ['title','url'] ]]

        private $current_slug = '';

        private function get_item_slug( $item ) {
            foreach ( (array) $item->classes as $class ) {
                $class = trim( $class );
                if ( '' !== $class ) {
                    return $class;
                }
            }
            return 'sub-' . $item->ID;
        }

        public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {

            $has_children = in_array( 'menu-item-has-children', (array) $item->classes, true );
            $is_close     = in_array( 'pcmenu-close', (array) $item->classes, true );

            if ( 0 === $depth ) {

                if ( $has_children ) {
                    $slug = $this->get_item_slug( $item );

					$this->top_items[] = array(
						'title'    => $item->title,
						'url'      => $item->url,
						'slug'     => $slug,
						'is_close' => $is_close,
					);

					$this->current_slug      = $slug;
					$this->submenus[ $slug ] = array(
						'title'       => $item->title,
						'description' => $item->description, // <-- добавили
						'items'       => array(),
					);

                } else {
                    $this->current_slug = ''; // сброс, чтобы дети не улетели не туда

                    $this->top_items[] = array(
                        'title'    => $item->title,
                        'url'      => $item->url,
                        'slug'     => null,
                        'is_close' => $is_close,
                    );
                }

            } elseif ( 1 === $depth && $this->current_slug ) {

                $this->submenus[ $this->current_slug ]['items'][] = array(
                    'title' => $item->title,
                    'url'   => $item->url,
                );
            }
        }

        public function start_lvl( &$output, $depth = 0, $args = null ) {}
        public function end_lvl( &$output, $depth = 0, $args = null ) {}
        public function end_el( &$output, $item, $depth = 0, $args = null ) {}
    }
}
function get_pcmenu_data( $location ) {
    $locations = get_nav_menu_locations();

    if ( empty( $locations[ $location ] ) ) {
        return false;
    }

    $walker = new PC_Menu_Walker();

    wp_nav_menu( array(
        'theme_location' => $location,
        'container'      => false,
        'items_wrap'     => '%3$s',
        'depth'          => 2,
        'walker'         => $walker,
        'echo'           => false, // данные нам нужны только в $walker, HTML тут не используется
    ) );

    return $walker;
}
function render_pcmenu( $location = 'menu-2' ) {
    $walker = get_pcmenu_data( $location );
    if ( ! $walker ) {
        return;
    }

    $first_sub = true;

    echo '<ul class="pcmenu__main">';
    foreach ( $walker->top_items as $item ) {
       if ( $item['slug'] ) {
    $is_active = $first_sub;
    $first_sub = false;

   printf(
    '<li>
        <a href="%s" data-pcsub-link="%s">%s</a>
        <button type="button"%s data-pcsub="%s" aria-label="%s">
            <svg width="22" height="22"><use href="#i-chevron"/></svg>
        </button>
    </li>',
    esc_url( $item['url'] ),
    esc_attr( $item['slug'] ),
    esc_html( $item['title'] ),
    $is_active ? ' class="is-active"' : '',
    esc_attr( $item['slug'] ),
    esc_attr( $item['title'] )
);

    $walker->submenus[ $item['slug'] ]['active'] = $is_active;
        } else {
            printf(
                '<li><a href="%s"%s>%s</a></li>',
                esc_url( $item['url'] ),
                $item['is_close'] ? ' data-pcmenu-close' : '',
                esc_html( $item['title'] )
            );
        }
    }
    echo '</ul>';

    foreach ( $walker->submenus as $slug => $data ) {
        printf(
            '<ul class="pcmenu__sub%s" data-pcsub-list="%s">',
            ! empty( $data['active'] ) ? ' is-active' : '',
            esc_attr( $slug )
        );

        foreach ( $data['items'] as $child ) {
            printf(
                '<li><a href="%s">%s</a></li>',
                esc_url( $child['url'] ),
                esc_html( $child['title'] )
            );
        }

        echo '</ul>';
    }
}

function render_pcmenu_mobile( $location = 'menu-2', $card_html = '' ) {
    $walker = get_pcmenu_data( $location );
    if ( ! $walker ) {
        return;
    }

    ?>
    <div class="mmenu" data-mmenu aria-hidden="true">

        <div class="mmenu__panel is-active" data-panel="main">
            <div class="mmenu__head">
                <img loading="lazy" decoding="async" src="<?php echo get_template_directory_uri();  ?>/assets/logo-white2.png" width="148" height="33" alt="Спецзнак">
                <button class="mmenu__icon mmenu__icon--border" type="button" aria-label="Пошук"><svg width="18" height="18"><use href="#i-search"/></svg></button>
                <button class="mmenu__close" type="button" aria-label="Закрити меню" data-menu-close><svg width="18" height="18"><use href="#i-close"/></svg></button>
            </div>

            <ul class="mmenu__list mmenu__list--upper">
                <?php foreach ( $walker->top_items as $item ) : ?>
                    <?php if ( $item['slug'] ) : ?>
                        <li>
                            <button type="button" data-open-panel="<?php echo esc_attr( $item['slug'] ); ?>">
                                <?php echo esc_html( $item['title'] ); ?><svg width="22" height="22"><use href="#i-chevron"/></svg>
                            </button>
                        </li>
                    <?php else : ?>
                        <li>
                            <a href="<?php echo esc_url( $item['url'] ); ?>"<?php echo $item['is_close'] ? ' data-menu-close' : ''; ?>>
                                <?php echo esc_html( $item['title'] ); ?><svg width="22" height="22"><use href="#i-chevron"/></svg>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <?php echo $card_html; ?>
        </div>

        <?php foreach ( $walker->submenus as $slug => $data ) : ?>
            <div class="mmenu__panel" data-panel="<?php echo esc_attr( $slug ); ?>">
                <div class="mmenu__head mmenu__head--sub">
                    <button class="mmenu__icon mmenu__icon--border" type="button" aria-label="Назад" data-back>
                        <svg width="18" height="18" style="transform:scaleX(-1)"><use href="#i-chevron"/></svg>
                    </button>
                    <span class="mmenu__title">
                        <?php
                        echo ! empty( $data['description'] )
                            ? wp_kses_post( $data['description'] )
                            : esc_html( $data['title'] );
                        ?>
                    </span>
                    <button class="mmenu__close" type="button" aria-label="Закрити меню" data-menu-close>
                        <svg width="18" height="18"><use href="#i-close"/></svg>
                    </button>
                </div>

                <ul class="mmenu__list">
                    <?php foreach ( $data['items'] as $child ) : ?>
                        <li>
                            <a href="<?php echo esc_url( $child['url'] ); ?>">
                                <?php echo esc_html( $child['title'] ); ?><svg width="22" height="22"><use href="#i-chevron"/></svg>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>

    </div>
    <?php
}

if ( ! function_exists( 'bst_pagination' ) ) {
	function bst_pagination() {
		global $wp_query;
		$big = 999999999; // This needs to be an unlikely integer
		// For more options and info view the docs for paginate_links()
		// http://codex.wordpress.org/Function_Reference/paginate_links
		$paginate_links = paginate_links( array(
			'base' => str_replace( $big, '%#%', get_pagenum_link($big) ),
			'current' => max( 1, get_query_var('paged') ),
			'total' => $wp_query->max_num_pages,
			'mid_size' => 5,
			'prev_next' => True,
			'prev_text' => __('<'),
			'next_text' => __('>'),
			'type' => 'list'
		) );
		$paginate_links = str_replace( "<ul class='page-numbers'>", "<ul class='pagination'>", $paginate_links );
		$paginate_links = str_replace( "<li><span class='page-numbers current'>", "<li class='active'><a href='#'>", $paginate_links );
		$paginate_links = str_replace( "</span>", "</a>", $paginate_links );
		$paginate_links = preg_replace( "/\s*page-numbers/", "", $paginate_links );
		// Display the pagination if more than one page is found
		if ( $paginate_links ) {
			echo $paginate_links;
		}
	}
}

/***  Woocommerce   */

add_action( 'wp', function () {
    if ( ! is_product() ) return;

    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
}, 20 );

/**
 * WooCommerce product categories without /product-category/
 *
 * /product-category/avto/
 *      ↓
 * /avto/
 *
 * /product-category/avto/ramki/
 *      ↓
 * /avto/ramki/
 */

add_filter( 'term_link', 'custom_wc_product_cat_link', 10, 3 );

function custom_wc_product_cat_link( $url, $term, $taxonomy ) {

    if ( 'product_cat' !== $taxonomy ) {
        return $url;
    }

    // Получаем полный путь категории с родителями
    $ancestors = get_ancestors( $term->term_id, 'product_cat' );

    if ( ! empty( $ancestors ) ) {
        $ancestors = array_reverse( $ancestors );

        $slugs = array();

        foreach ( $ancestors as $ancestor_id ) {
            $ancestor = get_term( $ancestor_id, 'product_cat' );

            if ( $ancestor && ! is_wp_error( $ancestor ) ) {
                $slugs[] = $ancestor->slug;
            }
        }

        $slugs[] = $term->slug;

    } else {
        $slugs = array( $term->slug );
    }

    return home_url( '/' . implode( '/', $slugs ) . '/' );
}


/**
 * Rewrite rules for WooCommerce product categories
 */
add_action( 'init', 'custom_wc_product_cat_rewrite_rules', 20 );

function custom_wc_product_cat_rewrite_rules() {

    $categories = get_terms( array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
    ) );

    if ( empty( $categories ) || is_wp_error( $categories ) ) {
        return;
    }

    foreach ( $categories as $category ) {

        $ancestors = get_ancestors(
            $category->term_id,
            'product_cat'
        );

        $ancestors = array_reverse( $ancestors );

        $slugs = array();

        foreach ( $ancestors as $ancestor_id ) {

            $ancestor = get_term(
                $ancestor_id,
                'product_cat'
            );

            if ( $ancestor && ! is_wp_error( $ancestor ) ) {
                $slugs[] = $ancestor->slug;
            }
        }

        $slugs[] = $category->slug;

        $path = implode( '/', $slugs );

        add_rewrite_rule(
            '^' . $path . '/?$',
            'index.php?product_cat=' . $category->slug,
            'top'
        );
    }
}

function pdp_lang( $text ) {
    if ( function_exists( 'qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage' ) ) {
        return qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage( $text );
    }
    if ( function_exists( 'qtranxf_use' ) ) {
        return qtranxf_use( qtranxf_getLanguage(), $text, false, false );
    }
    return $text;
}

add_action( 'woocommerce_before_shop_loop_item_title', function () {
    $badge = function_exists( 'get_field' ) ? get_field( 'product_badge' ) : '';
    if ( ! $badge ) return;
    $labels = [ 'top' => 'Топ', 'new' => 'Новинка', 'hit' => 'Хіт' ];
    if ( isset( $labels[ $badge ] ) ) {
        printf( '<span class="pdp-badge pdp-badge--%s">%s</span>', esc_attr( $badge ), esc_html( $labels[ $badge ] ) );
    }
}, 9 );

function pdp_pager() {
    global $wp_query;
    $total = (int) $wp_query->max_num_pages;
    if ( $total < 2 ) return;

    $cur   = max( 1, (int) get_query_var( 'paged' ) );
    $pages = array_unique( array_filter(
        [ 1, $cur - 1, $cur, $cur + 1, $total ],
        fn( $n ) => $n >= 1 && $n <= $total
    ) );
    sort( $pages );

    $arrow_prev = '<svg width="20" height="20" viewBox="0 0 20 20" aria-hidden="true"><path d="M16 10H4m0 0 5-5m-5 5 5 5" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    $arrow_next = '<svg width="20" height="20" viewBox="0 0 20 20" aria-hidden="true"><path d="M4 10h12m0 0-5-5m5 5-5 5" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    $t_prev = qtranxf_useCurrentLanguageIfNotFoundShowAvailable( '[:ua]Попередня[:ru]Предыдущая' );
    $t_next = qtranxf_useCurrentLanguageIfNotFoundShowAvailable( '[:ua]Наступна[:ru]Следующая' );
    ?>
    <nav class="pager" aria-label="Сторінки каталогу">
      <?php if ( $cur > 1 ) : ?>
        <a class="pager__nav pager__prev" href="<?php echo esc_url( get_pagenum_link( $cur - 1 ) ); ?>" aria-label="<?php echo esc_attr( $t_prev ); ?>"><?php echo $arrow_prev; ?><span><?php echo esc_html( $t_prev ); ?></span></a>
      <?php else : ?>
        <span class="pager__nav pager__prev is-disabled" aria-disabled="true"><?php echo $arrow_prev; ?><span><?php echo esc_html( $t_prev ); ?></span></span>
      <?php endif; ?>

      <ul class="pager__list">
        <?php $prev = 0; foreach ( $pages as $n ) :
            if ( $prev && $n - $prev > 1 ) echo '<li><span>...</span></li>';
            $prev = $n; ?>
          <li><a<?php echo $n === $cur ? ' class="is-active" aria-current="page"' : ''; ?> href="<?php echo esc_url( get_pagenum_link( $n ) ); ?>"><?php echo (int) $n; ?></a></li>
        <?php endforeach; ?>
      </ul>

      <?php if ( $cur < $total ) : ?>
        <a class="pager__nav pager__next" href="<?php echo esc_url( get_pagenum_link( $cur + 1 ) ); ?>" aria-label="<?php echo esc_attr( $t_next ); ?>"><span><?php echo esc_html( $t_next ); ?></span><?php echo $arrow_next; ?></a>
      <?php else : ?>
        <span class="pager__nav pager__next is-disabled" aria-disabled="true"><span><?php echo esc_html( $t_next ); ?></span><?php echo $arrow_next; ?></span>
      <?php endif; ?>
    </nav>
    <?php
}

add_action( 'init', function () {
    $tops = get_terms( [
        'taxonomy'   => 'product_cat',
        'parent'     => 0,
        'hide_empty' => false,
        'fields'     => 'slugs',
    ] );
    if ( is_wp_error( $tops ) ) return;

    foreach ( $tops as $slug ) {
        $q = preg_quote( $slug, '#' );

        add_rewrite_rule(
            '^' . $q . '/page/([0-9]+)/?$',
            'index.php?product_cat=' . $slug . '&paged=$matches[1]',
            'top'
        );

        add_rewrite_rule(
            '^' . $q . '/(?:.+?/)?([^/]+)/page/([0-9]+)/?$',
            'index.php?product_cat=$matches[1]&paged=$matches[2]',
            'top'
        );
    }
}, 20 );

// пересоздаём правила при изменении категорий
foreach ( [ 'created_product_cat', 'edited_product_cat', 'delete_product_cat' ] as $hook ) {
    add_action( $hook, fn() => update_option( 'pdp_flush_rewrite', 1 ) );
}
add_action( 'init', function () {
    if ( get_option( 'pdp_flush_rewrite' ) ) {
        flush_rewrite_rules();
        delete_option( 'pdp_flush_rewrite' );
    }
}, 99 );

add_filter( 'loop_shop_per_page', 'custom_loop_shop_per_page', 20 );

function custom_loop_shop_per_page( $products_per_page ) {
     return 16; 
}

add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
    ob_start();
    ?>
    <span class="header__cart-count" data-cart-count><?php echo (int) WC()->cart->get_cart_contents_count(); ?></span>
    <?php
    $fragments['span.header__cart-count'] = ob_get_clean();
    return $fragments;
} );

// поле в админке вариации
add_action( 'woocommerce_variation_options_pricing', function ( $loop, $data, $variation ) {
    woocommerce_wp_text_input( [
        'id'                => "_signs[$loop]",
        'name'              => "_signs[$loop]",
        'label'             => 'Кількість знаків',
        'type'              => 'number',
        'value'             => get_post_meta( $variation->ID, '_signs', true ) ?: 1,
        'custom_attributes' => [ 'min' => 1, 'step' => 1 ],
        'wrapper_class'     => 'form-row form-row-full',
    ] );
}, 10, 3 );

add_action( 'woocommerce_save_product_variation', function ( $id, $i ) {
    if ( isset( $_POST['_signs'][ $i ] ) ) {
        update_post_meta( $id, '_signs', max( 1, (int) $_POST['_signs'][ $i ] ) );
    }
}, 10, 2 );

// отдаём в шаблон
add_filter( 'woocommerce_available_variation', function ( $data, $product, $variation ) {
    $data['signs'] = max( 1, (int) get_post_meta( $variation->get_id(), '_signs', true ) );
    return $data;
}, 10, 3 );

add_filter( 'woocommerce_add_to_cart_redirect', function ( $url ) {
    if ( $url ) {
        return $url; // например, включён редирект на корзину в настройках Woo
    }
    $id = isset( $_REQUEST['add-to-cart'] ) ? absint( $_REQUEST['add-to-cart'] ) : 0;
    return $id ? get_permalink( $id ) : wc_get_page_permalink( 'shop' );
}, 99 );

add_action( 'template_redirect', function () {
    if ( ! isset( $_GET['pdp_clear'] ) || ! is_cart() ) return;
    if ( ! wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'pdp_clear' ) ) return;

    WC()->cart->empty_cart();
    wp_safe_redirect( wc_get_cart_url() );
    exit;
} );

add_filter( 'woocommerce_add_to_cart_validation', function ( $ok, $product_id, $qty, $variation_id = 0 ) {
    if ( ! $variation_id || ! WC()->cart ) return $ok;   // только вариативные товары

    foreach ( WC()->cart->get_cart() as $item ) {
        if ( (int) $item['product_id'] === (int) $product_id ) {
            wc_add_notice(
                qtranxf_useCurrentLanguageIfNotFoundShowAvailable(
                    '[:ua]Цей товар вже в кошику. На один автомобіль можна замовити не більше 2 знаків.[:ru]Этот товар уже в корзине. На один автомобиль можно заказать не более 2 знаков.'
                ),
                'error'
            );
            return false;
        }
    }
    return $ok;
}, 10, 4 );

add_action( 'wp_loaded', function () {
    if ( empty( $_POST['cart'] ) || ! is_array( $_POST['cart'] ) ) return;
    if ( ! wp_verify_nonce( wp_unslash( $_POST['woocommerce-cart-nonce'] ?? '' ), 'woocommerce-cart' ) ) return;
    if ( ! WC()->cart ) wc_load_cart();

    $contents = WC()->cart->get_cart();   // загружает корзину из сессии
    $_POST['update_cart'] = 1;
    $changed = false;

    foreach ( $_POST['cart'] as $key => $row ) {
        $item = $contents[ $key ] ?? null;
        error_log( 'CARTUPD key=' . $key . ' found=' . var_export( (bool) $item, true )
            . ' vid=' . ( $item['variation_id'] ?? 'none' ) );
        if ( ! $item || empty( $item['variation_id'] ) ) continue;

        $want = max( 1, min( 2, (int) ( $row['qty'] ?? 1 ) ) );
        $cur  = max( 1, (int) get_post_meta( $item['variation_id'], '_signs', true ) );
        error_log( "CARTUPD want=$want cur=$cur" );

        if ( $want === $cur ) {
            $_POST['cart'][ $key ]['qty'] = $cur;
            continue;
        }

        $target = 0;
        foreach ( wc_get_product( $item['product_id'] )->get_children() as $vid ) {
    $s = max( 1, (int) get_post_meta( $vid, '_signs', true ) );
    if ( $s === $want ) { $target = $vid; break; }
}
        if ( ! $target ) {
            $_POST['cart'][ $key ]['qty'] = $cur;
            continue;
        }

        $attrs = wc_get_product( $target )->get_variation_attributes();
        WC()->cart->remove_cart_item( $key );
        $new = WC()->cart->add_to_cart( $item['product_id'], $want, $target, $attrs );
        unset( $_POST['cart'][ $key ] );
        $changed = true;
    }

    if ( $changed && empty( $_POST['cart'] ) ) {
        wp_safe_redirect( wc_get_cart_url() );
        exit;
    }
}, 15 );


/* ---------- хелперы ---------- */
function pdp_t( $s ) {
    return function_exists( 'qtranxf_useCurrentLanguageIfNotFoundShowAvailable' ) ? qtranxf_useCurrentLanguageIfNotFoundShowAvailable( $s ) : $s;
}

// какие способы оплаты доступны при каждой доставке (ID методов поправьте под свои)
function pdp_pay_rules() {
    $rules = [];
    $all   = function_exists( 'WC' ) && WC()->payment_gateways() ? array_keys( WC()->payment_gateways()->payment_gateways() ) : [];

    if ( function_exists( 'get_field' ) ) {
        foreach ( (array) get_field( 'co_delivery', 'option' ) as $row ) {
            if ( empty( $row['key'] ) ) continue;
            $pay = array_values( array_map( 'strval', (array) ( $row['pay'] ?? [] ) ) );
            $rules[ $row['key'] ] = $pay ?: $all;   // ничего не отмечено = доступны все
        }
    }

    // запасной вариант, если повторитель пуст
    return apply_filters( 'pdp_pay_rules', $rules ?: [
        'pickup'  => [ 'cheque', 'wayforpay' ],
        'np'      => [ 'cod', 'wayforpay' ],
        'courier' => [ 'cod', 'wayforpay' ],
    ] );
}
function pdp_doc_labels() {
    return [
        'license_1' => 'Посвідчення водія, 1 сторона', 'license_2' => 'Посвідчення водія, 2 сторона',
        'reg_1'     => 'Свідоцтво про реєстрацію, 1 сторона', 'reg_2' => 'Свідоцтво про реєстрацію, 2 сторона',
    ];
}

function pdp_docs_dir() {
    $dir = wp_upload_dir()['basedir'] . '/co-docs';
    if ( ! is_dir( $dir ) ) {
        wp_mkdir_p( $dir );
        file_put_contents( $dir . '/.htaccess', "<IfModule mod_authz_core.c>\nRequire all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\nDeny from all\n</IfModule>\n" );
        file_put_contents( $dir . '/index.html', '' );
    }
    return $dir;
}

/* ---------- убираем лишнее из стандартного checkout ---------- */
add_action( 'wp', function () {
    remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
    remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
} );


add_filter( 'woocommerce_checkout_fields', function ( $f ) {
    $f['billing'] = array_intersect_key( $f['billing'], array_flip( [ 'billing_first_name', 'billing_last_name', 'billing_phone' ] ) );
    $f['billing']['billing_first_name']['required'] = true;
    $f['billing']['billing_last_name']['required']  = false;
    $f['billing']['billing_phone']['required']      = true;
    unset( $f['shipping'], $f['account'] );
    return $f;
} );

/* ---------- быстрый режим: оплата по умолчанию ---------- */
add_filter( 'woocommerce_checkout_posted_data', function ( $d ) {
    if ( ( $_POST['order_mode'] ?? '' ) === 'quick' ) {
        // швидке замовлення: тільки ім'я + телефон, без онлайн-оплати — обираємо доступний офлайн-спосіб
        $d['payment_method']    = pdp_quick_gateway();
        $d['billing_last_name'] = '';
    }
    return $d;
} );
function pdp_quick_gateway() {
    $avail = WC()->payment_gateways() ? WC()->payment_gateways()->get_available_payment_gateways() : [];
    foreach ( [ 'cheque', 'cod', 'bacs' ] as $id ) {          // оплата при отриманні / без передоплати
        if ( isset( $avail[ $id ] ) ) return $id;
    }
    $rules = pdp_pay_rules();
    foreach ( (array) ( $rules['pickup'] ?? [] ) as $id ) {   // запасний варіант: будь-який не онлайн-спосіб
        if ( isset( $avail[ $id ] ) && false === stripos( $id, 'wayforpay' ) && false === stripos( $id, 'liqpay' ) ) return $id;
    }
    return 'cheque';
}

/* ---------- серверная проверка (детальный режим) ---------- */
add_action( 'woocommerce_after_checkout_validation', function ( $data, $errors ) {
    if ( ( $_POST['order_mode'] ?? 'full' ) === 'quick' ) return;

    $delivery = sanitize_key( $_POST['delivery'] ?? 'pickup' );
    $rules    = pdp_pay_rules();
    if ( ! isset( $rules[ $delivery ] ) ) {
        $errors->add( 'delivery', pdp_t( '[:ua]Оберіть спосіб доставки.[:ru]Выберите способ доставки.' ) );
        return;
    }
    if ( ! in_array( $data['payment_method'], $rules[ $delivery ], true ) ) {
        $errors->add( 'payment', pdp_t( '[:ua]Цей спосіб оплати недоступний для обраної доставки.[:ru]Этот способ оплаты недоступен для выбранной доставки.' ) );
    }
    if ( trim( $data['billing_last_name'] ) === '' ) {   // прізвище обов'язкове для всіх способів доставки
        $errors->add( 'last_name', pdp_t( '[:ua]Вкажіть прізвище одержувача.[:ru]Укажите фамилию получателя.' ) );
    }
}, 10, 2 );

/* ---------- сохранение данных в заказ ---------- */
add_action( 'woocommerce_checkout_create_order', function ( $order ) {
    $quick = ( $_POST['order_mode'] ?? '' ) === 'quick';
    $order->update_meta_data( '_order_mode', $quick ? 'quick' : 'full' );
    if ( $quick ) return;

    $delivery = sanitize_key( $_POST['delivery'] ?? 'pickup' );
    $order->update_meta_data( '_co_delivery', $delivery );
    if ( $delivery === 'pickup' ) {
        $order->update_meta_data( '_co_office', sanitize_text_field( wp_unslash( $_POST['office'] ?? '' ) ) );
    }
    $order->update_meta_data( '_co_no_call', empty( $_POST['no_call'] ) ? 'no' : 'yes' );

    foreach ( array_keys( pdp_doc_labels() ) as $k ) {
        $file = basename( sanitize_file_name( wp_unslash( $_POST[ "doc_$k" ] ?? '' ) ) );
        if ( $file && is_file( pdp_docs_dir() . '/' . $file ) ) {
            $order->update_meta_data( "_doc_$k", $file );
        }
    }
} );

/* ---------- загрузка фото (AJAX) ---------- */
add_action( 'wp_ajax_co_upload', 'pdp_co_upload' );
add_action( 'wp_ajax_nopriv_co_upload', 'pdp_co_upload' );
function pdp_co_upload() {
    check_ajax_referer( 'co_upload', 'nonce' );
    $f = $_FILES['file'] ?? null;
    if ( ! $f || $f['error'] !== UPLOAD_ERR_OK || $f['size'] > 10 * MB_IN_BYTES ) wp_send_json_error( 'bad file' );

    $ft = wp_check_filetype_and_ext( $f['tmp_name'], $f['name'], [ 'jpg|jpeg' => 'image/jpeg', 'png' => 'image/png' ] );
    if ( empty( $ft['type'] ) || empty( $ft['ext'] ) ) wp_send_json_error( 'bad type' );

    $name = wp_generate_password( 32, false ) . '.' . $ft['ext'];
    if ( ! move_uploaded_file( $f['tmp_name'], pdp_docs_dir() . '/' . $name ) ) wp_send_json_error( 'save failed' );
    wp_send_json_success( [ 'id' => $name ] );
}

/* ---------- заказ в админке: тип, доставка, ссылки на фото ---------- */
add_action( 'woocommerce_admin_order_data_after_billing_address', function ( $order ) {
    $mode = $order->get_meta( '_order_mode' );
    if ( ! $mode ) return;
    echo '<p><strong>Тип замовлення:</strong> ' . ( $mode === 'quick' ? 'швидке' : 'детальне' ) . '</p>';
    if ( $mode === 'quick' ) return;

    $names = [ 'pickup' => 'Самовивіз', 'np' => 'Нова Пошта — відділення', 'courier' => 'Курʼєр Нової Пошти' ];
    $d     = $order->get_meta( '_co_delivery' );
    echo '<p><strong>Доставка:</strong> ' . esc_html( $names[ $d ] ?? $d ) . '</p>';
    if ( $order->get_meta( '_co_office' ) ) echo '<p><strong>Офіс:</strong> ' . esc_html( $order->get_meta( '_co_office' ) ) . '</p>';
    echo '<p><strong>Дзвінок клієнту:</strong> ' . esc_html( pdp_no_call_label( $order ) ) . '</p>';

    foreach ( pdp_doc_labels() as $k => $label ) {
        if ( $order->get_meta( "_doc_$k" ) ) {
            $url = admin_url( 'admin-post.php?action=co_doc&order=' . $order->get_id() . '&k=' . $k );
            echo '<p><a href="' . esc_url( $url ) . '" target="_blank">' . esc_html( $label ) . '</a></p>';
        }
    }
} );

/* ---------- «Не дзвоніть мені»: текст, колонка у списку замовлень, лист адміну ---------- */
function pdp_no_call_label( $order ) {
    $v = $order->get_meta( '_co_no_call' );
    if ( $v === '' ) return '';
    return $v === 'yes' ? '❌ Не дзвонити — писати у Viber' : '📞 Можна дзвонити';
}

$pdp_no_call_col = function ( $cols ) {
    $cols['co_no_call'] = 'Дзвінок';
    return $cols;
};
add_filter( 'manage_edit-shop_order_columns', $pdp_no_call_col );              // класичний список
add_filter( 'manage_woocommerce_page_wc-orders_columns', $pdp_no_call_col );   // HPOS

$pdp_no_call_cell = function ( $col, $order ) {
    if ( $col !== 'co_no_call' ) return;
    $order = is_a( $order, 'WC_Order' ) ? $order : wc_get_order( $order );
    $v     = $order ? $order->get_meta( '_co_no_call' ) : '';
    echo $v === 'yes' ? '❌ Не дзвонити' : ( $v === 'no' ? '📞 Дзвонити' : '—' );
};
add_action( 'manage_shop_order_posts_custom_column', $pdp_no_call_cell, 10, 2 );
add_action( 'manage_woocommerce_page_wc-orders_custom_column', $pdp_no_call_cell, 10, 2 );

add_filter( 'woocommerce_email_order_meta_fields', function ( $fields, $sent_to_admin, $order ) {
    $label = pdp_no_call_label( $order );
    if ( $sent_to_admin && $label ) $fields['co_no_call'] = [ 'label' => 'Дзвінок клієнту', 'value' => $label ];
    return $fields;
}, 10, 3 );

add_action( 'admin_post_co_doc', function () {
    if ( ! current_user_can( 'manage_woocommerce' ) ) wp_die( '403', '', 403 );
    $k     = sanitize_key( $_GET['k'] ?? '' );
    $order = wc_get_order( absint( $_GET['order'] ?? 0 ) );
    if ( ! $order || ! isset( pdp_doc_labels()[ $k ] ) ) wp_die( '404', '', 404 );

    $path = pdp_docs_dir() . '/' . basename( (string) $order->get_meta( "_doc_$k" ) );
    if ( ! is_file( $path ) ) wp_die( '404', '', 404 );
    header( 'Content-Type: ' . wp_check_filetype( $path )['type'] );
    readfile( $path );
    exit;
} );

/* ---------- подключение JS ---------- */
add_action( 'wp_enqueue_scripts', function () {
    if ( ! is_checkout() || is_wc_endpoint_url( 'order-received' ) ) return;
    $file = get_stylesheet_directory() . '/assets/js/checkout.js';   // путь поправьте под свою структуру
    wp_enqueue_script( 'pdp-checkout', get_stylesheet_directory_uri() . '/assets/js/checkout.js', [ 'jquery' ], filemtime( $file ), true );
    wp_localize_script( 'pdp-checkout', 'pdpCheckout', [
        'ajax'  => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'co_upload' ),
        'i18n'  => [
            'loading' => pdp_t( '[:ua]Завантаження…[:ru]Загрузка…' ),
            'bad'     => pdp_t( '[:ua]Лише JPG/PNG до 10 МБ[:ru]Только JPG/PNG до 10 МБ' ),
            'err'     => pdp_t( '[:ua]Помилка, спробуйте ще[:ru]Ошибка, попробуйте ещё' ),
        ],
    ] );
} );

add_action( 'woocommerce_email_after_order_table', function ( $order, $sent_to_admin ) {
    if ( ! $sent_to_admin ) return;   // клиенту не показываем

    $out = '';
    foreach ( pdp_doc_labels() as $k => $label ) {
        if ( $order->get_meta( "_doc_$k" ) ) {
            $url  = admin_url( 'admin-post.php?action=co_doc&order=' . $order->get_id() . '&k=' . $k );
            $out .= '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
        }
    }
    if ( $out ) echo '<h3>Фото документів</h3><ul>' . $out . '</ul>';
}, 10, 2 );

add_filter( 'acf/load_field/name=pay', function ( $field ) {
    if ( ! is_admin() || ! function_exists( 'WC' ) || ! WC()->payment_gateways() ) return $field;
    $field['choices'] = [];
    foreach ( WC()->payment_gateways()->payment_gateways() as $g ) {
        if ( $g->enabled !== 'yes' ) continue;
        $field['choices'][ $g->id ] = pdp_lang( $g->get_title() ) . ' (' . $g->id . ')';
    }
    return $field;
} );

add_action( 'init', function () {
    remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
    remove_action( 'woocommerce_thankyou_cod', 'wc_gateway_cod_thankyou_page' );   // см. примечание
} );

add_action( 'woocommerce_thankyou_cod', function () {
    $gw = WC()->payment_gateways()->payment_gateways()['cod'] ?? null;
    if ( $gw ) remove_action( 'woocommerce_thankyou_cod', [ $gw, 'thankyou_page' ] );
}, 1 );
/* ---------- форма «Заявка» (CF7): кнопки месенджерів — тег [sz_messengers] ----------
 * Посилання з ACF «Опції»: viber / telegram / whatsapp.
 * Клік по кнопці — одразу перехід у месенджер (без вибору й полів для username/номера).
 */
add_action( 'wpcf7_init', function () {
    wpcf7_add_form_tag( 'sz_messengers', function () {
        $items = [ 'viber' => 'Viber', 'telegram' => 'Telegram', 'whatsapp' => 'WhatsApp' ];

        $html = '<span class="wpcf7-form-control msg-choice msg-choice--links">';
        foreach ( $items as $key => $name ) {
            $url = function_exists( 'get_field' ) ? trim( (string) get_field( $key, 'option' ) ) : '';
            if ( ! $url ) continue;
            $html .= sprintf(
                '<span class="wpcf7-list-item"><a class="msg-choice__link msg-choice__link--%1$s" href="%2$s" target="_blank" rel="noopener nofollow"><span class="wpcf7-list-item-label">%3$s</span></a></span>',
                esc_attr( $key ),
                esc_url( $url, [ 'http', 'https', 'viber', 'tg', 'whatsapp' ] ),
                esc_html( $name )
            );
        }
        return $html . '</span>';
    } );
} );

add_action( 'woocommerce_before_checkout_form', function () {
    if ( ! WC()->session || WC()->session->get( 'pdp_ship_init' ) ) return;
    foreach ( WC()->shipping()->get_packages() as $p ) {
        foreach ( array_keys( $p['rates'] ?? [] ) as $id ) {
            if ( strpos( $id, 'local_pickup' ) === 0 ) {
                WC()->session->set( 'chosen_shipping_methods', [ $id ] );
            }
        }
    }
    WC()->session->set( 'pdp_ship_init', 1 );
} );
/* ==========================================================
   Спецзнак — 3D-прелоадер (добавить в functions.php темы)
   Файлы: /js/sz-preloader.js (стили — в css/style.css)
   ========================================================== */

define( 'SZPL_ONCE', false ); // true — показывать один раз за сессию (для продакшна)

function szpl_enabled() {
	return is_front_page() && ! is_admin() && ! wp_doing_ajax()
		&& file_exists( get_template_directory() . '/js/sz-preloader.js' );
}

function szpl_logo_url() {
	return content_url( '/uploads/2026/10/' . rawurlencode( 'kruglыj-logotyp-sz-s-synym-gradyentom-2.png' ) );
}

/* критичный CSS + блокировка скролла — до отрисовки страницы */
add_action( 'wp_head', function () {
	if ( ! szpl_enabled() ) return; ?>
<style id="szpl-critical">html.szpl-lock,html.szpl-lock body{overflow:hidden}.szpl{position:fixed;inset:0;z-index:10000;animation:szplKill .3s 5s forwards}.szpl__bg{position:absolute;inset:0;background:#05080d}@keyframes szplKill{to{opacity:0;visibility:hidden}}html.szpl-skip .szpl{display:none}</style>
<script>(function(d){var h=d.documentElement;try{if(<?php echo SZPL_ONCE ? 'true' : 'false'; ?>&&sessionStorage.getItem('szpl')){h.classList.add('szpl-skip');return}sessionStorage.setItem('szpl','1')}catch(e){}h.classList.add('szpl-lock');setTimeout(function(){h.classList.remove('szpl-lock')},5500)})(document);</script>
<?php }, 1 );

/* разметка — сразу после <body> */
add_action( 'wp_body_open', function () {
	if ( ! szpl_enabled() ) return;
	$logo  = szpl_logo_url();
	$chars = mb_str_split( 'СПЕЦЗНАК' ); ?>
<div id="szpl" class="szpl" data-logo="<?php echo esc_attr( $logo ); ?>" aria-hidden="true">
	<div class="szpl__bg"></div>
	<img class="szpl__fallback" src="<?php echo esc_attr( $logo ); ?>" alt="" width="156" height="156">
	<div class="szpl__brand">
		<div class="szpl__name"><?php foreach ( $chars as $i => $c ) echo '<span style="--i:' . (int) $i . '">' . esc_html( $c ) . '</span>'; ?></div>
		<div class="szpl__bar"><i></i></div>
	</div>
</div>
<?php }, 1 );

/* подключение файлов */
add_action( 'wp_enqueue_scripts', function () {
	if ( ! szpl_enabled() ) return;
	$uri = get_template_directory_uri();
	$dir = get_template_directory();
	wp_enqueue_script( 'szpl-three', 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js', [], 'r128', [ 'strategy' => 'defer', 'in_footer' => false ] );
	// свечение (bloom): модули постобработки Three.js одним запросом
	wp_enqueue_script( 'szpl-three-fx', 'https://cdn.jsdelivr.net/combine/npm/three@0.128.0/examples/js/shaders/CopyShader.js,npm/three@0.128.0/examples/js/shaders/LuminosityHighPassShader.js,npm/three@0.128.0/examples/js/postprocessing/EffectComposer.js,npm/three@0.128.0/examples/js/postprocessing/RenderPass.js,npm/three@0.128.0/examples/js/postprocessing/ShaderPass.js,npm/three@0.128.0/examples/js/postprocessing/UnrealBloomPass.js', [ 'szpl-three' ], 'r128', [ 'strategy' => 'defer', 'in_footer' => false ] );
	$js = $dir . '/js/sz-preloader.js';
	if ( ! file_exists( $js ) ) return; // нет файла — прелоадер просто не подключается, без ошибок
	wp_enqueue_script( 'sz-preloader', $uri . '/js/sz-preloader.js', [ 'szpl-three', 'szpl-three-fx' ], filemtime( $js ), [ 'strategy' => 'defer', 'in_footer' => false ] );
} );

/* класс текущего языка на <body> (lang-ru / lang-ua) — для стилей под конкретную версию */
add_filter( 'body_class', function ( $classes ) {
	if ( function_exists( 'qtranxf_getLanguage' ) ) {
		$classes[] = 'lang-' . sanitize_html_class( qtranxf_getLanguage() );
	}
	return $classes;
} );

/* RU: в заголовке FAQ «Распространены/Распространенные» → «Частые» (на всех страницах, без правки каждой в админке) */
add_filter( 'acf/format_value/name=faq_zagolovok', function ( $value ) {
	if ( ! is_string( $value ) ) return $value;
	return preg_replace_callback( '/распростран[её]нн?ы[ея]?/iu', function ( $m ) {
		$w = $m[0];
		if ( mb_strtoupper( $w, 'UTF-8' ) === $w ) return 'ЧАСТЫЕ';               // всё заглавными
		$first = mb_substr( $w, 0, 1, 'UTF-8' );
		return ( mb_strtoupper( $first, 'UTF-8' ) === $first ) ? 'Частые' : 'частые'; // с заглавной / строчными
	}, $value );
}, 99 );

/* ---------- формы (CF7): ссылка «Політики конфіденційності / Политики конфиденциальности» → страница политики на текущем языке ---------- */
function sz_privacy_url() {
	$lang = function_exists( 'qtranxf_getLanguage' ) ? qtranxf_getLanguage() : 'ua';
	return 'ru' === $lang
		? site_url( '/ru/polityka-konfidenczijnosti/' )
		: site_url( '/polityka-konfidenczijnosti/' );
}
function sz_fix_privacy_links( $html ) {
	if ( ! is_string( $html ) || stripos( $html, '<a' ) === false ) return $html;
	return preg_replace_callback( '~<a\b([^>]*)>(.*?)</a>~isu', function ( $m ) {
		if ( ! preg_match( '~конфіденційн|конфиденциальн~iu', wp_strip_all_tags( $m[2] ) ) ) return $m[0];
		$attrs = preg_replace( '~\s(href|target|rel)\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)~iu', '', $m[1] );
		return '<a' . $attrs . ' href="' . esc_url( sz_privacy_url() ) . '" target="_blank" rel="noopener">' . $m[2] . '</a>';
	}, $html );
}
add_filter( 'wpcf7_form_elements', 'sz_fix_privacy_links', 20 );

/* то же для ссылок вне CF7 (попапы в шаблонах): адрес политики для скрипта в main.js */
add_action( 'wp_footer', function () {
	echo '<script>window.SZ_PRIVACY_URL=' . wp_json_encode( sz_privacy_url() ) . ';</script>';
}, 1 );

/* ---------- оформлення замовлення: тексти способів оплати ---------- */
// прибрати опис «Готівкою або карткою на місці» / «Наличными или картой на месте» (UA і RU)
add_filter( 'woocommerce_gateway_description', function ( $desc ) {
	if ( ! is_string( $desc ) ) return $desc;
	// опечатка мовного тегу в налаштуваннях: «[:}» замість «[:]» — виправляємо, перекладаємо, прибираємо залишки тегів
	if ( strpos( $desc, '[:' ) !== false ) {
		$desc = str_replace( '[:}', '[:]', $desc );
		if ( function_exists( 'pdp_lang' ) && preg_match( '~\[:[a-z]{2}\]~', $desc ) ) $desc = pdp_lang( $desc );
		$desc = trim( preg_replace( '~\[:[a-z]{0,2}[\]}]?~u', '', $desc ) );
	}
	$plain = trim( wp_strip_all_tags( function_exists( 'pdp_lang' ) ? pdp_lang( $desc ) : $desc ) );
	if ( preg_match( '~^(готівкою\s+або\s+карткою\s+на\s+місці|наличными\s+или\s+картой\s+на\s+месте)\.?$~iu', $plain ) ) return '';
	return $desc;
}, 99 );
// UA: «Накладений платіж» → «Післяплата» (RU-назва не змінюється)
add_filter( 'woocommerce_gateway_title', function ( $title ) {
	if ( ! is_string( $title ) ) return $title;
	if ( strpos( $title, '[:' ) !== false ) {
		$title = str_replace( '[:}', '[:]', $title );
		if ( function_exists( 'pdp_lang' ) && preg_match( '~\[:[a-z]{2}\]~', $title ) ) $title = pdp_lang( $title );
		$title = trim( preg_replace( '~\[:[a-z]{0,2}[\]}]?~u', '', $title ) );
	}
	return preg_replace( '~накладений\s+платіж~iu', 'Післяплата', $title );
}, 99 );

/* швидке замовлення: позначка в замовленні для менеджера (після збереження замовлення) */
add_action( 'woocommerce_checkout_order_processed', function ( $order_id ) {
    $order = wc_get_order( $order_id );
    if ( $order && 'quick' === $order->get_meta( '_order_mode' ) ) {
        $order->add_order_note( 'Швидке замовлення: лише ім’я та телефон, без оплати на сайті — менеджер передзвонить.' );
    }
}, 20 );

/* ---------- каталог (категорії, магазин): порядок за артикулом 00001, 00002, … ; без артикула — в кінці ----------
 * Працює для стандартного сортування. Якщо покупець сам обрав сортування (ціна тощо) — діє його вибір. */
add_filter( 'posts_clauses', function ( $c, $q ) {
	if ( is_admin() || ! $q->is_main_query() ) return $c;
	if ( ! ( function_exists( 'is_product_category' ) && ( is_product_category() || is_shop() || is_product_tag() ) ) ) return $c;
	$ob = isset( $_GET['orderby'] ) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : '';
	if ( $ob !== '' && $ob !== 'menu_order' ) return $c;
	global $wpdb;
	$c['join']   .= " LEFT JOIN {$wpdb->wc_product_meta_lookup} sz_sku ON sz_sku.product_id = {$wpdb->posts}.ID ";
	$c['orderby'] = "( sz_sku.sku IS NULL OR sz_sku.sku = '' ) ASC, ( sz_sku.sku REGEXP '^[0-9]+$' ) DESC, CAST( sz_sku.sku AS UNSIGNED ) ASC, sz_sku.sku ASC"
		. ( $c['orderby'] ? ', ' . $c['orderby'] : '' );
	return $c;
}, 20, 2 );

/* ==========================================================
   Превью в Telegram / Facebook / Viber: в <head> (title, og:, twitter:, schema)
   показываем только текущий язык вместо «[:ua]…[:ru]…[:]»
   ========================================================== */
function sz_head_lang_fix( $html ) {
	if ( strpos( $html, '[:' ) === false ) return $html;
	$lang = function_exists( 'qtranxf_getLanguage' ) ? qtranxf_getLanguage() : 'ua';
	return preg_replace_callback( '/\[:[a-z]{2}\].*?(?:\[:\]|(?=["\'<]))/su', function ( $m ) use ( $lang ) {
		$block = $m[0];
		$parts = [];
		if ( preg_match_all( '/\[:([a-z]{2})\](.*?)(?=\[:[a-z]{2}\]|\[:\]|$)/su', $block, $mm, PREG_SET_ORDER ) ) {
			foreach ( $mm as $x ) $parts[ $x[1] ] = trim( $x[2] );
		}
		if ( isset( $parts[ $lang ] ) && $parts[ $lang ] !== '' ) return $parts[ $lang ];
		foreach ( $parts as $t ) if ( $t !== '' ) return $t;
		return '';
	}, $html );
}
add_action( 'wp_head', function () { ob_start(); }, -9999 );
add_action( 'wp_head', function () {
	if ( ob_get_level() ) echo sz_head_lang_fix( ob_get_clean() );
}, PHP_INT_MAX );
/* <title> тоже (если тема выводит его вне wp_head) */
add_filter( 'pre_get_document_title', function ( $t ) { return $t ? sz_head_lang_fix( $t ) : $t; }, 99 );
add_filter( 'document_title_parts', function ( $p ) {
	foreach ( $p as $k => $v ) if ( is_string( $v ) ) $p[ $k ] = sz_head_lang_fix( $v );
	return $p;
}, 99 );

/* ==========================================================
   SEO карточек товаров: если title / description не заполнены
   для текущего языка — подставляем шаблон (Yoast, Rank Math или без плагина)
   ========================================================== */
function sz_seo_lang() {
	return function_exists( 'qtranxf_getLanguage' ) ? qtranxf_getLanguage() : 'ua';
}
/* текст на текущем языке из «[:ua]…[:ru]…[:]»; без тегов — как есть */
function sz_seo_part( $text ) {
	$text = (string) $text;
	if ( strpos( $text, '[:' ) === false ) return trim( $text );
	$lang = sz_seo_lang();
	if ( preg_match( '/\[:' . $lang . '\](.*?)(?=\[:[a-z]{2}\]|\[:\]|$)/su', $text, $m ) ) return trim( $m[1] );
	return '';
}
function sz_seo_product_id() {
	return ( function_exists( 'is_product' ) && is_product() ) ? get_queried_object_id() : 0;
}
function sz_seo_name( $id ) {
	$raw = get_post_field( 'post_title', $id, 'raw' );
	$n   = sz_seo_part( $raw );
	if ( $n === '' ) $n = pdp_lang( $raw ); // нет перевода — берём доступный язык
	return trim( wp_strip_all_tags( $n ) );
}
/* товар у категорії «Аксесуари, сувенірні номери» (others) або її підкатегоріях */
function sz_seo_is_others( $id ) {
	$cat = get_term_by( 'slug', 'others', 'product_cat' );
	if ( ! $cat ) return false;
	$ids   = get_term_children( $cat->term_id, 'product_cat' );
	$ids[] = $cat->term_id;
	return has_term( $ids, 'product_cat', $id );
}
function sz_seo_auto_title( $id ) {
	$ru = 'ru' === sz_seo_lang();
	if ( sz_seo_is_others( $id ) ) {
		return sz_seo_name( $id ) . ( $ru ? ' – заказать | Спецзнак' : ' – замовити | Спецзнак' );
	}
	return sz_seo_name( $id ) . ( $ru ? ' – заказать автономер | Спецзнак' : ' – замовити автономер | Спецзнак' );
}
function sz_seo_auto_desc( $id ) {
	$n = sz_seo_name( $id );
	return 'ru' === sz_seo_lang()
		? $n . ' заказывайте в «Спецзнак». Быстрое и официальное изготовление. Самовывоз или доставка по всей Украине.'
		: $n . ' замовляйте у «Спецзнак». Швидке та офіційне виготовлення. Самовивіз або доставка по всій Україні.';
}
/* заполнено ли поле SEO-плагина для текущего языка */
function sz_seo_filled( $id, $what ) {
	$keys = 'title' === $what
		? [ '_yoast_wpseo_title', 'rank_math_title' ]
		: [ '_yoast_wpseo_metadesc', 'rank_math_description' ];
	foreach ( $keys as $k ) {
		if ( sz_seo_part( get_post_meta( $id, $k, true ) ) !== '' ) return true;
	}
	return false;
}
function sz_seo_title_filter( $t ) {
	$id = sz_seo_product_id();
	return ( $id && ! sz_seo_filled( $id, 'title' ) ) ? sz_seo_auto_title( $id ) : $t;
}
function sz_seo_desc_filter( $d ) {
	$id = sz_seo_product_id();
	return ( $id && ! sz_seo_filled( $id, 'desc' ) ) ? sz_seo_auto_desc( $id ) : $d;
}
/* Yoast */
foreach ( [ 'wpseo_title', 'wpseo_opengraph_title', 'wpseo_twitter_title' ] as $h ) add_filter( $h, 'sz_seo_title_filter', 99 );
foreach ( [ 'wpseo_metadesc', 'wpseo_opengraph_desc', 'wpseo_twitter_description' ] as $h ) add_filter( $h, 'sz_seo_desc_filter', 99 );
/* Rank Math */
add_filter( 'rank_math/frontend/title', 'sz_seo_title_filter', 99 );
add_filter( 'rank_math/frontend/description', 'sz_seo_desc_filter', 99 );
add_filter( 'rank_math/opengraph/facebook/og_title', 'sz_seo_title_filter', 99 );
add_filter( 'rank_math/opengraph/facebook/og_description', 'sz_seo_desc_filter', 99 );
add_filter( 'rank_math/opengraph/twitter/twitter_title', 'sz_seo_title_filter', 99 );
add_filter( 'rank_math/opengraph/twitter/twitter_description', 'sz_seo_desc_filter', 99 );
/* без SEO-плагина */
add_filter( 'pre_get_document_title', function ( $t ) {
	if ( defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) ) return $t;
	$id = sz_seo_product_id();
	return $id ? sz_seo_auto_title( $id ) : $t;
}, 100 );
add_action( 'wp_head', function () {
	if ( defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) ) return;
	$id = sz_seo_product_id();
	if ( ! $id ) return;
	$d = esc_attr( sz_seo_auto_desc( $id ) );
	$t = esc_attr( sz_seo_auto_title( $id ) );
	echo "<meta name=\"description\" content=\"$d\">\n<meta property=\"og:title\" content=\"$t\">\n<meta property=\"og:description\" content=\"$d\">\n";
}, 2 );/**
 * Додати в кінець functions.php теми.
 * Товари з категорії «Аксесуари, сувенірні номери» (slug: others) і її підкатегорій
 * отримують клас body.pdp-cat-others — CSS ховає «Купити» і робить «Замовити швидко» синьою.
 * Інші категорії не змінюються.
 */
add_filter( 'body_class', function ( $classes ) {
	if ( function_exists( 'is_product' ) && is_product() ) {
		$cat = get_term_by( 'slug', 'others', 'product_cat' );
		if ( $cat ) {
			$ids   = get_term_children( $cat->term_id, 'product_cat' );
			$ids[] = $cat->term_id;
			if ( has_term( $ids, 'product_cat', get_the_ID() ) ) {
				$classes[] = 'pdp-cat-others';
			}
		}
	}
	return $classes;
} );
/* ==========================================================
   Картка товару в каталозі: для категорії «Аксесуари, сувенірні номери» (others)
   замість кошика — галочка на синьому фоні, клік веде в картку товару (UA / RU).
   ========================================================== */
function sz_product_card( $p ) {
	set_query_var( 'pdp_product', $p );
	get_template_part( 'template-parts/content', 'product' );
}

add_action( 'wp_footer', function () {
	$cat = get_term_by( 'slug', 'others', 'product_cat' );
	if ( ! $cat ) return;
	$ids = wc_get_products( [
		'status' => 'publish', 'limit' => -1, 'return' => 'ids',
		'category' => array_merge( [ $cat->slug ], array_map( function ( $id ) { $t = get_term( $id, 'product_cat' ); return $t->slug; }, get_term_children( $cat->term_id, 'product_cat' ) ) ),
	] );
	if ( ! $ids ) return;
	$slugs = array_values( array_filter( array_map( function ( $id ) { return get_post_field( 'post_name', $id ); }, $ids ) ) );
	?>
<script>
(function () {
  var slugs = <?php echo wp_json_encode( $slugs ); ?>;
  var ru = document.documentElement.lang.indexOf('ru') === 0 || location.pathname.indexOf('/ru/') !== -1;
  var check = '<svg width="22" height="22" viewBox="0 0 24 24" aria-hidden="true" style="color:#fff"><path d="m5 12.5 4.5 4.5L19 7.5" fill="none" stroke="#fff" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
  function slugOf(href) { var m = (href || '').match(/\/product\/([^\/?#]+)/); return m ? decodeURIComponent(m[1]) : ''; }
  document.querySelectorAll('a[href*="/product/"]').forEach(function (link) {
    if (slugs.indexOf(slugOf(link.getAttribute('href'))) === -1) return;
    var card = link.parentElement, btn = null;
    while (card && card !== document.body) {
      btn = card.querySelector('.cart-btn, [data-add-to-cart], .add_to_cart_button, button[aria-label*="кош" i], button[aria-label*="корз" i]');
      if (btn) break;
      card = card.parentElement;
    }
    if (!btn || btn.dataset.szDone) return;
    var a = document.createElement('a');
    a.className = btn.className.replace(/\bis-added\b/g, '');
    a.href = link.href;
    a.dataset.szDone = '1';
    a.setAttribute('aria-label', ru ? 'Перейти к товару' : 'Перейти до товару');
    a.innerHTML = check;
    btn.parentNode.replaceChild(a, btn);
  });
})();
</script>
<?php }, 50 );

/* ==========================================================
   Оформлення: вибір офісу (самовивіз) — власний список на всю ширину поля
   замість нативного вузького меню iOS / Android. Значення пишеться в той самий <select name="office">.
   ========================================================== */
add_action( 'wp_footer', function () {
	if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) return; ?>
<style>
.co-select.sz-dd { position: relative; }
.co-select.sz-dd select { position: absolute; inset: 0; opacity: 0; pointer-events: none; }
.sz-dd__btn { display: flex; align-items: center; width: 100%; min-height: inherit; padding: 0; border: 0; background: none; font: inherit; color: inherit; text-align: left; cursor: pointer; }
.sz-dd__btn span { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.sz-dd__list { position: absolute; left: -1px; right: -1px; top: calc(100% + 6px); z-index: 80; max-height: min(60vh, 420px); overflow-y: auto; -webkit-overflow-scrolling: touch;
  margin: 0; padding: 6px 0; list-style: none; background: #fff; border: 1px solid #e3e8ef; border-radius: 12px; box-shadow: 0 16px 40px rgba(3, 36, 62, .14); display: none; }
.sz-dd.is-open .sz-dd__list { display: block; }
.sz-dd.is-open > svg { transform: rotate(180deg); }
.sz-dd__list li { padding: 12px 18px; font-size: 16px; line-height: 1.35; color: #1b1b1b; cursor: pointer; white-space: normal; }
.sz-dd__list li:hover, .sz-dd__list li.is-active { background: #f1f5fb; color: var(--blue-2, #1f5fd2); }
</style>
<script>
(function () {
  document.querySelectorAll('.co-select select[name="office"]').forEach(function (sel) {
    var wrap = sel.closest('.co-select');
    if (!wrap || wrap.classList.contains('sz-dd')) return;
    wrap.classList.add('sz-dd');
    var btn = document.createElement('button');
    btn.type = 'button'; btn.className = 'sz-dd__btn';
    btn.setAttribute('aria-haspopup', 'listbox'); btn.setAttribute('aria-expanded', 'false');
    var label = document.createElement('span'); btn.appendChild(label);
    var list = document.createElement('ul'); list.className = 'sz-dd__list'; list.setAttribute('role', 'listbox');
    Array.prototype.forEach.call(sel.options, function (o, i) {
      var li = document.createElement('li'); li.textContent = o.text; li.setAttribute('role', 'option');
      li.addEventListener('click', function () {
        sel.selectedIndex = i; sel.dispatchEvent(new Event('change', { bubbles: true }));
        sync(); close();
      });
      list.appendChild(li);
    });
    function sync() {
      label.textContent = sel.options[sel.selectedIndex] ? sel.options[sel.selectedIndex].text : '';
      Array.prototype.forEach.call(list.children, function (li, i) { li.classList.toggle('is-active', i === sel.selectedIndex); li.setAttribute('aria-selected', i === sel.selectedIndex); });
    }
    function close() { wrap.classList.remove('is-open'); btn.setAttribute('aria-expanded', 'false'); }
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      var open = !wrap.classList.contains('is-open');
      wrap.classList.toggle('is-open', open); btn.setAttribute('aria-expanded', open);
    });
    document.addEventListener('click', function (e) { if (!wrap.contains(e.target)) close(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
    sel.addEventListener('change', sync);
    wrap.insertBefore(btn, sel); wrap.appendChild(list);
    sync();
  });
})();
</script>
<?php }, 60 );

/* ==========================================================
   Анімація появи (data-reveal): блоки, які вже на екрані при завантаженні,
   показуються одразу — не чекаючи скролу. Діє на всіх сторінках, UA / RU.
   ========================================================== */
add_action( 'wp_footer', function () { ?>
<script>
(function () {
  function showVisible() {
    var h = window.innerHeight || document.documentElement.clientHeight;
    document.querySelectorAll('[data-reveal]:not(.is-revealed)').forEach(function (el) {
      var r = el.getBoundingClientRect();
      if (r.top < h && r.bottom > 0) el.classList.add('is-revealed');
    });
  }
  if (document.readyState === 'complete') showVisible();
  else window.addEventListener('load', showVisible);
  document.addEventListener('DOMContentLoaded', function () { setTimeout(showVisible, 300); });
})();
</script>
<?php }, 99 );
/* ---------- футер: посилання (у т.ч. «Політика конфіденційності») відкриваються в цій же вкладці ----------
   main.js додає target="_blank" усім посиланням на політику — для футера прибираємо. UA / RU */
add_action( 'wp_footer', function () { ?>
<script>
(function () {
  function fix() {
    document.querySelectorAll('.footer a[target="_blank"]').forEach(function (a) {
      if (a.hostname === location.hostname) { a.removeAttribute('target'); a.removeAttribute('rel'); }
    });
  }
  document.addEventListener('DOMContentLoaded', function () { setTimeout(fix, 0); });
  window.addEventListener('load', fix);
})();
</script>
<?php }, 100 );

/* ---------- відгуки: клік по зірці ставить оцінку (запасний обробник; зірки малює WooCommerce, дублів немає) ---------- */
add_action( 'wp_footer', function () {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) return; ?>
<script>
(function () {
  function dedupe() {
    var wrap = document.querySelector('.comment-form-rating');
    if (!wrap) return;
    var all = wrap.querySelectorAll('p.stars');
    for (var i = 1; i < all.length; i++) all[i].remove();
  }
  window.addEventListener('load', dedupe);
  document.addEventListener('click', function (e) {
    var a = e.target.closest('.comment-form-rating p.stars a');
    if (!a) return;
    e.preventDefault();
    var p = a.closest('p.stars'), sel = document.getElementById('rating');
    var n = parseInt((a.className.match(/star-(\d)/) || [])[1], 10);
    if (!n || !sel) return;
    sel.value = String(n);
    p.classList.add('selected');
    p.querySelectorAll('a').forEach(function (x) { x.classList.toggle('active', x === a); });
  });
})();
</script>
<?php }, 100 );

/* ---------- відгуки: поле «Назва» → «Ім'я» / «Имя» (неточний переклад WooCommerce) ---------- */
add_filter( 'woocommerce_product_review_comment_form_args', function ( $args ) {
	if ( empty( $args['fields']['author'] ) ) return $args;
	$ru    = function_exists( 'qtranxf_getLanguage' ) && 'ru' === qtranxf_getLanguage();
	$label = $ru ? 'Имя' : 'Ім’я';
	$args['fields']['author'] = preg_replace_callback(
		'~(<label[^>]*for="author"[^>]*>)(.*?)(<span|</label>)~su',
		function ( $m ) use ( $label ) { return $m[1] . esc_html( $label ) . '&nbsp;' . $m[3]; },
		$args['fields']['author'],
		1
	);
	return $args;
}, 99 );

/* ---------- «Післяплата / Наложенный платёж» = шлюз «Банківський переказ» (bacs):
   дозволяємо його для доставки Новою Поштою та кур'єром (серверна перевірка) ---------- */
add_filter( 'pdp_pay_rules', function ( $rules ) {
	foreach ( [ 'np', 'courier' ] as $k ) {
		if ( isset( $rules[ $k ] ) && ! in_array( 'bacs', $rules[ $k ], true ) ) $rules[ $k ][] = 'bacs';
	}
	return $rules;
} );

/* ---------- оформлення: «Післяплата» (bacs) активна для Нової Пошти та кур'єра (UA / RU) ----------
   checkout.js теми вимикає способи оплати, яких немає у його списку; тут вмикаємо bacs назад. */
add_action( 'wp_footer', function () {
	if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) return; ?>
<script>
(function () {
  function fix() {
    var d = document.querySelector('input[name="delivery"]:checked');
    var allow = d && (d.value === 'np' || d.value === 'courier');
    var inp = document.querySelector('input[name="payment_method"][value="bacs"]');
    if (!inp) return;
    var li = inp.closest('li, label, .wc_payment_method, .co-opt');
    if (allow) {
      inp.disabled = false;
      if (li) { li.classList.remove('is-disabled', 'disabled', 'is-off'); li.removeAttribute('aria-disabled'); li.style.opacity = ''; li.style.pointerEvents = ''; }
    }
  }
  document.addEventListener('change', function (e) { if (e.target.name === 'delivery') setTimeout(fix, 0); });
  if (window.jQuery) jQuery(document.body).on('updated_checkout payment_method_selected', function () { setTimeout(fix, 0); });
  document.addEventListener('DOMContentLoaded', function () { setTimeout(fix, 50); });
  window.addEventListener('load', fix);
})();
</script>
<?php }, 100 );

/* ==========================================================
   RU-версія: поп-ап «Перейти на солов’їну? 🇺🇦» (стиль як у «Заявка»)
   Показується на будь-якій сторінці /ru/ один раз за візит (sessionStorage).
   «Так» → та сама сторінка українською, «Ні» → закрити.
   ========================================================== */
add_action( 'wp_footer', function () {
	if ( is_admin() || ! function_exists( 'qtranxf_getLanguage' ) || 'ru' !== qtranxf_getLanguage() ) return;
	$scheme  = is_ssl() ? 'https://' : 'http://';
	$current = $scheme . ( $_SERVER['HTTP_HOST'] ?? '' ) . ( $_SERVER['REQUEST_URI'] ?? '/' );
	$ua_url  = function_exists( 'qtranxf_convertURL' ) ? qtranxf_convertURL( $current, 'ua', true ) : str_replace( '/ru/', '/', $current );
	?>
<div class="sz-langpop" id="sz-langpop" role="dialog" aria-modal="true" aria-labelledby="sz-langpop-title" hidden>
  <div class="sz-langpop__overlay" data-langpop-close></div>
  <div class="sz-langpop__box">
    <button class="sz-langpop__x" type="button" aria-label="Закрити" data-langpop-close><svg width="22" height="22" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
    <h2 class="sz-langpop__title" id="sz-langpop-title">Перейти на солов’їну? <span aria-hidden="true">🇺🇦</span></h2>
    <div class="sz-langpop__btns">
      <a class="sz-langpop__btn sz-langpop__btn--yes" href="<?php echo esc_url( $ua_url ); ?>" data-langpop-yes>Так</a>
      <button class="sz-langpop__btn sz-langpop__btn--no" type="button" data-langpop-close>Ні</button>
    </div>
  </div>
</div>
<style>
.sz-langpop { position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 16px; }
.sz-langpop[hidden] { display: none; }
.sz-langpop__overlay { position: absolute; inset: 0; background: rgba(5, 8, 13, .55); -webkit-backdrop-filter: blur(6px); backdrop-filter: blur(6px); opacity: 0; transition: opacity .3s; }
.sz-langpop__box {
  position: relative; width: min(560px, 100%); padding: 56px 48px 48px; border-radius: 40px; text-align: center; color: #fff;
  background: var(--grad, linear-gradient(135deg, #4a8be6 0%, #2f55b5 100%));
  box-shadow: 0 30px 80px rgba(3, 20, 50, .45);
  opacity: 0; transform: translateY(16px) scale(.97); transition: opacity .35s cubic-bezier(.16,1,.3,1), transform .45s cubic-bezier(.16,1,.3,1);
}
.sz-langpop.is-open .sz-langpop__overlay { opacity: 1; }
.sz-langpop.is-open .sz-langpop__box { opacity: 1; transform: none; }
.sz-langpop__x { position: absolute; top: 24px; right: 24px; padding: 6px; border: 0; background: none; color: #fff; cursor: pointer; line-height: 0; opacity: .9; }
.sz-langpop__x:hover { opacity: 1; }
.sz-langpop__title { margin: 0 0 34px; font-size: 40px; font-weight: 600; line-height: 1.15; letter-spacing: -.02em; text-transform: uppercase; color: #fff; }
.sz-langpop__btns { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.sz-langpop__btn {
  display: flex; align-items: center; justify-content: center; height: 64px; border-radius: 10px;
  font: inherit; font-size: 20px; font-weight: 600; text-transform: uppercase; text-decoration: none; cursor: pointer;
  transition: transform .15s, background .2s, color .2s;
}
.sz-langpop__btn--yes { background: #fff; color: #2c5fc0; border: 0; }
.sz-langpop__btn--yes:hover { transform: translateY(-1px); color: #1f4ea8; }
.sz-langpop__btn--no { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,.6); }
.sz-langpop__btn--no:hover { background: rgba(255,255,255,.12); }
@media (max-width: 767px) {
  .sz-langpop__box { padding: 52px 22px 26px; border-radius: 28px; }
  .sz-langpop__x { top: 16px; right: 16px; }
  .sz-langpop__title { margin-bottom: 26px; font-size: 28px; }
  .sz-langpop__btn { height: 56px; font-size: 18px; }
}
</style>
<script>
(function () {
  var KEY = 'sz_langpop_seen', pop = document.getElementById('sz-langpop');
  if (!pop) return;
  try { if (sessionStorage.getItem(KEY)) return; } catch (e) {}
  function mark() { try { sessionStorage.setItem(KEY, '1'); } catch (e) {} }
  function close() {
    pop.classList.remove('is-open'); mark();
    document.documentElement.style.overflow = '';
    setTimeout(function () { pop.hidden = true; }, 350);
  }
  setTimeout(function () {
    pop.hidden = false;
    document.documentElement.style.overflow = 'hidden';
    requestAnimationFrame(function () { pop.classList.add('is-open'); });
  }, 1200);
  pop.querySelectorAll('[data-langpop-close]').forEach(function (el) { el.addEventListener('click', close); });
  pop.querySelector('[data-langpop-yes]').addEventListener('click', function (e) {
    e.preventDefault(); e.stopPropagation(); mark();
    try { document.cookie = 'qtrans_front_language=ua; path=/; max-age=31536000'; } catch (er) {}
    /* 1) посилання qTranslate на UA-версію цієї сторінки (hreflang у <head> або перемикач мов у шапці) */
    var alt = document.querySelector('link[rel="alternate"][hreflang="uk"], link[rel="alternate"][hreflang="ua"], link[rel="alternate"][hreflang^="uk-"], link[rel="alternate"][hreflang="x-default"]');
    var url = alt && alt.href && alt.href.indexOf('/ru/') === -1 ? alt.href : '';
    if (!url) {
      var sw = document.querySelector('a[hreflang="uk"], a[hreflang="ua"], .lang a[href]:not([href*="/ru/"]), .qtranxs_language_chooser a[href]:not([href*="/ru/"])');
      if (sw && sw.href && sw.href.indexOf('/ru/') === -1) url = sw.href;
    }
    /* 2) запасний варіант: прибираємо /ru/ з адреси */
    if (!url) url = location.origin + location.pathname.replace(/\/ru(\/|$)/, '/') + location.hash;
    /* ?lang=ua — qTranslate перемикає мову й не повертає на /ru/ */
    var u = new URL(url, location.href);
    new URLSearchParams(location.search).forEach(function (v, k) { if (!u.searchParams.has(k)) u.searchParams.set(k, v); });
    u.searchParams.set('lang', 'ua');
    location.href = u.toString();
  });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !pop.hidden) close(); });
})();
</script>
<?php }, 120 );