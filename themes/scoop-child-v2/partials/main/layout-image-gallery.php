<?php
/**
 * Main image gallery layout
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
$gallery		= get_sub_field( 'gallery' );
$top_padding	= get_sub_field( 'top_padding' );
$bottom_padding	= get_sub_field( 'bottom_padding' );

if ( ! $title || ! $gallery )
	return;

$layout_style	.= $top_padding ? 'padding-top:' . $top_padding . 'px;' : '';
$layout_style	.= $bottom_padding ? 'padding-bottom:' . $bottom_padding . 'px;' : '';

?>

<div class="main-image-gallery" <?php echo $layout_style ? 'style="' . $layout_style . '"' : ''; ?>>
	<div class="image-gallery-wrap container">

		<div class="main-layout-title-wrap">

			<h2><?php echo $title; ?></h2>
			<?php echo $sub_title ? '<div class="sub-title">' . $sub_title . '</div>' : ''; ?>

		</div>

		<div class="gallery-wrap row">

			<?php foreach ( $gallery as $image ) { ?>

				<div class="col-20 col-md-3 col-sm-4">
					<div class="image-wrap">
						<img src="<?php echo $image[ 'url' ]; ?>" alt="<?php echo $image[ 'alt' ]; ?>" />
					</div>
				</div>

			<?php } ?>

		</div>

	</div>
</div><!-- .main-image-gallery -->