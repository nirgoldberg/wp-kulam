<?php
/**
 * Advanced Search
 *
 * @author      Nir Goldberg
 * @package     scoop-child
 * @version     1.1.4
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Variables
 */
$formats		= array();
$types			= array();
$categories		= array();

// post formats

if ( current_theme_supports( 'post-formats' ) ) {

	$post_formats = get_theme_support( 'post-formats' );

	if ( is_array( $post_formats[0] ) ) {
		$formats = $post_formats[0];
	}

}

// article types

$types_args = array(
	'taxonomy'		=> 'post_types_tax',
	'hide_empty'	=> 0
);
$types = get_terms( $types_args );

if ( $types ) {
	foreach ( $types as $key => $type ) {
		if ( $type->count <= 1 ) {
			unset( $types[ $key ] );
		}
	}
}

// categories

$categories	= array();
$locations	= get_nav_menu_locations();
$menu		= wp_get_nav_menu_object( $locations[ 'primary' ] );
$menu_items	= wp_get_nav_menu_items( $menu->name );

if ( $menu_items ) {
	foreach ( $menu_items as $item ) {
		if ( $item->object == 'category' ) {

			$cat	= get_category( $item->object_id );
			$id		= $cat->term_id;
			$title	= str_replace( array( '"', "'" ), array( '', '' ), $item->title );

			if ( $cat->count > 3 ) {
				$categories[] = array(
					'id'	=> $id,
					'title'	=> $title
				);
				continue;
			}

			$args = array(
				'posts_per_page'	=> 3,
				'cat'				=> $id
			);
			$query = new WP_Query( $args );

			$count = $query->found_posts;

			if ( $count > 3 ) {
				$categories[] = array(
					'id'	=> $id,
					'title'	=> $title
				);
			}

			wp_reset_postdata();

		}
	}
}

?>

<form role="search" action="<?php echo home_url( '/' ); ?>" method="get">

	<div class="menu-search-input-text">

		<span class="menu-search-input">
			<input type="search" name="s" placeholder="<?php _e( 'Search...', 'kulam-scoop' ); ?>" value="<?php echo esc_attr( isset( $_GET['s'] ) ? $_GET['s'] : '' ); ?>" autocomplete="on" />
		</span>

		<span class="menu-search-submit fa fa-search">
			<input type="submit" value="<?php _e( 'Search', 'pojo' ); ?>" />
		</span>

	</div>

	<div class="advanced-search">

		<div class="advanced-search-btn"><a><?php _e( 'Advanced Search', 'kulam-scoop' ); ?></a></div>

		<div class="advanced-search-fields-wrapper">

			<div class="instructions"><?php _e( 'Please select at least one field and click Search', 'kulam-scoop' ); ?><span>&times;</span></div>

			<div class="advanced-search-fields">

				<?php if ( $formats ) { ?>

					<span id="menu-search-input-post-format" class="menu-search-input">
						<select name="post_format">

							<option value=""><?php _e( 'Choose a post format', 'kulam-scoop' ); ?></option>
							<option value="0" <?php echo ( ( isset( $_GET[ 'post_format' ] ) && '0' == $_GET[ 'post_format' ] ) ? 'selected="selected"' : '' ); ?>><?php _e( 'Text', 'kulam-scoop' ); ?></option>

							<?php foreach ( $formats as $f ) {
								echo '<option value="' . $f . '" ' . ( ( isset( $_GET[ 'post_format' ] ) && $f == $_GET[ 'post_format' ] ) ? 'selected="selected"' : '' ) . '>' . esc_html( get_post_format_string( $f ) ) . '</option>';
							} ?>

						</select>
					</span>

				<?php }

				if ( $types ) { ?>

					<span id="menu-search-input-post-type" class="menu-search-input">
						<select name="pt">

							<option value=""><?php _e( 'Choose a post type', 'kulam-scoop' ); ?></option>

							<?php foreach ( $types as $t ) {
								echo '<option value="' . $t->slug . '" ' . ( ( isset( $_GET[ 'pt' ] ) && $t->slug == $_GET[ 'pt' ] ) ? 'selected="selected"' : '' ) . '>' . $t->name . '</option>';
							} ?>

						</select>
					</span>

				<?php }

				if ( $categories ) { ?>

					<span id="menu-search-input-category" class="menu-search-input">
						<input type="text" name="cat_name" class="auto-complete-input" placeholder="<?php _e( 'Choose a category', 'kulam-scoop' ); ?>" value="<?php echo esc_attr( isset( $_GET[ 'cat_name' ] ) ? $_GET[ 'cat_name' ] : '' ); ?>" data-options="<?php echo esc_js( json_encode( $categories, JSON_UNESCAPED_UNICODE ) ); ?>" data-auto-complete-output="auto-complete-cat_name" />
						<input type="hidden" name="cat" class="auto-complete-cat_name" value="<?php echo esc_attr( isset( $_GET[ 'cat' ] ) ? $_GET[ 'cat' ] : '' ); ?>" />
					</span>

				<?php } ?>

			</div><!-- .advanced-search-fields -->

			<span class="menu-search-input">
				<input type="submit" class="advanced-search-submit" value="<?php _e( 'Search', 'pojo' ); ?>" />
			</span>

		</div><!-- .advanced-search-fields-wrapper -->

	</div><!-- .advanced-search -->

</form>