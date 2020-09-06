<?php
/**
 * Archive
 *
 * @author		Nir Goldberg
 * @package		scoop-child/loop
 * @version		2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_field' ) )
	return;

// vars
$strip_image			= get_field( 'acf-option_strip_image', 'option' );
$my_siddur_custom_label	= get_field( 'acf-option_my_siddur_custom_label', 'option' );
$my_siddur_label		= $my_siddur_custom_label ? $my_siddur_custom_label : __( 'My Siddur', 'kulam-scoop' );
$display_type			= po_get_display_type();
$user_state				= kulam_get_current_user_state();

if ( is_category() ) {

	$category = get_queried_object();

}

if ( ! is_home() && ! is_front_page() ) { ?>

	<header class="entry-header">

		<?php if ( $strip_image ) : ?>
			<div class="strip-image">
				<img src="<?php echo $strip_image[ 'url' ]; ?>" alt="<?php echo $strip_image[ 'alt' ]; ?>" />
			</div>
		<?php endif; ?>

		<?php if ( ! is_tax( 'siddurim' ) && po_breadcrumbs_need_to_show() ) {

			pojo_breadcrumbs();

		}

		if ( is_category() ) {

			$category_description			= category_description( $category->term_id );
			$category_description_toggling	= get_field( 'acf-option_category_description_toggling', 'option' );
			$google_map						= get_field( 'acf-category_google_map', 'category_' . $category->term_id );
			$google_map_zoom				= get_field( 'acf-category_google_map_zoom', 'category_' . $category->term_id );
			$google_maps_api				= get_field( 'acf-option_google_maps_api', 'option' );
			$popup_image					= get_field( 'acf-category_popup_image', 'category_' . $category->term_id );
			$popup_button_text				= get_field( 'acf-category_popup_button_text', 'category_' . $category->term_id );
			$popup_button_text_color		= get_field( 'acf-category_popup_button_text_color', 'category_' . $category->term_id );
			$popup_button_bg_color			= get_field( 'acf-category_popup_button_bg_color', 'category_' . $category->term_id );
			$category_icon					= get_field( 'acf-category_category_icon', 'category_' . $category->term_id );
			$category_filters				= kulam_get_category_filters( $category );
			$filters						= $category_filters ? array_map( function( $tax ){ return $tax->name; }, $category_filters ) : array();

		} ?>

		<div class="page-title">
			<h1 class="entry-title <?php echo $category_icon ? 'has-icon" style="background-image: url(\'' . $category_icon[ 'url' ] . '\'); min-height:' . $category_icon[ 'height' ] . 'px;"' : '"'; ?>><?php
				if ( is_day() ) :
					printf( __( 'Archive for %s', 'pojo' ), '<span>' . get_the_date() . '</span>' );
				elseif ( is_month() ) :
					printf( __( 'Archive for %s', 'pojo' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'pojo' ) ) . '</span>' );
				elseif ( is_year() ) :
					printf( __( 'Archive for %s', 'pojo' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'pojo' ) ) . '</span>' );
				elseif ( is_category() ) :
					echo '<span>' . single_cat_title( '', false ) . '</span>' . ( $category_description && $category_description_toggling ? '<span class="more">' . kulam_get_svg( 'info' ) . '</span><span class="less">' . kulam_get_svg( 'minus' ) . '</span>' : '' );
				elseif ( is_tag() ) :
					echo '<span>' . single_tag_title( '', false ) . '</span>';
				elseif ( is_tax( 'post_format' ) ) :
					printf( __( 'Archive %s', 'pojo' ), '<span>' . get_post_format_string( get_post_format() ) . '</span>' );
				elseif ( is_tax( 'post_types_tax' ) ) :
					printf( __( 'Archive %s', 'pojo' ), '<span>' . $category->name . '</span>' );
				elseif ( is_tax( 'siddurim' ) ) :
					echo $my_siddur_label . ' <span>' . wp_get_current_user()->display_name . '</span>';
				elseif ( is_author() ) :
					global $author;
					$userdata = get_userdata( $author );
					printf( __( 'All posts by %s', 'pojo' ), '<span>' . $userdata->display_name . '</span>' );
				else :
					_e( 'Archive', 'pojo' );
				endif;
			?></h1>
		</div>

		<?php if ( is_category() ) {

			if ( $category_description ) { ?>

				<div class="category-desc <?php echo ! $category_description_toggling ? 'open' : ''; ?>">
					<div class="desc"><?php echo $category_description; ?></div>
				</div>

			<?php }

			if ( $popup_image && $popup_button_text ) {

				$style = '';
				$style .= $popup_button_text_color ? 'color:' . $popup_button_text_color . ';' : '';
				$style .= $popup_button_bg_color ? 'background-color:' . $popup_button_bg_color . ';' : '';

				?>

				<button class="btn category-popup-button kulam-button" data-toggle="modal" data-target="#modal-category-popup-image" style="<?php echo $style; ?>"><?php echo $popup_button_text; ?></button>

			<?php }

			if ( $google_map && $google_maps_api ) { ?>

				<div class="acf-map" data-zoom="<?php echo $google_map_zoom ?: '11'; ?>">
					<div class="marker" data-lat="<?php echo esc_attr( $google_map[ 'lat' ] ); ?>" data-lng="<?php echo esc_attr( $google_map[ 'lng' ] ); ?>"></div>
				</div>

			<?php }

		} ?>

	</header><!-- .entry-header -->

<?php }

if ( have_posts() ) {

	if ( is_category() ) {

		// get category post types
		$post_types = get_field( 'acf-category_post_types', 'category_' . $category->term_id );

		if ( ! $post_types ) {
			$post_types = get_field( 'acf-option_category_page_post_types', 'option' );
		}

		if ( ! $post_types ) {
			$post_types = get_terms( array(
				'taxonomy'	=> 'post_types_tax',
				'orderby'	=> 'term_order',
			));
		}

		// prevent use of $post_types here as a temporary solution for this page template
		$post_types = null;

		if ( ! empty( $post_types ) && ! is_wp_error( $post_types ) ) {

			$single_post_type = ( count( $post_types ) == 1 );

			foreach ( $post_types as $post_type ) {

				// get post type top posts
				$top_posts = get_field( 'kulam_top_posts_relationship_' . $post_type->slug, 'category_' . $category->term_id );

				// get all top posts associated with current category and post type
				if ( $top_posts ) {

					$top_posts_query_args = array(
						'post_type'			=> 'post',
						'posts_per_page'	=> '-1',
						'post__in'			=> $top_posts,
						'orderby'			=> 'post__in',
						'cat'				=> $category->term_id,
						'tax_query'			=> array(
							array(
								'taxonomy'	=> 'post_types_tax',
								'field'		=> 'slug',
								'terms'		=> $post_type->slug,
							),
						),
					);
					$top_posts_query = new WP_Query( $top_posts_query_args );

				}

				// get all posts associated with current category and post type, excluding top posts
				$posts_query_args = array(
					'post_type'			=> 'post',
					'posts_per_page'	=> '-1',
					'post__not_in'		=> $top_posts,
					'cat'				=> $category->term_id,
					'tax_query'			=> array(
						array(
							'taxonomy'	=> 'post_types_tax',
							'field'		=> 'slug',
							'terms'		=> $post_type->slug,
						),
					),
				);
				$posts_query = new WP_Query( $posts_query_args );

				if ( $top_posts && $top_posts_query->have_posts() || $posts_query->have_posts() ) : ?>

					<div class="post-type-posts-grid <?php echo $single_post_type ? 'single-post-type open' : ''; ?>">

						<h2 class="post-type-title"><?php echo $post_type->name; ?></h2>
						<p><a class="post-type-sub-title more" data-toggle="<span><?php printf( __( 'Click here to show less <i>%s</i> content', 'kulam-scoop' ), $post_type->name ); ?></span> <span>&uarr;</span>"><span><?php printf( __( 'Click here to show more <i>%s</i> content', 'kulam-scoop' ), $post_type->name ); ?></span> <span>&darr;</span></a></p>

						<?php do_action( 'pojo_before_content_loop', $display_type );

						// display top posts
						if ( $top_posts && $top_posts_query->have_posts() ) : while ( $top_posts_query->have_posts() ) :

							$top_posts_query->the_post();
							pojo_get_content_template_part( 'content', $display_type );

						endwhile; endif;

						// display all other posts
						while ( $posts_query->have_posts() ) :

							$posts_query->the_post();
							pojo_get_content_template_part( 'content', $display_type );

						endwhile;

						do_action( 'pojo_after_content_loop', $display_type ); ?>

						<p><a class="post-type-sub-title less"><span><?php printf( __( 'Click here to show less <i>%s</i> content', 'kulam-scoop' ), $post_type->name ); ?></span> <span>&uarr;</span></a></p>

					</div><!-- .post-type-posts-grid -->

				<?php endif;

				wp_reset_postdata();

			}

		} else {

			// no post types
			// get posts
			$posts = array();

			$args = array(
				'posts_per_page'	=> -1,
				'tax_query'			=> array(
					array(
						'taxonomy'	=> 'category',
						'field'		=> 'term_id',
						'terms'		=> $category->term_id,
					),
				),
			);

			// modify query according to user state ( hmembership_member | logged_in | public )
			if ( in_array( $user_state, array( 'logged_in', 'public' ) ) ) {

				// setup meta_query
				$args[ 'meta_query' ] = array(
					'relation'		=> 'OR',
					array(
						'key'		=> 'acf-post_restrict_post',
						'compare'	=> 'NOT EXISTS',
					),
				);

				if ( 'logged_in' == $user_state ) {
					$value = array( 'public', 'logged_in' );
				}
				else {
					$value = array( 'public' );
				}

				$args[ 'meta_query' ][] = array(
					'key'		=> 'acf-post_restrict_post',
					'value'		=> $value,
					'compare'	=> 'IN',
				);

			}

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) : ?>

				<?php
					/**
					 * display filters menu
					 */
					include( locate_template( 'partials/category/filters-menu.php' ) );
				?>

				<div class="posts-wrap row">

					<?php while( $query->have_posts() ) : $query->the_post();

						// get post taxonomies
						$taxonomies = get_post_taxonomies();

						// include only assigned taxonomy filters
						$taxonomies = array_intersect( $taxonomies, $filters );

						echo kulam_get_post( $taxonomies );

					endwhile; ?>

				</div>

			<?php endif; wp_reset_postdata(); ?>

			<div class="filtered-posts-not-found" style="display: none;">

				<?php pojo_get_content_template_part( 'content', 'none' ); ?>

			</div>

			<?php
				/**
				 * Display registration banner
				 */
				get_template_part( 'partials/registration-banner' );
			?>

		<?php }

	} else {

		do_action( 'pojo_before_content_loop', $display_type );

		while ( have_posts() ) : the_post();

			pojo_get_content_template_part( 'content', $display_type );

		endwhile;

		do_action( 'pojo_after_content_loop', $display_type );
		pojo_paginate();

	}

} else {

	pojo_get_content_template_part( 'content', 'none' );

}