<?php
/**
 * Pojo functions
 *
 * @author		Nir Goldberg
 * @package		functions
 * @version		2.1.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_pojo_register_customize_sections
 *
 * This function modifies Pojo customizer default settings
 *
 * @param	$sections (array)
 * @return	(array)
 */
function kulam_pojo_register_customize_sections( $sections = array() ) {

	if ( ! function_exists( 'get_field' ) )
		return $sections;

	/**
	 * Variables
	 */
	$color_scheme	= array(
		'color-1'		=> '#EEEBE9',
		'color-2'		=> '#F3E8E2',
		'color-3'		=> '#F6F6F6',
		'color-4'		=> '#FFFFFF',
		'color-5'		=> '#CBDEE6',
		'color-6'		=> '#FFD8BC',
		'color-7'		=> '#FFF1CC',
		'title-color'	=> '#000000',
		'title-hover'	=> '#000000',
		'menu-color'	=> '#2D2D2D',
		'menu-hover'	=> '#2D2D2D',
		'font-color'	=> '#2D2D2D',
		'hover-color'	=> '#2D2D2D',
		'link-color'	=> '#00587E',
		'link-hover'	=> '#00587E',
	);

	$typography		= array(
		'font-family'	=> 'Work Sans',
		'titles'		=> array(
			'h1'		=> array(
				'font-size'		=> 	'60px',
			),
			'h2'		=> array(
				'font-size'		=> 	'35px',
			),
			'h3'		=> array(
				'font-size'		=> 	'20px',
			),
			'h4'		=> array(
				'font-size'		=> 	'18px',
			),
			'h5'		=> array(
				'font-size'		=> 	'16px',
			),
			'h6'		=> array(
				'font-size'		=> 	'14px',
			),
		),
	);

	/**
	 * Style
	 */
	$section	= array_search( 'style', array_column( $sections, 'id' ) );
	$field		= array_search( 'primary_color', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= $color_scheme[ 'color-3' ];

	$field		= array_search( 'secondary_color', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= $color_scheme[ 'color-1' ];

	$field		= array_search( 'primary_border_color', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= $color_scheme[ 'color-1' ];

	$field		= array_search( 'layout_site', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= 'wide';

	$field		= array_search( 'bg_body', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]			= $color_scheme[ 'color-3' ];

	$field		= array_search( 'bg_primary', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]			= '#FFFFFF';

	$field		= array_search( 'button_typo', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'family' ]		= $typography[ 'font-family' ];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]			= $color_scheme[ 'title-color' ];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'transform' ]		= 'none';

	$field		= array_search( 'button_typo_hover', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= $color_scheme[ 'title-hover' ];

	$field		= array_search( 'button_background', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= $color_scheme[ 'color-1' ];

	$field		= array_search( 'button_background_hover', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= $color_scheme[ 'color-2' ];

	$field		= array_search( 'button_border', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= $color_scheme[ 'color-1' ];

	$field		= array_search( 'button_border_hover', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= $color_scheme[ 'color-2' ];

	/**
	 * Logo
	 */
	$section	= 2;//array_search( 'logo', array_column( $sections, 'id' ) );
	$field		= array_search( 'image_logo_width', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= '343px';

	$section	= 3;//array_search( 'logo', array_column( $sections, 'id' ) );
	$field		= array_search( 'typo_site_title', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'family' ]		= $typography[ 'font-family' ];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]			= $color_scheme[ 'title-color' ];

	$field		= array_search( 'image_logo', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= get_field( 'acf-option_logo_header_logo', 'option' );

	$field		= array_search( 'image_logo_margin_top', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= '0px';

	$field		= array_search( 'image_logo_margin_bottom', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= '0px';

	$field		= array_search( 'image_sticky_header_logo', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= get_field( 'acf-option_logo_footer_logo', 'option' );

	/**
	 * Top Bar
	 */
	$section	= array_search( 'top_bar', array_column( $sections, 'id' ) );
	$field		= array_search( 'bg_top_bar', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]			= $color_scheme[ 'color-3' ];

	$field		= array_search( 'typo_top_bar', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'family' ]		= $typography[ 'font-family'];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]			= $color_scheme[ 'title-color'];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'transform' ]		= 'none';

	$field		= array_search( 'color_link_top_bar', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= $color_scheme[ 'link-color'];

	$field		= array_search( 'color_link_hover_top_bar', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= $color_scheme[ 'link-hover'];

	/**
	 * Header
	 */
	$section	= array_search( 'header', array_column( $sections, 'id' ) );
	$field		= array_search( 'bg_header', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]			= $color_scheme[ 'color-3' ];

	$field		= array_search( 'header_border_color', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= $color_scheme[ 'color-3' ];

	$field		= array_search( 'chk_enable_sticky_header', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= 0;

	/**
	 * Navigation
	 */
	$section	= array_search( 'menus', array_column( $sections, 'id' ) );
	$field		= array_search( 'height_menu', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= '72px';

	$field		= array_search( 'typo_menu_primary', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'family' ]		= $typography[ 'font-family'];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'weight' ]		= 700;
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]			= $color_scheme[ 'menu-color' ];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'transform' ]		= 'none';

	$field		= array_search( 'color_menu_primary_hover', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= $color_scheme[ 'menu-hover' ];

	/**
	 * Search Bar
	 */
	$section	= array_search( 'search_bar', array_column( $sections, 'id' ) );
	$field		= array_search( 'chk_enable_menu_search', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= 0;

	/**
	 * Sub Header
	 */
	$section	= array_search( 'sub_header', array_column( $sections, 'id' ) );
	$field		= array_search( 'bg_sub_header', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]			= $color_scheme[ 'color-3' ];

	$field		= array_search( 'typo_sub_header', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'family' ]		= $typography[ 'font-family'];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]			= $color_scheme[ 'title-color' ];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'transform' ]		= 'none';

	$field		= array_search( 'color_link_sub_header', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= $color_scheme[ 'link-color'];

	$field		= array_search( 'color_link_hover_sub_header', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= $color_scheme[ 'link-hover'];

	/**
	 * Title Bar
	 */
	$section	= array_search( 'title_bar', array_column( $sections, 'id' ) );
	$field		= array_search( 'height_title_bar', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= '184px';

	$field		= array_search( 'background_title_bar', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]			= $color_scheme[ 'color-3' ];

	$field		= array_search( 'typo_title_title_bar', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'size' ]			= $typography[ 'titles' ][ 'h2' ][ 'font-size' ];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'family' ]		= $typography[ 'font-family' ];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]			= $color_scheme[ 'title-color' ];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'transform' ]		= 'none';

	$field		= array_search( 'typo_breadcrumbs_title_bar', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'size' ]			= '14px';
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'family' ]		= $typography[ 'font-family' ];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]			= $color_scheme[ 'title-color' ];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'transform' ]		= 'none';

	/**
	 * Typography
	 */
	$section	= array_search( 'typography', array_column( $sections, 'id' ) );
	$field		= array_search( 'typo_body_text', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'size' ]			= '16px';
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'family' ]		= $typography[ 'font-family' ];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]			= $color_scheme[ 'font-color' ];

	$field		= array_search( 'color_link', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= $color_scheme[ 'link-color'];

	$field		= array_search( 'color_link_hover', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= $color_scheme[ 'link-hover'];

	$field		= array_search( 'color_text_selection', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= $color_scheme[ 'font-color'];

	$field		= array_search( 'color_text_bg_selection', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= $color_scheme[ 'color-1'];

	// H1-H6
	$fields		= array( 'typo_h1', 'typo_h2', 'typo_h3', 'typo_h4', 'typo_h5', 'typo_h6' );

	foreach ( $fields as $f ) {

		$field		= array_search( $f, array_column( $sections[ $section ][ 'fields' ], 'id' ) );
		$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'size' ]			= $typography[ 'titles' ][ substr( $f, 5 ) ][ 'font-size' ];
		$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'family' ]		= $typography[ 'font-family' ];
		$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'weight' ]		= 700;
		$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]			= $color_scheme[ 'title-color' ];
		$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'transform' ]		= 'none';

	}

	/**
	 * Content
	 */
	$section	= array_search( 'content', array_column( $sections, 'id' ) );

	// titles
	$fields		= array(
		'typo_heading_list',
		'typo_heading_list_two',
		'typo_heading_list_Three',
		'typo_heading_big_thumbnail',
		'typo_heading_list_format',
		'typo_heading_grid_one',
		'typo_heading_grid_two',
		'typo_heading_grid_three',
		'typo_heading_grid_four',
		'typo_heading_posts_group_featured',
		'typo_heading_posts_group',
		'typo_meta_data_archive',
		'typo_meta_data_single',
		'typo_excerpt_archive',
		'typo_excerpt_single',
		'typo_category_label',
		'typo_nav_breadcrumbs',
	);

	foreach ( $fields as $f ) {

		$field		= array_search( $f, array_column( $sections[ $section ][ 'fields' ], 'id' ) );
		$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'family' ]		= $typography[ 'font-family' ];
		$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]			= $color_scheme[ 'title-color' ];
		$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'transform' ]		= 'none';

	}

	$field		= array_search( 'typo_nav_breadcrumbs', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'size' ]				= '14px';

	/**
	 * Footer Widgets
	 */
	$section	= array_search( 'footer_widgets', array_column( $sections, 'id' ) );
	$field		= array_search( 'bg_footer_widgets', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]				= null;

	$field		= array_search( 'typo_text_footer_widgets', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'size' ]				= '14px';
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'family' ]			= $typography[ 'font-family' ];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]				= $color_scheme[ 'title-color' ];

	$field		= array_search( 'color_link_footer_widgets', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]						= $color_scheme[ 'title-color' ];

	$field		= array_search( 'color_link_hover_footer_widgets', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]						= $color_scheme[ 'title-color' ];

	$field		= array_search( 'typo_title_footer_widgets', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'size' ]				= '20px';
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'family' ]			= $typography[ 'font-family' ];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]				= $color_scheme[ 'title-color' ];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'transform' ]			= 'none';

	$field		= array_search( 'footer_widgets_columns', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]						= 4;

	/**
	 * Footer Copyright
	 */
	$section	= array_search( 'footer', array_column( $sections, 'id' ) );
	$field		= array_search( 'bg_footer_copyright', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]				= null;

	$field		= array_search( 'footer_copyright_border_color', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]						= $color_scheme[ 'color-1' ];

	$field		= array_search( 'typo_text_footer', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'size' ]				= '14px';
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'family' ]			= $typography[ 'font-family' ];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'weight' ]			= 700;
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'color' ]				= $color_scheme[ 'title-color' ];
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ][ 'transform' ]			= 'none';

	$field		= array_search( 'footer_copyright_color_link', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]						= $color_scheme[ 'title-color' ];

	$field		= array_search( 'footer_copyright_color_link_hover', array_column( $sections[ $section ][ 'fields' ], 'id' ) );
	$sections[ $section ][ 'fields' ][ $field ][ 'std' ]						= $color_scheme[ 'title-color' ];

	/**
	 * Layout
	 */
	$section	= array_search( 'layout', array_column( $sections, 'id' ) );

	// layouts
	$fields		= array(
		'pojo_general_layouts',
		'post_layout_archive',
		'post_layout',
		'page_layout',
		'attachment_layout_archive',
		'attachment_layout',
		'elementor_library_layout_archive',
		'elementor_library_layout',
		'gallery_layout_archive',
		'gallery_layout',
		'pojo_404_layouts',
	);

	foreach ( $fields as $f ) {

		$field		= array_search( $f, array_column( $sections[ $section ][ 'fields' ], 'id' ) );
		$sections[ $section ][ 'fields' ][ $field ][ 'std' ]					= 'full';

	}

	// set_theme_mods
	foreach ( $sections as $section ) {
		foreach ( $section['fields'] as $field ) {
			$option = get_theme_mod( $field['id'] );
			if ( ! $option || isset( $option[ 'color' ] ) && 'null' == $option[ 'color' ] ) {
				set_theme_mod( $field['id'], $field['std'] );
			}
		}
	}

/*	?><pre><?php print_r( $sections ); ?></pre><?php */

	// return
	return $sections;

}
add_filter( 'pojo_register_customize_sections', 'kulam_pojo_register_customize_sections', 600 );

/**
 * kulam_pojo_style
 *
 * This function adds custom style according to Pojo customizer settings
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_pojo_style() {

	// vars
	$primary_color				= get_theme_mod( 'primary_color' );
	$button_background			= get_theme_mod( 'button_background' );
	$button_background_hover	= get_theme_mod( 'button_background_hover' );

	?>

	<style type="text/css">

		/********/
		/* main */
		/********/

		body.page-template-main #primary {
			background-color: <?php echo $primary_color; ?>;
		}

		/***************/
		/* main banner */
		/***************/

		.main-banner .controls .control {
			background-color: <?php echo $button_background; ?>;
		}

		.main-banner .controls .control:hover {
			background-color: <?php echo $button_background_hover; ?>;
		}

	</style>

	<?php

}
add_action( 'wp_head', 'kulam_pojo_style' );