<?php
/**
 * The template for displaying My Siddur Single Public Folder page
 *
 * Template name: Single Public Folder
 *
 * @author      Nir Goldberg
 * @package     scoop-child
 * @version     1.2.0
 */
get_header();

/**
 * Variables
 */
$folder	= isset( $_GET[ 'folder' ] )	? str_replace( '_', ' ', $_GET[ 'folder' ] )	: '';
$user	= isset( $_GET[ 'u' ] )			? $_GET[ 'u' ]									: '';
$site	= isset( $_GET[ 'si' ] )		? $_GET[ 'si' ]									: '';
$lang	= get_locale();

if ( $folder && $user && $site ) :

	global $wpdb;
	$table_name = $wpdb->prefix . 'public_folders';
	$sqlQuery = "SELECT * FROM $table_name WHERE folder_name = '$folder' AND id_user = '$user' AND id_site = '$site' AND lang = '$lang'";
	$result = $wpdb->get_results( $sqlQuery, OBJECT );

	if ( $result ) { ?>

		<h1><?php echo $folder; ?></h1>

		<?php $data_value = json_decode( get_user_meta( $user, $folder . $site, true ), true );

		if( $data_value ) {

			$args = array(
				'post_type'	=> 'post',
				'post__in'	=> (array)$data_value
			);
			$data_value_query = new WP_Query( $args ); ?>

			<div id="primary">
				<div id="content" role="main">

					<?php while ( $data_value_query->have_posts() ) : $data_value_query->the_post();

						get_template_part( 'content/content', 'grid_three' );

					endwhile;

					wp_reset_postdata(); ?>

				</div><!-- #content -->
			</div><!-- #primary -->

		<?php }

	}

endif;

get_footer();