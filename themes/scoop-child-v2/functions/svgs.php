<?php
/**
 * SVGs
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions
 * @version     1.1.1
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_get_svg
 *
 * This function gets an SVG image
 *
 * @param	$svg_file (string) SVG file name
 * @return	N/A
 */
function kulam_get_svg( $svg_file ) {

	/**
	 * Variables
	 */
	$svg = '';

	ob_start();
	get_template_part( 'partials/svgs/svg', $svg_file );
	$svg = ob_get_clean();

	// return
	return $svg;

}