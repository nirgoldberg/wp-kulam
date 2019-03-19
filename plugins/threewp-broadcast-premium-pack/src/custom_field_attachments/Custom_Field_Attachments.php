<?php

namespace threewp_broadcast\premium_pack\custom_field_attachments;

use \plainview\sdk_broadcast\collections\collection;
use \threewp_broadcast\attachment_data;

/**
	@brief			Allow post custom field containing attachment IDs to be broadcasted correctly.
	@plugin_group	Control
	@since			2014-04-06 23:19:04
**/
class Custom_Field_Attachments
	extends \threewp_broadcast\premium_pack\base
{
	public function _construct()
	{
		$this->add_action( 'threewp_broadcast_menu' );
		$this->add_action( 'threewp_broadcast_broadcasting_before_restore_current_blog' );
		$this->add_action( 'threewp_broadcast_broadcasting_started' );
		$this->add_action( 'threewp_broadcast_prepare_meta_box' );
	}

	// --------------------------------------------------------------------------------------------
	// ----------------------------------------- Admin
	// --------------------------------------------------------------------------------------------

	public function admin_menu_settings()
	{
		$form = $this->form2();
		$form->id( 'custom_field_attachments' );

		$id_fields = $form->textarea( 'id_fields' )
			// Setting textarea input title
			->description( __( 'A list of custom field names. One field per line.', 'threewp_broadcast' ) )
			// Setting textarea input label
			->label( __( 'ID fields', 'threewp_broadcast' ) )
			->rows( 10, 20 )
			->trim()
			->value( implode( "\n", $this->get_site_option( 'id_fields', [] ) ) );

		$save = $form->primary_button( 'save' )
			->value( __( 'Save settings', 'threewp_broadcast' ) );

		if ( $form->is_posting() )
		{
			$form->post();
			$form->use_post_values();

			foreach( [ 'id_fields' ] as $key )
			{
				$value = $$key->get_filtered_post_value();
				$values = $this->parse_textarea_lines( $value );
				foreach( $values as $index => $value )
					$values[ $index ] = trim( $value );
				$this->update_site_option( $key, $values );
			}

			$this->message( __( 'Settings saved!', 'threewp_broadcast' ) );
		}

		$r = $this->p( __( "Some post custom fields can contain attachment IDs that normally aren't updated when broadcasting to child blogs.", 'threewp_broadcast' ) );

		$r .= $this->p( __( "Enter the names of the fields in the text box to tell Broadcast that the attachments need to be broadcasted and their new child-IDs set into the named custom fields. Specify wildcards with an asterisk. You can use most regexps also, as long as you include an asterisk somewhere.", 'threewp_broadcast' ) );

		$r .= $this->p( __( "To see the names of the custom fields, enable Broadcast debug mode and look at the Broadcast meta box in the post editor of an existing post.", 'threewp_broadcast' ) );

		$r .= $form->open_tag();
		$r .= $form->display_form_table();
		$r .= $form->close_tag();

		$r .= $this->p( __( "Some examples:", 'threewp_broadcast' ) );
		$r .= $this->p( "<code>article_image<br/>gallery_image_*<br/>set_*_image_*<br/>_product_image_gallery</code>" );

		echo $r;
	}

	public function admin_menu_tabs()
	{
		$tabs = $this->tabs();

		$tabs->tab( 'settings' )
			->callback_this( 'admin_menu_settings' )
			// Tab name for add-on settings
			->name( __( 'Settings', 'threewp_broadcast' ) );

		echo $tabs->render();
	}

	// --------------------------------------------------------------------------------------------
	// ----------------------------------------- Callbacks
	// --------------------------------------------------------------------------------------------

	/**
		@brief		Put in the new attachment IDs.
		@since		2014-04-06 15:54:36
	**/
	public function threewp_broadcast_broadcasting_before_restore_current_blog( $action )
	{
		$bcd = $action->broadcasting_data;

		if ( ! isset( $bcd->custom_field_attachments ) )
			return;

		$o = (object)[];
		$o->broadcasting_data = $bcd;
		$o->id_fields = $this->get_site_option( 'id_fields' );
		$o->type = 'replace';

		$meta = get_post_meta( $bcd->new_post( 'ID' ) );
		$o->array = [];
		foreach( $meta as $key => $value )
			$o->array[ $key ] = reset( $value );
		$meta = $o->array;

		$this->debug( 'Process meta array.' );
		$this->process_array( $o );

		// Resave the meta.
		foreach( $o->array as $key => $value )
			// Only update the changed fields.
			if ( json_encode( maybe_unserialize( $meta[ $key ] ) ) != json_encode( $value ) )
			{
				$this->debug( 'Updating post meta field %s with %s', $key, $value );
				$bcd->custom_fields()->child_fields()->update_meta( $key, $value );
			}
	}

	/**
		@brief		Maybe store our info.
		@since		2014-04-06 15:46:04
	**/
	public function threewp_broadcast_broadcasting_started( $action )
	{
		$bcd = $action->broadcasting_data;
		$bcd->custom_field_attachments = ThreeWP_Broadcast()->collection();
		$o = (object)[];
		$o->broadcasting_data = $bcd;
		$o->id_fields = $this->get_site_option( 'id_fields' );
		$o->type = 'add';

		$meta = get_post_meta( $bcd->post->ID );
		$o->array = [];
		foreach( $meta as $key => $value )
			$o->array[ $key ] = reset( $value );

		$this->debug( 'Process meta array.' );
		$this->process_array( $o );
	}

	/**
		@brief		Hide the premium pack info.
		@since		20131030
	**/
	public function threewp_broadcast_menu( $action )
	{
		$action->menu_page
			->submenu( 'threewp_broadcast_custom_field_attachments' )
			->callback_this( 'admin_menu_tabs' )
			// Menu item for menu
			->menu_title( __( 'Custom Field Attachments', 'threewp_broadcast' ) )
			// Page title for menu
			->page_title( __( 'Broadcast Custom Field Attachments', 'threewp_broadcast' ) );
	}

	/**
		@brief		Add debug information to the meta box.
		@since		2014-04-06 15:01:34
	**/
	public function threewp_broadcast_prepare_meta_box( $action )
	{
		if ( ! ThreeWP_Broadcast()->debugging() )
			return;

		$mbd = $action->meta_box_data;

		// Meta box title
		$r = '<h4>' . __( 'Custom Field Attachments', 'threewp_broadcast' ) . '</h4>';

		// Get a list of all of the post's custom fields.
		$meta = get_post_meta( $mbd->post->ID );
		// And all of the fields we are handling.
		$id_fields = $this->get_site_option( 'id_fields' );

		if ( ! $meta OR count( $meta ) < 1 )
		{
			$r .= ThreeWP_Broadcast()->p( __( 'This post has no custom fields.', 'threewp_broadcast' ) );
		}
		else
		{
			$r .= ThreeWP_Broadcast()->p( __( 'The custom fields in bold should specify attachment IDs:', 'threewp_broadcast' ) );
			$r .= '<ul>';
			foreach( $meta as $key => $value )
			{
				$div = new \plainview\sdk_broadcast\html\div;
				$div->tag = 'li';

				if ( $this->key_matches_field( $key, $id_fields ) )
					$div->css_style( 'font-weight: bold;' );

				$div->content = $key;
				$r .= $div;
			}
			$r .= '</ul>';
		}

		$mbd->html->set( 'custom_field_attachments', $r );
	}

	// --------------------------------------------------------------------------------------------
	// ----------------------------------------- Misc
	// --------------------------------------------------------------------------------------------
	/**
		@brief		Find the key in the field name array.
		@since		2014-04-06 15:25:32
	**/
	public function key_matches_field( $key, $field_names )
	{
		if ( $key == '' )
			return false;
		foreach( $field_names as $field_name )
		{
			// No wildcard = straight match
			if ( strpos( $field_name, '*' ) === false )
			{
				if ( $field_name == $key )
					return true;
			}
			else
			{
				$preg = str_replace( '*', '.*', $field_name );
				$preg = sprintf( '/%s/', $preg );
				$result = preg_replace( $preg, '', $key );
				if ( $result !== $key )
					return true;
			}
		}
		return false;
	}

	/**
		@brief		Parses a textarea into an array of unique lines.
		@since		2014-04-19 23:55:38
	**/
	public function parse_textarea_lines( $text )
	{
		$lines = array_filter( explode( "\n", $text ) );
		$lines = array_flip( $lines );
		$lines = array_flip( $lines );
		return $lines;
	}

	public function process_array( $options )
	{
		// Convenience.
		$bcd = $options->broadcasting_data;

		$this->debug( 'Preprocessing array %s', $options->array );

		foreach( $options->array as $key => $value )
		{
			$value = maybe_unserialize( $value );
			if ( is_array( $value ) )
			{
				$new_options = clone( $options );
				$new_options->array = $value;
				$this->debug( 'Preprocessing subarray for key %s', $key );
				$this->process_array( $new_options );
				$options->array[ $key ] = $new_options->array;
			}

			$match = $this->key_matches_field( $key, $options->id_fields );
			if ( $match )
			{
				$possible_subvalues = $value;

				// Key matches. Try to extract as much information from the value as possible.
				// Convert the value to an array, if it is not already one, in order to simplify handling.
				if ( ! is_array( $possible_subvalues ) )
					$possible_subvalues = [ $possible_subvalues ];

				$ids = [];
				foreach( $possible_subvalues as $single_key => $single_value )
				{
					$original_value = $single_value;

					$this->debug( 'Examining key %s, value %s', $single_key, $single_value );
					$unserialized = maybe_unserialize( $single_value );
					if ( is_array( $unserialized ) )
						$single_value = $unserialized;
					else
						$single_value = [ $single_value ];
					// Extract as many IDs as possible.
					foreach( $single_value as $maybe_id )
						$ids = array_merge( $ids, preg_split( '/[^0-9]/', $maybe_id ) );
				}
				if ( $options->type == 'add' )
				{
					$bcd->collection( 'custom_field_attachments' )->collection( 'ids' )->set( $key, $ids );
					$this->debug( 'The IDs found: %s', $ids );
					foreach( $ids as $id )
					{
						$id = intval( $id );
						if ( $id < 1 )
						{
							$this->debug( 'Skipping image #0.' );
							continue;
						}

						if ( ! is_object( get_post( $id ) ) )
						{
							$this->debug( 'Invalid post %s.', $id );
							continue;
						}

						$this->debug( 'Yes. Saving attachment from %s: %s', $key, $id );
						if ( $bcd->add_attachment( $id ) )
							$this->debug( 'Adding attachment data for the image %s.', $id );
					}
				}

				if ( $options->type == 'replace' )
				{
					$this->debug( 'Beginning replace.' );
					$new_value = $original_value;
					foreach( $ids as $id )
					{
						$new_id = $bcd->copied_attachments()->get( $id );
						if ( ! $new_id )
							continue;
						$new_value = preg_replace( '/' . $id . '/', $new_id, $new_value, 1 );
						$this->debug( 'New value for key %s %s is %s', $single_key, $id, $new_id );
					}
					$options->array[ $key ] = $new_value;
				}
			}
		} // foreach( $options->array as $key => $value )
	}

	public function site_options()
	{
		return array_merge( [
			'id_fields' => [
				'_product_image_gallery',
			],					// Array of custom fields that are expected to contain an attachment ID.
		], parent::site_options() );
	}
}
