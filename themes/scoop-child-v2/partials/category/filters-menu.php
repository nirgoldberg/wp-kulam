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
$filter_width		= count( $category_filters ) >= 7 ? 100/7 : 100/count( $category_filters );
$categories_label	= get_field( 'acf-category_categories_filter_label', 'category_' . $category->term_id );
$filter_items		= array();

// build $filter_items
foreach ( $category_filters as $filter ) {

	$filter_label = $filter->label;

	// category
	if ( 'category' == $filter->name ) {

		// verify sub categories exist
		if ( ! get_terms( array(
				'taxonomy'		=> 'category',
				'parent'		=> $category->term_id,
				'hide_empty'	=> 0,
			) ) )
			continue;

		// modify filter name
		$filter_label = $categories_label ? $categories_label : $filter->label;

	}

	$filter_items[] = 	'<li data-tax="' . $filter->name . '" data-count="0" style="width:' . $filter_width . '%;">' .
							'<a href="#" role="link" data-toggle="modal" data-target="#modal-category-filter">' .
								$filter_label .
							'</a>' .
							'<span class="count"></span>' .
						'</li>';

}

if ( ! $filter_items )
	return;

?>

<div class="category-filters">

	<div class="filters-menu">

		<div class="filters-menu-toggle visible-xs"><?php _e( 'Filter', 'kulam-scoop' ); ?></div>

		<ul class="filters-selections">

			<?php echo implode( '', $filter_items ); ?>

		</ul>

	</div>

	<div class="reset-filters"><?php _e( 'Reset filter', 'kulam-scoop' ); ?></div>

	<ul class="checked-filters"></ul>

</div>