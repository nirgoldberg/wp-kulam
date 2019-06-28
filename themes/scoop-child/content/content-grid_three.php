<?php
/**
 * Content: Grid - Three Columns
 *
 * @author      Nir Goldberg
 * @package     scoop-child
 * @version     1.3.6
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Variables
 */
global $_pojo_parent_id;

$site_id			= get_current_blog_id();
$post_id			= get_the_ID();
$categories			= '';
$categories_terms	= get_the_category();

if ( ! empty( $categories_terms ) && ! is_wp_error( $categories_terms ) ) :
	$categories = wp_list_pluck( $categories_terms, 'name' );
	$categories = $categories[0];
endif;

$format_icon_class = 'format-icon-hide';
if ( po_archive_metadata_show( 'format_icon', $_pojo_parent_id ) ) :
	$format_icon_class = 'format-icon-show';
endif;
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( apply_filters( 'pojo_post_classes', array( 'grid-item grid-three col-sm-4 col-xs-12', $format_icon_class ), get_post_type() ) ); ?>>
	<div class="item-inner">
		<?php if ( $image_url = Pojo_Thumbnails::get_post_thumbnail_url( array( 'width' => '460', 'height' => '295', 'crop' => true, 'placeholder' => true ) ) ) : ?>
			<div class="entry-thumbnail">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark" class="image-link">
					<img src="<?php echo $image_url; ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="media-object thambnail" />
					<?php if ( ! empty( $categories ) && po_archive_metadata_show( 'category', $_pojo_parent_id ) ) : ?>
						<div class="category-label"><div><span><?php echo $categories; ?></span></div></div>
					<?php endif; ?>
				</a>
				<div class="entry-meta">
					<?php if ( po_archive_metadata_show( 'date', $_pojo_parent_id ) ) : ?>
						<span><time datetime="<?php the_time('o-m-d'); ?>" class="entry-date date published updated"><a href="<?php echo get_month_link( get_the_time('Y'), get_the_time('m') ); ?>"><?php echo get_the_date(); ?></a></time></span>
					<?php endif; ?>
					<?php if ( po_archive_metadata_show( 'time', $_pojo_parent_id ) ) : ?>
						<span class="entry-time"><?php echo get_the_time(); ?></span>
					<?php endif; ?>
					<?php if ( po_archive_metadata_show( 'comments', $_pojo_parent_id ) ) : ?>
						<span class="entry-comment"><?php comments_popup_link( __( 'No Comments', 'pojo' ), __( 'One Comment', 'pojo' ), __( '% Comments', 'pojo' ), 'comments' ); ?></span>
					<?php endif; ?>
					<?php if ( po_archive_metadata_show( 'author', $_pojo_parent_id ) ) : ?>
						<span class="entry-user vcard author"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author" class="fn"><?php echo get_the_author(); ?></a></span>
					<?php endif; ?>

					<span class="favorite">
						<?php

							if ( ! ( is_user_logged_in() ) ) { ?>

								<span class="wrap-heart"><a class="siddur-button" data-toggle="modal" data-target="#modal-login" data-redirect="#" data-show-pre-text="true"><i class="fa fa-heart-o" aria-hidden="true"></i></a></span>

							<?php } else {

								$user_id	= get_current_user_id();
								$favorite	= get_user_meta( $user_id, 'favorite' . $site_id , true );

								if ( $favorite ) {

									$favorite			= json_decode( $favorite, true );
									$in_favorite		= $favorite && in_array( $post_id, $favorite );
									$btn_action			= $in_favorite ? 'remove_from_siddur' : 'add_to_siddur';
									$btn_toggle_action	= $in_favorite ? 'add_to_siddur' : 'remove_from_siddur'; ?>

									<span class="wrap-heart"><a href='#' class="siddur-button siddur-toggle-button" data-post-id="<?php echo $post_id; ?>" data-action="<?php echo $btn_action; ?>" data-toggle-action="<?php echo $btn_toggle_action; ?>"><i class="fa <?php echo $in_favorite ? 'fa-heart' : 'fa-heart-o'; ?>" aria-hidden="true"></i></a></span>

								<?php }

							}

						?>
					</span><!-- .favorite -->

					<?php if ( $page_template && in_array( $page_template, array( 'template-siddur.php', 'template-siddur-folder.php' ) ) ) {

						if ( 'template-siddur.php' == $page_template && $folders ) {
							$icon_class	= 'fa-plus';
							$tooltip	= __( 'Add to folder', 'kulam-scoop' );
						}
						elseif ( 'template-siddur-folder.php' == $page_template ) {
							$icon_class	= 'fa-minus';
							$tooltip	= __( 'Remove from folder', 'kulam-scoop' );
						}

						if ( $icon_class ) { ?>

							<span class="folders-assignment <?php echo substr( $page_template, 0, -4 ); ?>">
								<a class="pojo-tooltip" id="folders-assignment-post-<?php echo $post_id; ?>" title="<?php echo $tooltip; ?>">
									<i class="fa <?php echo $icon_class; ?>"></i>
								</a>
							</span><!-- .folders-assignment -->

						<?php }

					} ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="caption">
			<h3 class="grid-heading entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<?php po_print_archive_excerpt( $_pojo_parent_id ); ?>
			
			<?php

				//avg of rating
				//general
				$generalrate			= get_post_meta( $post_id, 'sumGeneral' . $site_id );
				$countgeneral			= get_post_meta( $post_id, 'countratingGeneral' . $site_id );

				if ( $generalrate[0] && $countgeneral[0] )
					$val1 = $generalrate[0]/$countgeneral[0];
				else
					$val1 = 0;

				//religiosity
				$religiosityrate		= get_post_meta( $post_id, 'sumReligiosity' . $site_id );
				$countreligiosity		= get_post_meta( $post_id, 'countratingReligiosity' . $site_id );

				if ( $religiosityrate[0] && $countreligiosity[0] )
					$val2 = $religiosityrate[0]/$countreligiosity[0];
				else
					$val2 = 0;

				//authentic
				$authenticrate			= get_post_meta( $post_id, 'sumAuthentic' . $site_id );
				$countratingAuthentic	= get_post_meta( $post_id, 'countratingAuthentic' . $site_id );

				if ( $authenticrate[0] && $countratingAuthentic[0] )
					$val3 = $authenticrate[0]/$countratingAuthentic[0];
				else
					$val3 = 0;

			?>
		</div>
	</div>
</div>