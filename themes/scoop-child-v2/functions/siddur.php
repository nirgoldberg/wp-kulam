<?php
/**
 * Siddur functions
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions
 * @version		1.4.10
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

/**
 * kulam_check_public_folder
 *
 * This function checks folder public status
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_check_public_folder() {

	/**
	 * Variables
	 */
	global $wpdb, $sitepress;

	$table				= $wpdb->prefix . 'public_folders';
	$site_id			= get_current_blog_id();
	$user_id			= isset( $_POST[ 'user_id' ] )		? $_POST[ 'user_id' ]					: '';
	$folder				= isset( $_POST[ 'folder' ] )		? $_POST[ 'folder' ]					: '';
	$clipboard			= isset( $_POST[ 'clipboard' ] )	? $_POST[ 'clipboard' ]					: '';
	$social				= isset( $_POST[ 'social' ] )		? $_POST[ 'social' ]					: '';
	$lang_code			= defined( 'ICL_LANGUAGE_CODE' )	? ICL_LANGUAGE_CODE						: '';
	$lang				= get_locale();
	$sqlQuery			= "
		SELECT *
		FROM $table
		WHERE folder_name = %s
			AND id_user = $user_id
			AND id_site = $site_id
			AND lang = %s";

	if ( ! $site_id || ! $user_id || ! $folder ) {
		echo '-1';
		wp_die();
	}

	$results = $wpdb->get_results( $wpdb->prepare( $sqlQuery, $folder, $lang ), OBJECT );

	if ( ! $clipboard && ! $social ) {

		echo $results ? 'on' : 'off';

	}
	elseif ( $results ) {

		// prepare folder name for url share
		$folder = str_replace( ' ', '_', $folder );

		// build url
		$url = ( $lang_code ? '/' . ICL_LANGUAGE_CODE : '' ) . '/single-public-folder?folder=' . $folder . '&u=' . $user_id . '&si=' . $site_id;

		if ( $clipboard ) {

			// clipboard
			echo $url;

		}
		elseif ( $social ) {

			// social
			echo urlencode( $url );

		}
		else {

			echo '-1';

		}

	}
	else {

		echo '-1';

	}

	wp_die();

}
add_action( 'wp_ajax_check_public_folder', 'kulam_check_public_folder', 10, 1 );

/**
 * kulam_save_folder_settings
 *
 * This function updates folder settings
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_save_folder_settings() {

	/**
	 * Variables
	 */
	$user_id		= isset( $_POST[ 'user_id' ] )			? $_POST[ 'user_id' ]			: '';
	$folder			= isset( $_POST[ 'folder' ] )			? $_POST[ 'folder' ]			: '';
	$folder_new		= isset( $_POST[ 'folder_new' ] )		? $_POST[ 'folder_new' ]		: '';
	$folder_desc	= isset( $_POST[ 'folder_desc' ] )		? $_POST[ 'folder_desc' ]		: '';
	$delete_folder	= isset( $_POST[ 'delete_folder' ] )	? $_POST[ 'delete_folder' ]		: '';
	$public_folder	= isset( $_POST[ 'public_folder' ] )	? $_POST[ 'public_folder' ]		: '';
	$result			= [];

	if ( ! $user_id || ! $folder ) {
		echo '-1';
		wp_die();
	}

	// delete folder
	if ( $delete_folder == 'true' ) {
		$result[2] = kulam_delete_folder( $folder );
	}
	else {

		// update folder public status
		$result[3] = kulam_public_folder( $folder, $public_folder );

		$result[1] = array();

		// update folder name
		if ( $folder_new && $folder != $folder_new ) {
			$result[1][ 'name' ] = kulam_update_folder_name( $folder, $folder_new );
		}
		else {
			$result[1][ 'name' ] = $folder;
		}

		// update folder description
		$result[1][ 'description' ] = kulam_update_folder_description( $result[1][ 'name' ], $folder_desc );

	}

	echo json_encode( $result );

	wp_die();

}
add_action( 'wp_ajax_save_folder_settings', 'kulam_save_folder_settings', 10, 1 );

/**
 * kulam_delete_folder
 *
 * This function deletes a folder
 *
 * @param	$folder (string)
 * @return	(bool)
 */
function kulam_delete_folder( $folder ) {

	/**
	 * Variables
	 */
	global $wpdb;
	$table		= $wpdb->prefix . 'public_folders';
	$site_id	= get_current_blog_id();
	$user_id	= get_current_user_id();
	$sqlQuery	= "
		SELECT *
		FROM $table
		WHERE folder_name = %s
			AND id_user = $user_id
			AND id_site = $site_id";

	// delete folder from public_folders table
	$results = $wpdb->get_results( $wpdb->prepare( $sqlQuery, $folder ), OBJECT );

	if ( $results ) {

		foreach ( $results as $result ) {

			$wpdb->delete(
				$table,
				array( 'folder_id' => $result->folder_id )
			);

		}

	}

	// move deleted folder posts back to siddur
	$post_ids = get_user_meta( $user_id, $folder . $site_id, true );

	if ( $post_ids ) {

		$post_ids = json_decode( $post_ids, true );

		foreach ( $post_ids as $post_id ) {

			$data = get_user_meta( $user_id, 'sidur' . $site_id, true );
			kulam_siddur_push( $post_id, $data );
			update_user_meta( $user_id, 'sidur' . $site_id, $data );

		}
	}

	// delete folder
	delete_user_meta( $user_id, $folder . $site_id );
	$folders = get_user_meta( $user_id, 'nameFolder' . $site_id, true );

	if ( $folders ) {

		$folders = json_decode( $folders, true );

		if ( is_array( $folders ) ) {
			foreach ( $folders as $key => $folder_arr ) {
				if ( ( is_array( $folder_arr ) && $folder == $folder_arr[ 'name' ] ) || ( ! is_array( $folder_arr ) && $folder == $folder_arr ) ) {

					unset( $folders[ $key ] );
					break;

				}
			}
		}

		$folders = json_encode( $folders, JSON_UNESCAPED_UNICODE );

	}

	update_user_meta( $user_id, 'nameFolder' . $site_id , $folders );

	// return
	return true;

}

/**
 * kulam_public_folder
 *
 * This function updates a folder public status
 *
 * @param	$folder (string)
 * @param	$public_folder (bool)
 * @return	(bool)
 */
function kulam_public_folder( $folder, $public_folder ) {

	/**
	 * Variables
	 */
	global $wpdb;
	$table		= $wpdb->prefix . 'public_folders';
	$site_id	= get_current_blog_id();
	$user_id	= get_current_user_id();
	$lang		= get_locale();
	$sqlQuery	= "
		SELECT *
		FROM $table
		WHERE folder_name = %s
			AND id_user = $user_id
			AND id_site = $site_id
			AND lang = %s";

	$results = $wpdb->get_results( $wpdb->prepare( $sqlQuery, $folder, $lang ), OBJECT );

	if ( $public_folder == 'true' && ! $results ) {

		$wpdb->insert(
			$table,
			array(
				'folder_name'	=> $folder,
				'id_user'		=> $user_id,
				'id_site'		=> $site_id,
				'lang'			=> $lang,
			)
		);

	}

	elseif ( $public_folder == 'false' && $results ) {

		foreach ( $results as $result ) {

			$wpdb->delete(
				$table,
				array( 'folder_id' => $result->folder_id )
			);

		}

	}

	// return
	return true;

}

/**
 * kulam_update_folder_name
 *
 * This function updates a folder name
 *
 * @param	$folder (string)
 * @param	$folder_new (string)
 * @return	(string)
 */
function kulam_update_folder_name( $folder, $folder_new ) {

	/**
	 * Variables
	 */
	global $wpdb;
	$table		= $wpdb->prefix . 'public_folders';
	$site_id	= get_current_blog_id();
	$user_id	= get_current_user_id();
	$folder_new	= str_replace( array( "\'", '\"', '\\' ), '', $folder_new );
	$lang		= get_locale();
	$sqlQuery	= "
		SELECT *
		FROM $table
		WHERE folder_name = %s
			AND id_user = $user_id
			AND id_site = $site_id
			AND lang = %s";

	// update folder name in public_folders table
	$results = $wpdb->get_results( $wpdb->prepare( $sqlQuery, $folder, $lang ), OBJECT );

	if ( $results ) {
		$wpdb->update(
			$table,
			array( 'folder_name' => $folder_new ),
			array( 'id_user' => $user_id, 'folder_name' => $folder )
		);
	}

	// update user nameFolder array
	$folders = get_user_meta( $user_id, 'nameFolder' . $site_id, true );

	if ( $folders ) {

		$folders = json_decode( $folders, true );

		// update existing item
		if ( is_array( $folders ) ) {
			foreach ( $folders as $key => $folder_arr ) {
				if ( is_array( $folder_arr ) && $folder == $folder_arr[ 'name' ] ) {

					$folders[ $key ][ 'name' ] = $folder_new;
					break;

				}
			}
		}

		$folders = json_encode( $folders, JSON_UNESCAPED_UNICODE );

		update_user_meta( $user_id, 'nameFolder' . $site_id , $folders );

	}

	// update folder name and preserve content
	$post_ids = get_user_meta( $user_id, $folder . $site_id, true );
	delete_user_meta( $user_id, $folder . $site_id );
	add_user_meta( $user_id, $folder_new . $site_id, $post_ids, true );

	// return
	return $folder_new;

}

/**
 * kulam_update_folder_description
 *
 * This function updates a folder description
 *
 * @param	$folder (string)
 * @param	$folder_description (string)
 * @return	(string)
 */
function kulam_update_folder_description( $folder, $folder_description ) {

	/**
	 * Variables
	 */
	$site_id			= get_current_blog_id();
	$user_id			= get_current_user_id();
	$folder_description	= str_replace( array( "\n", "\'", '\"', '\\', '&#092;&#092;' ), array( '<br />', '&#039;', '&#034;', '&#092;', '&#092;' ), $folder_description );

	// update user nameFolder array
	$folders = get_user_meta( $user_id, 'nameFolder' . $site_id, true );

	if ( $folders ) {

		$folders = json_decode( $folders, true );

		// update existing item
		if ( is_array( $folders ) ) {
			foreach ( $folders as $key => $folder_arr ) {
				if ( is_array( $folder_arr ) ) {

					if ( $folder == $folder_arr[ 'name' ] ) {

						$folders[ $key ][ 'description' ] = $folder_description;
						break;

					}

				}
				else {

					// backward compatibility
					if ( $folder == $folder_arr ) {

						// unser folder row
						unset( $folders[ $key ] );

						// create folder row as an array
						$folders[] = array(
							'name'			=> $folder,
							'description'	=> $folder_description,
						);

					}

				}
			}
		}

		$folders = json_encode( $folders, JSON_UNESCAPED_UNICODE );

		update_user_meta( $user_id, 'nameFolder' . $site_id , $folders );

	}

	// return
	return $folder_description;

}

/**
 * kulam_add_folder
 *
 * This function adds a single post to current user siddur
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_add_folder() {

	/**
	 * Variables
	 */
	$folder_name		= isset( $_POST[ 'nameFolder' ] )	? $_POST[ 'nameFolder' ]	: '';
	$folder_description	= isset( $_POST[ 'folderDesc' ] )	? $_POST[ 'folderDesc' ]	: '';

	if ( ! $folder_name )
		wp_die();

	$folder_name		= str_replace( array( "\'", '\"', '\\' ), '', $folder_name );
	$folder_description	= str_replace( array( "\n", "\'", '\"', '\\', '&#092;&#092;' ), array( '<br />', '&#039;', '&#034;', '&#092;', '&#092;' ), $folder_description );
	$user				= wp_get_current_user();
	$site_id			= get_current_blog_id();
	$allFolders			= get_user_meta( $user->ID, 'nameFolder' . $site_id, true );
	$folder_arr			= array( 'name' => $folder_name, 'description' => $folder_description );

	if ( $allFolders ) {

		// meta exists - add to or update array
		$allFolders = json_decode( $allFolders, true );

		if ( ! in_array( $folder_name, array_column( $allFolders, 'name' ) ) ) {

			// add a new item
			$allFolders[] = $folder_arr;

		}
		else {

			// update existing item
			foreach ( $allFolders as $key => $folder ) {
				if ( $folder && $folder_name == $folder[ 'name' ] ) {

					$allFolders[ $key ][ 'description' ] = $folder_description;
					break;

				}
			}

		}

	}
	else {

		/// meta not exist - create array
		$allFolders =  array(
			0 => $folder_arr
		);

	}

	$allFolders = json_encode( $allFolders, JSON_UNESCAPED_UNICODE );

	update_user_meta( $user->ID, 'nameFolder' . $site_id, $allFolders );

	// success
	echo "Success";

	// die
	wp_die();

}
add_action( 'wp_ajax_add_folder', 'kulam_add_folder', 10, 1 );

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

	$data = json_encode( $data, JSON_UNESCAPED_UNICODE );

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

		$data = json_encode( $data, JSON_UNESCAPED_UNICODE );

	}

	// return
	return $changed;

}