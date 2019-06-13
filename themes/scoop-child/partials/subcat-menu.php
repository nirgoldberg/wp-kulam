<?php
/**
 * Sub categories menu
 *
 * @author		Nir Goldberg
 * @package		scoop-child
 * @version		1.2.6
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
	array(
		'taxonomy'		=> 'category',
		'parent'		=> get_queried_object_id(),
		'hide_empty'	=> false,
		'orderby'		=> 'term_order'
	)
);

?>

<nav class="subcat-menu" style="float:none">
	<div class="row">

		<?php if ( $children ) {

			foreach ( $children as $child ) {

				$child_obj				= get_category( $child );
				$category_link			= get_category_link( $child_obj->term_id );
				$category_title			= $child_obj->name;
				$category_description	= category_description( $child_obj->term_id );
				$category_bg			= get_field( 'acf-category_background_image', 'category_' . $child_obj->term_id );
				$category_bg_markup		= 'style="' . ( $category_bg ? 'background-image: url(\'' . $category_bg[ 'url' ] . '\');' : 'background: ' . $bg_color . ';' ) . '"';
				$cat_desc_content		= '';
				$cat_desc_btn			= '';

				if ( isset( $_GET['pt'] ) ) {

					$link .= '?pt=' . $_GET['pt'] . ( ( isset( $_GET['hide_as'] ) ) ? '&hide_as=' . $_GET['hide_as'] : '' );

				}

				$classes = 'tile-box-wrapper-child-cat col-md-3' . ( $category_description ? ' cat-desc' : '' );

				if ( $category_description ) {

					$cat_desc_content	= '<div class="desc hidden-xs"><div class="desc-content">' . $category_description . '</div></div>';
					$cat_desc_btn		=
						'<div class="more hidden-xs">' .

							kulam_get_svg( 'info' ) .

						'</div>';

				}

				?>

				<div class="tile-box-wrapper-child-cat col-md-3">
					<a href="<?php echo $category_link; ?>" class="tile-box-link" role="button" <?php echo $category_bg_markup; ?>>
						<div class="tile-box">
							<div class="tile-box-content">
								<h2 style="color: <?php echo $font_color; ?>;"><?php echo $category_title; ?></h2>
								<?php echo $cat_desc_content; ?>
							</div>

							<?php echo $cat_desc_btn; ?>
						</div>
					</a>
				</div>

			<?php }

		} ?>

	</div>
</nav><!-- .subcat-menu -->