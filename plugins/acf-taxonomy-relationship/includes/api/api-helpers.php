<?php

/*
*  acf_get_grouped_taxonomies
*
*  This function will return all taxonomies grouped by post_type
*  This is handy for select settings
*
*  @type	function
*  @date	27/02/2014
*  @since	5.0.0
*
*  @param	$args (array)
*  @return	(array)
*/

function acf_get_grouped_taxonomies( $args ) {

	// vars
	$data = array();


	// defaults
	$args = wp_parse_args( $args, array(
		'post_type'	=> 'post',
	));


	// find array of post_type
	$post_types = acf_get_array( $args[ 'post_type' ] );
	$post_types_labels = acf_get_pretty_post_types( $post_types );


	// loop
	foreach( $post_types as $post_type ) {

		// vars
		$this_taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$this_group = array();


		// bail early if no taxonomies for this post type
		if( empty( $this_taxonomies ) ) continue;


		// populate $this_group
		foreach( $this_taxonomies as $taxonomy ) {
			$this_group[ $taxonomy->name ] = $taxonomy;
		}


		// group by post type
		$label = $post_types_labels[ $post_type ];
		$data[ $label ] = $this_group;

	}


	// return
	return $data;

}


/*
*  acf_order_taxonomies_by_search
*
*  This function will order a taxonomies array by search input
*
*  @type	function
*  @date	21/07/2020
*  @since	5.0.0
*
*  @param	$array (array)
*  @param	$search (string)
*  @return	(array)
*/

function acf_order_taxonomies_by_search( $array, $search ) {

	// vars
	$weights = array();
	$needle = strtolower( $search );


	// add key prefix
	foreach( array_keys($array) as $k ) {

		$array[ '_' . $k ] = acf_extract_var( $array, $k );

	}


	// add search weight
	foreach( $array as $k => $v ) {

		// vars
		$weight = 0;
		$haystack = strtolower( $v->labels->singular_name );
		$strpos = strpos( $haystack, $needle );


		// detect search match
		if( $strpos !== false ) {

			// set eright to length of match
			$weight = strlen( $search );


			// increase weight if match starts at begining of string
			if( $strpos == 0 ) {

				$weight++;

			}

			// append to wights
			$weights[ $k ] = $weight;

		} else {

			unset( $array[ $k ] );

		}

	}


	// sort the array with menu_order ascending
	array_multisort( $weights, SORT_DESC, $array );


	// remove key prefix
	foreach( array_keys( $array ) as $k ) {

		$array[ substr( $k, 1 ) ] = acf_extract_var( $array, $k );

	}


	// return
	return $array;

}