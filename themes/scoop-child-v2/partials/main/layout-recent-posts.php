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

// vars
$title			= get_sub_field( 'title' );
$sub_title		= get_sub_field( 'sub_title' );
$category		= get_sub_field( 'posts_category' );
$top_padding	= get_sub_field( 'top_padding' );
$bottom_padding	= get_sub_field( 'bottom_padding' );
$user_state		= kulam_get_current_user_state();

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

// modify query according to user state ( hmembership_member | logged_in | public )
if ( in_array( $user_state, array( 'logged_in', 'public' ) ) ) {

	// setup meta_query
	$args[ 'meta_query' ] = array(
		'relation'		=> 'OR',
		array(
			'key'		=> 'acf-post_restrict_post',
			'compare'	=> 'NOT EXISTS',
		),
	);

	if ( 'logged_in' == $user_state ) {
		$value = array( 'public', 'logged_in' );
	}
	else {
		$value = array( 'public' );
	}

	$args[ 'meta_query' ][] = array(
		'key'		=> 'acf-post_restrict_post',
		'value'		=> $value,
		'compare'	=> 'IN',
	);

}

$query = new WP_Query( $args );

if ( $query->have_posts() ) : while( $query->have_posts() ) : $query->the_post();

	$posts[] = kulam_get_post();

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