<?php
/**
 * Template Name: Login
 *
 * @author		Nir Goldberg
 * @package		scoop-child
 * @version		2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_field' ) )
	return;

get_header();

// vars
$strip_image					= get_field( 'acf-option_strip_image', 'option' );
$login_registration_pages		= get_field( 'acf-oprion_login_registration_pages', 'option' );
$login_registration_buttons		= get_field( 'acf-option_login_registration_buttons', 'option' );
$login_registration_login_form	= get_field( 'acf-oprion_login_registration_login_form', 'options' );
$page_showing					= basename( $_SERVER[ 'REQUEST_URI' ] );

do_action( 'pojo_get_start_layout', 'page', get_post_type(), '' );

if ( have_posts() ) :
	while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-page">
				<?php if ( po_breadcrumbs_need_to_show() || pojo_is_show_page_title() ) : ?>
					<header class="entry-header">

						<?php if ( $strip_image && ! is_front_page() && ! is_home() ) : ?>
							<div class="strip-image">
								<img src="<?php echo $strip_image[ 'url' ]; ?>" alt="<?php echo $strip_image[ 'alt' ]; ?>" />
							</div>
						<?php endif; ?>

						<?php pojo_breadcrumbs(); ?>

						<?php if ( pojo_is_show_page_title() ) : ?>
							<div class="page-title">
								<h1 class="entry-title"><?php the_title(); ?></h1>
							</div>
						<?php endif; ?>

					</header>
				<?php endif; ?>
				<div class="entry-content">
					<div class="form-section row">

						<div class="form-wrap col-sm-6">

							<?php if ( ! is_user_logged_in() ) { ?>

								<div class="form-title"><?php _e( 'Your Details', 'kulam-scoop' ); ?></div>

								<div class="error-msg">

									<?php if ( strpos( $page_showing, 'failed' ) !== false ) {

										printf( '<strong>%s:</strong> %s', __( 'ERROR', 'kulam-scoop' ), __( 'Invalid username and/or password.', 'kulam-scoop' ) );

									} elseif ( strpos( $page_showing, 'blank' ) !== false ) {

										printf( '<strong>%s:</strong> %s', __( 'ERROR', 'kulam-scoop' ), __( 'Username and/or Password is empty.', 'kulam-scoop' ) );

									} ?>

								</div>

								<form name="loginform" id="loginform" action="<?php echo wp_login_url(); ?>" method="post">
									<input type="text" name="log" id="user_login" class="input" value="" size="20" autocapitalize="off" placeholder="<?php _e( 'Username or Email Address', 'kulam-scoop' ); ?>" >
									<input type="password" name="pwd" id="user_pass" class="input password-input" value="" size="20" placeholder="<?php _e( 'Password', 'kulam-scoop' ); ?>" >
									<button type="submit" name="wp-submit" id="wp-submit" class="button"><?php _e( 'Login', 'kulam-scoop' ); ?></button>
									<input type="hidden" name="redirect_to" value="<?php echo get_permalink(); ?>">
									<input type="hidden" name="testcookie" value="1">
								</form>

							<?php } else {

								global $current_user;

								?>

								<div class="logged-in">
									<p><?php printf( __( 'Logged in as <b>%s</b>, ', 'kulam-scoop' ), $current_user->user_login ); ?>
									<a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="<?php __( 'Log out of this account', 'kulam-scoop' ); ?>"><?php _e( 'Log out &raquo;', 'pojo' ); ?></a></p>
								</div>

							<?php } ?>

						</div><!-- .login-wrap -->

						<div class="form-wrap col-sm-6">

							<div class="form-title"><?php _e( 'Not registered?', 'kulam-scoop' ); ?></div>

							<p class="alternate-desc"><?php echo $login_registration_login_form[ 'registration_invitation_message' ]; ?></p>

							<button class="button">
								<a href="<?php echo $login_registration_pages[ 'hmembership_register_page' ]; ?>">
									<?php echo $login_registration_buttons[ 'member_registration' ]; ?>
								</a>
							</button>
							<button class="button">
								<a href="<?php echo $login_registration_pages[ 'register_page' ]; ?>">
									<?php echo $login_registration_buttons[ 'normal_registration' ]; ?>
								</a>
							</button>

						</div><!-- .login-wrap -->

					</div>
				</div>
			</div>
		</article>
	<?php endwhile;
else :
	pojo_get_content_template_part( 'content', 'none' );
endif;

do_action( 'pojo_get_end_layout', 'page', get_post_type(), '' );

get_footer();