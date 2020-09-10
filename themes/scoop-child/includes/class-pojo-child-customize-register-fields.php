<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pojo_Scoop_Customize_Register_Field {

	public function section_logo( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'image_logo_width',
			'title' => __( 'Logo Width', 'kulam-scoop' ),
			'std'   => '100px',
			'selector' => '.logo .logo-img a img',
			'change_type' => 'width',
		);

		$sections[] = array(
			'id' => 'logo',
			'title' => __( 'Logo', 'kulam-scoop' ),
			'desc' => '',
			'fields' => $fields,
		);

		return $sections;
	}

	public function __construct() {
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_logo' ), 110 );
	}

}
new Pojo_Scoop_Customize_Register_Field();