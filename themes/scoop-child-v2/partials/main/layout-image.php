<?php
/**
 * Main image layout
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
$title			= get_sub_field( 'title' );
$sub_title		= get_sub_field( 'sub_title' );
$image			= get_sub_field( 'image' );
$top_padding	= get_sub_field( 'top_padding' );
$bottom_padding	= get_sub_field( 'bottom_padding' );

if ( ! $title || ! $image )
	return;

$layout_style	.= $top_padding ? 'padding-top:' . $top_padding . 'px;' : '';
$layout_style	.= $bottom_padding ? 'padding-bottom:' . $bottom_padding . 'px;' : '';

?>

<div class="main-image" <?php echo $layout_style ? 'style="' . $layout_style . '"' : ''; ?>>
	<div class="image-wrap container">

		<div class="main-layout-title-wrap">

			<h2><?php echo $title; ?></h2>
			<?php echo $sub_title ? '<div class="sub-title">' . $sub_title . '</div>' : ''; ?>

		</div>

		<div class="image-wrap">
			<img src="<?php echo $image[ 'url' ]; ?>" alt="<?php echo $image[ 'alt' ]; ?>" />
		</div>

	</div>
</div><!-- .main-image -->