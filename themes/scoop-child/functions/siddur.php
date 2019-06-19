<?php
/**
 * Siddur functions
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions
 * @version		1.3.2
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_add_to_siddur
 *
 * This function adds a single post to current user siddur
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_add_to_siddur() {

	/**
	 * Variables
	 */
	$site_id	= get_current_blog_id();
	$user_id	= isset( $_POST[ 'user_id' ] )	? $_POST[ 'user_id' ]	: '';
	$post_id	= isset( $_POST[ 'post_id' ] )	? $_POST[ 'post_id' ]	: '';

	if ( ! $site_id && ! $user_id && ! $post_id ) {
		echo '-1';
		wp_die();
	}

	// update user siddur for specific site
	$data = get_user_meta( $user_id, 'sidur' . $site_id, true );
	kulam_add_post_id_to_siddur( $post_id, $data );
	update_user_meta( $user_id, 'sidur' . $site_id, $data );

	// update user favorite
	$data = get_user_meta( $user_id, 'favorite' . $site_id, true );
	kulam_add_post_id_to_siddur( $post_id, $data );
	update_user_meta( $user_id, 'favorite' . $site_id, $data );

	echo '1';

	wp_die();

}
add_action( 'wp_ajax_add_to_sidur', 'kulam_add_to_siddur', 10, 1 );
add_action( 'wp_ajax_nopriv_add_to_sidur', 'kulam_add_to_siddur', 10, 1 );

/**
 * kulam_remove_from_siddur
 *
 * This function adds a single post to current user siddur
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_remove_from_siddur() {

	/**
	 * Variables
	 */
	$site_id	= get_current_blog_id();
	$user_id	= isset( $_POST[ 'user_id' ] )		? $_POST[ 'user_id' ]		: '';
	$post_id	= isset( $_POST[ 'post_id' ] )		? $_POST[ 'post_id' ]		: '';

	if ( ! $site_id && ! $user_id && ! $post_id ) {
		echo '-1';
		wp_die();
	}

	// update user siddur for specific site
	$data = get_user_meta( $user_id, 'sidur' . $site_id, true );
	if ( kulam_remove_post_id_from_siddur( $post_id, $data ) )
		update_user_meta( $user_id, 'sidur' . $site_id, $data );

	// update user favorite
	$data = get_user_meta( $user_id, 'favorite' . $site_id, true );
	if ( kulam_remove_post_id_from_siddur( $post_id, $data ) )
		update_user_meta( $user_id, 'favorite' . $site_id, $data );

	// remove post ID from all user folders for specific site
	$folders = get_user_meta( $user_id, 'nameFolder' . $site_id, true );

	if ( $folders ) {

		$folders = json_decode( $folders, true );

		foreach ( $folders as $folder ) {

			$data = get_user_meta( $user_id, $folder . $site_id, true );
			if ( kulam_remove_post_id_from_siddur( $post_id, $data ) )
				update_user_meta( $user_id, $folder . $site_id , $data );

		}
	}

	echo '1';

	wp_die();

}
add_action( 'wp_ajax_remove_from_sidur', 'kulam_remove_from_siddur', 10, 1 );
add_action( 'wp_ajax_nopriv_remove_from_sidur', 'kulam_remove_from_siddur', 10, 1 );

/**
 * kulam_add_post_id_to_siddur
 *
 * This function adds a single post ID to a JSON structure
 *
 * @param	$post_id (int)
 * @param	&$data (json)
 * @return	N/A
 */
function kulam_add_post_id_to_siddur( $post_id, &$data ) {

	if ( $data ) {

		$data = json_decode( $data, true );

		if ( ! in_array( $post_id, $data ) ) {
			$data[] = $post_id;
		}

	}
	else{

		$data = array(
			0 => $post_id,
		);

	}

	$data = json_encode( $data );

}

/**
 * kulam_remove_post_id_from_siddur
 *
 * This function removes a single post ID from a JSON structure
 *
 * @param	$post_id (int)
 * @param	&$data (json)
 * @return	(bool) whether $data has been changed
 */
function kulam_remove_post_id_from_siddur( $post_id, &$data ) {

	/**
	 * Variables
	 */
	$changed = false;

	if ( $data ) {

		$data = json_decode( $data, true );

		foreach ( $data as $key => $p_id ) {
			if ( $p_id == $post_id ) {

				unset( $data[ $key ] );
				$changed = true;
				break;

			}
		}

		$data = json_encode( $data );

	}

	// return
	return $changed;

}