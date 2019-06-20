<?php
/**
 * Siddur functions
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions
 * @version		1.3.3
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
	$user_id	= isset( $_POST[ 'user_id' ] )		? $_POST[ 'user_id' ]		: '';
	$post_id	= isset( $_POST[ 'post_id' ] )		? $_POST[ 'post_id' ]		: '';

	if ( ! $site_id || ! $user_id || ! $post_id ) {
		echo '-1';
		wp_die();
	}

	// update user siddur for specific site
	$data = get_user_meta( $user_id, 'sidur' . $site_id, true );
	kulam_siddur_push( $post_id, $data );
	update_user_meta( $user_id, 'sidur' . $site_id, $data );

	// update user favorite
	$data = get_user_meta( $user_id, 'favorite' . $site_id, true );
	kulam_siddur_push( $post_id, $data );
	update_user_meta( $user_id, 'favorite' . $site_id, $data );

	echo '1';

	wp_die();

}
add_action( 'wp_ajax_add_to_siddur', 'kulam_add_to_siddur', 10, 1 );
add_action( 'wp_ajax_nopriv_add_to_siddur', 'kulam_add_to_siddur', 10, 1 );

/**
 * kulam_remove_from_siddur
 *
 * This function removes a single post from current user siddur
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

	if ( ! $site_id || ! $user_id || ! $post_id ) {
		echo '-1';
		wp_die();
	}

	// update user siddur for specific site
	$data = get_user_meta( $user_id, 'sidur' . $site_id, true );
	if ( kulam_siddur_pull( $post_id, $data ) )
		update_user_meta( $user_id, 'sidur' . $site_id, $data );

	// update user favorite
	$data = get_user_meta( $user_id, 'favorite' . $site_id, true );
	if ( kulam_siddur_pull( $post_id, $data ) )
		update_user_meta( $user_id, 'favorite' . $site_id, $data );

	// remove post ID from all user folders for specific site
	$folders = get_user_meta( $user_id, 'nameFolder' . $site_id, true );

	if ( $folders ) {

		$folders = json_decode( $folders, true );

		foreach ( $folders as $folder ) {

			$data = get_user_meta( $user_id, $folder . $site_id, true );
			if ( kulam_siddur_pull( $post_id, $data ) )
				update_user_meta( $user_id, $folder . $site_id , $data );

		}
	}

	echo '1';

	wp_die();

}
add_action( 'wp_ajax_remove_from_siddur', 'kulam_remove_from_siddur', 10, 1 );
add_action( 'wp_ajax_nopriv_remove_from_siddur', 'kulam_remove_from_siddur', 10, 1 );

/**
 * kulam_add_to_folders
 *
 * This function adds posts to current user siddur folders
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_add_to_folders() {

	/**
	 * Variables
	 */
	$site_id	= get_current_blog_id();
	$user_id	= isset( $_POST[ 'user_id' ] )		? $_POST[ 'user_id' ]											: '';
	$post_ids	= isset( $_POST[ 'post_ids' ] )		? json_decode( $_POST[ 'post_ids' ], true )						: '';
	$folders	= isset( $_POST[ 'folders' ] )		? json_decode( stripslashes( $_POST[ 'folders' ] ), true )		: '';

	if ( ! $site_id || ! $user_id || ! $post_ids || ! $folders ) {
		echo '-1';
		wp_die();
	}

	// update user siddur folders for specific site
	foreach ( $folders as $folder ) {

		$data = get_user_meta( $user_id, $folder . $site_id, true );

		foreach ( $post_ids as $post_id )
			kulam_siddur_push( $post_id, $data );

		update_user_meta( $user_id, $folder . $site_id, $data );

	}

	// update user favorite - in case posts were not added to siddur before add them to folders (in case posts added to folders right from category page)
	$data = get_user_meta( $user_id, 'favorite' . $site_id, true );

	foreach ( $post_ids as $post_id )
		kulam_siddur_push( $post_id, $data );

	update_user_meta( $user_id, 'favorite' . $site_id, $data );

	// update user siddur for specific site - in case posts were first added to siddur (in case posts added to folders from siddur)
	$data = get_user_meta( $user_id, 'sidur' . $site_id, true );

	foreach ( $post_ids as $post_id )
		kulam_siddur_pull( $post_id, $data );

	update_user_meta( $user_id, 'sidur' . $site_id, $data );

	echo '1';

	wp_die();

}
add_action( 'wp_ajax_add_to_folders', 'kulam_add_to_folders', 10, 1 );
add_action( 'wp_ajax_nopriv_add_to_folders', 'kulam_add_to_folders', 10, 1 );

/**
 * kulam_remove_from_folder
 *
 * This function removes a single post from current user siddur folder
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_remove_from_folder() {

	/**
	 * Variables
	 */
	$site_id	= get_current_blog_id();
	$user_id	= isset( $_POST[ 'user_id' ] )		? $_POST[ 'user_id' ]		: '';
	$post_id	= isset( $_POST[ 'post_id' ] )		? $_POST[ 'post_id' ]		: '';
	$folder		= isset( $_POST[ 'folder' ] )		? $_POST[ 'folder' ]		: '';

	if ( ! $site_id || ! $user_id || ! $post_id || ! $folder ) {
		echo '-1';
		wp_die();
	}

	// update user siddur folder for specific site
	$data = get_user_meta( $user_id, $folder . $site_id, true );
	if ( kulam_siddur_pull( $post_id, $data ) )
		update_user_meta( $user_id, $folder . $site_id , $data );

	// update user siddur for specific site
	$data = get_user_meta( $user_id, 'sidur' . $site_id, true );
	kulam_siddur_push( $post_id, $data );
	update_user_meta( $user_id, 'sidur' . $site_id, $data );

	echo '1';

	wp_die();

}
add_action( 'wp_ajax_remove_from_folder', 'kulam_remove_from_folder', 10, 1 );
add_action( 'wp_ajax_nopriv_remove_from_folder', 'kulam_remove_from_folder', 10, 1 );

/**
 * kulam_siddur_push
 *
 * This function pushes a single value to a JSON structure
 *
 * @param	$value (int)
 * @param	&$data (json)
 * @return	N/A
 */
function kulam_siddur_push( $value, &$data ) {

	if ( $data ) {

		$data = json_decode( $data, true );

		if ( ! in_array( $value, $data ) ) {
			$data[] = $value;
		}

	}
	else{

		$data = array(
			0 => $value,
		);

	}

	$data = json_encode( $data );

}

/**
 * kulam_siddur_pull
 *
 * This function pulls a single value from a JSON structure
 *
 * @param	$value (int)
 * @param	&$data (json)
 * @return	(bool) whether $data has been changed
 */
function kulam_siddur_pull( $value, &$data ) {

	/**
	 * Variables
	 */
	$changed = false;

	if ( $data ) {

		$data = json_decode( $data, true );

		foreach ( $data as $key => $val ) {
			if ( $val == $value ) {

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