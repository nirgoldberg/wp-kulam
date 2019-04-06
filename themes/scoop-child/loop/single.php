<?php
/**
 * Default Single
 *
 * @author      Nir Goldberg
 * @package     scoop-child
 * @version     1.0.5
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$lang=get_locale();
?>
  <?php if ($lang=="en_US"):?>
<div id="popup-to" hidden>
<form class="sendEmailpopup" > 
  <div class="close-popup-to" >+</div> 
  Please type or paste email addresses with a space between each one:
    <input type="email"  size="35" pattern="[^ @]*@[^ @]*" required class="to" id="idto"/>
    <input type="button" value="send" id="send-single-post"/>
</form>
</div>
<?php endif;?>
<?php if ($lang=="he_IL"):?>
<div id="popup-to" hidden>
<form class="sendEmailpopup" > 
  <div class="close-popup-to" >+</div> 
  הקלד או הדבק את כתובות המייל לשיתוף עם רווח בין כתובת אחת לשניה
      <input type="email"  size="35" pattern="[^ @]*@[^ @]*" required class="to" id="idto"/>
    <input type="button" value="שלח" id="send-single-post"/>
</form>
</div>
<?php endif;?>
<?php if ($lang=="he_IL"):?>
<div class="popup-new-folder" id="popup-link-copy">
  <form class="popup-form-setting">
  <div class="close-popup-link" >+</div>
    <div class="form-body">
        <h4>לינק לשיתוף</h4>
        <input type="text" id="link_to_copy">
    </div>
  </form>
</div>
<?php endif;?>
<?php if ($lang=="en_US"):?>
<div class="popup-new-folder" id="popup-link-copy">
  <form class="popup-form-setting">
  <div class="close-popup-link" >+</div>
    <div class="form-body">
        <h4>Link to share</h4>
        <input type="text" id="link_to_copy">
    </div>
  </form>
</div>
<?php endif;?>
<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( have_posts() ) :
	while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div hidden class="loader">
        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/loader.gif"/>
    </div>
			<div class="entry-post">
				<header class="entry-header">
					<div class="breadcrumb"><?php get_breadcrumb(); ?></div>
					<?php if ( pojo_is_show_page_title() ) : ?>
						<div class="page-title">
							<h1 class="entry-title">
								<?php the_title(); ?>
							</h1>
						</div>
					<?php endif; ?>
					<div class="entry-meta">
						<?php if ( po_single_metadata_show( 'author' ) ) : ?>
							<span class="entry-user vcard author"><?php echo get_avatar( get_the_author_meta( 'email' ), '24' ); ?> <?php the_author_link(); ?></span>
						<?php endif; ?>
						<?php if ( po_single_metadata_show( 'date' ) ) : ?>
							<span><time datetime="<?php the_time('o-m-d'); ?>" class="entry-date date published updated"><a href="<?php echo get_month_link( get_the_time('Y'), get_the_time('m') ); ?>"><?php echo get_the_date(); ?></a></time></span>
						<?php endif; ?>
						<?php if ( po_single_metadata_show( 'time' ) ) : ?>
							<span class="entry-time"><?php echo get_the_time(); ?></span>
						<?php endif; ?>
						<?php if ( po_single_metadata_show( 'comments' ) ) : ?>
							<span class="entry-comment"><?php comments_popup_link( __( 'No Comments', 'pojo' ), __( 'One Comment', 'pojo' ), __( '% Comments', 'pojo' ), 'comments' ); ?></span>
						<?php endif; ?>
					</div>
					<?php if ( po_single_metadata_show( 'excerpt' ) && has_excerpt() ) : ?>
					<div class="entry-excerpt">
						<?php the_excerpt(); ?>
					</div>
					<?php endif; ?>

				</header>
				<?php //if ( po_single_metadata_show( 'sharing' ) ) : ?>
				<div class="entry-sharing col-sm-1">
				<?php if (!(is_user_logged_in())){
						$btn_text = __('Add to My Siddur', 'kulam-scoop');
						?>
						<span><a href="#" class="sidur_button" data-toggle="modal" data-target="#modal-login" data-redirect="#"><?php echo $btn_text;?></a></span>
				<?php }?>
                    <?php if (is_user_logged_in()) {
                        $site = get_current_blog_id();
						$btn_text = __('Add to My Siddur', 'kulam-scoop');
						$path = get_home_url();

						if($path === 'https://masaisraeli.kulam.org')
							$btn_text = ('הוסף למועדפים שלי');
						else if($path === 'https://onward.kulam.org')
							$btn_text = ('Add to My Shelf');

                        $btn_id = 'add_to_sidur';
                        $uid = get_current_user_id();
                        $tax_name = 'siddur_'.$uid.'_1';
						$favorite = get_user_meta($uid,"favorite" . $site ,true);
						$favorite = json_decode($favorite,true);
						if($favorite) {
                            if (in_array(get_the_ID(), $favorite)) {
								$btn_text = __('Remove from My Siddur', 'kulam-scoop');
								$path = get_home_url();

						        if($path === 'https://masaisraeli.kulam.org')
									$btn_text = ('הסר ממועדפים שלי');
								else if($path === 'https://onward.kulam.org')
									$btn_text = ('Remove from My Shelf');

                                $btn_id = 'remove_from_sidur';
                            }
                        }
                        ?>
                       <span><a class="sidur_button" href='#' id='<?php echo $btn_id;?>'><?php echo $btn_text;?></a></span>

					<?php } ?>
					<div class="wrap-sharing-public">
					<a class="entry-facebook pojo-tooltip" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&t=<?php the_title(); ?>" title="<?php _e( 'Facebook', 'pojo' ); ?>" target="_blank">
						<span class="fa fa-facebook"></span>
					</a>
					<a class="entry-twitter pojo-tooltip" href="http://twitter.com/home?status=<?php the_title(); ?> <?php the_permalink(); ?>" title="<?php _e( 'Twitter', 'pojo' ); ?>" target="_blank">
						<span class="fa fa-twitter"></span>
					</a>
					<a class="entry-whatsaap pojo-tooltip " id="whatsapp-share" href="whatsapp://send?text=<?php the_permalink();?>">
                       <span class="fa fa-whatsapp"></span>
					</a>
					<a class="entry-telegram pojo-tooltip " id="telegram-share" href="tg://msg?text=<?php the_permalink();?>">
                       <span class="fa fa-telegram"></span>
                    </a>
					<a class="entry-clipboard pojo-tooltip " id="clipboard-share-single">
						<span class="fa fa-clipboard"></span>
					</a>
					<?php
					if($lang == 'en'){
					?>
					<a class="entry-mail" data-toggle="tooltip-mail" title="Mail" id="send">
                       <span class="fa fa-envelope-o "></span>
                    </a>
					<?php
					}
					else{
					?>
					<a class="entry-mail" data-toggle="tooltip-mail" title="אימייל" id="send">
                       <span class="fa fa-envelope-o "></span>
                    </a>
					<?php } ?>
					<a class="entry-print pojo-tooltip" href="javascript:window.print()" title="<?php _e( 'Print', 'pojo' ); ?>">
						<span class="fa fa-print"></span>
					</a>
					</div>
				</div>
				<?php //endif; ?>
				<div class="entry-content col-sm-11">
				<div class="print-button" onclick="window.print()"><img src='https://kulam.org/wp-content/uploads/2018/09/send-to-printer.png'/> </div>
					<?php if ( has_post_format( array( 'image', 'gallery', 'audio', 'video' ) ) ) : ?>
						<div class="entry-format">
						<?php if ( has_post_format( 'image' ) ) :
							$image_args = array( 'width' => '1170', 'height' => '660' );
							$image_url = Pojo_Thumbnails::get_post_thumbnail_url( $image_args );
							if ( $image_url ) : ?>
								<img src="<?php echo $image_url; ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="media-object" />
							<?php endif; ?>
						<?php endif; ?>
						<?php if ( has_post_format( 'video' ) ) : ?>
							<?php if ( $video_link = atmb_get_field( 'format_video_link' ) ) : ?>
								<div class="custom-embed" data-save_ratio="<?php echo atmb_get_field( 'format_aspect_ratio' ); ?>"><?php echo wp_oembed_get( $video_link, wp_embed_defaults() ); ?></div>
							<?php endif; ?>
						<?php endif; ?>
						<?php if ( has_post_format( 'audio' ) ) : ?>
							<?php echo wp_audio_shortcode( array( 'mp3' => atmb_get_field( 'format_mp3_url' ), 'ogg' => atmb_get_field( 'format_oga_url' ) ) ); ?>
							<div class="custom-embed"><?php echo wp_oembed_get( atmb_get_field( 'format_embed_url' ), wp_embed_defaults() ); ?></div>
						<?php endif; ?>
						<?php if ( has_post_format( 'gallery' ) ) :
							$gallery_items = explode( ',', atmb_get_field( 'format_gallery' ) );
							$slides = array();
							if ( ! empty( $gallery_items ) ) :
								foreach ( $gallery_items as $item_id ) :
									$attachment     = get_post( $item_id );
									$attachment_url = Pojo_Thumbnails::get_attachment_image_src( $item_id, array( 'width' => '1170', 'height' => '660' ) );
									if ( ! empty( $attachment_url ) )
										$slides[] = sprintf(
											'<li><img src="%2$s" title="%3$s" alt="%3$s" /></li>',
											esc_attr( get_permalink() ),
											esc_attr( $attachment_url ),
											esc_attr( $attachment->post_excerpt )
										);
								endforeach;
								if ( ! empty( $slides ) ) :
									echo '<ul class="pojo-simple-gallery">' . implode( '', $slides ) . '</ul>';
								endif;
							endif; ?>
						<?php endif; ?>
						</div>
					<?php endif; ?>
					


					<?php if ( ! Pojo_Core::instance()->builder->display_builder() ) : ?>

						<?php
							the_content();
							pojo_link_pages();
						?>
					<?php endif; ?>
										<!-- rating -->
										<?php
					$site = get_current_blog_id();
					$id=get_the_ID();
				   $val1=get_post_meta($id,'general'.$site);
				   $val1=$val1[0];
				   if(!$val1)
					  $val1=0;
				   $val2=get_post_meta($id,'religiosity'.$site);
				   $val2=$val2[0];
				   if(!$val2)
					  $val2=0;
				   $val3=get_post_meta($id,'authentic'.$site);
				   $val3=$val3[0];
				   if(!$val3)
					  $val3=0;
				?>

		<div class="rating-form after_content mr-filter" id="rate-<?php echo the_ID(); ?>">
					<?php 
				
				if($site!=17):
					$lang=get_locale();
										if($lang=="he_IL"):
											global $post;
											$cats = get_the_category($post->ID);
											$parent = get_category($cats[1]->category_parent);
											if (is_wp_error($parent)){
													$cat = get_category($cats[0]);
												}
												else{
													$cat = $parent;
												}
											if( have_rows('range', $cat) ):?>
                 	  <h4>בבקשה, דרג פוסט זה</h4>
                    <form id="rating-form-<?php echo the_ID(); ?>">
					<?php
					global $post;
					$postcat = get_the_category( $post->ID );
					foreach($postcat as $term) {
						if( get_post_meta($post->ID, '_yoast_wpseo_primary_category',true) == $term->term_id ) {
						  $cat = $term;
						  break;
						}
						else{
							$cat = $term;
						}
					 }	
					$category_id = 'category_' . esc_html( $cat->term_id );
					
					if( have_rows('range', $category_id) ): $i =0; $j=0;
						while ( have_rows('range', $category_id) && $j<= 4) : the_row(); $i++; 
						$j++; 					
					$rangeName= get_sub_field('range_name');
				   	$val =get_post_meta($id, $rangeName.$site);
				   	$val =$val[0];
				   	if(!$val )
						$val =0;	
					?>
					<span class="rating-item-mr"> 
						<form class="range-rating">
						<label class="description" for="rating-item"> <?php the_sub_field('range_name');?></label>
						<input class="slider" id="optionID<?php echo $i; ?>" name="range-option" type="range" min="0" max="10" 
						oninput="outputID<?php echo $i; ?>.value = optionID<?php echo $i; ?>.value" value="<?php echo $val ?>"/>
						<output id="outputID<?php echo $i; ?>" value='<?php echo $val?>'><?php echo $val?></output>
						</form>
					</span>
					<?php
						endwhile;
					endif;
					?>
					<!-- <span class="rating-item-mr"> 
                     	<form class="range-rating">
						 <label class="description" for="rating-item"> כללי</label>
                     	  <input class="slider" id="optionID1" name="range-option" type="range" min="0" max="10" oninput="outputID1.value=optionID1.value" value="<?php echo $val1?>"/>
						  <output id="outputID1"><?php echo $val1?></output>
						</form>
                 	  </span>

					    <span class="rating-item-mr">
                     	<form class="range-rating">
						 <label class="description" for="rating-item">דתיות</label>
                     	  <input class="slider" id="optionID2" name="range-option" type="range" min="0" max="10" oninput="outputID2.value=optionID2.value" value="<?php echo $val2?>"/>
						  <output id="outputID2"><?php echo $val2?></output>
						</form>
                 	  </span>

					<span class="rating-item-mr">
                     	<form class="range-rating">
						 <label class="description" for="rating-item">רענן</label>
                     	  <input class="slider" id="optionID3" name="range-option" type="range" min="0" max="10" oninput="outputID3.value=optionID3.value" value="<?php echo $val3?>"/>
						  <output id="outputID3"><?php echo $val3?></output>
						</form>
                 	  </span> -->
					   <input type="submit" class="save-rating" value="שמור דירוג">
                    </form>
				<?php endif;
			         endif;?>

					<!-- english-rating -->
					<?php $lang=get_locale();
										if($lang=="en_US"):
											global $post;
											$cats = get_the_category($post->ID);
											$parent = get_category($cats[1]->category_parent);
											if (is_wp_error($parent)){
													$cat = get_category($cats[0]);
												}
												else{
													$cat = $parent;
												}
											if( have_rows('range_en', $cat) ):?>
                 	  <h4>Please rate this post:</h4>
                    <form id="rating-form-<?php echo the_ID(); ?>">
					<?php
					global $post;
					$postcat = get_the_category( $post->ID );
					foreach($postcat as $term) {
						if( get_post_meta($post->ID, '_yoast_wpseo_primary_category',true) == $term->term_id ) {
						  $cat = $term;
						  break;
						}
						else{
							$cat = $term;
						}
					 }	
					$category_id = 'category_' . esc_html( $cat->term_id );

					if( have_rows('range_en', $category_id) ): $i =0; $j=0;
						while ( have_rows('range_en', $category_id) && $j<= 4) : the_row(); $i++; 
						$j++; 					
					$rangeName= get_sub_field('range_name_en');
				   	$val =get_post_meta($id, $rangeName.$site);
				   	$val =$val[0];
				   	if(!$val )
						$val =0;	
					?>
					<span class="rating-item-mr"> 
						<form class="range-rating">
						<label class="description" for="rating-item"> <?php the_sub_field('range_name_en');?></label>
						<input class="slider" id="optionID<?php echo $i; ?>" name="range-option" type="range" min="0" max="10" 
						oninput="outputID<?php echo $i; ?>.value = optionID<?php echo $i; ?>.value" value="<?php echo $val ?>"/>
						<output id="outputID<?php echo $i; ?>" value='<?php echo $val?>'><?php echo $val?></output>
						</form>
					</span>
					<?php
						endwhile;
					endif;
					?>
                 	 <!-- <span class="rating-item-mr">
                 	   
                     	<form class="range-rating">
						 <labal class="description" for="rating-item">General</labal>
                     	  <input class="slider" id="optionID1" name="range-option" type="range" min="0" max="10" oninput="outputID1.value=optionID1.value" value="<?php echo $val1?>"/>
						  <output id="outputID1"><?php echo $val1?></output>
						</form>
                 	  </span>

					    <span class="rating-item-mr">
                 	   
                     	<form class="range-rating">
						 <labal class="description" for="rating-item">Traditional</labal>
                     	  <input class="slider" id="optionID2" name="range-option" type="range" min="0" max="10" oninput="outputID2.value=optionID2.value" value="<?php echo $val2?>"/>
						  <output id="outputID2"><?php echo $val2?></output>
						</form>
                 	  </span>

					<span class="rating-item-mr">
                 	    
                     	<form class="range-rating">
						 <labal class="description" for="rating-item">Innovative</labal>
                     	  <input class="slider" id="optionID3" name="range-option" type="range" min="0" max="10" oninput="outputID3.value=optionID3.value" value="<?php echo $val3?>"/>
						  <output id="outputID3"><?php echo $val3?></output>
						</form>
                 	  </span> -->
					   <input type="submit" class="save-rating" value="Submit Rating">
                  	</form>
				<?php endif;
			          endif;?>
					<!-- end english-rating -->
      </div>
					<!-- end rating -->
		<?php endif; ?>
					<?php if($lang=="en_US"):?>
					<div id="popUp" style="display: none;"> <p class="txtMashov">Thank you for rating!</p> </div>
						<?php endif;?>
						<?php if($lang=="he_IL"):?>
					<div id="popUp" style="display: none;"><p class="txtMashov"> הדירוג התקבל. תודה! </p></div>
						<?php endif;?>	
					<footer class="entry-footer">
						<div class="entry-edit">
							<?php pojo_button_post_edit(); ?>
						</div>
						<?php $tags = get_the_tags(); if ( $tags ) : ?>
							<div class="entry-tags"><?php the_tags( '', ' ' ); ?></div>
						<?php endif; ?>
						<?php if ( pojo_is_show_about_author() ) : ?>
							<div class="author-info media">
								<div class="author-info-inner">
									<h3 class="author-title"><?php _e( 'About the Author', 'pojo' ); ?></h3>
									<div class="author-avatar pull-left">
										<a href="<?php the_author_meta( 'user_url' ); ?>"><?php echo get_avatar( get_the_author_meta( 'email' ), '90' ); ?></a>
									</div>
									<div class="author-content media-body">
										<h4 class="author-name">
											<?php the_author_meta( 'user_firstname' ); ?> <?php the_author_meta( 'user_lastname' ); ?>
											<small><a class="author-link" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author" class="fn"><?php _e( 'View all posts by', 'pojo' ); ?> <?php echo get_the_author(); ?></a></small>
										</h4>
										<p class="author-bio">
											<?php echo nl2br( get_the_author_meta( 'description' ) ); ?><br />
										</p>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<?php
							// Previous/next post navigation.
							echo pojo_get_post_navigation(
								array(
									'prev_text' => __( '&laquo; Previous Post', 'pojo' ),
									'next_text' => __( 'Next Post &raquo;', 'pojo' ),
								)
							);
						?>
					</footer>
				</div>
			</div>
		
			<div class="clearfix"></div>

			<?php comments_template( '', true ); ?>

		</article>
	<?php endwhile;
else :
	pojo_get_content_template_part( 'content', 'none' );
endif;