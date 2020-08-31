<?php
/**
 * Category filters menu
 *
 * @author		Nir Goldberg
 * @package		scoop-child/partials/category
 * @version		2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! $category_filters )
	return;

// vars
global $globals;
$filter_width = count( $category_filters ) >= 7 ? 100/7 : 100/count( $category_filters );

?>

<div class="category-filters">

	<div class="filters-menu">

		<div class="filters-menu-toggle visible-xs"><?php _e( 'Filter', 'kulam-scoop' ); ?></div>

		<ul class="filters-selections">

			<?php foreach ( $category_filters as $filter ) {

				echo	'<li data-tax="' . $filter->name . '" data-count="0" style="width:' . $filter_width . '%;">' .
							'<a href="#" role="link" data-toggle="modal" data-target="#modal-category-filter">' .
								$filter->label .
							'</a>' .
							'<span class="count"></span>' .
						'</li>';

			} ?>

		</ul>

	</div>

	<div class="reset-filters"><?php _e( 'Reset filter', 'kulam-scoop' ); ?></div>

	<ul class="checked-filters"></ul>

</div>