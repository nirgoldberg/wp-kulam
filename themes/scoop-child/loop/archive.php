<?php
/**
 * Archive
 *
 * @author      Nir Goldberg
 * @package     scoop-child
 * @version     1.1
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$display_type = po_get_display_type();
$is_filtered = ( isset($_GET['pt']) ? true : false );

?>
<?php if (!is_home() && ! is_front_page()) : ?>
	<header class="entry-header">
		<?php if (!is_tax('siddurim') && po_breadcrumbs_need_to_show()) { ?>
			<div class="breadcrumb"><?php get_breadcrumb(); ?></div>
		<?php }

		if ( is_category() ) {

			$category_description = category_description( get_queried_object()->term_id );

		} ?>

		<div class="page-title">
			<h1 class="entry-title"><?php
			if (is_day()) :
				printf(__('Archive for %s', 'pojo'), '<span>' . get_the_date() . '</span>');
			elseif (is_month()) :
				printf(__('Archive for %s', 'pojo'), '<span>' . get_the_date(_x('F Y', 'monthly archives date format', 'pojo')) . '</span>');
			elseif (is_year()) :
				printf(__('Archive for %s', 'pojo'), '<span>' . get_the_date(_x('Y', 'yearly archives date format', 'pojo')) . '</span>');
			elseif (is_category()) :
				echo '<span>'.single_cat_title('', false) . '</span>' . ( $category_description ? '<span class="more">' . Kulam_get_svg( 'info' ) . '</span><span class="less">' . Kulam_get_svg( 'minus' ) . '</span>' : '' );
			elseif (is_tag()) :
				echo '<span>'.single_tag_title('', false) . '</span>';
			elseif (is_tax('post_format')) :
				printf(__('Archive %s', 'pojo'), '<span>' . get_post_format_string(get_post_format()) . '</span>');
			elseif (is_tax('post_types_tax')) :
				printf(__('Archive %s', 'pojo'), '<span>' . get_queried_object()->name . '</span>');
			elseif (is_tax('siddurim')) :
				$label = __("My Siddur", "kulam-scoop");
				$custom_label = get_field('album_label', 'options');
				if ($custom_label) :
					$label = $custom_label;
				endif;
				echo $label . ' <span>' . wp_get_current_user()->display_name . '</span>';
			elseif (is_author()) :
				global $author;
				$userdata = get_userdata($author);
				printf(__('All posts by %s', 'pojo'), '<span>' . $userdata->display_name . '</span>');
			else :
				_e('Archive', 'pojo');
			endif;
			?></h1>
		</div>
		<?php

		if ( is_category() && $category_description ) { ?>

			<div class="category-desc">
				<div class="desc"><?php echo $category_description; ?></div>
			</div>

		<?php }

		if (is_category() && get_term_children(get_queried_object()->term_id, 'category')) {
			get_template_part('partials/subcat-menu');
		} ?>
	</header>
<?php endif; ?>
<?php if (have_posts()) :


	if (is_category() && false == $is_filtered) {

	// If the category is not filtered by post type, the page should display up to 3 posts from each post type (empty ones will be hidden).

		$kol_post_types_setting = get_field('category_page_sections', 'options');

	   if ($kol_post_types_setting && 0 < count($kol_post_types_setting)) {
		  $kol_post_types = $kol_post_types_setting;
	   } else {
			$kol_post_types = get_terms( array(
				'taxonomy' => 'post_types_tax',
				'hide_empty' => true,
				'include' => 'all',
			) ); 
		}

		foreach ($kol_post_types as $kol_post_type) {

			if (is_string($kol_post_type)):
				$kol_post_type = get_term_by('id', $kol_post_type, 'post_types_tax');
			endif;

			$joint_query_args = kulam_get_joint_query_args( get_queried_object()->term_id, $kol_post_type->slug, $is_filtered);

			$joint_query = new WP_Query($joint_query_args);

			if ($joint_query->have_posts()  && 0 < $joint_query_args['query_count'] ) :
				$link = sprintf( esc_html__( 'Click For More %s', 'kulam-scoop' ), $kol_post_type->name );
				$url = '?pt=' . $kol_post_type->slug;
				echo "<h2 class='post-type-title'>" . $kol_post_type->name . "</h2>";
				echo "<p><a class='post-type-sub-title' href='" . $url . " '>" . $link . "</a></p>";
				do_action('pojo_before_content_loop', $display_type);
				while ($joint_query->have_posts()) :
					$joint_query->the_post();
					pojo_get_content_template_part('content', $display_type);
				endwhile;
				do_action('pojo_after_content_loop', $display_type);
			endif;

			wp_reset_postdata();
		}

	} elseif (is_category() && true == $is_filtered) {

	// If the category is filtered by post type, populate the query with this post type alone, now limit on number of posts.

		$active_filter = $_GET['pt'];
		$filtered_term = get_term_by('slug', $active_filter, 'post_types_tax');

		$joint_query_args = kulam_get_joint_query_args( get_queried_object()->term_id, $filtered_term->slug , $is_filtered);

		$joint_query = new WP_Query($joint_query_args);

		if ($joint_query->have_posts()  && 0 < $joint_query_args['query_count'] ) :
			$link = esc_html__('Back to category', 'kulam-scoop');
			$url = get_category_link( get_queried_object()->term_id );
			echo "<h2 class='post-type-title'>" . $filtered_term->name . "</h2>";
			echo "<p><a  href='" . $url . " ' rel='nofollow'>" . $link . "</a></p>";
			do_action('pojo_before_content_loop', $display_type);
			while ($joint_query->have_posts()) :
				$joint_query->the_post();
				pojo_get_content_template_part('content', $display_type);
			endwhile;
			do_action('pojo_after_content_loop', $display_type);
		endif;

		wp_reset_postdata();

	} else {
		do_action('pojo_before_content_loop', $display_type);
		while (have_posts()) :
			the_post();
			pojo_get_content_template_part('content', $display_type);
		endwhile;
		do_action('pojo_after_content_loop', $display_type);
		pojo_paginate();
	}
else :
	pojo_get_content_template_part('content', 'none');
endif;
