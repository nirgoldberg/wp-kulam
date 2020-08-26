<?php
/**
 * Main banner layout slide
 *
 * @author      Nir Goldberg
 * @package     scoop-child/partials/main
 * @version     2.0.0
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
$image					= $slide[ 'image' ];

$title_font_att			= $title_font_att[ 'override_defaults' ]		? $title_font_att		: $def_title_font_att;
$short_desc_font_att	= $short_desc_font_att[ 'override_defaults' ]	? $short_desc_font_att	: $def_short_desc_font_att;

$title_top_margin			= $title_font_att[ 'top_margin' ] !== false	? kulam_px_to_vw( $title_font_att[ 'top_margin' ] ) . 'vw' : '';
$title_font_size_desktop	= $title_font_att[ 'font_size_desktop' ]	? kulam_px_to_vw( $title_font_att[ 'font_size_desktop' ] ) . 'vw' : '';
$title_font_size_mobile		= $title_font_att[ 'font_size_mobile' ]		? kulam_px_to_vw( $title_font_att[ 'font_size_mobile' ], 767 ) . 'vw' : '';

$title_style_desktop	= '';
$title_style_desktop	.= $title_top_margin							? 'margin-top:' . $title_top_margin . ';' : '';
$title_style_desktop	.= $title_font_att[ 'font_family' ]				? 'font-family:\'' . $title_font_att[ 'font_family' ] . '\';' : '';
$title_style_desktop	.= $title_font_att[ 'font_weight' ]				? 'font-weight:' . $title_font_att[ 'font_weight' ] . ';' : '';

$title_style_mobile		.= $title_style_desktop;

$title_style_desktop	.= $title_font_size_desktop						? 'font-size:' . $title_font_size_desktop . ';line-height:' . $title_font_size_desktop . ';' : '';
$title_style_mobile		.= $title_font_size_mobile						? 'font-size:' . $title_font_size_mobile . ';line-height:' . $title_font_size_mobile . ';' : '';

$short_desc_style		= '';
$short_desc_style		.= $short_desc_font_att[ 'font_size' ]			? 'font-size:' . $short_desc_font_att[ 'font_size' ] . 'px;line-height:1.25;' : '';
$short_desc_style		.= $short_desc_font_att[ 'font_weight' ]		? 'font-weight:' . $short_desc_font_att[ 'font_weight' ] . ';' : '';
$short_desc_style		.= $short_desc_font_att[ 'font_color' ]			? 'color:' . $short_desc_font_att[ 'font_color' ] . ';' : '';

if ( ! $title || ! $color || ! $image )
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

if ( $link ) {
	$link_event = ( 'self' == $link_target ) ? 'location.href=\'' . $link . '\'' : 'window.open(\'' . $link . '\')';
}

?>

<div class="slide <?php echo $link ? 'linked' : ''; ?>" <?php echo $link ? 'onclick="' . $link_event . '"' : ''; ?>>

	<div class="image-wrap">

		<div class="image">
			<img src="<?php echo $image[ 'url' ]; ?>" alt="<?php echo $image[ 'alt' ]; ?>" />
		</div>

	</div>

	<div class="text-wrap" <?php echo $text_wrap_style ? 'style="' . $text_wrap_style . '"' : ''; ?>>

		<div class="controls visible-xs">
			<div class="control cycle-next"></div>
			<span class="cycle-caption"></span>
		</div>

		<div class="title hidden-xs" <?php echo $title_style_desktop ? 'style="' . $title_style_desktop . '"' : ''; ?>><?php echo $title; ?></div>
		<div class="title visible-xs" <?php echo $title_style_mobile ? 'style="' . $title_style_mobile . '"' : ''; ?>><?php echo $title; ?></div>
		<div class="short-desc" <?php echo $short_desc_style ? 'style="' . $short_desc_style . '"' : ''; ?>><?php echo $short_desc; ?></div>

		<?php if ( $link ) {
			echo '<a class="button kulam-button">' . __( 'Plus', 'kulm-scoop' ) . '</a>';
		} ?>

		<div class="desc"><?php echo $desc; ?></div>

	</div>

</div>