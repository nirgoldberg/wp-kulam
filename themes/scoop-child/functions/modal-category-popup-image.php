<?php
/**
 * Login Modal
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions
 * @version     1.7.12
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_modal_category_popup_image
 *
 * This function includes a category popup image modal within the footer
 *
 * @param	N/A
 * @return	N/A
 */
function kulam_modal_category_popup_image() {

	/**
	 * Variables
	 */
	$category		= get_queried_object();
	$popup_image	= get_field( 'acf-category_popup_image', 'category_' . $category->term_id );

	if ( ! $popup_image )
		return;

	?>

	<div class="modal fade" id="modal-category-popup-image" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">

				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<img src="<?php echo $popup_image[ 'url' ]; ?>" alt="<?php echo $popup_image[ 'alt' ]; ?>" />
				</div>

			</div>
		</div>

	</div><!-- #modal-category-popup-image -->

	<?php

}