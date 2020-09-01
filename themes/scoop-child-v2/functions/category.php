<?php
/**
 * Category functions
 *
 * @author		Nir Goldberg
 * @package		scoop-child/partials
 * @version		2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_get_category_filter_term_fields
 *
 * This function generates and returns the category filter term fields
 *
 * @param	$category (object)
 * @return	(string)
 */
function kulam_get_category_filter_term_fields( $category ) {

	if ( ! $category )
		return '';

	//vars
	$filters	= kulam_get_category_filters( $category );
	$output		= '';

	if ( ! $filters || ! is_array( $filters ) )
		return '';

	foreach ( $filters as $taxonomy ) {

		$parent = 0;

		// modify $parent in case of caategory taxonomy in order to list direct children only
		if ( 'category' == $taxonomy->name ) {
			$parent = $category->term_id;
		}

		$terms = kulam_get_taxonomy_hierarchy( $taxonomy->name, $parent );

		// build HTML output
		$output .= '<ul class="checkbox-list" data-tax="' . $taxonomy->name . '">';

		$output .= kulam_get_category_filter_terms( $terms );

		$output .= '</li></ul>';

	}

	// return
	return $output;

}

/**
 * kulam_get_category_filters
 *
 * This function returns category filter taxonomies for current category
 *
 * @param	$category (object)
 * @return	(mixed) Array of taxonomy objects or false in case of failure
 */
function kulam_get_category_filters( $category ) {

	if ( ! function_exists( 'get_field' ) || ! $category )
		return false;

	// vars
	$category_filters	= get_field( 'acf-category_filters', 'category_' . $category->term_id );
	$category_filters	= $category_filters ? $category_filters : array();

	// add category taxonomy to $category_filters in case not exists
	if ( ! in_array( 'category', array_map( function( $tax ){ return $tax->name; }, $category_filters ) ) ) {
		array_unshift( $category_filters, get_taxonomy( 'category' ) );
	}

	// return
	return $category_filters;

}

/**
 * kulam_get_category_filter_terms
 *
 * This function recursively returns terms in HTML LIs structure
 *
 * @param	$terms (array) term children are store within $term->children
 * @return	(string)
 */
function kulam_get_category_filter_terms( $terms ) {

	if ( ! $terms || ! is_array( $terms ) )
		return;

	// vars
	$output = '';

	// loop
	foreach ( $terms as $term ) {

		$has_children = isset( $term->children ) && is_array( $term->children ) && count( $term->children );

		$output .= sprintf( '<li data-id="term_%1$s"><label><input type="checkbox" name="%2$s[]" value="%1$s" %3$s> <span>%4$s</span>%5$s</label>',
			$term->term_id,
			$term->taxonomy,
			isset( $_GET[ 'filters' ][ 'category' ] ) && is_array( $_GET[ 'filters' ][ 'category' ] ) ? checked( in_array( $term->term_id, $_GET[ 'filters' ][ 'category' ] ), 1, false ) : '',
			$term->name,
			$has_children ? '<span class="expand"></span>' : ''
		);

		if ( $has_children ) {

			$output .= '<ul class="children">';

			$output .= kulam_get_category_filter_terms( $term->children );

			$output .= '</ul></li>';

		}

	}

	// return
	return $output;

}