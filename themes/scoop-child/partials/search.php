<?php
/**
 * Advanced Search
 *
 * @author      Nir Goldberg
 * @package     scoop-child
 * @version     1.6.3
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_field' ) )
	return;

/**
 * Variables
 */
$formats		= array();
$types			= array();
$activities		= array();
$categories		= array();

$search_input_placeholder	= get_field( 'acf-option_search_input_placeholder', 'option' );
$advanced_search			= get_field( 'acf-option_advanced_search', 'option' );

$search_input_placeholder	= $search_input_placeholder ?: __( 'Search...', 'kulam-scoop' );
$advanced_search			= $advanced_search !== false ? true : false;

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

// activity types

$enable_activity_types = get_field( 'acf-option_enable_activity_types_custom_taxonomy', 'option' );

if ( $enable_activity_types && true === $enable_activity_types ) {

	$activity_types_args = array(
		'taxonomy'		=> 'activity_types',
		'hide_empty'	=> 0
	);
	$activity_types = get_terms( $activity_types_args );

	if ( $activity_types ) {
		foreach ( $activity_types as $key => $type ) {
			if ( $type->count <= 1 ) {
				unset( $activity_types[ $key ] );
			}
		}
	}

}

// audiences

$enable_audiences = get_field( 'acf-option_enable_audiences_custom_taxonomy', 'option' );

if ( $enable_audiences && true === $enable_audiences ) {

	$audiences_args = array(
		'taxonomy'		=> 'audiences',
		'hide_empty'	=> 0
	);
	$audiences = get_terms( $audiences_args );

	if ( $audiences ) {
		foreach ( $audiences as $key => $audience ) {
			if ( $audience->count <= 1 ) {
				unset( $audiences[ $key ] );
			}
		}
	}

}

// categories

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
			<input type="text" name="s" placeholder="<?php echo $search_input_placeholder; ?>" data-alternate-placeholder="<?php _e( 'Free text...', 'kulam-scoop' ); ?>" value="<?php echo esc_attr( isset( $_GET['s'] ) ? $_GET['s'] : '' ); ?>" autocomplete="on" />
		</span>

		<span class="menu-search-submit fa fa-search">
			<input type="submit" value="<?php _e( 'Search', 'pojo' ); ?>" />
		</span>

	</div>

	<?php if ( $advanced_search ) : ?>

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

					if ( $activity_types ) { ?>

						<span id="menu-search-input-activity-type" class="menu-search-input">
							<select name="activity_type">

								<option value=""><?php _e( 'Choose an activity type', 'kulam-scoop' ); ?></option>

								<?php foreach ( $activity_types as $t ) {
									echo '<option value="' . $t->slug . '" ' . ( ( isset( $_GET[ 'activity_type' ] ) && $t->slug == $_GET[ 'activity_type' ] ) ? 'selected="selected"' : '' ) . '>' . $t->name . '</option>';
								} ?>

							</select>
						</span>

					<?php }

					if ( $audiences ) { ?>

						<span id="menu-search-input-audience" class="menu-search-input">
							<select name="audience">

								<option value=""><?php _e( 'Choose an audience', 'kulam-scoop' ); ?></option>

								<?php foreach ( $audiences as $t ) {
									echo '<option value="' . $t->slug . '" ' . ( ( isset( $_GET[ 'audience' ] ) && $t->slug == $_GET[ 'audience' ] ) ? 'selected="selected"' : '' ) . '>' . $t->name . '</option>';
								} ?>

							</select>
						</span>

					<?php }

					if ( $categories ) { ?>

						<span id="menu-search-input-category" class="menu-search-input">
							<input type="text" name="cat_name" class="auto-complete-input" placeholder="<?php _e( 'Type category name', 'kulam-scoop' ); ?>" value="<?php echo esc_attr( isset( $_GET[ 'cat_name' ] ) ? $_GET[ 'cat_name' ] : '' ); ?>" data-options="<?php echo esc_js( json_encode( $categories, JSON_UNESCAPED_UNICODE ) ); ?>" data-auto-complete-output="auto-complete-cat_name" />
							<input type="hidden" name="cat" class="auto-complete-cat_name" value="<?php echo esc_attr( isset( $_GET[ 'cat' ] ) ? $_GET[ 'cat' ] : '' ); ?>" />
						</span>

					<?php } ?>

				</div><!-- .advanced-search-fields -->

				<span class="menu-search-input">
					<input type="submit" class="advanced-search-submit" value="<?php _e( 'Search', 'pojo' ); ?>" />
				</span>

			</div><!-- .advanced-search-fields-wrapper -->

		</div><!-- .advanced-search -->

	<?php endif; ?>

</form>