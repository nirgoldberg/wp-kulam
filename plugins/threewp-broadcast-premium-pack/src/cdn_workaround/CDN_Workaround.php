<?php

namespace threewp_broadcast\premium_pack\cdn_workaround;

/**
	@brief			Work around faulty CDNs that do not report the correct URL for attachments.
	@plugin_group	Utilities
	@since			2015-11-17 19:37:25
**/
class CDN_Workaround
	extends \threewp_broadcast\premium_pack\base
{
	public function _construct()
	{
		$this->add_filter( 'get_attached_file', 10, 2 );
	}

	/**
		@brief		Maybe override the filename during broadcast.
		@since		2015-11-17 19:31:35
	**/
	public function get_attached_file( $filename, $attachment_id )
	{
		// Only modify the filename if we are broadcasting.
		if ( ! ThreeWP_Broadcast()->is_broadcasting() )
			return $filename;

		// Only modify if the file doesn't exist.
		if ( file_exists( $filename ) )
			return $filename;

		// Filename doesn't exist, we need its guid.
		$attachment = get_post( $attachment_id );
		$url = $attachment->guid;

		$override_type = '';

		// If this is a cloudinary URL, extract the URL.
		if ( strpos( $filename, 'cloudinary.com/' ) !== false )
		{
			$override_type = 'Cloudinary ';
			$url = preg_replace( '/.*(http[s]?:\/\/)/', '\1', $filename );
		}

		$this->debug( 'Overriding %spath %s with %s', $override_type, $filename, $url );

		return $url;
	}
}
