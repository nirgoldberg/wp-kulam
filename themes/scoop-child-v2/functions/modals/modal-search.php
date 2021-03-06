<?php
/**
 * Search Modal
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions/modals
 * @version     2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_modal_search
 *
 * This function includes a search modal within the footer
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_modal_search() {

	?>

	<div class="modal left fade" id="modal-search" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="modal-dialog modal-dialog-fullscreen" role="document">
			<div class="modal-content">

				<div class="modal-body">
					<div class="search-header">

						<?php
							/**
							 * Search
							 */
							get_template_part( 'partials/search' );
						?>

					</div>
				</div>

			</div>
		</div>

	</div><!-- #modal-search -->

	<?php

}
add_action( 'wp_footer', 'kulam_modal_search' );