<?php
/**
 * HTMLine Memmbership action and filter hooks
 *
 * @author      Nir Goldberg
 * @package     scoop-child/functions
 * @version     2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * kulam_hmembership_user_userdata
 *
 * @param   $userdata (array) Role user data
 * @param	$user (array) User data
 * @return  (array)
 */
function kulam_hmembership_user_userdata( $userdata, $user ) {

	// vars
	$user_info		= unserialize( $user[ 'user_info' ] );
	$field_keys		= array_keys( $user_info );
	$field_labels	= array(
		'user_nicename'	=> 'First Name',
		'display_name'	=> 'First Name',
		'first_name'	=> 'First Name',
		'last_name'		=> 'Last Name',
	);

	// store relevant userdata found in user info
	$userdata_info = array();

	foreach ( $field_labels as $key => $value ) {
		$matches = preg_grep( "/hmembership-[\d+]-(" . sanitize_title_with_dashes( $value ) . ")/", $field_keys );

		if ( $matches && $user_info[ current( $matches ) ][ 'value' ] ) {
			$userdata_info[ $key ] = $user_info[ current( $matches ) ][ 'value' ];
		}
	}

	// return
	return array_merge( $userdata, $userdata_info );

}
add_filter( 'hmembership_user/userdata', 'kulam_hmembership_user_userdata', 10, 2 );

/**
 * kulam_hmembership_form_get_text_field_input
 *
 * @param   $output (string)
 * @param	$field (array)
 * @return  (string)
 */
function kulam_hmembership_form_get_text_field_input( $output, $field ) {

	$output =	sprintf( '<tr class="%3$s %4$s"><th scope="row" style="display: none;"><label for="%1$s">%2$s</label>%5$s</th><td><input name="%1$s" id="%1$s" type="%3$s" placeholder="%2$s %5$s" value="" /></td></tr>',
					$field[ 'id' ],
					$field[ 'label' ],
					$field[ 'type' ],
					$field[ 'required' ] ? 'required' : '',
					$field[ 'required' ] ? '*' : ''
				);

	// return
	return $output;

}
add_filter( 'hmembership_form/get_text_field_input', 'kulam_hmembership_form_get_text_field_input', 10, 2 );

/**
 * kulam_hmembership_form_get_select_field_input
 *
 * @param   $output (string)
 * @param	$field (array)
 * @return  (string)
 */
function kulam_hmembership_form_get_select_field_input( $output, $field ) {

	if ( ! empty ( $field[ 'options' ] ) && is_array( $field[ 'options' ] ) ) {

		// vars
		$attributes				= '';
		$options_markup			= '';

		if ( ! $field[ 'default' ] ) {
			array_unshift( $field[ 'options' ],  __( 'Classes I teach', 'kulam-scoop' ) );
		}

		foreach ( $field[ 'options' ] as $key => $label ) {
			$options_markup .=	sprintf( '<option value="%s" %s>%s</option>',
									$key,
									selected( $field[ 'default' ], $key, false ),
									$label
								);
		}

		if ( 'multiselect' === $field[ 'type' ] ) {
			$attributes = ' multiple="multiple" ';
		}

		$output =	sprintf( '<tr class="select %5$s"><th scope="row" style="display: none;"><label for="%1$s">%2$s</label>%6$s</th><td><select name="%1$s[]" id="%1$s" %3$s>%4$s</select></td></tr>',
						$field[ 'id' ],
						$field[ 'label' ],
						$attributes,
						$options_markup,
						$field[ 'required' ] ? 'required' : '',
						$field[ 'required' ] ? '<span> *</span>' : ''
					);

	}

	// return
	return $output;

}
add_filter( 'hmembership_form/get_select_field_input', 'kulam_hmembership_form_get_select_field_input', 10, 2 );

/**
 * kulam_hmembership_form_get_radio_field_input
 *
 * @param   $output (string)
 * @param	$field (array)
 * @return  (string)
 */
function kulam_hmembership_form_get_radio_field_input( $output, $field ) {

	if ( ! function_exists( 'get_field' ) )
		return $output;

	if ( ! empty ( $field[ 'options' ] ) && is_array( $field[ 'options' ] ) ) {

		// vars
		$options_markup					= '';
		$iterator						= 0;
		$emails_delivery_opt_in_message	= get_field( 'acf-option-registration_form_emails_delivery_opt-in_message', 'option' );
		$privacy_policy_message			= get_field( 'acf-option-registration_form_privacy_policy_message', 'option' );
		$cancelling_message				= get_field( 'acf-option-registration_form_cancelling_message', 'option' );
		$pre_text						= '';
		$post_text						= '';

		foreach ( $field[ 'options' ] as $key => $label ) {

			$iterator++;
			$options_markup .=	sprintf( '<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /><span>%5$s</span></label><br/>',
									$field[ 'id' ],
									$field[ 'type' ],
									$key,
									checked( $field[ 'default' ], $key, false ),
									$label,
									$iterator
								);

		}

		switch ( substr( $field[ 'id' ], 0, 13 ) ) {

			case 'hmembership-5':

				$pre_text = '<tr class="pre-text"><td>' . $emails_delivery_opt_in_message . '</td></tr>';
				break;

			case 'hmembership-6':

				$pre_text = '<tr class="pre-text"><td>' . $privacy_policy_message . '</td></tr>';
				break;

			case 'hmembership-7':

				$post_text = '<tr class="post-text"><td>' . $cancelling_message . '</td></tr>';

		}

		$output = $pre_text;

		$output .=	sprintf( '<tr class="fieldset %4$s"><th scope="row"><label for="%1$s">%2$s</label>%5$s</th><td><fieldset>%3$s</fieldset></td></tr>',
						$field[ 'id' ],
						$field[ 'label' ],
						$options_markup,
						$field[ 'required' ] ? 'required' : '',
						$field[ 'required' ] ? '<span> *</span>' : ''
					);

		$output .= $post_text;

	}

	// return
	return $output;

}
add_filter( 'hmembership_form/get_radio_field_input', 'kulam_hmembership_form_get_radio_field_input', 10, 2 );

/**
 * kulam_hmembership_user_message_patterns
 *
 * @param   $patterns (array)
 * @param	$user (object)
 * @param	$user_email (string)
 * @param	$user_info (string)
 * @return  (array)
 */
function kulam_hmembership_user_message_patterns( $patterns, $user, $user_email, $user_info ) {

	$patterns[ '{user_first_name}' ]	= $user->first_name;
	$patterns[ '[user_last_name}' ]		= $user->last_name;

	// return
	return $patterns;

}
add_filter( 'hmembership_approval_notification_to_user_message_patterns', 'kulam_hmembership_user_message_patterns', 10, 4 );