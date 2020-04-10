<?php
/**
 * ACF Field Groups
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions/acf
 * @version		1.7.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * ACF register field groups
 *
 * @fieldgroup	ACF Front-End Form
 * @fieldgroup	Questions & Answers
 * @fieldgroup	Ratings
 * @fieldgroup	Category Attributes
 * @fieldgroup	User Information
 * @fieldgroup	Header/Footer Settings
 * @fieldgroup	My Siddur Settings
 * @fieldgroup	General Settings
 * @fieldgroup	ACF Form Fields
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
		array(
			'key' => 'field_5e86337963c2a',
			'label' => __('Form Title', 'kulam-scoop'),
			'name' => 'acf-form_form_title',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5a4fdd3871d9b',
						'operator' => '==',
						'value' => 'upload',
					),
				),
			),
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
			'key' => 'field_5e8633af63c2b',
			'label' => __('Form Content Title', 'kulam-scoop'),
			'name' => 'acf-form_form_content_title',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5a4fdd3871d9b',
						'operator' => '==',
						'value' => 'upload',
					),
				),
			),
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
			'key' => 'field_5e8668eea69b5',
			'label' => __('Form Category Title', 'kulam-scoop'),
			'name' => 'acf-form_form_category_title',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5a4fdd3871d9b',
						'operator' => '==',
						'value' => 'upload',
					),
				),
			),
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
	'key' => 'group_5e89d267bf10c',
	'title' => __('Questions & Answers', 'kulam-scoop'),
	'fields' => array(
		array(
			'key' => 'field_5e89d71b14535',
			'label' => __('Q&A Blocks', 'kulam-scoop'),
			'name' => 'acf-qna_blocks',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => 'field_5e89dcc4363e7',
			'min' => 0,
			'max' => 0,
			'layout' => 'row',
			'button_label' => __('Add Q&A Block', 'kulam-scoop'),
			'sub_fields' => array(
				array(
					'key' => 'field_5e89d2e914534',
					'label' => __('ID', 'kulam-scoop'),
					'name' => 'acf-qna_block_id',
					'type' => 'text',
					'instructions' => __('Use this field as a slug for your shortcode, e.g. [kulam_qna id=ID]', 'kulam-scoop'),
					'required' => 1,
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
					'key' => 'field_5e89dcc4363e7',
					'label' => __('Shortcode', 'kulam-scoop'),
					'name' => 'acf-qna_block_shortcode',
					'type' => 'text',
					'instructions' => __('Auto generated after post save/update.
Copy and paste this code into your post editor', 'kulam-scoop'),
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
					'readonly' => 1,
					'disabled' => 0,
				),
				array(
					'key' => 'field_5e89d8d8911bd',
					'label' => __('Questions', 'kulam-scoop'),
					'name' => 'acf-qna_block_questions',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'collapsed' => 'field_5e89d75914536',
					'min' => 0,
					'max' => 0,
					'layout' => 'block',
					'button_label' => __('Add Question', 'kulam-scoop'),
					'sub_fields' => array(
						array(
							'key' => 'field_5e89d75914536',
							'label' => __('Question', 'kulam-scoop'),
							'name' => 'question',
							'type' => 'text',
							'instructions' => '',
							'required' => 1,
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
							'key' => 'field_5e89d77214537',
							'label' => __('Answer', 'kulam-scoop'),
							'name' => 'answer',
							'type' => 'wysiwyg',
							'instructions' => '',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 1,
							'delay' => 1,
						),
					),
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
			),
		),
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'page',
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
	'menu_order' => 3,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
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
	'menu_order' => 4,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
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
	'menu_order' => 5,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

acf_add_local_field_group(array(
	'key' => 'group_5e4beda225c07',
	'title' => __('Header/Footer Settings', 'kulam-scoop'),
	'fields' => array(
		array(
			'key' => 'field_5e890348f6c87',
			'label' => __('Search Form', 'kulam-scoop'),
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
			'key' => 'field_5e4bf3a29323d',
			'label' => __('Search Form Type', 'kulam-scoop'),
			'name' => 'acf-option_search_form_type',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'minimalist' => __('Minimalist', 'kulam-scoop'),
				'exposed' => __('Exposed', 'kulam-scoop'),
			),
			'default_value' => array(
				0 => 'minimalist',
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_5e8902e5f6c86',
			'label' => __('Search Input Placeholder', 'kulam-scoop'),
			'name' => 'acf-option_search_input_placeholder',
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
		array(
			'key' => 'field_5e890285f6c85',
			'label' => __('Advanced Search', 'kulam-scoop'),
			'name' => 'acf-option_advanced_search',
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
			'default_value' => 1,
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
				'value' => 'acf-options-header-footer',
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
	'menu_order' => 102,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
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
			'key' => 'field_5e8c4b80e2ff2',
			'label' => __('Questions & Answers', 'kulam-scoop'),
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
			'key' => 'field_5e8d7f3b4ad72',
			'label' => __('Font Family', 'kulam-scoop'),
			'name' => 'acf-option_qna_font_family',
			'type' => 'font_family',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'font_family' => 0,
		),
		array(
			'key' => 'field_5e8dcb0641bb6',
			'label' => __('Font Size', 'kulam-scoop'),
			'name' => 'acf-option_qna_font_size',
			'type' => 'number',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 20,
			'placeholder' => '',
			'prepend' => '',
			'append' => 'px',
			'min' => 8,
			'max' => 72,
			'step' => 1,
		),
		array(
			'key' => 'field_5e8c4baae2ff3',
			'label' => __('Color', 'kulam-scoop'),
			'name' => 'acf-option_qna_color',
			'type' => 'color_picker',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
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
			'key' => 'field_5e4ba10303d95',
			'label' => __('Google Tag Manager Code', 'kulam-scoop'),
			'name' => 'acf-option_google_tag_manager_code',
			'type' => 'text',
			'instructions' => __('Code format: GTM-XXXXXX', 'kulam-scoop'),
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
		array(
			'key' => 'field_5e74a985c5ba1',
			'label' => __('Strip Image', 'kulam-scoop'),
			'name' => 'acf-option_strip_image',
			'type' => 'image',
			'instructions' => __('Default strip which will be displayed in single post, page and category.
Image dimensions: 1140x268 (px)', 'kulam-scoop'),
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
	'menu_order' => 103,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

acf_add_local_field_group(array(
	'key' => 'group_5e865fad1f3c4',
	'title' => __('ACF Form Fields', 'kulam-scoop'),
	'fields' => array(
		array(
			'key' => 'field_5e86605e1140b',
			'label' => __('Category', 'kulam-scoop'),
			'name' => 'acf-form_category',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'taxonomy' => 'category',
			'field_type' => 'select',
			'allow_null' => 0,
			'add_term' => 0,
			'save_terms' => 1,
			'load_terms' => 0,
			'return_format' => 'id',
			'multiple' => 0,
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
	'menu_order' => 104,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 0,
	'description' => '',
));

endif;