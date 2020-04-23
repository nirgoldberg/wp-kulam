<?php
/**
 * Author: Itay Banner
 * Date: 05/01/2018
 * Time: 21:51
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

function exodus_acf_init() {
    acf_update_setting('l10n_textdomain', 'kulam-scoop');
}

add_action('acf/init', 'exodus_acf_init');

/****************************************/

function exodus_frontend_change_labels( $field ) {

    // Only do it on the front end
    if( !is_admin() ){

        // Check if the current field is post title
        if( $field['name'] == 'post_title' ){

            // Change the label
            $field['label'] = 'Foo!';
        }
    }
    return $field;
}

add_filter('acf/prepare_field', 'exodus_frontend_change_labels');

/****************************************/

add_filter('acf/validate_value/type=oembed', 'exodus_acf_validate_oembed', 10, 4);

function exodus_acf_validate_oembed( $valid, $value, $field, $input ){

    // - Supported YouTube URL formats:
    //   - http://www.youtube.com/watch?v=My2FRPA3Gf8
    //   - http://youtu.be/My2FRPA3Gf8
    // - Supported Vimeo URL formats:
    //   - http://vimeo.com/25451551
    //   - http://player.vimeo.com/video/25451551
    // - Also supports relative URLs:
    //   - //player.vimeo.com/video/25451551
    // Source: http://stackoverflow.com/questions/5612602/improving-regex-for-parsing-youtube-vimeo-urls

    // bail early if value is already invalid
    if( empty($value) || !$valid ) {return $valid;}

    $pattern = '/(http:\/\/|https:\/\/|)(player.|www.)?(vimeo\.com|youtube\.com|youtu\.be)\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/';
    $match = preg_match($pattern, $value);

    if ( !empty($value) && $match != 1) {
        $valid = "This isn't a valid video URL (Currently supporting YouTube and Vimeo only).";
    }

    return $valid;
}

/****************************************/

function exodus_kses_post( $value ) {

    // is array
    if( is_array($value) ) {
        return array_map('exodus_kses_post', $value);
    }

    // return
    return wp_kses_post( $value );
}
add_filter('acf/update_value', 'exodus_kses_post', 10, 1);

/*******************************************/

if (! function_exists('exodus_acf_oembed_filter')) {

    function exodus_acf_oembed_filter( $field, array $params = array(), $attributes = "" ) {

        /**
         * Based on what I found here: https://www.advancedcustomfields.com/resources/oembed/
         */

        // get iframe HTML
        $iframe = get_field($field);

        // use preg_match to find iframe src
        preg_match('/src="(.+?)"/', $iframe, $matches);

        if ( isset($matches[1]) ) {

            $src = $matches[1];
            $new_src = add_query_arg($params, $src);

            $iframe = str_replace($src, $new_src, $iframe);

            // add extra attributes to iframe html

            $iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe);

            // surround with a wrapper for better responsiveness
            $iframe = '<div class="wrapper--oembed">' . $iframe . '</div>';

        } else {
            $iframe = false;
        }

        // return $iframe
        return $iframe;
    }

}

/*******************************************/

if (! function_exists('exodus_acf_oembed_strip')) {

    function exodus_acf_oembed_strip($field) {

        /**
         * Based on what I found here: https://www.advancedcustomfields.com/resources/oembed/
         */

        $params = array(
            'controls'      => 1,
            'hd'            => 1,
            'autohide'      => 1,
            'showinfo'      => 1,
            'rel'           => 0,
        );

        $iframe = exodus_acf_oembed_filter($field,$params);

        if ( false == $iframe ) {
            $iframe = '<div class="video-unavailable">' . esc_html__('Video Unavailable', 'kulam-scoop') . '</div>';
        }

        echo $iframe;
    }
}



/*******************************************/

add_filter('acf/settings/save_json', 'exodus_acf_json_save_point');

function exodus_acf_json_save_point( $path ) {

    // update path
    $path = get_stylesheet_directory() . '/acf-json';


    // return
    return $path;

}

/**************************/

function kol_get_acf_form_args($form) {
    global $wp;
    $args = array();
    switch ($form) {
        case 'upload':
            $args = array(

                'id' => 'share-post',
                'post_id' => 'new_post',
                'new_post' => array(
                    'post_type'     => 'post',
                    'post_status'   => 'pending'
                ),
                'post_title' => true,
                'post_content' => true,
                'field_groups' => array( 'group_5e865fad1f3c4' ),
                'submit_value' => __("Submit for review", 'kulam-scoop'),
                'updated_message' => __("<b>Success!</b> Your article was submitted successfully and will be reviewed shortly.", 'kulam-scoop'),
                'return' => add_query_arg( 'updated', 'true', home_url( $wp->request ) ),
                // 'honeypot' => true

            );
            break;
        case 'user_profile':
            $uid = get_current_user_id();
            $args = array(
                'post_id' => 'user_' . $uid,
                'field_groups' => array('1229'),
                'submit_value' => __('Update Account', 'kulam-scoop'),
                'updated_message' => __('<b>Success!</b> Your account is now up-to-date with the new info.', 'kulam-scoop'),
            );
            break;
        default:
            break;
    }
    return $args;
}