<?php
/**
 * SVGs
 *
 * @author      Nir Goldberg
 * @package     scoop-child
 * @version     1.0.7
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Kulam_get_svg
 *
 * This function gets an SVG image
 *
 * @param	$svg_file (string) SVG file name
 * @return	N/A
 */
function Kulam_get_svg( $svg_file ) {

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