<?php
/**
 * The template for displaying My Siddur page
 *
 * Template name: My Siddur
 *
 * @author		Nir Goldberg
 * @package		scoop-child
 * @version		1.7.16
 */
get_header();

/**
 * page content
 */
the_content();

if ( is_user_logged_in() ) :

	/**
	 * Variables
	 */
	$site_id		= get_current_blog_id();
	$user_id		= get_current_user_id();
	$folders		= get_user_meta( $user_id, 'nameFolder' . $site_id, true );
	$folders		= $folders ? json_decode( $folders, true ) : array();
	$siddur			= get_user_meta( $user_id, 'sidur' . $site_id, true );
	$siddur			= $siddur ? json_decode( $siddur, true ) : array();
	$page_template	= basename( get_page_template() );

	if ( $folders ) { ?>

		<div class="popup popup-new-folder" id="all-folders" hidden>
			<form class="add-to-folder-form popup-form-div">
				<div class="close-popup-folders">+</div>
				<div class="all-input">

					<?php foreach ( $folders as $folder ) {

						$folder_name = ( is_array( $folder ) ) ? $folder[ 'name' ] : $folder;

						?>

						<input type="radio" value="<?php echo $folder_name; ?>" id="<?php echo $folder_name; ?>" name="option" />
						<span><?php echo $folder_name; ?></span></br>

					<?php } ?>

				</div>
				<input type="hidden" class="post-ids" data-post-ids="" />
				<input type="submit" class="add-to-folder" />
			</form>
		</div><!-- .popup-new-folder#all-folders -->

	<?php } ?>

	<div class="popup popup-new-folder" id="new-fold" hidden>
		<div class="popup-form">
			<div class="close-popup">+</div>
			<label class="name"><?php _e( 'Folder Name', 'kulam-scoop' ); ?></label>
			<div class="notice"><?php _e( 'Allowed characters: digits, letters, spaces and dashes', 'kulam-scoop' ); ?></div>
			<input type="text" id="name-folder" >
			<textarea rows="4" id="folder-description" placeholder="<?php _e( 'Folder description', 'kulam-scoop' ); ?>"></textarea>
			<input type="submit" class="add-save-folder" value="<?php _e( 'Add', 'kulam-scoop' ); ?>" />
		</div>
	</div><!-- .popup-new-folder#new-fold -->

	<div class="siddur-wrap">

		<button class="add-new-folder"></button>

		<?php if ( $folders ) { ?>

			<div class="wrap-all-folders">

				<?php foreach ( $folders as $folder ) {

					$folder_name = ( is_array( $folder ) ) ? $folder[ 'name' ] : $folder;

					?>

					<div class="single-folder-wrap">
						<a href="<?php echo home_url( '/my-siddur-folder' ); ?>?folder=<?php echo urlencode( $folder_name ); ?>">
							<div class="link-folder">
								<div class="folder"><?php echo esc_html( $folder_name ); ?></div>
							</div>
						</a>
					</div>

				<?php } ?>

			</div><!-- .wrap-all-folders -->

		<?php }

		if ( $siddur ) {

			$args = array(
				'post_type'			=> 'post',
				'post__in'			=> $siddur,
				'posts_per_page'	=> -1,
				'orderby'			=> 'post__in',
			);
			$siddur_query = new WP_Query( $args ); ?>

			<div id="primary">
				<div id="content" role="main">

					<?php if ( $siddur_query->have_posts() ) : while ( $siddur_query->have_posts() ) : $siddur_query->the_post();

						include( locate_template( 'content/content-grid_three.php' ) );

					endwhile; endif;

					wp_reset_postdata(); ?>

				</div><!-- #content -->
			</div><!-- #primary -->

			<?php
				/**
				 * Loader
				 */
				get_template_part( 'partials/loader' );
			?>

		<?php } ?>

	</div><!-- .siddur-wrap -->

<?php endif;

get_footer();