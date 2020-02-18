<?php
/**
 * Google Tag Manager
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions
 * @version		1.5.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * gtag_head
 *
 * @param		N/A
 * @return		N/A
 */
function gtag_head() {

	if ( ! function_exists( 'get_field' ) )
		return;

	/**
	 * Variables
	 */
	$code = get_field( 'acf-option_google_tag_manager_code', 'option' );

	if ( ! $code )
		return;

	?>

	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','<?php echo $code; ?>');</script>
	<!-- End Google Tag Manager -->

	<?php

}
add_action( 'wp_head', 'gtag_head', 5 );

/**
 * gtag_body
 *
 * @param		N/A
 * @return		N/A
 */
function gtag_body() {

	if ( ! function_exists( 'get_field' ) )
		return;

	/**
	 * Variables
	 */
	$code = get_field( 'acf-option_google_tag_manager_code', 'option' );

	if ( ! $code )
		return;

	?>

	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $code; ?>"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

	<?php

}
add_action( 'wp_body_open', 'gtag_body', 5 );