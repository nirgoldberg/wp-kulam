<?php
/**
 * Admin header functions
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions/admin
 * @version     1.2.4
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_admin_head
 *
 * This function loads scripts and styles on the dashboard
 *
 * @param   N/A
 * @return  N/A
 */
function kulam_admin_head() {

	wp_enqueue_style( 'admin-style' );

	if ( is_rtl() ) {
		wp_enqueue_style( 'admin-style-rtl' );
	}

}
add_action( 'admin_head', 'kulam_admin_head' );