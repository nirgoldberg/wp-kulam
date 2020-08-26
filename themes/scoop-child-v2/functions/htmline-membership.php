<?php
/**
 * HTMLine Memmbership action and filter hooks
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions
 * @version     1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_hmembership_user_userdata
 *
 * @param   $userdata (array) Role user data
 * @param	$user (array) User data
 * @return  (array)
 */
function kulam_hmembership_user_userdata( $userdata, $user ) {

	// vars
	$user_info		= unserialize( $user[ 'user_info' ] );
	$field_keys		= array_keys( $user_info );
	$field_labels	= array(
		'user_login'	=> 'Username',
		'user_nicename'	=> 'First Name',
		'display_name'	=> 'First Name',
		'first_name'	=> 'First Name',
		'last_name'		=> 'Last Name',
	);

	// store relevant userdata found in user info
	$userdata_info = array();

	foreach ( $field_labels as $key => $value ) {
		$matches = preg_grep( "/hmembership-[\d+]-(" . sanitize_title_with_dashes( $value ) . ")/", $field_keys );

		if ( $matches && $user_info[ current( $matches ) ][ 'value' ] ) {
			$userdata_info[ $key ] = $user_info[ current( $matches ) ][ 'value' ];
		}
	}

	// return
	return array_merge( $userdata, $userdata_info );

}
add_filter( 'hmembership_user/userdata', 'kulam_hmembership_user_userdata', 10, 2 );