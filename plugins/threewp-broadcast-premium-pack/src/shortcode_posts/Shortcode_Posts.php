<?php

namespace threewp_broadcast\premium_pack\shortcode_posts;

/**
	@brief			Modifies post IDs found in shortcodes to match their equivalent posts on each blog.
	@plugin_group	Control
	@since			2018-03-22 17:19:48
**/
class Shortcode_Posts
	extends \threewp_broadcast\premium_pack\classes\shortcode_items\Shortcode_Items
{
	/**
		@brief		Return the HTML text which is help for the overview.
		@since		2016-07-14 13:21:49
	**/
	public function get_overview_html()
	{
		return $this->wpautop_file( __DIR__ . '/html/overview.html' );
	}

	/**
		@brief		Return the name of the plugin.
		@since		2016-07-14 12:31:45
	**/
	public function get_plugin_name()
	{
		return 'Shortcode Posts';
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
		@brief		Replace the old ID with a new one.
		@since		2016-07-14 14:21:21
	**/
	public function replace_id( $broadcasting_data, $find, $old_id )
	{
		$bcd = $broadcasting_data;	// Conv
		$new_id = $bcd->equivalent_posts()->get_or_broadcast( $bcd->parent_blog_id, $old_id, get_current_blog_id() );
		return $new_id;
	}
}
