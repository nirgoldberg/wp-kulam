<?php
/**
 * Search functions
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions
 * @version     1.1.4
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_search_standard_post_format
 *
 * This function modifies the search query in order to fetch only standard post format posts
 *
 * @param   $query (obj)
 * @return  N/A
 */
function kulam_search_standard_post_format( $query ) {

	if ( ! $query->is_search || ! isset( $_GET[ 'post_format' ] ) || $_GET[ 'post_format' ] != '0' )
		return $query;

	$taxquery = array(
		array(
			'taxonomy' => 'post_format',
			'operator' => 'NOT EXISTS'
		)
	);

	$query->set( 'tax_query', $taxquery );

}
add_action( 'pre_get_posts', 'kulam_search_standard_post_format' );