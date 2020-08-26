<?php
/**
 * Functions
 *
 * @author		Nir Goldberg
 * @package		scoop-child
 * @version		2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// live uploads
if ( defined( 'WP_SITEURL' ) && defined( 'LIVE_SITEURL' ) ) {
	require_once( 'functions/live-uploads.php' );
}

// helper functions
require_once( 'functions/helpers.php' );

// custom post types
require_once( 'functions/post-types.php' );

// theme functions
require_once( 'functions/theme.php' );

// pojo functions
require_once( 'functions/pojo.php' );

// admin header section
require_once( 'functions/admin/header.php' );

// google tag manager
require_once( 'functions/gtag.php' );

// google analytics
require_once( 'functions/google-analytics.php' );

// menus functions
require_once( 'functions/menus.php' );

// widgets functions
require_once( 'functions/widgets.php' );

// shortcodes functions
require_once( 'functions/shortcodes.php' );

// menu modal
require_once( 'functions/modal-menu.php' );

// login modal
require_once( 'functions/modal-login.php' );

// registration modal
require_once( 'functions/modal-registration.php' );

// search modal
require_once( 'functions/modal-search.php' );

// category popup image modal
require_once( 'functions/modal-category-popup-image.php' );

// svgs
require_once( 'functions/svgs.php' );

// search functions
require_once( 'functions/search.php' );

// users functions
require_once( 'functions/users.php' );

// htmline membership functions
require_once( 'functions/htmline-membership.php' );

// yoast functions
require_once( 'functions/yoast.php' );

/**
 *     SETUP FUNCTIONS
 */
require_once('vendors/acf.php');                   // ACF Functions

// ACF field groups
require_once( 'functions/acf/acf-configuration.php' );

if ( ! defined( 'USE_LOCAL_ACF_CONFIGURATION' ) || ! USE_LOCAL_ACF_CONFIGURATION ) {
	require_once( 'functions/acf/acf-field-groups.php' );
}

// my siddur
require_once( 'functions/siddur.php' );

use Mailgun\HttpClientConfigurator;
use Mailgun\Mailgun;

/**
* Customizer additions.
*/
require_once ('includes/class-pojo-child-customize-register-fields.php');

// Theme Shortcodes

if ( ! function_exists( 'kol_setup' ) ) :

	function kol_setup() {

		load_theme_textdomain( 'kulam-scoop', get_stylesheet_directory() . '/languages' );
		// set_post_thumbnail_size( 1170, 658, true );

		// Set up the default content width
		$GLOBALS['content_width'] = apply_filters( 'kol_content_width', 1170 );
	}
endif;

add_action( 'after_setup_theme', 'kol_setup' );

// Create table to save public folders details
function create_public_folder_table_db() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	$table_name = $wpdb->prefix . 'public_folders';
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		`folder_id` INT(11) NOT NULL AUTO_INCREMENT,
		`folder_name` VARCHAR(100) NOT NULL,
		`id_user` VARCHAR(100) NOT NULL,
		`id_site` VARCHAR(100) NOT NULL,
		`lang` VARCHAR(100) NOT NULL,
		PRIMARY KEY (`folder_id`)
	) $charset_collate;";
	dbDelta( $sql );

   } 
   add_action( 'after_setup_theme', 'create_public_folder_table_db', 10);

//change number of post to show on search
function change_wp_search_size($query) {
	if ( $query->is_search ) // Make sure it is a search page
		$query->query_vars['posts_per_page'] = 18; // Change 10 to 18

	return $query; // Return our modified query variables
}
add_filter('pre_get_posts', 'change_wp_search_size'); // Hook our custom function onto the request filter

function get_breadcrumb() {
	echo '<a href="'.home_url().'" rel="nofollow">Home</a>';
	if (is_category() || is_single()) {
		echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
		the_category(' &bull; ');
		if (is_single()) {
			echo " &nbsp;&nbsp;&#187;&nbsp;&nbsp; ";
			the_title();
		}
	} elseif (is_page()) {
		echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
		echo the_title();
	} elseif (is_search()) {
		echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;Search Results for... ";
		echo '"<em>';
		echo the_search_query();
		echo '</em>"';
	}
}

add_action("wp_ajax_nopriv_create_account", "create_account",10,1);
function create_account(){
	//You may need some data validation here
	$lang = get_locale();
	$captcha_instance = new ReallySimpleCaptcha();
	$user = ( isset($_POST['uname']) ? $_POST['uname'] : '' );
	$pass = ( isset($_POST['upass']) ? $_POST['upass'] : '' );
	$email = ( isset($_POST['uemail']) ? $_POST['uemail'] : '' );
	$captcha = ( isset($_POST['captcha']) ? $_POST['captcha'] : '' );
	$prefix = ( isset($_POST['prefix']) ? $_POST['prefix'] : '' );
	if($captcha_instance->check( $prefix, $captcha )) {
		if (!username_exists($user) && !email_exists($email)) {
			$user_id = wp_create_user($user, $pass, $email);
			if (!is_wp_error($user_id)) {
				//user has been created
				$user = new WP_User($user_id);
				$user->set_role('subscriber');
				wp_set_current_user($user_id);
				wp_set_auth_cookie($user_id);
				$captcha_instance->remove( $prefix );
				//Redirect
				echo "Success";
			}
		} else {
			if ($lang == 'he_IL') {
				echo "שם משתמש או אימייל כבר קיימים";
			} else {
				echo "Username Or Email already exists";
			}
		}
	}
	else{
		if ($lang == 'he_IL') {
			echo "הטקסט אינו זהה לתמונה";
		} else {
			echo "Incorrect Text Entered";
		}
	}
}

function custom_login(){


	$login_data = array(
		'user_login' => $_POST['unamelog'],
		'user_password' => $_POST['upasslog'],
	);
	$user_verify = wp_signon( $login_data, false );
	$site = get_current_blog_id();
	$user = get_user_by( 'id', $user_verify->ID );
	if(is_user_member_of_blog($user_verify->ID, $site)){
		if ( is_wp_error($user_verify) )
		{
			echo "Invalid login details";
		}
		else
		{
			wp_set_current_user( $user_verify->ID, $user->user_login );
			wp_set_auth_cookie( $user_verify->ID );
			do_action( 'wp_login', $user->user_login );
			echo "Success";
		}
	}
	else{
		echo "Invalid login details";
	}

}
add_action("wp_ajax_nopriv_custom_login", "custom_login",10,1);

add_action("wp_ajax_nopriv_save_rating_post","saveRatingPost",10,1);
add_action("wp_ajax_save_rating_post","saveRatingPost",10,1);
function saveRatingPost(){
	$site = get_current_blog_id();
	if(isset($_POST['postID']))
	   $post_id=$_POST['postID'];

	global $post;
	$postcat = get_the_category( $post_id);
	$category_id = 'category_' . esc_html( $postcat[0]->term_id );
	
	$lang=get_locale();
	if($lang=="he_IL"){
		if( have_rows('range', $category_id) ): $i=0;
			while ( have_rows('range', $category_id) ) : the_row();
			$i++;  
			$site = get_current_blog_id();
			if(isset($_POST['val'.$i]))
				$val=$_POST['val'.$i];
			$rangeName= get_sub_field('range_name');
			if ($val != null ){
				update_post_meta($post_id, $rangeName.$site,$val);
			}
			endwhile;    
		endif;
	}
	
	$lang=get_locale();
	if($lang=="en_US"){
		if( have_rows('range_en', $category_id) ): $i=0;
			while ( have_rows('range_en', $category_id) ) : the_row();
			$i++;  
			$site = get_current_blog_id();
			if(isset($_POST['val'.$i]))
				$val=$_POST['val'.$i];
			$rangeName= get_sub_field('range_name_en');
			if ($val != null ){
				update_post_meta($post_id, $rangeName.$site,$val);
			}
			endwhile;    
		endif;
	}

	
	echo "Success";
}

//create newsletter of single post 
add_action("wp_ajax_post_to_send","postToSend",10,1);
function postToSend()
{
	$user=wp_get_current_user();
	$site_info = get_bloginfo();
	if(isset($_POST['post']))
	   $post_id=$_POST['post'];
	if(isset($_POST['to']))
	   $list_email=$_POST['to'];
   
	if($list_email && sizeof($list_email)>0)
	{
	   $res = '<html>
				 <body>';
	   $res .= '<div style="display:flex; flex-direction: row; flex-wrap: wrap;">';
	   $post = get_post($post_id);
	   //object to send in mail
			$src=get_post_permalink($post->ID);
			$res .='<div style="padding-bottom: 15px; padding-right: 2%;">';
			$res .='<a href='. $src .'>';
			$src=get_the_post_thumbnail_url($post->ID);
			$res .='<img style="width: 200px; height: 150px;" src='. $src . '>';
			$res .='</a>';
			$src1=$post->post_title;
			$src=get_post_permalink($post->ID);
			$res .='<h3 style="font-size: 15px; text-transform: uppercase; margin-top: 0px; margin-bottom: 0px; padding-left: 2%;width: 210px;">
					 <a href=' . $src .'>'. $src1  . '</a></h3>';
			$res .='</div>';
	$res .=' </div>
			 </body>
	</html>';


		$mg = Mailgun::create('e0f2397c0d404569a8e0acf1ad56a35c-52cbfb43-f671d8b2');
		if($site_info == "kulam.org"){
			$from = $user->data->user_nicename . '@kulam.org';
		}
		else {
			$from = $user->data->user_nicename . '@' . $site_info . '.kulam.org';
		}
		# Now, compose and send your message.
		# $mg->messages()->send($domain, $params);
		foreach ($list_email as $to) {
			$mg->messages()->send('mg.kulam.org', [
				'from' => $from,
				'to' => $to,
				'subject' => $post->post_title,
				'html' => $res,
			]);
		}
	} 
  echo "Success";             
 }
//create newsletter of folder in my siddur
add_action("wp_ajax_folder_to_send","folderToSend",10,1);
function folderToSend()
{
	$site = get_current_blog_id();
	$site_info = get_bloginfo();
	$site_url = get_bloginfo("url");
	$user = wp_get_current_user();
	if(isset($_POST['f']))
		$folder=$_POST['f'];
	if(isset($_POST['to']))
		$list_email=$_POST['to'];
	$data_value = get_user_meta($user->ID,$folder . $site, true);
	$data_value = json_decode($data_value, true);
	if($list_email && sizeof($list_email)>0)
	{
		if($data_value){
			$res = '<html>
				 <body>';
			$res .='<table>
					<tr>';
			$index=1;
			foreach($data_value as $post_id):
			
				$post = get_post($post_id);
				//object to send in mail
				$src=get_post_permalink($post->ID);
				$res .='<td valign="baseline" width="200">';
				$res .='<a href='. $src .'>';
				$src=get_the_post_thumbnail_url($post->ID);
				$res .='<img width="200" height="150"  src='. $src . '>';
				$res .='</a>';
				$src1=$post->post_title;
				$src=get_post_permalink($post->ID);
				$res .='<h3 style="font-size: 15px; width: 200px; text-align: center;">
					 <a href=' . $src .'>'. $src1  . '</a></h3>';
				$res .='</td>';
				if($index%4 ===0):
					$res.='</tr><tr>';
				endif;
				$index++;
			endforeach;
			$res .='</tr></table>';

			$res .=' </div>
			   <div>'. $site_url .'</div>
			 </body>
	</html>';

	if($site_info == "Kulam.org"){
		$from = $user->data->user_nicename . '@' . $site_info;
	}
	else {
		$site_url = str_replace('https://','',$site_url);
		$from = $user->data->user_nicename . '@' . $site_url;
	}
	$mg = Mailgun::create('e0f2397c0d404569a8e0acf1ad56a35c-52cbfb43-f671d8b2');
	foreach ($list_email as $to) {
		$mg->messages()->send('mg.kulam.org', [
			'from' => $from,
			'to' => $to,
			'subject' => $folder,
			'html' => $res,
		]);

	}
		}

		echo "Success";
	}
}