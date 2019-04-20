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

			// page title
			KULAM_general.page_title();

			// advanced search
			KULAM_general.advanced_search();

			// bootstrap modal
			KULAM_general.bootstrap_modal();

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
				cat = KULAM_general.get_url_param('cat');

			if (post_format && post_format != 'N/A' || pt && pt != 'N/A' || cat && cat != 'N/A') {
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
				advancedFields = searchForm.find('.advanced-search-fields');

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
				advancedFields = searchForm.find('.advanced-search-fields'),
				advancedInputFields = advancedFields.find('input, select');

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
					renderItem: function (item, search) {
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
		 * breakpoint_refreshValue
		 *
		 * Set window breakpoint values
		 * Called from loaded/alignments
		 *
		 * @param	N/A
		 * @return	N/A
		 */
		breakpoint_refreshValue : function () {

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

		}

	};

// make it safe to use console.log always
(function(a){function b(){}for(var c="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),d;!!(d=c.pop());){a[d]=a[d]||b;}})
(function(){try{console.log();return window.console;}catch(a){return (window.console={});}}());

$(KULAM_general.init);
$(window).load(KULAM_general.loaded);


jQuery(document).ready(function ($) {

	$('.entry-sharing').on('click', '#add_to_sidur', function (event) {
		event.preventDefault();
		var data = {
			action: 'change_sidur',
			user: ajaxdata.user_id,
			post: ajaxdata.post_id,
			security: ajaxdata.ajax_nonce
		};
		jQuery.post(ajaxdata.ajaxurl, data, function (response) {
			var path = window.location.origin;
			if ( path === 'https://onward.kulam.org' ) {
				$('#add_to_sidur').text("Remove from My Shelf").attr("id", 'remove_from_sidur');
			} 
			else if (document.documentElement.lang === "en-US") {
				$('#add_to_sidur').text("Remove from My Siddur").attr("id", 'remove_from_sidur');
			}
			else if( path === 'https://masaisraeli.kulam.org'){
				$('#add_to_sidur').text("להסיר ממועדפים שלי").attr("id", 'remove_from_sidur');
			} 
			else
			   $('#add_to_sidur').text("להסיר מהסידור שלי").attr("id", 'remove_from_sidur');

		});
		return false;
	});

	$('.entry-sharing').on('click', '#remove_from_sidur', function (event) {
		event.preventDefault();
		var data = {
			action: 'remove_sidur',
			user: ajaxdata.user_id,
			post: ajaxdata.post_id,
			security: ajaxdata.ajax_nonce
		};
		jQuery.post(ajaxdata.ajaxurl, data, function (response) {
			var path = window.location.origin;
			if ( path === 'https://onward.kulam.org' )
			{
				$('#remove_from_sidur').text("Add to My Shelf").attr("id", 'add_to_sidur');
			} 
			else if (document.documentElement.lang === "en-US") {
				$('#remove_from_sidur').text("Add to My Siddur").attr("id", 'add_to_sidur');
			}
			else if( path === 'https://masaisraeli.kulam.org')
			{
				$('#remove_from_sidur').text(" הוסף למועדפים שלי").attr("id", 'add_to_sidur');
			} 
			else
			   $('#remove_from_sidur').text("הוסף לסידור שלי").attr("id", 'add_to_sidur');

		});
		return false;
	});

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
	$('.close-popup').click(function () {
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
	var id;
	var x = $('.link-folder').get();
	if (x.length > 0) {
		$(".siddur-wrap .grid-item").append('<label class="add-post-to-folder" id="a" >+</label>');
	}
	$('.add-post-to-folder').click(function () {
		id = event.target.offsetParent.id;
		id = id.slice(5);
		var popup = document.getElementById("all-folders");
		popup.classList.toggle("show");
	});
	$('.close-popup-folders').click(function () {
		var popup = document.getElementById("all-folders");
		popup.classList.toggle("show");
	});
	$('.save-in-folder').click(function () {
		$('.loader').show();
		var data = {
			action: "save-in-folder",
			idPost: id,
			selectedOption: $("input:radio[name=option]:checked").val(),
			security: ajaxdata.ajax_nonce
		}
		jQuery.post(ajaxdata.ajaxurl, data, function (response) {
			if (response === "Success0") {
				location.reload();
			}
			else {
				location.reload();
				$('loader').hide();
				alert(response);
			}
		});
	});

	$(".folder-wrap .grid-item").append('<label class="remove-post-from-folder" id="r">-</label>');
	$('.remove-post-from-folder').click(function () {
		$('.loader').show();
		var data = {
			action: "remove-post-from-folder",
			postRemove: (event.target.offsetParent.id).slice(5),
			from_name_folder: $('#name-new-folder').val(),
			security: ajaxdata.ajax_nonce
		};
		jQuery.post(ajaxdata.ajaxurl, data, function (response) {
			if (response === "Success0") {
				location.reload();
			}
			else {
				$('.loader').hide();

			}
		});
	});

	$('.settings').click(function () {
		var data={
			action:'check-share-public',
			namefolder:$('#name-folder-hide').val(),
			security:ajaxdata.ajax_nonce
		};
		jQuery.post(ajaxdata.ajaxurl,data,function(response){
		  if(response !="no0")
		  {
			 $('#is-public').attr("checked", true);
		  }
		  var popup = document.getElementById("popup-settings");
		  popup.classList.toggle("open");
		});
		
	});
	$('.close-popup-settings').click(function () {
		var popup = document.getElementById("popup-settings");
		popup.classList.toggle("open");
	});
	$('.save-settings').click(function () {

			$('.loader').show();
			var data = {
				action: 'setting-folder',
				name_old_folder: $('#name-folder-hide').val(),
				name_new_folder: $('#name-new-folder').val(),
				delete_folder: $('#del').is(':checked'),
				public_folder:$('#is-public').is(':checked'),
				security: ajaxdata.ajax_nonce
			};
			jQuery.post(ajaxdata.ajaxurl, data, function (response) {
				if (response === "Success0" || response == "Success" ||response=="0") {
					var home_url = document.location.origin;
					var loc = home_url.concat("/my-siddur");
					window.location.href = loc;
					$('#popup-settings').hide();

				}
			});
		
	});

	//sharing section
	$('#facebook-share').click(function(){
		var data={
			action:'check-share-public',
			namefolder:$('#name-folder-hide').val(),
			security:ajaxdata.ajax_nonce
		};
		jQuery.post(ajaxdata.ajaxurl,data,function(response){
		  if(response=="no0")
		  {
			$('input[name=public-folder]').attr('checked',false);
			$('.share-popup').addClass('open');
		  }
		  else
		  {
			response= response.substring(0, response.length-1);
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
			action:'check-share-public',
			namefolder:$('#name-folder-hide').val(),
			security:ajaxdata.ajax_nonce
		};
		jQuery.post(ajaxdata.ajaxurl,data,function(response){
		  if(response=="no0")
		  {
			$('input[name=public-folder]').attr('checked',false);
			$('.share-popup').addClass('open');
		  }
		  else
		  {
			response= response.substring(0, response.length-1);
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
		action:'check-share-public',
		namefolder:$('#name-folder-hide').val(),
		security:ajaxdata.ajax_nonce
	};
	jQuery.post(ajaxdata.ajaxurl,data,function(response){
	  if(response=="no0")
	  {
		$('input[name=public-folder]').attr('checked',false);
		$('.share-popup').addClass('open');
	  }
	  else
	  {
		response= response.substring(0, response.length-1);
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
		action:'check-share-public',
		namefolder:$('#name-folder-hide').val(),
		security:ajaxdata.ajax_nonce
	};
	jQuery.post(ajaxdata.ajaxurl,data,function(response){
	  if(response=="no0")
	  {
		$('input[name=public-folder]').attr('checked',false);
		$('.share-popup').addClass('open');
	  }
	  else
	  {
		response= response.substring(0, response.length-1);
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
	$('#popup-link-copy').addClass('open');
})
$('#clipboard-share').click(function(){
	var data={
		action:'check-share-public',
		namefolder:$('#name-folder-hide').val(),
		clipboard:'cliboard',
		security:ajaxdata.ajax_nonce
	};

	jQuery.post(ajaxdata.ajaxurl,data,function(response){
	  if(response=="no0")
	  {
		$('input[name=public-folder]').attr('checked',false);
		$('.share-popup').addClass('open');
	  }
	  else
	  {
		response= response.substring(0, response.length-1);
		var home_url = document.location.origin;
		response = home_url.concat(response);
		document.getElementById("link_to_copy").value = response;
		$('#popup-link-copy').addClass('open');
	  }
});
});
$('.close-popup-link').click(function(){
	$('#popup-link-copy').removeClass('open');
});
	$('.close-share-popup').click(function() {
		$('.share-popup').removeClass('open');
	});
	$('.save-sharing-choosing').click(function(){
		var choose=$('.conf').val();
		if(choose=='public-folder')
		{
			$('.loader').show();
			var data = {
				action: 'setting-folder',
				public_folder:true,
				name_old_folder:$('#name-folder-hide').val(),
				security: ajaxdata.ajax_nonce
			};
			jQuery.post(ajaxdata.ajaxurl, data, function (response){
				if(response=="Success0"){
					$('.loader').hide();
				var lang = document.documentElement.lang;
				if(lang=="he-IL") 
					alert("התקיה שלך  ציבורית");
				else
					alert("your folder is public");
				 console.log(response);
				}
			});
		}
		$('.share-popup').removeClass('open');
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
	$('.close-popup-to').click(function () {
		var popup = document.getElementById("popup-to");
		popup.classList.toggle("show");
	})
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