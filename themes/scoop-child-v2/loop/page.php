<?php
/**
 * Default Page
 *
 * @author		Nir Goldberg
 * @package		scoop-child/loop
 * @version		2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_field' ) )
	return;

/**
 * Variables
 */
$strip_image = get_field( 'acf-option_strip_image', 'option' );

if ( have_posts() ) :
	while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-page">
				<?php if ( po_breadcrumbs_need_to_show() || pojo_is_show_page_title() ) : ?>
					<header class="entry-header">

						<?php if ( $strip_image && ! is_front_page() && ! is_home() ) : ?>
							<div class="strip-image">
								<img src="<?php echo $strip_image[ 'url' ]; ?>" alt="<?php echo $strip_image[ 'alt' ]; ?>" />
							</div>
						<?php endif; ?>

						<?php pojo_breadcrumbs(); ?>

						<?php if ( pojo_is_show_page_title() ) : ?>
							<div class="page-title">
								<h1 class="entry-title"><?php the_title(); ?></h1>
							</div>
						<?php endif; ?>

					</header>
				<?php endif; ?>
				<div class="entry-content">
					<?php if ( ! Pojo_Core::instance()->builder->display_builder() ) : ?>

						<?php if ( get_field( 'display_acf_form' ) ) { ?>

							<?php
								/**
								 * ACF form
								 */
								get_template_part( 'partials/acf-form' );
							?>

						<?php } else { ?>

							<?php
								/**
								 * the_content
								 */
								the_content();
							?>

						<?php } ?>

						<?php pojo_link_pages(); ?>

					<?php endif; ?>
				</div>
			</div>
		</article>
	<?php endwhile;
else :
	pojo_get_content_template_part( 'content', 'none' );
endif;