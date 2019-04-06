<?php
/**
 * Content: Grid - Three Columns
 *
 * @author      Nir Goldberg
 * @package     scoop-child
 * @version     1.0.5
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $_pojo_parent_id;

$categories       = '';
$categories_terms = get_the_category();
if ( ! empty( $categories_terms ) && ! is_wp_error( $categories_terms ) ) :
	$categories = wp_list_pluck( $categories_terms, 'name' );
	$categories = $categories[0];
endif;

$format_icon_class = 'format-icon-hide';
if ( po_archive_metadata_show( 'format_icon', $_pojo_parent_id ) ) :
	$format_icon_class = 'format-icon-show';
endif;
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( apply_filters( 'pojo_post_classes', array( 'grid-item grid-three col-sm-4 col-xs-12', $format_icon_class ), get_post_type() ) ); ?>>
	<div class="item-inner">
		<?php if ( $image_url = Pojo_Thumbnails::get_post_thumbnail_url( array( 'width' => '460', 'height' => '295', 'crop' => true, 'placeholder' => true ) ) ) : ?>
			<div class="entry-thumbnail">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark" class="image-link">
					<img src="<?php echo $image_url; ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="media-object thambnail" />
					<?php if ( ! empty( $categories ) && po_archive_metadata_show( 'category', $_pojo_parent_id ) ) : ?>
						<div class="category-label"><div><span><?php echo $categories; ?></span></div></div>
					<?php endif; ?>
				</a>
				<div class="entry-meta">
					<?php if ( po_archive_metadata_show( 'date', $_pojo_parent_id ) ) : ?>
						<span><time datetime="<?php the_time('o-m-d'); ?>" class="entry-date date published updated"><a href="<?php echo get_month_link( get_the_time('Y'), get_the_time('m') ); ?>"><?php echo get_the_date(); ?></a></time></span>
					<?php endif; ?>
					<?php if ( po_archive_metadata_show( 'time', $_pojo_parent_id ) ) : ?>
						<span class="entry-time"><?php echo get_the_time(); ?></span>
					<?php endif; ?>
					<?php if ( po_archive_metadata_show( 'comments', $_pojo_parent_id ) ) : ?>
						<span class="entry-comment"><?php comments_popup_link( __( 'No Comments', 'pojo' ), __( 'One Comment', 'pojo' ), __( '% Comments', 'pojo' ), 'comments' ); ?></span>
					<?php endif; ?>
					<?php if ( po_archive_metadata_show( 'author', $_pojo_parent_id ) ) : ?>
						<span class="entry-user vcard author"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author" class="fn"><?php echo get_the_author(); ?></a></span>
					<?php endif; ?>
					<!-- add-remove-favorite -->
					<?php 
					if (!(is_user_logged_in())){
						?>
						<span class="wrap-heart"><a class="sidur_button" data-toggle="modal" data-target="#modal-login" data-redirect="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a></span>
				
					<?php }
					?>
					<span class="favorite">
						<?php
					if(is_user_logged_in()):
						$site = get_current_blog_id();
                        $btn_text = "add";
                        $btn_id = 'add_to_sidur';
                        $uid = get_current_user_id();
                        $tax_name = 'siddur_'.$uid.'_1';
						$favorite = get_user_meta($uid,"favorite" . $site ,true);
						$favorite = json_decode($favorite,true);
						if($favorite) {
                            if (in_array(get_the_ID(), $favorite)) {
                                $btn_text = "remove";
								$btn_id = 'remove_from_sidur';
                            }
                        }
					
					  if($btn_text=="remove"){?>	
					     <span class="wrap-heart"><a class="sidur_button" href='#' id='<?php echo $btn_id;?>'><i class="fa fa-heart" aria-hidden="true"></i></a></span>
					  <?php }
					  else if($btn_text=="add"){?>
					   <span class="wrap-heart"><a class="sidur_button" href='#' id='<?php echo $btn_id;?>'><i class="fa fa-heart-o" aria-hidden="true"></i></a></span>
					  </span>
                    <?php } endif;?>
				</div>
			</div>
		<?php endif; ?>
		<div class="caption">
			<h3 class="grid-heading entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<?php po_print_archive_excerpt( $_pojo_parent_id ); ?>
			
			<?php
		    //avg of rating
			$post_id=get_the_ID();
			$site=get_current_blog_id();
			//general
			 $generalrate=get_post_meta($post_id,'sumGeneral'.$site);
			 $countgeneral=get_post_meta($post_id,"countratingGeneral".$site);
			 if($generalrate[0]&&$countgeneral[0])
			    $val1=$generalrate[0]/$countgeneral[0];
			else
				$val1=0;
			//religiosity
			 $religiosityrate=get_post_meta($post_id,'sumReligiosity'.$site);
			 $countreligiosity=get_post_meta($post_id,"countratingReligiosity".$site);
			 if($religiosityrate[0]&&$countreligiosity[0])
			   $val2=$religiosityrate[0]/$countreligiosity[0];
			 else
				   $val2=0;
			//authentic
				   $authenticrate=get_post_meta($post_id,'sumAuthentic'.$site);
				   $countratingAuthentic=get_post_meta($post_id,"countratingAuthentic".$site);
				   if($authenticrate[0]&& $countratingAuthentic[0])
				      $val3=$authenticrate[0]/$countratingAuthentic[0];
				   else
					  $val3=0;
	       $lang=get_locale();
		    if($lang=="he_IL"):
			 ?>
			 <!-- <div class="bars-t">
		 <p class="txtsl">כללי</p><div class="authenticIconV "></div>	<input disabled="disabled" class="slidert" id="optionID1"  type="range" min="0" max="10"  value="<?php //echo $val1?>"/><div class="authenticIcon-"></div>
		 <p class="txtsl">דתיות</p> <div class="religiosityIconV"></div>	<input disabled="disabled" class="slidert" id="optionID2"  type="range" min="0" max="10"  value="<?php //echo $val2?>"/><div class="religiosityIcon-"></div>
		 <p class="txtsl">רענן </p> <div class="generalIconV"></div> <input disabled="disabled" class="slidert" id="optionID3"  type="range" min="0" max="10"  value="<?php //echo $val3?>"/><div class="generalIconX"></div>
			</div>  -->
			 <?php endif;
		 if($lang=="en_US"):?>
		 <!-- <div  class="bars-t">
		 <p class="txtsl">General</p> <div class="authenticIcon-"></div>	<input disabled="disabled" class="slidert" id="optionID1"  type="range" min="0" max="10"  value="<?php //echo $val1?>"/><div class="authenticIconV"></div>
		 <p class="txtsl"> Traditional </p>	<div class="religiosityIcon-"></div> <input disabled="disabled" class="slidert" id="optionID2"  type="range" min="0" max="10"  value="<?php //echo $val2?>"/><div class="religiosityIconV"></div>
		 <p class="txtsl">Innovative </p> <div class=" generalIconX"></div>  <input disabled="disabled" class="slidert" id="optionID3"  type="range" min="0" max="10"  value="<?php //echo $val3?>"/><div class="generalIconV "></div>
			</div> -->
		 <?php endif;?>
		</div>
	</div>
</div>
