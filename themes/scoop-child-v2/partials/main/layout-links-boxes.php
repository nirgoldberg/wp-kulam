<?php
/**
 * Main links boxes layout
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
$links			= get_sub_field( 'links' );
$top_padding	= get_sub_field( 'top_padding' );
$bottom_padding	= get_sub_field( 'bottom_padding' );
$layout_style	= '';
$index			= 0;

if ( ! $links )
	return;

$layout_style	.= $top_padding ? 'padding-top:' . $top_padding . 'px;' : '';
$layout_style	.= $bottom_padding ? 'padding-bottom:' . $bottom_padding . 'px;' : '';

?>

<div class="main-links-boxes" <?php echo $layout_style ? 'style="' . $layout_style . '"' : ''; ?>>
	<div class="links-boxes-wrap container">
		<div class="row">

			<?php foreach ( $links as $link ) {

				// increment $index
				$index++;

				/**
				 * Display link
				 */
				include( locate_template( 'partials/main/layout-links-boxes-link.php' ) );

			} ?>

		</div>
	</div>
</div><!-- .main-links-boxes -->