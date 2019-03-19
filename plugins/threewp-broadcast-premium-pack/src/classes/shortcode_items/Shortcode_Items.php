<?php

namespace threewp_broadcast\premium_pack\classes\shortcode_items;

use \Exception;

/**
	@brief		Generic handler for items in shortcodes.
	@since		2016-07-14 12:29:31
**/
abstract class Shortcode_Items
	extends \threewp_broadcast\premium_pack\base
{
	public function _construct()
	{
		$this->add_action( 'threewp_broadcast_menu' );
		$this->add_filter( 'threewp_broadcast_parse_content' );
		$this->add_action( 'threewp_broadcast_preparse_content' );
	}

	// --------------------------------------------------------------------------------------------
	// ----------------------------------------- Admin
	// --------------------------------------------------------------------------------------------

	/**
		@brief		Edit a shortcode.
		@since		2014-03-12 15:59:17
	**/
	public function edit_shortcode( $id )
	{
		$shortcodes = $this->shortcodes();
		if ( ! $shortcodes->has( $id ) )
			wp_die( 'No shortcode with this ID exists!' );

		$form = $this->form2();
		$shortcode = $shortcodes->get( $id );
		$r = '';

		$r .= $this->get_shortcode_editor_html();

		$form->text( 'name' )
			->description( 'The name of the shortcode.' )
			->size( 20, 128 )
			->label( 'Shortcode name' )
			->trim()
			->required()
			->value( $shortcode->shortcode );

		$form->textarea( 'value' )
			->description( 'One shortcode attribute per line that contains a single attachment ID.' )
			->label( 'Single ID attributes' )
			->rows( 5, 20 )
			->trim()
			->value( $shortcode->get_value_text() );

		$form->textarea( 'values' )
			->description( 'One shortcode attribute per line that contains mulitple attachment IDs. Delimiters are written separated by spaces after the attribute.' )
			->label( 'Multiple ID attributes' )
			->rows( 5, 20 )
			->trim()
			->value( $shortcode->get_values_text() );

		$form->markup( 'values_info' )
			->markup( 'Delimiters can be mixed within the same attribute, meaning that if you have specified commas and semicolons as delimiters, <em>ids="123,234;345"</em> will work.' );

		$form->create = $form->primary_button( 'save' )
			->value( __( 'Save the shortcode data', 'threewp_broadcast' ) );

		if ( $form->is_posting() )
		{
			$form->post()->use_post_values();

			try
			{
				$shortcode->set_shortcode( $form->input( 'name' )->get_filtered_post_value() );
				$shortcode->parse_value( $form->input( 'value' )->get_filtered_post_value() );
				$shortcode->parse_values( $form->input( 'values' )->get_filtered_post_value() );
				$this->save_shortcodes();
				$r .= $this->info_message_box()->_( __( 'The shortcode has been updated!', 'threewp_broadcast' ) );
			}
			catch( Exception $e )
			{
				$this->error_message_box()->_( 'You have errors in your settings: %s', $e->getMessage() );
			}
		}

		$r .= $form->open_tag();
		$r .= $form->display_form_table();
		$r .= $form->close_tag();

		echo $r;
	}

	/**
		@brief		Add to the admin menu.
		@since		2016-07-14 12:30:21
	**/
	public function threewp_broadcast_menu( $action )
	{
		if ( ! is_super_admin() )
			return;

		$slug = $this->get_class_slug();

		$action->menu_page
			->submenu( $slug )
			->callback_this( 'admin_menu_tabs' )
			->menu_title( $this->get_plugin_name() )
			->page_title( $this->get_plugin_name() );
	}

	/**
		@brief		Show all shortcodes.
		@since		2016-07-14 12:40:58
	**/
	public function shortcodes_overview()
	{
		$form = $this->form2();
		$r = ThreeWP_Broadcast()->html_css();

		$sc = $this->new_shortcode();
		$form->select( 'type' )
			->description( 'Choose to create an empty template or use a known shortcode.' )
			->label( 'Wizard' )
			->options( $sc->get_wizard_options() );

		$form->create = $form->primary_button( 'create' )
			->value( __( 'Create a new shortcode', 'threewp_broadcast' ) );

		$table = $this->table();
		$row = $table->head()->row();
		$table->bulk_actions()
			->form( $form )
			->add( __( 'Delete', 'threewp_broadcast' ), 'delete' )
			->cb( $row );
		$row->th()->text( 'Shortcode' );
		$row->th()->text( 'Example' );

		$shortcodes = $this->shortcodes();

		if ( $form->is_posting() )
		{
			$form->post();
			if ( $table->bulk_actions()->pressed() )
			{
				switch ( $table->bulk_actions()->get_action() )
				{
					case 'delete':
						$ids = $table->bulk_actions()->get_rows();
						foreach( $ids as $id )
							$shortcodes->forget( $id );
						$this->save_shortcodes();
						$r .= $this->info_message_box()->_( __( 'The selected shortcodes have been deleted!', 'threewp_broadcast' ) );
					break;
				}
			}
			if ( $form->create->pressed() )
			{
				$shortcodes = $this->shortcodes();
				$shortcode = $this->new_shortcode();
				$shortcode->apply_wizard( $form->input( 'type' )->get_filtered_post_value() );
				$shortcodes->append( $shortcode );
				$this->save_shortcodes();
				$r .= $this->info_message_box()->_(
					// Shortcode NAME has been created
					__( 'Shortcode %s has been created!', 'threewp_broadcast' ),
					$shortcode->get_shortcode() );
			}
		}

		foreach( $shortcodes as $index => $shortcode )
		{
			$row = $table->body()->row();
			$table->bulk_actions()->cb( $row, $index );
			$url = sprintf( '<a href="%s">%s</a>', add_query_arg( [
				'tab' => 'edit',
				'id' => $index,
			] ), $shortcode->get_shortcode() );
			$row->td()->text( $url );
			$row->td()->text( $shortcode->get_info() );
		}

		$r .= $form->open_tag();
		$r .= $table;
		$r .= $this->p( 'The spaces in the example column are for legibility.' );
		$r .= $form->display_form_table();
		$r .= $form->close_tag();

		echo $r;
	}

	/**
		@brief		Menu tabs.
		@since		2016-07-14 12:39:44
	**/
	public function admin_menu_tabs()
	{
		$tabs = $this->tabs();

		$tabs->tab( 'shortcodes' )
			->callback_this( 'shortcodes_overview' )
			->name( __( 'Shortcodes', 'threewp_broadcast' ) );

		if ( $tabs->get_is( 'edit' ) )
		{
			$tabs->tab( 'edit' )
				->callback_this( 'edit_shortcode' )
				->parameters( intval( $_GET[ 'id' ] ) )
				->name( __( 'Edit', 'threewp_broadcast' ) );
		}

		echo $tabs->render();
	}

	// --------------------------------------------------------------------------------------------
	// ----------------------------------------- Content parsing
	// --------------------------------------------------------------------------------------------

	/**
		@brief		Parse a find array containing a value or values.
		@since		2016-07-14 13:40:28
	**/
	public function parse_find( $data, $find )
	{
	}

	public function replace_id( $broadcasting_data, $find, $old_id )
	{
		return $broadcasting_data->copied_attachments()->get( $old_id );
	}

	/**
		@brief		Parse the content, replacing shortcodes.
		@since		2016-07-14 13:57:20
	**/
	public function threewp_broadcast_parse_content( $action )
	{
		$bcd = $action->broadcasting_data;		// Convenience.

		$slug = $this->get_class_slug() . '_preparse';

		if ( ! isset( $bcd->$slug ) )
			return;

		$finds = $bcd->$slug->get( $action->id, [] );

		foreach( $finds as $find )
		{
			$shortcode = $find->original;
			// Find single IDs
			foreach( $find->value as $attribute => $old_id )
			{
				$new_id = $this->replace_id( $bcd, $find, $old_id );
				if ( $new_id )
				{
					$old_attribute = sprintf( '/(%s=[\"|\'])%s([\"|\'])/', $attribute, $old_id );
					$new_attribute = sprintf( '${1}%s${2}', $new_id );
					$shortcode = preg_replace( $old_attribute, $new_attribute, $shortcode );
				}
			}

			// Find multiple IDs
			foreach( $find->values as $attribute => $data )
			{
				$ids = $data[ 'ids' ];
				$delimiters = $data[ 'delimiters' ];

				$old_ids = $ids;
				$new_ids = $old_ids;
				foreach( $ids as $index => $old_id )
				{
					$new_id = $this->replace_id( $bcd, $find, $old_id );
					if ( $new_id )
						$new_ids[ $index ] = $new_id;
				}
				$old_regexp = sprintf( '/%s="%s"/', $attribute, implode( '(.*)', $old_ids ) );
				$new_regexp = reset( $new_ids );
				array_shift( $new_ids );
				foreach( $new_ids as $index => $new_id )
					$new_regexp .= sprintf( '${%s}%s', $index+1, $new_id );
				$new_regexp = sprintf( '%s="%s"', $attribute, $new_regexp );

				$this->debug( 'Replacing old shortcode <em>%s</em> with new shortcode <em>%s</em>.', htmlspecialchars( $find->original ), htmlspecialchars( $shortcode ) );
				$shortcode = preg_replace( $old_regexp, $new_regexp, $shortcode );
			}

			$this->debug( 'Replacing shortcode <em>%s</em> with <em>%s</em>', htmlspecialchars( $find->original ), htmlspecialchars( $shortcode ) );
			$action->content = str_replace( $find->original, $shortcode, $action->content );
		}
	}

	/**
		@brief		Preparse some content.
		@since		2016-07-14 13:27:35
	**/
	public function threewp_broadcast_preparse_content( $action )
	{
		$bcd = $action->broadcasting_data;		// Convenience.
		$content = $action->content;			// Also very convenient.

		$slug = $this->get_class_slug() . '_preparse';

		// In case another preparse hasn't asked for this already.
		if ( ! isset( $bcd->$slug ) )
			$bcd->$slug = ThreeWP_Broadcast()->collection();

		$shortcodes = $this->shortcodes();

		$finds = [];

		foreach( $shortcodes as $shortcode )
		{
			$this->debug( 'Looking for shortcode: %s', $shortcode->shortcode );
			$matches = ThreeWP_Broadcast()->find_shortcodes( $content, [ $shortcode->shortcode ] );

			if ( count( $matches[ 0 ] ) < 1 )
				continue;

			// We've found something!
			// [2] contains only the shortcode command / key. No options.
			foreach( $matches[ 2 ] as $index => $key )
			{
				// Does the key match this shortcode?
				if ( $key !== $shortcode->shortcode )
					continue;
				$find = ThreeWP_Broadcast()->collection();
				$find->value = ThreeWP_Broadcast()->collection();
				$find->values = ThreeWP_Broadcast()->collection();

				// Complete match is in 0.
				$find->original = $matches[ 0 ][ $index ];

				// Trim off everything after the first ]
				$find->original = preg_replace( '/\].*/s', ']', $find->original );

				$this->debug( 'Found shortcode %s as %s', $key, htmlspecialchars( $find->original ) );

				// Extract the image ID
				foreach( $shortcode->value as $attribute => $ignore )
				{
					// Does this shortcode use this attribute?
					if ( strpos( $find->original, $attribute . '=' ) === false )
					{
						$this->debug( 'The shortcode does not contain the attribute %s.', $attribute );
						continue;
					}

					// Remove anything before the attribute
					$string = preg_replace( '/.*' . $attribute .'=[\"|\']/', '', $find->original );
					// And everything after the quotes.
					$string = preg_replace( '/[\"|\'].*/s', '', $string );

					// Workaround for shortcodes that don't follow the Wordpress standards: remove single apostrophies from the ends.
					$string = trim( $string, "'" );

					$this->debug( 'Attribute is: %s', $string );

					$id = $string;

					$this->debug( 'Found item %s in attribute %s.', $id, $attribute );

					$find->value->set( $attribute, $id );
				}

				// Extract the images IDs
				foreach( $shortcode->values as $attribute => $delimiters )
				{
					// Does this shortcode use this attribute?
					if ( strpos( $find->original, $attribute . '=' ) === false )
					{
						$this->debug( 'The shortcode does not contain the attribute %s.', $attribute );
						continue;
					}

					// Remove anything before the attribute
					$string = preg_replace( '/.*' . $attribute .'=[\"|\']/', '', $find->original );
					// And everything after the quotes.
					$string = preg_replace( '/[\"|\'].*/', '', $string );

					// Workaround for shortcodes that don't follow the Wordpress standards: remove single apostrophies from the ends.
					$string = trim( $string, "'" );

					$this->debug( 'Attribute is: %s', $string );

					$ids = $string;

					// Convert all delimiters to commas.
					foreach( $delimiters as $delimiter )
						$ids = str_replace( $delimiter, ',', $ids );

					$this->debug( 'While looking in attribute %s, we found this: <em>%s</em>', $attribute, htmlspecialchars( $ids ) );
					// And now explode the ids.
					$ids = explode( ',', $ids );

					// Save the IDs in the find.
					$find->values->set( $attribute, [
						'ids' => $ids,
						'delimiters' => $delimiters,
					] );

					$this->debug( 'Found items %s in attribute %s', implode( ', ', $ids ), $attribute );
				}

				$this->debug( 'Adding this find to the array.' );
				$this->parse_find( $bcd, $find );
				$finds []= $find;
			}
		}

		$this->debug( 'Found %s shortcode occurrences in the content.', count( $finds ) );

		if ( count( $finds ) < 1 )
			return;

		$bcd->$slug->set( $action->id, $finds );
	}

	// --------------------------------------------------------------------------------------------
	// ----------------------------------------- Misc functions
	// --------------------------------------------------------------------------------------------

	/**
		@brief		Return any html that you want to display above the shortcode editor.
		@since		2016-07-14 13:10:17
	**/
	public function get_shortcode_editor_html()
	{
	}

	/**
		@brief		Return the class slug.
		@since		2016-07-14 13:28:59
	**/
	public function get_class_slug()
	{
		$slug = get_called_class();
		$slug = preg_replace( '/.*\\\\/', 'bc_', $slug );
		$slug = sanitize_title( $slug );
		return $slug;
	}

	/**
		@brief		Create a new shortcode object.
		@since		2016-07-14 12:54:45
	**/
	public abstract function new_shortcode();

	/**
		@brief		Create a collection of items.
		@since		2016-07-14 12:44:29
	**/
	public function new_shortcodes()
	{
		return new Shortcodes();
	}

	/**
		@brief		Save the shortcodes.
		@since		2016-07-14 13:02:18
	**/
	public function save_shortcodes()
	{
		$this->update_site_option( 'shortcodes', $this->shortcodes() );
	}

	/**
		@brief		Load all of the shortcodes.
		@since		2016-07-14 12:42:50
	**/
	public function shortcodes()
	{
		if ( isset( $this->__shortcodes ) )
			return $this->__shortcodes;
		$this->__shortcodes = $this->get_site_option( 'shortcodes', null );
		if ( ! $this->__shortcodes )
			$this->__shortcodes = $this->new_shortcodes();
		return $this->__shortcodes;
	}

}
