<?php
/**
 * Main banner layout
 *
 * @author      Nir Goldberg
 * @package     scoop-child/partials/main
 * @version     2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_field' ) )
	return;

/**
 * Variables
 */
$slides						= get_sub_field( 'slides' );
$def_title_font_att			= get_sub_field( 'title_font_attributes' );
$def_short_desc_font_att	= get_sub_field( 'short_description_font_attributes' );
$top_padding				= get_sub_field( 'top_padding' );
$bottom_padding				= get_sub_field( 'bottom_padding' );

$layout_style			= '';

if ( ! $slides )
	return;

$layout_style	.= $top_padding ? 'padding-top:' . $top_padding . 'px;' : '';
$layout_style	.= $bottom_padding ? 'padding-bottom:' . $bottom_padding . 'px;' : '';

?>

<div class="main-banner" <?php echo $layout_style ? 'style="' . $layout_style . '"' : ''; ?>>
	<div class="cycle-slideshow"
		data-cycle-auto-height="calc"
		data-cycle-fx="scrollHorz"
		data-cycle-loader=true
		data-cycle-log=false
		data-cycle-slides="> .slide"
		data-cycle-swipe=true
		data-cycle-timeout="6000"
		data-cycle-caption=".cycle-caption"
		data-cycle-caption-template="{{slideNum}}/{{slideCount}}"
	>

		<?php foreach ( $slides as $slide ) {

			/**
			 * Display slide
			 */
			include( locate_template( 'partials/main/layout-banner-slide.php' ) );

		} ?>

		<div class="controls hidden-xs">
			<div class="control cycle-next"></div>
			<span class="cycle-caption"></span>
		</div>

	</div>
</div><!-- .main-banner -->