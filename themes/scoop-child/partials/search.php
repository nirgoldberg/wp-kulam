<?php
/**
 * Advanced Search
 *
 * @author      Nir Goldberg
 * @package     scoop-child
 * @version     1.7.32
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_field' ) )
	return;

/**
 * Variables
 */
$search_input_placeholder	= get_field( 'acf-option_search_input_placeholder', 'option' );
$advanced_search			= get_field( 'acf-option_advanced_search', 'option' );

$search_input_placeholder	= $search_input_placeholder ?: __( 'Search...', 'kulam-scoop' );
$advanced_search			= $advanced_search !== false ? true : false;

?>

<form role="search" action="<?php echo home_url( '/' ); ?>" method="get">

	<input type="hidden" name="post_type" value="post">

	<div class="menu-search-input-text">

		<span class="menu-search-input">
			<input type="text" name="s" placeholder="<?php echo $search_input_placeholder; ?>" data-alternate-placeholder="<?php _e( 'Free text...', 'kulam-scoop' ); ?>" value="<?php echo esc_attr( isset( $_GET['s'] ) ? $_GET['s'] : '' ); ?>" autocomplete="on" />
		</span>

		<span class="menu-search-submit fa fa-search">
			<input type="submit" value="<?php _e( 'Search', 'pojo' ); ?>" />
		</span>

	</div>

	<?php if ( $advanced_search ) {

		// generate form fields
		$formats_field		= kulam_search_get_post_formats_field();
		$categories_field	= kulam_search_get_category_terms_field();
		$taxonomies_fields	= kulam_search_get_taxonomies_fields();

		if ( $formats_field || $categories_field || $taxonomies_fields ) { ?>

			<div class="advanced-search">

				<div class="advanced-search-btn"><a><?php _e( 'Advanced Search', 'kulam-scoop' ); ?></a></div>

				<div class="advanced-search-fields-wrapper">

					<div class="instructions"><?php _e( 'Please select at least one field and click Search', 'kulam-scoop' ); ?><span>&times;</span></div>

					<div class="advanced-search-fields">

						<?php
							/**
							 * Display post formats form field
							 */
							echo $formats_field;
						?>

						<?php
							/**
							 * Display category terms form field
							 */
							echo $categories_field;
						?>

						<?php
							/**
							 * Display taxonomies form fields
							 */
							echo $taxonomies_fields;
						?>

					</div><!-- .advanced-search-fields -->

					<span class="menu-search-input">
						<input type="submit" class="advanced-search-submit" value="<?php _e( 'Search', 'pojo' ); ?>" />
					</span>

				</div><!-- .advanced-search-fields-wrapper -->

			</div><!-- .advanced-search -->

		<?php }

	} ?>

</form>