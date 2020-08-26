<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pojo_Theme_Template extends Pojo_Templates {

	protected function _print_start_layout_default() {
		?>
		<section id="main" class="<?php echo MAIN_CLASSES; ?> sidebar-right" role="main">
	<?php
	}

	protected function _print_end_layout_default() {
		?>
		</section><!-- section#main -->
		<?php get_sidebar(); ?>
	<?php
	}

	protected function _print_start_layout_sidebar_left() {
		?>
		<section id="main" class="<?php echo MAIN_CLASSES; ?> sidebar-left" role="main">
	<?php
	}

	protected function _print_end_layout_sidebar_left() {
		?>
		</section><!-- section#main -->
		<?php get_sidebar(); ?>
	<?php
	}

	protected function _print_start_layout_full() {
		?>
		<section id="main" class="<?php echo FULLWIDTH_CLASSES; ?> full-width" role="main">
	<?php
	}

	protected function _print_end_layout_full() {
		?>
		</section><!-- section#main -->
	<?php
	}

	protected function _print_start_layout_section() {
		?>
		<section id="main" role="main">
	<?php
	}

	protected function _print_end_layout_section() {
		?>
		</section><!-- section#main -->
	<?php
	}

	public function pojo_before_content_loop( $display_type ) {
		if ( in_array( $display_type, array( 'grid_two', 'grid_three', 'grid_four', 'gallery_grid_four', 'gallery_grid_three' ) ) ) :
			echo '<div id="grid-items" class="row">';
		else :
			echo '<div id="list-items">';
		endif;
	}

	public function pojo_after_content_loop( $display_type ) {
		echo '</div>';
	}

	public function po_display_types( $display_types = array(), $cpt ) {
		if ( 'post' === $cpt ) {
			$display_types['blog']       = __( 'Blog', 'pojo' );
			$display_types['list']       = __( 'List', 'pojo' );
			$display_types['grid_two']   = __( 'Grid Two', 'pojo' );
			$display_types['grid_three'] = __( 'Grid Three', 'pojo' );
			$display_types['grid_four']  = __( 'Grid Four', 'pojo' );
		}

		if ( 'pojo_gallery' === $cpt ) {
			$display_types['gallery_grid_three'] = __( 'Grid Three', 'pojo' );
			$display_types['gallery_grid_four']  = __( 'Grid Four', 'pojo' );
		}

		return $display_types;
	}

	public function pojo_recent_posts_layouts( $styles = array() ) {
		$styles['list']           = __( 'List', 'pojo' );
		$styles['list_big_thumb'] = __( 'List - Big Thumbnail', 'pojo' );
		$styles['list_format']    = __( 'List - With Format', 'pojo' );
		$styles['list_two']       = __( 'List - Two Columns', 'pojo' );
		$styles['list_three']     = __( 'List - Three Columns', 'pojo' );
		$styles['grid_one']       = __( 'Grid - One Columns', 'pojo' );
		$styles['grid_two']       = __( 'Grid - Two Columns', 'pojo' );
		$styles['grid_three']     = __( 'Grid - Three Columns', 'pojo' );
		$styles['grid_four']      = __( 'Grid - Four Columns', 'pojo' );

		return $styles;
	}

	public function pojo_posts_group_layouts( $styles = array() ) {
		$styles['featured_list_below']     = __( 'Featured Post // List Below', 'pojo' );
		$styles['featured_list_aside']     = __( 'Featured Post // List Aside', 'pojo' );
		$styles['featured_list_two_below'] = __( 'Featured Post // List Two Below', 'pojo' );

		return $styles;
	}

	public function pojo_recent_galleries_layouts( $styles = array() ) {
		$styles['grid_three'] = __( 'Grid Three', 'pojo' );
		$styles['grid_four']  = __( 'Grid Four', 'pojo' );

		return $styles;
	}

	public function pojo_recent_post_before_content_loop( $style ) {
		if ( in_array( $style, array( 'list_two', 'list_three', 'grid_two', 'grid_three', 'grid_four' ) ) ) :
			echo '<div class="row recent-post-wrap-grid">';
		else :
			echo '<div class="list-items">';
		endif;
	}

	public function pojo_recent_post_after_content_loop( $style ) {
		if ( in_array( $style, array( 'list_two', 'list_three', 'grid_two', 'grid_three', 'grid_four' ) ) ) :
			echo '</div>';
		else :
			echo '</div>';
		endif;
	}

	public function pojo_recent_gallery_before_content_loop( $style ) {
		if ( in_array( $style, array( 'grid_three', 'grid_four' ) ) ) :
			echo '<div class="row recent-galleries-wrap-grid">';
		endif;
	}

	public function pojo_recent_gallery_after_content_loop( $style ) {
		if ( in_array( $style, array( 'grid_three', 'grid_four' ) ) ) :
			echo '</div>';
		endif;
	}

	public function pojo_posts_group_before_content_loop( $style ) {
		if ( in_array( $style, array( 'featured_list_two_below' ) ) ) :
			echo '<div class="posts-group featured-list-two-below row posts-group-wrap-grid">';
		elseif ( in_array( $style, array( 'featured_list_aside' ) ) ) :
			echo '<div class="posts-group featured-list-aside row">';
		elseif ( in_array( $style, array( 'featured_list_below' ) ) ) :
			echo '<div class="posts-group featured-list-below">';
		else :
			echo '<div class="posts-group">';
		endif;
	}

	public function pojo_posts_group_after_content_loop( $style ) {
		if ( in_array( $style, array( 'featured_list_two_below' ) ) ) :
			echo '</div>';
		else :
			echo '</div>';
		endif;
	}

	public function default_layout( $default ) {
		return Pojo_Layouts::LAYOUT_FULL;
	}

	public function default_layout_post( $default ) {
		return Pojo_Layouts::LAYOUT_SIDEBAR_RIGHT;
	}

	public function add_metadata_category_in_recent_post_widget( $fields, $widget_obj ) {
		$prefix_id = '';
		if ( 'pojo_posts_group_widget_fields_after_featured_metadata' === current_filter() )
			$prefix_id = 'featured_';
		
		$fields[] = array(
			'id' => $prefix_id . 'metadata_category',
			'title' => __( 'Category Label:', 'pojo' ),
			'type' => 'select',
			'std' => 'show',
			'options' => array(
				'show' => __( 'Show', 'pojo' ),
				'hide' => __( 'Hide', 'pojo' ),
			),
			'filter' => array( &$widget_obj, '_valid_by_options' ),
		);
		
		$fields[] = array(
			'id' => $prefix_id . 'metadata_format_icon',
			'title' => __( 'Format Icon:', 'pojo' ),
			'type' => 'select',
			'std' => 'show',
			'options' => array(
				'show' => __( 'Show', 'pojo' ),
				'hide' => __( 'Hide', 'pojo' ),
			),
			'filter' => array( &$widget_obj, '_valid_by_options' ),
		);
		
		return $fields;
	}

	public function add_metadata_in_single_options( $fields ) {
		$fields[] = array(
			'id'      => 'single_metadata_excerpt',
			'title'   => __( 'Excerpt', 'pojo' ),
			'type'    => Pojo_Settings::FIELD_SELECT,
			'options' => array(
				'' => __( 'Show', 'pojo' ),
				'hide' => __( 'Hide', 'pojo' ),
			),
			'std' => '',
		);
		
		$fields[] = array(
			'id'      => 'single_metadata_sharing',
			'title'   => __( 'Side Sharing', 'pojo' ),
			'type'    => Pojo_Settings::FIELD_SELECT,
			'options' => array(
				'' => __( 'Show', 'pojo' ),
				'hide' => __( 'Hide', 'pojo' ),
			),
			'std' => '',
		);
		
		return $fields;
	}

	public function po_single_metadata_list( $metadata_list ) {
		$metadata_list['excerpt'] = __( 'Excerpt', 'pojo' );
		$metadata_list['sharing'] = __( 'Side Sharing', 'pojo' );
		
		return $metadata_list;
	}
	
	public function add_metadata_in_archive_options( $fields ) {
		$fields[] = array(
			'id'      => 'archive_metadata_category',
			'title'   => __( 'Category Label', 'pojo' ),
			'type'    => Pojo_Settings::FIELD_SELECT,
			'options' => array(
				'' => __( 'Show', 'pojo' ),
				'hide' => __( 'Hide', 'pojo' ),
			),
			'std' => '',
		);
		
		$fields[] = array(
			'id'      => 'archive_metadata_format_icon',
			'title'   => __( 'Format Icon', 'pojo' ),
			'type'    => Pojo_Settings::FIELD_SELECT,
			'options' => array(
				'' => __( 'Show', 'pojo' ),
				'hide' => __( 'Hide', 'pojo' ),
			),
			'std' => '',
		);

		return $fields;
	}

	public function add_metadata_in_list_posts_options( $fields ) {
		$fields[] = array(
			'id'      => 'metadata_category',
			'title'   => __( 'Category Label', 'pojo' ),
			'type'    => Pojo_MetaBox::FIELD_SELECT,
			'options' => array(
				'' => __( 'Default', 'pojo' ),
				'show' => __( 'Show', 'pojo' ),
				'hide' => __( 'Hide', 'pojo' ),
			),
			'std' => '',
		);
		
		$fields[] = array(
			'id'      => 'metadata_format_icon',
			'title'   => __( 'Format Icon', 'pojo' ),
			'type'    => Pojo_MetaBox::FIELD_SELECT,
			'options' => array(
				'' => __( 'Default', 'pojo' ),
				'show' => __( 'Show', 'pojo' ),
				'hide' => __( 'Hide', 'pojo' ),
			),
			'std' => '',
		);
		
		return $fields;
	}

	public function __construct() {
		add_filter( 'pojo_default_layout', array( &$this, 'default_layout' ) );
		add_filter( 'pojo_default_layout_post', array( &$this, 'default_layout_post' ) );

		add_filter( 'pojo_recent_posts_widget_fields_after_metadata', array( &$this, 'add_metadata_category_in_recent_post_widget' ), 30, 2 );
		add_filter( 'pojo_register_settings_single_after_metadata', array( &$this, 'add_metadata_in_single_options' ) );
		add_filter( 'po_single_metadata_list', array( &$this, 'po_single_metadata_list' ) );

		add_filter( 'pojo_register_settings_archive_after_metadata', array( &$this,	'add_metadata_in_archive_options' ) );
		add_filter( 'po_register_list_post_fields_after_metadata', array( &$this,	'add_metadata_in_list_posts_options' ) );

		//add_filter( 'pojo_posts_group_widget_fields_after_metadata', array( &$this, 'add_metadata_category_in_recent_post_widget' ), 30, 2 );
		add_filter( 'pojo_posts_group_widget_fields_after_featured_metadata', array( &$this, 'add_metadata_category_in_recent_post_widget' ), 30, 2 );
		
		parent::__construct();
	}

}