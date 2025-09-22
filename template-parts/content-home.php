<?php
/**
 * Template part for displaying home content in home.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Loonity
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header" id="homeheader">
		<div class="row">
			<div  class="col-1"></div>
			<div  class="col-1"></div>
			<div  class="col-5 acenter">
				<?php the_content(); ?>
			</div>
			<div  class="col-1"></div>
			<div  class="col-1"></div>		
		</div>		
	</header><!-- .entry-header -->
	
	<div id="homeslider">
		<div class="row">
			<div  class="col-9 acenter">
				<?php
// Récupérer les catégories parents uniquement
$product_categories = get_terms(array(
    'taxonomy'   => 'product_cat',
    'parent'     => 0,
    'hide_empty' => false,
	'exclude'    => array( get_term_by( 'slug', 'non-classe', 'product_cat' )->term_id ),
));

if (!empty($product_categories) && !is_wp_error($product_categories)) : ?>
    <div class="product-cat-grid">
        <?php foreach ($product_categories as $category) :
            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
            $image_url = wp_get_attachment_url($thumbnail_id);
            $category_link = get_term_link($category); ?>
            
            <div class="product-cat-item">
                <a href="<?php echo esc_url($category_link); ?>">
                    <?php if ($image_url): ?>
					<div class="product-cat-contain">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>">
					</div>
                    <?php endif; ?>
                    <h3><?php echo esc_html($category->name); ?></h3>
                </a>
            </div>

        <?php endforeach; ?>
    </div>
<?php endif; ?>

			</div>
		</div>	
	</div>

	<div class="entry-content pad6">
		<div class="row">
			<div  class="col-1"></div>
			<div  class="col-1"></div>
			<div  class="col-5">
				<h2 class="small"><?php the_field('who-title'); ?></h2>
				<p class="big"><?php the_field('who-text'); ?></p>
			</div>
			<div  class="col-1"></div>
			<div  class="col-1"></div>		
		</div>		
		
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
