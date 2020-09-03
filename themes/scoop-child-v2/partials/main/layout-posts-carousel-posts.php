<?php
/**
 * Main posts carousel layout posts
 *
 * @author		Nir Goldberg
 * @package		scoop-child/partials/main
 * @version		2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// vars
$slides			= array();
$wrapper_width	= '100%';

if ( empty( $slide_width ) || 0 === $slide_width ) {
	$slide_width = '350';
}

if ( $posts ) {
	foreach ( $posts as $p ) {

		// get post thumbnail ID
		$thumbnail_id = get_post_thumbnail_id( $p->ID );

		$image_html = '';

		if ( ! empty( $thumbnail_id ) ) {

			$attachment_src = wp_get_attachment_image_src( $thumbnail_id, 'large' );

			if ( $attachment_src ) {
				$attachment_url	= $attachment_src[0];
				$image_title	= get_the_title();
				$image_classes	= array( 'carousel-image' );
				$image_html		=	sprintf( '<div class="post-image"><img src="%1$s" alt="%2$s" title="%2$s" class="%3$s" /></div>',
										$attachment_url,
										esc_attr( $image_title ),
										esc_attr( implode( ' ', $image_classes ) )
									);
			}

		}

		if ( ! empty( $image_html ) ) {

			$slides[] =	sprintf(
							'<div class="slide">
								<div style="width: %1$s">
									<a href="%2$s">
										<div class="post-wrap">
											%3$s
											<div class="post-info">
												<div class="post-meta">
													<div class="title">%4$s</div>
												</div>
												<div class="more"><span>%5$s</span></div>
											</div>
										</div>
									</a>
								</div>
							</div>',

							esc_attr( $wrapper_width ),
							get_permalink( $p->ID ),
							$image_html,
							$p->post_title,
							__( 'Read more', 'scoop-child' )
						);

		}

	}
}

if ( ! $slides )
	return;

// build the carousel wrapper and integrate $slides
$js_array = array();

$js_array[ 'slideWidth' ]	= $slide_width;
$js_array[ 'minSlides' ]	= absint( $minimum_slides );
$js_array[ 'maxSlides' ]	= absint( $maximum_slides );
$js_array[ 'moveSlides' ]	= absint( $move_slides );

if ( ! $slide_margin && '0' !== $slide_margin ) {
	$slide_margin = 10;
}
$js_array[ 'slideMargin' ]	= absint( $slide_margin );

$meta = absint( $slide_duration );
if ( empty( $meta ) || 0 === $meta )
	$meta = 10000;
$js_array[ 'pause' ] = $meta;

$meta = absint( $transition_speed );
if ( empty( $meta ) || 0 === $meta )
	$meta = 100;

$js_array[ 'speed' ]		= $meta;
$js_array[ 'captions' ]		= false;
$js_array[ 'autoStart' ]	= 'off' !== $auto_play;
$js_array[ 'autoHover' ]	= 'off' !== $auto_pause_hover;

$js_array[ 'auto' ]			= true;

$js_array[ 'pager' ]		= 'bullets' === $navigation || 'both' === $navigation;
$js_array[ 'controls' ]		= empty( $navigation ) || 'both' === $navigation;

$js_array[ 'touchEnabled' ]	= false;

$js_json	= ! empty( $js_array ) ? json_encode( $js_array ) : '';
$print_js	= '<script>jQuery(function($){$("div.pojo-slideshow-' . $id . '").bxSlider(' . $js_json . ');});</script>';

$output .=	sprintf(
				'%s<div class="posts-wrap"><div style="width: %s; direction: ltr;" class="pojo-slideshow%s"><div class="pojo-slideshow-%s pojo-slideshow-wrapper">%s</div></div></div>',
				$print_js,
				esc_attr( $wrapper_width ),
				$js_array[ 'pager' ] ? ' slideshow-bullets' : '',
				$id,
				implode( '', $slides )
			);