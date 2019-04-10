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
 * @param   N/A
 * @return  N/A
 */
function kulam_remove_admin_bar() {

	if ( ! current_user_can( 'administrator' ) && ! is_admin() ) {

		show_admin_bar(false);

	}

}
add_action( 'after_setup_theme', 'kulam_remove_admin_bar' );