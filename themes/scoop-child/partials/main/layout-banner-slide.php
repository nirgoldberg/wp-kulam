<?php
/**
 * Main banner layout slide
 *
 * @author      Nir Goldberg
 * @package     scoop-child/partials/main
 * @version     1.7.26
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Variables
 */
$title					= $slide[ 'title' ];
$title_font_att			= $slide[ 'title_font_attributes' ];
$short_desc				= $slide[ 'short_description' ];
$short_desc_font_att	= $slide[ 'short_description_font_attributes' ];
$desc					= $slide[ 'description' ];
$link					= $slide[ 'link' ];
$link_target			= $slide[ 'link_target' ];
$color					= $slide[ 'text_color' ];
$bg_image				= $slide[ 'background_image' ];
$image					= $slide[ 'image' ];

$title_font_att			= $title_font_att[ 'override_defaults' ]		? $title_font_att		: $def_title_font_att;
$short_desc_font_att	= $short_desc_font_att[ 'override_defaults' ]	? $short_desc_font_att	: $def_short_desc_font_att;

$title_style			= '';
$title_style			.= $title_font_att[ 'top_margin' ] !== false	? 'margin-top:' . $title_font_att[ 'top_margin' ] . 'px;' : '';
$title_style			.= $title_font_att[ 'font_family' ]				? 'font-family:\'' . $title_font_att[ 'font_family' ] . '\';' : '';
$title_style			.= $title_font_att[ 'font_size' ]				? 'font-size:' . $title_font_att[ 'font_size' ] . 'px;line-height:' . $title_font_att[ 'font_size' ] . 'px;' : '';
$title_style			.= $title_font_att[ 'font_weight' ]				? 'font-weight:' . $title_font_att[ 'font_weight' ] . ';' : '';

$short_desc_style		= '';
$short_desc_style		.= $short_desc_font_att[ 'font_size' ]			? 'font-size:' . $short_desc_font_att[ 'font_size' ] . 'px;line-height:1.25;' : '';
$short_desc_style		.= $short_desc_font_att[ 'font_weight' ]		? 'font-weight:' . $short_desc_font_att[ 'font_weight' ] . ';' : '';
$short_desc_style		.= $short_desc_font_att[ 'font_color' ]			? 'color:' . $short_desc_font_att[ 'font_color' ] . ';' : '';

if ( ! $title || ! $color || ! $bg_image || ! $image )
	return;

// Add Google Font
if ( $title_font_att[ 'font_family' ] ) {

	$font = $title_font_att[ 'font_family' ];

	add_filter( 'kulam_embed_google_fonts', function( $fonts ) use ( $font ) {

		$added_fonts[] = array(
			'family'	=> $font,
			'type'		=> htmline_acf_web_fonts::get_font_type( $font ),
		);

		// return
		return array_merge( $fonts, $added_fonts );

	});

}

$text_wrap_style = '';
$text_wrap_style .= $color ? 'color: ' . $color . ';' : '';
$text_wrap_style .= $bg_image ? 'background-image: url(\'' . $bg_image[ 'url' ] . '\');' : '';

if ( $link ) {
	$link_event = ( 'self' == $link_target ) ? 'location.href=\'' . $link . '\'' : 'window.open(\'' . $link . '\')';
}

?>

<div class="slide <?php echo $link ? 'linked' : ''; ?>" <?php echo $link ? 'onclick="' . $link_event . '"' : ''; ?>>

	<div class="text-wrap" <?php echo $text_wrap_style ? 'style="' . $text_wrap_style . '"' : ''; ?>>

		<div class="title" <?php echo $title_style ? 'style="' . $title_style . '"' : ''; ?>><?php echo $title; ?></div>
		<div class="short-desc" <?php echo $short_desc_style ? 'style="' . $short_desc_style . '"' : ''; ?>><?php echo $short_desc; ?></div>
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