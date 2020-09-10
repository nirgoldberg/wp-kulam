<?php
/**
 * ACF configuration
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions/acf
 * @version		2.0.4
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
			'page_title' 	=> __( 'Header/Footer Settings', 'kulam-scoop' ),
			'menu_title' 	=> __( 'Header/Footer', 'kulam-scoop' ),
			'menu_slug' 	=> 'acf-options-header-footer',
			'parent_slug' 	=> 'site-options',
		));

		acf_add_options_sub_page( array(
			'page_title' 	=> __( 'Custom Taxonomies Settings', 'kulam-scoop' ),
			'menu_title' 	=> __( 'Custom Taxonomies', 'kulam-scoop' ),
			'menu_slug' 	=> 'acf-options-custom-taxonomies',
			'parent_slug' 	=> 'site-options',
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
 * kulam_acf_set_default_language
 *
 * This function sets ACF default language.
 * Used to fetch an options field value from the default language
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_acf_set_default_language() {

	// return
	return acf_get_setting( 'default_language' );

}

/**
 * kulam_acf_get_global_option
 *
 * This function fetches an options field value from the default language
 *
 * @param	$name (string)
 * @return	(mix)
 */
function kulam_acf_get_global_option( $name ) {

	add_filter( 'acf/settings/current_language', 'kulam_acf_set_default_language', 100 );

	$option = get_field( $name, 'option' );

	remove_filter( 'acf/settings/current_language', 'kulam_acf_set_default_language', 100 );

	// return
	return $option;

}

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
		'taxonomy'	=> 'post_types_tax',
		'exclude'	=> $default_post_types_ids,
		'orderby'	=> 'term_order',
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

		$is_default = empty( $default_post_types ) ? true : false;

		// register ACF Relationship fields (rest of post types)
		if ( ! empty( $post_types ) && ! is_wp_error( $post_types ) ) {
			foreach ( $post_types as $post_type ) {

				kulam_acf_add_local_field_relationship( $post_type, $is_default );

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
 * kulam_acf_prepare_acf_form_post_title_field
 *
 * This function filters acf form title
 *
 * @param	$field (array)
 * @return	(array)
 */
function kulam_acf_prepare_acf_form_post_title_field( $field ) {

	/**
	 * Variables
	 */
	$field_label = get_field( 'acf-form_form_title' );

	if ( $field_label ) {

		$field[ 'label' ] = $field_label;

	}

	// return
	return $field;

}
add_filter( 'acf/prepare_field/name=_post_title', 'kulam_acf_prepare_acf_form_post_title_field' );

/**
 * kulam_acf_prepare_acf_form_post_content_field
 *
 * This function filters acf form content title
 *
 * @param	$field (array)
 * @return	(array)
 */
function kulam_acf_prepare_acf_form_post_content_field( $field ) {

	/**
	 * Variables
	 */
	$field_label = get_field( 'acf-form_form_content_title' );

	if ( $field_label ) {

		$field[ 'label' ] = $field_label;

	}

	// return
	return $field;

}
add_filter( 'acf/prepare_field/name=_post_content', 'kulam_acf_prepare_acf_form_post_content_field' );

/**
 * kulam_acf_prepare_acf_form_post_category_field
 *
 * This function filters acf form category title
 *
 * @param	$field (array)
 * @return	(array)
 */
function kulam_acf_prepare_acf_form_post_category_field( $field ) {

	/**
	 * Variables
	 */
	$field_label = get_field( 'acf-form_form_category_title' );

	if ( $field_label ) {

		$field[ 'label' ] = $field_label;

	}

	// return
	return $field;

}
add_filter( 'acf/prepare_field/name=acf-form_category', 'kulam_acf_prepare_acf_form_post_category_field' );

/**
 * kulam_acf_add_readonly_and_disabled_to_text_field
 *
 * This function adds readonly and disabled attributes to text field
 *
 * @param	$field (array)
 * @return	(array)
 */
function kulam_acf_add_readonly_and_disabled_to_text_field( $field ) {

	acf_render_field_setting( $field, array(
		'label'         => __( 'Read Only?', 'acf' ),
		'instructions'  => '',
		'type'          => 'radio',
		'name'          => 'readonly',
		'choices'       => array(
			0           => __( 'No', 'acf' ),
			1           => __( 'Yes', 'acf' ),
		),
		'layout'        => 'horizontal',
	));

	acf_render_field_setting( $field, array(
		'label'         => __( 'Disabled?', 'acf' ),
		'instructions'  => '',
		'type'          => 'radio',
		'name'          => 'disabled',
		'choices'       => array(
			0           => __( 'No', 'acf' ),
			1           => __( 'Yes', 'acf' ),
		),
		'layout'        => 'horizontal',
	));

}
add_action( 'acf/render_field_settings/type=text', 'kulam_acf_add_readonly_and_disabled_to_text_field' );

/**
 * kulam_acf_qna_generate_shortcodes
 *
 * This function generates Question & Answers module shortcodes
 *
 * @param	$post_id (int) Post ID
 * @return	N/A
 */
function kulam_acf_qna_generate_shortcodes( $post_id ) {

	/**
	 * Variables
	 */
	$qna_blocks			= 'field_5e89d71b14535';
	$block_id			= 'field_5e89d2e914534';
	$block_shortcode	= 'field_5e89dcc4363e7';

	if ( ! isset( $_POST[ 'acf' ][ $qna_blocks ] ) || ! is_array( $_POST[ 'acf' ][ $qna_blocks ] ) )
		return;

	foreach ( $_POST[ 'acf' ][ $qna_blocks ] as $key => $qna ) {

		// vars
		$id = $qna[ $block_id ];

		// set shortcode
		$_POST[ 'acf' ][ $qna_blocks ][ $key ][ $block_shortcode ] = '[kulam_qna id="' . sanitize_title_with_dashes( $id ) . '"]';

	}

}
add_action( 'acf/save_post', 'kulam_acf_qna_generate_shortcodes', 5 );

/**
 * kulam_embed_google_fonts
 *
 * This function embeds google fonts chosen by ACF Font Family type fields associated with current post and options
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_embed_google_fonts() {

	/**
	 * Variables
	 */
	$fonts = array();
	$post_fields = (array) get_field_objects();
	$options_fields = (array) get_field_objects( 'option' );
	$fields = array_merge( $post_fields, $options_fields );

	if ( $fields ) {
		foreach ( $fields as $key => $field ) {
			if ( 'font_family' == $field[ 'type' ] ) {

				$fonts[] = array(
					'family'	=> $field[ 'value' ],
					'type'		=> htmline_acf_web_fonts::get_font_type( $field[ 'value' ] ),
				);

			}
		}
	}

	$fonts = apply_filters( 'kulam_embed_google_fonts', $fonts );

	if ( $fonts ) {

		$google_fonts = array();
		$fonts_url = '';
		$google_early_access_fonts = array();

		foreach ( $fonts as $font ) {

			if ( 'googlefonts' == $font[ 'type' ] ) {

				$google_fonts[] = $font[ 'family' ] . ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';

			}

			elseif ( 'earlyaccess' == $font[ 'type' ] ) {

				$google_early_access_fonts[] = strtolower( str_replace( ' ', '', $font[ 'family' ] ) );

			}

		}

		if ( $google_fonts ) {

			$fonts_url = sprintf( 'https://fonts.googleapis.com/css?family=%s', implode( '|', $google_fonts ) );

			if ( 'he-IL' === get_bloginfo( 'language' ) ) {
				$fonts_url .= '&subset=hebrew';
			}

			printf( '<link rel="stylesheet" type="text/css" href="' . $fonts_url . '">' );

		}

		if ( $google_early_access_fonts ) {
			foreach ( $google_early_access_fonts as $font ) {
				printf( '<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/earlyaccess/%s.css">', $font );
			}
		}

	}

}
add_action( 'wp_footer', 'kulam_embed_google_fonts' );

/**
 * kulam_acf_init_google_maps_api
 *
 * This function initiates Google API key for use by Google Maps custom field
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_acf_init_google_maps_api() {

	$google_maps_api = get_field( 'acf-option_google_maps_api', 'option' );

	if ( $google_maps_api ) {
		acf_update_setting( 'google_api_key', $google_maps_api );
	}

}
add_action( 'acf/init', 'kulam_acf_init_google_maps_api' );

/**
 * kulam_acf_slideshow_generate_shortcode
 *
 * This function generates slideshow shortcode
 *
 * @param	$post_id (int) Post ID
 * @return	N/A
 */
function kulam_acf_slideshow_generate_shortcode( $post_id ) {

	if ( 'pojo_slideshow' != get_post_type() )
		return;

	/**
	 * Variables
	 */
	$shortcode	= 'field_5e982fa13ae7d';

	// set shortcode
	$_POST[ 'acf' ][ $shortcode ] = '[kulam_slideshow id="' . $post_id . '"]';

}
add_action( 'acf/save_post', 'kulam_acf_slideshow_generate_shortcode', 5 );

/**
 * kulam_acf_pc_generate_shortcodes
 *
 * This function generates Posts Carousels module shortcodes
 *
 * @param	$post_id (int) Post ID
 * @return	N/A
 */
function kulam_acf_pc_generate_shortcodes( $post_id ) {

	/**
	 * Variables
	 */
	$pcs			= 'field_5ea9a4a182caa';
	$pc_id			= 'field_5ea9a52782cab';
	$pc_shortcode	= 'field_5ea9a59882cac';

	if ( ! isset( $_POST[ 'acf' ][ $pcs ] ) || ! is_array( $_POST[ 'acf' ][ $pcs ] ) )
		return;

	foreach ( $_POST[ 'acf' ][ $pcs ] as $key => $pc ) {

		// vars
		$id = $pc[ $pc_id ];

		// set shortcode
		$_POST[ 'acf' ][ $pcs ][ $key ][ $pc_shortcode ] = '[kulam_pc id="' . sanitize_title_with_dashes( $id ) . '"]';

	}

}
add_action( 'acf/save_post', 'kulam_acf_pc_generate_shortcodes', 5 );

/**
 * kulam_acf_register_custom_taxonomies
 *
 * This function registers custom taxonomies based on acf-option_custom_taxonomies_generator ACF repeater
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_acf_register_custom_taxonomies() {

	/**
	 * Variables
	 */
	$custom_tax	= kulam_acf_get_global_option( 'acf-option_custom_taxonomies_generator' );

	if ( ! $custom_tax )
		return;

	foreach ( $custom_tax as $tax ) {

		/**
		 * Variables
		 */
		$name		= $tax[ 'name' ];
		$singular	= $tax[ 'singular_name' ];

		if ( ! $name || ! $singular )
			continue;

		$labels = kulam_get_custom_taxonomy_labels( $name, $singular );

		if ( ! $labels )
			continue;

		$args = array(
			'labels'				=> $labels,
			'public'				=> true,
			'hierarchical'			=> true,
			'show_in_rest'			=> true,
			'show_admin_column'		=> true,
		);

		register_taxonomy( urldecode( sanitize_title( urldecode( 'post_tax_' . $singular ) ) ), 'post', $args );

	}

}
add_action( 'init', 'kulam_acf_register_custom_taxonomies' );

/**
 * kulam_get_custom_taxonomy_labels
 *
 * This function generates custom taxonomy labels
 *
 * @param	$name (string)
 * @param	$singular (string)
 * @return	(array)
 */
function kulam_get_custom_taxonomy_labels( $name, $singular ) {

	$labels = array();

	if ( $name && $singular ) {

		$labels = array(
			'name'							=> $name,
			'singular_name'					=> $singular,
			'menu_name'						=> $name,
			'search_items'					=> sprintf( __( 'Search %s',						'kulam-scoop' ), $name ),
			'popular_items'					=> sprintf( __( 'Popular %s',						'kulam-scoop' ), $name ),
			'all_items'						=> sprintf( __( 'All %s',							'kulam-scoop' ), $name ),
			'parent_item'					=> sprintf( __( 'Parent %s',						'kulam-scoop' ), $singular ),
			'parent_item_colon'				=> sprintf( __( 'Parent %s:',						'kulam-scoop' ), $singular ),
			'edit_item'						=> sprintf( __( 'Edit %s',							'kulam-scoop' ), $singular ),
			'view_item'						=> sprintf( __( 'View %s',							'kulam-scoop' ), $singular ),
			'update_item'					=> sprintf( __( 'Update %s',						'kulam-scoop' ), $singular ),
			'add_new_item'					=> sprintf( __( 'Add New %s',						'kulam-scoop' ), $singular ),
			'new_item_name'					=> sprintf( __( 'New %s Name',						'kulam-scoop' ), $singular ),
			'separate_items_with_commas'	=> sprintf( __( 'Separate %s with commas',			'kulam-scoop' ), $name ),
			'add_or_remove_items'			=> sprintf( __( 'Add or remove %s',					'kulam-scoop' ), $name ),
			'choose_from_most_used'			=> sprintf( __( 'Choose from the most used %s',		'kulam-scoop' ), $name ),
			'not_found'						=> sprintf( __( 'No %s Found',						'kulam-scoop' ), $name ),
			'no_terms'						=> sprintf( __( 'No %s',							'kulam-scoop' ), $name ),
			'items_list_navigation'			=> sprintf( __( '%s list navigation',				'kulam-scoop' ), $name ),
			'items_list'					=> sprintf( __( '%s list',							'kulam-scoop' ), $name ),
			'back_to_items'					=> sprintf( __( '&larr; Back to %s',				'kulam-scoop' ), $name ),
		);

	}

	// return
	return $labels;

}

/**
 * kulam_acf_unregister_custom_taxonomies
 *
 * This function provides the following for each deleted custom taxonomy:
 * 1. Delete custom taxonomy terms
 * 2. Unregister custom taxonomy
 *
 * @param	$post_id (int) Post ID
 * @return	N/A
 */
function kulam_acf_unregister_custom_taxonomies( $post_id ) {

	/**
	 * Variables
	 */
	$custom_tax_key		= 'field_5f144b60b40dd';
	$singular_name_key	= 'field_5f144dffb40e0';

	if ( ! isset( $_POST[ 'acf' ][ $custom_tax_key ] ) )
		return;

	// get pre-registered custom taxonomies
	$old_custom_tax = kulam_acf_get_global_option( 'acf-option_custom_taxonomies_generator' );

	if ( ! $old_custom_tax )
		return;

	foreach ( $old_custom_tax as $old_tax ) {

		/**
		 * Variables
		 */
		$old_singular_name	= $old_tax[ 'singular_name' ];
		$old_tax_found		= false;

		if ( $_POST[ 'acf' ][ $custom_tax_key ] ) {
			foreach ( $_POST[ 'acf' ][ $custom_tax_key ] as $row => $tax ) {
				if ( $old_singular_name == $tax[ $singular_name_key ] ) {
					$old_tax_found = true;
					break;
				}
			}
		}

		if ( ! $old_tax_found ) {

			$taxonomy = urldecode( sanitize_title( urldecode( 'post_tax_' . $old_singular_name ) ) );

			// pre-registered tax has not found
			// Delete custom taxonomy terms
			kulam_acf_delete_custom_taxonomy_terms( $taxonomy );

			// Unregister custom taxonomy
			kulam_acf_unregister_custom_taxonomy( $taxonomy );

		}

	}

}
add_action( 'acf/save_post', 'kulam_acf_unregister_custom_taxonomies', 5 );

/**
 * kulam_acf_delete_custom_taxonomy_terms
 *
 * This function deletes custom taxonomy terms
 *
 * @param	$taxonomy (string)
 * @return	N/A
 */
function kulam_acf_delete_custom_taxonomy_terms( $taxonomy ) {

	// get taxonomy terms
	$terms = get_terms(array(
		'taxonomy'		=> $taxonomy,
		'hide_empty'	=> false,
	));

	if ( ! $terms ) {
		return;
	}

	foreach ( $terms as $term ) {

		wp_delete_term( $term->term_id, $taxonomy );

	}

}

/**
 * kulam_acf_unregister_custom_taxonomy
 *
 * This function unregisters custom taxonomy
 *
 * @param	$taxonomy (string)
 * @return	N/A
 */
function kulam_acf_unregister_custom_taxonomy( $taxonomy ) {

	unregister_taxonomy_for_object_type( $taxonomy, 'post' );

}

/**
 * kulam_acf_category_posts_relationship_query
 *
 * This function filters the Relationship field query of category posts in order to include current category
 *
 * @param	$args (array)
 * @param	$field (array)
 * @param	$post_id (int)
 * @return	array
 */
function kulam_acf_category_posts_relationship_query( $args, $field, $post_id ) {

	if ( 'acf-category_sticky_posts' == $field['name'] ) {

		$args[ 'cat' ] = $post_id;

	}

	// return
	return $args;

}
add_filter( 'acf/fields/relationship/query', 'kulam_acf_category_posts_relationship_query', 10, 3 );