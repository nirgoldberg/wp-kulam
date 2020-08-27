<?php
/**
 * Post functions
 *
 * @author		Nir Goldberg
 * @package		scoop-child/partials
 * @version		2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_get_post
 *
 * This function returns post box HTML
 * Used in recent posts module and archives
 *
 * @param	N/A
 * @return	(string)
 */
function kulam_get_post( $taxonomies = array() ) {

	// vars
	global $post;
	$output = '';

	// get post thumbnail ID
	$thumbnail_id = get_post_thumbnail_id( $post->ID );

	$image_html = '';

	if ( ! empty( $thumbnail_id ) ) {

		$attachment_src = wp_get_attachment_image_src( $thumbnail_id, 'large' );

		if ( $attachment_src ) {
			$attachment_url = $attachment_src[0];
			$image_title = get_the_title();
			$image_html = sprintf( '<div class="post-image"><img src="%1$s" alt="%2$s" /></div>', $attachment_url, esc_attr( $image_title ) );
		}

	}

	// get post terms
	$terms = $taxonomies ? wp_get_post_terms( $post->ID, $taxonomies, array( 'fields' => 'all' ) ) : array();

	// display
	if ( ! empty( $image_html ) ) {

		$classes = implode( ' ', array_map( function( $v ){ return 'term_' . $v->term_id; }, $terms ) );

		// build term_names list
		$terms_list = array();

		foreach ( $terms as $term ) {
			$terms_list[] = '<li>' . $term->name . '</li>';
		}

		$terms_list = $terms_list ? '<ul class="terms_list">' . implode( ' ', $terms_list ) . '</ul>' : '';

		$output =	'<div class="col-md-3 col-sm-6 ' . $classes . '">' .
						'<a href="' . get_the_permalink() . '">' .
							'<div class="post-wrap">' .
								$image_html .
								'<div class="post-meta">' .
									'<div class="title">' . get_the_title() . '</div>' .
									$terms_list .
								'</div>' .
								'<div class="more"><span>' . __( 'Read more', 'scoop-child' ) . '</span></div>' .
							'</div>' .
						'</a>' .
					'</div>';

	}

	// return
	return $output;

}