<?php
/**
 * The template for displaying My Siddur page
 *
 * Template name: My Siddur
 *
 * @author      Nir Goldberg
 * @package     scoop-child
 * @version     1.2.0
 */
get_header();

if ( is_user_logged_in() ) :

	/**
	 * Variables
	 */
	$site		= get_current_blog_id();
	$user		= wp_get_current_user();
	$folders	= json_decode( get_user_meta( $user->ID, "nameFolder" . $site, true ), true );
	$sidur		= json_decode( get_user_meta( $user->ID, "sidur" . $site, true ) );

	if ( $folders ) { ?>

		<div class="popup-new-folder" id="all-folders" hidden>
			<form class="popup-form-div">
				<div class="close-popup-folders">+</div>
				<div class="all-input">

					<?php foreach ( $folders as $folder ) { ?>

						<input type="radio" value="<?php echo $folder; ?>" id="<?php echo $folder; ?>" name="option" />
						<span><?php echo $folder; ?></span></br>

					<?php } ?>

				</div>
				<input type="submit" class="save-in-folder" />
			</form>
		</div><!-- .popup-new-folder#all-folders -->

	<?php } ?>

	<div class="popup-new-folder" id="new-fold" hidden>
		<div class="popup-form">
			<div class="close-popup">+</div>
			<label class="name"><?php _e( 'Folder Name', 'kulam-scoop' ); ?></label>
			<input type="text" id="name-folder" >
			<input type="submit" class="add-save-folder" value="<?php _e( 'Add', 'kulam-scoop' ); ?>" />
		</div>
	</div><!-- .popup-new-folder#new-fold -->

	<div class="siddur-wrap">

		<button class="add-new-folder"></button>

		<?php if ( $folders ) { ?>

			<div class="wrap-all-folders">

				<?php foreach ( $folders as $folder ) { ?>

					<div class="single-folder-wrap">
						<a href="<?php echo home_url( '/my-siddur-folder' ); ?>?folder=<?php echo urlencode( $folder ); ?>">
							<div class="link-folder">
								<div class="folder"><?php echo esc_html( $folder ); ?></div>
							</div>
						</a>
					</div>

				<?php } ?>

			</div><!-- .wrap-all-folders -->

		<?php }

		if ( $sidur ) {

			$args = array(
				'post_type'	=> 'post',
				'post__in'	=> (array)$sidur,
				'posts_per_page'	=> -1,
				'orderby'			=> 'post__in'
			);
			$siddur_query = new WP_Query( $args ); ?>

			<div id="primary">
				<div id="content" role="main">

					<?php if ( $siddur_query->have_posts() ) : while ( $siddur_query->have_posts() ) : $siddur_query->the_post();

						get_template_part( 'content/content', 'grid_three' );

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