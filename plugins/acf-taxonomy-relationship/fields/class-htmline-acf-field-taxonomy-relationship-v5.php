<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('htmline_acf_field_taxonomy_relationship') ) :


class htmline_acf_field_taxonomy_relationship extends acf_field {


	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	$settings (array)
	*  @return	n/a
	*/

	function __construct( $settings ) {

		/*
		*  name (string)
		*/

		$this->name = 'taxonomy_relationship';


		/*
		*  label (string)
		*/

		$this->label = __('Taxonomy Relationship', 'acf-taxonomy-relationship');


		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/

		$this->category = 'relational';


		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/

		$this->defaults = array(
			'post_type'			=> array(),
			'min' 				=> 0,
			'max' 				=> 0,
			'filters'			=> array( 'search', 'post_type' ),
			'return_format'		=> 'object',
		);


		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/

		$this->settings = $settings;


		// do not delete!
		parent::__construct();

	}


	/*
	*  initialize
	*
	*  This function will initialize the field type
	*
	*  @type	function
	*  @date	27/6/17
	*  @since	5.6.0
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function initialize() {

		// extra
		add_action( 'wp_ajax_acf/fields/taxonomy_relationship/query',			array( $this, 'ajax_query' ) );
		add_action( 'wp_ajax_nopriv_acf/fields/taxonomy_relationship/query',	array( $this, 'ajax_query' ) );

	}


	/*
	*  input_admin_enqueue_scripts
	*
	*  description
	*
	*  @type	function
	*  @date	16/12/2015
	*  @since	5.3.2
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function input_admin_enqueue_scripts() {

		// vars
		$url = $this->settings[ 'url' ];
		$version = $this->settings[ 'version' ];


		// localize
		acf_localize_text(array(
			//'Minimum values reached ( {min} values )'	=> __( 'Minimum values reached ( {min} values )', 'acf' ),
			'Maximum values reached ( {max} values )'	=> __( 'Maximum values reached ( {max} values )', 'acf' ),
			'Loading'									=> __( 'Loading', 'acf' ),
			'No matches found'							=> __( 'No matches found', 'acf' ),
		));


		// register & include JS
		wp_register_script( 'acf-taxonomy-relationship', "{$url}assets/js/input.js", array( 'acf-input' ), $version );
		wp_enqueue_script( 'acf-taxonomy-relationship' );


		// register & include CSS
		wp_register_style( 'acf-taxonomy-relationship', "{$url}assets/css/input.css", array( 'acf-input' ), $version );
		wp_enqueue_style( 'acf-taxonomy-relationship' );

	}


	/*
	*  ajax_query
	*
	*  description
	*
	*  @type	function
	*  @date	24/10/13
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function ajax_query() {

		// validate
		if ( ! acf_verify_ajax() ) die();


		// get choices
		$response = $this->get_ajax_query( $_POST );


		// return
		acf_send_ajax_results( $response );

	}


	/*
	*  get_ajax_query
	*
	*  This function will return an array of data formatted for use in a select2 AJAX response
	*
	*  @type	function
	*  @date	15/10/2014
	*  @since	5.0.9
	*
	*  @param	$options (array)
	*  @return	(array)
	*/

	function get_ajax_query( $options = array() ) {

		// defaults
		$options = wp_parse_args($options, array(
			'post_id'		=> 0,
			's'				=> '',
			'field_key'		=> '',
			'paged'			=> 1,
			'post_type'		=> '',
		));


		// load field
		$field = acf_get_field( $options[ 'field_key' ] );
		if ( ! $field ) return false;


		// vars
		$results = array();
		$args = array();
		$s = false;
		$is_search = false;


		// paged
		$args[ 'paged' ] = intval( $options[ 'paged' ] );


		// search
		if ( $options[ 's' ] !== '' ) {

			// strip slashes (search may be integer)
			$s = wp_unslash( strval( $options[ 's' ] ) );


			// update vars
			$args[ 's' ] = $s;
			$is_search = true;

		}


		// post_type
		if ( ! empty( $options[ 'post_type' ] ) ) {

			$args[ 'post_type' ] = acf_get_array( $options[ 'post_type' ] );

		} elseif ( ! empty( $field[ 'post_type' ] ) ) {

			$args[ 'post_type' ] = acf_get_array( $field[ 'post_type' ] );

		} else {

			$args[ 'post_type' ] = acf_get_post_types();

		}


		// filters
		$args = apply_filters( 'acf/fields/taxonomy_relationship/query', $args, $field, $options[ 'post_id' ] );
		$args = apply_filters( 'acf/fields/taxonomy_relationship/query/name=' . $field[ 'name' ], $args, $field, $options[ 'post_id' ] );
		$args = apply_filters( 'acf/fields/taxonomy_relationship/query/key=' . $field[ 'key' ], $args, $field, $options[ 'post_id' ] );


		// get taxonomies grouped by post type
		$groups = acf_get_grouped_taxonomies( $args );


		// bail early if no taxonomies
		if ( empty( $groups ) ) return false;


		// loop
		foreach ( array_keys( $groups ) as $group_title ) {

			// vars
			$taxonomies = acf_extract_var( $groups, $group_title );


			// data
			$data = array(
				'text'		=> $group_title,
				'children'	=> array()
			);


			// order taxonomies by search
			if ( $is_search ) {

				$taxonomies = acf_order_taxonomies_by_search( $taxonomies, $args[ 's' ] );

			}


			if ( ! $taxonomies )
				continue;

			// append to $data
			foreach ( array_keys( $taxonomies ) as $tax_name ) {

				$data['children'][] = $this->get_taxonomy_result( $tax_name, $taxonomies[ $tax_name ]->labels->singular_name );

			}


			// append to $results
			$results[] = $data;

		}


		// add as optgroup or results
		if ( count( $args[ 'post_type' ] ) == 1 ) {

			$results = $results[0][ 'children' ];

		}


		// vars
		$response = array(
			'results' => $results,
		);


		// return
		return $response;

	}


	/*
	*  get_taxonomy_result
	*
	*  This function will return an array containing id, text and maybe description data
	*
	*  @type	function
	*  @date	7/07/2016
	*  @since	5.4.0
	*
	*  @param	$id (mixed)
	*  @param	$text (string)
	*  @return	(array)
	*/

	function get_taxonomy_result( $id, $text ) {

		// vars
		$result = array(
			'id'	=> $id,
			'text'	=> $text
		);


		// return
		return $result;

	}


	/*
	*  render_field
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function render_field( $field ) {

		// vars
		$post_type = acf_get_array( $field[ 'post_type' ] );
		$filters = acf_get_array( $field[ 'filters' ] );

		// filters
		$filter_count = count( $filters );
		$filter_post_type_choices = array();

		// post_type filter
		if ( in_array( 'post_type', $filters ) ) {
			$filter_post_type_choices = array(
				''	=> __( 'Select post type', 'acf' )
			) + acf_get_pretty_post_types( $post_type );
		}

		// div attributes
		$atts = array(
			'id'				=> $field[ 'id' ],
			'class'				=> "acf-relationship {$field['class']}",
			'data-min'			=> $field[ 'min' ],
			'data-max'			=> $field[ 'max' ],
			'data-s'			=> '',
			'data-paged'		=> 1,
			'data-post_type'	=> '',
		);

		?>
<div <?php acf_esc_attr_e( $atts ); ?>>

	<?php acf_hidden_input( array( 'name' => $field[ 'name' ], 'value' => '' ) ); ?>

	<?php

	/* filters */
	if ( $filter_count ): ?>
	<div class="filters -f<?php echo esc_attr( $filter_count ); ?>">
		<?php

		/* search */
		if ( in_array( 'search', $filters ) ): ?>
		<div class="filter -search">
			<?php acf_text_input( array( 'placeholder' => __( "Search...",'acf' ), 'data-filter' => 's' ) ); ?>
		</div>
		<?php endif;


		/* post_type */
		if ( in_array( 'post_type', $filters ) ): ?>
		<div class="filter -post_type">
			<?php acf_select_input( array( 'choices' => $filter_post_type_choices, 'data-filter' => 'post_type' ) ); ?>
		</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<div class="selection">
		<div class="choices">
			<ul class="acf-bl list choices-list"></ul>
		</div>
		<div class="values">
			<ul class="acf-bl list values-list">
			<?php if ( ! empty( $field[ 'value' ] ) ) :

				// get taxonomies
				$taxonomies = acf_get_taxonomy_labels( $field[ 'value' ] );


				// loop
				foreach ( $taxonomies as $tax_name => $tax_label ): ?>
					<li>
						<?php acf_hidden_input( array( 'name' => $field[ 'name' ].'[]', 'value' => $tax_name ) ); ?>
						<span data-id="<?php echo esc_attr( $tax_name ); ?>" class="acf-rel-item">
							<?php echo $tax_label; ?>
							<a href="#" class="acf-icon -minus small dark" data-name="remove_item"></a>
						</span>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
			</ul>
		</div>
	</div>
</div>
		<?php
	}


	/*
	*  render_field_settings
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/

	function render_field_settings( $field ) {

		// vars
		$field[ 'min' ] = empty( $field[ 'min' ] ) ? '' : $field[ 'min' ];
		$field[ 'max' ] = empty( $field[ 'max' ] ) ? '' : $field[ 'max' ];


		// post_type
		acf_render_field_setting( $field, array(
			'label'			=> __( 'Filter by Post Type','acf' ),
			'instructions'	=> '',
			'type'			=> 'select',
			'name'			=> 'post_type',
			'choices'		=> acf_get_pretty_post_types(),
			'multiple'		=> 1,
			'ui'			=> 1,
			'allow_null'	=> 1,
			'placeholder'	=> __( "All post types",'acf' ),
		));


		// filters
		acf_render_field_setting( $field, array(
			'label'			=> __( 'Filters','acf' ),
			'instructions'	=> '',
			'type'			=> 'checkbox',
			'name'			=> 'filters',
			'choices'		=> array(
				'search'		=> __( "Search",'acf' ),
				'post_type'		=> __( "Post Type",'acf' ),
			),
		));


		// min
		acf_render_field_setting( $field, array(
			'label'			=> __( 'Minimum Taxonomies','acf' ),
			'instructions'	=> '',
			'type'			=> 'number',
			'name'			=> 'min',
		));


		// max
		acf_render_field_setting( $field, array(
			'label'			=> __( 'Maximum Taxonomies','acf' ),
			'instructions'	=> '',
			'type'			=> 'number',
			'name'			=> 'max',
		));


		// return_format
		acf_render_field_setting( $field, array(
			'label'			=> __( 'Return Format','acf' ),
			'instructions'	=> '',
			'type'			=> 'radio',
			'name'			=> 'return_format',
			'choices'		=> array(
				'object'		=> __( "Taxonomy Object",'acf' ),
				'name'			=> __( "Taxonomy Name",'acf' ),
			),
			'layout'	=>	'horizontal',
		));

	}


	/*
	*  format_value
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/

	function format_value( $value, $post_id, $field ) {

		// bail early if no value
		if ( empty( $value ) ) {

			return $value;

		}


		// force value to array
		$value = acf_get_array( $value );


		// load taxonomies if needed
		if ( $field[ 'return_format' ] == 'object' ) {

			$taxonomies = array();

			foreach ( $value as $tax_name ) {
				$taxonomies[] = get_taxonomy( $tax_name );
			}

			$value = $taxonomies;

		}


		// return
		return $value;

	}


	/*
	*  validate_value
	*
	*  description
	*
	*  @type	function
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (int)
	*  @return	$post_id (int)
	*/

	function validate_value( $valid, $value, $field, $input ){

		// default
		if ( empty( $value ) || ! is_array( $value ) ) {

			$value = array();

		}


		// min
		if ( count( $value ) < $field[ 'min' ] ) {

			$valid = _n( '%s requires at least %s selection', '%s requires at least %s selections', $field[ 'min' ], 'acf' );
			$valid = sprintf( $valid, $field[ 'label' ], $field[ 'min' ] );

		}


		// return
		return $valid;

	}


}


// initialize
new htmline_acf_field_taxonomy_relationship( $this->settings );


// class_exists check
endif;

?>