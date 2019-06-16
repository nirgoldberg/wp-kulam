<?php
/**
 * The template for displaying My Siddur Folder page
 *
 * Template name: My Siddur Folder
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
	$folder		= $_GET[ 'folder' ];
	$data_value	= json_decode( get_user_meta( $user->ID, $folder . $site, true ), true );

	?>

	<div id="popup-to" hidden>
		<form class="sendEmailpopup">
			<div class="close-popup-to">+</div>
			<?php _e( 'Please type or paste email addresses with a space between each one:', 'kulam-scoop' ); ?>
			<input type="email" pattern="[^ @]*@[^ @]*" required class="to" id="idto" />
			<input type="button" value="<?php _e( 'Send', 'kulam-scoop' ); ?>" id="sendTo" />
		</form>
	</div><!-- #popup-to -->

	<div class="share-popup">
		<div class="share-popup-inner">
			<div class="close-share-popup">+</div>
			<h3><?php _e( 'In order to share this folder you must set it public', 'kulam-scoop' ); ?></h3>
			<form>
				<p><?php _e( 'Note! When you share this folder, all content in it becomes public and anyone can see it on the Web. Do you confirm?', 'kulam-scoop' ); ?></p>
				<div class="choosing-items">
					<label><?php _e( 'Yes', 'kulam-scoop' ); ?></label><input type="radio" name="public-folder" class="conf" value="public-folder" /><br>
					<label><?php _e( 'No', 'kulam-scoop' ); ?></label><input type="radio" name="public-folder" class="conf" value="cancel" /><br>
				</div>
				<input type="button" value="<?php _e( 'Done', 'kulam-scoop' ); ?>" class="save-sharing-choosing" />
			</form>
		</div>
	</div><!-- .share-popup -->

	<div class="popup-new-folder" id="popup-settings">
		<form class="popup-form-setting">
			<input type="text" value="<?php echo $folder; ?>" id="name-folder-hide" />
			<div class="close-popup-settings">+</div>
			<div class="form-body">
				<h3><?php _e( 'Folder Name', 'kulam-scoop' ); ?></h3>
				<span>*<?php _e( 'Here you can rename the folder', 'kulam-scoop' ); ?></span>
				<div class="notice"><?php _e( 'Allowed characters: digits, letters, spaces and dashes', 'kulam-scoop' ); ?></div>
				<input type="text" value="<?php echo $folder; ?>" id="name-new-folder" />
				<div id="wrap-checkbox">
					<input type="checkbox" id="del" />
					<label class="labal-del" for="del"><?php _e( 'Delete this folder?', 'kulam-scoop' ); ?></label>
				</div>

				<input type="checkbox" id="is-public" name="is-public" value="is-public">
				<label for="is-public"><?php _e( 'Public folder?', 'kulam-scoop' ); ?></label>
				<input type="button" class="save-settings" value="<?php _e( 'Save settings', 'kulam-scoop' ); ?>" />
			</div>
		</form>
	</div><!-- .popup-new-folder#popup-settings -->

	<div class="popup-new-folder" id="popup-link-copy">
		<form class="popup-form-setting">
			<div class="close-popup-link">+</div>
			<div class="form-body">
				<h4><?php _e( 'Link to share', 'kulam-scoop' ); ?></h4>
				<input type="text" id="link_to_copy">
			</div>
		</form>
	</div><!-- .popup-new-folder#popup-link-copy -->

	<?php
		/**
		 * Loader
		 */
		get_template_part( 'partials/loader' );
	?>

	<div class="folder-wrap">

		<h1 class="entry-title"><?php echo $folder; ?></h1>
		<div class="settings"><i class="fa fa-cog" aria-hidden="true"></i></div>
		<i class="fa fa-arrow-circle-o-right my-siddur" aria-hidden="true"></i>

		<?php if ( $data_value ) { ?>

			<div class="shere-section">
				<a class="entry-mail pojo-tooltip" id="send">
					<span class="fa fa-envelope-o"></span>
				</a>
				<a class="entry-facebook pojo-tooltip" id="facebook-share" target="_blank">
					<span class="fa fa-facebook"></span>
				</a>
				<a class="entry-twitter pojo-tooltip" id="twitter-share" target="_blank">
					<span class="fa fa-twitter"></span>
				</a>
				<a class="entry-whatsaap pojo-tooltip" id="whatsapp-share">
					<span class="fa fa-whatsapp"></span>
				</a>
				<a class="entry-telegram pojo-tooltip" id="telegram-share">
					<span class="fa fa-telegram"></span>
				</a>
				<a class="entry-clipboard pojo-tooltip" id="clipboard-share">
					<span class="fa fa-clipboard"></span>
				</a>
			</div>

			<?php $args = array(
				'post_type'			=> 'post',
				'post__in'			=> (array)$data_value,
				'posts_per_page'	=> -1,
				'orderby'			=> 'post__in'
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

		<?php } ?>

	</div><!-- .folder-wrap -->

<?php endif;

get_footer();