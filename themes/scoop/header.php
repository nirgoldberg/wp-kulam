<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$logo_img = get_theme_mod( 'image_logo' ); // Getting from option your choice.
$sticky_logo_img = get_theme_mod( 'image_sticky_header_logo' ); // Getting from option your choice.
if ( ! $sticky_logo_img )
	$sticky_logo_img = $logo_img;

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

<div id="container" class="<?php echo esc_attr( str_replace( '_', '-', $layout_site ) ); ?>">
	<?php po_change_loop_to_parent( 'change' ); ?>
	
	<?php if ( ! pojo_is_blank_page() && ! pojo_elementor_theme_do_location( 'header' ) ) : ?>
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
					<?php if ( pojo_has_nav_menu( 'primary' ) ) : ?>
					<button type="button" class="navbar-toggle visible-xs" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only"><?php _e( 'Toggle navigation', 'pojo' ); ?></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<?php endif; ?>
				</div><!--.logo -->
				<nav class="nav-main" role="navigation">
					<div class="navbar-collapse collapse">
						<div class="nav-main-inner">
							<?php if ( has_nav_menu( 'primary' ) ) : ?>
								<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'sf-menu hidden-xs', 'walker' => new Pojo_Navbar_Nav_Walker() ) );
								wp_nav_menu( array( 'theme_location' => has_nav_menu( 'primary_mobile' ) ? 'primary_mobile' : 'primary', 'container' => false, 'menu_class' => 'mobile-menu visible-xs', 'walker' => new Pojo_Navbar_Nav_Walker() ) ); ?>
								<?php if ( get_theme_mod( 'chk_enable_menu_search' ) && pojo_has_nav_menu( 'primary' ) ) : ?>
									<div class="search-header hidden-xs">
										<a href="javascript:void(0);" class="search-toggle" data-target="#search-section-primary">
											<i class="fa fa-search"></i>
										</a>
									</div>
								<?php endif; ?>
							<?php elseif ( current_user_can( 'edit_theme_options' ) ) : ?>
								<mark class="menu-no-found"><?php printf( __( '<a href="%s">Choose Menu</a>', 'pojo' ), admin_url( 'nav-menus.php?action=locations' ) ); ?></mark>
							<?php endif; ?>
						</div>
					</div>
				</nav><!--/#nav-menu -->
			</div><!-- /.container -->
		</header>

		<?php if ( get_theme_mod( 'chk_enable_menu_search' ) ) : ?>
			<div class="hidden-xs">
				<div id="search-section-primary" class="search-section" style="display: none;">
					<div class="<?php echo WRAP_CLASSES; ?>">
						<?php get_search_form(); ?><i class="fa fa-search"></i>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<section id="sub-header">
			<div class="<?php echo WRAP_CLASSES; ?>">
				<div class="pull-left">
					<?php dynamic_sidebar( 'pojo-' . sanitize_title( 'Sub Header Left' ) ); ?>
				</div>
				<div class="pull-right">
					<?php dynamic_sidebar( 'pojo-' . sanitize_title( 'Sub Header Right' ) ); ?>
				</div>
			</div><!-- .<?php echo WRAP_CLASSES; ?> -->
		</section>

		<div class="sticky-header-running"></div>

		<?php if ( get_theme_mod( 'chk_enable_sticky_header' ) ) :?>
			<div class="sticky-header logo-<?php echo ( 'logo_left' === get_theme_mod( 'header_layout' ) ) ? 'left' : 'right'; ?>">
				<div class="<?php echo WRAP_CLASSES; ?>">
					<div class="logo">
						<?php if ( ! empty( $sticky_logo_img ) ) : ?>
							<div class="logo-img">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo esc_attr( $sticky_logo_img ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="logo-img-secondary" /></a>
							</div>
						<?php else : ?>
							<div class="logo-text">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
							</div>
						<?php endif; ?>
						<?php if ( pojo_has_nav_menu( 'sticky_menu' ) ) : ?>
						<button type="button" class="navbar-toggle visible-xs" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only"><?php _e( 'Toggle navigation', 'pojo' ); ?></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<?php endif; ?>
					</div><!--.logo -->
					<nav class="nav-main" role="navigation">
						<div class="navbar-collapse collapse">
							<div class="nav-main-inner">
								<?php if ( has_nav_menu( 'primary' ) ) : ?>
									<?php wp_nav_menu( array( 'theme_location' => 'sticky_menu', 'container' => false, 'menu_class' => 'sf-menu hidden-xs', 'walker' => new Pojo_Navbar_Nav_Walker() ) );
									wp_nav_menu( array( 'theme_location' => has_nav_menu( 'primary_mobile' ) ? 'primary_mobile' : 'primary', 'container' => false, 'menu_class' => 'mobile-menu visible-xs', 'walker' => new Pojo_Navbar_Nav_Walker() ) ); ?>
									<?php if ( get_theme_mod( 'chk_enable_menu_search' ) && pojo_has_nav_menu( 'sticky_menu' ) ) : ?>
										<div class="search-header hidden-xs">
											<a href="javascript:void(0);" class="search-toggle" data-target="#search-section-sticky">
												<i class="fa fa-search"></i>
											</a>
										</div>
									<?php endif; ?>
								<?php elseif ( current_user_can( 'edit_theme_options' ) ) : ?>
									<mark class="menu-no-found"><?php printf( __( '<a href="%s">Choose Menu</a>', 'pojo' ), admin_url( 'nav-menus.php?action=locations' ) ); ?></mark>
								<?php endif; ?>
							</div>
						</div>
					</nav><!--.nav-menu -->
				</div><!-- /.container -->
				<?php if ( get_theme_mod( 'chk_enable_menu_search' ) ) : ?>
					<div class="hidden-xs">
						<div id="search-section-sticky" class="search-section" style="display: none;">
							<div class="<?php echo WRAP_CLASSES; ?>">
								<?php get_search_form(); ?>
							</div>
						</div>
					</div>
				<?php endif; // Search Menu ?>
			</div>
		<?php endif; // end sticky header ?>

	<?php endif; // end blank page ?>

	<?php po_change_loop_to_parent(); ?>
	<?php pojo_print_titlebar(); ?>

	<div id="primary">
		<div class="<?php echo WRAP_CLASSES; ?>">
			<div id="content" class="<?php echo CONTAINER_CLASSES; ?>">
