<?php
/**
 * Default Page
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( have_posts() ) :
	while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-page">
				<?php if ( po_breadcrumbs_need_to_show() || pojo_is_show_page_title() ) : ?>
					<header class="entry-header">
						<?php if ( po_breadcrumbs_need_to_show() ) : ?>
							<?php pojo_breadcrumbs(); ?>
						<?php endif; ?>
						<?php if ( pojo_is_show_page_title() ) : ?>
							<div class="page-title">
								<h1 class="entry-title"><?php the_title(); ?></h1>
							</div>
						<?php endif; ?>
					</header>
				<?php endif; ?>
				<div class="entry-content">
					<?php if ( ! Pojo_Core::instance()->builder->display_builder() ) : ?>
						<?php the_content(); ?>
						<?php pojo_link_pages(); ?>
					<?php endif; ?>
				</div>
				<footer class="entry-footer">
					<div class="entry-edit">
						<?php pojo_button_post_edit(); ?>
					</div>
				</footer>
			</div>
		</article>
		<?php
			// Previous/next post navigation.
			echo pojo_get_post_navigation(
				array(
					'prev_text' => __( '&laquo; Previous', 'pojo' ),
					'next_text' => __( 'Next &raquo;', 'pojo' ),
				)
			);
		?>
	<?php endwhile;
else :
	pojo_get_content_template_part( 'content', 'none' );
endif;