<?php
/**
 * Helper functions
 *
 * @author		Nir Goldberg
 * @package		functions
 * @version		2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_trim_str
 *
 * This function trims special characters from a given string
 *
 * @param	$str (string)
 * @return	(string)
 */
function kulam_trim_str( $str ) {

	// return
	return str_replace( array("\r\n", "\n", "\r"), array("\\r\\n", "\\n", "\\r"), $str );

}

/**
 * kulam_px_to_vw
 *
 * This function converts px to vw
 * Assumes px size in 1920 viewport width
 *
 * @param	$font_size (int)
 * @return	(float)
 */
function kulam_px_to_vw ( $font_size, $viewport = 1920 ) {

	if ( ! $font_size )
		return;

	// return
	return $font_size * 100 / $viewport;

}

/**
 * kulam_get_taxonomy_hierarchy
 *
 * This function recursively returns taxonomy terms hierarchy
 *
 * @param	$taxonomy (string)
 * @param	$parent (int)
 * @return	(array)
 */
function kulam_get_taxonomy_hierarchy( $taxonomy, $parent = 0 ) {

	// get all direct children of $parent ordered by term_order
	$args = array(
		'taxonomy'		=> $taxonomy,
		'orderby'		=> 'term_order',
		'hide_empty'	=> 0,
		'parent'		=> $parent,
	);
	$terms = get_terms( $args );

	// prepare a new array. these are the children of $parent
	// we'll ultimately copy all the $terms into this new array, but only after they
	// find their own children
	$children = array();

	if ( is_wp_error( $terms ) || ! is_array( $terms ) )
		return $children;

	// loop through all the direct decendants of $parent and gather their children
	foreach ( $terms as $term ) {

		// recurse to get the direct decendants of "this" term
		$term->children = kulam_get_taxonomy_hierarchy( $taxonomy, $term->term_id );

		// add the term to our new array
		$children[ $term->term_id ] = $term;

	}

	// return
	return $children;

}