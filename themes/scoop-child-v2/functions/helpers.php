<?php
/**
 * Helper functions
 *
 * @author		Nir Goldberg
 * @package		functions
 * @version		2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_trim_str
 *
 * This function trims special characters from a given string
 *
 * @param	$str (string)
 * @return	(string)
 */
function kulam_trim_str( $str ) {

	// return
	return str_replace( array("\r\n", "\n", "\r"), array("\\r\\n", "\\n", "\\r"), $str );

}

/**
 * kulam_px_to_vw
 *
 * This function converts px to vw
 * Assumes px size in 1920 viewport width
 *
 * @param	$font_size (int)
 * @return	(float)
 */
function kulam_px_to_vw ( $font_size, $viewport = 1920 ) {

	if ( ! $font_size )
		return;

	// return
	return $font_size * 100 / $viewport;

}