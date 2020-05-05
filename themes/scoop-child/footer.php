<?php
/**
 * Footer
 *
 * @author		Nir Goldberg
 * @package		scoop-child
 * @version		1.7.9
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
					<div class="pull-left-copyright">&copy; <?php bloginfo( 'name' ); ?> All rights reserved</div>
					<div class="pull-right-copyright"><img src="<?php echo get_theme_mod( 'image_logo' ); ?>" alt="<?php bloginfo( 'name' ); ?>" /></div>
				</div>
			</div><!-- .container -->
		</footer>
	<?php endif; // end blank page ?>
<?php po_change_loop_to_parent(); ?>

</div><!-- #container -->
<?php wp_footer(); ?>
</body>
</html>
