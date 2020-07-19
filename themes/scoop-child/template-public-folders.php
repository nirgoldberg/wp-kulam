<?php
/**
 * The template for displaying the Public folders page.
 *
 * Template name:public-folders
 *
 * @author      Nir Goldberg
 * @package     scoop-child
 * @version     1.7.27
 */
get_header();

if ( function_exists( 'get_field' ) && false !== get_field( 'acf-option_my_siddur_activate_module', 'option' ) ) : ?>

	<h1><?php echo the_title();?></h1>

	<?php

		$lang=get_locale();
		global $wpdb;
		$table_name = $wpdb->prefix . 'public_folders';
		$sqlQuery="SELECT * FROM  ". $table_name ." WHERE `lang`='".$lang."'";
		$result = $wpdb->get_results($sqlQuery,OBJECT);

		if ( $result ) : ?>

			<div class="wrap-all-folders">

				<?php foreach( $result as $key => $val ) :

					if ( $key%2 == 0 ) { ?>
						<div class="single-folder-wrap right_folder">
					<?php } else { ?>
						<div class="single-folder-wrap left_folder">
					<?php } ?>

					<a href=" <?php echo home_url('/single-public-folder')?>?folder=<?php echo urlencode($val->folder_name)?>&u=<?php echo $val->id_user?>&si=<?php echo $val->id_site?>" >
						<div class="link-folder">
							<div class="folder"><?php echo $val->folder_name;?></div>
						</div>
					</a>
					</div>

				<?php endforeach; ?>

			</div>

		<?php endif;

	?>

<?php endif;

get_footer();