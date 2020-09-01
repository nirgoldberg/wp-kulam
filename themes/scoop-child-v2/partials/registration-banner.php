<?php
/**
 * Registration banner
 *
 * @author		Nir Goldberg
 * @package		scoop-child/partials
 * @version		2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( is_user_logged_in() || ! function_exists( 'get_field' ) )
	return;

// vars
$pages		= get_field( 'acf-oprion_login_registration_pages', 'option' );
$banner		= get_field( 'acf-option_login_registration_banner', 'option' );
$buttons	= get_field( 'acf-option_login_registration_buttons', 'option' );

if ( ! $pages || ! $banner || ! $buttons )
	return;

?>

<div class="registration-banner-wrap">
	<div class="registration-banner">
		<div class="container">

			<div class="text-wrap row">

				<div class="title col-sm-4 col-md-push-1 col-md-3"><?php echo $banner[ 'title' ]; ?></div>
				<div class="text col-md-push-1 col-sm-8"><?php echo $banner[ 'text' ]; ?></div>

			</div>

			<div class="buttons-wrap row">

				<div class="col-sm-6">
					<button class="button">
						<a href="<?php echo $pages[ 'hmembership_register_page' ]; ?>">
							<?php echo $buttons[ 'member_registration' ]; ?>
						</a>
					</button>
				</div>
				<div class="col-sm-6">
					<button class="button">
						<a href="<?php echo $pages[ 'register_page' ]; ?>">
							<?php echo $buttons[ 'normal_registration' ]; ?>
						</a>
					</button>
				</div>

			</div>

		</div>
	</div>
</div><!-- .registration-banner -->