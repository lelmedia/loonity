<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Loonity
 */

?>

	<footer id="colophon" class="site-footer bbrown">
		<div class="row">
			<div class="col-12">
					<?php the_custom_logo();?>
				<hr/>
			</div>
		</div>
		<div class="row">
			<div class="col-3">
				<?php $store_address     = get_option( 'woocommerce_store_address' );
				$store_city        = get_option( 'woocommerce_store_city' );
				$store_postcode    = get_option( 'woocommerce_store_postcode' );
				$raw_tel = get_field('tel', 'option');

if ($raw_tel) {
    // Nettoyage : enlever les espaces, tirets, points, etc.
    $clean_tel = preg_replace('/\D+/', '', $raw_tel); // garde uniquement les chiffres

    // Remplacement du 0 initial par +33 (France)
    if (substr($clean_tel, 0, 1) === '0') {
        $clickable_tel = '+33' . substr($clean_tel, 1);
    } else {
        $clickable_tel = $clean_tel; // au cas où c'est déjà international
    }
}
?>
				<p><?php echo $store_address.', '.$store_postcode.' '.$store_city;?><br/>
				<a href="tel:<?php echo $clickable_tel;?>"><?php echo esc_html($raw_tel);?></a><br/>
				<a href="mailto:<?php the_field('mail', 'option');?>"><?php the_field('mail', 'option');?></a></p>
			</div>
			<div class="col-6">
				
			</div>
			<div class="col-3 aright">
			<!--<?php if (have_rows('social', 'option')) : ?>
    <ul class="social-icons">
        <?php while (have_rows('social', 'option')) : the_row(); 
            $url = get_sub_field('url');
            $icon = get_sub_field('icon'); // exemple : 'fab fa-facebook-f'
        ?>
            <?php if ($url && $icon): ?>
                <li>
                    <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer">
                        <div class="<?php echo esc_attr( $icon['value'] ); ?>"></div>
                    </a>
                </li>
            <?php endif; ?>
        <?php endwhile; ?>
    </ul>
<?php endif; ?>--->
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

</body>
</html>
