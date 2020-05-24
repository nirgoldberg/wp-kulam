<?php
/**
 * Widget Name: Contact_Details
 *
 * @author		Nir Goldberg
 * @package		scoop-child/functions/widgets
 * @version		1.7.14
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Contact_Details extends WP_Widget {

	/**
	 * __construct
	 *
	 * @param	N/A
	 * @return	N/A
	 */
	function __construct() {

		$widget_options = array(
			'classname' => 'Contact_Details',
			'description' => 'List contact details and social network icons',
		);

		parent::__construct( 'Contact_Details', 'Contact Details', $widget_options );

	}

	/**
	 * form
	 *
	 * @param	$instance (array)
	 * @return	N/A
	 */
	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance,
			array (
				'title' => '',
			)
		);

		$title = $instance['title'];

		?>

		<p class="inline"><label for="<?php echo $this->get_field_id( 'title' ); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>

		<?php

	}

	/**
	 * update
	 *
	 * @param	$new_instance (array)
	 * @param	$old_instance (array)
	 * @return	(array)
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = $new_instance['title'];

		return $instance;

	}

	/**
	 * widget
	 *
	 * @param	$args (array)
	 * @param	$instance (array)
	 * @return	N/A
	 */
	function widget( $args, $instance ) {

		if ( ! function_exists( 'get_field' ) )
			return;

		/**
		 * Variables
		 */
		$email				= get_field( 'acf-option_contact_details_email', 'option' );
		$address			= get_field( 'acf-option_contact_details_address', 'option' );
		$phone				= get_field( 'acf-option_contact_details_phone', 'option' );
		$social_networks	= get_field( 'acf-option_contact_details_social_networks', 'option' );

		if ( ! $email && ! $address && ! $phone && ! $social_networks[ 'youtube' ] && ! $social_networks[ 'facebook' ] && ! $social_networks[ 'instagram' ] )
			return;

		extract( $args, EXTR_SKIP );

		// widget content
		echo $before_widget;

		$title = apply_filters( 'widget_title', empty( $instance[ 'title' ] ) ? '' : $instance[ 'title' ], $instance );

		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		?>

		<div class="widgetcontent">

			<ul>
				<?php

					echo $email ? '<li class="contact-details-email">' . __( 'Email', 'kulam-scoop' ) . ': <a href="mailto:' . $email . '">' . $email . '</a></li>' : '';
					echo $address ? '<li class="contact-details-address">' . __( 'Address', 'kulam-scoop' ) . ': ' . $address . '</li>' : '';
					echo $phone ? '<li class="contact-details-phone">' . __( 'Phone', 'kulam-scoop' ) . ': <a href="tel:' . $phone . '">' . $phone . '</a></li>' : '';

					if ( ! empty( $social_networks ) ) {
						echo '<li class="contact-details-social"><ul>';
						echo $social_networks[ 'youtube' ] ? '<li class="youtube"><a href="' . $social_networks[ 'youtube' ] . '" target="_blank"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>' : '';
						echo $social_networks[ 'facebook' ] ? '<li class="facebook"><a href="' . $social_networks[ 'facebook' ] . '" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>' : '';
						echo $social_networks[ 'instagram' ] ? '<li class="instagram"><a href="' . $social_networks[ 'instagram' ] . '" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>' : '';
						echo '</ul></li>';
					}

				?>
			</ul>

		</div>

		<?php echo $after_widget;

	}

}
add_action( 'widgets_init', create_function( '', 'return register_widget( "Contact_Details" );' ) );