<?php
/**
 * Footer
 *
 * @author		Nir Goldberg
 * @package		scoop-child
 * @version		1.7.23
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

			</div><!-- #content -->
		</div><!-- .container -->
	</div><!-- #primary -->

<?php po_change_loop_to_parent( 'change' ); ?>
	<?php if ( ! pojo_is_blank_page() && ! pojo_elementor_theme_do_location( 'footer' ) ) : ?>
		<?php get_sidebar( 'footer' ); ?>
		<footer id="footer-copyright" role="contentinfo">
			<div class="<?php echo WRAP_CLASSES; ?>">
				<div class="content-copyright">
					<div class="pull-left-copyright">&copy; <a href="<?php bloginfo( 'url' ); ?>" ><?php bloginfo( 'name' ); ?></a> <?php _e( 'All rights reserved', 'kulam-scoop' ); ?></div>
					<div class="pull-right-copyright"><img src="<?php echo get_theme_mod( 'image_logo' ); ?>" alt="<?php bloginfo( 'name' ); ?>" /></div>
				</div>
			</div><!-- .container -->
		</footer>
	<?php endif; // end blank page ?>
<?php po_change_loop_to_parent(); ?>

</div><!-- #container -->

<?php

	// Globals
	global $globals;

	if ( count( $globals['_galleries'] ) ) {

		get_template_part( 'partials/footer/footer-photoswipe' );
		wp_enqueue_style ( 'photoswipe' );
		wp_enqueue_style ( 'photoswipe-default-skin' );
		wp_enqueue_script( 'photoswipe' );
		wp_enqueue_script( 'photoswipe-ui-default' );

	}

	$strings = array(
		'copied_to_clipboard'	=> __( 'Link has been copied to clipboard. You may share it anywhere', 'kulam-scoop' ),
	);

?>

<script>

	var js_globals = {};
	js_globals.galleries	= '<?php echo json_encode( $globals['_galleries'] ); ?>';
	js_globals.strings		= '<?php echo json_encode( $strings ); ?>';

</script>

<?php wp_footer(); ?>
</body>
</html>
