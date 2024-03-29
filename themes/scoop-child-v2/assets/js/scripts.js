var $ = jQuery,
	KULAM_general = {

		/**
		 * params
		 */
		params : {

			// gallery params
			galleries               : {},
			images_columns          : 4,
			images_more_interval    : 8,

			window_width            : 0,        // client window width - used to maintain window resize events (int)
			breakpoint              : '',       // CSS media query breakpoint (int)
			prev_breakpoint         : '',       // previous media query breakpoint (int)
			timeout                 : 400       // general timeout (int)

		},

		/**
		 * hexToRgbA
		 *
		 * @param   hex (string)
		 * @return  (string)
		 */
		hexToRgbA : function(hex, opacity) {

			// variables
			var c;

			if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
				c = hex.substring(1).split('');
				if(c.length== 3){
					c= [c[0], c[0], c[1], c[1], c[2], c[2]];
				}

				c= '0x'+c.join('');

				// return
				return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+','+opacity+')';
			}

			throw new Error('Bad Hex');

		},

		/**
		 * init
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		init : function() {

			// jQuery extentions
			$.fn.setAllToMaxHeight = function() {
				return this.height( Math.max.apply(this, $.map(this, function(e) { return $(e).height() })) );
			}

			// scroll top button event
			KULAM_general.scroll_top();

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

			// category filters menu
			KULAM_general.category_filters_menu();

			// category filters
			KULAM_general.category_filter_events();

			// banner
			KULAM_general.banner();

			// Q&A
			KULAM_general.qna();

			// google map
			KULAM_general.google_map();

			// slideshow
			KULAM_general.slideshow();

			// galleries
			KULAM_general.galleries();

			// hmembership
			// KULAM_general.hmembership();

			// my siddur
			KULAM_general.my_siddur();

			// post types posts grid
			KULAM_general.post_types_posts_grid();

		},

		/**
		 * scroll_top
		 *
		 * Called from init
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		scroll_top : function() {

			// variables
			var icon = $('.scroll-top span');

			icon.on('click', function() {
				$('html, body').animate({ scrollTop: 0 }, 500);
			});

		},

		/**
		 * a11y_icon_direction
		 *
		 * Called from init
		 *
		 * @param   N/A
		 * @return  N/A
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
		 * @param   N/A
		 * @return  N/A
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
		 * @param   N/A
		 * @return  N/A
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
		 * @param   N/A
		 * @return  N/A
		 */
		advanced_search : function() {

			// advanced search categories tree styling
			KULAM_general.advanced_search_categories();

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
			//KULAM_general.advanced_search_auto_complete();

		},

		/**
		 * advanced_search_categories
		 *
		 * Called from advanced_search
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		advanced_search_categories : function() {

			// variables
			var categories_tree_sections = $('.menu-search-input-category');

			if (categories_tree_sections.length) {
				categories_tree_sections.each(function(i, tree) {

					var tree_lis = $(tree).find('.checkbox-list li');

					tree_lis.each(function(j, li) {
						if ($(li).children('.children').length) {
							$(li).children('label').children('span').css('text-decoration', 'underline');
						}
					});

				});
			}

		},

		/**
		 * maybe_open_advanced_search
		 *
		 * Called from advanced_search
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		maybe_open_advanced_search : function() {

			// variables
			var query_string = ajaxdata.query_string;

			if (!(typeof query_string.hide_as !== 'undefined' && query_string.hide_as == 1) && typeof query_string.filters !== 'undefined') {
				KULAM_general.advance_search_open_all();
			}

		},

		/**
		 * advance_search_open_all
		 *
		 * Called from maybe_open_advanced_search
		 *
		 * @param   N/A
		 * @return  N/A
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
		 * @param   obj (object)
		 * @return  N/A
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
		 * @param   obj (object)
		 * @return  N/A
		 */
		advance_search_close : function(obj) {

			// variables
			var searchForm = obj.closest('.search-header'),
				inputText = searchForm.find('.menu-search-input-text'),
				inputTextInput = inputText.find('input[type="text"]'),
				inputTextInputTmpPlaceholder = inputTextInput.data('alternate-placeholder'),
				advancedFields = searchForm.find('.advanced-search-fields'),
				advancedInputFields = advancedFields.find('input');

			inputTextInput.data('alternate-placeholder', inputTextInput.attr('placeholder'));
			inputTextInput.attr('placeholder', inputTextInputTmpPlaceholder);

			searchForm.toggleClass('advanced');

			advancedInputFields.prop('checked', false);
			searchForm.find('form').prepend(inputText);

		},

		/**
		 * advanced_search_auto_complete
		 *
		 * Called from advanced_search
		 *
		 * @param   N/A
		 * @return  N/A
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
		 * @param   input (string) input field to be verified
		 * @return  (int)
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
		 * @param   input (string) input field to be updated
		 * @param   value (string) value to be updated
		 * @return  N/A
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
		 * @param   name (string)
		 * @return  (string)
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
		 * @param   N/A
		 * @return  N/A
		 */
		bootstrap_modal : function() {

			// menu modal
			KULAM_general.menu_modal();

			// search modal
			KULAM_general.search_modal();

			// login modal
			KULAM_general.login_modal();

			// category filter modal
			KULAM_general.category_filter_modal();

		},

		/**
		 * menu_modal
		 *
		 * Called from bootstrap_modal
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		menu_modal : function() {

			// menu button
			$('.nav-login li.menu').on('click', function() {
				// hide icon
				$(this).addClass('close');
			});

			// triggered when modal is about to be hidden
			$('#modal-menu').on('hide.bs.modal', function() {
				// vars
				var li = $('.nav-login li.menu');

				// show icon
				li.removeClass('close');
			});

		},

		/**
		 * search_modal
		 *
		 * Called from bootstrap_modal
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		search_modal : function() {

			// menu button
			$('.nav-login li.search').on('click', function() {
				// hide icon
				$(this).addClass('close');
			});

			// triggered when modal is about to be hidden
			$('#modal-search').on('hide.bs.modal', function() {
				// vars
				var li = $('.nav-login li.search');

				// show icon
				li.removeClass('close');
			});

		},

		/**
		 * login_modal
		 *
		 * Called from bootstrap_modal
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		login_modal : function() {

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

		},

		/**
		 * category_filter_modal
		 *
		 * Called from bootstrap_modal
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		category_filter_modal : function() {

			// triggered when modal is about to be shown
			$('#modal-category-filter').on('show.bs.modal', function(e) {
				// get data attributes of the clicked element
				var filter_name = $(e.relatedTarget).text(),
					taxonomy = $(e.relatedTarget).parent().data('tax'),
					filters = $('#modal-category-filter').find('.modal-body').children(),
					current_filter = $('#modal-category-filter').find('.modal-body').children('ul[data-tax="' + taxonomy + '"]'),
					no_terms_msg = $('#modal-category-filter').find('.no-terms'),
					apply_filters = $('#modal-category-filter').find('.apply-filters');

				// populate the filter title
				$('#modal-category-filter').find('.filter-title').text(filter_name);

				// hide filters
				filters.hide();
				no_terms_msg.hide();

				// expose required filter
				if (current_filter.children().length) {
					current_filter.show();
					apply_filters.show();
				} else {
					// no terms exist
					no_terms_msg.show();
					apply_filters.hide();
				}
			});

		},

		/**
		 * category_filters_menu
		 *
		 * Called from init
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		category_filters_menu : function() {

			// vars
			var btn = $('.filters-menu-toggle'),
				filters = btn.next(),
				lis = filters.children();

			btn.on('click', function() {
				filters.toggleClass('active');

				// close mobile menu
				lis.on('click', function() {
					filters.removeClass('active');
				});
			});

		},

		/**
		 * category_filter_events
		 *
		 * Called from init
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		category_filter_events : function() {

			// vars
			var lis = $('#modal-category-filter').find('.modal-body').children().find('li'),
				reset = $('.reset-filters'),
				apply_filters = $('#modal-category-filter').find('.apply-filters');

			if (lis.length) {
				// toggle category filter lists
				lis.find('.expand').on('click', function(e) {
					// vars
					var li = $(this).closest('li');

					e.preventDefault();
					li.toggleClass('open');
				});

				// check/uncheck li
				lis.find('input').on('click', function(e) {
					// vars
					var li = $(this).closest('li'),
						taxonomy = li.closest('.checkbox-list').data('tax'),
						term_id = li.data('id').substring(5),
						value = $(this).next().text(),
						state = $(this).prop('checked');

					// updates filters state and posts displayed
					KULAM_general.category_posts_update(taxonomy, term_id, value, state);

					// bind uncheck event on checked filters
					if (state) {
						var checked_filter = $('.checked-filters').find('li[data-id="term_' + term_id + '"]');

						// uncheck li
						checked_filter.find('.remove').on('click', function() {
							// updates filters state and posts displayed
							KULAM_general.category_posts_update(taxonomy, term_id, value, false);
						});
					}
				});
			}

			if (reset.length) {
				reset.on('click', function() {
					KULAM_general.reset_category_filters();
				});
			}

			if (apply_filters.length) {
				apply_filters.on('click', function() {
					$('#modal-category-filter').modal('hide');
				});
			}

		},

		/**
		 * category_posts_update
		 *
		 * updates filters state and posts displayed
		 *
		 * @param   taxonomy (string)
		 * @param   term_id (int)
		 * @param   value (string)
		 * @param	state (bool)
		 * @return  N/A
		 */
		category_posts_update : function(taxonomy, term_id, value, state) {

			// vars
			var filters_menu = $('.filters-selections'),
				filter_menu_item = filters_menu.children('li[data-tax="' + taxonomy + '"]'),
				category_filters = $('#modal-category-filter').find('.modal-body').children(),
				checked_filters = $('.checked-filters'),
				posts = $('.posts-wrap').children();

			// update checked filters
			if ( state ) {
				// +1 to filter menu item
				count = parseInt(filter_menu_item.data('count'));
				filter_menu_item.data('count', count+1);
				filter_menu_item.find('.count').text('(' + (count+1) + ')');

				// add filter to checked filters
				checked_filters.append('<li data-id="term_' + term_id + '"><span>' + value + '</span><span class="remove">X</span></li>');
			}
			else {
				// -1 to filter menu item
				count = parseInt(filter_menu_item.data('count'));
				filter_menu_item.data('count', count-1);

				if ( count == 1 ) {
					filter_menu_item.find('.count').text('');
				}
				else {
					filter_menu_item.find('.count').text('(' + (count-1) + ')');
				}

				// remove filter from checked filters
				checked_filters.find('li[data-id="term_' + term_id + '"]').remove();

				// uncheck filter from category filter modal
				category_filters.find('li[data-id="term_' + term_id + '"]').children('label').children('input').prop('checked', false);
			}

			// expose/hide checked filters
			if (checked_filters.children().length) {
				// expose checked filters
				checked_filters.show();
			}
			else {
				// hide checked filters
				checked_filters.hide();

				// expose all posts
				posts.show();
				posts.addClass('unfiltered');
			}

			// update posts displayed
			if (checked_filters.children().length) {
				// hide all posts
				posts.removeClass('unfiltered');
				posts.hide();

				// vars
				var posts_not_found_msg = $('.filtered-posts-not-found'),
					posts_not_found = true;

				$.each(posts, function(i, post) {
					// vars
					var show = true;

					$.each(checked_filters.children(), function(j, filter) {
						// vars
						var term_id = $(filter).data('id');

						if (!$(post).hasClass(term_id)) {
							// don't show post
							show = false;
							return false;
						}
					});

					if (show) {
						$(post).show();
						$(post).addClass('unfiltered');
						posts_not_found = false;
					}
				});

				// expose not found error in case of no posts filtered
				if (posts_not_found) {
					posts_not_found_msg.show();
				}
				else {
					posts_not_found_msg.hide();
				}
			}

			// align post boxes
			KULAM_general.post_boxes();

		},

		/**
		 * reset_category_filters
		 *
		 * Called from category_filter_events
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		reset_category_filters : function() {

			var filters_menu = $('.filters-selections'),
				category_filters = $('#modal-category-filter').find('.modal-body').children(),
				checked_filters = $('.checked-filters'),
				posts = $('.posts-wrap').children(),
				posts_not_found_msg = $('.filtered-posts-not-found');

			// reset filters menu
			filters_menu.children().data('count', 0);
			filters_menu.find('.count').text('');

			// reset category filters
			category_filters.find('input').prop('checked', false);

			// reset checked filters
			checked_filters.find('li').remove();
			checked_filters.hide();

			// expose all posts
			posts.show();
			posts.addClass('unfiltered');

			// hide not found error
			posts_not_found_msg.hide();

			// align post boxes
			KULAM_general.post_boxes();

		},

		/**
		 * banner
		 *
		 * Called from init
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		banner : function() {

			// variables
			var banners = $('.main-banner');

			if (banners.length) {
				banners.find('.control').on('click', function(event) {
					event.stopPropagation();

					var banner = $(this).closest('.cycle-slideshow');

					if ($(this).hasClass('cycle-next')) {
						banner.cycle('next');
					}
				});
			}

		},

		/**
		 * qna
		 *
		 * Called from init
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		qna : function() {

			// toggle question
			$('.kulam-qna li .qna-title').on('click', function(event) {

				event.preventDefault();

				KULAM_general.qna_toggle($(this));

			});

		},

		/**
		 * qna_toggle
		 *
		 * Called from qna
		 *
		 * @param   question (object)
		 * @return  N/A
		 */
		qna_toggle : function(question) {

			// variables
			var qnaBlock = question.closest('.kulam-qna'),
				questions = qnaBlock.children('li'),
				current = question.parent('li'),
				isActive = current.hasClass('active');

			if (isActive) {
				// close current question
				current.toggleClass('active');
			}
			else {
				// close all questions
				questions.removeClass('active');

				// open current question
				current.addClass('active');
			}

		},

		/**
		 * google_map
		 *
		 * Called from init
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		google_map : function() {

			if ( typeof googleMapsData !== 'undefined' && typeof googleMapsData._googleMapsApi !== 'undefined' ) {
				$('.acf-map').each(function() {

					// variables
					var map = KULAM_general.initMap($(this));

				});
			}

		},

		/**
		* initMap
		*
		* Renders a Google Map onto the selected jQuery element
		*
		* @param    $el (object) The jQuery element
		* @return   (object) The map instance
		*/
		initMap : function($el) {

			// find marker elements within map
			var $markers = $el.find('.marker');

			// create gerenic map
			var mapArgs = {
				zoom: $el.data('zoom') || 16,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			var map = new google.maps.Map($el[0], mapArgs);

			// add markers
			map.markers = [];
			$markers.each(function() {
				KULAM_general.initMarker($(this), map);
			});

			// center map based on markers
			KULAM_general.centerMap(map);

			// return
			return map;

		},

		/**
		* initMarker
		*
		* Creates a marker for the given jQuery element and map
		*
		* @param    $el (object) The jQuery element
		* @param    map (object) The map instance
		* @return   N/A
		*/
		initMarker : function($marker, map) {

			// get position from marker
			var lat = $marker.data('lat');
			var lng = $marker.data('lng');
			var latLng = {
				lat: parseFloat( lat ),
				lng: parseFloat( lng )
			};

			// create marker instance
			var marker = new google.maps.Marker({
				position: latLng,
				map: map
			});

			// append to reference for later use
			map.markers.push(marker);

			// if marker contains HTML, add it to an infoWindow
			if ($marker.html()) {
				// create info window
				var infowindow = new google.maps.InfoWindow({
					content: $marker.html()
				});

				// show info window when marker is clicked
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.open(map, marker);
				});
			}

		},

		/**
		* centerMap
		*
		* Centers the map showing all markers in view
		*
		* @param    map (object) The map instance
		* @return   N/A
		*/
		centerMap : function(map) {

			// create map boundaries from all map markers
			var bounds = new google.maps.LatLngBounds();

			map.markers.forEach(function( marker ){
				bounds.extend({
					lat: marker.position.lat(),
					lng: marker.position.lng()
				});
			});

			// single marker
			if (map.markers.length == 1) {
				map.setCenter( bounds.getCenter() );

			// multiple markers
			} else {
				map.fitBounds(bounds);
			}

		},

		/**
		 * slideshow
		 *
		 * Called from init
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		slideshow : function() {

			// variables
			var slideshows = $('.kulam-slideshow');

			if (slideshows.length) {
				slideshows.find('.slide').each(function() {
					KULAM_general.slide_read_more_wrap($(this));
				});
			}

		},

		/**
		 * slide_read_more_wrap
		 *
		 * Called from slideshow
		 *
		 * @param   slide (object)
		 * @return  N/A
		 */
		slide_read_more_wrap : function(slide) {

			// variables
			var read_more_wrap = '<div class="read-more"><span>' + ajaxdata.strings.read_more + '</span></div>',
				bg_image = slide.find('img').attr('src'),
				color_scheme = slide.closest('.kulam-slideshow').data('scheme-color'),
				rgba_color_scheme = color_scheme ? KULAM_general.hexToRgbA(color_scheme, '.5') : 'transparent';

			slide.children('a').append(read_more_wrap);

			slide.find('.read-more').css({'background-image': 'url(\'' + bg_image + '\')', 'background-color': rgba_color_scheme});

		},

		/**
		 * galleries
		 *
		 * Called from init
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		galleries : function() {

			if (js_globals.galleries.length > 0) {
				var galleries = $.parseJSON(js_globals.galleries);

				$.each(galleries, function(id, gallery) {
					// variables
					var controls = $('.'+id).next('.controls'),
						more = controls.find('.load-more'),
						all = controls.find('.show-all'),
						less = controls.find('.show-less');

					// init gallery
					KULAM_general.initGallery(id, gallery);

					KULAM_general.lazyLoad(id, KULAM_general.params.galleries[id]);

					// bind click event to gallery 'load more' btn
					more.bind('click', function() {
						KULAM_general.lazyLoad(id, KULAM_general.params.galleries[id]);
					});

					// bind click event to gallery 'view all' btn
					all.bind('click', function() {
						KULAM_general.lazyLoad(id, KULAM_general.params.galleries[id], true);
					});

					// bind click event to gallery 'show less' btn
					less.bind('click', function() {
						KULAM_general.initGallery(id, gallery);
						KULAM_general.lazyLoad(id, KULAM_general.params.galleries[id]);
					});

					// PhotoSwipe
					KULAM_general.initPhotoSwipeFromDOM('.'+id);
				});
			}

		},

		/**
		 * initGallery
		 *
		 * Init gallery images
		 *
		 * @param   id (int) Gallery ID
		 * @param   gallery (array)
		 * @return  N/A
		 */
		initGallery : function (id, gallery) {

			// clear gallery grid
			$('.'+id).find('.gallery-col').html('');

			// init gallery
			KULAM_general.params.galleries[id] = {
				images          : gallery['images'],
				scheme_color    : gallery['scheme_color'] ? KULAM_general.hexToRgbA(gallery['scheme_color'], '1') : 'transparent',
				active_images   : 0,
				active_column   : 0
			};

		},

		/**
		 * lazyLoad
		 *
		 * Load gallery images
		 *
		 * @param   id (int) Gallery ID
		 * @param   gallery (obj) Gallery object
		 * @param   showAll (bool) Whether to load all images
		 * @return  N/A
		 */
		lazyLoad : function (id, gallery, showAll) {

			showAll = typeof showAll !== 'undefined' ? showAll : false;

			// variables
			var controls = $('.'+id).next('.controls'),
				more = controls.find('.load-more'),
				all = controls.find('.show-all'),
				less = controls.find('.show-less'),
				index, j,
				maxLoad = showAll ? gallery['images'].length : KULAM_general.params.images_more_interval;

			for (index=gallery['active_images'], j=0 ; j<maxLoad && gallery['images'].length>index ; index++, j++) {
				// remove video url query string
				gallery['images'][index]['description'] = gallery['images'][index]['description'].split('&')[0];

				// fix youtube embed url string
				gallery['images'][index]['description'] = gallery['images'][index]['description'].replace('watch?v=', 'embed/');

				// fix vimeo embed url string
				gallery['images'][index]['description'] = gallery['images'][index]['description'].replace('https://vimeo.com/', 'https://player.vimeo.com/video/');

				// expose image
				var imageItem =
					'<figure class="gallery-item" data-index="' + index + '" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject" ' + (gallery['images'][index]['description'] ? 'data-type="video" data-video-src="' + gallery['images'][index]['description'] + '" data-video=\'<div class="wrapper"><div class="video-wrapper"><iframe class="pswp__video" src="' + gallery['images'][index]['description'] + '" width="960" height="640" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div></div>\'' : '') + '>' +
						'<a href="' + (gallery['images'][index]['description'] ? '#' : gallery['images'][index]['url']) + '" itemprop="contentUrl">' +
							'<img class="no-border" src="' + gallery['images'][index]['url'] + '" itemprop="thumbnail" alt="' + gallery['images'][index]['alt'] + '" />' +
						'</a>' +
						'<figcaption itemprop="caption description" style="background-image:url(\'' + gallery['images'][index]['url'] + '\');background-color:' + gallery['scheme_color'] + ';">' +
							'<div class="caption">' +
								'<div class="title">' + gallery['images'][index]['title'] + '</div>' +
								'<div class="date">' + gallery['images'][index]['date'] +  '</div>' +
								(gallery['images'][index]['caption'] ? '<div class="caption-content">' + gallery['images'][index]['caption'] +  '</div>' : '') +
							'</div>' +
						'</figcaption>' +
						(gallery['images'][index]['description'] ? '<div class="play"><svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%"><path class="ytp-large-play-button-bg" d="m .66,37.62 c 0,0 .66,4.70 2.70,6.77 2.58,2.71 5.98,2.63 7.49,2.91 5.43,.52 23.10,.68 23.12,.68 .00,-1.3e-5 14.29,-0.02 23.81,-0.71 1.32,-0.15 4.22,-0.17 6.81,-2.89 2.03,-2.07 2.70,-6.77 2.70,-6.77 0,0 .67,-5.52 .67,-11.04 l 0,-5.17 c 0,-5.52 -0.67,-11.04 -0.67,-11.04 0,0 -0.66,-4.70 -2.70,-6.77 C 62.03,.86 59.13,.84 57.80,.69 48.28,0 34.00,0 34.00,0 33.97,0 19.69,0 10.18,.69 8.85,.84 5.95,.86 3.36,3.58 1.32,5.65 .66,10.35 .66,10.35 c 0,0 -0.55,4.50 -0.66,9.45 l 0,8.36 c .10,4.94 .66,9.45 .66,9.45 z" fill="#1f1f1e" fill-opacity="0.81"></path><path d="m 26.96,13.67 18.37,9.62 -18.37,9.55 -0.00,-19.17 z" fill="#fff"></path><path d="M 45.02,23.46 45.32,23.28 26.96,13.67 43.32,24.34 45.02,23.46 z" fill="#ccc"></path></svg></div>' : '') +
					'</figure>';

				$(imageItem).appendTo( $('.'+id+' .col' + gallery['active_column']%KULAM_general.params.images_columns) );

				// Update active column
				gallery['active_column'] = gallery['active_column']%KULAM_general.params.images_columns + 1;
			}

			if (index == gallery['images'].length) {
				// hide more btn
				more.css('display', 'none');

				// hide all btn
				all.css('display', 'none');
			} else {
				// expose more btn
				more.css('display', 'block');

				// expose all btn
				all.css('display', 'block');
			}

			if (index > KULAM_general.params.images_more_interval) {
				// expose less btn
				less.css('display', 'block');
			} else {
				// hide less btn
				less.css('display', 'none');
			}

			// Update active images
			gallery['active_images'] += j;

		},

		/**
		 * initPhotoSwipeFromDOM
		 *
		 * PhotoSwipe init
		 *
		 * @param   gallerySelector (string)
		 * @return  N/A
		 */
		initPhotoSwipeFromDOM : function(gallerySelector) {

			// parse slide data (url, title, size ...) from DOM elements
			// (children of gallerySelector)
			var parseThumbnailElements = function(el) {

				var galleryCols = el.children('.gallery-col'),
					items = [];

				$(galleryCols).each(function() {
					var galleryColItems = $(this).children('.gallery-item');

					$(galleryColItems).each(function() {
						var index = $(this).data('index'),
							link = $(this).children('a'),
							caption = $(this).children('figcaption'),
							date = $(this).children('figcaption').find('.date'),
							img = link.children('img');

						// create slide object
						var item;

						if ($(this).data('type') == 'video') {
							item = {
								html: $(this).data('video'),
								clipboard: $(this).data('video-src')
							};
						} else {
							item = {
								src: link.attr('href'),
								w: img[0].naturalWidth,
								h: img[0].naturalHeight,
								msrc: img.attr('src'),
								clipboard: link.attr('href')
							};
						}

						if (caption) {
							item.title = caption.html();
						}

						if (date) {
							item.date = date.html();
						}

						item.el = $(this)[0]; // save link to element for getThumbBoundsFn

						items[index] = item;
					});
				});

				return items;

			};

			// triggers when user clicks on thumbnail
			var onThumbnailsClick = function(e) {

				e = e || window.event;
				e.preventDefault ? e.preventDefault() : e.returnValue = false;

				var eTarget = e.target || e.srcElement;

				// find root element of slide
				var clickedListItem = $(eTarget).closest('figure');

				if(!clickedListItem) {
					return;
				}

				// find index of clicked item
				var clickedGallery = clickedListItem.parent().parent(),
					index = clickedListItem.attr('data-index');

				if(clickedGallery && index >= 0) {
					// open PhotoSwipe if valid index found
					openPhotoSwipe(index, clickedGallery);
				}

				return false;

			};

			var openPhotoSwipe = function(index, galleryElement) {

				var pswpElement = document.querySelectorAll('.pswp')[0],
					gallery,
					options,
					items;

				items = parseThumbnailElements(galleryElement);

				// define options (if needed)
				options = {
					// define gallery index (for URL)
					galleryUID: galleryElement.attr('data-pswp-uid'),

					getThumbBoundsFn: function(index) {

						// See Options -> getThumbBoundsFn section of documentation for more info
						var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
						pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
						rect = thumbnail.getBoundingClientRect();

						return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};

					},

					index: parseInt(index, 10)
				};

				// exit if index not found
				if( isNaN(options.index) ) {
					return;
				}

				// Pass data to PhotoSwipe and initialize it
				gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
				gallery.init();

				gallery.listen('beforeChange', function() {
					var currItem = $(gallery.currItem.container);
					$('.pswp__video').removeClass('active');
					var currItemIframe = currItem.find('.pswp__video').addClass('active');
					$('.pswp__video').each(function() {
						if (!$(this).hasClass('active')) {
							$(this).attr('src', $(this).attr('src'));
						}
					});
				});

				gallery.listen('close', function() {
					$('.pswp__video').each(function() {
						$(this).attr('src', $(this).attr('src'));
					});
				});

				$(document).off('pswpTap').on('pswpTap', function(e){

					if ($(e.target).hasClass('wrapper'))
						gallery.close();

				});

			};

			// loop through all gallery elements and bind events
			var galleryElements = document.querySelectorAll( gallerySelector );

			for(var i = 0, l = galleryElements.length; i < l; i++) {
				galleryElements[i].setAttribute('data-pswp-uid', i+1);
				galleryElements[i].onclick = onThumbnailsClick;
			}

		},

		/**
		 * hmembership
		 *
		 * Called from init
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		hmembership : function() {

			// vars
			var hmembership_form = $('.hmembership-form');

			// change email field order
			if (hmembership_form.length) {
				// vars
				email = hmembership_form.find('tbody').children('tr:first-child');
				select = hmembership_form.find('tbody').children('tr.select');

				// move
				select.after(email);
			}

		},

		/**
		 * my_siddur
		 *
		 * Called from init
		 *
		 * @param   N/A
		 * @return  N/A
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
		 * @param   btn (object)
		 * @return  N/A
		 */
		my_siddur_siddur_toggle : function(btn) {

			// expose loader
			$('.loader').show();

			// variables
			var contentGrid = btn.parent().hasClass('wrap-heart');

			if (!contentGrid) {
				// single post
				text = btn.prop('title');
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
						btn.data('toggle-text', text);
						btn.prop('title', toggleText);
						btn.find('.fa').toggleClass('fa-heart').toggleClass('fa-heart-o');
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
		 * @param   N/A
		 * @return  N/A
		 *
		 * @todo    This function prepare array of post IDS to add, rather than a single post ID.
		 *          In order to activate this option, closing popup form should not clear post_ids_field's post-ids data attribute
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
		 * @param   btn (object)
		 * @return  N/A
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
		 * @param   btn (object)
		 * @return  N/A
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
		 * @param   btn (object)
		 * @return  N/A
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
		 * @param   btn (object)
		 * @return  N/A
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
		 * @param   btn (object)
		 * @return  N/A
		 */
		my_siddur_save_folder_settings : function(btn) {

			// expose loader
			$('.loader').show();

			// variables
			var popup = $('#popup-settings'),
				folder = popup.find('#name-folder-hide').val(),
				folder_new = popup.find('#name-new-folder').val(),
				folder_desc = popup.find('#folder-description').val(),
				delete_folder = popup.find('#del').is(':checked'),
				public_folder = popup.find('#is-public').is(':checked');

			var data = {
				action: 'save_folder_settings',
				user_id: ajaxdata.user_id,
				folder: folder,
				folder_new: folder_new,
				folder_desc: folder_desc,
				delete_folder: delete_folder,
				public_folder: public_folder,
				security: ajaxdata.ajax_nonce
			};

			$.post(ajaxdata.ajaxurl, data, function(response) {

				response = JSON.parse(response);

				if (response[1] && response[1]['name']) {
					// folder name
					folder_new = response[1]['name'];

					// update folder name in url
					window.history.replaceState('', '', KULAM_general.update_url_parameter(window.location.href, 'folder', folder_new));

					// update folder name in page elements
					popup.find('#name-folder-hide').val(folder_new);
					popup.find('#name-new-folder').val(folder_new);
					$('.folder-wrap > .entry-title').text(folder_new);

					// update folder description in page elements
					$('.folder-wrap > .folder-description').html(folder_desc.replace(/\n/g, '<br />'));
					popup.find('#folder-description').val(folder_desc.replace(/<br \/>/g, '\n'));
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
		 * @param   N/A
		 * @return  N/A
		 */
		my_siddur_make_folder_public_before_share : function() {

			$('.save-sharing-choosing').click(function() {

				// variables
				var popup = $('.share-popup'),
					folder = $('#name-folder-hide').val(),
					folder_desc = $('.folder-description').html(),
					choice = popup.find('.choosing-items #public-folder').prop('checked');

				if (choice) {

					// expose loader
					$('.loader').show();

					var data = {
						action: 'save_folder_settings',
						user_id: ajaxdata.user_id,
						folder: folder,
						folder_new: folder,
						folder_desc: folder_desc,
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
		 * @param   uri (string)
		 * @param   key (string)
		 * @param   value (string)
		 * @return  (string)
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
		 * @param   N/A
		 * @return  N/A
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
		 * @param   N/A
		 * @return  N/A
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
		 * homepage_grid
		 *
		 * Called from loaded
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		homepage_grid : function() {

			// variables
			var grid = $('#homepage-tiles');

			if (grid.length) {
				var cat_in_row = grid.data('cat-in-row'),
					header_container = $('header#header > .container'),
					sticky_header_container = $('.sticky-header > .container'),
					grid_wrap = grid.closest('.elementor-container'),
					grid_columns = grid.find('.tile-box-wrapper');

				// set grid columns layout according to number of categories in row
				if (cat_in_row && cat_in_row > 4 && KULAM_general.params.breakpoint >= 992) {
					header_container.css('max-width', '100%');
					sticky_header_container.css('max-width', '100%');
					grid_wrap.css('max-width', 'none');
					grid.css('max-width', '100%');
					grid_columns.css('width', 100/cat_in_row + '%');
				}
				else {
					header_container.css('max-width', '');
					sticky_header_container.css('max-width', '');
					grid.css('max-width', '');
					grid_columns.css('width', '');
				}

			}

		},

		/**
		 * category_filters
		 *
		 * Called from loaded
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		category_filters : function() {

			if (KULAM_general.params.breakpoint >= 1200) {
				KULAM_general.align_category_filters(7);
			}
			else if (KULAM_general.params.breakpoint >= 992 && KULAM_general.params.breakpoint <= 1199) {
				KULAM_general.align_category_filters(5);
			}
			else if (KULAM_general.params.breakpoint >= 768 && KULAM_general.params.breakpoint <= 991) {
				KULAM_general.align_category_filters(4);
			}
			else {
				KULAM_general.align_category_filters();
			}

		},

		/**
		 * align_category_filters
		 *
		 * Called from category_filters
		 *
		 * @param   countInRow (int) Number of elements in a row or 0 to reset alignment
		 * @return  N/A
		 */
		align_category_filters : function(countInRow) {

			if (!$('.filters-selections').length)
				return;

			// vars
			var filters_wrap = $('.category-filters'),
				filters = filters_wrap.find('.filters-selections'),
				li = filters.children();

			// reset elements height
			li.css('width', '100%');

			// setup number of elements in a row
			if (countInRow) {
				$filter_width = li.length >= countInRow ? 100/countInRow : 100/li.length;
				li.css('width', $filter_width+'%');
			}

			// expose filters
			filters_wrap.show();

		},

		/**
		 * post_boxes
		 *
		 * Called from loaded
		 *
		 * @param   N/A
		 * @return  N/A
		 */
		post_boxes : function() {

			if (KULAM_general.params.breakpoint >= 992) {
				KULAM_general.align_post_boxes(4);
			}
			else if (KULAM_general.params.breakpoint >= 768 && KULAM_general.params.breakpoint <= 991) {
				KULAM_general.align_post_boxes(2);
			}
			else {
				KULAM_general.align_post_boxes();
			}

		},

		/**
		 * align_post_boxes
		 *
		 * Called from post_boxes
		 *
		 * @param   countInRow (int) Number of elements in a row or 0 to reset alignment
		 * @return  N/A
		 */
		align_post_boxes : function(countInRow) {

			if (!$('.posts-wrap').length)
				return;

			// vars
			var posts_wrap = $('.posts-wrap'),
				post_boxes = posts_wrap.find('.post-meta');

			// reset elements height
			post_boxes.css('height', 'auto');

			$.each(posts_wrap, function() {
				if ($(this).parent('.kulam-slideshow').length) {
					// slideshow post boxes
					$(this).find('.post-meta').setAllToMaxHeight();
				}
				else if (countInRow) {
					// set same height for all elements in a row
					// vars
					var post_boxes_first_in_row = posts_wrap.children('.unfiltered').filter(function(index) {return index % countInRow == 0;});

					$.each(post_boxes_first_in_row, function() {
						$(this).nextAll('.unfiltered').andSelf().slice(0, countInRow).find('.post-meta').setAllToMaxHeight();
					});
				}
			});

		},

		/**
		 * copyToClipboard
		 *
		 * @param   str (string)
		 * @return  N/A
		 */
		copyToClipboard : function(str) {

			// variables
			var strings = $.parseJSON(js_globals.strings),
				$temp = $("<input>");

			$("body").append($temp);
			$temp.val(str).select();
			document.execCommand("copy");
			$temp.remove();

			alert(strings.copied_to_clipboard);

		},

		/**
		 * breakpoint_refreshValue
		 *
		 * Set window breakpoint values
		 * Called from loaded/alignments
		 *
		 * @param   N/A
		 * @return  N/A
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
		 * @param   N/A
		 * @return  N/A
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
		 * @param   N/A
		 * @return  N/A
		 */
		alignments : function() {

			// set window breakpoint values
			KULAM_general.breakpoint_refreshValue();

			// accessibility icon auto position
			KULAM_general.a11y_icon_top();

			// homepage grid
			KULAM_general.homepage_grid();

			// category filters alignment
			KULAM_general.category_filters();

			// post boxes alignment
			KULAM_general.post_boxes();

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

		// vars
		var uname = $('#uname').val(),
			email = $('#uemail').val(),
			upass = $('#upass').val(),
			cookies = $('#term_cookies'),
			privacy_policy = $('#term_privacy_policy'),
			terms_of_use = $('#term_terms_of_use'),
			prefix = $('#prefix').val(),
			captcha = $('#captcha').val();

		if (!uname || !email || !upass) {
			$('.loader').hide();
			alert("Username/Email/Password is empty");
			return false;
		}

		if (!validateEmail(email)) {
			$('.loader').hide();
			alert("Email is not valid");
			$('#uemail').css("border-color", "red");
			return false;
		}

		if (cookies.length && !cookies.prop('checked') || privacy_policy.length && !privacy_policy.prop('checked') || terms_of_use.length && !terms_of_use.prop('checked')) {
			$('.loader').hide();

			var msg = "You must approve the following:\n\n";
			msg += cookies.length && !cookies.prop('checked') ? cookies.val() + "\n" : '';
			msg += privacy_policy.length && !privacy_policy.prop('checked') ? privacy_policy.val() + "\n" : '';
			msg += terms_of_use.length && !terms_of_use.prop('checked') ? terms_of_use.val() + "\n" : '';

			alert(msg);
			return false;
		}

		var data = {
			action: 'create_account',
			uemail: email,
			upass: upass,
			uname: uname,
			prefix: prefix,
			captcha: captcha,
			security: ajaxdata.ajax_nonce
		};
		jQuery.post(ajaxdata.ajaxurl, data, function (response) {
			if (response === "Success0") {
				window.location.reload(false);
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
			action: 'add_folder',
			nameFolder: $('#name-folder').val(),
			folderDesc: $('#folder-description').val(),
			security: ajaxdata.ajax_nonce
		};
		jQuery.post(ajaxdata.ajaxurl, data, function (response) {
			if (response === "Success") {
				location.reload();
			}
			else if (response === "no") {
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