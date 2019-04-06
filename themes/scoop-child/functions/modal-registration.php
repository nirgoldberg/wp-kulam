<?php
/**
 * Registration Modal
 *
 * @author      Nir Goldberg
 * @package     scoop-child
 * @version     1.0.5
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Kulam_modal_registration
 *
 * This function includes a registration modal within the footer
 *
 * @param	N/A
 * @return	N/A
 */
function Kulam_modal_registration() {

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
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<form>
					<div class="modal-body">
						<?php _e( 'User', 'kulam-scoop' ); ?> <input type="text" id="uname" name="uname" />
						<?php _e( 'Email', 'kulam-scoop' ); ?> <input id="uemail" type="text" name="uemail" />
						<?php _e( 'Password', 'kulam-scoop' ); ?> <input type="password" id="upass" name="upass" />
						<img src="/wp-content/plugins/really-simple-captcha/tmp/<?php echo $captcha_instance->generate_image( $prefix, $word ); ?>" />
						<br />
						<?php _e( 'Please enter the following text in the box below:', 'kulam-scoop' ); ?> <input id="captcha" type="text" name="captcha" />
						<input hidden id="prefix" name="prefix" value="<?php echo $prefix ?>" />
						<?php echo ( $lang != 'he_IL' ) ? '<input hidden id="lang" value="' . $lang . '" />' : ''; ?>
						<input type="hidden" id="redirect" name="redirect" />
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal" data-toggle="modal" data-target="#modal-login" data-redirect=""><?php _e( 'Login', 'kulam-scoop' ); ?></button>
						<input type="submit" class="btn btn-primary submit_reg" value="<?php _e( 'Register', 'kulam-scoop' ); ?>" />
					</div>
				</form>

			</div>
		</div>

	</div><!-- .modal.registration -->

	<?php

}
add_action( 'wp_footer', 'Kulam_modal_registration' );