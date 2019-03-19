<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'pojo_comment' ) ) :
	function pojo_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment; ?>
		<li <?php comment_class( 'media' ); ?>>
			<?php if ( 0 != $args['avatar_size'] ) : // Avatar ?>
				<div class="pull-left">
					<?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
				</div>
			<?php endif; ?>
			<div class="media-body">
				<header class="comment-author vcard">
					<?php echo '<cite class="fn">' . get_comment_author_link() . '</cite>'; ?>
					<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					<?php edit_comment_link( __( '(Edit)', 'pojo' ), '', '' ); ?>
					<time datetime="<?php comment_date( 'c' ); ?>">
						<a href="<?php echo esc_attr( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( __( '%1$s at %2$s', 'pojo' ), get_comment_date(), get_comment_time() ); ?></a>
					</time>
				</header>
				<article id="comment-<?php comment_ID(); ?>">
					<?php if ( $comment->comment_approved == '0' ) : ?>
						<?php pojo_alert( __( 'Your comment is awaiting moderation.', 'pojo' ), false, false, 'block' ); ?>
					<?php endif; ?>
					<section class="comment">
						<?php comment_text() ?>
					</section>
				</article>
			</div>
		</li>
	<?php }
endif;
?>

<?php if ( post_password_required() ) : ?>
	<section id="comments">
		<div class="<?php echo WRAP_CLASSES; ?>">
			<?php pojo_alert( __( 'This post is password protected. Enter the password to view comments.', 'pojo' ), false, false, 'block' ); ?>
		</div><!-- .container -->
	</section><!-- /#comments -->
	<?php
	return;
endif; ?>

<?php if ( have_comments() ) : ?>
	<section id="comments">
		<div class="<?php echo WRAP_CLASSES; ?>">
			<h3 class="title-comments"><span><?php printf( _n( 'One Response', '%1$s Responses', get_comments_number(), 'pojo' ), number_format_i18n( get_comments_number() ), get_the_title() ); ?></span></h3>

			<ol class="commentlist">
				<?php wp_list_comments( array( 'callback' => 'pojo_comment', 'avatar_size' => 80 ) ); ?>
			</ol>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
				<nav id="comments-nav" class="pager">
					<div class="previous"><?php previous_comments_link( __( '&larr; Older comments', 'pojo' ) ); ?></div>
					<div class="next"><?php next_comments_link( __( 'Newer comments &rarr;', 'pojo' ) ); ?></div>
				</nav>

			<?php endif; // check for comment navigation ?>
		</div><!-- .container -->
	</section><!-- /#comments -->
<?php endif; ?>

<?php if ( comments_open() ) : ?>
	<section id="respond">
		<div class="<?php echo WRAP_CLASSES; ?>">
			<h3 class="title-respond"><span><?php comment_form_title( __( 'Leave a Reply', 'pojo' ), __( 'Leave a Reply to %s', 'pojo' ) ); ?></span></h3>

			<p class="cancel-comment-reply"><?php cancel_comment_reply_link(); ?></p>

			<?php if ( get_option( 'comment_registration' ) && ! is_user_logged_in() ) : ?>

				<p><?php printf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'pojo' ), wp_login_url( get_permalink() ) ); ?></p>

			<?php else : ?>

				<form action="<?php echo get_option( 'siteurl' ); ?>/wp-comments-post.php" method="post" id="commentform" class="form">
					<?php if ( is_user_logged_in() ) : ?>

						<p><?php printf( __( 'Logged in as <a href="%s/wp-admin/profile.php">%s</a>.', 'pojo' ), get_option( 'siteurl' ), $user_identity ); ?>
							<a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="<?php __( 'Log out of this account', 'pojo' ); ?>"><?php _e( 'Log out &raquo;', 'pojo' ); ?></a>
						</p>

					<?php else : ?>

					<div class="row">
						<div class="col-sm-6">
							<label class="sr-only" for="author"><?php _e( 'Name', 'pojo' ); if ( $req ) _e( '*', 'pojo' ); ?></label>
							<input class="field" type="text" class="text" name="author" placeholder="<?php _e( 'Name', 'pojo' ); if ( $req ) _e( '*', 'pojo' ); ?>" id="author" value="<?php echo esc_attr( $comment_author ); ?>" <?php if ( $req ) echo "aria-required='true'"; ?> />
						</div>
						<div class="col-sm-6">
							<label class="sr-only"for="email"><?php _e( 'Email', 'pojo' ); if ( $req ) _e( '*', 'pojo' ); ?></label>
							<input class="field" type="email" class="text" name="email" placeholder="<?php _e( 'Email', 'pojo' ); if ( $req ) _e( '*', 'pojo' ); ?>" id="email" value="<?php echo esc_attr( $comment_author_email ); ?>" <?php if ( $req ) echo "aria-required='true'"; ?> />
						</div>
						<div class="col-sm-12">
							<label class="sr-only" for="url"><?php _e( 'Website', 'pojo' ); ?></label>
							<input class="field" type="url" class="text" name="url" placeholder="<?php _e( 'Website', 'pojo' ); ?>" id="url" value="<?php echo esc_attr( $comment_author_url ); ?>" />
						</div>
					</div><!-- .row -->

					<?php endif; ?>

					<label class="sr-only" for="comment"><?php _e( 'Comment', 'pojo' ); ?></label>
					<textarea id="comment" class="field" name="comment" placeholder="<?php _e( 'Enter your comment', 'pojo' ); ?>" cols="10" rows="10"></textarea>
					<input class="button size-large" name="submit" type="submit" tabindex="5" value="<?php _e( 'Submit', 'pojo' ); ?>" />

					<?php comment_id_fields(); ?>
					<?php do_action( 'comment_form', $post->ID ); ?>
				</form>

			<?php endif; // if registration required and not logged in ?>
		</div><!-- .container -->
	</section><!-- /#respond -->
<?php endif; ?>
