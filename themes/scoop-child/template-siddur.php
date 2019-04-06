<?php
/**
 * The template for displaying the ProductsBTB page.
 *
 * Template name:siddur 
 *
 * @author      Nir Goldberg
 * @package     scoop-child
 * @version     1.0.5
 */
get_header();

$site = get_current_blog_id();
$user = wp_get_current_user();
$folders=get_user_meta($user->ID,"nameFolder".$site,true);
$x=json_decode($folders,true);
$lang=get_locale();

if($folders):
	// echo '<label class="add-post-to-folder" id="a" >+</label>';
	$folders=json_decode($folders,true);
?>
   <div class="popup-new-folder" id="all-folders"  hidden>
<form class="popup-form-div">
	<div class="close-popup-folders" >+</div>
	<div class="all-input">
	<?php foreach($folders as $val):
	?>
	   <input type="radio" value="<?php echo $val?>"  id="<?php echo $val?>" name="option"> 
	   <span><?php echo $val?></span></br>
	<?php endforeach;?>
	</div>
	<input type="submit" class="save-in-folder"/>
	</form>
</div>
	<?php endif; ?>
<?php if ($lang=="he_IL"):?>
<div class="popup-new-folder" id="new-fold"  hidden>
	<div class="popup-form">
	<div class="close-popup" >+</div>
	<h3>שם התיקייה</h3>
	<input type="text" id="name-folder" placeholder="שם התיקייה" />
	<input type="submit" class="add-save-folder" value="הוסף"/>
</div>
</div>
	<?php endif;?>

	<?php if ($lang=="en_US"):?>
<div class="popup-new-folder" id="new-fold"  hidden>
	<div class="popup-form">
	<div class="close-popup" >+</div>
	<label class="name">Name Folder</label>
	<input type="text" id="name-folder" >
	<input type="submit" class="add-save-folder" value="add"/>
</div>
</div>
	<?php endif;?>

<?php

if ( is_user_logged_in() ) {
	$site = get_current_blog_id();
	$lang = get_locale();
	$user = wp_get_current_user();
	$sidur = get_user_meta($user->ID,"sidur" . $site, true);
	$sidur = json_decode($sidur, true);
	$folders=get_user_meta($user->ID,"nameFolder".$site,true);
   
   ?>
<div class="siddur-wrap">

	<button class="add-new-folder"></button>
		<?php
	if($folders)
	{
		$folders=json_decode($folders,JSON_UNESCAPED_UNICODE);
		?>
		<div class="wrap-all-folders">
		<?php

		foreach($folders as $key => $val):
					if($key%2 === 0){
?>
	 <div class="single-folder-wrap right_folder">
		 <?php }
		 else {
			 ?>
	  <div class="single-folder-wrap left_folder">
			 <?php
		 }
			 ?>
	   <a href=" <?php echo home_url('/my-siddur-folder')?>?folder=<?php echo urlencode($val)?>" >
	   <div class="link-folder">
		 <div class="folder"><?php echo $val;?></div>
		</div>
	   </a>
		</div>
	   <?php
	   endforeach;
	   ?></div><?php
	}
	if($sidur) {
		$args = array(
			'post_type' => 'post',
			'post__in' => $sidur
		);
		$the_query = new WP_Query($args);
		?>
		<div id="primary">
			<div id="content" role="main">
				<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>

					<?php get_template_part('content/content', 'grid_three'); ?>
				<?php endwhile; // end of the loop.
				?>
			</div><!-- #content -->
		</div><!-- #primary -->

		<div hidden class="loader">
		<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/loader.gif"/>
	</div>
		<?php
	}
	// else{
	//     the_content();
	// }
	?>
 </div>
	<?php
}

get_footer();