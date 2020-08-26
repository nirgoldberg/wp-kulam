<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

final class Pojo_Widget_Style {
	
	const SETTING_KEY = 'pojo_widget_style';
	
	protected static $_color_options = array();

	public static function get_color_options() {
		if ( empty( self::$_color_options ) ) {
			self::$_color_options = array(
				'' => __( 'None', 'pojo' ),
			);
			foreach ( range( 1, 10 ) as $color_id ) {
				self::$_color_options[ $color_id ] = strtr(
					__( '#{COLOR_ID} - {TITLE}', 'pojo' ),
					array(
						'{COLOR_ID}' => $color_id,
						'{TITLE}' => get_theme_mod( 'lbl_color_theme_' . $color_id ),
					)
				);
			}
		}
		
		return self::$_color_options;
	}

	public function register_menu_fields( $fields ) {
		$fields[] = array(
			'id' => 'bg_hover_color',
			'title' =>  __( 'Background Hover', 'pojo' ),
			'type' => Pojo_Menus::TYPE_SELECT,
			'options' => self::get_color_options(),
		);
		
		return $fields;
	}

	public function widget_form( WP_Widget $widget, $instance ) {
		$id    = self::SETTING_KEY;
		$value = isset( $instance[ $id ] ) ? $instance[ $id ] : '';
		?>
		<p>
			<label for="<?php echo $widget->get_field_id( $id ); ?>"><?php _e( 'Widget Style', 'pojo' ); ?></label>
			<select class="widefat pb-widget-<?php echo esc_attr( $id ); ?>" id="<?php echo $widget->get_field_id( $id ); ?>" name="<?php echo $widget->get_field_name( $id ); ?>">
				<?php foreach ( self::get_color_options() as $color_key => $color_title ) : ?>
					<option value="<?php echo esc_attr( $color_key ); ?>"<?php selected( $value, $color_key ); ?>><?php echo $color_title; ?></option>
				<?php endforeach; ?>
			</select>
		</p>
	<?php
	}
	
	public function wp_widget_form( WP_Widget $widget, $return, $instance ) {
		$this->widget_form( $widget, $instance );
	}

	public function update_callback( $instance, $new_instance ) {
		$id = self::SETTING_KEY;
		$instance[ $id ] = isset( $new_instance[ $id ] ) ? absint( $new_instance[ $id ] ) : '';
		
		return $instance;
	}

	public function wp_update_callback( $instance, $new_instance, $old_instance, WP_Widget $widget ) {
		return $this->update_callback( $instance, $new_instance );
	}

	public function widget_css_classes( $css_classes, $widget ) {
		if ( empty( $widget['widget_args'][ self::SETTING_KEY ] ) )
			return $css_classes;
		
		$css_classes[] = 'theme-color-' . $widget['widget_args'][ self::SETTING_KEY ];
		return $css_classes;
	}

	public function wp_sidebar_params( $params ) {
		$id = rtrim( $params[0]['widget_id'], '-' . $params[1]['number'] );
		$instance = get_option( 'widget_' . $id );
		$instance = $instance[ $params[1]['number'] ];

		if ( ! empty( $instance[ self::SETTING_KEY ] ) ) {
			$params[0]['before_widget'] = $params[0]['before_widget'] . '<div class="theme-color-' . $instance[ self::SETTING_KEY ] . '">';
			$params[0]['after_widget'] = '</div>' . $params[0]['after_widget'];
		}
		
		return $params;
	}

	public function menu_manage_columns( $columns ) {
		$columns['pojo-menu-hover'] = __( 'Menu Hover Color', 'pojo' );
		return $columns;
	}

	public function nav_menu_css_class( $class_names, $item ) {
		$color = get_post_meta( $item->ID, 'pojo-menu-item-bg_hover_color', true );
		if ( ! empty( $color ) ) {
			$class_names[] = 'theme-color-' . $color;
		}
		return $class_names;
	}

	public function register_field_in_sp( $fields, $cpt ) {
		$fields[] = array(
			'id' => 'color_style',
			'title' => 'Color Style',
			'type' => Pojo_MetaBox::FIELD_SELECT,
			'options' => self::get_color_options(),
		);
		
		return $fields;
	}

	public function pojo_before_content_loop( $display_type ) {
		if ( ! po_is_current_loop_smart_page() )
			return;

		$color = atmb_get_field( 'po_color_style' );
		if ( empty( $color ) )
			return;
		
		echo '<div class="theme-color-' . esc_attr( $color ) . '">';
	}

	public function pojo_after_content_loop( $display_type ) {
		if ( ! po_is_current_loop_smart_page() )
			return;

		$color = atmb_get_field( 'po_color_style' );
		if ( empty( $color ) )
			return;

		echo '</div>';
	}

	public function __construct() {
		// Builder Widget Form
		add_action( 'pb_after_widget_form', array( &$this, 'widget_form' ), 50, 2 );
		// WP Widget Form
		add_action( 'in_widget_form', array( &$this, 'wp_widget_form' ), 50, 3 );
		
		// Builder Update callback
		add_filter( 'pb_widget_update_callback', array( &$this, 'update_callback' ), 10, 2 );
		// WP Update callback
		add_filter( 'widget_update_callback', array( &$this, 'wp_update_callback' ), 10, 4 );
		
		// Builder Widget display
		add_filter( 'pb_widget_css_classes', array( &$this, 'widget_css_classes' ), 10, 2 );
		
		// WP Widget display
		add_filter( 'dynamic_sidebar_params', array( &$this, 'wp_sidebar_params' ) );
		
		// Menu
		add_filter( 'manage_nav-menus_columns', array( &$this, 'menu_manage_columns' ), 20 );
		
		// WP Menus
		add_filter( 'pojo_menus_register_fields', array( &$this, 'register_menu_fields' ) );
		add_filter( 'nav_menu_css_class', array( &$this, 'nav_menu_css_class' ), 10, 2 );
		
		// Smart Page
		add_filter( 'po_init_fields_after_display_type', array( &$this, 'register_field_in_sp' ), 30, 2 );
		add_action( 'pojo_before_content_loop', array( &$this, 'pojo_before_content_loop' ), 45 );
		add_action( 'pojo_after_content_loop', array( &$this, 'pojo_after_content_loop' ), 45 );
	}
	
}
new Pojo_Widget_Style();