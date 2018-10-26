var $ = jQuery;

/* Hero header functions */

window.CALAFATE.Hero = {

	// "public" functions

	$siteContent: $('#content'),
	$siteHeader: $('#site-header'),
	$heroHeaderTagline: $('.entry-hero-tagline'),

	mount: function( $elm, callback ) {

		var $heroHeader = $elm || $('.hero-header'),
		 		$heroHeaderImg = $heroHeader.find('.media.image').eq(0),
		 		$heroHeaderVideo = $heroHeader.find('.media.video');

		this.$siteContent = $('#content');
		this.$siteHeader = $('#site-header');
		this.$heroHeaderTagline = $('.entry-hero-tagline');

		this._fixOverlayBugOnce = true;

		// set arrow helper

		$('#primary').append($('.hero-helper-arrow'));
		var $heroArrow = $('.hero-helper-arrow');
		$heroArrow.on('click', function(){
			$('html, body').animate({scrollTop: $(window).height()-100}, 500, 'easeInSine');
		});

		var $heroPaging = null;

		// safe callback for when any loading is over

		var safeCallback = function() {

			$('body').removeClass('before');

			if ( ! $('body').hasClass('gap-1') ) {
				$('#content').css('paddingTop', 0);
			}

			callback();

			setTimeout(function(){
				$('.entry-hero-tagline').addClass('active');
			}, 400);

			setTimeout(function(){
				if ( $(window).scrollTop() < 20 ) {
					$('.hero-helper-arrow').addClass('init');
				}
			}, 400);

			if ( $heroPaging !== null ) {
				setTimeout(function(){
					setTimeout(function(){
						$heroPaging.addClass('active');
					}, 100);
				}, 500);
			}

			setTimeout(function(){
				$(window).trigger('resize');
				$('.hero-header .media').addClass('unmount-transition');
			}, 1000);

			setTimeout(function(){
				$('.hero-carousel-nav-anim').addClass('init');
			}, 1500);

		}

		// init HERO

		if ( $heroHeaderVideo.length > 0 ) {

			window.supports_video_autoplay((function(ok) {

				if ( ok && ! window.detectFirefoxAndroid() ) {
					// load video
					this._loadVideo( $heroHeaderVideo, safeCallback );
				} else {
					// load image
					this._loadImage( $heroHeaderImg, safeCallback );
				}	

			}).bind(this));

			$(window).on('resize.hero', (function() {
				this._positionTagline();
			}).bind(this));

		} else {

			// init slider (if case)
			if ( $heroHeader.find('.carousel').length > 0 ) {
				
				var $carousel = $heroHeader.find('.carousel').flickity({
				  cellAlign: 'left',
				  setGallerySize: false,
				  contain: true,
				  wrapAround: true,
				  selectedAttraction: 0.01,
					friction: 0.15,
					pageDots: true,
					prevNextButtons: false,
					autoPlay: $heroHeader.find('.carousel').data('autoplay') == '1' ? 5000 : false
				});
				
				$('#primary').append($('.flickity-page-dots'));
				$('#primary').children('.flickity-page-dots').addClass('hero-carousel-paging');
				$heroPaging = $('.hero-carousel-paging');

				$heroHeader.find('.carousel-cell:not(first-child) .media.image').each((function(i, elm) {
					this._loadImage($(elm));
				}).bind(this));

				setTimeout((function(){
					this._loadImage( $heroHeaderImg, safeCallback );
				}).bind(this), 250);

			} else {
				this._loadImage( $heroHeaderImg, safeCallback );
			}

		}

		// set window events

		$(window).on('scroll.hero', window.throttle(function(){

			if ( ! window.bodyHasScrollKilledTheProperWay ) { 

				if ( $(window).scrollTop() > 100 && ! $heroHeader.hasClass('active') ) {

					$heroHeader.addClass('active');
					$('.hero-carousel-nav-anim').removeClass('init');

				} else if ( $(window).scrollTop() < 100 && $heroHeader.hasClass('active') ){
					$heroHeader.removeClass('active');
					$('.hero-carousel-nav-anim').addClass('init');
				} 

				if ( $(window).scrollTop() > 20 ) {

					if ( $heroArrow.hasClass('init') ) {
						$heroArrow.removeClass('init');
					}

					if ( $heroPaging != null && $heroPaging.hasClass('active') ) {
						$heroPaging.removeClass('active');
					}

				}

				if ( $(window).scrollTop() < 20 && $heroPaging != null && ! $heroPaging.hasClass('active') ) {
					$heroPaging.addClass('active');
				}

			}

		}, 100));

	},

	unmount: function() {

		// kill all events

		$(window).off('scroll.hero');
		$(window).off('resize.hero');
		$(window).off('mousemove.hero');
		$(window).off('click.hero');

		/*$('.hero-header .media').fadeOut(200);
		$('.hero-header .overlay').fadeOut(200);

		if ( $('.flickity-page-dots').length > 0 ) {
			$('.flickity-page-dots').remove();
		}*/

		setTimeout(function(){
			$('#content').css('marginTop', 0);
		}, 400);

	},

	// "private" functions

	_loadImage: function( $img, callback ) {

		if ( $img.length > 0 ) {

			var initImg = new Image;
			initImg.src = window._srcsetBg($img, $(window));

			if ( initImg.complete ) {
				this._loadPreloadedImage($img, initImg.src, callback);
			} else {
				initImg.onload = (function(e){
					this._loadPreloadedImage($img, initImg.src, callback);
				}).bind(this);

			}

		} else {

			if ( callback )
				callback();

			$(window).on('resize.hero', (function() {
				this._positionTagline();
			}).bind(this)).trigger('resize.hero');

		}

	},

	_loadPreloadedImage: function( $img, src, callback ) {

		this._fixOverlayBug();

		$img.css('backgroundImage', 'url(' + src + ')')
			.addClass('active');

		$(window).on('resize.hero', (function() {
			$img.css( 'backgroundImage', 'url(' + window._srcsetBg( $img, $(window) ) + ')' );
			this._positionTagline();
		}).bind(this)).trigger('resize.hero');

		if ( callback )
			callback();

	},

	_loadVideo: function( $video, callback ) {

		this._fixOverlayBug();

		var initVid = '<video playsinline muted ' + ( $video.attr('data-loop') === '1' ? 'loop' : '' ) + ' class="video-obj no-mejs"><source type="video/mp4" src="' + $video.data('src') + '" /></video>'; 
		$video.append(initVid);

		$video.find('.video-obj')[0].addEventListener('loadedmetadata', function(){
			$video.addClass('active');
			this.play();
			callback();
		});

		$(window).on('resize.hero', (function() {
			this._positionTagline();
		}).bind(this)).trigger('resize.hero');

	},

	_fixOverlayBugOnce: true,

	_fixOverlayBug: function() {

		/*if ( this._fixOverlayBugOnce ) {

			$('body').addClass('kill-overlay');

			setTimeout(function(){
				$('body').removeClass('kill-overlay');
			}, 600);

			this._fixOverlayBugOnce = false;

		}*/

	},

	_positionTagline: function() {
		if ( $('body').hasClass('gap-1') ) {
			this.$siteContent.css('paddingTop', ( ( 100 - parseInt(this.$heroHeaderTagline.data('gap')) ) * ( $(window).height() - this.$siteHeader.outerHeight(true) ) / 100 ));
		} else {
			this.$heroHeaderTagline.height( $(window).height() - this.$siteHeader.outerHeight(true) );
		}	
	}

}