<?php
/**
 * Category filter Modal
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions/modals
 * @version		2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_modal_category_filter
 *
 * This function includes a category filter modal within the footer
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_modal_category_filter() {

	// vars
	$category = get_queried_object();

	?>

	<div class="modal left fade" id="modal-category-filter" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="modal-dialog modal-dialog-fullscreen" role="document">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
					<div class="reset-filters"><?php _e( 'Reset filter', 'kulam-scoop' ); ?></div>
					<div class="filter-title"></div>
				</div>

				<div class="modal-body">

					<?php
						/**
						 * Category filters
						 */
						echo kulam_get_category_filter_term_fields( $category );
					?>

					<div class="no-terms"><?php _e( 'No terms found', 'kulam-scoop' ); ?></div>

					<div class="apply-filters"><span><?php _e( 'Apply Filters', 'kulam-scoop' ); ?></span></div>

				</div>

			</div>
		</div>

	</div><!-- #modal-category-filter -->

	<?php

}

if ( is_category() ) {
	add_action( 'wp_footer', 'kulam_modal_category_filter' );
}