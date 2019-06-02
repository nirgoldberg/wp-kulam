<?php
/**
 * ACF configuration
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions/acf
 * @version		1.2.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_field' ) )
	return;

/**
 * kulam_acf_init
 *
 * This function initializes ACF configuration
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_acf_init() {

	if ( function_exists( 'acf_add_options_page' ) ) {

		acf_add_options_page( array(
			'page_title'	=> __( 'Site Setup', 'kulam-scoop' ),
			'menu_title'	=> __( 'Site Setup', 'kulam-scoop' ),
			'menu_slug'		=> 'site-options',
			'icon_url'		=> 'dashicons-admin-tools',
		));

		acf_add_options_sub_page( array(
			'page_title' 	=> __( 'My Siddur Settings', 'kulam-scoop' ),
			'menu_title' 	=> __( 'My Siddur', 'kulam-scoop' ),
			'menu_slug' 	=> 'acf-options-my-siddur',
			'parent_slug' 	=> 'site-options',
		));

		acf_add_options_sub_page( array(
			'page_title' 	=> __( 'General Settings', 'kulam-scoop' ),
			'menu_title' 	=> __( 'General', 'kulam-scoop' ),
			'menu_slug' 	=> 'acf-options-general',
			'parent_slug' 	=> 'site-options',
		));

	}

}
add_action( 'acf/init', 'kulam_acf_init' );

/**
 * kulam_acf_default_post_types
 *
 * This function modifies category post types selection according to options default
 *
 * @param	$value (mix)
 * @param	$post_id (int)
 * @param	$field (array)
 * @return	(mix)
 */
function kulam_acf_default_post_types( $value, $post_id, $field ) {

	/**
	 * Variables
	 */
	$post_types = get_field( 'acf-option_category_page_post_types', 'option' );

	if ( ! $value && $post_types ) {

		foreach ( $post_types as $post_type ) {

			$value[] = $post_type->term_id;

		}

	}

	// return
	return $value;

}
add_filter( 'acf/load_value/name=acf-category_post_types', 'kulam_acf_default_post_types', 10, 3 );

/**
 * kulam_acf_add_local_field_group_top_posts
 *
 * This function registers ACF field group handling top posts Relationship fields
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_acf_add_local_field_group_top_posts() {

	/**
	 * Variables
	 */
	$post_types = get_terms( array(
		'taxonomy'		=> 'post_types_tax',
		'hide_empty'	=> false,
	));

	if ( ! empty( $post_types ) && ! is_wp_error( $post_types ) && function_exists( 'acf_add_local_field_group' ) ) {

		// register ACF field group
		acf_add_local_field_group( array(
			'key'					=> 'kulam_top_posts',
			'title'					=> __( 'Post Types Top Posts', 'kulam-scoop' ),
			'fields'				=> array(),
			'location'				=> array(
				array(
					array(
						'param'		=> 'taxonomy',
						'operator'	=> '==',
						'value'		=> 'category',
					),
				),
			),
			'menu_order'			=> 99,
			'position'				=> 'normal',
			'style'					=> 'default',
			'label_placement'		=> 'top',
			'instruction_placement'	=> 'label',
			'hide_on_screen'		=> '',
			'active'				=> 1,
			'description'			=> '',
		));

		// register ACF Relationship fields
		foreach ( $post_types as $post_type ) {

			kulam_acf_add_local_field_relationship( $post_type );

		}

	}

}
add_action( 'init', 'kulam_acf_add_local_field_group_top_posts' );

/**
 * kulam_acf_add_local_field_relationship
 *
 * This function registers ACF Relationship field
 *
 * @param	$term (object) Taxonomy term
 * @return	N/A
 */
function kulam_acf_add_local_field_relationship( $term ) {

	if ( function_exists( 'acf_add_local_field' ) && $term ) {

		acf_add_local_field( array(
			'key'				=> 'kulam_top_posts_relationship_' . $term->slug,
			'label'				=> sprintf( __( '%s Top Posts', 'kulam-scoop' ), $term->name ),
			'name'				=> 'kulam_top_posts_relationship_' . $term->slug,
			'type'				=> 'relationship',
			'instructions'		=> '',
			'required'			=> 0,
			'conditional_logic'	=> 0,
			'wrapper'			=> array(
				'width'	=> '',
				'class'	=> 'acf-hidden kulam_top_posts_relationship_' . $term->term_id,
				'id'	=> '',
			),
			'post_type'			=> array(
				0		=> 'post',
			),
			'taxonomy'			=> array(
				0		=> 'post_types_tax:' . $term->slug,
			),
			'filters'			=> array(
				0		=> 'search',
			),
			'elements'			=> '',
			'min'				=> '',
			'max'				=> '',
			'return_format'		=> 'object',
			'parent'			=> 'kulam_top_posts',
		));

	}

}

/**
 * kulam_acf_relationship_query
 *
 * This function filters the Relationship field query in order to include current category
 *
 * @param	$args (array)
 * @param	$field (array)
 * @param	$post_id (int)
 * @return	array
 */
function kulam_acf_relationship_query( $args, $field, $post_id ) {

	if ( 'kulam_top_posts' == $field['parent'] ) {

		/**
		 * Variables
		 */
		$tt = explode( ':', $field[ 'taxonomy' ][0] );

		$args[ 'cat' ]			= $post_id;
		$args[ 'tax_query' ]	= array(
			array(
				'taxonomy'	=> $tt[0],
				'field'		=> 'slug',
				'terms'		=> $tt[1],
			)
		);

	}

	// return
	return $args;

}
add_filter( 'acf/fields/relationship/query', 'kulam_acf_relationship_query', 10, 3 );