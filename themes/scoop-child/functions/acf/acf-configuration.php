<?php
/**
 * ACF configuration
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions/acf
 * @version		1.2.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_acf_init
 *
 * This function initializes ACF configuration
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_acf_init() {

	if ( function_exists( 'acf_add_options_page' ) ) {

		acf_add_options_page( array(
			'page_title'	=> __( 'Site Setup', 'kulam-scoop' ),
			'menu_title'	=> __( 'Site Setup', 'kulam-scoop' ),
			'menu_slug'		=> 'site-options',
			'icon_url'		=> 'dashicons-admin-tools'
		));

		acf_add_options_sub_page( array(
			'page_title' 	=> __( 'My Siddur Settings', 'kulam-scoop' ),
			'menu_title' 	=> __( 'My Siddur', 'kulam-scoop' ),
			'menu_slug' 	=> 'acf-options-my-siddur',
			'parent_slug' 	=> 'site-options'
		));

		acf_add_options_sub_page( array(
			'page_title' 	=> __( 'General Settings', 'kulam-scoop' ),
			'menu_title' 	=> __( 'General', 'kulam-scoop' ),
			'menu_slug' 	=> 'acf-options-general',
			'parent_slug' 	=> 'site-options'
		));

	}

}
add_action( 'acf/init', 'kulam_acf_init' );