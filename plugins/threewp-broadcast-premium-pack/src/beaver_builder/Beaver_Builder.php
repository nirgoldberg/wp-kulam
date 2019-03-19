<?php

namespace threewp_broadcast\premium_pack\beaver_builder;

/**
	@brief			Adds support for the <a href="https://www.wpbeaverbuilder.com/">Beaver Builder page builder plugin</a>.
	@plugin_group	3rd party compatability
	@since			2016-10-25 18:57:26
**/
class Beaver_Builder
	extends \threewp_broadcast\premium_pack\base
{
	use \threewp_broadcast\premium_pack\classes\copy_options_trait;
	use \threewp_broadcast\premium_pack\classes\parse_and_preparse_content_trait;
	use \threewp_broadcast\premium_pack\classes\sync_taxonomy_trait;

	public function _construct()
	{
		$this->add_action( 'fl_builder_after_save_layout' );
		$this->add_action( 'threewp_broadcast_menu' );
		$this->add_action( 'threewp_broadcast_broadcasting_before_restore_current_blog' );
		$this->add_action( 'threewp_broadcast_broadcasting_started' );
		$this->add_action( 'threewp_broadcast_get_post_types' );
	}

	/**
		@brief		Update the children after saving the layout.
		@since		2016-10-25 18:58:28
	**/
	public function fl_builder_after_save_layout( $post_id )
	{
		ThreeWP_Broadcast()->api()->update_children( $post_id, [] );
	}

	/**
		@brief		Return an array of the options to copy.
		@since		2017-05-01 22:48:56
	**/
	public function get_options_to_copy()
	{
		return [
			'_fl_builder_*',
		];
	}

	/**
		@brief		show_copy_options
		@since		2017-05-01 22:47:16
	**/
	public function show_copy_settings()
	{
		echo $this->generic_copy_options_page( [
			'plugin_name' => 'Beaver Builder',
		] );
	}

	/**
		@brief		Parse the builder blocks.
		@since		2017-06-30 00:19:34
	**/
	public function threewp_broadcast_broadcasting_before_restore_current_blog( $action )
	{
		$bcd = $action->broadcasting_data;

		if ( ! isset( $bcd->beaver_builder ) )
			return;

		$bb = $bcd->beaver_builder;

		foreach( [
			'_fl_builder_draft',
			'_fl_builder_data',
		] as $key )
		{
			$data = $bcd->custom_fields()->get_single( $key );
			$data = maybe_unserialize( $data );

			if ( ! is_array( $data ) )
				continue;

			$this->debug( 'Parsing Beaver Builder %s', $key );
			foreach( $data as $block_id => $section )
			{
				$data[ $block_id ] = $this->parse_content( [
					'broadcasting_data' => $bcd,
					'content' => $section,
					'id' => $key . $block_id,
				] );
			}

			// Done modifying. Save it.
			$this->debug( 'Saving %s: %s', $key, $data );
			$bcd->custom_fields()->child_fields()->update_meta( $key, $data );
		}

		$meta_key = '_fl_theme_builder_locations';
		$locations = $bcd->custom_fields()->get_single( $meta_key );
		$locations = maybe_unserialize( $locations );
		if ( is_array( $locations ) )
		{
			$new_locations = [];
			foreach( $locations as $location )
			{
				// Split this location into pieces.
				$pieces = explode( ':', $location );
				switch( $pieces[ 0 ] )
				{
					case 'post':
						$post_type = $pieces[ 1 ];

						// Try handling a normal post ID.
						$post_id = intval( $pieces[ 2 ] );
						if ( $post_id > 0 )
						{
							$pieces[ 2 ] = $bcd->equivalent_posts()->get_or_broadcast( $bcd->parent_blog_id, $post_id, $bcd->current_child_blog_id );
						}
						// This is a taxonomy.
						if ( $pieces[ 2 ] == 'taxonomy' )
						{
							switch_to_blog( $bcd->parent_blog_id );

							$taxonomy = $pieces[ 3 ];
							$term_id = intval( $pieces[ 4 ] );
							$synced_bcd = $this->sync_taxonomy_to_blogs( $taxonomy, [ $bcd->current_child_blog_id ] );

							restore_current_blog();

							$pieces[ 4 ] = $synced_bcd->terms()->get( $pieces[ 4 ] );
						}
						break;
				}
				$new_location = implode( ':', $pieces );
				$new_locations []= $new_location;
			}
			$bcd->custom_fields()->child_fields()->update_meta( $meta_key, $new_locations );
		}
	}

	/**
		@brief		threewp_broadcast_broadcasting_started
		@since		2017-06-30 00:09:50
	**/
	public function threewp_broadcast_broadcasting_started( $action )
	{
		// Does this page have beaver info?
		$bcd = $action->broadcasting_data;

		if ( ! isset( $bcd->beaver_builder ) )
			$bcd->beaver_builder = ThreeWP_Broadcast()->collection();

		$bb = $bcd->beaver_builder;

		foreach( [
			'_fl_builder_draft',
			'_fl_builder_data',
		] as $key )
		{
			$data = $bcd->custom_fields()->get_single( $key );
			if ( ! $data )
				continue;
			$data = maybe_unserialize( $data );

			$this->debug( 'Preparsing Beaver Builder %s', $key );
			// Go through all of the data.
			foreach( $data as $block_id => $section )
			{
				$data[ $block_id ] = $this->preparse_content( [
					'broadcasting_data' => $bcd,
					'content' => $section,
					'id' => $key . $block_id,
				] );
			}
		}
	}

	/**
		@brief		Add our supported post types.
		@since		2018-06-19 09:26:44
	**/
	public function threewp_broadcast_get_post_types( $action )
	{
		$action->add_types( 'fl-theme-layout' );
	}

	/**
		@brief		Add ourselves into the menu.
		@since		2016-01-26 14:00:24
	**/
	public function threewp_broadcast_menu( $action )
	{
		if ( ! is_super_admin() )
			return;

		$action->menu_page
			->submenu( 'threewp_broadcast_beaver_builder' )
			->callback_this( 'show_copy_settings' )
			->menu_title( 'Beaver Builder' )
			->page_title( 'Beaver Builder Broadcast' );
	}
}
