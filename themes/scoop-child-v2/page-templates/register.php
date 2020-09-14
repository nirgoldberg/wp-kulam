<?php
/**
 * Template Name: Register
 *
 * @author		Nir Goldberg
 * @package		scoop-child
 * @version		2.0.5
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_field' ) )
	return;

// is website registration form active
$login_registration_website_registration_form	= get_field( 'acf-option_login_registration_website_registration_form', 'option' );
$login_registration_pages						= get_field( 'acf-oprion_login_registration_pages', 'option' );

if ( isset( $login_registration_website_registration_form[ 'status' ] ) && false === $login_registration_website_registration_form[ 'status' ] )
	wp_redirect( $login_registration_pages[ 'login_page' ] );

get_header();

// vars
$strip_image				= get_field( 'acf-option_strip_image', 'option' );
$captcha_instance			= new ReallySimpleCaptcha();
$word						= $captcha_instance->generate_random_word();
$prefix						= mt_rand();
$terms_pre_text				= $login_registration_website_registration_form[ 'terms_pre_text' ];

$terms						= array(
	'term_cookies'				=> array(
		'label'					=> $login_registration_website_registration_form[ 'cookies_label' ],
		'default'				=> $login_registration_website_registration_form[ 'cookies_default_value' ],
	),
	'term_privacy_policy'	=> array(
		'label'					=> $login_registration_website_registration_form[ 'privacy_policy_label' ],
		'default'				=> $login_registration_website_registration_form[ 'privacy_policy_default_value' ],
	),
	'term_terms_of_use'		=> array(
		'label'					=> $login_registration_website_registration_form[ 'terms_of_use_label' ],
		'default'				=> $login_registration_website_registration_form[ 'terms_of_use_default_value' ],
	),
);

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

								<form>
									<table>
										<tbody>
											<tr><td><input type="text" id="uname" name="uname" placeholder="<?php _e( 'User', 'kulam-scoop' ); ?>" /></td></tr>
											<tr><td><input id="uemail" type="text" name="uemail" placeholder="<?php _e( 'Email', 'kulam-scoop' ); ?>" /></td></tr>
											<tr><td><input type="password" id="upass" name="upass" password="<?php _e( 'Password', 'kulam-scoop' ); ?>" placeholder="<?php _e( 'Password', 'kulam-scoop' ); ?>" /></td></tr>

											<?php echo $terms_pre_text ? '<tr><td><p>' . $terms_pre_text . '</p></td></tr>' : ''; ?>

											<tr><td><fieldset>

												<?php foreach ( $terms as $id => $term ) {

													if ( $term[ 'label' ] ) {

														$label		= apply_filters( 'the_content', $term[ 'label' ] );
														$checked	= $term[ 'default' ] ? 'checked="checked"' : '';

														echo '<label for="' . $id . '"><input id="' . $id . '" name="' . $id . '" type="checkbox" value="' . sanitize_text_field( $label ) . '" ' . $checked . '><span>' . $label . '</span></label>';

													}

												} ?>

											</fieldset></td></tr>

											<tr>
												<td><img src="/wp-content/plugins/really-simple-captcha/tmp/<?php echo $captcha_instance->generate_image( $prefix, $word ); ?>" />
													<br />
													<label for="captcha"><h6><?php _e( 'Please enter the following text in the box below:', 'kulam-scoop' ); ?></h6></label> <input id="captcha" type="text" name="captcha" />
												</td>
											</tr>
											<tr>
												<td>
													<input hidden id="prefix" name="prefix" value="<?php echo $prefix ?>" />
													<input type="hidden" id="redirect" name="redirect" />
												</td>
											</tr>
										</tbody>
									</table>

									<button type="submit" class="button submit_reg"><?php _e( 'Register', 'kulam-scoop' ); ?></button>
								</form>

							<?php } else {

								global $current_user;

								?>

								<div class="logged-in">
									<p><?php printf( __( 'Logged in as <b>%s</b>, ', 'kulam-scoop' ), $current_user->user_login ); ?>
									<a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="<?php __( 'Log out of this account', 'kulam-scoop' ); ?>"><?php _e( 'Log out &raquo;', 'kulam-scoop' ); ?></a></p>
								</div>

							<?php } ?>

						</div><!-- .register-wrap -->

						<div class="form-wrap col-sm-6">

							<div class="form-title"><?php _e( 'Already registered?', 'kulam-scoop' ); ?></div>

							<button>
								<a href="<?php echo $login_registration_pages[ 'login_page' ]; ?>">
									<?php _e( 'Login', 'kulam-scoop' ); ?>
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