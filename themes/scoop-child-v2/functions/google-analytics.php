<?php
/**
 * Google Analytics
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions
 * @version		1.5.0
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
	$code = get_field( 'acf-option_google_analytics_code', 'option' );

	// backward compatibility - to be removed after all sites have defined an analytics code via ACF
	if ( ! $code ) {

		$current_site = $_SERVER['HTTP_HOST'];

		switch ( $current_site ) {

			case 'kulam.org': $code = 'UA-130681933-1'; 
			break;
			case 'iac.kulam.org': $code = 'UA-132186797-1'; 
			break;
			case 'ki.kulam.org': $code = 'UA-132184397-1'; 
			break;
			case 'matnasim.kulam.org': $code = 'UA-132224897-1'; 
			break;
			case 'hashomer.kulam.org': $code = 'UA-131938138-1';
			break;
			case 'lachish.kulam.org': $code = 'UA-132184208-1';
			break;
			case 'einprat.kulam.org': $code = 'UA-131902577-1';
			break;
			case 'sfhillel.kulam.org': $code = 'UA-132190351-1';
			break;
			case 'shabbat.kulam.org': $code = 'UA-132195815-1';
			break;
			case 'onward.kulam.org': $code = 'UA-132172377-1';
			break;
			case 'jcogs.kulam.org': $code = 'UA-132215886-1';
			break;
			case 'masaisraeli.kulam.org': $code = 'UA-131909123-1';
			break;
			case 'kol-ami.kulam.org': $code = 'UA-132189842-1';
			break;
			case 'nachshon.kulam.org': $code = 'UA-132187822-1';
			break;
			case 'masaisrael.kulam.org': $code = 'UA-132186217-1';
			break;
			case 'ramah.kulam.org': $code = 'UA-132213676-1';
			break;
			case 'honeymoonisrael.kulam.org': $code = 'UA-132211552-1';
			break;
			case 'masa.kulam.org': $code = 'UA-131941060-1';
			break;

		}

	}

	if ( ! $code )
		return;

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