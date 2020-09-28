<?php
/**
 * Registration banner
 *
 * @author		Nir Goldberg
 * @package		scoop-child/partials
 * @version		2.1.3
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( is_user_logged_in() || ! function_exists( 'get_field' ) )
	return;

// vars
$pages											= get_field( 'acf-oprion_login_registration_pages', 'option' );
$banner											= get_field( 'acf-option_login_registration_banner', 'option' );
$buttons										= get_field( 'acf-option_login_registration_buttons', 'option' );
$login_registration_website_registration_form	= get_field( 'acf-option_login_registration_website_registration_form', 'option' );
$is_register_allowed							= ( ! isset( $login_registration_website_registration_form[ 'status' ] ) || true === $login_registration_website_registration_form[ 'status' ] );
$banner_style									= '';

if ( ! $pages || ! $banner || ! $buttons )
	return;

if ( $banner[ 'desktop_background' ] || $banner[ 'mobile_background' ] ) { ?>

	<style>

		<?php if ( $banner[ 'desktop_background' ] ) { ?>
			@media (min-width: 768px) {
				.registration-banner-wrap .registration-banner { background-image: url('<?php echo $banner[ 'desktop_background' ][ 'url' ]; ?>'); }
			}
		<?php } ?>

		<?php if ( $banner[ 'mobile_background' ] ) { ?>
			@media (max-width: 767px) {
				.registration-banner-wrap .registration-banner { background-image: url('<?php echo $banner[ 'mobile_background' ][ 'url' ]; ?>'); }
			}
		<?php } ?>

	</style>

<?php }

?>

<div class="registration-banner-wrap">
	<div class="registration-banner">
		<div class="container">

			<div class="text-wrap row">

				<div class="title col-sm-4 col-md-push-1 col-md-3"><?php echo $banner[ 'title' ]; ?></div>
				<div class="text col-md-push-1 col-sm-8"><?php echo $banner[ 'text' ]; ?></div>

			</div>

			<div class="buttons-wrap row <?php echo ! $is_register_allowed ? 'register-not-allowed' : ''; ?>">

				<div class="col-sm-<?php echo $is_register_allowed ? '6' : '12'; ?>">
					<button>
						<a href="<?php echo $pages[ 'hmembership_register_page' ]; ?>">
							<?php echo $buttons[ 'member_registration' ]; ?>
						</a>
					</button>
				</div>

				<?php if ( $is_register_allowed ) { ?>

					<div class="col-sm-6">
						<button>
							<a href="<?php echo $pages[ 'register_page' ]; ?>">
								<?php echo $buttons[ 'normal_registration' ]; ?>
							</a>
						</button>
					</div>

				<?php } ?>

			</div>

		</div>
	</div>
</div><!-- .registration-banner -->