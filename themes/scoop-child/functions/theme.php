<?php
/**
 * Theme functions
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions
 * @version     1.7.32
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Theme version
 * Used to register styles and scripts
 */
if ( function_exists( 'wp_get_theme' ) ) {

	$theme_data		= wp_get_theme();
	$theme_version	= $theme_data->get( 'Version' );

}
else {

	$theme_data		= get_theme_data( trailingslashit( get_stylesheet_directory() ) . 'style.css' );
	$theme_version	= $theme_data[ 'Version' ];

}
define( 'KULAM_VERSION', $theme_version );

/**
 * kulam_enqueue_admin_styles
 *
 * This function enqueues admin styles
 *
 * @param   N/A
 * @return  N/A
 */
function kulam_enqueue_admin_styles() {

	wp_register_style( 'admin-style',		get_stylesheet_directory_uri() . '/assets/css/admin/style.css',	array(),	KULAM_VERSION );
	wp_register_style( 'admin-style-rtl',	get_stylesheet_directory_uri() . '/assets/css/admin/rtl.css',	array(),	KULAM_VERSION );

}
add_action( 'admin_enqueue_scripts', 'kulam_enqueue_admin_styles' );

/**
 * kulam_enqueue_admin_scripts
 *
 * This function enqueues admin scripts
 *
 * @param   N/A
 * @return  N/A
 */
function kulam_enqueue_admin_scripts() {

	/**
	 * Variables
	 */
	$screen = get_current_screen();

	if ( $screen && 'category' == $screen->taxonomy ) {

		wp_enqueue_script( 'acf-autopopulates', get_stylesheet_directory_uri() . '/assets/js/admin/acf-autopopulates.js',	array(),	KULAM_VERSION );
		wp_localize_script( 'acf-autopopulates', 'acf_ap_vars', array(
			'acf_ap_nonce' => wp_create_nonce( 'acf_ap_nonce' )
		));

	}

}
add_action( 'admin_enqueue_scripts', 'kulam_enqueue_admin_scripts' );

/**
 * kulam_enqueue_styles
 *
 * This function enqueues theme styles
 *
 * @param   N/A
 * @return  N/A
 */
function kulam_enqueue_styles() {

	// dequeue already defined theme styles
	wp_dequeue_style( 'pojo-style' );
	wp_dequeue_style( 'pojo-style-rtl' );
	wp_deregister_style( 'pojo-style' );
	wp_deregister_style( 'pojo-style-rtl' );

	wp_register_style( 'photoswipe',				get_stylesheet_directory_uri() . '/assets/css/libs/PhotoSwipe/photoswipe.css',					array(),						KULAM_VERSION );
	wp_register_style( 'photoswipe-default-skin',	get_stylesheet_directory_uri() . '/assets/css/libs/PhotoSwipe/default-skin/default-skin.css',	array(),						KULAM_VERSION );

	// https://goodies.pixabay.com/javascript/auto-complete/demo.html
	wp_enqueue_style( 'auto-complete',				get_stylesheet_directory_uri() . '/assets/css/auto-complete.css',								array(),						KULAM_VERSION );
	wp_enqueue_style( 'pojo-style',					get_stylesheet_directory_uri() . '/assets/css/style.css',										array( 'pojo-css-framework' ),	KULAM_VERSION );

	if ( is_rtl() ) {
		wp_enqueue_style( 'pojo-style-rtl', get_stylesheet_directory_uri() . '/assets/css/rtl.css', array( 'pojo-css-framework', 'pojo-style' ), KULAM_VERSION );
	}

}
add_action( 'wp_enqueue_scripts', 'kulam_enqueue_styles', 700 );

/**
 * kulam_enqueue_scripts
 *
 * This function enqueues theme scripts
 *
 * @param   N/A
 * @return  N/A
 */
function kulam_enqueue_scripts() {

	if ( function_exists( 'get_field' ) ) {

		$google_maps_api = get_field( 'acf-option_google_maps_api', 'option' );

		if ( $google_maps_api ) {

			wp_register_script( 'kulam-google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $google_maps_api . '&language=' . substr( get_bloginfo( 'language' ), 0, 2 ), array( 'jquery' ), KULAM_VERSION, true );

			$google_maps_api_params = array (
				'_googleMapsApi'	=> true
			);

			wp_localize_script( 'kulam-google-maps', 'googleMapsData', $google_maps_api_params );
			wp_enqueue_script( 'kulam-google-maps' );

		}

	}

	wp_register_script( 'photoswipe',				get_stylesheet_directory_uri() . '/assets/js/libs/PhotoSwipe/photoswipe.js',				array( 'jquery' ),									KULAM_VERSION,	true );
	wp_register_script( 'photoswipe-ui-default',	get_stylesheet_directory_uri() . '/assets/js/libs/PhotoSwipe/photoswipe-ui-default.js',		array( 'jquery', 'photoswipe' ),					KULAM_VERSION,	true );

	// https://goodies.pixabay.com/javascript/auto-complete/demo.html
	wp_register_script( 'auto-complete',			get_stylesheet_directory_uri() . '/assets/js/auto-complete.min.js',							array( 'jquery' ),									KULAM_VERSION,	true );
	wp_register_script( 'cycle2',					get_stylesheet_directory_uri() . '/assets/js/jquery.cycle2.min.js',							array( 'jquery' ),									KULAM_VERSION,	true );
	wp_register_script( 'cycle2-swipe',				get_stylesheet_directory_uri() . '/assets/js/jquery.cycle2.swipe.min.js',					array( 'jquery' ),									KULAM_VERSION,	true );
	wp_register_script( 'kulam-js',					get_stylesheet_directory_uri() . '/assets/js/scripts.js',									array( 'jquery', 'auto-complete' ),					KULAM_VERSION,	true );

	if ( 'main.php' == basename( get_page_template() ) ) {
		wp_enqueue_script( 'cycle2' );
		wp_enqueue_script( 'cycle2-swipe' );
	}

	$params = array (
		'ajaxurl'		=> admin_url( 'admin-ajax.php' ),
		'ajax_nonce'	=> wp_create_nonce( 'my-special-string' ),
		'post_id'		=> get_queried_object_id(),
		'user_id'		=> get_current_user_id(),
		'strings'		=> array(
			'read_more'		=> __( 'READ MORE', 'kulam-scoop' ),
		),
		'query_string'	=> $_GET,
	);

	wp_localize_script( 'kulam-js', 'ajaxdata', $params );
	wp_enqueue_script( 'kulam-js' );

	wp_enqueue_media();

}
add_action( 'wp_enqueue_scripts', 'kulam_enqueue_scripts' );

/**
 * Globals
 */
global $globals;

$globals = array(

	// Galleries
	'_galleries' => array(),		// Array of arrays of galleries images

);

/**
 * kulam_remove_admin_bar
 *
 * This function removes the admin bar for non administrators logged in users
 *
 * @param   $show (bool)
 * @return  (bool)
 */
function kulam_remove_admin_bar( $show ) {

	if ( ! current_user_can( 'administrator' ) && ! is_admin() ) {

		$show = false;

	}

	// return
	return $show;

}
add_filter( 'show_admin_bar', 'kulam_remove_admin_bar', 20, 1 );

/**
 * kulam_rename_post_formats
 *
 * This function renames specific post formats
 *
 * @param   $translated (string)
 * @param   $text (string)
 * @param   $context (string)
 * @param   $domain (string)
 * @return  (string)
 */
function kulam_rename_post_formats( $translated, $text, $context, $domain ) {

	$names = array(
		'Standard' => __( 'Text', 'kulam-scoop' )
	);

	if ( 'Post format' == $context && array_key_exists( $text, $names ) ) {

		$translated = str_replace( array_keys( $names ), array_values( $names ), $text );

	}

	// return
	return $translated;

}
add_filter( 'gettext_with_context', 'kulam_rename_post_formats', 10, 4 );