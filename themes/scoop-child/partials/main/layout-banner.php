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
	<div class="cycle-slideshow"
		data-cycle-auto-height="calc"
		data-cycle-fx="scrollHorz"
		data-cycle-loader=true
		data-cycle-log=false
		data-cycle-slides="> .slide"
		data-cycle-swipe=true
		data-cycle-timeout="0"
	>

		<?php foreach ( $slides as $slide ) {

			/**
			 * Display slide
			 */
			include( locate_template( 'partials/main/layout-banner-slide.php' ) );

		} ?>

		<div class="control cycle-prev hidden-xs"></div>
		<div class="control cycle-next hidden-xs"></div>

	</div>
</div><!-- .main-banner -->