<?php
/**
 * Main recent posts layout
 *
 * @author      Nir Goldberg
 * @package     scoop-child/partials/main
 * @version     2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_field' ) )
	return;

/**
 * Variables
 */
$title			= get_sub_field( 'title' );
$sub_title		= get_sub_field( 'sub_title' );
$category		= get_sub_field( 'posts_category' );
$top_padding	= get_sub_field( 'top_padding' );
$bottom_padding	= get_sub_field( 'bottom_padding' );

if ( ! $title || ! $category )
	return;

$layout_style	.= $top_padding ? 'padding-top:' . $top_padding . 'px;' : '';
$layout_style	.= $bottom_padding ? 'padding-bottom:' . $bottom_padding . 'px;' : '';

// get posts
$posts = array();

$args = array(
	'category__in'		=> array( $category ),
	'posts_per_page'	=> 4,
);
$query = new WP_Query( $args );

if ( $query->have_posts() ) : while( $query->have_posts() ) : $query->the_post();

	// get post thumbnail ID
	$thumbnail_id = get_post_thumbnail_id( $post->ID );

	$image_html = '';

	if ( ! empty( $thumbnail_id ) ) {

		$attachment_src = wp_get_attachment_image_src( $thumbnail_id, 'large' );

		if ( $attachment_src ) {
			$attachment_url = $attachment_src[0];
			$image_title = get_the_title();
			$image_html = sprintf( '<div class="post-image"><img src="%1$s" alt="%2$s" /></div>', $attachment_url, esc_attr( $image_title ) );
		}

	}

	if ( ! empty( $image_html ) ) {
		$posts[] =	'<div class="col-md-3 col-sm-6">' .
						'<div class="post-wrap">' .
							$image_html .
							'<div class="post-meta">' .
								'<div class="title">' . get_the_title() . '</div>' .
								'<div class="more">' . __( 'Read more', 'scoop-child' ) . '</div>' .
							'</div>' .
						'</div>' .
					'</div>';
	}

endwhile; endif; wp_reset_postdata();

if ( ! $posts )
	return;

?>

<div class="main-recent-posts" <?php echo $layout_style ? 'style="' . $layout_style . '"' : ''; ?>>
	<div class="recent-posts-wrap container">

		<div class="main-layout-title-wrap">

			<h2><?php echo $title; ?></h2>
			<?php echo $sub_title ? '<div class="sub-title">' . $sub_title . '</div>' : ''; ?>

		</div>

		<div class="posts-wrap row">

			<?php echo implode( '', $posts ); ?>

		</div>

		<div class="more-posts">
			<a href="<?php echo get_term_link( $category ); ?>"><span><?php _e( 'View all', 'scoop-child' ); ?></span></a>
		</div>

	</div>
</div><!-- .main-recent-posts -->