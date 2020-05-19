<?php
/**
 * Main banner layout slide
 *
 * @author      Nir Goldberg
 * @package     scoop-child/partials/main
 * @version     1.7.13
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Variables
 */
$title			= $slide[ 'title' ];
$short_desc		= $slide[ 'short_description' ];
$desc			= $slide[ 'description' ];
$link			= $slide[ 'link' ];
$link_target	= $slide[ 'link_target' ];
$color			= $slide[ 'text_color' ];
$bg_image		= $slide[ 'background_image' ];
$image			= $slide[ 'image' ];

if ( ! $title || ! $color || ! $bg_image || ! $image )
	return;

$text_wrap_style = '';
$text_wrap_style .= $color ? 'color: ' . $color . ';' : '';
$text_wrap_style .= $bg_image ? 'background-image: url(\'' . $bg_image[ 'url' ] . '\');' : '';

if ( $link ) {
	$link_event = ( 'self' == $link_target ) ? 'location.href=\'' . $link . '\'' : 'window.open(\'' . $link . '\')';
}

?>

<div class="slide <?php echo $link ? 'linked' : ''; ?>" <?php echo $link ? 'onclick="' . $link_event . '"' : ''; ?>>

	<div class="text-wrap" <?php echo $text_wrap_style ? 'style="' . $text_wrap_style . '"' : ''; ?>>

		<div class="title"><?php echo $title; ?></div>
		<div class="short-desc"><?php echo $short_desc; ?></div>
		<div class="desc"><?php echo $desc; ?></div>
		<div class="control cycle-prev visible-xs"></div>
		<div class="control cycle-next visible-xs"></div>

	</div>

	<div class="image-wrap">

		<div class="image">
			<img src="<?php echo $image[ 'url' ]; ?>" alt="<?php echo $image[ 'alt' ]; ?>" />
		</div>

	</div>

</div>