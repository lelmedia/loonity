<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Loonity
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'loonity' ); ?></a>

	<header id="masthead" class="site-header bbrown">
		<div class="row middle">
			<div class="col-2">
				<div class="site-branding">
					<?php the_custom_logo(); ?>				
				</div><!-- .site-branding -->
			</div>
			<div class="col-7">
				<nav id="site-navigation" class="main-navigation">
					<?php
					wp_nav_menu(
						array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
					'menu_class'     => 'main-menu masthead-menu', //
					));	?>
				</nav><!-- #site-navigation -->
			</div>
			<div class="col-3">
			
			</div>		
		</div>
		

		
	</header><!-- #masthead -->
