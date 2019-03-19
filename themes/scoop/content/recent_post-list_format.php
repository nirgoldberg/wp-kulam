<?php
/**
 * Recent Post: Default (List)
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $_current_widget_instance;

$categories       = '';
$categories_terms = get_the_category();
if ( ! empty( $categories_terms ) && ! is_wp_error( $categories_terms ) ) :
	$categories = wp_list_pluck( $categories_terms, 'name' );
	$categories = $categories[0];
endif;

$format_icon_class = 'format-icon-hide';
if ( 'show' === $_current_widget_instance['metadata_format_icon'] ) :
	$format_icon_class = 'format-icon-show';
endif;
?>
<div <?php post_class( 'recent-post media list-format ' . $format_icon_class ); ?>>
	<?php if ( 'show' === $_current_widget_instance['thumbnail'] ) : ?>
		<?php if ( has_post_format( 'gallery' ) ) :
			$gallery_items = explode( ',', atmb_get_field( 'format_gallery' ) );
			$slides = array();
			if ( ! empty( $gallery_items ) ) :
				foreach ( $gallery_items as $item_id ) :
					$attachment     = get_post( $item_id );
					$attachment_url = Pojo_Thumbnails::get_attachment_image_src( $item_id, array( 'width' => '650', 'height' => '435' ) );
					if ( ! empty( $attachment_url ) )
						$slides[] = sprintf(
							'<li><a href="%1$s"><img src="%2$s" title="%3$s" alt="%3$s" /></a></li>',
							esc_attr( get_permalink() ),
							esc_attr( $attachment_url ),
							esc_attr( $attachment->post_excerpt )
						);
				endforeach;
				if ( ! empty( $slides ) ) :
					echo '<div class="pull-right"><ul class="pojo-simple-gallery">' . implode( '', $slides ) . '</ul></div>';
				endif;
			endif;
		elseif ( has_post_format( 'video' ) ) : ?>
			<?php if ( $video_link = atmb_get_field( 'format_video_link' ) ) : ?>
				<div class="pull-right">
					<div class="custom-embed" data-save_ratio="<?php echo atmb_get_field( 'format_aspect_ratio' ); ?>"><?php echo wp_oembed_get( $video_link, wp_embed_defaults() ); ?></div>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<?php $image_url = Pojo_Thumbnails::get_post_thumbnail_url( array( 'width' => '650', 'height' => '435', 'crop' => true, 'placeholder' => true ) ); ?>
			<div class="pull-right">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark" class="image-link">
					<img src="<?php echo $image_url; ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="media-object" />
					<?php if ( ! empty( $categories ) && 'show' === $_current_widget_instance['metadata_category'] ) : ?>
						<div class="category-label"><div><span><?php echo $categories; ?></span></div></div>
					<?php endif; ?>
				</a>
				<?php if ( has_post_format( 'audio' ) ) : ?>
					<?php echo wp_audio_shortcode( array( 'mp3' => atmb_get_field( 'format_mp3_url' ), 'ogg' => atmb_get_field( 'format_oga_url' ) ) ); ?>
					<div class="custom-embed"><?php echo wp_oembed_get( atmb_get_field( 'format_embed_url' ), wp_embed_defaults() ); ?></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
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