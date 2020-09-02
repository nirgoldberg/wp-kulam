<?php
/**
 * Login functions
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions
 * @version		2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// get login page
$args = array(
	'meta_key'		=> '_wp_page_template',
	'meta_value'	=> 'page-templates/login.php'
);
$pages = get_pages( $args );

if ( $pages ) {
	foreach ( $pages as $page ) {
		$page_id = $page->ID;
	}
}

/**
 * kulam_redirect_to_login_page
 *
 * This function will redirect wp-login.php to the custom login page
 *
 * @param   N/A
 * @return  N/A
 */
function kulam_redirect_to_login_page() {

	// vars
	global $page_id;
	$login_page	= home_url( '/?page_id=' . $page_id . '/' );
	$page		= basename( $_SERVER[ 'REQUEST_URI' ] );

	if ( $page == "wp-login.php" && $_SERVER[ 'REQUEST_METHOD' ] == 'GET' ) {

		// redirect
		wp_redirect( $login_page );

		// exit
		exit;

	}

}
add_action( 'init', 'kulam_redirect_to_login_page' );

/**
 * kulam_login_failed
 *
 * This function will redirect failed login request to the custom login page
 *
 * @param   N/A
 * @return  N/A
 */
function kulam_login_failed() {

	// vars
	global $page_id;
	$login_page	= home_url( '/?page_id=' . $page_id . '/' );

	// redirect
	wp_redirect( $login_page . '&login=failed' );

	// exit
	exit;

}
add_action( 'wp_login_failed', 'kulam_login_failed' );

/**
 * kulam_blank_username_password
 *
 * This function will redirect failed login request to the custom login page
 *
 * @param   $user (object)
 * @param	$username (string)
 * @param	$password (string)
 * @return  N/A
 */
function kulam_blank_username_password( $user, $username, $password ) {

	// vars
	global $page_id;
	$login_page	= home_url( '/?page_id=' . $page_id . '/' );

	if ( $username == '' || $password == '' ) {

		// redirect
		wp_redirect( $login_page . '&login=blank' );

		// exit
		exit;

	}

}
add_filter( 'authenticate', 'kulam_blank_username_password', 1, 3 );

/**
 * kulam_logout_page
 *
 * This function will redirect logout request to the custom login page
 *
 * @param   $user (object)
 * @param	$username (string)
 * @param	$password (string)
 * @return  N/A
 */
function kulam_logout_page() {

	// vars
	global $page_id;
	$login_page	= home_url( '/?page_id=' . $page_id . '/' );

	// redirect
	wp_redirect( $login_page . "&login=false" );

	// exit
	exit;

}
add_action( 'wp_logout', 'kulam_logout_page' );