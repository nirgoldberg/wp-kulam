<?php
/**
 * Custom post types and custom taxonomies functions
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions
 * @version		1.4.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_register_custom_taxonomies
 *
 * This function registers custom taxonomies
 *
 * @param	N/A   
 * @return	N/A
 */
function kulam_register_custom_taxonomies() {

	kulam_register_post_types_tax();
	kulam_register_siddurim();
	kulam_register_activity_types();

}
add_action( 'init', 'kulam_register_custom_taxonomies' );

/**
 * kulam_register_post_types_tax
 *
 * This function registers the post_types_tax custom taxonomy
 *
 * @param	N/A   
 * @return	N/A
 */
function kulam_register_post_types_tax() {

	$labels = array(

		'name'							=> __( 'Post Types',							'kulam-scoop' ),
		'singular_name'					=> __( 'Post Type',								'kulam-scoop' ),
		'menu_name'						=> __( 'Post Types',							'kulam-scoop' ),
		'all_items'						=> __( 'All Post Types',						'kulam-scoop' ),
		'edit_item'						=> __( 'Edit Post Type',						'kulam-scoop' ),
		'view_item'						=> __( 'View Post Type',						'kulam-scoop' ),
		'update_item'					=> __( 'Update Post Type',						'kulam-scoop' ),
		'add_new_item'					=> __( 'Add New Post Type',						'kulam-scoop' ),
		'new_item_name'					=> __( 'New Post Type Name',					'kulam-scoop' ),
		'parent_item'					=> __( 'Parent Post Type',						'kulam-scoop' ),
		'parent_item_colon'				=> __( 'Parent Post Type:',						'kulam-scoop' ),
		'search_items'					=> __( 'Search Post Types',						'kulam-scoop' ),
		'popular_items'					=> __( 'Popular Post Types',					'kulam-scoop' ),
		'separate_items_with_commas'	=> __( 'Separate Post Types with commas',		'kulam-scoop' ),
		'add_or_remove_items'			=> __( 'Add or remove Post Types',				'kulam-scoop' ),
		'choose_from_most_used'			=> __( 'Choose from the most used Post Types',	'kulam-scoop' ),
		'not_found'						=> __( 'No Post Types found',					'kulam-scoop' ),
		'back_to_items'					=> __( '← Back to Post Types',					'kulam-scoop' ),

	);

	$args = array(

		'label'					=> __( 'Post Types', 'kulam-scoop' ),
		'labels'				=> $labels,
		'public'				=> true,
		'publicly_queryable'	=> true,
		'show_ui'				=> true,
		'show_in_menu'			=> true,
		'show_in_nav_menus'		=> true,
		'show_in_rest'			=> false,
		'rest_base'				=> 'post_types_tax',
		'show_tagcloud'			=> true,
		'show_in_quick_edit'	=> true,
		'show_admin_column'		=> true,
		'description'			=> '',
		'hierarchical'			=> true,
		'query_var'				=> 'pt',
		'rewrite'				=> array( 'slug' => 'pt', 'with_front' => false ),
		'capabilities'			=> array(
			'manage_terms'	=> 'manage_options',
			'edit_terms'	=> 'manage_options',
			'delete_terms'	=> 'manage_options',
		),

	);

	register_taxonomy( 'post_types_tax', array( 'post' ) , $args );

}

/**
 * kulam_register_siddurim
 *
 * This function registers the siddurim custom taxonomy
 *
 * @param	N/A   
 * @return	N/A
 */
function kulam_register_siddurim() {

	$labels = array(

		'name'			=> __( 'Siddurim',	'kulam-scoop' ),
		'singular_name'	=> __( 'My Siddur',	'kulam-scoop' ),

	);

	$args = array(

		'label'					=> __('Siddurim', 'kulam-scoop'),
		'labels'				=> $labels,
		'public'				=> true,
		'show_ui'				=> false,
		'show_in_menu'			=> false,
		'show_in_nav_menus'		=> false,
		'show_in_rest'			=> false,
		'rest_base'				=> 'siddurim',
		'show_in_quick_edit'	=> false,
		'show_admin_column'		=> false,
		'hierarchical'			=> true,
		'query_var'				=> "siddur",
		'rewrite'				=> array( 'slug' => 'siddur', 'with_front' => false ),
		'capabilities'			=> array(
			'manage_terms'	=> 'manage_siddurim',
			'edit_terms'	=> 'manage_siddurim',
			'delete_terms'	=> 'manage_siddurim',
		),

	);

	register_taxonomy( 'siddurim', array( 'post' ), $args );

}

/**
 * kulam_register_activity_types
 *
 * This function registers the post_types_tax custom taxonomy
 *
 * @param	N/A   
 * @return	N/A
 */
function kulam_register_activity_types() {

	$enable_activity_types	= get_field( 'acf-option_enable_activity_types_custom_taxonomy', 'option' );
	$is_visible				= $enable_activity_types && true === $enable_activity_types;

	$labels = array(

		'name'							=> __( 'Activity Types',							'kulam-scoop' ),
		'singular_name'					=> __( 'Activity Type',								'kulam-scoop' ),
		'menu_name'						=> __( 'Activity Types',							'kulam-scoop' ),
		'all_items'						=> __( 'All Activity Types',						'kulam-scoop' ),
		'edit_item'						=> __( 'Edit Activity Type',						'kulam-scoop' ),
		'view_item'						=> __( 'View Activity Type',						'kulam-scoop' ),
		'update_item'					=> __( 'Update Activity Type',						'kulam-scoop' ),
		'add_new_item'					=> __( 'Add New Activity Type',						'kulam-scoop' ),
		'new_item_name'					=> __( 'New Activity Type Name',					'kulam-scoop' ),
		'parent_item'					=> __( 'Parent Activity Type',						'kulam-scoop' ),
		'parent_item_colon'				=> __( 'Parent Activity Type:',						'kulam-scoop' ),
		'search_items'					=> __( 'Search Activity Types',						'kulam-scoop' ),
		'popular_items'					=> __( 'Popular Activity Types',					'kulam-scoop' ),
		'separate_items_with_commas'	=> __( 'Separate Activity Types with commas',		'kulam-scoop' ),
		'add_or_remove_items'			=> __( 'Add or remove Activity Types',				'kulam-scoop' ),
		'choose_from_most_used'			=> __( 'Choose from the most used Activity Types',	'kulam-scoop' ),
		'not_found'						=> __( 'No Activity Types found',					'kulam-scoop' ),
		'back_to_items'					=> __( '← Back to Activity Types',					'kulam-scoop' ),

	);

	$args = array(

		'label'					=> __( 'Activity Types', 'kulam-scoop' ),
		'labels'				=> $labels,
		'public'				=> true,
		'publicly_queryable'	=> $is_visible,
		'show_ui'				=> $is_visible,
		'show_in_menu'			=> $is_visible,
		'show_in_nav_menus'		=> $is_visible,
		'show_in_rest'			=> false,
		'rest_base'				=> 'activity_types',
		'show_tagcloud'			=> $is_visible,
		'show_in_quick_edit'	=> $is_visible,
		'show_admin_column'		=> $is_visible,
		'description'			=> '',
		'hierarchical'			=> true,
		'query_var'				=> 'activity_type',
		'rewrite'				=> array( 'slug' => 'activity_type', 'with_front' => false ),

	);

	register_taxonomy( 'activity_types', array( 'post' ) , $args );

}