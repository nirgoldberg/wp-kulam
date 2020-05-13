/*
	Name: YouTubePopUp
	Description: jQuery plugin to display YouTube or Vimeo video in PopUp, responsive and retina, easy to use.
	Version: 1.0.1
	Plugin URL: http://wp-time.com/youtube-popup-jquery-plugin/
	Written By: Qassim Hassan
	Twitter: @QQQHZ
	Websites: wp-time.com | qass.im | wp-plugins.in
	Dual licensed under the MIT and GPL licenses:
		http://www.opensource.org/licenses/mit-license.php
		http://www.gnu.org/licenses/gpl.html
	Copyright (c) 2016 - Qassim Hassan


	Mod by MaximusBaton
*/

(function ( $ ) {

	$.fn.YouTubePopUp = function(options) {

		var YouTubePopUpOptions = $.extend({
				autoplay       : 1,
				controls       : 1,
				cc_load_policy : 0,
				iv_load_policy : 3,
				rel            : 0,
				showinfo       : 0
		}, options );

		var youtubeLink = $(this).attr("href");

		if( youtubeLink.match(/(youtube.com)/) ){
			var split_c = "v=";
			var split_n = 1;
		}

		if( youtubeLink.match(/(youtu.be)/) || youtubeLink.match(/(vimeo.com\/)+[0-9]/) ){
			var split_c = "/";
			var split_n = 3;
		}

		if( youtubeLink.match(/(vimeo.com\/)+[a-zA-Z]/) ){
			var split_c = "/";
			var split_n = 5;
		}

		var getYouTubeVideoID = youtubeLink.split(split_c)[split_n];

		var cleanVideoID = getYouTubeVideoID.replace(/(&)+(.*)/, "");

		if( youtubeLink.match(/(youtu.be)/) || youtubeLink.match(/(youtube.com)/) ){
			var videoEmbedLink = "https://www.youtube.com/embed/"+cleanVideoID+"?autoplay="+YouTubePopUpOptions.autoplay+"&controls="+ YouTubePopUpOptions.controls +"&cc_load_policy="+ YouTubePopUpOptions.cc_load_policy +"&iv_load_policy="+ YouTubePopUpOptions.iv_load_policy +"&rel="+ YouTubePopUpOptions.rel +"&showinfo="+ YouTubePopUpOptions.showinfo +"";
		}

		if( youtubeLink.match(/(vimeo.com\/)+[0-9]/) || youtubeLink.match(/(vimeo.com\/)+[a-zA-Z]/) ){
			var videoEmbedLink = "https://player.vimeo.com/video/"+cleanVideoID+"?autoplay="+YouTubePopUpOptions.autoplay+"";
		}

		$("body").append('<div class="YouTubePopUp-Wrap YouTubePopUp-animation"><div class="YouTubePopUp-Content"><span class="loading">Loading...</span><span class="YouTubePopUp-Close"></span><iframe src="'+videoEmbedLink+'" allowfullscreen></iframe></div></div>');
		$('.YouTubePopUp-Content iframe')[0].onload = function() {
			$('.YouTubePopUp-Wrap .loading').hide();
			$('.YouTubePopUp-Wrap iframe').show();
		};

		if( $('.YouTubePopUp-Wrap').hasClass('YouTubePopUp-animation') ){
			setTimeout(function() {
				$('.YouTubePopUp-Wrap').removeClass("YouTubePopUp-animation");
			}, 600);
		}

		$(".YouTubePopUp-Wrap, .YouTubePopUp-Close").click(function(){
			$.event.trigger({type : 'youtubeVideoBeforeClose', link : youtubeLink});
			$(".YouTubePopUp-Wrap").addClass("YouTubePopUp-Hide").delay(515).queue(function() { $(this).remove(); });
		});

		$.event.trigger({type : 'youtubeVideoStarted', link : youtubeLink});


		$(document).keyup(function(e) {

			if ( e.keyCode == 27 ){
				$('.YouTubePopUp-Wrap, .YouTubePopUp-Close').click();
			}

		});

	};

}( jQuery ));
