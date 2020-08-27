<?php
/**
 * Custom post types and custom taxonomies functions
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions
 * @version		2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_register_custom_post_types
 *
 * This function registers custom post types
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_register_custom_post_types() {

	kulam_register_gallery();

}
add_action( 'init', 'kulam_register_custom_post_types' );

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

}
add_action( 'init', 'kulam_register_custom_taxonomies' );

/**
 * kulam_filter_pojo_post_type_gallery
 *
 * This function filteers Pojo gallery custom post type
 *
 * @param	$args (array)
 * @return	(array)
 */
function kulam_filter_pojo_post_type_gallery( $args ) {

	$args[ 'public' ]				= false;
	$args[ 'exclude_from_search' ]	= true;
	$args[ 'publicly_queryable' ]	= false;
	$args[ 'show_ui' ]				= false;
	$args[ 'show_in_menu' ]			= false;
	$args[ 'show_in_nav_menus' ]	= false;
	$args[ 'show_in_admin_bar' ]	= false;
	$args[ 'show_in_rest' ]			= false;
	$args[ 'can_export' ]			= false;

	// return
	return $args;

}
add_filter( 'pojo_register_post_type_gallery', 'kulam_filter_pojo_post_type_gallery' );

/**
 * kulam_register_gallery
 *
 * This function registers the gallery custom post type
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_register_gallery() {

	$labels = array(
		'name'						=> __( 'Galleries',						'kulam-scoop' ),
		'singular_name'				=> __( 'Gallery',						'kulam-scoop' ),
		'add_new'					=> __( 'Add New',						'kulam-scoop' ),
		'add_new_item'				=> __( 'Add New Gallery',				'kulam-scoop' ),
		'edit_item'					=> __( 'Edit Gallery',					'kulam-scoop' ),
		'new_item'					=> __( 'New Gallery',					'kulam-scoop' ),
		'view_item'					=> __( 'View Gallery',					'kulam-scoop' ),
		'view_items'				=> __( 'View Galleries',				'kulam-scoop' ),
		'search_items'				=> __( 'Search Galleries',				'kulam-scoop' ),
		'not_found'					=> __( 'No galleries found',			'kulam-scoop' ),
		'not_found_in_trash'		=> __( 'No galleries found in Trash',	'kulam-scoop' ),
		'parent_item_colon'			=> __( 'Parent Gallery:',				'kulam-scoop' ),
		'all_items'					=> __( 'All Galleries',					'kulam-scoop' ),
		'archives'					=> __( 'Gallery Archives',				'kulam-scoop' ),
		'attributes'				=> __( 'Gallery Attributes',			'kulam-scoop' ),
		'insert_into_item'			=> __( 'Insert into gallery',			'kulam-scoop' ),
		'uploaded_to_this_item'		=> __( 'Uploaded to this gallery',		'kulam-scoop' ),
		'menu_name'					=> __( 'Galleries',						'kulam-scoop' ),
		'filter_items_list'			=> __( 'Filter galleries list',			'kulam-scoop' ),
		'items_list_navigation'		=> __( 'Galleries list navigation',		'kulam-scoop' ),
		'items_list'				=> __( 'Galleries list',				'kulam-scoop' ),
		'item_published'			=> __( 'Gallery published',				'kulam-scoop' ),
		'item_published_privately'	=> __( 'Gallery published privately',	'kulam-scoop' ),
		'item_reverted_to_draft'	=> __( 'Gallery reverted to draft',		'kulam-scoop' ),
		'item_scheduled'			=> __( 'Gallery scheduled',				'kulam-scoop' ),
		'item_updated'				=> __( 'Gallery updated',				'kulam-scoop' ),
	);

	$args = array(
		'labels'				=> $labels,
		'public'				=> true,
		'hierarchical'			=> false,
		'exclude_from_search'	=> true,
		'publicly_queryable'	=> true,
		'show_ui'				=> true,
		'show_in_menu'			=> true,
		'show_in_nav_menus'		=> true,
		'show_in_admin_bar'		=> true,
		'show_in_rest'			=> true,
		'menu_position'			=> 21,
		'menu_icon'				=> 'dashicons-images-alt',
		'capability_type'		=> 'post',
		'supports'				=> array('title'),
		'has_archive'			=> true,
		'rewrite'				=> array('slug' => 'gallery', 'with_front' => false),
		'query_var'				=> true,
		'can_export'			=> true,
	);

	register_post_type( 'gallery', $args );

}

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
 * kulam_gallery_subpannel_columns
 *
 * This function adds columns to the gallery custom post type subpannel
 *
 * @param	$columns (array) Subpannel columns
 * @return	(array)
 */
function kulam_gallery_subpannel_columns( $columns ) {

	$gallery_columns = array(
		'shortcode'	=> 'Shortcode'
	);

	$columns = array_merge(
		array_slice( $columns, 0, -1 ),	// before
		$gallery_columns,				// inserted
		array_slice( $columns, -1 )		// after
	);

	// return
	return $columns;

}
add_filter( 'manage_gallery_posts_columns', 'kulam_gallery_subpannel_columns' );

/**
 * kulam_gallery_subpannel_columns_values
 *
 * This function adds columns values to the gallery custom post type subpannel
 *
 * @param	$columns (array) Subpannel columns
 * @param	$post_id (int) Post ID
 * @return	N/A
 */
function kulam_gallery_subpannel_columns_values( $columns, $post_id ) {

	// Get variables
	global $post;

	if ( 'shortcode' == $columns ) {

		$shortcode = esc_attr( sprintf( '[kulam-gallery id="%d"]', $post_id ) );
		$shortcode = '<span class="shortcode"><input type="text" onfocus="this.select();" readonly="readonly" value="' . $shortcode . '" class="large-text code"></span>';

		echo $shortcode ?: '';

	}

}
add_action( 'manage_gallery_posts_custom_column', 'kulam_gallery_subpannel_columns_values', 10, 2 );