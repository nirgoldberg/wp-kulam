<?php
/**
 * Archive
 *
 * @author		Nir Goldberg
 * @package		scoop-child/loop
 * @version		1.2.5
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_field' ) )
	return;

/**
 * Variables
 */
$my_siddur_custom_label	= get_field( 'acf-option_my_siddur_custom_label', 'option' );
$my_siddur_label		= $my_siddur_custom_label ? $my_siddur_custom_label : __( 'My Siddur', 'kulam-scoop' );
$display_type			= po_get_display_type();

if ( is_category() ) {

	$category = get_queried_object();

}

if ( ! is_home() && ! is_front_page() ) { ?>

	<header class="entry-header">

		<?php if ( ! is_tax( 'siddurim' ) && po_breadcrumbs_need_to_show() ) {

			pojo_breadcrumbs();

		}

		if ( is_category() ) {

			$category_description = category_description( $category->term_id );

		} ?>

		<div class="page-title">
			<h1 class="entry-title"><?php
				if ( is_day() ) :
					printf( __( 'Archive for %s', 'pojo' ), '<span>' . get_the_date() . '</span>' );
				elseif ( is_month() ) :
					printf( __( 'Archive for %s', 'pojo' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'pojo' ) ) . '</span>' );
				elseif ( is_year() ) :
					printf( __( 'Archive for %s', 'pojo' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'pojo' ) ) . '</span>' );
				elseif ( is_category() ) :
					echo '<span>' . single_cat_title( '', false ) . '</span>' . ( $category_description ? '<span class="more">' . kulam_get_svg( 'info' ) . '</span><span class="less">' . kulam_get_svg( 'minus' ) . '</span>' : '' );
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

		<?php if ( is_category() && $category_description ) { ?>

			<div class="category-desc">
				<div class="desc"><?php echo $category_description; ?></div>
			</div>

		<?php }

		if ( is_category() && get_term_children( $category->term_id, 'category' ) ) {

			get_template_part( 'partials/subcat-menu' );

		} ?>

	</header><!-- .entry-header -->

<?php }

if ( have_posts() ) {

	if ( is_category() ) {

		// get category post types
		$post_types = get_field( 'acf-category_post_types', 'category_' . $category->term_id );

		if ( $post_types ) {

			$single_post_type = ( count( $post_types ) == 1 );

			foreach ( $post_types as $post_type ) {

				// get post type top posts
				$top_posts = get_field( 'kulam_top_posts_relationship_' . $post_type->slug, 'category_' . $category->term_id );

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

				if ( $top_posts || $posts_query->have_posts() ) : ?>

					<div class="post-type-posts-grid <?php echo $single_post_type ? 'single-post-type open' : ''; ?>">

						<h2 class="post-type-title"><?php echo $post_type->name; ?></h2>
						<p><a class="post-type-sub-title more" data-toggle="<?php printf( __( 'Click for less %s', 'kulam-scoop' ), $post_type->name ); ?>"><?php printf( __( 'Click for more %s', 'kulam-scoop' ), $post_type->name ); ?></a></p>

						<?php do_action( 'pojo_before_content_loop', $display_type );

						if ( $top_posts ) {

							foreach ( $top_posts as $post_id ) {

								if ( 'publish' == get_post_status( $post_id ) && 'post' == get_post_type( $post_id ) ) {

									$post = get_post( $post_id );
									pojo_get_content_template_part( 'content', $display_type );

								}

							}

						}

						while ( $posts_query->have_posts() ) :

							$posts_query->the_post();
							pojo_get_content_template_part( 'content', $display_type );

						endwhile;

						do_action( 'pojo_after_content_loop', $display_type ); ?>

						<p><a class="post-type-sub-title less"><?php printf( __( 'Click for less %s', 'kulam-scoop' ), $post_type->name ); ?></a></p>

					</div><!-- .post-type-posts-grid -->

				<?php endif;

				wp_reset_postdata();

			}

		}

	}
	else {

		do_action( 'pojo_before_content_loop', $display_type );

		while ( have_posts() ) : the_post();

			pojo_get_content_template_part( 'content', $display_type );

		endwhile;

		do_action( 'pojo_after_content_loop', $display_type );
		pojo_paginate();

	}

}
else {

	pojo_get_content_template_part( 'content', 'none' );

}