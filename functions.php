<?php
/**
 * Loonity functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Loonity
 */

add_action( 'wp_enqueue_scripts', 'lelmedia_force_jquery', 0 );
function lelmedia_force_jquery() {
    if ( ! wp_script_is( 'jquery', 'enqueued' ) ) {
        wp_enqueue_script( 'jquery' );
    }
}
add_action('wp_enqueue_scripts', function() {
    if (is_product()) {
        wp_enqueue_script('wc-add-to-cart-variation');
    }
});

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
function loonity_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Loonity, use a find and replace
		* to change 'loonity' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'loonity', get_template_directory() . '/languages' );

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
			'menu-1' => esc_html__( 'Primary', 'loonity' ),
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
	
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'loonity_custom_background_args',
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
add_action( 'after_setup_theme', 'loonity_setup' );


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function loonity_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'loonity_content_width', 640 );
}
add_action( 'after_setup_theme', 'loonity_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function loonity_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'loonity' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'loonity' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'loonity_widgets_init' );

/*** MENU ***/

add_filter('nav_menu_item_title', 'custom_menu_title_formatting', 10, 4);

function custom_menu_title_formatting($title, $item, $args, $depth) {
    // Appliquer uniquement si on est dans le menu du header (classe personnalisée)
    if (strpos($args->menu_class, 'masthead-menu') === false) {
        return $title;
    }

    $words = explode(' ', $title);

    if (count($words) <= 1) {
        return $title;
    }

    $first = array_shift($words);
    $rest  = implode(' ', $words);

    // Mettre la première lettre en majuscule
    $rest  = ucfirst($rest);

    return $first . '<br><strong>' . $rest . '</strong>';
}



/*** OPTIONS PAGE ***/

if( function_exists('acf_add_options_page') ) {
	// Page principale
	acf_add_options_page(array(
		'page_title'    => 'Contact',
		'menu_title'    => 'Contact et réseaux',
		'menu_slug'     => 'links',
		'capability'    => 'edit_posts',
		'icon_url'		=> 'dashicons-admin-site',
		'redirect'      => true
	));
}

/*** IMAGE SIZES ***/

function custom_image_sizes() {
    // Ajouter une nouvelle taille d'image
	add_image_size('product', 800, 600, true); // Nom, largeur, hauteur, recadrage
}
add_action('after_setup_theme', 'custom_image_sizes');

/*** DASHICONS FRONT ***/
add_action( 'wp_enqueue_scripts', 'load_dashicons_front_end' );
function load_dashicons_front_end() {
  wp_enqueue_style( 'dashicons' );
}


remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

// UGS

// Retirer le bloc méta natif
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

// Afficher "Référence" au-dessus du titre
add_action( 'woocommerce_single_product_summary', 'custom_display_sku_before_title', 4 );
function custom_display_sku_before_title() {
    if ( ! wc_product_sku_enabled() ) return;

    global $product;
    $parent_sku = $product->get_sku();

    echo '<div class="product-sku" style="margin-bottom:6px">';
    echo '<span class="label">Référence&nbsp;: </span>';
    if ( $product->is_type( 'variable' ) ) {
        // valeur initiale = SKU parent s’il existe, sinon vide. .sku pour le microdata
        echo '<span class="sku value" data-parent-sku="' . esc_attr( $parent_sku ) . '">' . esc_html( $parent_sku ) . '</span>';
    } else {
        if ( ! $parent_sku ) return;
        echo '<span class="sku value">' . esc_html( $parent_sku ) . '</span>';
    }
    echo '</div>';
}

// Mettre à jour la Référence selon la variation choisie
add_action( 'wp_footer', 'custom_update_variation_sku' );
function custom_update_variation_sku() {
    if ( ! is_product() ) return; ?>
    <script>
    jQuery(function($){
        var $form  = $('form.variations_form');
        var $skuEl = $('.product-sku .sku');
        if (!$form.length || !$skuEl.length) return;

        var parentSku = $skuEl.data('parent-sku') || '';

        $form.on('found_variation', function(e, v){
            if (v && typeof v.sku !== 'undefined') {
                $skuEl.text(v.sku || parentSku);
            }
        });
        $form.on('reset_data', function(){
            $skuEl.text(parentSku);
        });
    });
    </script>
<?php }


// Vignettes de galerie non recadrées
add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function( $size ) {
    return array(
        'width'  => 160,   // ajuste si besoin
        'height' => 0,     // 0 = hauteur libre, respecte le ratio
        'crop'   => 0      // pas de recadrage
    );
});

// (optionnel) miniatures catalogue non recadrées
add_filter( 'woocommerce_get_image_size_thumbnail', function( $size ) {
    return array(
        'width'  => 300,
        'height' => 0,
        'crop'   => 0
    );
});



/**
 * Enqueue scripts and styles.
 */

add_action('wp_head', function () {
	 echo '<script src="' . get_template_directory_uri() . '/js/script.js?ver=' . time() . '"></script>';
});

function loonity_scripts() {
	wp_enqueue_style( 'inter-font', 'https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
	wp_enqueue_style( 'inter-adobe-font', 'https://use.typekit.net/olw3wyo.css');
	wp_enqueue_style( 'loonity-style', get_stylesheet_uri(), array(), 1.0 );

	wp_enqueue_script( 'loonity-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'loonity_scripts' );

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

function lelmedia_force_register_jquery() {
    if ( ! wp_script_is( 'jquery', 'registered' ) ) {
        wp_register_script( 'jquery', includes_url( '/js/jquery/jquery.js' ), array(), null, true );
    }
    wp_enqueue_script( 'jquery' );
}
