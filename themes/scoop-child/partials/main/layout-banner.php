<?php
/**
 * Main banner layout
 *
 * @author      Nir Goldberg
 * @package     scoop-child/partials/main
 * @version     1.7.6
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_field' ) )
	return;

/**
 * Variables
 */
$slides	= get_sub_field( 'slides' );

if ( ! $slides )
	return;

?>

<div class="main-banner">
	<ul>

		<?php foreach ( $slides as $slide ) {

			/**
			 * Display slide
			 */
			include( locate_template( 'partials/main/layout-banner-slide.php' ) );

		} ?>

	</ul>
</div><!-- .main-banner -->