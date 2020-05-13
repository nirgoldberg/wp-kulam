<?php
/**
 * Utils functions
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions
 * @version     1.7.10
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

};