<?php
/**
 * Advanced Search
 *
 * @author      Nir Goldberg
 * @package     scoop-child
 * @version     1.0.4
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Variables
 */
$formts			= array();
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

// categories

$categories	= array();
$locations	= get_nav_menu_locations();
$menu		= wp_get_nav_menu_object( $locations[ 'primary' ] );
$menu_items	= wp_get_nav_menu_items( $menu->name );

//var_dump($menu_items);

if ( $menu_items ) {
	foreach ( $menu_items as $item ) {
		if ( $item->object == 'category' ) {
			$categories[ $item->object_id ] = $item->title;
		}
	}
}

?>

<form role="search" action="<?php echo home_url( '/' ); ?>" method="get">

	<span class="menu-search-input">
		<input type="search" name="s" placeholder="<?php _e( 'search...', 'kulam-scoop' ); ?>" value="<?php echo esc_attr( isset( $_GET['s'] ) ? $_GET['s'] : '' ); ?>" autocomplete="on" />
	</span>

	<span class="menu-search-submit fa fa-search">
		<input type="submit" value="<?php _e( 'Search', 'pojo' ); ?>" />
	</span>

	<div class="advanced-search">

		<div class="advanced-search-btn"><a><?php _e( 'Advanced Search', 'kulam-scoop' ); ?></a></div>

		<div class="advanced-search-fields">

			<?php if ( $formats ) { ?>

				<span class="menu-search-input">
					<select name="post_format" id="post_format">

						<option value="0"><?php _e( 'Choose a post format', 'kulam-scoop' ); ?></option>

						<?php foreach ( $formats as $f ) {
							echo '<option value="' . $f . '" ' . ( ( isset( $_GET[ 'post_format' ] ) && $f == $_GET[ 'post_format' ] ) ? 'selected="selected"' : '' ) . '>' . esc_html( get_post_format_string( $f ) ) . '</option>';
						} ?>

					</select>
				</span>

			<?php }

			if ( $types ) { ?>

				<span class="menu-search-input">
					<select name="pt" id="pt">

						<option value="0"><?php _e( 'Choose a post type', 'kulam-scoop' ); ?></option>

						<?php foreach ( $types as $t ) {
							echo '<option value="' . $t->slug . '" ' . ( ( isset( $_GET[ 'pt' ] ) && $t->slug == $_GET[ 'pt' ] ) ? 'selected="selected"' : '' ) . '>' . $t->name . '</option>';
						} ?>

					</select>
				</span>

			<?php }

			if ( $categories ) { ?>

				<span class="menu-search-input">
					<select name="cat" id="cat">

						<option value="0"><?php _e( 'Choose a category', 'kulam-scoop' ); ?></option>

						<?php foreach ( $categories as $id => $name ) {
							echo '<option value="' . $id . '" ' . ( ( isset( $_GET[ 'cat' ] ) && $id == $_GET[ 'cat' ] ) ? 'selected="selected"' : '' ) . '>' . $name . '</option>';
						} ?>

					</select>
				</span>

			<?php } ?>

			<span class="menu-search-input">
				<input type="submit" class="advanced-search-submit" value="<?php _e( 'Search', 'pojo' ); ?>" />
			<span>

		</div><!-- .advanced-search-fields -->

	</div><!-- .advanced-search -->

</form>