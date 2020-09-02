<?php
/**
 * Category filters menu
 *
 * @author		Nir Goldberg
 * @package		scoop-child/partials/category
 * @version		2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! $category_filters || ! function_exists( 'get_field' ) )
	return;

// vars
global $globals;
$filter_width		= count( $category_filters ) >= 7 ? 100/7 : 100/count( $category_filters );
$categories_label	= get_field( 'acf-category_categories_filter_label', 'category_' . $category->term_id );

?>

<div class="category-filters">

	<div class="filters-menu">

		<div class="filters-menu-toggle visible-xs"><?php _e( 'Filter', 'kulam-scoop' ); ?></div>

		<ul class="filters-selections">

			<?php foreach ( $category_filters as $filter ) {

				$filter_label = 'category' == $filter->name && $categories_label ? $categories_label : $filter->label;

				echo	'<li data-tax="' . $filter->name . '" data-count="0" style="width:' . $filter_width . '%;">' .
							'<a href="#" role="link" data-toggle="modal" data-target="#modal-category-filter">' .
								$filter_label .
							'</a>' .
							'<span class="count"></span>' .
						'</li>';

			} ?>

		</ul>

	</div>

	<div class="reset-filters"><?php _e( 'Reset filter', 'kulam-scoop' ); ?></div>

	<ul class="checked-filters"></ul>

</div>