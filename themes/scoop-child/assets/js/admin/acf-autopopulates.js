jQuery(document).ready(function($) {

	// post types
	post_types_select			= $('.acf-field-taxonomy[data-name="acf-category_post_types"] select');
	selected_post_types			= post_types_select.find('option:selected');

	// top posts relationship fields
	top_posts_fields_prefix		= 'kulam_top_posts_relationship_';
	top_posts_fields			= $('[data-name^="' + top_posts_fields_prefix + '"]');
	default_top_posts_fields	= $('.default_top_posts_relationship');

	// get top posts field wrapper
	top_posts_fields_wrapper	= top_posts_fields.parent('tbody');

	// add identifier class to top posts relationship fields
	top_posts_fields_wrapper.addClass('top-posts-wrapper');

	/**
	 * Category Top Posts
	 * Expose top posts relationship fields for selected post types on edit category page load
	 */
	expose_top_posts();

	/**
	 * Hide/expose top posts relationship fields on post types selection change event
	 */
	$(document).on('change', '.acf-field-taxonomy[data-name="acf-category_post_types"] select', function () {

		// variables
		var post_types_select	= $(this);

		// get selected post types
		selected_post_types		= post_types_select.find('option:selected');

		// expose top posts relationship fields for selected post types
		expose_top_posts();

	});

	// expose top posts relationship fields for selected post types
	function expose_top_posts() {

		// variables;
		var index = 1;

		// hide top posts relationship fields
		top_posts_fields.addClass('acf-hidden');

		if (selected_post_types.length) {
			// expose top posts relationship fields for selected post types
			selected_post_types.each(function() {
				// variables
				var post_type_id = $(this).val();

				$('.' + top_posts_fields_prefix + post_type_id).removeClass('acf-hidden').css('order', index++);
			});
		}
		else {
			// expose default top posts relationship fields
			default_top_posts_fields.removeClass('acf-hidden').css('order', '');
		}

	}

});