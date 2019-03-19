<?php
/**
 * Posts Group: Featured Post // List Posts Aside
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $_current_widget_instance;

$categories       = '';
$categories_terms = get_the_category();
if ( ! empty( $categories_terms ) && ! is_wp_error( $categories_terms ) ) :
	$categories = wp_list_pluck( $categories_terms, 'name' );
	$categories = $categories[0];
endif;

if ( $_current_widget_instance['is_first_item'] ) :
	$format_icon_class = 'format-icon-hide';
	if ( 'show' === $_current_widget_instance['featured_metadata_format_icon'] ) :
		$format_icon_class = 'format-icon-show';
	endif;
	?>
	<div <?php post_class( 'post-group grid-item featured-post col-sm-6 ' . $format_icon_class ); ?>>
		<?php if ( 'show' === $_current_widget_instance['featured_thumbnail'] && $image_url = Pojo_Thumbnails::get_post_thumbnail_url( array( 'width' => '600', 'height' => '385', 'crop' => true, 'placeholder' => true ) ) ) : ?>
			<div class="entry-thumbnail">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark" class="image-link">
					<img src="<?php echo $image_url; ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="media-object" />
					<?php if ( ! empty( $categories ) && 'show' === $_current_widget_instance['featured_metadata_category'] ) : ?>
						<div class="category-label"><div><span><?php echo $categories; ?></span></div></div>
					<?php endif; ?>
				</a>
				<div class="entry-meta">
					<?php if ( 'show' === $_current_widget_instance['featured_metadata_date'] ) : ?>
						<span><time datetime="<?php the_time('o-m-d'); ?>" class="entry-date date published updated"><?php echo get_the_date(); ?></time></span>
					<?php endif; ?>
					<?php if ( 'show' === $_current_widget_instance['featured_metadata_time'] ) : ?>
						<span class="entry-time"><?php echo get_the_time(); ?></span>
					<?php endif; ?>
					<?php if ( 'show' === $_current_widget_instance['featured_metadata_comments'] ) : ?>
						<span class="entry-comment"><?php comments_popup_link( __( 'No Comments', 'pojo' ), __( 'One Comment', 'pojo' ), __( '% Comments', 'pojo' ), 'comments' ); ?></span>
					<?php endif; ?>
					<?php if ( 'show' === $_current_widget_instance['featured_metadata_author'] ) : ?>
						<span class="entry-user vcard author"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author" class="fn"><?php echo get_the_author(); ?></a></span>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="caption">
			<?php if ( 'show' === $_current_widget_instance['featured_show_title'] ) : ?>
				<h3 class="grid-heading entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<?php endif; ?>
			<?php if ( 'show' === $_current_widget_instance['featured_except'] ) : ?>
				<div class="entry-excerpt">
					<p><?php echo pojo_get_words_limit( get_the_excerpt(), $_current_widget_instance['featured_except_length_words'] ); ?></p>
				</div>
			<?php endif; ?>
			<?php if ( 'show' === $_current_widget_instance['featured_metadata_readmore'] ) : ?>
				<a href="<?php the_permalink(); ?>" class="read-more"><?php echo  ! empty( $_current_widget_instance['featured_text_readmore_mode'] ) ? $_current_widget_instance['featured_text_readmore_mode'] : __( 'Read More &raquo;', 'pojo' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
<?php else : ?>
	<div <?php post_class( 'post-group media list-item col-sm-6' ); ?>>
		<div class="item-inner">
			<?php if ( 'show' === $_current_widget_instance['thumbnail'] && $image_url = Pojo_Thumbnails::get_post_thumbnail_url( array( 'width' => '100', 'height' => '75', 'crop' => true, 'placeholder' => true ) ) ) : ?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark" class="image-link pull-left">
					<img src="<?php echo $image_url; ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="media-object" />
				</a>
			<?php endif; ?>
			<div class="media-body">
				<?php if ( 'show' === $_current_widget_instance['show_title'] ) : ?>
					<h3 class="media-heading entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<?php endif; ?>
				<div class="entry-meta">
					<?php if ( 'show' === $_current_widget_instance['metadata_date'] ) : ?>
						<span><time datetime="<?php the_time('o-m-d'); ?>" class="entry-date date published updated"><?php echo get_the_date(); ?></time></span>
					<?php endif; ?>
					<?php if ( 'show' === $_current_widget_instance['metadata_time'] ) : ?>
						<span class="entry-time"><?php echo get_the_time(); ?></span>
					<?php endif; ?>
					<?php if ( 'show' === $_current_widget_instance['metadata_comments'] ) : ?>
						<span class="entry-comment"><?php comments_popup_link( __( 'No Comments', 'pojo' ), __( 'One Comment', 'pojo' ), __( '% Comments', 'pojo' ), 'comments' ); ?></span>
					<?php endif; ?>
					<?php if ( 'show' === $_current_widget_instance['metadata_author'] ) : ?>
						<span class="entry-user vcard author"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author" class="fn"><?php echo get_the_author(); ?></a></span>
					<?php endif; ?>
				</div>
				<?php if ( 'show' === $_current_widget_instance['except'] ) : ?>
					<div class="entry-excerpt">
						<p><?php echo pojo_get_words_limit( get_the_excerpt(), $_current_widget_instance['except_length_words'] ); ?></p>
					</div>
				<?php endif; ?>
				<?php if ( 'show' === $_current_widget_instance['metadata_readmore'] ) : ?>
					<a href="<?php the_permalink(); ?>" class="read-more"><?php echo  ! empty( $_current_widget_instance['text_readmore_mode'] ) ? $_current_widget_instance['text_readmore_mode'] : __( 'Read More &raquo;', 'pojo' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif;