<?php

namespace threewp_broadcast\premium_pack\shortcode_attachments;

use \Exception;

/**
	@brief			Modify attachment IDs found in shortcodes to match their equivalent attachments on each blog.
	@plugin_group	Control
	@since			2014-03-12 13:18:37
**/
class Shortcode_Attachments
	extends \threewp_broadcast\premium_pack\classes\shortcode_items\Shortcode_Items
{
	/**
		@brief		Convert the old shortcodes into new ones.
		@since		2017-03-26 19:01:36
	**/
	public function activate()
	{
		// Convert the old attachments shortcodes data?
		global $wpdb;
		$query = sprintf( "SELECT `meta_value` FROM `%s` WHERE `meta_key` LIKE '%%Attachment_Shortcodes_shortcodes'",
			$wpdb->sitemeta
		);
		$var = $wpdb->get_var( $query );
		if ( $var != '' )
		{
			$var = str_replace( 'O:63:"threewp_broadcast\premium_pack\attachment_shortcodes\shortcodes', 'O:65:"threewp_broadcast\premium_pack\classes\shortcode_items\Shortcodes', $var );
			$var = str_replace( 'O:62:"threewp_broadcast\premium_pack\attachment_shortcodes\shortcode', 'O:62:"threewp_broadcast\premium_pack\shortcode_attachments\Shortcode', $var );
			$this->__shortcodes = unserialize( $var );
			$this->save_shortcodes();

			// Delete the old key so we don't keep reconverting.
			$query = sprintf( "DELETE FROM `%s` WHERE `meta_key` LIKE '%%Attachment_Shortcodes_shortcodes'",
				$wpdb->sitemeta
			);
			$wpdb->query( $query );
		}
	}
	/**
		@brief		Return the name of the plugin.
		@since		2016-07-14 12:31:45
	**/
	public function get_plugin_name()
	{
		return 'Shortcode Attachments';
	}

	/**
		@brief		Return the HTML text which is help for the editor.
		@since		2016-07-14 13:21:49
	**/
	public function get_shortcode_editor_html()
	{
		return $this->wpautop_file( __DIR__ . '/html/shortcode_editor.html' );
	}

	/**
		@brief		Return a new item collection.
		@since		2016-07-14 12:45:37
	**/
	public function new_shortcode()
	{
		return new Shortcode();
	}

	/**
		@brief		Add the attachment(s).
		@since		2016-07-14 13:41:10
	**/
	public function parse_find( $bcd, $find )
	{
		foreach( $find->value as $attribute => $id )
		{
			try
			{
				$id = intval( $id );
				$this->debug( 'Adding single attachment %s', $id );
				$bcd->add_attachment( $id );
			}
			catch ( Exception $e )
			{
				$this->debug( 'Error adding single attachment %s: %s', $id, $e->getMessage() );
			}
		}

		foreach( $find->values as $attribute => $array )
			foreach( $array[ 'ids' ] as $id )
			{
				try
				{
					$id = intval( $id );
					$this->debug( 'Adding one of several attachments %s', $id );
					$bcd->add_attachment( $id );
				}
				catch ( Exception $e )
				{
					$this->debug( 'Error adding one of several attachments %s: %s', $id, $e->getMessage() );
				}
			}
	}

	/**
		@brief		Replace the old ID with a new one.
		@since		2016-07-14 14:21:21
	**/
	public function replace_id( $broadcasting_data, $find, $old_id )
	{
		$new_id = $broadcasting_data->copied_attachments()->get( $old_id );
		if ( $new_id < 1 )
			$new_id = 0;
		return $new_id;
	}
}
