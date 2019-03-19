<?php

namespace threewp_broadcast\premium_pack\global_blocks_for_cornerstone;

/**
	@brief			Adds support for the <a href="https://wordpress.org/plugins/global-blocks-for-cornerstone/">Global Blocks for Cornerstone</a> plugin.
	@plugin_group	3rd party compatability
	@since			2017-01-11 22:51:31
**/
class Global_Blocks_For_Cornerstone
	extends \threewp_broadcast\premium_pack\classes\Shortcode_Preparser
{
	/**
		@brief		Return the name of the shortcode we are looking for.
		@since		2017-06-20 22:10:34
	**/
	public function get_shortcode_name()
	{
		return 'global_block';
	}

	/**
		@brief		Return the shortcode attribute that stores the item ID.
		@since		2017-01-11 23:04:21
	**/
	public function get_shortcode_id_attribute()
	{
		return 'block';
	}
}
