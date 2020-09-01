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

/**
 * kulam_is_restricted_content
 *
 * This function returns true if the current user is allowed to get the current post content
 * according to the current user state
 *
 * @param	N/A
 * @return	(bool)
 */
function kulam_is_restricted_content() {

	if ( ! function_exists( 'get_field' ) )
		return true;

	// vars
	$user_state		= kulam_get_current_user_state();
	$restrict_post	= get_field( 'acf-post_restrict_post' );

	if ( ! $restrict_post )
		return false;

	switch ( $user_state ) {

		case 'hmembership_member' :

			return false;

		case 'logged_in' :

			if ( 'hmembership_member' == $restrict_post ) {
				return true;
			}

			return false;

		case 'public' :

			if ( in_array( $restrict_post, array( 'hmembership_member', 'logged_in' ) ) ) {
				return true;
			}

			return false;

	}

	// return
	return false;

}

/**
 * kulam_get_current_user_state
 *
 * This function returns the current user state
 * Possible options: hmembership_member | logged_in | public
 *
 * @param	N/A
 * @return	(string)
 */
function kulam_get_current_user_state() {

	// vars
	$user_state = 'public';

	if ( is_user_logged_in() ) {

		$user = wp_get_current_user();
		$roles = ( array ) $user->roles;

		if ( in_array( 'hmembership_member', $roles ) ) {
			$user_state = 'hmembership_member';
		}
		else {
			$user_state = 'logged_in';
		}

	}

	// return
	return $user_state;

}