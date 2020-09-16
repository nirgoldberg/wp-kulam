<?php
/**
 * Main posts carousel layout
 *
 * @author		Nir Goldberg
 * @package		scoop-child/partials/main
 * @version		2.0.6
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_field' ) )
	return;

// vars
$title_arr			= get_sub_field( 'title' );
$title				= $title_arr[ 'title' ];
$title_font_family	= $title_arr[ 'font_family' ];
$title_font_size	= $title_arr[ 'font_size' ];
$title_font_weight	= $title_arr[ 'font_weight' ];
$title_color		= $title_arr[ 'color' ];
$title_bg_color		= $title_arr[ 'background_color' ];
$title_bg_image		= $title_arr[ 'background_image' ];
$title_link			= $title_arr[ 'link' ];

$sub_title			= get_sub_field( 'sub_title' );
$category			= get_sub_field( 'posts_category' );
$number_of_posts	= get_sub_field( 'number_of_posts' );

$slide_title		= get_sub_field( 'slide_title' );
$slide_font_family	= $slide_title[ 'font_family' ];
$slide_font_size	= $slide_title[ 'font_size' ];
$slide_font_weight	= $slide_title[ 'font_weight' ];
$slide_color		= $slide_title[ 'color' ];
$slide_bg_image		= $slide_title[ 'background_image' ];

$carousel_options	= get_sub_field( 'carousel_options' );
$slide_width		= $carousel_options[ 'slide_width' ];
$slide_margin		= $carousel_options[ 'slide_margin' ];
$minimum_slides		= $carousel_options[ 'minimum_slides' ];
$maximum_slides		= $carousel_options[ 'maximum_slides' ];
$move_slides		= $carousel_options[ 'move_slides' ];
$navigation			= $carousel_options[ 'navigation' ];
$transition_speed	= $carousel_options[ 'transition_speed' ];
$slide_duration		= $carousel_options[ 'slide_duration' ];
$auto_play			= $carousel_options[ 'auto_play' ];
$auto_pause_hover	= $carousel_options[ 'auto_pause_hover' ];

$general			= get_sub_field( 'general' );
$scheme_color		= $general[ 'scheme_color' ];

$top_padding		= get_sub_field( 'top_padding' );
$bottom_padding		= get_sub_field( 'bottom_padding' );
$user_state			= kulam_get_current_user_state();

$output				= '';

if ( ! $title || ! $category )
	return;

$layout_style	.= $top_padding ? 'padding-top:' . $top_padding . 'px;' : '';
$layout_style	.= $bottom_padding ? 'padding-bottom:' . $bottom_padding . 'px;' : '';

// get sticky posts
$sticky_posts = get_field( 'acf-category_sticky_posts', 'category_' . $category );

// get posts
$posts = array();

$args = array(
	'category__in'		=> array( $category ),
	'posts_per_page'	=> $number_of_posts > 0 ? $number_of_posts : -1,
);

// modify query according to user state ( hmembership_member | logged_in | public )
if ( in_array( $user_state, array( 'logged_in', 'public' ) ) ) {

	// setup meta_query
	$args[ 'meta_query' ] = array(
		'relation'		=> 'OR',
		array(
			'key'		=> 'acf-post_restrict_post',
			'compare'	=> 'NOT EXISTS',
		),
	);

	if ( 'logged_in' == $user_state ) {
		$value = array( 'public', 'logged_in' );
	}
	else {
		$value = array( 'public' );
	}

	$args[ 'meta_query' ][] = array(
		'key'		=> 'acf-post_restrict_post',
		'value'		=> $value,
		'compare'	=> 'IN',
	);

}

// build array for two queries, including and excluding sticky posts accordingly
if ( $sticky_posts ) {

	$query_args = array(
		array_merge( $args, array( 'post__in' => $sticky_posts, 'orderby' => 'post__in' ) ),
		array_merge( $args, array( 'post__not_in' => $sticky_posts ) ),
	);

} else {
	$query_args = array( $args );
}

// query posts
foreach ( $query_args as $args ) {

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) : while( $query->have_posts() ) : $query->the_post();

		$posts[] = $query->post;

	endwhile; endif; wp_reset_postdata();

}

if ( ! $posts )
	return;

// embed fonts
if ( $title_font_family || $slide_font_family ) {

	add_filter( 'kulam_embed_google_fonts', function( $fonts ) use ( $title_font_family, $slide_font_family ) {

		$new_fonts		= array( $title_font_family, $slide_font_family );
		$added_fonts	= array();

		foreach ( $new_fonts as $font ) {

			if ( $font ) {
				$added_fonts[] = array(
					'family'	=> $font,
					'type'		=> htmline_acf_web_fonts::get_font_type( $font ),
				);
			}

		}

		// return
		return array_merge( $fonts, $added_fonts );

	});

}

// carousel ID
$id = 'main-pc-' . $index;

// generate slide style
$style =
	'<style type="text/css">' .
		( $scheme_color			? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide .post-wrap .post-info {background-color:' . $scheme_color . ';}' : '' ) .
		( $slide_bg_image		? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide .post-wrap .post-info {background-image:url(\'' . $slide_bg_image . '\');}' : '' ) .
		( $slide_font_family	? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide .post-wrap .post-info {font-family:\'' . $slide_font_family . '\';}' : '' ) .
		( $slide_font_size		? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide .post-wrap .post-info .post-meta {font-size:' . $slide_font_size . 'px;line-height:' . $slide_font_size . 'px;}' : '' ) .
		( $slide_font_weight	? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide .post-wrap .post-info .post-meta .title {font-weight:' . $slide_font_weight . ';}' : '' ) .
		( $slide_color			? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide .post-wrap .post-info {color:' . $slide_color . ';}' : '' ) .
	'</style>';

$output .= $style;

$output .=	'<div class="main-posts-carousel" ' . ( $layout_style ? 'style="' . $layout_style . '"' : '' ) . '>' .
				'<div class="posts-carousel-wrap container">' .
					'<!-- Main Post Carousel #' . $id . ' --><div id="kulam-slideshow-' . $id . '" class="kulam-slideshow" data-scheme-color="' . $scheme_color . '">';

// slideshow title
if ( $title ) {

	$style = '';
	$style .= $title_font_family ? 'font-family:\'' . $title_font_family . '\';' : '';
	$style .= $title_font_size ? 'font-size:' . $title_font_size . 'px;line-height:' . $title_font_size . 'px;' : '';
	$style .= $title_font_weight ? 'font-weight:' . $title_font_weight . ';' : '';
	$style .= $title_color ? 'color:' . $title_color . ';' : '';
	$style .= $title_bg_color && ! $title_bg_image ? 'padding:10px;display:inline-block;background-color:' . $title_bg_color . ';' : '';
	$style .= $title_bg_image ? 'height: ' . $title_bg_image[ 'height' ] . 'px;' : '';

	$output .=	'<div class="main-layout-title-wrap">' .
					'<div ' . ( $style ? 'style="' . $style . '"' : '' ) . ' class="kulam-slideshow-title">' .
						( $title_bg_image ? '<img src="' . $title_bg_image[ 'url' ] . '" style="margin-left: -' . intval( $title_bg_image[ 'width' ] )/2 . 'px;" alt="" />' : '' ) .
						'<h2 class="title' . ( $title_bg_image ? ' has-bg-image' : '' ) . '">' .
							( $title_link ? '<a href="' . $title_link . '"' . ( $title_color ? ' style="color:' . $title_color . ';"' : '' ) . '>' : '' ) .
							$title .
							( $title_link ? '</a>' : '' ) .
						'</h2>' .
					'</div>' .

					( $sub_title ? '<div class="sub-title">' . $sub_title . '</div>' : '' ) .
				'</div>';

}

// get posts carousel
include( locate_template( 'partials/main/layout-posts-carousel-posts.php' ) );

$output .=				'<div class="more-posts">' .
							'<a href="' . get_term_link( $category ) . '"><span>' . __( 'View all', 'kulam-scoop' ) . '</span></a>' .
						'</div>' .
					'</div><!-- End of Main Post Carousel #' . $id . ' -->' .
				'</div>' .
			'</div><!-- .main-posts-carousel -->';

// echo
echo $output;