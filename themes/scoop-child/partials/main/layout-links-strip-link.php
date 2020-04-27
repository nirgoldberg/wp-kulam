<?php
/**
 * Main links strip layout link
 *
 * @author      Nir Goldberg
 * @package     scoop-child/partials/main
 * @version     1.7.6
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Variables
 */
$main_title	= $link[ 'main_title' ];
$sub_title	= $link[ 'sub_title' ];
$url		= $link[ 'link' ];
$target		= $link[ 'target' ];
$image		= $link[ 'image' ];

if ( ! $main_title || ! $url || ! $target || ! $image )
	return;

?>

<div class="link-wrap col-xs-4">
	<a href="<?php echo $url; ?>" target="_<?php echo $target; ?>">

		<div class="img-wrap">
			<img src="<?php echo $image[ 'url' ]; ?>" alt="<?php echo $image[ 'alt' ]; ?>" />
		</div>

		<div class="link-title">
			<div class="main-title"><?php echo $main_title; ?></div>

			<?php if ( $sub_title ) { ?>
				<div class="sub-title hidden-xs"><?php echo $sub_title; ?></div>
			<?php } ?>
		</div>

	</a>
</div>