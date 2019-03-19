<?php
/**
 * Live Uploads
 *
 * Display media from live environment as integrated media within local environment
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions
 * @version     1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_replace_image_urls
 *
 * @param   N/A
 * @return  N/A
 */
function kulam_replace_image_urls() {

	if ( defined( 'WP_SITEURL' ) && defined( 'LIVE_SITEURL' ) ) {

		if ( WP_SITEURL != LIVE_SITEURL ) {
			add_filter( 'wp_get_attachment_url', 'kulam_wp_get_attachment_url', 10, 2 );
		}

	}

}
add_action( 'init', 'kulam_replace_image_urls' );

/**
 * kulam_wp_get_attachment_url
 *
 * @param   $url (string)
 * @param	$post_id (int)
 * @return  (string)
 */
function kulam_wp_get_attachment_url( $url, $post_id ) {

	if ( $file = get_post_meta( $post_id, '_wp_attached_file', true ) ) {

		if ( ( $uploads = wp_upload_dir()) && false === $uploads['error'] ) {

			if ( file_exists( $uploads['basedir'] .'/'. $file ) ) {

				// return
				return $url;

			}

		}

	}

	// return
	return str_replace( WP_SITEURL, LIVE_SITEURL, $url );

}