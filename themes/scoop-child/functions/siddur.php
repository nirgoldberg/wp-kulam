<?php
/**
 * Siddur functions
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions
 * @version		1.3.5
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
	$default_lang_code	= $lang_code						? $sitepress->get_default_language()	: '';
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
		$url = ( $lang_code && $lang_code != $default_lang_code ? '/' . ICL_LANGUAGE_CODE : '' ) . '/single-public-folder?folder=' . $folder . '&u=' . $user_id . '&si=' . $site_id;

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

		// update folder name
		if ( $folder_new && $folder != $folder_new ) {
			$result[1] = kulam_update_folder_name( $folder, $folder_new );
		}

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
	if ( kulam_siddur_pull( $folder, $folders ) )
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
	$folder_new	= preg_replace( '/[^\\w- ]+/u', '', $folder_new );
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
	kulam_siddur_pull( $folder, $folders );
	kulam_siddur_push( $folder_new, $folders );
	update_user_meta( $user_id, 'nameFolder' . $site_id , $folders );

	// update folder name and preserve content
	$post_ids = get_user_meta( $user_id, $folder . $site_id, true );
	delete_user_meta( $user_id, $folder . $site_id );
	add_user_meta( $user_id, $folder_new . $site_id, $post_ids, true );

	// return
	return $folder_new;

}




/********************************************************************/



add_action("wp_ajax_add-folder","addFolder",10,1);
function addFolder()
{
	if(isset($_POST['nameFolder']))
	   $name_folder = preg_replace( '/[^\\w- ]+/u', '', $_POST[ 'nameFolder' ] );
  $user=wp_get_current_user();
  $site=get_current_blog_id();

  $allFolders=get_user_meta($user->ID,"nameFolder".$site,true);
  if($allFolders){ 
	  $allFolders=json_decode($allFolders,true);
	  if(!in_array($name_folder,$allFolders))
	{
		$allFolders[]=$name_folder;
	}
	 
   } 
   else{
	   $allFolders =  array(
		0 => $name_folder
	); 
}
	$allFolders=json_encode($allFolders,JSON_UNESCAPED_UNICODE);
	update_user_meta($user->ID,"nameFolder".$site,$allFolders);
	echo "Success";
 }


/********************************************************************/





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