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
?>


<?php
// On récupère la catégorie en cours (si on est sur une archive produit_cat)
$term = get_queried_object();
$thumb = get_term_meta( $term->term_id, 'thumbnail_id', true );
$cat_image_url = wp_get_attachment_url( $thumb );

if ( is_product_category() && isset($term->term_id) ) {
	$child_categories = get_terms(array(
		'taxonomy'   => 'product_cat',
		'parent'     => $term->term_id,
		'hide_empty' => false,
	));

	if ( !empty($child_categories) ) { 
		// Affiche les sous-catégories si elles existent ?>
<div class="pad2">
	<div class="row">
		<div class='col-1 nophone'></div>	
		<div class='col-7'>
			<div class="woocommerce-breadcrumb-wrapper">
				<?php woocommerce_breadcrumb(); ?>
			</div>			
		</div>
		<div class='col-1 nophone'></div>	
	</div>	
</div>
<header class="woocommerce-products-header pad3">
	<div class="row">
			<div  class="col-1"></div>
			<div  class="col-1"></div>
			<div  class="col-5 acenter">
				<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
					<h1><?php woocommerce_page_title(); ?></h1>
				<?php endif; ?>

				<?php do_action( 'woocommerce_archive_description' ); ?>
			</div>
			<div  class="col-1"></div>
			<div  class="col-1"></div>		
		</div>			
</header>
		<div class="pad06"><div class="product-cat-grid">
		<?php foreach ($child_categories as $category) :
			$thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
			$image_url = wp_get_attachment_url($thumbnail_id);
			$category_link = get_term_link($category); ?>

			<div class="product-cat-item child">
				<a href="<?php echo esc_url($category_link); ?>">
					<?php if ($image_url): ?>
						<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>">
					<?php endif; ?>
					<div class="product-cat-name">
						<h3><?php echo esc_html($category->name); ?></h3>
					</div>
				</a>
			</div>
		<?php endforeach; ?>
		</div></div>
<?php } else {?>
<header class="woocommerce-products-header pad53 bimage" style="background-image: url(<?php echo $cat_image_url;?>)">
	<div class="row">
			<div  class="col-1"></div>
			<div  class="col-7">
				<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
					<h1 class="big"><?php woocommerce_page_title(); ?></h1>
				<?php endif; ?>
			</div>
			<div  class="col-1"></div>		
		</div>			
</header>

<div class="pad2">
	<div class="row">
		<div class='col-1 nophone'></div>	
		<div class='col-7'>
			<div class="woocommerce-breadcrumb-wrapper">
				<?php woocommerce_breadcrumb(); ?>
			</div>			
		</div>
		<div class='col-1 nophone'></div>	
	</div>	
</div>
<section id="prodlist" class="pad04">
	<div class="row">
			<div  class="col-1"></div>
			<div  class="col-7">
<?php 	$term = get_queried_object();

$args = array(
    'post_type'      => 'product',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'tax_query'      => array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => $term->term_id,
        ),
    ),
);

$query = new WP_Query( $args );

if ( $query->have_posts() ) :
    echo '<div class="variation-grid">';
    while ( $query->have_posts() ) : $query->the_post();
        global $product;

        if ( $product->is_type('variable') ) {
            $variations = $product->get_available_variations();
            foreach ( $variations as $variation ) {
                $variation_obj = new WC_Product_Variation( $variation['variation_id'] );

                $variation_id = $variation['variation_id'];
                $name = $product->get_name(); // nom du produit parent
                $price_html = $variation_obj->get_price_html();

                // image
                $image_id = $variation_obj->get_image_id();
                $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : wc_placeholder_img_src();

                // attributs texte : formaté comme "Banc • Bois"
                $attributes = array();
foreach ( $variation_obj->get_attributes() as $key => $value ) {
    // Si c'est une taxonomie (ex : attribute_pa_couleur), on cherche le nom lisible
    $taxonomy = str_replace( 'attribute_', '', $key );
    if ( taxonomy_exists( $taxonomy ) ) {
        $term = get_term_by( 'slug', $value, $taxonomy );
        $attributes[] = $term ? $term->name : $value;
    } else {
        // attribut personnalisé (non taxonomique)
        $attributes[] = $value;
    }
}
$attributes_text = implode(' • ', $attributes);


                // lien vers le produit parent avec variation présélectionnée
                $product_url = get_permalink( $product->get_id() );
                $product_url = add_query_arg( $variation['attributes'], $product_url );
                ?>

                <div class="variation-card">
                    <a href="<?php echo esc_url($product_url); ?>">
                        <div class="variation-card-img">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($name); ?>">
                        </div>
                        <div class="variation-card-info">
                            <div class="variation-card-attr"><?php echo esc_html($attributes_text); ?></div>
                            <div class="variation-card-name-price">
                                <span class="variation-card-name"><?php echo esc_html($name); ?></span>
                                <span class="variation-card-price"><?php echo wp_kses_post($price_html); ?></span>
                            </div>
                        </div>
                    </a>
                </div>
                <?php
            }
        }
    endwhile;
    echo '</div>';
    wp_reset_postdata();
else :
    echo '<p>Aucun produit trouvé.</p>';
endif;
			 
			 
}?>
			</div>
			<div  class="col-1"></div>		
		</div>	
</section>
<?php } else {
	// ✅ Pas une catégorie produit ? Affiche la boutique ou autre archive
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
}

do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );

