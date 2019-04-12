<?php
/**
 * Theme functions
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions
 * @version     1.1.3
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

	wp_enqueue_style( 'pojo-style', get_stylesheet_directory_uri() . '/assets/css/style.css', array( 'pojo-css-framework' ), KULAM_VERSION );

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

	wp_register_script( 'kulam-js', get_stylesheet_directory_uri() . '/assets/js/scripts.js', array('jquery'), KULAM_VERSION, true );
	wp_register_script( 'kulam-js-favorite', get_stylesheet_directory_uri() . '/assets/js/scriptsForThumbnail.js', array('jquery'), KULAM_VERSION, true );

	$params = array (
		'ajaxurl'		=> admin_url( 'admin-ajax.php' ),
		'ajax_nonce'	=> wp_create_nonce( 'my-special-string' ),
		'post_id'		=> get_queried_object_id(),
		'user_id'		=> get_current_user_id()
	);

	$paramsForThumbnail = array (
		'ajaxurl'		=> admin_url( 'admin-ajax.php' ),
		'ajax_nonce'	=> wp_create_nonce( 'my-special-string' ),
		'user_id'		=> get_current_user_id()
	);

	wp_localize_script( 'kulam-js-favorite', 'ajaxdata', $paramsForThumbnail );
	wp_enqueue_script( 'kulam-js-favorite' );

	wp_localize_script( 'kulam-js', 'ajaxdata', $params );
	wp_enqueue_script( 'kulam-js' );

}
add_action( 'wp_enqueue_scripts', 'kulam_enqueue_scripts' );

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