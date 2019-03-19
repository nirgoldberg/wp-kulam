<?php
/**
 * Search Template
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$display_type = pojo_get_option( 'posts_display_type' );
?>
	<header class="entry-header">
		<?php if ( po_breadcrumbs_need_to_show() ) : ?>
			<?php pojo_breadcrumbs(); ?>
		<?php endif; ?>
		<div class="page-title">
			<h1 class="entry-title"><?php printf( __( 'Search Results for: %s', 'pojo' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
		</div>
	</header>

<?php if ( have_posts() ) : ?>
	<?php do_action( 'pojo_before_content_loop', $display_type ); ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<?php pojo_get_content_template_part( 'content', $display_type ); ?>
	<?php endwhile; ?>
	<?php do_action( 'pojo_after_content_loop', $display_type ); ?>
	<?php pojo_paginate(); ?>
<?php else : ?>
	<?php pojo_get_content_template_part( 'content', 'none' ); ?>
<?php endif; ?>