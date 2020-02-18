<?php
/**
 * ACF Field Groups
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions/acf
 * @version		1.5.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * ACF register field groups
 *
 * @fieldgroup	ACF Front-End Form
 * @fieldgroup	Ratings
 * @fieldgroup	Category Attributes
 * @fieldgroup	User Information
 * @fieldgroup	My Siddur Settings
 * @fieldgroup	General Settings
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5a4fc58481113',
	'title' => __('ACF Front-End Form', 'kulam-scoop'),
	'fields' => array(
		array(
			'key' => 'field_5a4fc5849fdd9',
			'label' => __('Display Front-End Form', 'kulam-scoop'),
			'name' => 'display_acf_form',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => __('Display Front-End Form', 'kulam-scoop'),
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5a4fdd3871d9b',
			'label' => __('ACF Form', 'kulam-scoop'),
			'name' => 'acf_form',
			'type' => 'select',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5a4fc5849fdd9',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'upload' => __('upload', 'kulam-scoop'),
				'user_profile' => __('user_profile', 'kulam-scoop'),
			),
			'default_value' => array(
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 1,
			'ajax' => 0,
			'return_format' => 'value',
			'placeholder' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'page',
			),
		),
	),
	'menu_order' => 1,
	'position' => 'side',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

acf_add_local_field_group(array(
	'key' => 'group_5c4f03398b667',
	'title' => __('Ratings', 'kulam-scoop'),
	'fields' => array(
		array(
			'key' => 'field_5c4f027d245b5',
			'label' => __('דירוג', 'kulam-scoop'),
			'name' => 'range',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => '',
			'sub_fields' => array(
				array(
					'key' => 'field_5c4f0299245b6',
					'label' => __('שם דירוג', 'kulam-scoop'),
					'name' => 'range_name',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
			),
		),
		array(
			'key' => 'field_5c4f030cb353a',
			'label' => __('ratings', 'kulam-scoop'),
			'name' => 'range_en',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => '',
			'sub_fields' => array(
				array(
					'key' => 'field_5c4f0329b353b',
					'label' => __('rating name', 'kulam-scoop'),
					'name' => 'range_name_en',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'category',
			),
		),
	),
	'menu_order' => 2,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
	'modified' => 1560431236,
));

acf_add_local_field_group(array(
	'key' => 'group_5a37e01e4f32b',
	'title' => __('Category Attributes', 'kulam-scoop'),
	'fields' => array(
		array(
			'key' => 'field_5d024ba641baa',
			'label' => __('Background Image', 'kulam-scoop'),
			'name' => 'acf-category_background_image',
			'type' => 'image',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'preview_size' => 'thumbnail',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
		array(
			'key' => 'field_5cf314117dac4',
			'label' => __('Post Types', 'kulam-scoop'),
			'name' => 'acf-category_post_types',
			'type' => 'taxonomy',
			'instructions' => __('Leave this field empty to display default Post Types', 'kulam-scoop'),
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'taxonomy' => 'post_types_tax',
			'field_type' => 'multi_select',
			'allow_null' => 0,
			'add_term' => 0,
			'save_terms' => 0,
			'load_terms' => 0,
			'return_format' => 'object',
			'multiple' => 0,
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'category',
			),
		),
	),
	'menu_order' => 3,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
	'modified' => 1560518021,
));

acf_add_local_field_group(array(
	'key' => 'group_5820857489fcb',
	'title' => __('User Information', 'kulam-scoop'),
	'fields' => array(
		array(
			'key' => 'field_5820857820158',
			'label' => __('Profile', 'kulam-scoop'),
			'name' => 'profile',
			'type' => 'textarea',
			'instructions' => __('Tell us something about yourself!', 'kulam-scoop'),
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => 4,
			'new_lines' => 'wpautop',
		),
		array(
			'key' => 'field_582085a620159',
			'label' => __('Gender', 'kulam-scoop'),
			'name' => 'gender',
			'type' => 'radio',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'Female' => __('Female', 'kulam-scoop'),
				'Male' => __('Male', 'kulam-scoop'),
			),
			'allow_null' => 0,
			'other_choice' => 0,
			'save_other_choice' => 0,
			'default_value' => '',
			'layout' => 'horizontal',
			'return_format' => 'value',
		),
		array(
			'key' => 'field_582085f22015a',
			'label' => __('Date of Birth', 'kulam-scoop'),
			'name' => 'date_of_birth',
			'type' => 'date_picker',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'display_format' => 'd/m/Y',
			'return_format' => 'd/m/Y',
			'first_day' => 1,
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'user_form',
				'operator' => '==',
				'value' => 'edit',
			),
			array(
				'param' => 'current_user',
				'operator' => '==',
				'value' => 'logged_in',
			),
		),
	),
	'menu_order' => 4,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
	'modified' => 1560431260,
));

acf_add_local_field_group(array(
	'key' => 'group_5ceaa42a7cfb6',
	'title' => __('My Siddur Settings', 'kulam-scoop'),
	'fields' => array(
		array(
			'key' => 'field_5a7fbe646768d',
			'label' => __('Custom Label', 'kulam-scoop'),
			'name' => 'acf-option_my_siddur_custom_label',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-my-siddur',
			),
		),
	),
	'menu_order' => 101,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
	'modified' => 1560431267,
));

acf_add_local_field_group(array(
	'key' => 'group_5a40e3bb6d57d',
	'title' => __('General Settings', 'kulam-scoop'),
	'fields' => array(
		array(
			'key' => 'field_5d6cbdbcddbb1',
			'label' => __('Homepage', 'kulam-scoop'),
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'left',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_5d6cbdd6ddbb2',
			'label' => __('Categories in Row', 'kulam-scoop'),
			'name' => 'acf-option_homepage_categories_in_row',
			'type' => 'range',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 4,
			'min' => 4,
			'max' => 6,
			'step' => 1,
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_5d6d5b6e9011b',
			'label' => __('My Siddur Tile Background Image', 'kulam-scoop'),
			'name' => 'acf-option_my_siddur_tile_background_image',
			'type' => 'image',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'preview_size' => 'thumbnail',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
		array(
			'key' => 'field_5d6d5bca9011c',
			'label' => __('Upload Tile Background Image', 'kulam-scoop'),
			'name' => 'acf-option_upload_tile_background_image',
			'type' => 'image',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'preview_size' => 'thumbnail',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
		array(
			'key' => 'field_5d028ffc43370',
			'label' => __('Category Attributes', 'kulam-scoop'),
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'left',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_5a71c0fb1a7c2',
			'label' => __('Category Page Post Types', 'kulam-scoop'),
			'name' => 'acf-option_category_page_post_types',
			'type' => 'taxonomy',
			'instructions' => __('Default selected Post Types to be displayed in a category page.
Additional Post Types may be added and re-sorted per each category', 'kulam-scoop'),
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'taxonomy' => 'post_types_tax',
			'field_type' => 'multi_select',
			'allow_null' => 0,
			'add_term' => 0,
			'save_terms' => 0,
			'load_terms' => 0,
			'return_format' => 'object',
			'multiple' => 0,
		),
		array(
			'key' => 'field_5e4b96e5aa1b6',
			'label' => __('Category Description Toggling', 'kulam-scoop'),
			'name' => 'acf-option_category_description_toggling',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5ca5f481de29b',
			'label' => __('Subcategory Attributes', 'kulam-scoop'),
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'left',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_5ca5f4c7de29c',
			'label' => __('Background color', 'kulam-scoop'),
			'name' => 'acf-option_subcategory_background_color',
			'type' => 'color_picker',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '#8F95CE',
		),
		array(
			'key' => 'field_5ca5f6cdde29d',
			'label' => __('Font Color', 'kulam-scoop'),
			'name' => 'acf-option_subcategory_font_color',
			'type' => 'color_picker',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '#FFFFFF',
		),
		array(
			'key' => 'field_5cd11da3dcb13',
			'label' => __('Social', 'kulam-scoop'),
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'left',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_5cd11dcadcb14',
			'label' => __('Google Analytics Code', 'kulam-scoop'),
			'name' => 'acf-option_google_analytics_code',
			'type' => 'text',
			'instructions' => __('Code format: UA-XXXXXXXXX-X', 'kulam-scoop'),
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5ca5febcbad83',
			'label' => __('Misc', 'kulam-scoop'),
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'left',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_5a40e3e02d04f',
			'label' => __('Placeholder Thumbnail', 'kulam-scoop'),
			'name' => 'placeholder_thumbnail',
			'type' => 'image',
			'instructions' => __('This image will be shown whenever the post has neither "featured video" nor "featured image"', 'kulam-scoop'),
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'preview_size' => 'thumbnail',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
		array(
			'key' => 'field_5d63767651bc8',
			'label' => __('Enable Activity Types custom taxonomy', 'kulam-scoop'),
			'name' => 'acf-option_enable_activity_types_custom_taxonomy',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5d6d6e5390f33',
			'label' => __('Enable Audiences custom taxonomy', 'kulam-scoop'),
			'name' => 'acf-option_enable_audiences_custom_taxonomy',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-general',
			),
		),
	),
	'menu_order' => 102,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;