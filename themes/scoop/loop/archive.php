<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$display_type = po_get_display_type();
?>
<?php if ( ! is_home() && ! is_front_page() ) : ?>
	<header class="entry-header">
		<?php if ( po_breadcrumbs_need_to_show() ) : ?>
			<?php pojo_breadcrumbs(); ?>
		<?php endif; ?>
		<div class="page-title">
			<h1 class="entry-title"><?php
				if ( is_day() ) :
					printf( __( 'Archive for %s', 'pojo' ), '<span>' . get_the_date() . '</span>' );
				elseif ( is_month() ) :
					printf( __( 'Archive for %s', 'pojo' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'pojo' ) ) . '</span>' );
				elseif ( is_year() ) :
					printf( __( 'Archive for %s', 'pojo' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'pojo' ) ) . '</span>' );
				elseif ( is_category() ) :
					printf( __( 'All posts in %s', 'pojo' ), '<span>' . single_cat_title( '', false ) . '</span>' );
				elseif ( is_tag() ) :
					printf( __( 'All posts in %s', 'pojo' ), '<span>' . single_tag_title( '', false ) . '</span>' );
				elseif ( is_tax( 'post_format' ) ) :
					printf( __( 'Archive %s', 'pojo' ), '<span>' . get_post_format_string( get_post_format() ) . '</span>' );
				elseif ( is_author() ) :
					global $author;
					$userdata = get_userdata( $author );
					printf( __( 'All posts by %s', 'pojo' ), '<span>' . $userdata->display_name . '</span>' );
				else :
					_e( 'Archive', 'pojo' );
				endif;
				?></h1>
		</div>
	</header>
<?php endif; ?>

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
