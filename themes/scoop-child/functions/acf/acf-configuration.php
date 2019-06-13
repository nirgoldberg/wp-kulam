<?php
/**
 * ACF configuration
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions/acf
 * @version		1.2.7
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
	$default_post_types		= get_field( 'acf-option_category_page_post_types', 'option' );
	$default_post_types_ids	= [];

	if ( ! $default_post_types ) {
		$default_post_types = [];
	}
	else {
		foreach ( $default_post_types as $p ) {
			$default_post_types_ids[] = $p->term_id;
		}
	}

	$post_types = get_terms( array(
		'taxonomy'		=> 'post_types_tax',
		'hide_empty'	=> false,
		'exclude'		=> $default_post_types_ids,
	));

	if ( ( ( ! empty( $default_post_types ) ) || ( ! empty( $post_types ) && ! is_wp_error( $post_types ) ) ) && function_exists( 'acf_add_local_field_group' ) ) {

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

		// register ACF Relationship fields (default post types)
		if ( ! empty( $default_post_types ) ) {
			foreach ( $default_post_types as $post_type ) {

				kulam_acf_add_local_field_relationship( $post_type, true );

			}
		}

		// register ACF Relationship fields (rest of post types)
		if ( ! empty( $post_types ) && ! is_wp_error( $post_types ) ) {
			foreach ( $post_types as $post_type ) {

				kulam_acf_add_local_field_relationship( $post_type );

			}
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
 * @param	$is_default (bool) Indication for default taxonomy term
 * @return	N/A
 */
function kulam_acf_add_local_field_relationship( $term, $is_default = false ) {

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
				'class'	=> ( $is_default ? 'default_top_posts_relationship ' : '' ) . 'kulam_top_posts_relationship_' . $term->term_id,
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
			'return_format'		=> 'id',
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

/**
 * kulam_acf_top_posts_migration_step1
 *
 * This function migrates all top posts to the new structure implementation
 * Step 1 - copy top posts to new structure
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_acf_top_posts_migration_step1() {

	/**
	 * Variables
	 */
	$post_types = array(
		'customs'		=> 'customs_-_top_posts',
		'how-to'		=> 'learn_how_-_top_posts',
		'music'			=> 'music_-_top_posts',
		'thought'		=> 'ideas_-_top_posts',
		'misc'			=> 'misc_-_top_posts',
	);

	$categories = get_terms( array(
		'taxonomy'		=> 'category',
		'hide_empty'	=> false,
	));

	if ( $categories ) {
		foreach ( $categories as $cat ) {

			$active_post_types = array();

			foreach ( $post_types as $pt_slug => $pt ) {

				$top_posts = get_field( $pt, 'category_' . $cat->term_id );

				if ( $top_posts ) {
					// save active post type
					$post_type = get_term_by( 'slug', $pt_slug, 'post_types_tax' );
					$active_post_types[] = $post_type->term_id;

					// update new post type top posts structure
					update_field( 'kulam_top_posts_relationship_' . $pt_slug, $top_posts, 'category_' . $cat->term_id );
				}
			}

			// update active post types
			update_field( 'acf-category_post_types', $active_post_types, 'category_' . $cat->term_id );

		}
	}

}
//add_action( 'init', 'kulam_acf_top_posts_migration_step1', 100 );

/**
 * kulam_acf_top_posts_migration_step2
 *
 * This function migrates all top posts to the new structure implementation
 * Step 2 - delete top posts from old structure
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_acf_top_posts_migration_step2() {

	/**
	 * Variables
	 */
	$post_types = array(
		'customs'		=> 'customs_-_top_posts',
		'how-to'		=> 'learn_how_-_top_posts',
		'music'			=> 'music_-_top_posts',
		'thought'		=> 'ideas_-_top_posts',
		'misc'			=> 'misc_-_top_posts',
	);

	$categories = get_terms( array(
		'taxonomy'		=> 'category',
		'hide_empty'	=> false,
	));

	if ( $categories ) {
		foreach ( $categories as $cat ) {
			foreach ( $post_types as $pt_slug => $pt ) {
				update_field( $pt, array(), 'category_' . $cat->term_id );
			}
		}
	}

}
//add_action( 'init', 'kulam_acf_top_posts_migration_step2', 100 );