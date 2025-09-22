<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;
get_header( 'shop' );

$term = get_queried_object();
$thumb = is_tax('product_cat') ? get_term_meta( $term->term_id, 'thumbnail_id', true ) : 0;
$cat_image_url = $thumb ? wp_get_attachment_url( $thumb ) : '';
?>

<?php if ( is_product_category() && isset( $term->term_id ) ) : 
    $child_categories = get_terms([
        'taxonomy'   => 'product_cat',
        'parent'     => $term->term_id,
        'hide_empty' => false,
    ]);
    if ( ! empty( $child_categories ) ) : ?>

<div class="pad2">
  <div class="row">
    <div class="col-1 nophone"></div>
    <div class="col-7">
      <div class="woocommerce-breadcrumb-wrapper"><?php woocommerce_breadcrumb(); ?></div>
    </div>
    <div class="col-1 nophone"></div>
  </div>
</div>

<header class="woocommerce-products-header pad3">
  <div class="row">
    <div class="col-1"></div><div class="col-1"></div>
    <div class="col-5 acenter">
      <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
        <h1><?php woocommerce_page_title(); ?></h1>
      <?php endif; ?>
      <?php do_action( 'woocommerce_archive_description' ); ?>
    </div>
    <div class="col-1"></div><div class="col-1"></div>
  </div>
</header>

<div class="pad06">
  <div class="product-cat-grid">
    <?php foreach ( $child_categories as $category ) :
      $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
      $image_url    = wp_get_attachment_url( $thumbnail_id );
      $category_link = get_term_link( $category ); ?>
      <div class="product-cat-item child">
        <a href="<?php echo esc_url( $category_link ); ?>">
          <?php if ( $image_url ) : ?>
            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $category->name ); ?>">
          <?php endif; ?>
          <div class="product-cat-name"><h3><?php echo esc_html( $category->name ); ?></h3></div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php else : // Pas d'enfants, afficher produits de la catégorie avec ta structure ?>

<header class="woocommerce-products-header pad53 bimage" <?php if ( $cat_image_url ) : ?>style="background-image:url(<?php echo esc_url( $cat_image_url ); ?>)"<?php endif; ?>>
  <div class="row">
    <div class="col-1"></div>
    <div class="col-7">
      <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
        <h1 class="big"><?php woocommerce_page_title(); ?></h1>
      <?php endif; ?>
    </div>
    <div class="col-1"></div>
  </div>
</header>

<div class="pad2">
  <div class="row">
    <div class="col-1 nophone"></div>
    <div class="col-7">
      <div class="woocommerce-breadcrumb-wrapper"><?php woocommerce_breadcrumb(); ?></div>
    </div>
    <div class="col-1 nophone"></div>
  </div>
</div>

<section id="prodlist" class="pad04">
  <div class="row">
    <div class="col-1"></div>
    <div class="col-7">

      <?php
      // Boucle standard sur la requête principale (catégorie en cours)
      if ( woocommerce_product_loop() ) {
          do_action( 'woocommerce_before_shop_loop' );
          woocommerce_product_loop_start();

          if ( wc_get_loop_prop( 'total' ) ) {
              while ( have_posts() ) {
                  the_post();
                  wc_get_template_part( 'content', 'product' );
              }
          }

          woocommerce_product_loop_end();
          do_action( 'woocommerce_after_shop_loop' );
      } else {
          do_action( 'woocommerce_no_products_found' );
      }
      ?>

    </div>
    <div class="col-1"></div>
  </div>
</section>

<?php endif; // end children vs products ?>

<?php else : // Pas une product_cat : archive boutique ou autre ?>
  <?php
  if ( woocommerce_product_loop() ) {
      do_action( 'woocommerce_before_shop_loop' );
      woocommerce_product_loop_start();

      if ( wc_get_loop_prop( 'total' ) ) {
          while ( have_posts() ) {
              the_post();
              wc_get_template_part( 'content', 'product' );
          }
      }

      woocommerce_product_loop_end();
      do_action( 'woocommerce_after_shop_loop' );
  } else {
      do_action( 'woocommerce_no_products_found' );
  }
  ?>
<?php endif; ?>

<?php
do_action( 'woocommerce_sidebar' );
get_footer( 'shop' );
