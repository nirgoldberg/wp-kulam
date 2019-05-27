<?php
/**
 * Menus functions
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions
 * @version		1.2.3
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_add_custom_menu_items
 *
 * This function adds My Siddur and Login/logout items to menu locations
 *
 * @param	$items (array)
 * @param	$args (array)
 * @return	(array)
 */
function kulam_add_custom_menu_items( $items, $args ) {

	if ( ! function_exists( 'get_field' ) )
		return $items;

	/**
	 * Variables
	 */
	$my_siddur_custom_label	= get_field( 'acf-option_my_siddur_custom_label', 'option' );
	$my_siddur_label		= $my_siddur_custom_label ? $my_siddur_custom_label : __( 'My Siddur', 'kulam-scoop' );
	$lang					= get_locale();
	$locations				= array( 'primary', 'primary_mobile', 'sticky_menu' );

	if ( in_array( $args->theme_location, $locations ) ) {

		if ( is_user_logged_in() ) {

			if ( 'he_IL' == $lang ) {

				$items .= '<li class="menu-item my-siddur"><a href="/my-siddur" role="link"><span>' . $my_siddur_label . '</span></a></li>';
				$items .= '<li class="menu-item logout"><a href="' . wp_logout_url(home_url()) . '"><span>התנתק</span></a></li>';

			}
			else {

				$items .= '<li class="menu-item my-siddur"><a href="/en/my-siddur" role="link"><span>' . $my_siddur_label . '</span></a></li>';
				$items .= '<li class="menu-item logout"><a href="' . wp_logout_url(home_url()) . '"><span>Logout</span></a></li>';

			}

		}
		else {

			$items .= '<li class="menu-item my-siddur"><a href="#" role="link" data-toggle="modal" data-target="#modal-login" data-redirect="/my-siddur" data-show-pre-text="true"><span>' . $my_siddur_label . '</span></a></li>';
			$items .= '<li class="menu-item logout"><a href="#" role="link" data-toggle="modal" data-target="#modal-login" data-redirect="#" data-show-pre-text="false"><span>' . __( 'Login', 'kulam-scoop' ) . '</span></a></li>';

		}

	}

	// return
	return $items;

}
add_filter( 'wp_nav_menu_items', 'kulam_add_custom_menu_items', 10, 2 );