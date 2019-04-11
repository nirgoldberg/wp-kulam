<?php
/**
 * Theme functions
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions
 * @version     1.1.1
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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