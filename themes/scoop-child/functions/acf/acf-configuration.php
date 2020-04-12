<?php
/**
 * ACF configuration
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions/acf
 * @version		1.7.4
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
		$_POST[ 'acf' ][ $qna_blocks ][ $key ][ $block_shortcode ] = '[kulam_qna id="' . sanitize_text_field( $id ) . '"]';

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
add_action( 'wp_head', 'kulam_embed_google_fonts' );

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
add_action('acf/init', 'kulam_acf_init_google_maps_api');