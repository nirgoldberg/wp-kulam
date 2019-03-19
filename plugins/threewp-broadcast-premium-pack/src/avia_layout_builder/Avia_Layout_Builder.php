<?php

namespace threewp_broadcast\premium_pack\avia_layout_builder;

use \threewp_broadcast\actions;

/**
	@brief			Adds support for the <a href="http://www.kriesi.at/">Avia Layout Builder plugin from Kriesi.at</a>.
	@details		Thanks for Francis from quai13.com for his work on this plugin.
	@plugin_group	3rd party compatability
	@since			2015-11-19 19:00:21
	@author			Francis
	@author_url		http://quai13.com
**/
class Avia_Layout_Builder
	extends \threewp_broadcast\premium_pack\base
{
	/**
		@brief		The shortcode add-ons through which to send the Avia builder content.
		@since		2016-07-19 19:32:09
	**/
	public static $shortcode_addons = [
		'\\threewp_broadcast\premium_pack\shortcode_attachments\\Shortcode_Attachments',
		'\\threewp_broadcast\premium_pack\shortcode_menus\\Shortcode_Menus',
	];

	/**
		@brief		The meta key in which Avia stores its data.
		@since		2016-07-19 19:33:53
	**/
	public static $meta_key = '_aviaLayoutBuilderCleanData';

	public function _construct()
	{
		$this->add_action( 'threewp_broadcast_broadcasting_before_restore_current_blog' );
		$this->add_action( 'threewp_broadcast_broadcasting_started' );
	}

	// --------------------------------------------------------------------------------------------
	// ----------------------------------------- Callbacks
	// --------------------------------------------------------------------------------------------

	/**
		@brief		threewp_broadcast_broadcasting_started
		@since		2016-07-19 19:27:21
	**/
	public function threewp_broadcast_broadcasting_started( $action )
	{
		$bcd = $action->broadcasting_data;

		$content = $bcd->custom_fields()->get_single( static::$meta_key );
		if ( strlen( $content ) < 1 )
			return $this->debug( 'No Avia Layout Builder content found.' );

		$bcd->avia_layout_builder = ThreeWP_Broadcast()->collection();
		$bcd->avia_layout_builder->set( static::$meta_key, $content );

		foreach( static::$shortcode_addons as $class )
		{
			if ( ! class_exists( $class ) )
				return $this->debug('The %s add-on is not enabled. Not doing anything.', $class );

			// Ask it to preparse this content.
			$i = $class::instance();

			$preparse = new actions\preparse_content();
			$preparse->broadcasting_data = $bcd;
			$preparse->content = $content;
			$preparse->id = static::$meta_key;

			$this->debug( 'Asking %s to preparse the Avia data.', $class );
			$i->threewp_broadcast_preparse_content( $preparse );
		}
	}

	/**
		@brief		Put in the new attachment IDs.
		@since		2014-04-06 15:54:36
	**/
	public function threewp_broadcast_broadcasting_before_restore_current_blog( $action )
	{
		$bcd = $action->broadcasting_data;

		if ( ! isset( $bcd->avia_layout_builder ) )
			return;

		$content = $bcd->avia_layout_builder->get( static::$meta_key, '' );

		foreach( static::$shortcode_addons as $class )
		{
			if ( ! class_exists( $class ) )
				return $this->debug('The %s add-on is not enabled. Not doing anything.', $class );

			// Ask it to parse this content.
			$i = $class::instance();

			$preparse = new actions\parse_content();
			$preparse->broadcasting_data = $bcd;
			$preparse->content = $content;
			$preparse->id = static::$meta_key;

			$this->debug( 'Asking %s to parse the Avia data.', $class );
			$i->threewp_broadcast_parse_content( $preparse );

			$content = $preparse->content;
		}

		$this->debug( 'New Avia Layout Builder data: %s', htmlspecialchars( $content ) );

		$bcd->custom_fields()
			->child_fields()
			->update_meta( static::$meta_key, $content );
	}
}
