<?php
/**
 * Default Single
 *
 * @author		Nir Goldberg
 * @package		scoop-child/loop
 * @version		1.4.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_field' ) )
	return;

/**
 * Variables
 */
$my_siddur_custom_label	= get_field( 'acf-option_my_siddur_custom_label', 'option' );
$my_siddur_label		= $my_siddur_custom_label ? $my_siddur_custom_label : __( 'My Siddur', 'kulam-scoop' );
$site_id				= get_current_blog_id();
$post_id				= get_the_ID();
$lang					= get_locale();

$enable_activity_types	= get_field( 'acf-option_enable_activity_types_custom_taxonomy', 'option' );

if ( $enable_activity_types && true === $enable_activity_types ) {

	$activity_types = wp_get_post_terms( $post_id, 'activity_types' );

}

?>

<div class="popup" id="popup-to" hidden>
	<form class="sendEmailpopup"> 
		<div class="close-popup close-popup-to">+</div> 
		<?php _e( 'Please type or paste email addresses with a space between each one:', 'kulam-scoop' ); ?>
		<input type="email"  size="35" pattern="[^ @]*@[^ @]*" required class="to" id="idto" />
		<input type="button" value="<?php _e( 'Send', 'kulam-scoop' ); ?>" id="send-single-post" />
	</form>
</div>

<div class="popup popup-new-folder" id="popup-link-copy" hidden>
	<form class="popup-form-setting">
		<div class="close-popup close-popup-link">+</div>
		<div class="form-body">
			<h4><?php _e( 'Link to share', 'kulam-scoop' ); ?></h4>
			<input type="text" id="link_to_copy" />
		</div>
	</form>
</div>

<?php if ( have_posts() ) :
	while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<div class="loader" style="display: none;">
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

				</header><!-- .entry-header -->

				<div class="entry-sharing col-sm-1">

					<?php

						$add_to_siddur_label		= __( 'Add to ', 'kulam-scoop' ) . $my_siddur_label;
						$remove_from_siddur_label	= __( 'Remove from ', 'kulam-scoop' ) . $my_siddur_label;

						if ( ! ( is_user_logged_in() ) ) { ?>

							<span><a href="#" class="siddur-button" data-toggle="modal" data-target="#modal-login" data-redirect="#" data-show-pre-text="true"><?php echo $add_to_siddur_label; ?></a></span>

						<?php } else {

							$user_id	= get_current_user_id();
							$favorite	= get_user_meta( $user_id, 'favorite' . $site_id , true );

							$favorite			= $favorite ? json_decode( $favorite, true ) : '';
							$in_favorite		= $favorite && in_array( $post_id, $favorite );
							$btn_text			= $in_favorite ? $remove_from_siddur_label : $add_to_siddur_label;
							$btn_toggle_text	= $in_favorite ? $add_to_siddur_label : $remove_from_siddur_label;
							$btn_action			= $in_favorite ? 'remove_from_siddur' : 'add_to_siddur';
							$btn_toggle_action	= $in_favorite ? 'add_to_siddur' : 'remove_from_siddur'; ?>

							<span><a href="#" class="siddur-button siddur-toggle-button" data-toggle-text="<?php echo $btn_toggle_text; ?>" data-action="<?php echo $btn_action; ?>" data-toggle-action="<?php echo $btn_toggle_action; ?>"><?php echo $btn_text; ?></a></span>

						<?php }
		
					?>

					<div class="wrap-sharing-public">

						<!-- facebook -->
						<a class="entry-facebook pojo-tooltip" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&t=<?php the_title(); ?>" title="<?php _e( 'Facebook', 'pojo' ); ?>" target="_blank">
							<span class="fa fa-facebook"></span>
						</a>

						<!-- twitter -->
						<a class="entry-twitter pojo-tooltip" href="http://twitter.com/home?status=<?php the_title(); ?> <?php the_permalink(); ?>" title="<?php _e( 'Twitter', 'pojo' ); ?>" target="_blank">
							<span class="fa fa-twitter"></span>
						</a>

						<!-- whatsapp -->
						<a class="entry-whatsaap pojo-tooltip " id="whatsapp-share" href="whatsapp://send?text=<?php the_permalink();?>">
							<span class="fa fa-whatsapp"></span>
						</a>

						<!-- telegram -->
						<a class="entry-telegram pojo-tooltip " id="telegram-share" href="tg://msg?text=<?php the_permalink();?>">
							<span class="fa fa-telegram"></span>
						</a>

						<!-- clipboard -->
						<a class="entry-clipboard pojo-tooltip " id="clipboard-share-single">
							<span class="fa fa-clipboard"></span>
						</a>

						<!-- mail -->
						<a class="entry-mail" data-toggle="tooltip-mail" title="<?php _e( 'Mail', 'kulam-scoop' ); ?>" id="send">
							<span class="fa fa-envelope-o "></span>
						</a>

						<!-- print -->
						<a class="entry-print pojo-tooltip" href="javascript:window.print()" title="<?php _e( 'Print', 'pojo' ); ?>">
							<span class="fa fa-print"></span>
						</a>

					</div>

				</div><!-- .entry-sharing -->

				<div class="entry-content col-sm-11">

					<div class="print-button" onclick="window.print()">
						<img src='https://kulam.org/wp-content/uploads/2018/09/send-to-printer.png'/>
					</div>

					<div class="entry-format">

						<?php if ( ! has_post_format( 'video' ) && has_post_thumbnail() ) :

							$image_args	= array( 'width' => '1170', 'height' => '660' );
							$image_url	= Pojo_Thumbnails::get_post_thumbnail_url( $image_args );

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

							$gallery_items	= explode( ',', atmb_get_field( 'format_gallery' ) );
							$slides			= array();

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

					</div><!-- .entry-format -->

					<?php if ( ! Pojo_Core::instance()->builder->display_builder() ) :

						the_content();
						pojo_link_pages();

					endif; ?>

					<!-- rating -->
					<?php
						$val1	= get_post_meta( $post_id, 'general' . $site_id );
						$val1	= $val1[0];

						if ( ! $val1 )
							$val1 = 0;

						$val2	= get_post_meta( $post_id, 'religiosity' . $site_id );
						$val2	= $val2[0];

						if ( ! $val2 )
							$val2 = 0;

						$val3	= get_post_meta( $post_id, 'authentic' . $site_id );
						$val3	= $val3[0];

						if ( ! $val3 )
							$val3 = 0;
					?>

					<div class="rating-form after_content mr-filter" id="rate-<?php the_ID(); ?>">

						<?php 

							if ( $site_id != 17 ) :

								$acf_range_field	= $lang == 'he_IL' ? 'range' : 'range_en';
								$acf_range_subfield	= $lang == 'he_IL' ? 'range_name' : 'range_name_en';

								global $post;
								$cats	= get_the_category( $post->ID );
								$parent	= get_category( $cats[1]->category_parent );

								if ( is_wp_error( $parent ) ) {
									$cat = get_category( $cats[0] );
								}
								else {
									$cat = $parent;
								}

								if ( have_rows( $acf_range_field, $cat ) ) : ?>

									<h4><?php _e( 'Please rate this post:', 'kulam-scoop' ); ?></h4>
									<form id="rating-form-<?php the_ID(); ?>">

										<?php
											global $post;
											$postcat = get_the_category( $post->ID );

											foreach( $postcat as $term ) {
												if ( get_post_meta( $post->ID, '_yoast_wpseo_primary_category', true ) == $term->term_id ) {
													$cat = $term;
													break;
												}
												else {
													$cat = $term;
												}
											}

											$category_id = 'category_' . esc_html( $cat->term_id );

											if ( have_rows( $acf_range_field, $category_id ) ) : $i=0; $j=0;
												while ( have_rows( $acf_range_field, $category_id ) && $j<= 4 ) : the_row(); $i++; $j++;

													$rangeName	= get_sub_field( $acf_range_subfield );
													$val		= get_post_meta( $post_id, $rangeName . $site_id );
													$val		= $val[0];

													if ( ! $val )
														$val = 0; ?>

													<span class="rating-item-mr"> 
														<form class="range-rating">
															<label class="description" for="rating-item"> <?php the_sub_field( $acf_range_subfield );?></label>
															<input class="slider" id="optionID<?php echo $i; ?>" name="range-option" type="range" min="0" max="10" oninput="outputID<?php echo $i; ?>.value = optionID<?php echo $i; ?>.value" value="<?php echo $val; ?>"/>
															<output id="outputID<?php echo $i; ?>" value='<?php echo $val; ?>'><?php echo $val; ?></output>
														</form>
													</span>

												<?php endwhile;
											endif;
										?>
										<input type="submit" class="save-rating" value="<?php _e( 'Submit Rating', 'kulam-scoop' ); ?>">

									</form>

								<?php endif;

							endif;

						?>

					</div><!-- .rating-form -->

					<div id="popUp" style="display: none;">
						<p class="txtMashov"><?php _e( 'Thank you for rating!', 'kulam-scoop' ); ?></p>
					</div>

					<footer class="entry-footer">
						<div class="entry-edit">
							<?php pojo_button_post_edit(); ?>
						</div>
						<?php $tags = get_the_tags(); if ( $tags ) : ?>
							<div class="entry-tags"><?php the_tags( '', ' ' ); ?></div>
						<?php endif; ?>

						<?php
							if ( $activity_types ) {

								foreach ( $activity_types as $t ) {
									$types_arr[] = '<a href="' . get_term_link( $t->term_id ) . '" rel="tag">' . $t->name . '</a>';
								}

								echo '<div class="entry-tags">' . implode( ' ', $types_arr ) . '</div>';

							}
						?>

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
					</footer><!-- .entry-footer -->

				</div><!-- .entry-content -->
			</div><!-- .entry-post -->
		
			<div class="clearfix"></div>

			<?php comments_template( '', true ); ?>

		</article>
	<?php endwhile;
else :
	pojo_get_content_template_part( 'content', 'none' );
endif;