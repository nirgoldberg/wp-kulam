<?php
/**
 * ACF form
 *
 * @author      Nir Goldberg
 * @package     scoop-child/partials
 * @version     1.7.22
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_field' ) )
	return;

if ( get_field( 'display_acf_form' ) ) {

	if ( is_user_logged_in() ) {

		/**
		 * the_content
		 */
		the_content();

		/**
		 * Variables
		 */
		$form = get_field( 'acf_form' );

		if ( ! $form )
			return;

		$args = kol_get_acf_form_args( $form ); ?>

		<div class="acf-form acf-form-<?php echo $form; ?>">

			<?php
				/**
				 * ACF form
				 */
				acf_form( $args );
			?>

		</div>

	<?php } else { ?>

		<p><?php _e( 'In order to save / upload posts or view saved content, you must register / log-in to your account', 'kulam-scoop' ); ?></p>
		<button data-toggle="modal" data-target="#modal-login" data-redirect="/share" data-show-pre-text="true"><?php _e( 'Login', 'kulam-scoop' ); ?></button>

	<?php }

}

?>
<script type="text/javascript">
	jQuery(document).ready(function($){
		wp.media.controller.Library.prototype.defaults.contentUserSetting=false;
	});
</script>