<?php

namespace threewp_broadcast\premium_pack\shortcode_terms;

use \Exception;

/**
	@brief			Modify term IDs found in shortcodes to match their equivalent terms on each blog.
	@plugin_group	Control
	@since			2016-12-20 22:13:43
**/
class Shortcode_Terms
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
		return 'Shortcode Terms';
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
		@brief		Take note of the taxonomies to sync.
		@since		2016-12-21 21:29:20
	**/
	public function parse_find( $bcd, $find )
	{
		$ids = ThreeWP_Broadcast()->collection();
		foreach( $find->value as $attribute => $id )
		{
			$id = intval( $id );
			$ids->set( $id, true );
		}

		foreach( $find->values as $attribute => $array )
			foreach( $array[ 'ids' ] as $id )
			{
				$id = intval( $id );
				$ids->set( $id, true );
			}

		$taxonomies = ThreeWP_Broadcast()->collection();
		foreach( array_keys( $ids->to_array() ) as $id )
		{
			$term = get_term( $id );
			if ( ! $term )
				continue;
			$taxonomy = $term->taxonomy;
			$this->debug( 'Term %s belongs to taxonomy %s', $id, $taxonomy );
			$find->collection( 'taxonomies' )->set( $id, $taxonomy );
			$taxonomies->set( $taxonomy, true );
		}

		foreach( array_keys( $taxonomies->to_array() ) as $taxonomy )
		{
			if ( isset( $bcd->parent_blog_taxonomies[ $taxonomy ] ) )
				continue;
			$terms = ThreeWP_Broadcast()->get_current_blog_taxonomy_terms( $taxonomy );
			$this->debug( 'Retrieved %s terms for taxonomy %s.', count( $terms ), $taxonomy );
			$bcd->parent_blog_taxonomies[ $taxonomy ] = [
				'taxonomy' => get_taxonomy( $taxonomy ),
				'terms' => $terms,
			];
		}
	}

	/**
		@brief		Replace the old ID with a new one.
		@since		2016-07-14 14:21:21
	**/
	public function replace_id( $broadcasting_data, $find, $old_id )
	{
		$taxonomy = $find->collection( 'taxonomies' )->get( $old_id );

		if ( ! $taxonomy )
			return;

		// Has the taxonomy been synced?
		if ( ! isset( $broadcasting_data->parent_blog_taxonomies[ $taxonomy ][ 'equivalent_terms' ] ) )
		{
			$this->debug( 'Asking broadcast to please sync %s', $taxonomy );
			ThreeWP_Broadcast()->sync_terms( $broadcasting_data, $taxonomy );
		}

		$new_id = $broadcasting_data->terms()->get( $old_id );
		if ( $new_id < 1 )
			$new_id = 0;
		return $new_id;
	}
}
