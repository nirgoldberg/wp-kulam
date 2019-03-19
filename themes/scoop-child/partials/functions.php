<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *     SETUP FUNCTIONS
 */

require_once ('includes/cpt-config.php');           // Register Custom Post Types & Taxonomies locally
require_once ('vendors/acf.php');                   // ACF Functions
require_once ('shortcodes.php');                   // Theme Shortcodes

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
    wp_register_script('kol-js', get_stylesheet_directory_uri().'/assets/js/scripts.js','jquery');
    $params = array (
        'ajaxurl' => admin_url('admin-ajax.php'),
        'ajax_nonce' => wp_create_nonce('my-special-string'),
        'post_id' => get_queried_object_id(),
        'user_id' => get_current_user_id()
    );
    $labels = array(
        'add' => __('Add to My Siddur', 'kulam-scoop'),
        'remove' => __('Remove from My Siddur', 'kulam-scoop'),
    );
    wp_localize_script('kol-js', 'ajaxdata', $params);
    wp_localize_script('kol-js', 'labels', $labels);
    wp_enqueue_script('kol-js');
}
add_action('wp_enqueue_scripts', 'kol_add_scripts');

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

add_filter( 'wp_nav_menu_items', 'kulam_add_siddur_menu_item', 10, 2 );
function kulam_add_siddur_menu_item ( $items, $args ) {
    $label = __("My Siddur", "kulam-scoop");
    $custom_label = get_field('album_label', 'options');
    if ($custom_label) :
        $label = $custom_label;
    endif;
    $uid = wp_get_current_user()->ID;
    if ( $args->theme_location == 'primary' ) {
        $items .= '<li class="menu-item my-siddur"><a href="/siddur/?=siddur_' . $uid . '_1" role="link"><span>' . $label . '</span></a></li>';
    }
    return $items;
}
