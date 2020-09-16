<?php
/**
 * Google Analytics
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions
 * @version		2.0.6
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * google_analytics_head
 *
 * @param		N/A
 * @return		N/A
 */
function google_analytics_head() {

	if ( ! function_exists( 'get_field' ) )
		return;

	/**
	 * Variables
	 */
	$code								= get_field( 'acf-option_google_analytics_code', 'option' );
	$gdpr_allowed_cookies				= isset( $_COOKIE[ 'viewed_cookie_policy' ] ) ? $_COOKIE[ 'viewed_cookie_policy' ] : '';
	$gdpr_allowed_non_necessary_cookies	= isset( $_COOKIE[ 'cookielawinfo-checkbox-non-necessary' ] ) ? $_COOKIE[ 'cookielawinfo-checkbox-non-necessary' ] : 'yes';

	// are cookies allowed
	if (	! $code ||
			'no' == $gdpr_allowed_cookies ||
			! $gdpr_allowed_non_necessary_cookies ||
			'yes' == $gdpr_allowed_cookies && 'no' == $gdpr_allowed_non_necessary_cookies ) {

		// vars
		$cookies	= array( '_ga', '_gid', '_gat_gtag_' . str_replace( '-', '_', $code ) );
		$host		= $_SERVER[ 'HTTP_HOST' ];

		// get domain name
		preg_match( "/[^\.\/]+\.[^\.\/]+$/", $host, $matches );

		// remove Google Analytics cookies
		foreach ( $cookies as $cookie ) {
			setcookie( $cookie, null, time() - DAY_IN_SECONDS, COOKIEPATH, '.' . $matches[0] );
		}

		// return
		return;

	}

	?>

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $code; ?>"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', '<?php echo $code; ?>');
	</script>

	<?php

}
add_action( 'wp_head', 'google_analytics_head', 5 );