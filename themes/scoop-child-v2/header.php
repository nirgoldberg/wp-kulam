<?php
/**
 * Header
 *
 * @author		Nir Goldberg
 * @package		scoop-child
 * @version		2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Variables
 */
if ( function_exists( 'get_field' ) ) {

	$search_form_type = get_field( 'acf-option_search_form_type', 'option' );

}

$logo_img = get_theme_mod( 'image_logo' ); // Getting from option your choice.

$layout_site_default = 'wide';
$layout_site = get_theme_mod( 'layout_site', $layout_site_default );
if ( empty( $layout_site ) || ! in_array( $layout_site, array( 'wide', 'boxed' ) ) )
	$layout_site = $layout_site_default;

?><!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>

	<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
<!--[if lt IE 7]><p class="chromeframe">Your browser is <em>ancient!</em>
	<a href="http://browsehappy.com/">Upgrade to a different browser</a> or
	<a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.
</p><![endif]-->

<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	}
	else {
		do_action( 'wp_body_open' );
	}
?>

<div id="container" class="<?php echo esc_attr( str_replace( '_', '-', $layout_site ) ); ?> ">
	<?php po_change_loop_to_parent( 'change' ); ?>

	<?php if ( ! pojo_is_blank_page() ) : ?>

		<section id="top-bar">
			<div class="<?php echo WRAP_CLASSES; ?>">
				<div class="pull-left">
					<?php dynamic_sidebar( 'pojo-' . sanitize_title( 'Top Bar Left' ) ); ?>
				</div>
				<div class="pull-right">
					<?php dynamic_sidebar( 'pojo-' . sanitize_title( 'Top Bar Right' ) ); ?>
				</div>
			</div><!-- .<?php echo WRAP_CLASSES; ?> -->
		</section>
		<header id="header" class="logo-<?php echo ( 'logo_left' === get_theme_mod( 'header_layout' ) ) ? 'left' : 'right'; ?>" role="banner">
			<div class="<?php echo WRAP_CLASSES; ?>">

				<div class="logo">

					<?php if ( ! empty( $logo_img ) ) : ?>

						<div class="logo-img">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo esc_attr( $logo_img ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="logo-img-primary" /></a>
						</div>

					<?php else : ?>

						<div class="logo-text">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
						</div>

					<?php endif; ?>

				</div><!--.logo -->

				<nav class="nav-login" role="navigation">
					<ul>
						<li class="menu-item search"><a href="#" role="link" data-toggle="modal" data-target="#modal-search"><span></span></a></li>
						<li class="menu-item login"><a href="#" role="link" data-toggle="modal" data-target="#modal-login" data-redirect="#" data-show-pre-text="false"><span></span></a></li>
						<li class="menu-item menu"><a href="#" role="link" data-toggle="modal" data-target="#modal-menu"><span></span></a></li>
					</ul>
				</nav><!-- .nav-login -->

				<nav class="nav-main" role="navigation">
					<div class="nav-main-inner">
						<?php if ( has_nav_menu( 'primary' ) ) :

							wp_nav_menu( array(
								'theme_location'	=> 'primary',
								'container'			=> false,
								'menu_class'		=> 'sf-menu',
								'walker' 			=> new Pojo_Navbar_Nav_Walker()
							) );

						endif; ?>
					</div>
				</nav><!--/#nav-menu -->

			</div><!-- /.container -->
		</header>

	<?php endif; // end blank page ?>

	<?php po_change_loop_to_parent(); ?>

	<?php pojo_print_titlebar(); ?>

	<div id="primary">
		<div class="<?php echo WRAP_CLASSES; ?>">
			<div id="content" class="<?php echo CONTAINER_CLASSES; ?>">