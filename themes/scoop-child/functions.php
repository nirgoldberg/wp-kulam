<?php
/**
 * Functions
 *
 * @author      Nir Goldberg
 * @package     scoop-child
 * @version     1.1.1
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// live uploads
if ( defined( 'WP_SITEURL' ) && defined( 'LIVE_SITEURL' ) ) {
	require_once( 'functions/live-uploads.php' );
}

// theme functions
require_once ( 'functions/theme.php' );

// login modal
require_once ( 'functions/modal-login.php' );

// registration modal
require_once ( 'functions/modal-registration.php' );

// search modal
require_once ( 'functions/modal-search.php' );

// svgs
require_once ( 'functions/svgs.php' );

/**
 *     SETUP FUNCTIONS
 */
require_once ('includes/cpt-config.php');           // Register Custom Post Types & Taxonomies locally
require_once ('vendors/acf.php');                   // ACF Functions
require_once ('shortcodes.php');

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

		register_nav_menus( array(
			'homepage_tiles' => 'Homepage Tiles',
		) );

		// Set up the default content width
		$GLOBALS['content_width'] = apply_filters( 'kol_content_width', 1170 );
	}
endif;

add_action( 'after_setup_theme', 'kol_setup' );

/**
 *     SCRIPTS & STYLES
 */

function kol_add_scripts()
{
  
	wp_register_script('kol-js', get_stylesheet_directory_uri().'/assets/js/scripts.js', array('jquery'), null, true);
	wp_register_script('kol-js-favorite', get_stylesheet_directory_uri().'/assets/js/scriptsForThumbnail.js', array('jquery'), null, true);

	$params = array (
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'ajax_nonce' => wp_create_nonce('my-special-string'),
		'post_id' => get_queried_object_id(),
		'user_id' => get_current_user_id()
	);
	$paramsForThumbnail = array (
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'ajax_nonce' => wp_create_nonce('my-special-string'),
		'user_id' => get_current_user_id()
	);
	wp_localize_script('kol-js-favorite', 'ajaxdata', $paramsForThumbnail);
	wp_enqueue_script('kol-js-favorite');
	wp_localize_script('kol-js', 'ajaxdata', $params);
	wp_enqueue_script('kol-js');
}
add_action('wp_enqueue_scripts', 'kol_add_scripts');

//autocomplete search
add_action( 'wp_enqueue_scripts', 'my_theme_autocomplete' );
function my_theme_autocomplete() {	

	wp_enqueue_script('autocopmlete', get_stylesheet_directory_uri() . '/assets/js/auto-complete.min.js','',true);
	wp_enqueue_style('autocomplete_css', get_stylesheet_directory_uri() . '/assets/css/auto-complete.css');
	//wp_enqueue_script("streets",plugin_dir_url( __FILE__ ) . 'js/streets.js',array('jquery'),true);
	//wp_localize_script('streets', 'street', $street);
}

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

/**
 *     Helper function to get the current user's Siddur
 */

// function kulam_get_current_user_siddur_id() {
//     $siddur = 'siddur_' . get_current_user_id() . '_1';
//     return $siddur;
// }

/**
 *     Prepare a new query for category pages - posts filtered by post types
 */

function kulam_get_joint_query_args( $category, $post_type_slug, $is_filtered = false) {

	$tops = array(
		'how-to' => 'learn_how_-_top_posts',
		'music' => 'music_-_top_posts',
		'customs' =>'customs_-_top_posts',
		'thought' => 'ideas_-_top_posts',
		'misc' => 'misc_-_top_posts',
		'hack-he' => 'hack-he_-_top_posts',
		'1posts' => '1posts_-_top_posts',
	);

	$top = $tops[$post_type_slug];
	$joint_query_array = array();
	$top_posts = get_field($top, 'category_' . $category);

	if ($top_posts && 0 < count($top_posts)) :
		foreach ($top_posts as $p) {
			$joint_query_array[] = $p;
		}
	endif;

	$args = array(
		'category' => $category,
		'posts_per_page' => '-1',
		'exclude' => $top_posts,
		'ignore_sticky_posts' => true,
		'tax_query' => array(
			array(
				'taxonomy' => 'post_types_tax',
				'field'    => 'slug',
				'terms'    => $post_type_slug,
			),
		),
	);

	$rest_of_posts = get_posts($args);

	foreach ($rest_of_posts as $p) {
		$joint_query_array[] = $p->ID;
	}

	$joint_query_args = array(
		'post__in' => $joint_query_array,
		'posts_per_page' => (true == $is_filtered ? '-1' : '3' ),
		'ignore_sticky_posts' => true,
		'orderby' => 'post__in',
		'query_count' => count($joint_query_array),
	);

	return $joint_query_args;
}

/**
 *     Dynamically Add "My Siddur" to Navbar
 */

function kulam_add_siddur_menu_item ( $items, $args ) {
	$label = __("My Siddur", "kulam-scoop");
	$custom_label = get_field('album_label', 'options');
	if ($custom_label) :
		$label = $custom_label;
	endif;

	$uid = wp_get_current_user()->ID;
	$lang = get_locale();
	if ( $args->theme_location == 'primary' ) {

		if ( is_user_logged_in() ) {
			if ($lang == 'he_IL') {
				$items .= '<li class="menu-item my-siddur"><a href="/my-siddur" role="link"><span>' . $label . '</span></a></li>';
			}
			else{
				$items .= '<li class="menu-item my-siddur"><a href="/en/my-siddur" role="link"><span>' . $label . '</span></a></li>';
			}
		}
		else {
			$items .= '<li class="menu-item my-siddur"><a href="#" role="link" data-toggle="modal" data-target="#modal-login" data-redirect="/my-siddur" data-show-pre-text="true"><span>' . $label . '</span></a></li>';
		}
	}
	if (is_user_logged_in()) {
		$lang = get_locale();
		if ($lang == 'he_IL') {
			$items .= '<li class="menu-item logout"><a href="' . wp_logout_url(home_url()) . '"><span>התנתק</span></a></li>';
		}
		else{
			$items .= '<li class="menu-item logout"><a href="' . wp_logout_url(home_url()) . '"><span>Logout</span></a></li>';
		}
	}
	else{
		$items .= '<li class="menu-item logout"><a href="#" role="link" data-toggle="modal" data-target="#modal-login" data-redirect="#" data-show-pre-text="false"><span>' . __( 'Login', 'kulam-scoop' ) . '</span></a></li>';
	}

	return $items;
}
add_filter( 'wp_nav_menu_items', 'kulam_add_siddur_menu_item', 10, 2 );

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
//save post to sidur
add_action("wp_ajax_change_sidur","saveToSiddur",10,1);
function saveToSiddur(){
	$site = get_current_blog_id();
	if( isset( $_POST[ 'user' ] ) ) 
	{
	  $user_id=$_POST['user'];
	}
	if( isset( $_POST[ 'post' ] ) ) 
	{
	  $postid =$_POST[ 'post' ];
	}
	$data_value = get_user_meta($user_id,"sidur" . $site, true);
	if($data_value){
		$data_value = json_decode($data_value,true);
		if(!in_array($postid,$data_value)) {
			$data_value[] = $postid;
		}
	}
	else{
		$data_value =  array(
			0 => $postid
		);
	}
	$data_value = json_encode($data_value);
	update_user_meta( $user_id, "sidur" . $site, $data_value);
//add to favorite
$data_value = get_user_meta($user_id,"favorite" . $site, true);
if($data_value){
	$data_value = json_decode($data_value,true);
	if(!in_array($postid,$data_value)) {
		$data_value[] = $postid;
	}
}
else{
	$data_value =  array(
		0 => $postid
	);
}
$data_value = json_encode($data_value);
update_user_meta( $user_id, "favorite" . $site, $data_value);
}
add_action("wp_ajax_remove_sidur","removeSiddur",10,1);
function removeSiddur(){
	$site =get_current_blog_id();
	if( isset( $_POST[ 'user' ] ) )
	{
		$user_id = $_POST['user'];
	}
	if( isset( $_POST[ 'post' ] ) )
	{
		$postid = $_POST[ 'post' ];
	}
	$data_value = get_user_meta($user_id,"sidur" . $site, true);
	$data_value = json_decode($data_value,true);
	if($data_value){
		foreach ($data_value as $key => $post){
			if($post == $postid){
				unset($data_value[$key]);
				break;
			}
		}
	}
	$data_value = json_encode($data_value);
	update_user_meta( $user_id, "sidur" . $site , $data_value);
	//remove from favorite
	$data_value = get_user_meta($user_id,"favorite" . $site, true);
	$data_value = json_decode($data_value,true);
	if($data_value){
		foreach ($data_value as $key => $post){
			if($post == $postid){
				unset($data_value[$key]);
				break;
			}
		}
	}
	$data_value = json_encode($data_value);
	update_user_meta( $user_id, "favorite" . $site , $data_value);
	//remove from folder
	if(isset($_POST['fromFolder']))
	  $folder=$_POST['fromFolder'];
	  $data_value = get_user_meta($user_id,$folder . $site, true);
	  $data_value = json_decode($data_value,true);
	  if($data_value){
		  foreach ($data_value as $key => $post){
			  if($post == $postid){
				  unset($data_value[$key]);
				  break;
			  }
		  }
	  }
	  $data_value = json_encode($data_value);
	  update_user_meta( $user_id, $folder . $site , $data_value);
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
				$user->set_role('Subscriber');
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


function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}
add_action('after_setup_theme', 'remove_admin_bar');


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

	//   //avg-general
	// $generalrate=get_post_meta($post_id,'general'.$site);
	//   if($generalrate[0]!=$val1)
	//   {
	//    $countrateGeneral=get_post_meta($post_id,"countratingGeneral".$site);
	//     if(!$countrateGeneral)
	//         $countrateGeneral=0;
	//    update_post_meta($post_id,"countratingGeneral".$site,$countrateGeneral[0]+1);
	//    $sum=get_post_meta($post_id,"sumGeneral".$site);
	//    if(!$sum[0])
	//       $sum=$generalrate[0];
	//     else
	//       $sum=$sum[0];
	//    update_post_meta($post_id,"sumGeneral".$site,$sum+$val1);
	//   }
	//   //avg-religiosity
	// $religiosityrate=get_post_meta($post_id,'religiosity'.$site);
	//   if($religiosityrate[0]!=$val2)
	//   {
	//    $countrateReligiosity=get_post_meta($post_id,"countratingReligiosity".$site);
	//     if(!$countrateReligiosity)
	//         $countrateReligiosity=0;
	//    update_post_meta($post_id,"countratingReligiosity".$site,$countrateReligiosity[0]+1);
	//    $sum=get_post_meta($post_id,"sumReligiosity".$site);
	//    if(!$sum[0])
	//       $sum=$religiosityrate[0];
	//     else
	//       $sum=$sum[0];
	//    update_post_meta($post_id,"sumReligiosity".$site,$sum+$val2);
	//   }
	//   //avg-authentic
	//   $authenticrate=get_post_meta($post_id,'authentic'.$site);
	//   if($authenticrate[0]!=$val3)
	//   {
	//    $countrateAuthentic=get_post_meta($post_id,"countratingAuthentic".$site);
	//     if(!$countrateAuthentic)
	//         $countrateAuthentic=0;
	//    update_post_meta($post_id,"countratingAuthentic".$site,$countrateAuthentic[0]+1);
	//    $sum=get_post_meta($post_id,"sumAuthentic".$site);
	//    if(!$sum[0])
	//       $sum=$authenticrate[0];
	//     else
	//       $sum=$sum[0];
	//    update_post_meta($post_id,"sumAuthentic".$site,$sum+$val3);
	//   }
	
}
add_action("wp_ajax_add-folder","addFolder",10,1);
function addFolder()
{
	if(isset($_POST['nameFolder']))
	   $name_folder=$_POST['nameFolder'];
  $user=wp_get_current_user();
  $site=get_current_blog_id();

  $allFolders=get_user_meta($user->ID,"nameFolder".$site,true);
  if($allFolders){ 
	  $allFolders=json_decode($allFolders,true);
	  if(!in_array($name_folder,$allFolders))
	{
		$allFolders[]=$name_folder;
	}
	 
   } 
   else{
	   $allFolders =  array(
		0 => $name_folder
	); 
}
	$allFolders=json_encode($allFolders,JSON_UNESCAPED_UNICODE);
	update_user_meta($user->ID,"nameFolder".$site,$allFolders);
	echo "Success";
 }

add_action("wp_ajax_save-in-folder","saveInFolder",10,1);
function saveInFolder(){
	if(isset($_POST['idPost'])&& isset($_POST['selectedOption']))
	$idPost=$_POST['idPost'];
	$folder=$_POST['selectedOption'];
	$user=wp_get_current_user();
	$site=get_current_blog_id();

$data_value = get_user_meta($user->ID,$folder . $site, true);
if($data_value){
	$data_value = json_decode($data_value,true);
	if(!in_array($idPost,$data_value)) {
		$data_value[] = $idPost;
	}
}
else{
	$data_value =  array(
		0 => $idPost
	);
}
$data_value = json_encode($data_value);
update_user_meta( $user->ID, $folder . $site, $data_value);
//remove from siddur
$data_value_siddur = get_user_meta($user->ID,"sidur" . $site, true);
$data_value_siddur = json_decode($data_value_siddur,true);
if($data_value_siddur){
	foreach ($data_value_siddur as $key => $post){
		if($post == $idPost){
			unset($data_value_siddur[$key]);
			break;
		}
	}
}
$data_value_siddur = json_encode($data_value_siddur);
update_user_meta( $user->ID, "sidur" . $site , $data_value_siddur);

//add to favorite
$data_value = get_user_meta($user->ID,"favorite" . $site, true);
if($data_value){
	$data_value = json_decode($data_value,true);
	if(!in_array($idPost,$data_value)) {
		$data_value[] = $idPost;
	}
}
else{
	$data_value =  array(
		0 => $idPost
	);
}
$data_value = json_encode($data_value);
update_user_meta( $user->ID, "favorite" . $site, $data_value);
echo "Success";
}
add_action("wp_ajax_remove-post-from-folder","removeFromFolder",10,1);
function removeFromFolder(){
	if(isset($_POST['postRemove'])&& isset($_POST['from_name_folder']))
	{
	  $post=$_POST['postRemove'];
	  $folder=$_POST['from_name_folder'];
	  $user=wp_get_current_user();
	  $site=get_current_blog_id();
	  $postsInFolders=get_user_meta($user->ID,$folder.$site,true);
	  $postsInFolders=json_decode($postsInFolders,true);
	   if($postsInFolders){
		foreach ($postsInFolders as $key => $postf){
			if($postf == $post){
				unset($postsInFolders[$key]);
				break;
			}
		}
	}
	$postsInFolders = json_encode($postsInFolders);
	update_user_meta( $user->ID, $folder . $site , $postsInFolders);

	//added to sidur
	$data_value = get_user_meta($user->ID,"sidur" . $site, true);
if($data_value){
	$data_value = json_decode($data_value,true);
	if(!in_array($post,$data_value)) {
		$data_value[] = $post;
	}
}
else{
	$data_value =  array(
		0 => $post
	);
}
$data_value = json_encode($data_value);
update_user_meta( $user->ID, "sidur" . $site, $data_value);
echo "Success";
   }
}
//save data to table public_folders
function public_folder($name_folder){
	global $wpdb;
	$user=wp_get_current_user();
	$site=get_current_blog_id();
	$lang=get_locale();
	$table_name = $wpdb->prefix . 'public_folders';
	$wpdb->insert($table_name, array(
		'folder_name' => $name_folder,
		'id_user' => $user->ID,
		'id_site' => $site,
		'lang'=>$lang,
	));
	echo "Success";
	
}
add_action("wp_ajax_setting-folder","settingFolder",10,1);
function settingFolder(){
	if(isset($_POST['name_old_folder']))
	   $old_folder=$_POST['name_old_folder'];
	if(isset($_POST['name_new_folder'])) 
	   $new_folder=$_POST['name_new_folder'];
	if(isset($_POST['delete_folder']))
	   $delete_folder=$_POST['delete_folder'];
	if(isset($_POST['public_folder']))
	   $public_folder=$_POST['public_folder'];
	$user=wp_get_current_user();
	$site=get_current_blog_id();

	if( $delete_folder=="true"){
		//remove post that in this folder from favorite
		$postsToRemoveFromFavorite=get_user_meta($user->ID,$old_folder.$site,true);
		 $postsToRemoveFromFavorite=json_decode($postsToRemoveFromFavorite,true);
		$favorite=get_user_meta($user->ID,"favorite".$site,true);
		$favorite=json_decode($favorite,true);
		foreach($postsToRemoveFromFavorite as $key=>$post)
		{
		   foreach($favorite as $key=>$postFavorite)
			  {
				  if($post==$postFavorite)
				  {
					 unset($favorite[$key]);
					break;
				  }
			  }
		}
		$favorite=json_encode($favorite,true);
		update_user_meta($user->ID,"favorite".$site,$favorite);
		//delete folder
		delete_user_meta($user->ID,$old_folder.$site);
		$namFolder=get_user_meta($user->ID,"nameFolder".$site,true);
	   
	   if($namFolder){
		$namFolder = json_decode($namFolder,true);
		   foreach ($namFolder as $key => $fold){
		if($fold == $old_folder){
			unset($namFolder[$key]);
			break;
		}
	}
}
	$namFolder = json_encode($namFolder,JSON_UNESCAPED_UNICODE);
	update_user_meta( $user->ID, "nameFolder" . $site , $namFolder);
	   
	   echo "Success";
	   exit;
	}
	if($public_folder=="true"){
		public_folder($old_folder);
	}
	if($public_folder=="false")
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'public_folders';
		$lang = get_locale();
		$sqlQuery="SELECT * FROM ". $table_name ." WHERE `folder_name` = '". $old_folder ."' AND `id_user` = '".$user->ID."' AND `id_site` = '".$site ."'AND `lang` = '".$lang ."'"; 
		$result = $wpdb->get_results($sqlQuery,OBJECT);
		if($result)
		{
		   $id= $result[0]->folder_id;
			$wpdb->delete( $table_name, array( 'folder_id' => $id ));
		}
	}
	if($new_folder)
	{
	if($old_folder!= $new_folder)
	{ //change name in public
		global $wpdb;
		$table_name = $wpdb->prefix . 'public_folders';
		$lang = get_locale();
		$sqlQuery="SELECT * FROM ". $table_name ." WHERE `folder_name` = '". $old_folder ."' AND `id_user` = '".$user->ID."' AND `id_site` = '".$site ."'AND `lang` = '".$lang ."'"; 
		$result = $wpdb->get_results($sqlQuery,OBJECT);
		if($result)
		 {
			$wpdb->update( $table_name, array( 'folder_name' => $new_folder),array('id_user'=>$user->ID,'folder_name'=>$old_folder));

		 }
	  //  
	   $folder= get_user_meta($user->ID,"nameFolder".$site,true);
	   if($folder){
		 $folder=json_decode($folder,true);
	   foreach($folder as $key=>$val)
	   {
		if($old_folder==$folder[$key])
		 {
		   $folder[$key]=$new_folder;
		   break;
		 }
		}
	   $folder= json_encode($folder,JSON_UNESCAPED_UNICODE);
	}
		update_user_meta($user->ID,"nameFolder".$site,$folder);
		$f=get_user_meta($user->ID,$old_folder.$site,true);
		delete_user_meta($user->ID,$old_folder.$site);
		add_user_meta($user->ID,$new_folder.$site,$f,true);
		echo "Success";
	}
	}
}

add_action('wp_ajax_check-share-public','checkFolderPublic',10,1);
function checkFolderPublic()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'public_folders';
	$site=get_current_blog_id();
	$user=wp_get_current_user();
	$lang=get_locale();
	if(isset($_POST['namefolder']))
	   $name_folder=$_POST['namefolder'];
	$sqlQuery="SELECT * FROM  ". $table_name ." WHERE `folder_name` =  '". $name_folder ."' AND `id_user` = '".$user->ID."'  AND `id_site` = '".$site ."'AND `lang` = '".$lang ."'";
	$result = $wpdb->get_results($sqlQuery,OBJECT);
	  if($result)
	   {
		if ( preg_match('/\s/',$name_folder) ) 
		{
		  $name_folder = str_replace(' ', '_', $name_folder);
		}
		  // return  response - url to public folder 
		  if ($lang == 'he_IL'){
			  $var ='/single-public-folder?folder='. $name_folder."&u=". $user->ID."&si=". $site;
			  if(isset($_POST['clipboard']))
				echo $var;
			  else 
				echo urlencode($var);
		  }
		else
		{
			 $var ='/en/single-public-folder?folder='. $name_folder."&u=". $user->ID."&si=". $site;
			 if(isset($_POST['clipboard']))
			 echo $var;
		   else 
			 echo urlencode($var);
		}

	   }
	   else
	   { 
		echo "no";
	   }
	
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
//        $headers = "From:".$user->user_login."\r\n";
//        $headers.="Replay-To:ahadassa.cambium.co.il\r\n";
//        $headers.="MIME-Version:1.0\r\n";
//        // $headers.="Content-type:text/html; charset*utf-8";
//        $headers .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
//        for($i=0;$i<sizeof($list_email);$i++)
//        {
//             $to=$list_email[$i];
//             mail($to,$folder,$res,$headers);
//        }
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
			//cdaniel@cambium.co.il cnetalee@cambium.co.il
//            $headers = "From:".$user->user_login."\r\n";
//            $headers.="Replay-To:ahadassa.cambium.co.il\r\n";
//            // $headers.="MIME-Version:1.0\r\n";
//            $headers.= 'MIME-Version: 1.0' . "\r\n";
//            $headers.= 'Content-type: text/html; charset=utf-8' . "\r\n";
//            for($i=0;$i<sizeof($list_email);$i++)
//            {
//                $to=$list_email[$i];
//                mail($to,$folder,$res,$headers);
//            }


		}

		echo "Success";
	}
}

// register Sidurim post type
// function create_post_type_sidurim() {
// 	register_post_type( 'sidurim',
// 	  array(
// 		'labels' => array(
//             'name' => __( 'סידורים' ),
//             'singular_name' => __( 'סידור' ),
//             'add_new' => __( 'הוסף סידור' )		
// 		),
// 		'public' => true,
// 		'has_archive' => true,
// 		'supports' => array('title', 'editor', 'thumbnail', 'post-formats'),
//         'menu_icon' => 'dashicons-universal-access'	
// 	  )
// 	);
//   }
//   add_action( 'init', 'create_post_type_sidurim' );

function rename_post_formats( $safe_text ) {
	if ( $safe_text == 'Standard' )
		return 'Text';

	return $safe_text;
}
add_filter( 'esc_html', 'rename_post_formats' );

//rename Standard in posts list table
function live_rename_formats() { 
	global $current_screen;

	if ( $current_screen->id == 'edit-post' ) { ?>
		<script type="text/javascript">
		jQuery('document').ready(function() {

			jQuery("span.post-state-format").each(function() { 
				if ( jQuery(this).text() == "Standard" )
					jQuery(this).text("Text");             
			});

		});      
		</script>
<?php }
}
add_action('admin_head', 'live_rename_formats');