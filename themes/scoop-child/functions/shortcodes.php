<?php
/**
 * Shortcodes
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions
 * @version     1.1.1
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_generate_homepage_tiles
 *
 * This function adds Homepage tiles as well as custom tiles - Upload and My Siddur items
 *
 * @param   $atts (array)
 * @return  (string)
 */
function kulam_generate_homepage_tiles( $atts ) {

	if ( ! function_exists( 'get_field' ) )
		return '';

	/**
	 * Variables
	 */
	$my_siddur_label		= __( 'My Siddur', 'kulam-scoop' );
	$my_siddur_custom_label	= get_field( 'album_label', 'options' );

	if ( $my_siddur_custom_label ) {

		$my_siddur_label = $my_siddur_custom_label;

	}

	$a = shortcode_atts( array(
		'view-all_label'	=> __( 'Posts', 'kulam-scoop' ),
		'upload_label'		=> __( 'Upload', 'kulam-scoop' ),
		'upload_sub'		=> __( 'Upload somthing of your own', 'kulam-scoop' ),
		'my_siddur_label'	=> $my_siddur_label,
		'my_siddur_sub'		=> __( 'Save your favorite contents', 'kulam-scoop' ),
	), $atts );

	$theme_locations = get_nav_menu_locations();
	$menu_obj = get_term( $theme_locations[ 'homepage_tiles' ], 'nav_menu' );
	$homepage_tiles = wp_get_nav_menu_items( $menu_obj->term_id );

	$markup = '<div id="homepage-tiles"><div class="row">';

	foreach ( $homepage_tiles as $homepage_tile ) {

		$category = get_category( $homepage_tile->object_id );
		$category_children = get_term_children( $homepage_tile->object_id, 'category' );
		$category_count = intval( $category->category_count );

		foreach ( $category_children as $child ) {
			$category_count += intval( get_category($child)->category_count );
		}

		$category_link = get_category_link( $homepage_tile->object_id );
		$category_title = $homepage_tile->title;
		$category_description = category_description( $homepage_tile->object_id );

		$classes = 'tile-box-wrapper col-sm-3 col-xs-6' . ( $category_description ? ' cat-desc' : '' );

		$cat_desc_content	= '';
		$cat_desc_btn		= '';

		if ( $category_description ) {

			$cat_desc_content	= '<div class="desc hidden-xs"><div class="desc-content">' . $category_description . '</div></div>';
			$cat_desc_btn		=
				'<div class="more hidden-xs">' .

					kulam_get_svg( 'info' ) .

				'</div>';

		}

		$markup .=
			'<div class="' . $classes . '">
				<a href="' . $category_link .'"  role="link" class="tile-box-link">
					<div class="tile-box">
						<div class="tile-box-content">
							<h2>' . $category_title . '</h2>' .
							$cat_desc_content .
						'</div>' .

						$cat_desc_btn .
					'</div>
				</a>
			</div>';
	}

	// add custom tile for "Upload"
	$markup .= '<div class="tile-box-wrapper tile-box-upload col-sm-3 col-xs-6">' .
		'<a href="' . home_url( '/' ) . 'share"  role="link" class="tile-box-link">' .
		'<div class="tile-box">' .
			'<div class="tile-box-content">' .
				'<i class="fa fa-cloud-upload"></i>' .
				'<h2>' . $a[ 'upload_label' ] . '</h2>' .
				'<p>' . $a[ 'upload_sub' ] . '</p>' .
			'</div>' .
		'</div>' .
		'</a>' .
		'</div>';

	// add custom tile for "My Album"
	$markup .= '<div class="tile-box-wrapper tile-box-album col-sm-3 col-xs-6">' .
		( ( is_user_logged_in() ) ?
			'<a href="/my-siddur" role="link" class="tile-box-link">' :
			'<a href="#" role="link" class="tile-box-link" data-toggle="modal" data-target="#modal-login" data-redirect="/my-siddur" data-show-pre-text="true">' ) .
				'<div class="tile-box albumsiddur">' .
					'<div class="tile-box-content">' .
						'<i class="fa fa-book"></i>' .
						'<h2>' . $a[ 'my_siddur_label' ] . '</h2>' .
						'<p>' . $a[ 'my_siddur_sub' ] . '</p>' .
					'</div>' .
				'</div>' .
			'</a>' .
		'</div>';

	$markup .= '</div></div>';

	// return
	return $markup;

}
add_shortcode( 'kulam_hp', 'kulam_generate_homepage_tiles' );