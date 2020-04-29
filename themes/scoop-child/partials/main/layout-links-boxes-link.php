<?php
/**
 * Main links boxes layout link
 *
 * @author      Nir Goldberg
 * @package     scoop-child/partials/main
 * @version     1.7.6
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Variables
 */
$title			= $link[ 'title' ];
$url			= $link[ 'link' ];
$target			= $link[ 'target' ];
$image			= $link[ 'image' ];
$left_margin	= $link[ 'left_margin' ];
$right_margin	= $link[ 'right_margin' ];
$img_wrap_style	= '';

if ( ! $title || ! $url || ! $target || ! $image )
	return;

?>

<style>
	@media (max-width: 767px) {
		.main-links-boxes .link-wrap-<?php echo $index; ?> a .img-wrap {
			left: <?php echo $left_margin[ 'mobile' ] ? floatval( $left_margin[ 'mobile' ] ) . '%' : 'auto'; ?>;
			right: <?php echo $right_margin[ 'mobile' ] ? floatval( $right_margin[ 'mobile' ] ) . '%' : 'auto'; ?>;
		}
	}
	@media (min-width: 768px) {
		.main-links-boxes .link-wrap-<?php echo $index; ?> a .img-wrap {
			left: <?php echo $left_margin[ 'desktop' ] ? floatval( $left_margin[ 'desktop' ] ) . '%' : 'auto'; ?>;
			right: <?php echo $right_margin[ 'desktop' ] ? floatval( $right_margin[ 'desktop' ] ) . '%' : 'auto'; ?>;
		}
	}
</style>

<div class="link-wrap link-wrap-<?php echo $index; ?> col-sm-6">
	<a href="<?php echo $url; ?>" target="_<?php echo $target; ?>">

		<div class="img-wrap">
			<img src="<?php echo $image[ 'url' ]; ?>" alt="<?php echo $image[ 'alt' ]; ?>" />
		</div>

		<div class="title-wrap">
			<div class="title"><?php echo $title; ?></div>
			<div class="more"><?php echo strtoupper( __( 'Read more', 'kulam-scoop' ) ); ?></div>
		</div>

	</a>
</div>