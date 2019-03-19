<?php

namespace threewp_broadcast\premium_pack\shortcode_menus;

/**
	@brief			Modify menu IDs found in shortcodes to match their equivalent menus on each blog.
	@plugin_group	Control
	@since			2014-03-12 13:18:37
**/
class Shortcode_Menus
	extends \threewp_broadcast\premium_pack\classes\shortcode_items\Shortcode_Items
{
	/**
		@brief		Find the slug of this numeric menu ID.
		@since		2016-07-14 14:35:45
	**/
	public function find_menu_slug( $id )
	{
		$menus = $this->menus();
		foreach( $menus as $menu )
			if ( $menu->term_id == $id )
				return $menu->slug;
		return false;
	}

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
		return 'Shortcode Menus';
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
		@brief		Load all of the menus on this blog.
		@since		2016-07-14 14:37:44
	**/
	public function menus()
	{
		$key = sprintf( '__menus_%s', get_current_blog_id() );
		if ( isset( $this->$key ) )
			return $this->$key;
		$menus = wp_get_nav_menus();
		$this->$key = $this->array_rekey( $menus, 'slug' );
		return $this->$key;
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
		@brief		Add the menu(s).
		@since		2016-07-14 13:41:10
	**/
	public function parse_find( $bcd, $find )
	{
		foreach( $find->value as $attribute => $id )
			if ( intval( $id ) > 0 )
				$find->collection( 'menu_slugs' )->set( $id, $this->find_menu_slug( $id ) );

		foreach( $find->values as $attribute => $array )
			foreach( $array[ 'ids' ] as $id )
				if ( intval( $id ) > 0 )
					$find->collection( 'menu_slugs' )->set( $id, $this->find_menu_slug( $id ) );

		$this->debug( 'Find data: %s', $find );
	}

	/**
		@brief		Replace the old ID with a new one.
		@since		2016-07-14 14:21:21
	**/
	public function replace_id( $broadcasting_data, $find, $old_id )
	{
		// Convert the ID to a slug.
		$menu_slug = $find->collection( 'menu_slugs' )->get( $old_id, false );
		if ( ! $menu_slug )
		{
			$this->debug( 'Menu slug %s not found.', $menu_slug );
			return false;
		}
		$menus = $this->menus();
		if ( ! isset( $menus[ $menu_slug ] ) )
		{
			$this->debug( 'No menu on this blog with slug %s', $menu_slug );
			return false;
		}

		return $menus[ $menu_slug ]->term_id;
	}
}
