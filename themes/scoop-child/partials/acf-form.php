<?php
/**
 * ACF form
 *
 * @author      Nir Goldberg
 * @package     scoop-child/partials
 * @version     1.6.2
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_field' ) )
	return;

if ( get_field( 'display_acf_form' ) ) {

	if ( is_user_logged_in() ) {

		/**
		 * Variables
		 */
		$form = get_field( 'acf_form' );

		if ( ! $form )
			return;

		$args = kol_get_acf_form_args( $form ); ?>

		<div class="acf-form acf-form--"<?php echo $form; ?>>

			<?php
				/**
				 * ACF form
				 */
				acf_form( $args );
			?>

		</div>

	<?php } else { ?>

		<button data-toggle="modal" data-target="#modal-login" data-redirect="/share" data-show-pre-text="false"><?php _e( 'Login', 'kulam-scoop' ); ?></button>

	<?php }

}