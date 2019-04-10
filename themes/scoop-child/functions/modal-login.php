<?php
/**
 * Login Modal
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions
 * @version     1.1.1
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_modal_login
 *
 * This function includes a login modal within the footer
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_modal_login() {

	/**
	 * Variables
	 */
	$lang = get_locale();

	?>

	<div class="modal fade" id="modal-login" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">

				<div class="modal-header">
					<h5 class="modal-title"><?php _e( 'Login', 'kulam-scoop' ); ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="pre-text"><?php _e( 'In order to save posts or view saved content, you must register / log-in to your account', 'kulam-scoop' ); ?></div>

				<form>
					<div class="modal-body">
						<?php _e( 'User', 'kulam-scoop' ); ?> <input type="text" id="unamelog" name="unamelog" />
						<?php _e( 'Password', 'kulam-scoop' ); ?> <input type="password" id="upasslog" name="upasslog" />
						<?php echo ( $lang != 'he_IL' ) ? '<input hidden id="langlog" value="' . $lang . '" />' : ''; ?>
						<input type="hidden" id="redirectlog" name="redirectlog" />
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal" data-toggle="modal" data-target="#modal-registration" data-redirect=""><?php _e( 'Register Now', 'kulam-scoop' ); ?></button>
						<input type="submit" class="btn btn-primary submit_log" value="<?php _e( 'Login', 'kulam-scoop' ); ?>" />
					</div>
				</form>

			</div>
		</div>

	</div><!-- #modal-login -->

	<?php

}
add_action( 'wp_footer', 'kulam_modal_login' );