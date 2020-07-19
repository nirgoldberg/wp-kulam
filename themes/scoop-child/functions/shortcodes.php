<?php
/**
 * Shortcodes
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions
 * @version     1.7.27
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
	$my_siddur_activate_module	= get_field( 'acf-option_my_siddur_activate_module', 'option' );
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
			'<a href="#" role="link" class="tile-box-link" data-toggle="modal" data-target="#modal-login" data-redirect="/share" data-show-pre-text="true"' . $upload_tile_bg_markup . '>' ) .
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
	$markup .= false !== $my_siddur_activate_module ?
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
		'</div>' : '';

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

		if ( ! isset( $qna[ 'acf-qna_block_id' ] ) )
			continue;

		$qna_id = sanitize_title_with_dashes( $qna[ 'acf-qna_block_id' ] );

		if ( $id != $qna_id )
			continue;

		$output = kulam_qna_block_html( $qna, $qna_id );

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
 * @param	$id (string) questions and answers block ID
 * @return	(string)
 */
function kulam_qna_block_html( $qna, $id ) {

	/**
	 * Variables
	 */
	$output		= '';
	$li_style	= '';

	if ( function_exists( 'get_field' ) ) {
		$font_family	= get_field( 'acf-option_qna_font_family', 'option' );
		$font_size		= get_field( 'acf-option_qna_font_size', 'option' );
		$color			= get_field( 'acf-option_qna_color', 'option' );
	}

	$li_style .= $font_family ? 'font-family: \'' . $font_family . '\';' : '';
	$li_style .= $font_size ? 'font-size: ' . $font_size . 'px;line-height: ' . $font_size . 'px;' : '';
	$li_style .= $color ? 'color: ' . $color . ';' : '';

	if ( ! is_array( $qna ) || ! $id || ! isset( $qna[ 'acf-qna_block_questions' ] ) || ! is_array( $qna[ 'acf-qna_block_questions' ] ) )
		return $output;

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
		$output .= '<div class="qna-content">' . apply_filters( 'the_content', $a ) . '</div>';
		$output .= '</li>';

	}

	$output .= '</ul><!-- End of QnA #' . $id . ' -->';

	// return
	return $output;

}

/**
 * kulam_slideshow
 *
 * This function adds the "kulam_slideshow" Shortcode
 *
 * @param	$atts (array)
 * @return	(string)
 */
function kulam_slideshow( $atts ) {

	extract( shortcode_atts( array(
		'id'		=> '',
	), $atts ) );

	if ( ! $id )
		return;

	// return
	return kulam_slideshow_html( $id );

}
add_shortcode( 'kulam_slideshow', 'kulam_slideshow' );

/**
 * kulam_slideshow_html
 *
 * This function returns a Slideshow HTML markup
 *
 * @param	$id (int) post ID
 * @return	(string)
 */
function kulam_slideshow_html( $id ) {

	if ( ! function_exists( 'get_field' ) || 'pojo_slideshow' != get_post_type( $id ) )
		return '';

	/**
	 * variables
	 */
	$title				= get_field( 'acf-slideshow_title',							$id );
	$title_font_family	= get_field( 'acf-slideshow_title_font_family',				$id );
	$title_font_size	= get_field( 'acf-slideshow_title_font_size',				$id );
	$title_font_weight	= get_field( 'acf-slideshow_title_font_weight',				$id );
	$title_color		= get_field( 'acf-slideshow_title_color',					$id );
	$title_bg_color		= get_field( 'acf-slideshow_title_background_color',		$id );
	$title_bg_image		= get_field( 'acf-slideshow_title_background_image',		$id );
	$title_link			= get_field( 'acf-slideshow_title_link',					$id );
	$slide_font_family	= get_field( 'acf-slideshow_slider_title_font_family',		$id );
	$slide_font_size	= get_field( 'acf-slideshow_slider_title_font_size',		$id );
	$slide_font_weight	= get_field( 'acf-slideshow_slider_title_font_weight',		$id );
	$slide_color		= get_field( 'acf-slideshow_slider_title_color',			$id );
	$slide_bg_image		= get_field( 'acf-slideshow_slider_title_background_image',	$id );
	$scheme_color		= get_field( 'acf-slideshow_scheme_color',					$id );

	$slide_height = absint( atmb_get_field( 'slide_slide_height', $id ) );
	if ( empty( $slide_height ) || 0 === $slide_height ) {
		$slide_height = '260';
	}

	$output				= '';

	if ( $title_font_family || $slide_font_family ) {

		add_filter( 'kulam_embed_google_fonts', function( $fonts ) use ( $title_font_family, $slide_font_family ) {

			$new_fonts		= array( $title_font_family, $slide_font_family );
			$added_fonts	= array();

			foreach ( $new_fonts as $font ) {

				if ( $font ) {
					$added_fonts[] = array(
						'family'	=> $font,
						'type'		=> htmline_acf_web_fonts::get_font_type( $font ),
					);
				}

			}

			// return
			return array_merge( $fonts, $added_fonts );

		});

	}

	$style =
		'<style type="text/css">' .
			'#kulam-slideshow-' . $id . ' .pojo-slideshow{height:' . ($slide_height+35) . 'px !important;}' .
			( $scheme_color ? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide{padding:6px;height:' . $slide_height . 'px;background-color:' . $scheme_color . ';}' : '' ) .
			( $scheme_color ? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide .bx-caption{background-color:' . $scheme_color . ';}' : '' ) .
			( $slide_bg_image ? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide .bx-caption{background-image:url(\'' . $slide_bg_image . '\');}' : '' ) .
			( $slide_font_family ? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide .bx-caption > span{font-family:\'' . $slide_font_family . '\';}' : '' ) .
			( $slide_font_size ? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide .bx-caption > span{font-size:' . $slide_font_size . 'px;line-height:' . $slide_font_size . 'px;}' : '' ) .
			( $slide_font_weight ? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide .bx-caption > span{font-weight:' . $slide_font_weight . ';}' : '' ) .
			( $slide_color ? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide .bx-caption > span{color:' . $slide_color . ';}' : '' ) .
		'</style>';

	$output .= $style;

	$output .= '<!-- Slideshow #' . $id . ' --><div id="kulam-slideshow-' . $id . '" class="kulam-slideshow" data-scheme-color="' . $scheme_color . '">';

	// slideshow title
	if ( $title ) {

		$style = '';
		$style .= $title_font_family ? 'font-family:\'' . $title_font_family . '\';' : '';
		$style .= $title_font_size ? 'font-size:' . $title_font_size . 'px;line-height:' . $title_font_size . 'px;' : '';
		$style .= $title_font_weight ? 'font-weight:' . $title_font_weight . ';' : '';
		$style .= $title_color ? 'color:' . $title_color . ';' : '';
		$style .= $title_bg_color && ! $title_bg_image ? 'padding:10px;display:inline-block;background-color:' . $title_bg_color . ';' : '';
		$style .= $title_bg_image ? 'height: ' . $title_bg_image[ 'height' ] . 'px;' : '';

		$output .=
			'<div ' . ( $style ? 'style="' . $style . '"' : '' ) . ' class="kulam-slideshow-title">' .
				( $title_bg_image ? '<img src="' . $title_bg_image[ 'url' ] . '" style="margin-left: -' . intval( $title_bg_image[ 'width' ] )/2 . 'px;" alt="" />' : '' ) .
				'<div class="title' . ( $title_bg_image ? ' has-bg-image' : '' ) . '">' .
					( $title_link ? '<a href="' . $title_link . '"' . ( $title_color ? ' style="color:' . $title_color . ';"' : '' ) . '>' : '' ) .
					$title .
					( $title_link ? '</a>' : '' ) .
				'</div>' .
			'</div>';
	}

	$output .= do_shortcode('[pojo-slideshow id="' . $id . '"]');

	$output .= '</div><!-- End of Slideshow #' . $id . ' -->';

	// return
	return $output;

}

/**
 * kulam_pc
 *
 * This function adds the "kulam_pc" Shortcode (Posts Carousel)
 *
 * @param	$atts (array)
 * @return	(string)
 */
function kulam_pc( $atts ) {

	extract( shortcode_atts( array(
		'id'		=> '',
	), $atts ) );

	if ( ! $id )
		return;

	// return
	return kulam_pc_html( $id );

}
add_shortcode( 'kulam_pc', 'kulam_pc' );

/**
 * kulam_pc_html
 *
 * This function returns a Posts Carousel module HTML markup
 *
 * @param	$id (int) Posts Carousel ID
 * @return	(string)
 */
function kulam_pc_html( $id ) {

	if ( ! function_exists( 'get_field' ) )
		return '';

	/**
	 * variables
	 */
	$pc_carousels	= get_field( 'acf-pc_carousels' );
	$output			= '';

	if ( ! $pc_carousels || ! is_array( $pc_carousels ) )
		return $output;

	foreach ( $pc_carousels as $pc ) {

		if ( ! isset( $pc[ 'id' ] ) )
			continue;

		$pc_id = sanitize_title_with_dashes( $pc[ 'id' ] );

		if ( $id != $pc_id )
			continue;

		$output = kulam_pc_carousel( $pc, $pc_id );

	}

	// return
	return $output;

}

/**
 * kulam_pc_carousel
 *
 * This function returns a Posts Carousel HTML markup
 *
 * @param	$pc (int) Posts Carousel
 * @param	$id (string) Posts Carousel ID
 * @return	(string)
 */
function kulam_pc_carousel( $pc, $id ) {

	if ( ! is_array( $pc ) || ! $id )
		return '';

	/**
	 * variables
	 */
	$categories			= $pc[ 'posts_categories' ];
	$title				= $pc[ 'title' ][ 'title' ];
	$title_font_family	= $pc[ 'title' ][ 'font_family' ];
	$title_font_size	= $pc[ 'title' ][ 'font_size' ];
	$title_font_weight	= $pc[ 'title' ][ 'font_weight' ];
	$title_color		= $pc[ 'title' ][ 'color' ];
	$title_bg_color		= $pc[ 'title' ][ 'background_color' ];
	$title_bg_image		= $pc[ 'title' ][ 'background_image' ];
	$title_link			= $pc[ 'title' ][ 'link' ];
	$slide_font_family	= $pc[ 'slide_title' ][ 'font_family' ];
	$slide_font_size	= $pc[ 'slide_title' ][ 'font_size' ];
	$slide_font_weight	= $pc[ 'slide_title' ][ 'font_weight' ];
	$slide_color		= $pc[ 'slide_title' ][ 'color' ];
	$slide_bg_image		= $pc[ 'slide_title' ][ 'background_image' ];
	$scheme_color		= $pc[ 'general' ][ 'scheme_color' ];
	$slide_height		= $pc[ 'carousel_options' ][ 'slide_height' ];
	$output				= '';

	if ( ! $categories )
		return $output;

	if ( empty( $slide_height ) || 0 === $slide_height ) {
		$slide_height = '200';
	}

	if ( $title_font_family || $slide_font_family ) {

		add_filter( 'kulam_embed_google_fonts', function( $fonts ) use ( $title_font_family, $slide_font_family ) {

			$new_fonts		= array( $title_font_family, $slide_font_family );
			$added_fonts	= array();

			foreach ( $new_fonts as $font ) {

				if ( $font ) {
					$added_fonts[] = array(
						'family'	=> $font,
						'type'		=> htmline_acf_web_fonts::get_font_type( $font ),
					);
				}

			}

			// return
			return array_merge( $fonts, $added_fonts );

		});

	}

	$style =
		'<style type="text/css">' .
			'#kulam-slideshow-' . $id . ' .pojo-slideshow{height:' . ($slide_height+50) . 'px !important;}' .
			( $scheme_color ? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide{padding:6px;height:' . $slide_height . 'px;background-color:' . $scheme_color . ';}' : '' ) .
			( $scheme_color ? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide .bx-caption{background-color:' . $scheme_color . ';}' : '' ) .
			( $slide_bg_image ? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide .bx-caption{background-image:url(\'' . $slide_bg_image . '\');}' : '' ) .
			( $slide_font_family ? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide .bx-caption > span{font-family:\'' . $slide_font_family . '\';}' : '' ) .
			( $slide_font_size ? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide .bx-caption > span{font-size:' . $slide_font_size . 'px;line-height:' . $slide_font_size . 'px;}' : '' ) .
			( $slide_font_weight ? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide .bx-caption > span{font-weight:' . $slide_font_weight . ';}' : '' ) .
			( $slide_color ? '#kulam-slideshow-' . $id . ' .pojo-slideshow .slide .bx-caption > span{color:' . $slide_color . ';}' : '' ) .
		'</style>';

	$output .= $style;

	$output .= '<!-- Kulam Post Carousel #' . $id . ' --><div id="kulam-slideshow-' . $id . '" class="kulam-slideshow" data-scheme-color="' . $scheme_color . '">';

	// slideshow title
	if ( $title ) {

		$style = '';
		$style .= $title_font_family ? 'font-family:\'' . $title_font_family . '\';' : '';
		$style .= $title_font_size ? 'font-size:' . $title_font_size . 'px;line-height:' . $title_font_size . 'px;' : '';
		$style .= $title_font_weight ? 'font-weight:' . $title_font_weight . ';' : '';
		$style .= $title_color ? 'color:' . $title_color . ';' : '';
		$style .= $title_bg_color && ! $title_bg_image ? 'padding:10px;display:inline-block;background-color:' . $title_bg_color . ';' : '';
		$style .= $title_bg_image ? 'height: ' . $title_bg_image[ 'height' ] . 'px;' : '';

		$output .=
			'<div ' . ( $style ? 'style="' . $style . '"' : '' ) . ' class="kulam-slideshow-title">' .
				( $title_bg_image ? '<img src="' . $title_bg_image[ 'url' ] . '" style="margin-left: -' . intval( $title_bg_image[ 'width' ] )/2 . 'px;" alt="" />' : '' ) .
				'<div class="title' . ( $title_bg_image ? ' has-bg-image' : '' ) . '">' .
					( $title_link ? '<a href="' . $title_link . '"' . ( $title_color ? ' style="color:' . $title_color . ';"' : '' ) . '>' : '' ) .
					$title .
					( $title_link ? '</a>' : '' ) .
				'</div>' .
			'</div>';
	}

	$output .= kulam_pc_carousel_html( $pc, $id );

	$output .= '</div><!-- End of Kulam Post Carousel #' . $id . ' -->';

	// return
	return $output;

}

/**
 * kulam_pc_carousel_html
 *
 * This function returns a Posts Carousel HTML markup
 *
 * @param	$pc (int) Posts Carousel
 * @param	$id (string) Posts Carousel ID
 * @return	(string)
 */
function kulam_pc_carousel_html( $pc, $id ) {

	/**
	 * variables
	 */
	$categories			= $pc[ 'posts_categories' ];
	$number_of_posts	= $pc[ 'number_of_posts' ];
	$slide_width		= $pc[ 'carousel_options' ][ 'slide_width' ];
	$slide_height		= $pc[ 'carousel_options' ][ 'slide_height' ];
	$slide_margin		= $pc[ 'carousel_options' ][ 'slide_margin' ];
	$minimum_slides		= $pc[ 'carousel_options' ][ 'minimum_slides' ];
	$maximum_slides		= $pc[ 'carousel_options' ][ 'maximum_slides' ];
	$move_slides		= $pc[ 'carousel_options' ][ 'move_slides' ];
	$navigation			= $pc[ 'carousel_options' ][ 'navigation' ];
	$transition_speed	= $pc[ 'carousel_options' ][ 'transition_speed' ];
	$slide_duration		= $pc[ 'carousel_options' ][ 'slide_duration' ];
	$auto_play			= $pc[ 'carousel_options' ][ 'auto_play' ];
	$auto_pause_hover	= $pc[ 'carousel_options' ][ 'auto_pause_hover' ];
	$panels				= array();
	$output				= '';

	$wrapper_width = '100%';

	if ( empty( $slide_width ) || 0 === $slide_width )
		$slide_width = '200';

	if ( empty( $slide_height ) || 0 === $slide_height )
		$slide_height = '200';

	// get posts
	$args = array(
		'category__in'		=> $categories,
		'posts_per_page'	=> $number_of_posts > 0 ? $number_of_posts : -1,
	);
	$query = new WP_Query( $args );

	if ( $query->have_posts() ) : while( $query->have_posts() ) : $query->the_post();

		// get post thumbnail ID
		$thumbnail_id = get_post_thumbnail_id( $post->ID );

		$image_html = '';
		if ( ! empty( $thumbnail_id ) ) {
			$attachment_url = Pojo_Thumbnails::get_attachment_image_src(
				$thumbnail_id,
				apply_filters(
					'pojo_slideshow_carousel_thumbnail_args',
					array(
						'width' => $slide_width,
						'height' => $slide_height,
						'crop' => true,
						'placeholder' => true,
					)
				)
			);

			if ( $attachment_url ) {
				$image_title = get_the_title();

				$img_classes = array( 'carousel-image' );

				$image_html = sprintf( '<div style="width: %3$s; height: %4$s;"><img src="%1$s" alt="%2$s" title="%2$s" class="%5$s" /></div>', $attachment_url, esc_attr( $image_title ), esc_attr( $wrapper_width ), esc_attr( $slide_height . 'px' ), esc_attr( implode( ' ', $img_classes ) ) );
			}
		}

		if ( ! empty( $image_html ) ) {

			$panel_html = sprintf( '<a href="%s" target="%s">%s</a>', get_permalink( $post->ID ), '_self', $image_html );

			$panels[] = sprintf( '<div class="slide">%s</div>', $panel_html );

		}

	endwhile; endif; wp_reset_postdata();

	if ( empty( $panels ) )
		return '';

	$js_array = array();

	$js_array['slideWidth'] = $slide_width;
	$js_array['minSlides'] = absint( $minimum_slides );
	$js_array['maxSlides'] = absint( $maximum_slides );
	$js_array['moveSlides'] = absint( $move_slides );

	if ( ! $slide_margin && '0' !== $slide_margin )
		$slide_margin = 10;
	$js_array['slideMargin'] = absint( $slide_margin );

	$meta = absint( $slide_duration );
	if ( empty( $meta ) || 0 === $meta )
		$meta = 10000;
	$js_array['pause'] = $meta;

	$meta = absint( $transition_speed );
	if ( empty( $meta ) || 0 === $meta )
		$meta = 100;

	$js_array['speed']     = $meta;
	$js_array['captions']  = true;
	$js_array['autoStart'] = 'off' !== $auto_play;
	$js_array['autoHover'] = 'off' !== $auto_pause_hover;

	$js_array['auto'] = true;

	$js_array['pager']    = 'bullets' === $navigation || 'both' === $navigation;
	$js_array['controls'] = empty( $navigation ) || 'both' === $navigation;

	$js_json = ! empty( $js_array ) ? json_encode( $js_array ) : '';
	$print_js = '<script>jQuery(function($){$("div.pojo-slideshow-' . $id . '").bxSlider(' . $js_json . ');});</script>';

	return sprintf(
		'%s<div style="width: %s; height: %s; direction: ltr;" class="pojo-slideshow%s"><div class="pojo-slideshow-%s pojo-slideshow-wrapper">%s</div>%s</div>',
		$print_js,
		esc_attr( $wrapper_width ),
		esc_attr( $slide_height . 'px' ),
		$js_array['pager'] ? ' slideshow-bullets' : '',
		$id,
		implode( '', $panels ),
		''
	);

	// return
	return $output;

}

/**
 * kulam_gallery
 *
 * This function adds the "kulam_gallery" Shortcode
 *
 * @param	$atts (array)
 * @return	(string)
 */
function kulam_gallery( $atts ) {

	extract( shortcode_atts( array(
		'id'		=> '',
	), $atts ) );

	if ( ! $id )
		return;

	// return
	return kulam_gallery_html( $id );

}
add_shortcode( 'kulam-gallery', 'kulam_gallery' );

/**
 * kulam_gallery_html
 *
 * This function returns a Gallery module HTML markup
 *
 * @param	$id (int) Gallery ID
 * @return	(string)
 */
function kulam_gallery_html( $id ) {

	if ( ! function_exists( 'get_field' ) )
		// return
		return '';

	/**
	 * variables
	 */
	$title			= get_field( 'acf-gallery_title', $id );
	$date			= get_field( 'acf-gallery_date', $id );
	$description	= get_field( 'acf-gallery_description', $id );
	$scheme_color	= get_field( 'acf-gallery_scheme_color', $id );
	$images			= get_field( 'acf-gallery_images', $id );
	$output	= '';

	if ( ! $images )
		// return
		return $output;

	// Globals
	global $globals;

	$output .= '<!-- Gallery #' . $id . ' --><div class="kulam-gallery-layout-content">';
	$output .= $title ? '<h3 class="title" ' . ( $scheme_color ? 'style="color:' . $scheme_color . ';"' : '' ) . '>' . $title . '</h3>' : '';
	$output .= $date ? '<small class="date">' . $date . '</small>' : '';
	$output .= $description ? '<div class="description">' . $description . '</div>' : '';
	$output .= '<div class="kulam-gallery gallery-' . $id . ' row" itemscope itemtype="http://schema.org/ImageGallery">';

	$gallery = array(
		'images'		=> array(),
		'scheme_color'	=> $scheme_color,
	);

	foreach ( $images as $i ) {

		$image = array(
			'title'			=> esc_attr( kulam_trim_str( $i[ 'title' ] ) ),
			'caption'		=> esc_attr( kulam_trim_str( $i[ 'caption' ] ) ),
			'alt'			=> esc_attr( kulam_trim_str( $i[ 'alt' ] ) ),
			'description'	=> esc_attr( kulam_trim_str( $i[ 'description' ] ) ),
			'url'			=> esc_attr( kulam_trim_str( $i[ 'url' ] ) ),
			'date'			=> esc_attr( kulam_trim_str( get_field( 'acf-attachment_date', $i['ID'] ) ) ),
		);

		$gallery[ 'images' ][] = $image;

	}

	if ( $gallery[ 'images' ] ) {

		$i = 0;
		while ( $i <= 3 ) {
			$output .= '<div class="gallery-col col' . $i++ . ' col-sm-3"></div>';
		}

	}

	$output .= '</div>';

	if ( $gallery[ 'images' ] ) {

		// upload button
		$upload_text	= get_field( 'acf-option_gallery_upload_button_text', 'option' );
		$upload_page	= get_field( 'acf-option_gallery_upload_page', 'option' );

		$more_style = $scheme_color ? 'background-color:' . $scheme_color . ';border-color:' . $scheme_color . ';color:#FFF;' : '';
		$less_style = $scheme_color ? 'background-color:#FFF;border-color:' . $scheme_color . ';color:' . $scheme_color . ';' : '';

		$output .= '<div class="controls">';
		$output .= '<button class="btn load-more" style="' . ( $more_style ?: '' ) . '">' . __( 'Show more', 'kulam-scoop' ) . '</button>';
		$output .= '<button class="btn show-all" style="' . ( $more_style ?: '' ) . '">' . __( 'View all', 'kulam-scoop' ) . '</button>';
		$output .= '<button class="btn show-less" style="' . ( $less_style ?: '' ) . '">' . __( 'Show less', 'kulam-scoop' ) . '</button>';
		$output .= $upload_text && $upload_page ? '<a href="' . $upload_page . '" class="btn upload" style="' . ( $more_style ?: '' ) . '">' . $upload_text . '</a>' : '';
		$output .= '</div>';

		$globals[ '_galleries' ][ 'gallery-'.$id ] = $gallery;

	}

	$output .= '</div><!-- End of Gallery #' . $id . ' -->';

	// return
	return $output;

}