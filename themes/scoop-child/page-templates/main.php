<?php
/**
 * Template Name: Main
 *
 * @author		Nir Goldberg
 * @package		scoop-child
 * @version		1.7.6
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_field' ) )
	return;

get_header();

?>

<div class="main-wrapper col-sm-12">

<?php

	if ( have_rows( 'acf-main_template_layouts' ) ) : ?>

		<div class="main-layouts">

			<?php while ( have_rows( 'acf-main_template_layouts' ) ) : the_row();

				$layout = get_row_layout();

				switch ( $layout ) :

					case 'banner' :

						/**********/
						/* banner */
						/**********/

						get_template_part( 'partials/main/layout', 'banner' );

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

<div class="container">

	<?php
		/**
		 * the_content()
		 */
		the_content();
	?>

</div>

<?php get_footer(); ?>