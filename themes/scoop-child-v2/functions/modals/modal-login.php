<?php
/**
 * Login Modal
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions/modals
 * @version     2.1.3
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

	// vars
	$login_registration_pages = get_field( 'acf-oprion_login_registration_pages', 'option' );

	?>

	<div class="modal fade" id="modal-login" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">

				<div class="modal-header">
					<h5 class="modal-title"><?php _e( 'Login', 'kulam-scoop' ); ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
				</div>

				<div class="pre-text"><?php _e( 'In order to save / upload posts or view saved content, you must register / log-in to your account', 'kulam-scoop' ); ?></div>

				<div class="modal-footer">
					<button>
						<a href="<?php echo $login_registration_pages[ 'login_page' ]; ?>">
							<?php _e( 'Login', 'kulam-scoop' ); ?>
						</a>
					</button>
				</div>

			</div>
		</div>

	</div><!-- #modal-login -->

	<?php

}
add_action( 'wp_footer', 'kulam_modal_login' );