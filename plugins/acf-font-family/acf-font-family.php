<?php

/*
Plugin Name: Advanced Custom Fields: Font Family
Plugin URI: PLUGIN_URL
Description: Extends ACF with Font Family select field
Version: 1.0.0
Author: Nir Goldberg
Author URI: http://www.htmline.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// check if class already exists
if( ! class_exists( 'htmline_acf_plugin_font_family' ) ) :

class htmline_acf_plugin_font_family {

	// vars
	var $settings;

	/*
	*  __construct
	*
	*  This function will setup the class functionality
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	void
	*  @return	void
	*/
	function __construct() {

		// settings
		// - these will be passed into the field class.
		$this->settings = array(
			'version'	=> '1.0.0',
			'url'		=> plugin_dir_url( __FILE__ ),
			'path'		=> plugin_dir_path( __FILE__ )
		);

		// classes
		add_action( 'init', array( $this, 'init_classes' ) );

		// include field
		add_action( 'acf/include_field_types', array( $this, 'include_field' ) ); // v5

	}

	/*
	*  init_classes
	*
	*  This function will include plugin classes
	*
	*  @param	void
	*  @return	void
	*/
	function init_classes() {

		// web fonts
		include_once( 'includes/class-web-fonts.php' );

	}

	/*
	*  include_field
	*
	*  This function will include the field type class
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	$version (int) major ACF version. Defaults to false
	*  @return	void
	*/
	function include_field( $version = false ) {

		// load textdomain
		load_plugin_textdomain( 'htmline-font-family', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );


		// include
		include_once('fields/class-htmline-acf-field-font-family-v' . $version . '.php');

	}

}

// initialize
new htmline_acf_plugin_font_family();

// class_exists check
endif;