<?php

namespace threewp_broadcast\premium_pack\polylang;

/**
	@brief				Adds support for Frédéric Demarle's <a href="https://wordpress.org/plugins/polylang/">Polylang</a> translation plugin.
	@plugin_group		3rd party compatability
	@since				2014-11-12 19:51:37
**/
class Polylang
	extends \threewp_broadcast\premium_pack\base
{
	public function _construct()
	{
		$this->add_filter( 'threewp_broadcast_broadcasting_finished' );
		$this->add_filter( 'threewp_broadcast_broadcasting_started' );
		$this->add_action( 'threewp_broadcast_broadcasting_after_switch_to_blog' );
		$this->add_action( 'threewp_broadcast_broadcasting_before_restore_current_blog' );
		$this->add_action( 'threewp_broadcast_collect_post_type_taxonomies', 8 );
		$this->add_action( 'threewp_broadcast_menu' );
		$this->add_action( 'threewp_broadcast_synced_taxonomy' );
		$this->add_action( 'threewp_broadcast_wp_update_term', 2 );		// Broadcast handles this at 5, so we have to do it before BC.
		$this->add_action( 'threewp_broadcast_wp_insert_term', 2 );
	}

	// --------------------------------------------------------------------------------------------
	// ----------------------------------------- Admin
	// --------------------------------------------------------------------------------------------

	/**
		@brief		menu_settings
		@since		2016-12-13 15:47:29
	**/
	public function menu_settings()
	{
		$form = $this->form();
		$r = '';

		$no_language_action = $form->select( 'no_language_action' )
			// Input description
			->description( __( "What to do when the child blog does not have the post's language.", 'threewp_broadcast' ) )
			// Input label
			->label( __( 'No language action', 'threewp_broadcast' ) )
			// Select option for when there is no language on the blog
			->option( __( "Broadcast the post in the blog's default language", 'threewp_broadcast' ), 'default_language' )
			// Select option for when there is no language on the blog
			->option( __( "Do not broadcast to the blog", 'threewp_broadcast' ), '' )
			->value( $this->get_site_option( 'no_language_action' ) );

		$update_all_translations = $form->checkbox( 'update_all_translations' )
			->checked( $this->get_site_option( 'update_all_translations' ) )
			// Input description
			->description( __( "Broadcast all translations of a post when saving, else just broadcast the current language.", 'threewp_broadcast' ) )
			// Input label
			->label( __( 'Update all translations', 'threewp_broadcast' ) );

		$save = $form->primary_button( 'save' )
			->value( __( 'Save settings', 'threewp_broadcast' ) );

		if ( $form->is_posting() )
		{
			$form->post();
			$form->use_post_values();

			$value = $no_language_action->get_post_value();
			$this->update_site_option( 'no_language_action', $value );
			$value = $update_all_translations->get_post_value();
			$this->update_site_option( 'update_all_translations', $value );

			$r .= $this->info_message_box()->_( __( 'Settings saved!', 'threewp_broadcast' ) );
		}

		$r .= $form->open_tag();
		$r .= $form->display_form_table();
		$r .= $form->close_tag();

		// Settings page header
		echo $this->wrap( $r, __( 'Broadcast Polylang Settings', 'threewp_broadcast' ) );
	}

	// --------------------------------------------------------------------------------------------
	// ----------------------------------------- Callbacks
	// --------------------------------------------------------------------------------------------

	/**
		@brief		Is the language available on this blog?
		@since		2014-11-12 19:55:49
	**/
	public function threewp_broadcast_broadcasting_after_switch_to_blog( $action )
	{
		if ( ! $this->action_check( $action ) )
			return;

		$bcd = $action->broadcasting_data;

		if ( ! $bcd->polylang->pll_is_translated_post_type )
			return $this->debug( 'Not a translated post type.' );

		$this->clear_polylang_languages_cache();

		// Assume no translations for this blog.
		$bcd->polylang->child_translations = [];

		// Does this blog have the language?
		$found = false;
		foreach( $this->get_languages_list() as $language )
			$found |= ( $language->slug == $bcd->polylang->language );

		if ( ! $found )
		{
			$no_language_action = $this->get_site_option( 'no_language_action' );
			switch ( $no_language_action )
			{
				case 'default_language':
					$bcd->polylang->no_language_action = $no_language_action;
					// Do not broadcast taxonomies since they will all be in the parent post's language.
					$bcd->polylang->displaced_taxonomies = $bcd->parent_post_taxonomies;
					$bcd->parent_post_taxonomies = [];
					$bcd->polylang->old_pref_lang = PLL()->pref_lang;
					PLL()->pref_lang = pll_default_language();
					return;
				case '':
					$action->broadcast_here = false;
			}
		}

		if ( ! $action->broadcast_here )
			return $this->debug( 'This blog does not have language %s enabled.', $bcd->polylang->language );

		// Assume no child translations.
		$bcd->polylang->child_translations = [];

		if ( $action->broadcasting_data->link )
		{
			$child_id = $action->broadcasting_data->broadcast_data->get_linked_post_on_this_blog();
			if ( ! $child_id )
				return $this->debug( 'No child on this blog.' );
			// Save these translations for later when we set the new child IDs, if any.
			$bcd->polylang->child_translations = pll_get_post_translations( $child_id );
		}

		$this->debug( 'Current child translations: %s', $bcd->polylang->child_translations );
	}

	/**
		@brief		Set the translations of this post.
		@details	This is a two step process.

					1. Manually clear the current listed of translations from the term.
					2. Add the current language to the translated children.

					#1 because the term is synced from the parent blog, and it contains links to the wrong posts.

		@since		2014-11-12 19:53:51
	**/
	public function threewp_broadcast_broadcasting_before_restore_current_blog( $action )
	{
		if ( ! $this->action_check( $action ) )
			return;

		$bcd = $action->broadcasting_data;

		if ( ! $bcd->polylang->pll_is_translated_post_type )
			return $this->debug( 'Not a translated post type.' );

		if ( isset( $bcd->polylang->no_language_action ) )
		{
			// Broadcast as normal, but use the blog default language.
			if ( $bcd->polylang->no_language_action == 'default_language' )
				$post_language = pll_default_language();
		}
		else
			$post_language = $bcd->polylang->language;

		$this->debug( 'Post language will be: %s', $post_language );

		// Clear the description manually, which is where the translation array is contained.
		$terms = wp_get_object_terms( $bcd->polylang->child_translations, 'post_translations' );
		foreach( $terms as $term )
			wp_update_term( $term->term_id, 'post_translations', [ 'description' => serialize( [] ) ] );

		$post_id = $action->broadcasting_data->new_post()->ID;

		$bcd->polylang->child_translations[ $post_language ] = $post_id;

		$languages = $this->get_languages_list();
		foreach( $languages as $language )
			foreach( $bcd->polylang->translations as $translation_language => $translation_post_id )
				// Only set languages that are active on this blog.
				if ( $translation_language == $language->slug )
				{
					$child_bcd = $bcd->polylang->bcds[ $translation_post_id ];
					$linked_child = $child_bcd->get_linked_post_on_this_blog();
					if ( $linked_child )
						$bcd->polylang->child_translations[ $translation_language ] = $linked_child;
				}

		$this->debug( 'Saving post translations: %s', $bcd->polylang->child_translations );

		PLL()->model->post->set_language( $post_id, $post_language );

		// save_post_translations wants post_id => lang_name, which is cute because get_post_translations gives it to us the other way.
		array_flip( $bcd->polylang->child_translations );
		pll_save_post_translations( $bcd->polylang->child_translations );

		if ( isset( $bcd->polylang->no_language_action ) )
		{
			unset( $bcd->polylang->no_language_action );

			// Maybe the next blog will have the language, so put everything back as it was.
			$bcd->parent_post_taxonomies = $bcd->polylang->displaced_taxonomies;
			unset( $bcd->polylang->displaced_taxonomies );

			// Restore the pref lang.
			PLL()->pref_lang = $bcd->polylang->old_pref_lang;
			unset( $bcd->polylang->old_pref_lang );
		}
	}

	/**
		@brief		threewp_broadcast_broadcasting_finished
		@since		2015-04-14 17:16:44
	**/
	public function threewp_broadcast_broadcasting_finished( $action )
	{
		$this->clear_polylang_languages_cache();

		if ( ! $this->get_site_option( 'update_all_translations' ) )
			return;

		if ( isset( $this->__update_all_translations ) )
			return;

		$bcd = $action->broadcasting_data;

		$this->__update_all_translations = true;

		foreach( $bcd->polylang->translations as $lang_id => $translated_post_id )
		{
			if ( $translated_post_id == $bcd->post->ID )
				continue;
			$this->debug( 'Updating translation %s (%s)', $lang_id, $translated_post_id );
			ThreeWP_Broadcast()->api()
				->update_children( $translated_post_id );
		}

		$this->debug( 'Finished updating translations.' );

		unset( $this->__update_all_translations );
	}

	/**
		@brief		threewp_broadcast_broadcasting_started
		@since		2014-11-12 19:54:17
	**/
	public function threewp_broadcast_broadcasting_started( $action )
	{
		if ( ! $this->has_polylang() )
			return;

		$bcd = $action->broadcasting_data;

		$this->prepare_bcd( $bcd );

		$post = $bcd->post;
		$bcd->polylang->pll_is_translated_post_type = pll_is_translated_post_type( $post->post_type );

		if ( ! $bcd->polylang->pll_is_translated_post_type )
			return;

		// Disable Polylang's save_post.
		$pll = PLL();
		if ( isset( $pll->filters_post ) )
		{
			$this->debug( 'Disabling save_post for Polylang.' );
			remove_action( 'save_post', array( PLL()->filters_post, 'save_post' ), 21, 3 );
		}

		$bcd->polylang->language = pll_get_post_language( $post->ID );
		$bcd->polylang->translations = PLL()->model->post->get_translations( $post->ID );

		global $polylang;
		// Preferred language term ID is incorrect, so get the slug.
		if ( is_object( $polylang->pref_lang ) )
			$polylang->pref_lang = $bcd->polylang->language;

		// We need to load all of the broadcast datas for each translation.
		$blog_id = get_current_blog_id();
		$bcd->polylang->bcds = [];
		foreach( $bcd->polylang->translations as $translated_post_id )
			$bcd->polylang->bcds[ $translated_post_id ] = ThreeWP_Broadcast()->get_post_broadcast_data( $blog_id, $translated_post_id );
	}

	/**
		@brief		threewp_broadcast_collect_post_type_taxonomies
		@since		2017-11-20 12:21:39
	**/
	public function threewp_broadcast_collect_post_type_taxonomies( $action )
	{
		if ( ! $this->has_polylang() )
			return;

		$bcd = $action->broadcasting_data;
		$this->prepare_bcd( $bcd );

		// Remove taxonomies even though we might be __collecting_more_terms. This is to ensure that things aren't collected unnecessarily.
		// Remove all parent blog taxonomies that are translations.
		$post_types = get_post_types();
		foreach( $post_types as $post_type => $ignore )
		{
			$key = $post_type . '_translations';
			if ( isset( $bcd->parent_blog_taxonomies[ $key ] ) )
			{
				$this->debug( 'Unsetting parent blog taxonomy %s', $key );
				unset( $bcd->parent_blog_taxonomies[ $key ] );
			}
			if ( isset( $bcd->parent_post_taxonomies[ $key ] ) )
			{
				$this->debug( 'Unsetting parent post taxonomy %s', $key );
				unset( $bcd->parent_post_taxonomies[ $key ] );
			}
		}

		// The language taxonomy we don't want synced.
		$this->debug( 'Unsetting taxonomies language' );
		unset( $bcd->parent_blog_taxonomies[ 'language' ] );
		unset( $bcd->parent_post_taxonomies[ 'language' ] );

		if ( isset( $this->__collecting_more_terms ) )
			return;

		// Use BLOG terms in order to get all translations correct.
		foreach( $bcd->parent_blog_taxonomies as $parent_post_taxonomy => $taxonomy )
		{
			$this->__collecting_more_terms = true;
			// Remove the filter that prevents ALL terms from being shown. This filter causes havoc in several places.
			remove_filter( 'terms_clauses', array( PLL()->filters_term, 'terms_clauses' ), 10, 3 );
			$bcd->taxonomies()->also_sync( $bcd->post->post_type, $parent_post_taxonomy );
			//add_filter( 'terms_clauses', array( PLL()->filters_term, 'terms_clauses' ), 10, 3 );
			unset( $this->__collecting_more_terms );

			// Reload the modified taxonomy from the bcd.
			$taxonomy = $bcd->parent_blog_taxonomies[ $parent_post_taxonomy ];

			foreach( $taxonomy[ 'terms' ] as $term )
			{
				$translations = PLL()->model->term->get_translations( $term->term_id );

				if ( count( $translations ) < 1 )
				{
					$this->debug( 'No translations for taxonomy %s %s (%s)', $parent_post_taxonomy, $term->slug, $term->term_id );
					continue;
				}
				$this->debug( 'Translations for taxonomy %s %s (%s): %s', $parent_post_taxonomy, $term->slug, $term->term_id, $translations );

				$bcd->polylang->collection( 'term_translations' )
					->set( $term->term_id, $translations );
			}
		}
	}

	/**
		@brief		Prevent languages from being created.
		@since		2017-11-24 18:28:43
	**/
	public function threewp_broadcast_wp_insert_term( $action )
	{
		if ( ! isset( $action->broadcasting_data->polylang ) )
			return;

		// Do not sync languages.
		if ( $action->taxonomy == 'language' )
		{
			$this->debug( 'Do not insert any languages.' );
			$action->finish();
			return;
		}

		// Prevent terms from being created that have a non-existent language on this blog.

		$parent_translations = $action->broadcasting_data->polylang->collection( 'term_translations' )
			->get( $action->term->term_id );

		// No parent translations. Nothing to do.
		if ( ! $parent_translations )
			return;

		$parent_translations = array_flip( $parent_translations );
		$term_language = $parent_translations[ $action->term->term_id ];

		$languages = $this->get_languages_list();
		foreach( $languages as $language )
			if ( $language->slug == $term_language )
				return;
		$action->finish();
		return;
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
			->submenu( 'threewp_broadcast_polylang' )
			->callback_this( 'menu_settings' )
			// Menu item name
			->menu_title( 'Polylang' )
			// Page title
			->page_title( 'Polylang Broadcast' );
	}

	/**
		@brief		This taxonomy has just been synced.
		@since		2017-11-20 12:21:59
	**/
	public function threewp_broadcast_synced_taxonomy( $action )
	{
		if ( ! $this->has_polylang() )
			return;

		$this->clear_polylang_languages_cache();

		$bcd = $action->broadcasting_data;

		if ( ! isset( $bcd->polylang ) )
			return;

		// Which translations are on this child.
		$translations = [];

		// Languages on this blog.
		$languages = $this->get_languages_list();
		foreach( $this->get_languages_list() as $language )
			$languages [ $language->slug ] = $language;

		foreach( $bcd->parent_blog_taxonomies[ $action->taxonomy ][ 'terms' ] as $term )
		{
			$parent_term_id = $term->term_id;
			$parent_translations = $bcd->polylang->collection( 'term_translations' )
				->get( $parent_term_id );

			// Are there translations for this term?
			if ( ! $parent_translations )
				continue;

			$parent_translations = array_flip( $parent_translations );
			if ( ! isset( $parent_translations[ $parent_term_id ] ) )
				continue;

			$language = $parent_translations[ $parent_term_id ];

			$child_term_id = $bcd->terms()->get( $parent_term_id );
			$translations[ $child_term_id ] = [];

			foreach( $parent_translations as $term_id => $language )
			{
				if ( ! isset( $languages[ $language ] ) )
					continue;
				$lang_term = $bcd->terms()->get( $term_id );
				$translations[ $child_term_id ][ $language ] = $lang_term;
			}
		}

		$this->debug( 'Saving translations for taxonomy %s: %s', $action->taxonomy, $translations );
		foreach( $translations as $term_id => $new_translations )
		{
			// Set the language for each term, else new terms will be marked as languageless.
			foreach( $new_translations as $lang => $term_id )
				PLL()->model->term->set_language( $term_id, $lang );
			PLL()->model->term->save_translations( $term_id, $new_translations );
		}

		$this->clear_polylang_languages_cache();
	}

	/**
		@brief		If this a language, don't change the description, which contains the exact language and flag info.
		@since		2016-04-13 16:22:28
	**/
	public function threewp_broadcast_wp_update_term( $action )
	{
		if ( $action->taxonomy != 'language' )
			return;
		$this->debug( 'Keeping old language data: %s', $action->old_term->description );
		$action->new_term->description = $action->old_term->description;
	}

	// --------------------------------------------------------------------------------------------
	// ----------------------------------------- Misc functions
	// --------------------------------------------------------------------------------------------

	/**
		@brief		Check for the existence of Polylang.
		@since		2017-11-20 12:29:46
	**/
	public function action_check( $action )
	{
		if ( ! $this->has_polylang() )
			return false;

		if ( ! isset( $action->broadcasting_data->polylang ) )
			return false;

		return true;
	}

	/**
		@brief		Convenience method to clear polylang's language cache.
		@since		2015-04-14 17:17:27
	**/
	public function clear_polylang_languages_cache()
	{
		global $polylang;
		if ( ! $polylang )
			return;
		// Else it thinks we have the languages from the previous blog.
		$polylang->model->clean_languages_cache();
	}

	/**
		@brief		Return a list of languages on this blog.
		@since		2014-11-12 21:39:36
	**/
	public function get_languages_list()
	{
		global $polylang;
		return $polylang->model->get_languages_list();
	}

	/**
		@brief		Is Polylang available?
		@since		2014-11-12 19:58:42
	**/
	public function has_polylang()
	{
		return defined( 'POLYLANG_VERSION' );
	}

	/**
		@brief		prepare_bcd
		@since		2017-11-20 12:24:15
	**/
	public function prepare_bcd( $broadcasting_data )
	{
		if ( isset( $broadcasting_data->polylang ) )
			return;
		$broadcasting_data->polylang = ThreeWP_Broadcast()->collection();
		return $broadcasting_data;
	}

	public function site_options()
	{
		return array_merge( [
			'no_language_action' => '',
			'update_all_translations' => false,
		], parent::site_options() );
	}

}
