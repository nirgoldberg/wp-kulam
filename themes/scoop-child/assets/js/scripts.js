jQuery(document).ready(function ($) {

	/*******************/
	/* advanced search */
	/*******************/

	$('.advanced-search-btn').click(function() {
		// variables
		var searchForm = $('.search-header');

		searchForm.toggleClass('advanced');
	});

	/**************/
	/* page title */
	/**************/

	$('.page-title .more').click(function() {
		$(this).toggleClass('open');
		$('.category-desc').toggleClass('open');
	});

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
			if (document.documentElement.lang === "en-US") {
				$('#add_to_sidur').text("Remove from my siddur").attr("id", 'remove_from_sidur');
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
			if (document.documentElement.lang === "en-US") {
				$('#remove_from_sidur').text("Add to my siddur").attr("id", 'add_to_sidur');
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
				if ($('#lang').val()) {
					var home_url = document.location.origin;
					var loc = home_url.concat("/en/");
					window.location.href = loc;
				}
				else window.location.href = "/";
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
				if ($('#lang').val()) {
					var home_url = document.location.origin;
					var loc = home_url.concat("/en/");
					window.location.href = loc;
				}
				else window.location.href = "/";
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
