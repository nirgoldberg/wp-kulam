<?php
/**
 * Shortcodes
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions
 * @version     1.7.1
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
	$categories_in_row			= get_field( 'acf-option_homepage_categories_in_row', 'option' );
	$my_siddur_custom_label		= get_field( 'acf-option_my_siddur_custom_label', 'option' );
	$my_siddur_label			= $my_siddur_custom_label ? $my_siddur_custom_label : __( 'My Siddur', 'kulam-scoop' );
	$my_siddur_tile_bg			= get_field( 'acf-option_my_siddur_tile_background_image', 'option' );
	$my_siddur_tile_bg_markup	= $my_siddur_tile_bg ? 'style="background-image: url(\'' . $my_siddur_tile_bg[ 'url' ] . '\');"' : '';
	$upload_tile_bg				= get_field( 'acf-option_upload_tile_background_image', 'option' );
	$upload_tile_bg_markup		= $upload_tile_bg ? 'style="background-image: url(\'' . $upload_tile_bg[ 'url' ] . '\');"' : '';

	$a = shortcode_atts( array(
		'view-all_label'	=> __( 'Posts', 'kulam-scoop' ),
		'upload_label'		=> __( 'Upload', 'kulam-scoop' ),
		'upload_sub'		=> __( 'Upload something of your own', 'kulam-scoop' ),
		'my_siddur_label'	=> $my_siddur_label,
		'my_siddur_sub'		=> __( 'Save your favorite contents', 'kulam-scoop' ),
	), $atts );

	$theme_locations		= get_nav_menu_locations();
	$menu_obj				= get_term( $theme_locations[ 'homepage_tiles' ], 'nav_menu' );
	$homepage_tiles			= wp_get_nav_menu_items( $menu_obj->term_id );

	$markup = '<div id="homepage-tiles" class="container"' . ( $categories_in_row ? ' data-cat-in-row="' . $categories_in_row . '"' : '' ) . '><div class="row">';

	if ( $homepage_tiles ) {

		foreach ( $homepage_tiles as $homepage_tile ) {

			if ( $homepage_tile->object != 'category' )
				continue;

			$category_link				= get_category_link( $homepage_tile->object_id );
			$category_title				= $homepage_tile->title;
			$category_description		= category_description( $homepage_tile->object_id );
			$homepage_tile_bg			= get_field( 'acf-category_background_image', 'category_' . $homepage_tile->object_id );
			$homepage_tile_bg_markup	= $homepage_tile_bg ? 'style="background-image: url(\'' . $homepage_tile_bg[ 'url' ] . '\');"' : '';
			$cat_desc_content			= '';
			$cat_desc_btn				= '';

			$classes = 'tile-box-wrapper col-sm-3 col-xs-6' . ( $category_description ? ' cat-desc' : '' ) . ( $homepage_tile_bg ? ' tile-bg' : '' );

			if ( $category_description ) {

				$cat_desc_content	= '<div class="desc hidden-xs"><div class="desc-content">' . $category_description . '</div></div>';
				$cat_desc_btn		=
					'<div class="more hidden-xs">' .

						kulam_get_svg( 'info' ) .

					'</div>';

			}

			$markup .=
				'<div class="' . $classes . '">
					<a href="' . $category_link .'" role="link" class="tile-box-link" ' . $homepage_tile_bg_markup . '>
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

	}

	// custom tile for "Upload"
	$markup .=
		'<div class="tile-box-wrapper tile-box-upload col-sm-3 col-xs-6">' .
			( ( is_user_logged_in() ) ?
			'<a href="' . home_url( '/' ) . 'share"  role="link" class="tile-box-link" ' . $upload_tile_bg_markup . '>' :
			'<a href="#" role="link" class="tile-box-link" data-toggle="modal" data-target="#modal-login" data-redirect="/share" data-show-pre-text="false"' . $upload_tile_bg_markup . '>' ) .
				'<div class="tile-box">' .
					'<div class="tile-box-content">' .
						'<i class="fa fa-cloud-upload"></i>' .
						'<h2>' . $a[ 'upload_label' ] . '</h2>' .
						'<p>' . $a[ 'upload_sub' ] . '</p>' .
					'</div>' .
				'</div>' .
			'</a>' .
		'</div>';

	// custom tile for "My Siddur"
	$markup .=
		'<div class="tile-box-wrapper tile-box-album col-sm-3 col-xs-6">' .
			( ( is_user_logged_in() ) ?
			'<a href="/my-siddur" role="link" class="tile-box-link"' . $my_siddur_tile_bg_markup . '>' :
			'<a href="#" role="link" class="tile-box-link" data-toggle="modal" data-target="#modal-login" data-redirect="/my-siddur" data-show-pre-text="true"' . $my_siddur_tile_bg_markup . '>' ) .
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

/**
 * kulam_qna
 *
 * This function adds the "kulam_qna" Shortcode
 *
 * @param	$atts (array)
 * @return	(string)
 */
function kulam_qna( $atts ) {

	extract( shortcode_atts( array(
		'id'		=> '',
	), $atts ) );

	if ( ! $id )
		return;

	// return
	return kulam_qna_html( $id );

}
add_shortcode( 'kulam_qna', 'kulam_qna' );

/**
 * kulam_qna_html
 *
 * This function returns a Questions & Answers module HTML markup
 *
 * @param	$id (int) qna ID
 * @return	(string)
 */
function kulam_qna_html( $id ) {

	if ( ! function_exists( 'get_field' ) )
		return '';

	/**
	 * variables
	 */
	$qna_blocks	= get_field( 'acf-qna_blocks' );
	$output		= '';

	if ( ! $qna_blocks || ! is_array( $qna_blocks ) )
		return $output;

	foreach ( $qna_blocks as $qna ) {

		if ( ! isset( $qna[ 'acf-qna_block_id' ] ) || $id != $qna[ 'acf-qna_block_id' ] )
			continue;

		$output = kulam_qna_block_html( $qna );

	}

	// return
	return $output;

}

/**
 * kulam_qna_block_html
 *
 * This function returns a Questions & Answers module HTML markup
 *
 * @param	$qna (array) questions and answers block
 * @return	(string)
 */
function kulam_qna_block_html( $qna ) {

	/**
	 * Variables
	 */
	$output = '';
	$li_style = '';

	if ( function_exists( 'get_field' ) ) {
		$font_family	= get_field( 'acf-option_qna_font_family', 'option' );
		$font_size		= get_field( 'acf-option_qna_font_size', 'option' );
		$color			= get_field( 'acf-option_qna_color', 'option' );
	}

	$li_style .= $font_family ? 'font-family: ' . $font_family . ';' : '';
	$li_style .= $font_size ? 'font-size: ' . $font_size . 'px; line-height: ' . $font_size . 'px;' : '';
	$li_style .= $color ? 'color: ' . $color . ';' : '';

	if ( ! is_array( $qna ) || ! isset( $qna[ 'acf-qna_block_id' ] ) || ! isset( $qna[ 'acf-qna_block_questions' ] ) || ! is_array( $qna[ 'acf-qna_block_questions' ] ) )
		return $output;

	$id			= urlencode( $qna[ 'acf-qna_block_id' ] );
	$questions	= $qna[ 'acf-qna_block_questions' ];

	$output .= '<!-- QnA #' . $id . ' --><ul id="kulam-qna-' . $id . '" class="kulam-qna">';

	foreach ( $questions as $qna_pair ) {

		/**
		 * Variables
		 */
		$q = $qna_pair[ 'question' ];
		$a = $qna_pair[ 'answer' ];

		if ( ! $q || ! $a )
			continue;

		$output .= '<li>';
		$output .= '<h3 class="qna-title" ' . ( $li_style ? 'style="' . $li_style . '"' : '') . '>' . $q . '</h3>';
		$output .= '<div class="qna-content">' . $a . '</div>';
		$output .= '</li>';

	}

	$output .= '</ul><!-- End of QnA #' . $id . ' -->';

	// return
	return $output;

}