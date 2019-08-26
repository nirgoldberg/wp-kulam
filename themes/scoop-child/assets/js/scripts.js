var $ = jQuery,
	KULAM_general = {

		/**
		 * params
		 */
		params : {

			window_width			: 0,		// client window width - used to maintain window resize events (int)
			breakpoint				: '',		// CSS media query breakpoint (int)
			prev_breakpoint			: '',		// previous media query breakpoint (int)
			timeout					: 400		// general timeout (int)

		},

		/**
		 * init
		 *
		 * @param	N/A
		 * @return	N/A
		 */
		init : function() {

			// jQuery extentions
			$.fn.setAllToMaxHeight = function() {
				return this.height( Math.max.apply(this, $.map(this, function(e) { return $(e).height() })) );
			}

			// accessibility icon direction
			KULAM_general.a11y_icon_direction();

			// page title
			KULAM_general.page_title();

			// popups
			KULAM_general.popups();

			// advanced search
			KULAM_general.advanced_search();

			// bootstrap modal
			KULAM_general.bootstrap_modal();

			// my siddur
			KULAM_general.my_siddur();

			// post types posts grid
			KULAM_general.post_types_posts_grid();

		},

		/**
		 * a11y_icon_direction
		 *
		 * Called from init
		 *
		 * @param	N/A
		 * @return	N/A
		 */
		a11y_icon_direction : function() {

			// variables
			var icon = $('#pojo-a11y-toolbar');

			if ($('body').hasClass('rtl')) {
				icon.removeClass('pojo-a11y-toolbar-right').addClass('pojo-a11y-toolbar-left');
			}
			else {
				icon.removeClass('pojo-a11y-toolbar-left').addClass('pojo-a11y-toolbar-right');
			}

		},

		/**
		 * page_title
		 *
		 * Called from init
		 *
		 * @param	N/A
		 * @return	N/A
		 */
		page_title : function() {

			// toggle category description
			$('.page-title .more, .page-title .less').click(function() {
				$('.page-title .more, .page-title .less').toggleClass('open');
				$('.category-desc').toggleClass('open');
			});

		},

		/**
		 * popups
		 *
		 * Called from init
		 *
		 * @param	N/A
		 * @return	N/A
		 */
		popups : function() {

			$('.close-popup').click(function() {
				$(this).closest('.popup').removeClass('show');
			});

		},

		/**
		 * advanced_search
		 *
		 * Called from init
		 *
		 * @param	N/A
		 * @return	N/A
		 */
		advanced_search : function() {

			// open advanced search in case of advanced search field is set
			KULAM_general.maybe_open_advanced_search();

			// open advanced search
			$('.advanced-search-btn').click(function() {
				KULAM_general.advance_search_open($(this));
			});

			// close advanced search
			$('.advanced-search .instructions span').click(function() {
				KULAM_general.advance_search_close($(this));
			});

			// auto complete
			KULAM_general.advanced_search_auto_complete();

		},

		/**
		 * maybe_open_advanced_search
		 *
		 * Called from advanced_search
		 *
		 * @param	N/A
		 * @return	N/A
		 */
		maybe_open_advanced_search : function() {

			// variables
			var post_format = KULAM_general.get_url_param('post_format'),
				pt = KULAM_general.get_url_param('pt'),
				activity_type = KULAM_general.get_url_param('activity_type'),
				cat = KULAM_general.get_url_param('cat'),
				hide_as = KULAM_general.get_url_param('hide_as');

			if (!(hide_as && hide_as == 1) && (post_format && post_format != 'N/A' || pt && pt != 'N/A' || activity_type && activity_type != 'N/A' || cat && cat != 'N/A')) {
				KULAM_general.advance_search_open_all();
			}

		},

		/**
		 * advance_search_open_all
		 *
		 * Called from maybe_open_advanced_search
		 *
		 * @param	N/A
		 * @return	N/A
		 */
		advance_search_open_all : function() {

			// variables
			var obj = $('.advanced-search-btn');

			obj.each(function() {
				KULAM_general.advance_search_open($(this));
			});

		},

		/**
		 * advance_search_open
		 *
		 * Called from advanced_search and advance_search_open_all
		 *
		 * @param	obj (object)
		 * @return	N/A
		 */
		advance_search_open : function(obj) {

			// variables
			var searchForm = obj.closest('.search-header'),
				inputText = searchForm.find('.menu-search-input-text'),
				inputTextInput = inputText.find('input[type="text"]'),
				inputTextInputTmpPlaceholder = inputTextInput.data('alternate-placeholder'),
				advancedFields = searchForm.find('.advanced-search-fields');

			inputTextInput.data('alternate-placeholder', inputTextInput.attr('placeholder'));
			inputTextInput.attr('placeholder', inputTextInputTmpPlaceholder);

			searchForm.toggleClass('advanced');

			advancedFields.after(inputText);

		},

		/**
		 * advance_search_close
		 *
		 * Called from advanced_search
		 *
		 * @param	obj (object)
		 * @return	N/A
		 */
		advance_search_close : function(obj) {

			// variables
			var searchForm = obj.closest('.search-header'),
				inputText = searchForm.find('.menu-search-input-text'),
				inputTextInput = inputText.find('input[type="text"]'),
				inputTextInputTmpPlaceholder = inputTextInput.data('alternate-placeholder'),
				advancedFields = searchForm.find('.advanced-search-fields'),
				advancedInputFields = advancedFields.find('input, select');

			inputTextInput.data('alternate-placeholder', inputTextInput.attr('placeholder'));
			inputTextInput.attr('placeholder', inputTextInputTmpPlaceholder);

			searchForm.toggleClass('advanced');

			advancedInputFields.val('');
			searchForm.find('form').prepend(inputText);

		},

		/**
		 * advanced_search_auto_complete
		 *
		 * Called from advanced_search
		 *
		 * @param	N/A
		 * @return	N/A
		 */
		advanced_search_auto_complete : function() {

			// variables
			var searchForm = $('.search-header'),
				inputText = searchForm.find('.auto-complete-input');

			if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
				inputText.off('menufocus hover mouseover');
			}

			inputText.each(function() {
				// variables
				field = $(this);
				options = field.data('options');

				field.autoComplete({
					minChars: 1,
					cache: false,
					source: function(term, suggest) {

						term = term.toLowerCase();
						var choices = options;
						var matches = [];

						for (i=0; i<choices.length; i++) {
							if (~choices[i]['title'].toLowerCase().indexOf(term))
								matches.push(JSON.stringify(choices[i]));
						}

						suggest(matches);

					},
					renderItem: function(item, search) {
						item = JSON.parse(item);

						search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
						var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");

						return '<div class="autocomplete-suggestion" data-val="' + item['title'] + '" data-id="' + item['id'] + '">' + item['title'].replace(re, "<b>$1</b>") + '</div>';
					},
					onSelect: function(e, term, item) {
						KULAM_general.after_auto_complete(field.data('auto-complete-output'), item.data('id'));
					}
				});

				// check auto complete field on value change event
				field.on('change paste keyup', function() {
					var id = KULAM_general.auto_complete_get_id_by_title($(this));
					KULAM_general.after_auto_complete($(this).data('auto-complete-output'), id);
				});

			});

		},

		/**
		 * auto_complete_get_id_by_title
		 *
		 * Called from advanced_search_auto_complete
		 *
		 * @param	input (string) input field to be verified
		 * @return	(int)
		 */
		auto_complete_get_id_by_title : function(input) {

			// variables
			value = input.val().toLowerCase();
			options = input.data('options');
			option_id = 0;

			$.each(options, function(index, option) {
				// variables
				var title = option['title'].toLowerCase();

				if (value == title) {
					option_id = option['id'];

					return false;
				}
			});

			// return
			return option_id;

		},

		/**
		 * after_auto_complete
		 *
		 * Called from advanced_search_auto_complete
		 *
		 * @param	input (string) input field to be updated
		 * @param	value (string) value to be updated
		 * @return	N/A
		 */
		after_auto_complete : function(input, value) {

			// variables
			var inputText = $('.' + input);

			inputText.val(value);

		},

		/**
		 * get_url_param
		 *
		 * Called from maybe_open_advanced_search
		 *
		 * @param	name (string)
		 * @return	(string)
		 */
		get_url_param : function(name) {

			var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);

			if (results == null) {
				return null;
			}

			// return
			return decodeURI(results[1]) || 'N/A';

		},

		/**
		 * bootstrap_modal
		 *
		 * Called from init
		 *
		 * @param	N/A
		 * @return	N/A
		 */
		bootstrap_modal : function() {

			// triggered when modal is about to be shown
			$('#modal-login').on('show.bs.modal', function(e) {
				// get data attributes of the clicked element
				var redirect = $(e.relatedTarget).data('redirect'),
					showPreText = $(e.relatedTarget).data('show-pre-text');

				// populate the textbox
				$('#modal-login').find('input[name="redirectlog"]').val(redirect);
				$('#modal-registration').find('input[name="redirect"]').val(redirect);
				$('#modal-login, #modal-registration').find('button').data('redirect', redirect);
				$('#modal-login, #modal-registration').find('button').data('show-pre-text', showPreText);

				// expose pre-text if redirect to my siddur
				if (showPreText) {
					$('#modal-login').find('.pre-text').show();
				}
				else {
					$('#modal-login').find('.pre-text').hide();
				}
			});

			$('#modal-login, #modal-registration').on('shown.bs.modal', function(e) {
				$(this).data('bs.modal').$backdrop.css('background-color', '#000');
			});

			// modify search modal background
			$('#modal-search').on('shown.bs.modal', function(e) {
				$(this).data('bs.modal').$backdrop.css('background-color', '#FFF');
			});

		},

		/**
		 * my_siddur
		 *
		 * Called from init
		 *
		 * @param	N/A
		 * @return	N/A
		 */
		my_siddur : function() {

			// add to / remove from siddur
			$('.siddur-toggle-button').on('click', function(event) {

				event.preventDefault();

				KULAM_general.my_siddur_siddur_toggle($(this));

			});

			// prepare posts to add to siddur folders
			KULAM_general.my_siddur_prepare_posts_to_add_to_folders();

			// prevent default on form submit
			$('.add-to-folder-form').submit(function(e) {
				e.preventDefault();
			});

			// add to siddur folders
			$('.add-to-folder').on('click', function() {

				KULAM_general.my_siddur_add_to_folders($(this));

			});

			// remove from siddur folder
			$('.folders-assignment.template-siddur-folder a').on('click', function() {

				KULAM_general.my_siddur_remove_from_folder($(this));

			});

			// siddur folder settings
			KULAM_general.my_siddur_folder_settings();

		},

		/**
		 * my_siddur_siddur_toggle
		 *
		 * Called from my_siddur
		 *
		 * @param	btn (object)
		 * @return	N/A
		 */
		my_siddur_siddur_toggle : function(btn) {

			// expose loader
			$('.loader').show();

			// variables
			var contentGrid = btn.parent().hasClass('wrap-heart');

			if (!contentGrid) {
				// single post
				text = btn.text();
				toggleText = btn.data('toggle-text');
			}

			var action = btn.data('action'),
				toggleAction = btn.data('toggle-action');

			var data = {
				action: action,
				user_id: ajaxdata.user_id,
				post_id: contentGrid ? btn.data('post-id') : ajaxdata.post_id,
				security: ajaxdata.ajax_nonce
			};

			$.post(ajaxdata.ajaxurl, data, function(response) {

				if (response == 1) {
					if (!contentGrid) {
						// single post
						btn.text(toggleText).data('toggle-text', text);
					}
					else {
						// grid
						btn.find('i').toggleClass('fa-heart').toggleClass('fa-heart-o');
					}

					btn.data('action', toggleAction).data('toggle-action', action);

					if ($('body').hasClass('page-template-template-siddur') || $('body').hasClass('page-template-template-siddur-folder')) {
						// in the siddur or in a folder - action must be 'remove_from_siddur'
						// remove post from grid
						btn.closest('.grid-item').remove();
					}
				}

			});

			// hide loader
			$('.loader').hide();

			// return
			return false;

		},

		/**
		 * my_siddur_prepare_posts_to_add_to_folders
		 *
		 * Called from my_siddur
		 *
		 * @param	N/A
		 * @return	N/A
		 *
		 * @todo	This function prepare array of post IDS to add, rather than a single post ID.
		 * 			In order to activate this option, closing popup form should not clear post_ids_field's post-ids data attribute 	
		 */
		my_siddur_prepare_posts_to_add_to_folders : function() {

			// variables
			var form = $('.add-to-folder-form'),
				post_ids_field = form.find('input.post-ids');

			// prepare a single post to add and open the popup form
			$('.folders-assignment.template-siddur a').click(function() {
				// variables
				var post_ids = post_ids_field.data('post-ids'),
					post_ids_arr = post_ids ? JSON.parse(post_ids) : [],
					post_id = parseInt($(this).attr('id').slice(24));

				if ($.inArray(post_id, post_ids_arr) == -1) {
					post_ids_arr.push(post_id);
					post_ids_field.data('post-ids', JSON.stringify(post_ids_arr));
				}

				// expose form
				form.parent().addClass('show');
			});

			// close the popup form
			$('.close-popup-folders').click(function() {
				// clear post_ids
				post_ids_field.data('post-ids', '');

				// hide form
				form.parent().removeClass('show');
			});

		},

		/**
		 * my_siddur_add_to_folders
		 *
		 * Called from my_siddur
		 *
		 * @param	btn (object)
		 * @return	N/A
		 */
		my_siddur_add_to_folders : function(btn) {

			// expose loader
			$('.loader').show();

			// variables
			var form = $('.add-to-folder-form'),
				post_ids_field = form.find('input.post-ids'),
				post_ids = post_ids_field.data('post-ids'),
				post_ids_arr = post_ids ? JSON.parse(post_ids) : [],
				folders = form.find('input:radio[name=option]:checked').val(),
				folders_arr = folders ? [form.find('input:radio[name=option]:checked').val()] : [];

			if (!post_ids || !folders) {
				// hide loader
				$('.loader').hide();

				// return
				return false;
			}

			var data = {
				action: 'add_to_folders',
				user_id: ajaxdata.user_id,
				post_ids: post_ids,
				folders: JSON.stringify(folders_arr),
				security: ajaxdata.ajax_nonce
			};

			$.post(ajaxdata.ajaxurl, data, function(response) {

				if (response == 1) {
					// clear post_ids
					post_ids_field.data('post-ids', '');

					// remove posts from grid
					$('.grid-item').each(function() {
						// variables
						var post_id = parseInt($(this).attr('id').slice(5));

						if ($.inArray(post_id, post_ids_arr) > -1) {
							$(this).remove();
						}
					});

					// hide form
					form.parent().removeClass('show');
				}

			});

			// hide loader
			$('.loader').hide();

			// return
			return false;

		},

		/**
		 * my_siddur_remove_from_folder
		 *
		 * Called from my_siddur
		 *
		 * @param	btn (object)
		 * @return	N/A
		 */
		my_siddur_remove_from_folder : function(btn) {

			// expose loader
			$('.loader').show();

			var data = {
				action: 'remove_from_folder',
				user_id: ajaxdata.user_id,
				post_id: parseInt(btn.attr('id').slice(24)),
				folder: $('.folder-wrap > .entry-title').text(),
				security: ajaxdata.ajax_nonce
			};

			$.post(ajaxdata.ajaxurl, data, function(response) {

				if (response == 1) {
					// remove post from grid
					btn.closest('.grid-item').remove();
				}

			});

			// hide loader
			$('.loader').hide();

			// return
			return false;

		},

		/**
		 * my_siddur_folder_settings
		 *
		 * Called from my_siddur
		 *
		 * @param	btn (object)
		 * @return	N/A
		 */
		my_siddur_folder_settings : function() {

			// initialize siddur folder settings
			KULAM_general.my_siddur_folder_settings_init();

			// save folder settings
			$('.save-settings').on('click', function() {

				KULAM_general.my_siddur_save_folder_settings($(this));

			});

			// make folder public before share
			KULAM_general.my_siddur_make_folder_public_before_share();

		},

		/**
		 * my_siddur_folder_settings_init
		 *
		 * Called from my_siddur_folder_settings
		 *
		 * @param	btn (object)
		 * @return	N/A
		 */
		my_siddur_folder_settings_init : function() {

			// variables
			var popup = $('#popup-settings');

			// check public value and open the popup form
			$('.settings').click(function () {
				// variables
				var folder = popup.find('#name-folder-hide').val();

				var data = {
					action: 'check_public_folder',
					user_id: ajaxdata.user_id,
					folder: folder,
					security: ajaxdata.ajax_nonce
				};

				$.post(ajaxdata.ajaxurl, data, function(response) {

					if (response == 'on') {
						$('#is-public').attr('checked', true);
					}
					else if (response == 'off') {
						$('#is-public').attr('checked', false);
					}

					// expose form
					popup.addClass('show');

				});
			});

		},

		/**
		 * my_siddur_save_folder_settings
		 *
		 * Called from my_siddur_folder_settings
		 *
		 * @param	btn (object)
		 * @return	N/A
		 */
		my_siddur_save_folder_settings : function(btn) {

			// expose loader
			$('.loader').show();

			// variables
			var popup = $('#popup-settings'),
				folder = popup.find('#name-folder-hide').val(),
				folder_new = popup.find('#name-new-folder').val(),
				delete_folder = popup.find('#del').is(':checked'),
				public_folder = popup.find('#is-public').is(':checked');

			var data = {
				action: 'save_folder_settings',
				user_id: ajaxdata.user_id,
				folder: folder,
				folder_new: folder_new,
				delete_folder: delete_folder,
				public_folder: public_folder,
				security: ajaxdata.ajax_nonce
			};

			$.post(ajaxdata.ajaxurl, data, function(response) {

				response = JSON.parse(response);

				if (response[1]) {
					// folder name updated
					folder_new = response[1];

					// update folder name in url
					window.history.replaceState('', '', KULAM_general.update_url_parameter(window.location.href, 'folder', folder_new));

					// update folder name in page elements
					popup.find('#name-folder-hide').val(folder_new);
					popup.find('#name-new-folder').val(folder_new);
					$('.folder-wrap > .entry-title').text(folder_new);
				}
				else if (response[2]) {
					// folder deleted
					window.location = window.location.href.split("-folder?")[0];
				}

				// hide form
				popup.removeClass('show');

			});

			// hide loader
			$('.loader').hide();

			// return
			return false;

		},

		/**
		 * my_siddur_make_folder_public_before_share
		 *
		 * Called from my_siddur_folder_settings
		 *
		 * @param	N/A
		 * @return	N/A
		 */
		my_siddur_make_folder_public_before_share : function() {

			$('.save-sharing-choosing').click(function() {

				// variables
				var popup = $('.share-popup'),
					folder = $('#name-folder-hide').val(),
					choice = popup.find('.choosing-items #public-folder').prop('checked');

				if (choice) {

					// expose loader
					$('.loader').show();

					var data = {
						action: 'save_folder_settings',
						user_id: ajaxdata.user_id,
						folder: folder,
						folder_new: folder,
						delete_folder: false,
						public_folder: true,
						security: ajaxdata.ajax_nonce
					};

					$.post(ajaxdata.ajaxurl, data, function(response) {

						response = JSON.parse(response);

						if (response[3]) {
							alert('Your folder is public');
						}

					});

				}

				// hide form
				popup.removeClass('show');

				// hide loader
				$('.loader').hide();

			});

			// return
			return false;

		},

		/**
		 * update_url_parameter
		 *
		 * Add / Update a key-value pair in the URL query parameters
		 *
		 * @param	uri (string)
		 * @param	key (string)
		 * @param	value (string)
		 * @return	(string)
		 */
		update_url_parameter : function(uri, key, value) {

			// remove the hash part before operating on the uri
			var i = uri.indexOf('#'),
				hash = i === -1 ? ''  : uri.substr(i),
				uri = i === -1 ? uri : uri.substr(0, i);

			var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i"),
				separator = uri.indexOf('?') !== -1 ? "&" : "?";

			if (uri.match(re)) {
				uri = uri.replace(re, '$1' + key + "=" + value + '$2');
			}
			else {
				uri = uri + separator + key + "=" + value;
			}

			// return
			return uri + hash;

		},

		/**
		 * post_types_posts_grid
		 *
		 * Called from init
		 *
		 * @param	N/A
		 * @return	N/A
		 */
		post_types_posts_grid : function() {

			$('.post-type-posts-grid .post-type-sub-title').on('click', function(event) {

				// variables
				var grid = $(this).closest('.post-type-posts-grid'),
					more_txt = grid.find('.post-type-sub-title.more');

				// toggle grid class
				grid.toggleClass('open');

				// toggle "Click for more" text
				tmp_txt = more_txt.data('toggle');
				more_txt.data('toggle', more_txt.html());
				more_txt.html(tmp_txt);

			});

		},

		/**
		 * a11y_icon_top
		 *
		 * Called from loaded
		 *
		 * @param	N/A
		 * @return	N/A
		 */
		a11y_icon_top : function() {

			// variables
			var header = $('header#header'),
				icon = $('#pojo-a11y-toolbar');

			icon.attr('style',
				'top:' + header.outerHeight(true) + 'px !important;' +
				'visibility: visible;'
			);

		},

		/**
		 * breakpoint_refreshValue
		 *
		 * Set window breakpoint values
		 * Called from loaded/alignments
		 *
		 * @param	N/A
		 * @return	N/A
		 */
		breakpoint_refreshValue : function() {

			var new_breakpoint = window.getComputedStyle(
				document.querySelector('body'), ':before'
			).getPropertyValue('content').replace(/\"/g, '').replace(/\'/g, '');

			KULAM_general.params.prev_breakpoint = KULAM_general.params.breakpoint;
			KULAM_general.params.breakpoint = new_breakpoint;

		},

		/**
		 * loaded
		 *
		 * Called by $(window).load event
		 *
		 * @param	N/A
		 * @return	N/A
		 */
		loaded : function() {

			KULAM_general.params.window_width = $(window).width();
			$(window).resize(function() {
				if ( KULAM_general.params.window_width != $(window).width() ) {
					KULAM_general.alignments();
					KULAM_general.params.window_width = $(window).width();
				}
			});

			KULAM_general.alignments();

		},

		/**
		 * alignments
		 *
		 * Align components after window resize event
		 *
		 * @param	N/A
		 * @return	N/A
		 */
		alignments : function() {
			
			// set window breakpoint values
			KULAM_general.breakpoint_refreshValue();

			// accessibility icon auto position
			KULAM_general.a11y_icon_top();

		}

	};

// make it safe to use console.log always
(function(a){function b(){}for(var c="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),d;!!(d=c.pop());){a[d]=a[d]||b;}})
(function(){try{console.log();return window.console;}catch(a){return (window.console={});}}());

$(KULAM_general.init);
$(window).load(KULAM_general.loaded);


jQuery(document).ready(function ($) {

	$('.submit_reg').click(function () {
		$('.loader').show();
		var email = $('#uemail').val();
		if (!validateEmail(email)) {
			$('.loader').hide();
			alert("email is not valid");
			$('#uemail').css("border-color", "red");
			return false;
		}
		var data = {
			action: 'create_account',
			uemail: email,
			upass: $('#upass').val(),
			uname: $('#uname').val(),
			prefix: $('#prefix').val(),
			captcha: $('#captcha').val(),
			security: ajaxdata.ajax_nonce
		};
		jQuery.post(ajaxdata.ajaxurl, data, function (response) {
			if (response === "Success0") {
				if ($('#redirectlog').val() == '#') {
					window.location.reload(false);
				}
				else if ($('#langlog').val()) {
					var home_url = document.location.origin;
					var loc = home_url.concat("/en").concat($('#redirectlog').val());
					window.location.href = loc;
				}
				else window.location.href = $('#redirectlog').val();
			}
			else {
				response = response.substring(0, response.length - 1);
				$('.loader').hide();
				alert(response);
			}
		});
		return false;
	});

	function validateEmail(email) {
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}

	$('.submit_log').click(function () {
		$('.loader').show();
		var data = {
			action: 'custom_login',
			upasslog: $('#upasslog').val(),
			unamelog: $('#unamelog').val(),
			security: ajaxdata.ajax_nonce
		};
		jQuery.post(ajaxdata.ajaxurl, data, function (response) {
			if (response === "Success0") {
				if ($('#redirectlog').val() == '#') {
					window.location.reload(false);
				}
				else if ($('#langlog').val()) {
					var home_url = document.location.origin;
					var loc = home_url.concat("/en").concat($('#redirectlog').val());
					window.location.href = loc;
				}
				else window.location.href = $('#redirectlog').val();
			}
			else {
				response = response.substring(0, response.length - 1);
				$('.loader').hide();
				alert(response);
			}
		});
		return false;
	});
	$('.add-new-folder').click(function () {
		var popup = document.getElementById("new-fold");
		popup.classList.toggle("show");
	});
	$('.add-save-folder').click(function () {
		$('.loader').show();
		var data = {
			action: "add-folder",
			nameFolder: $('#name-folder').val(),
			security: ajaxdata.ajax_nonce
		};
		jQuery.post(ajaxdata.ajaxurl, data, function (response) {
			if (response === "Success0") {
				location.reload();
			}
			else if (response === "no0") {
				alert("Please select a different name for the folder, this folder exists");
				$('loader').hide();
				location.reload();
			}
			else {
				response = response.substring(0, response.length - 1);
				$('loader').hide();
			}
		});
	});

	//sharing section
	$('#facebook-share').click(function(){
		var data={
			action: 'check_public_folder',
			user_id: ajaxdata.user_id,
			folder: $('#name-folder-hide').val(),
			social: 'social',
			security: ajaxdata.ajax_nonce
		};
		jQuery.post(ajaxdata.ajaxurl,data,function(response){
		  if(response=="-1")
		  {
			$('input[name=public-folder]').attr('checked',false);
			$('.share-popup').addClass('show');
		  }
		  else
		  {
			var home_url = document.location.origin;
			response = home_url.concat(response);
			var refFacbook="https://www.facebook.com/sharer.php?u=";
			var ref = refFacbook.concat(response);
			$(location).attr('href',ref);
		  }
		});
	});
	$('#twitter-share').click(function(){
		var data={
			action: 'check_public_folder',
			user_id: ajaxdata.user_id,
			folder: $('#name-folder-hide').val(),
			social: 'social',
			security: ajaxdata.ajax_nonce
		};
		jQuery.post(ajaxdata.ajaxurl,data,function(response){
		  if(response=="-1")
		  {
			$('input[name=public-folder]').attr('checked',false);
			$('.share-popup').addClass('show');
		  }
		  else
		  {
			var home_url = document.location.origin;
			response = home_url.concat(response);
		   var refTwitter="https://twitter.com/intent/tweet?text= "
		   var ref= refTwitter.concat(response);
		   $(location).attr('href',ref);
		  }
	});
  });
  $('#whatsapp-share').click(function(){
	var data={
		action: 'check_public_folder',
		user_id: ajaxdata.user_id,
		folder: $('#name-folder-hide').val(),
		social: 'social',
		security: ajaxdata.ajax_nonce
	};
	jQuery.post(ajaxdata.ajaxurl,data,function(response){
	  if(response=="-1")
	  {
		$('input[name=public-folder]').attr('checked',false);
		$('.share-popup').addClass('show');
	  }
	  else
	  {
		var home_url = document.location.origin;
		response = home_url.concat(response);
		var refTwatsApp="whatsapp://send?text=";
		var ref= refTwatsApp.concat(response);
		$(location).attr('href',ref);
	  }
});
});
$('#telegram-share').click(function(){
	var data={
		action: 'check_public_folder',
		user_id: ajaxdata.user_id,
		folder: $('#name-folder-hide').val(),
		social: 'social',
		security: ajaxdata.ajax_nonce
	};
	jQuery.post(ajaxdata.ajaxurl,data,function(response){
	  if(response=="-1")
	  {
		$('input[name=public-folder]').attr('checked',false);
		$('.share-popup').addClass('show');
	  }
	  else
	  {
		var home_url = document.location.origin;
		response = home_url.concat(response);
		var refTelegram="tg://msg?text=";
		var ref= refTelegram.concat(response);
		$(location).attr('href',ref);
	  }
});
});
$('#clipboard-share-single').click(function(){
	document.getElementById("link_to_copy").value = window.location.href;
	$('#popup-link-copy').addClass('show');
})
$('#clipboard-share').click(function(){
	var data={
		action: 'check_public_folder',
		user_id: ajaxdata.user_id,
		folder: $('#name-folder-hide').val(),
		clipboard: 'clipboard',
		security: ajaxdata.ajax_nonce
	};

	jQuery.post(ajaxdata.ajaxurl,data,function(response){
	  if(response=="-1")
	  {
		$('input[name=public-folder]').attr('checked',false);
		$('.share-popup').addClass('show');
	  }
	  else
	  {
		var home_url = document.location.origin;
		response = home_url.concat(response);
		document.getElementById("link_to_copy").value = response;
		$('#popup-link-copy').addClass('show');
	  }
});
});

	$('.save-rating').click(function (event) {
		$('.loader').show();
		var a = event.target.parentElement.id;
		var postID = a.slice(5);
		var data = {
			action: 'save_rating_post',
			postID: postID,
			val1: $('#optionID1').val(),
			val2: $('#optionID2').val(),
			val3: $('#optionID3').val(),
			val4: $('#optionID4').val(),
			val5: $('#optionID5').val(),
			security: ajaxdata.ajax_nonce
		}
		jQuery.post(ajaxdata.ajaxurl, data, function (response) {
			if (response === "Success0") {
				$('.loader').hide();
				$("#popUp").show();
				setTimeout(function () {
					$("#popUp").hide();
				}, 2000);
			}
			else {
				$('.loader').hide();
				alert(response);
			}
		});
	});

	$('#send').click(function () {
		document.getElementById("idto").value = "";
		var popup = document.getElementById("popup-to");
		popup.classList.toggle("show");
	});
	$('#sendTo').click(function () {
	   var Semail= $('.to').val();
	   var emails=Semail.split(' ');
			$('.loader').show();
			var popup = document.getElementById("popup-to");
			popup.classList.toggle("show");
			var data = {
				action: "folder_to_send",
				f: $('#name-new-folder').val(),
				to: emails,
				security: ajaxdata.ajax_nonce
			};
		
			jQuery.post(ajaxdata.ajaxurl, data, function (response) {
				var lang = document.documentElement.lang;
				if (response === "Success0") {
					$('.loader').hide();

					if (lang == "he-IL")
						alert("האימייל שלך נשלח בהצלחה");
					else
						alert("your mail has been sent");

				}
				else {
					$('.loader').hide();
					if (lang == "he-IL")
						alert("נכשל");
					else
						alert("faild");
				}
			});
	});
	function validateEmail(sEmail) {
		var filter = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		if (filter.test(sEmail)) {
			return true;
		}
		else {
			return false;
		}
	}
	$('#send-single-post').click(function () {
		var Semail= $('.to').val();
		var emails=Semail.split(' ');
			 $('.loader').show();
			 var popup = document.getElementById("popup-to");
			 popup.classList.toggle("show");
			 var data = {
				 action: "post_to_send",
				 post: $('#comment_post_ID').val(),
				 to: emails,
				 security: ajaxdata.ajax_nonce
			 };
		 
			 jQuery.post(ajaxdata.ajaxurl, data, function (response) {
				 var lang = document.documentElement.lang;
				 if (response === "Success0") {
					 $('.loader').hide();
 
					 if (lang == "he-IL")
						 alert("האימייל שלך נשלח בהצלחה");
					 else
						 alert("your mail has been sent");
 
				 }
				 else {
					 $('.loader').hide();
					 if (lang == "he-IL")
						 alert("נכשל");
					 else
						 alert("faild");
				 }
			 });
	 });
	$('i.fa.fa-arrow-circle-o-left.my-siddur').click(function () {
		var home_url = document.location.origin;
		var loc = home_url.concat("/my-siddur");
		window.location.href = loc;
	});
	$('i.fa.fa-arrow-circle-o-right.my-siddur').click(function () {
		var home_url = document.location.origin;
		var loc = home_url.concat("/en/my-siddur");
		window.location.href = loc;
	});
	$(window).scroll(function () {
		var sc = $(window).scrollTop();
		if (sc > 50) {
			$(".container").addClass("big");
		} else {
			$(".container").removeClass("big");
		}
	});

	$('#is-public').click(function () {
		if ($(this).is(':checked')) {
			$(".popup-form-setting").addClass("for-public");
		} else {
			$(".popup-form-setting").removeClass("for-public");
		}
	});
	$('[data-toggle="tooltip-mail"]').tooltip();
});