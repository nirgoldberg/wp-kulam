<?php
/**
 * Menu Modal
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions
 * @version		2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_modal_menu
 *
 * This function includes a menu modal within the footer
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_modal_menu() {

	?>

	<div class="modal fade" id="modal-menu" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="modal-dialog modal-dialog-fullscreen" role="document">
			<div class="modal-content">

				<div class="modal-body">

					<nav class="nav-main" role="navigation">
						<div class="nav-main-inner">
							<?php if ( has_nav_menu( 'primary' ) ) :

								wp_nav_menu( array(
									'theme_location'	=> 'primary',
									'container'			=> false,
									'menu_class'		=> 'sf-menu',
									'walker' 			=> new Pojo_Navbar_Nav_Walker()
								) );

							endif; ?>
						</div>
					</nav><!--/#nav-menu -->

				</div>

			</div>
		</div>

	</div><!-- #modal-menu -->

	<?php

}
add_action( 'wp_footer', 'kulam_modal_menu' );