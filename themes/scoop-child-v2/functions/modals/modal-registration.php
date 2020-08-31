<?php
/**
 * Registration Modal
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions/modals
 * @version     2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_modal_registration
 *
 * This function includes a registration modal within the footer
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_modal_registration() {

	/**
	 * Variables
	 */
	$captcha_instance	= new ReallySimpleCaptcha();
	$word				= $captcha_instance->generate_random_word();
	$prefix				= mt_rand();

	?>

	<div class="modal fade" id="modal-registration" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">

				<div class="modal-header">
					<h5 class="modal-title"><?php _e( 'Registration', 'kulam-scoop' ); ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
				</div>

				<form>
					<div class="modal-body">
						<label for="uname"><h6><?php _e( 'User', 'kulam-scoop' ); ?></h6></label> <input type="text" id="uname" name="uname" />
						<label for="uemail"><h6><?php _e( 'Email', 'kulam-scoop' ); ?></h6></label> <input id="uemail" type="text" name="uemail" />
						<label for="upass"><h6><?php _e( 'Password', 'kulam-scoop' ); ?></h6></label> <input type="password" id="upass" name="upass" />
						<img src="/wp-content/plugins/really-simple-captcha/tmp/<?php echo $captcha_instance->generate_image( $prefix, $word ); ?>" />
						<br />
						<label for="captcha"><h6><?php _e( 'Please enter the following text in the box below:', 'kulam-scoop' ); ?></h6></label> <input id="captcha" type="text" name="captcha" />
						<input hidden id="prefix" name="prefix" value="<?php echo $prefix ?>" />
						<?php echo ( $lang != 'he_IL' ) ? '<input hidden id="lang" value="' . $lang . '" />' : ''; ?>
						<input type="hidden" id="redirect" name="redirect" />
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal" data-toggle="modal" data-target="#modal-login" data-redirect="" data-show-pre-text=""><?php _e( 'Login', 'kulam-scoop' ); ?></button>
						<input type="submit" class="btn btn-primary submit_reg" value="<?php _e( 'Register', 'kulam-scoop' ); ?>" />
					</div>
				</form>

			</div>
		</div>

	</div><!-- #modal-registration -->

	<?php

}
add_action( 'wp_footer', 'kulam_modal_registration' );