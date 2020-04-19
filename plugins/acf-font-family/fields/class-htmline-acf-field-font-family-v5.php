<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// check if class already exists
if( ! class_exists( 'htmline_acf_field_font_family' ) ) :

class htmline_acf_field_font_family extends acf_field {

	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	function __construct( $settings ) {

		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/
		$this->name = 'font_family';

		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		$this->label = __( 'Font Family', 'htmline-font-family' );

		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/
		$this->category = 'basic';

		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/

		$this->defaults = array(
			'font_family'	=> '0',
		);

		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('font_family', 'error');
		*/
		$this->l10n = array();

		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/
		$this->settings = $settings;

		// do not delete!
    	parent::__construct();

	}

	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	function render_field_settings( $field ) {

		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/
		acf_render_field_setting( $field, array(
			'label'			=> __( 'Font Family', 'htmline-font-family' ),
			'instructions'	=> __( 'Default font family', 'htmline-font-family' ),
			'type'			=> 'select',
			'choices'		=> htmline_acf_web_fonts::get_web_fonts_choices(),
			'name'			=> 'font_family',
		));

	}

	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	function render_field( $field ) {

		?>

		<select id="<?php echo esc_attr( $field['id'] ); ?>" class="<?php echo esc_attr( $field['class'] ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>" data-ui="0" data-ajax="0" data-multiple="0" data-placeholder="Select" data-allow_null="0">
			<?php
				foreach ( htmline_acf_web_fonts::get_web_fonts() as $k_family => $v_family ) : ?>
					<option data-font_type="<?php echo $v_family; ?>" value="<?php echo $k_family; ?>" <?php selected( $field[ 'value' ], $k_family ); ?> <?php echo $field[ 'value' ] == $k_family ? 'data-i="0"' : ''; ?>><?php echo $k_family; ?></option>
				<?php endforeach;
			?>
		</select>

		<?php

	}

}

// initialize
new htmline_acf_field_font_family( $this->settings );

// class_exists check
endif;