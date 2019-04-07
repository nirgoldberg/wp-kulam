<?php
/**
 * Sub categories menu
 *
 * @author      Nir Goldberg
 * @package     scoop-child
 * @version     1.1
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_field' ) )
	return;

/**
 * Variables
 */
$bg_color	= get_field( 'acf-option_subcategory_background_color', 'option' );
$font_color	= get_field( 'acf-option_subcategory_font_color', 'option' );

$bg_color	= $bg_color ? $bg_color : '#8F95CE';
$font_color	= $font_color ? $font_color : '#FFFFFF';

$children = get_terms(
	'category',
	array(
		'parent' => get_queried_object_id(),
		'hide_empty' => false,
		'include' => 'all'
	)
);

?>

<nav class="subcat-menu" style="float:none">
	<div class="row">

		<?php foreach ( $children as $child ) {

			$child_obj = get_category($child);
			$link = get_category_link($child_obj->term_id);

			if ( isset( $_GET['pt'] ) ) {

				$link .= '?pt=' . $_GET['pt'];

			}

			$category_description = category_description( $child_obj->term_id );

			$classes = 'tile-box-wrapper-child-cat col-md-3' . ( $category_description ? ' cat-desc' : '' );

			$cat_desc_content	= '';
			$cat_desc_btn		= '';

			if ( $category_description ) {

				$cat_desc_content	= '<div class="desc hidden-xs"><div class="desc-content">' . $category_description . '</div></div>';
				$cat_desc_btn		=
					'<div class="more hidden-xs">' .

						Kulam_get_svg( 'info' ) .

					'</div>';

			}

			?>

			<div class="tile-box-wrapper-child-cat col-md-3">
				<a href="<?php echo $link; ?>" class="tile-box-link" role="button" style="background: <?php echo $bg_color; ?>">
					<div class="tile-box">
						<div class="tile-box-content">
							<h2 style="color: <?php echo $font_color; ?>;"><?php echo $child_obj->name; ?></h2>
							<?php echo $cat_desc_content; ?>
						</div>

						<?php echo $cat_desc_btn; ?>
					</div>
				</a>
			</div>

		<?php } ?>

	</div>
</nav> 