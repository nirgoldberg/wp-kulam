<?php
/**
 * Users functions
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions
 * @version     1.6.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_attachments_posts_where
 *
 * This function hides media library attachments of other users (Subscriber role)
 *
 * @param	$where (string)
 * @return	(string)
 */
function kulam_attachments_posts_where( $where ){

	if ( is_user_logged_in() ) {

		$user = wp_get_current_user();
		$roles = ( array ) $user->roles;

		// library query
		if ( isset( $_POST[ 'action' ] ) && 'query-attachments' == $_POST[ 'action' ] && in_array( 'subscriber', $roles ) ) {

			$where .= ' AND post_author='.$user->data->ID;

		}

	}

	// return
	return $where;

}
add_filter( 'posts_where', 'kulam_attachments_posts_where' );