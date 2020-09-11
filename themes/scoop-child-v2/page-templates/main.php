<?php
/**
 * Template Name: Main
 *
 * @author		Nir Goldberg
 * @package		scoop-child
 * @version		2.0.4
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_field' ) )
	return;

get_header();

// layout index
$index = 0;

?>

<div class="main-wrapper col-sm-12">

<?php

	if ( have_rows( 'acf-main_template_layouts' ) ) : ?>

		<div class="main-layouts">

			<?php while ( have_rows( 'acf-main_template_layouts' ) ) : the_row();

				$index++;
				$layout = get_row_layout();

				switch ( $layout ) :

					case 'banner' :

						/**********/
						/* banner */
						/**********/

						get_template_part( 'partials/main/layout', 'banner' );

						break;

					case 'recent_posts' :

						/****************/
						/* recent_posts */
						/****************/

						get_template_part( 'partials/main/layout', 'recent-posts' );

						break;

					case 'posts_carousel' :

						/****************/
						/* posts_carousel */
						/****************/

						include( locate_template( 'partials/main/layout-posts-carousel.php' ) );

						break;

					case 'image' :

						/*********/
						/* image */
						/*********/

						get_template_part( 'partials/main/layout', 'image' );

						break;

					case 'image_gallery' :

						/******************/
						/* image_gallery */
						/******************/

						get_template_part( 'partials/main/layout', 'image-gallery' );

						break;

					case 'links_strip' :

						/***************/
						/* links strip */
						/***************/

						get_template_part( 'partials/main/layout', 'links-strip' );

						break;

					case 'links_boxes' :

						/***************/
						/* links boxes */
						/***************/

						get_template_part( 'partials/main/layout', 'links-boxes' );

						break;

				endswitch;

			endwhile; ?>

		</div>

	<?php endif;

?>

</div><!-- .main-wrapper -->

<div class="col-sm-12">

	<?php
		/**
		 * the_content()
		 */
		the_content();
	?>

	<?php
		/**
		 * Display scroll up button
		 */
		get_template_part( 'partials/scroll-top' );
	?>

</div>

<?php get_footer(); ?>