<?php
/**
 * Category filters
 *
 * @author      Nir Goldberg
 * @package     scoop-child/partials/category
 * @version     2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! $category_filters )
	return;

$filter_width = count( $category_filters ) >= 7 ? 100/7 : 100/count( $category_filters );

?>

<div class="category-filters">

	<ul class="filters-selections">

		<?php foreach ( $category_filters as $filter ) {

			echo	'<li class="tax_' . $filter->name . '" style="width:' . $filter_width . '%;">' .
						$filter->label .
						'<span class="count"></span>' .
					'</li>';

		} ?>

	</ul>

</div>