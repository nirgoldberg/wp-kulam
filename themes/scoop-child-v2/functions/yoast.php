<?php
/**
 * Yoast
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions
 * @version     1.5.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * This function returns the primary term for the chosen taxonomy set by Yoast SEO
 * or the first term selected.
 *
 * @param	$post (int) Post id
 * @param	$taxonomy (string) Taxonomy to query. Defaults to category
 * @return	(mix) The term object or empty string in case of failure
 */
function kulam_get_primary_taxonomy_term( $post = 0, $taxonomy = 'category' ) {

	if ( ! $post ) {
		$post = get_the_ID();
	}

	$terms        = get_the_terms( $post, $taxonomy );
	$primary_term = '';

	if ( $terms ) {

		if ( class_exists( 'WPSEO_Primary_Term' ) ) {

			$wpseo_primary_term = new WPSEO_Primary_Term( $taxonomy, $post );
			$wpseo_primary_term = $wpseo_primary_term->get_primary_term();
			$term               = get_term( $wpseo_primary_term );

			if ( is_wp_error( $term ) ) {
				$term = $terms[0];
			}

		} else {
			$term = $terms[0];
		}

	}

	if ( $term ) {
		$primary_term = $term;
	}

	// return
	return $primary_term;
}