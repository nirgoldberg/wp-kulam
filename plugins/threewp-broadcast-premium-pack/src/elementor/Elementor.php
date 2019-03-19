<?php

namespace threewp_broadcast\premium_pack\elementor;

/**
	@brief			Adds support for the <a href="https://wordpress.org/plugins/elementor/">Elementor Page Builder plugin</a>.
	@plugin_group	3rd party compatability
	@since			2017-04-28 23:16:00
**/
class Elementor
	extends \threewp_broadcast\premium_pack\base
{
	public function _construct()
	{
		$this->add_action( 'threewp_broadcast_broadcasting_started' );
		$this->add_action( 'threewp_broadcast_broadcasting_before_restore_current_blog' );
		$this->add_action( 'threewp_broadcast_get_post_types' );
		new Elementor_Template_Shortcode();
	}

	/**
		@brief		Returns the post's Elementor CSS filename.
		@since		2017-08-09 17:21:09
	**/
	public function get_post_css_file( $post_id )
	{
		$wp_upload_dir = wp_upload_dir();
		$path = sprintf( '%s%s', $wp_upload_dir['basedir'], \Elementor\CSS_File::FILE_BASE_DIR );
		$new_filename = sprintf( '%s/post-%d.css', $path, $post_id );
		return $new_filename;
	}

	/**
		@brief		Parse an EL element, looking for images and the like.
		@since		2017-04-29 02:14:28
	**/
	public function parse_element( $bcd, $element )
	{
		if ( isset( $element->settings ) )
			if ( isset( $element->settings->background_image ) )
				if ( $element->settings->background_image->id > 0 )
				{
					$image_id = $element->settings->background_image->id;
					$this->debug( 'Found background image %s.', $image_id );
					$bcd->add_attachment( $image_id );
				}

		if ( $element->elType == 'widget' )
		{
			switch( $element->widgetType )
			{
				case 'image':
					$image_id = $element->settings->image->id;
					$this->debug( 'Found image widget. Adding attachment %s', $image_id );
					$bcd->add_attachment( $image_id );
					break;
				case 'image-gallery':
					foreach( $element->settings->wp_gallery as $gallery_index => $gallery_item )
					{
						$image_id = $gallery_item->id;
						$this->debug( 'Found gallery widget. Adding attachment %s', $image_id );
						$bcd->add_attachment( $image_id );
					}
					break;
			}
		}

		if ( ! isset( $element->elements ) )
			return $element;

		// Parse subelements.
		foreach( $element->elements as $element_index => $subelement )
			$this->parse_element( $bcd, $subelement );

		return $element;
	}

	/**
		@brief		threewp_broadcast_broadcasting_before_restore_current_blog
		@since		2017-04-28 23:39:15
	**/
	public function threewp_broadcast_broadcasting_before_restore_current_blog( $action )
	{
		$bcd = $action->broadcasting_data;

		$meta_key = '_elementor_data';

		$ed = $bcd->custom_fields()->get_single( '_elementor_data' );

		if ( ! $ed )
			return;

		$ed = json_decode( $ed );

		if ( ! $ed )
			return;

		foreach( $ed as $index => $element )
			$ed[ $index ] = $this->update_element( $bcd, $element );

		$ed = json_encode( $ed );

		$this->debug( 'Updating elementor data: <pre>%s</pre>', htmlspecialchars( $ed ) );
		// TODO: Change this to a update_meta_json call after v40
		$bcd->custom_fields()
			->child_fields()
			->update_meta_json( $meta_key, $ed );

		// Copy the css file.
		if ( ! isset( $bcd->elementor ) )
			return;
		$old_filename = $bcd->elementor->get( 'old_post_css_filename' );
		$new_filename = $this->get_post_css_file( $bcd->new_post( 'ID' ) );

		// Replace the post ID in the file.
		$css_file = file_get_contents( $old_filename );
		$css_file = str_replace( 'elementor-' . $bcd->post->ID, 'elementor-' . $bcd->new_post( 'ID' ), $css_file );

		file_put_contents( $new_filename, $css_file );

		$this->debug( 'Copied Elementor CSS file %s to %s', $old_filename, $new_filename );
	}

	/**
		@brief		threewp_broadcast_broadcasting_started
		@since		2017-04-28 23:39:00
	**/
	public function threewp_broadcast_broadcasting_started( $action )
	{
		$bcd = $action->broadcasting_data;

		$ed = $bcd->custom_fields()->get_single( '_elementor_data' );

		if ( ! $ed )
			return;

		$ed = json_decode( $ed );
		if ( ! $ed )
			return $this->debug( 'Warning! Elementor data is invalid!' );

		$this->debug( 'Elementor data found: %s', $ed );

		// Remember things.
		foreach( $ed as $index => $section )
			$this->parse_element( $bcd, $section );

		if ( ! isset( $bcd->elementor ) )
			$bcd->elementor = ThreeWP_Broadcast()->collection();

		$bcd->elementor->set( 'old_post_css_filename', $this->get_post_css_file( $bcd->post->ID ) );
		$this->debug( 'Saved old Elementor CSS filename %s', $bcd->elementor->get( 'old_post_css_filename' ) );
	}

	/**
		@brief		Add foogallery types.
		@since		2015-10-02 12:47:49
	**/
	public function threewp_broadcast_get_post_types( $action )
	{
		$action->add_type( 'elementor_library' );
	}

	/**
		@brief		Update the Elementor data with new values.
		@since		2017-04-29 02:26:52
	**/
	public function update_element( $bcd, $element )
	{
		if ( isset( $element->settings ) )
			if ( isset( $element->settings->background_image ) )
				if ( $element->settings->background_image->id > 0 )
				{
					$old_image_id = $element->settings->background_image->id;
					$new_image_id = $bcd->copied_attachments()->get( $old_image_id );
					$this->debug( 'Replacing old background image %s with %s.', $old_image_id, $new_image_id );
					$element->settings->background_image->id = $new_image_id;
					$element->settings->background_image->url = ThreeWP_Broadcast()->update_attachment_ids( $bcd, $element->settings->background_image->url );
				}

		if ( $element->elType == 'widget' )
		{
			switch( $element->widgetType )
			{
				case 'image':
					$image_id = $element->settings->image->id;
					$new_image_id = $bcd->copied_attachments()->get( $image_id );
					$this->debug( 'Found image widget. Replacing %s with %s.', $image_id, $new_image_id );
					$element->settings->image->id = $new_image_id;
					$element->settings->image->url = ThreeWP_Broadcast()->update_attachment_ids( $bcd, $element->settings->image->url );
					break;
				case 'image-gallery':
					foreach( $element->settings->wp_gallery as $gallery_index => $gallery_item )
					{
						$image_id = $gallery_item->id;
						$new_image_id = $bcd->copied_attachments()->get( $image_id );
						$this->debug( 'Found gallery widget. Replacing %s with %s', $image_id, $new_image_id );
						$element->settings->wp_gallery[ $gallery_index ]->id = $new_image_id;
						$element->settings->wp_gallery[ $gallery_index ]->url = ThreeWP_Broadcast()->update_attachment_ids( $bcd, $element->settings->wp_gallery[ $gallery_index ]->url );
					}
					break;
				case 'template':
					$old_template_id = $element->settings->template_id;
					$new_template_id = $bcd->equivalent_posts()->get( $bcd->parent_blog_id, $old_template_id, get_current_blog_id() );
					$this->debug( 'Found template widget. Replacing %d with %d.', $old_template_id, $new_template_id );
					$element->settings->template_id = $new_template_id;
					break;
			}
		}

		if ( ! isset( $element->elements ) )
			return $element;

		// Update subelements.
		foreach( $element->elements as $element_index => $subelement )
			$element->elements[ $element_index ] = $this->update_element( $bcd, $subelement );

		return $element;
	}
}
