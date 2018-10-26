var $ = jQuery;

/* Page functions (present in any page/post/portfolio/etc.) */
/*
var currentCover = 0;

var $initImg = $('.cover-item:first-child').find('img');
if ( $initImg[0].naturalWidth > 0 ) {

	$('.cover-control li:first-child').addClass('active');
	$('.cover-item').eq(0).addClass('in');
	setTimeout((function(currentCover){
		$('.cover-item').eq(0).removeClass('in').removeClass('pre-down');
	}).bind(this, currentCover), 1000);

} else {

	$initImg.on('load', function(){

		$('.cover-control li:first-child').addClass('active');
		$('.cover-item').eq(0).addClass('in');
		setTimeout((function(currentCover){
			$('.cover-item').eq(0).removeClass('in').removeClass('pre-down');
		}).bind(this, currentCover), 1000);

	})

}

$('.cover-control li').each(function(){

	$(this).on('click', function(e){

		$('.cover-control li').removeClass('active');
		$(this).addClass('active');

		$('.cover-item').eq(currentCover).addClass('out');
		currentCover = $(this).data('index');

		$('.cover-item').eq(currentCover).css('display', 'flex').addClass('pre-down');
		setTimeout((function(currentCover){
			$('.cover-item').eq(currentCover).addClass('in');
		}).bind(this, currentCover), 25);
		setTimeout((function(currentCover){
			$('.cover-item').eq(currentCover).removeClass('in').removeClass('pre-down');
		}).bind(this, currentCover), 1000);

	})

})
*/
window.CALAFATE.Page = {

	mountCovers: function() {

		$('.cover-item').each(function(){
			PreventGhostClick($(this)[0]);
		})

		$('.cover-item').each(function(){

			$(this).hammer().bind('tap', function(e){

				e.preventDefault();

				if ( ! $(this).hasClass('hover') && $('.covers').data('mobile-behavior') != 'taponce' ) {
					$(this).addClass('hover');
					$('.cover-item.hover:not(#' + $(this).attr('id') + ')').removeClass('hover');
				} else {
					if ( themeSettings.ajax === 'enabled' && $(this).attr('target') != '_blank' ) {
						window.CALAFATE.Ajax.lateInit($(this));
						$(this).trigger('click');
					} else {
						if ( $(this).attr('target') != '_blank' ) {
							document.location.href = $(this).attr('href');
						} 
					}
				}

			});

		});

		$('.cover-item').on('mouseenter', function(){

			$('.covers-holder').addClass('hover');
			$(this).addClass('hover').addClass('active');
			$('.cover-item.hover:not(#' + $(this).attr('id') + ')').removeClass('hover');

			if ( $('.covers').hasClass('style--Full') ) {
				$('.covers-background').css('opacity', 0);
			}

		}).on('mouseleave', function(){

				$(this).removeClass('active');

				clearTimeout(window.cit1);
				window.cit1 = setTimeout((function(){
					if ( ! $(this).hasClass('active') ) {
						$(this).removeClass('hover');
					}
				}).bind(this), 3000);

				$('.cover-item.hover:not(#' + $(this).attr('id') + ')').removeClass('hover');

				clearTimeout(window.cit2);
				window.cit2 = setTimeout((function(){
					if( ! $('.cover-item.hover').length > 0 ) {

						$('.covers-holder').removeClass('hover');

						if ( $('.covers').hasClass('style--Full') ) {
							$('.covers-background').css('opacity', 1);
						}

					}
				}).bind(this), 3010);

		});

		if ( $('.covers').hasClass('style--Split') ) {

			$('.covers').append('<div class="bg-split-object"><div class="bg-split-holder"></div></div>');

			var $bgSplit = $('.bg-split-holder');

			$('.covers .bg').each(function(){
				$(this).clone().appendTo($bgSplit);
			});

			$('.cover-item').on('mouseenter', function(){
				$bgSplit.css('transform', 'translateY(' + $(this).data('id') * -100 + '%)');
			});

		}

		function loadCoverVideo() {

			$('.covers-background').append('<video class="no-mejs" src="' + $('.covers-background').data('video') + '" muted loop playsinline></video>');

			$('.covers-background').find('video')[0].addEventListener('loadedmetadata', function(){
				$('.covers-background').css('opacity', 1);
				this.play();
				callback();
			});

		}

		function loadCoverImage() {

			var initImg = new Image;
			initImg.src = $('.covers-background').data('image');

			if ( initImg.complete ) {
				$('.covers-background').append('<div class="image" style="background-image:url(' + $('.covers-background').data('image') + ')"></div>');
				$('.covers-background').css('opacity', 1);
			} else {
				initImg.onload = (function(e){
					$('.covers-background').append('<div class="image" style="background-image:url(' + $('.covers-background').data('image') + ')"></div>');
					$('.covers-background').css('opacity', 1);
				}).bind(this);

			}

		}

		if ( $('.covers-background').length > 0 ) {

			if ( $('.covers-background').data('video') != '' ) {

				window.supports_video_autoplay((function(ok) {

					if ( ok && ! window.detectFirefoxAndroid() ) {
						loadCoverVideo();
					} else {
						loadCoverImage();
					}	

				}).bind(this));

			} else {
				loadCoverImage();
			}

			if ( $('.covers').hasClass('style--Full') ) {
				$('head').append('<style type="text/css">.page-template-template-cover .cover-item.hover + .bg { opacity: 1 !important; }</style>');
			}

		}

	},

	mount: function( $elm ) {

		setTimeout(function(){
			$('.entry-header').addClass('active');
		}, 400);

		var $pageContent = $elm || $('.entry-content > *:not(.grid), .entry-content > .grid > .grid__item, .entry-content > p > img, .entry-content > p > iframe, .entry-footer, .entry-archive, .archive-header, .post-navigation, #site-footer, .single-product .summary > *, .single-product .grid__item, .entry-navigation, .shop_table tr, .cart-collaterals'),

			$pageSharing = $('#site > #site-share') || null,
			$siteActions = $('#site-actions') || null,

			scrollHelpOffset = $('body').hasClass('hero-1') ? 50 : 100; 

		// Set GLOBAL scroll event (only for animations)

		var firstScroll = true;

		$(window).on('scroll.page-anim', function(e) {

			// content animation - WIP (for delay)

			$pageContent.each(function(){

				// content animation (only once !)

				if ( ( $(window).height() + $(window).scrollTop() >= $(this).offset().top + scrollHelpOffset ) && ! $(this).hasClass('active') ) {

					if ( firstScroll ) {

						setTimeout((function(){
							$(this).addClass('active');
						}).bind(this), ( 100 * $(this).index() ) + 400 );

					} else {
						$(this).addClass('active');
					}

				}

			});

			if ( firstScroll )
				firstScroll = false;

		}).trigger('scroll.page-anim');

		// Sharing functions 

		if ( $pageSharing !== null ) {

			$(window).on('scroll.page-share', window.throttle(function(){

				if ( $(window).scrollTop() > 300 && ! $pageSharing.hasClass('shoow') ) {

					$pageSharing.addClass('shoow');
					$pageSharing.children('*').each(function(){
						setTimeout((function(){
							$(this).addClass('active');
						}).bind(this), $(this).index()*100);
					});

				} else if ( $(window).scrollTop() < 300 && $pageSharing.hasClass('shoow') ) {

					$pageSharing.removeClass('shoow');
					$pageSharing.children('*').each(function(){
						setTimeout((function(){
							$(this).removeClass('active');
						}).bind(this), $(this).index()*100);
					});

				}

			}, 250));

		}

		// Site actions init

		if ( $siteActions !== null ) {

			$('#site-actions-up').off('click touchstart');
			$('#site-actions-up').on('click touchstart', function(e) { 

				e.preventDefault();

				window.bodyHasScrollKilledTheProperWay = true;

				if ( ! $('body').hasClass('has-sticky-header-always') ) {
	      	$('#site-header').removeClass('sticky animate shoow');
	      	$('body').css('paddingTop', 0);
					if ( $('.hero-header').length > 0 && $('body').hasClass('scroll-1') ) {
						$('.hero-header').css('marginTop', 0);
					}
				}

				$('html, body').stop().animate({scrollTop: 0}, 500, 'easeInOutSine', function(){
					window.bodyHasScrollKilledTheProperWay = false;
				});

			});

			$('#site-actions-search, .responsive-search').off('click touchstart');
			$('#site-actions-search, .responsive-search').on('click touchstart', function(e){ 
				window.siteSearch($(this));
				e.preventDefault();
			});

			$(window).on('scroll.page-actions', window.throttle(function(){

				if ( $(window).scrollTop() > 300 && ! $siteActions.hasClass('shoow') ) {

					$siteActions.addClass('shoow');
					$siteActions.find('a').each(function(){
						setTimeout((function(){
							$(this).addClass('active');
						}).bind(this), $(this).index()*100);
					});

				} else if ( $(window).scrollTop() < 300 && $siteActions.hasClass('shoow') ) {

					$siteActions.removeClass('shoow');
					$siteActions.find('a').each(function(){
						setTimeout((function(){
							$(this).removeClass('active');
						}).bind(this), $(this).index()*100);
					});

				}

			}, 250));

		}

		// Below we have function calls to js shortcodes, functions, various inits, etc..

		window.parallax();

		var $contentHolder = $('.entry-content');

		// Fit vids

		this._initFitVid( $contentHolder );

		// Contact page

		if ( $contentHolder.find('a[href="#open-map"]').length > 0 ) {
			this._preInitContactPage( $contentHolder.find('a[href="#open-map"]') );
		}

    $('.form-columns select:not(.styled)').each(function(){
      $(this).styledSelect({
        coverClass: 'simple-select-cover',
        innerClass: 'simple-select-inner'
      }).addClass('styled');
    });

		// Twitter feed

		if ( $('.calafate-twitter').length > 0 ) {
			setTimeout((function(){
				this._initTwitterFeed($('.calafate-twitter'));
			}).bind(this), 1000)
		}

		// Lightboxes ---

		// 1. prepare all images for lightbox

		$('.entry-content').find('img:not(.text-link)').parent('a[href]').each(function(){
			if ( $(this).attr('href').match(/\.(jpg|jpeg|png|gif)$/) ) {
				$(this).attr('class', 'fancybox fancybox-thumb ' + $(this).children('img').attr('class'));
				$(this).removeClass('ajax-link');
			}
		});

		if ( $('body').find('.fancybox, div[id*="attachment"]').length > 0 ){

			// 2. set iframes where needed

			if ( $('body').find('.fancybox-iframe').length > 0 ) {
				$('body').find('.fancybox-iframe').each(function(){
					$(this).attr('data-fancybox-type', 'iframe');
				});
			}

			// 3. set galleries when needed

			if ( $('body').find('.fancybox-group').length > 0 ) {
				$('body').find('.fancybox-group').each(function(){
					$(this).attr('data-fancybox', 'one-and-only');
				});
			}

			if ( $('body').find('.fancybox[rel="gallery"]').length > 0 ) { 
				$('body').find('.fancybox[rel="gallery"]').each(function(){
					$(this).attr('data-fancybox', 'rel-and-only');
				});
			}

			// 3.1. captions

			$('.fancybox > img').each(function(){
				if ( $(this).attr('title') != '' ) {
					$(this).parent().attr('data-caption', $(this).attr('title'))
				}
			})

			// 4. there are iframes with predefined used width/height, which need to be tackled independently

			$('body').find('.fancybox, div[id*="attachment"] > a').each(function(){

				if ( $(this).attr('data-fancybox-type') == 'iframe' ) {

					if ( parseInt($(this).attr('data-width')) > 0 || parseInt($(this).attr('data-height')) > 0 ) {

						$(this).addClass('stop-fancybox-double');

						$(this).fancybox({
							aspectRatio: true,
							scrolling: 'no',
							mouseWheel: false,
							nextEasing: 'easeInQuad',
							prevEasing: 'easeInQuad',
						  iframe : {
								css : {
									maxWidth : $(this).attr('data-width') + 'px',
									height: $(this).attr('data-height') + 'px'
								}
							}
						}).append('<span></span>');

					}

				}

			});

			// 5. for all other images & videos, we created galleries

			$('body').find('.fancybox:not(.stop-fancybox-double), div[id*="attachment"] > a:not(.stop-fancybox-double)').fancybox({
				aspectRatio: true,
				scrolling: 'no',
				mouseWheel: false,
				nextEasing: 'easeInQuad',
				prevEasing: 'easeInQuad',
			  onUpdate : function( instance, current ) {

			    var width,
			        height,
			        ratio = 16/9,
			        video = current.$content;
			    
			    if ( video ) {

			      video.hide();

			      width  = current.$slide.width();
			      height = current.$slide.height() - 100;
			      
			      if ( height * ratio > width ) {
			        height = width / ratio;
			      } else {
			        width = height * ratio;
			      }

			      video.css({
			        width  : width,
			        height : height
			      }).show();

			    }

			  } 
			}).append('<span></span>');

		}

		// Carousels

		if ( $('body').find('.calafate-slider').length > 0 ) {
			$('body').find('.calafate-slider').each(function(){
				$(this).children('br').remove();
				$(this).children('.carousel-cell').children('br:first-child').remove();
				$(this).flickity({
				  cellAlign: 'left',
				  contain: true,
				  prevNextButtons: $(this).hasClass('arrows') ? true : false,
				  pageDots: $(this).hasClass('arrows') ? false : true,
				  wrapAround: true,
				  imagesLoaded: true,
				  adaptiveHeight: true,
					pauseAutoPlayOnHover: false,
				  autoPlay: $(this).hasClass('autoplay') ? 4000 : false,
				  arrowShape: 'M11.7191069,4.60592355 L0.463884,4.60592355 C0.207665404,4.60592355 1.11022302e-13,4.78718982 1.11022302e-13,5.01068633 C1.11022302e-13,5.23418285 0.207665404,5.41544911 0.463884,5.41544911 L11.6993162,5.41544911 L8.11126582,9.29848955 C7.96291139,9.45896982 7.96291139,9.71917664 8.11126582,9.87965692 C8.18537975,9.95986281 8.28259494,10 8.37974684,10 C8.47689873,10 8.57411392,9.95986281 8.64822785,9.87965692 L12.8887342,5.29059225 C13.0370886,5.13011197 13.0370886,4.86990515 12.8887342,4.70942488 L8.64822785,0.120360207 C8.5,-0.0401200691 8.25943038,-0.0401200691 8.11126582,0.120360207 C7.96291139,0.280840484 7.96291139,0.5410473 8.11126582,0.701527576 L11.7191069,4.60592355 Z'
				});
			});
		}

		// Galleries

		if ( $('body').find('.calafate-gallery').length > 0 ) {
			$('body').find('.calafate-gallery').each(function(){
				var $galleryGrid = $(this).isotope({
				  itemSelector: '.calafate-gallery--item'
				});
				$galleryGrid.imagesLoaded().progress( function() {
				  $galleryGrid.isotope('layout');
				});
			});
		}

		// Self hosted videos

		if ( $('#content').find('audio, video').length > 0 ) {

			function initmedia() {
				console.log('init media');
				$('#content').find('audio, video:not(.no-mejs)').each(function(){
					console.log('load with mejs');
					$(this).mediaelementplayer({
				    alwaysShowControls: false,
				    iPadUseNativeControls: false,
				    iPhoneUseNativeControls: false,
				    AndroidUseNativeControls: false,
				    enableKeyboard: false,
				    stretching: 'responsive',
				    success: function() {
			        $(window).trigger('resize');
				    }
					});
				});
			}

			if ( typeof $('body').mediaelementplayer === 'function' ) {

				initmedia();

			} else {

				$.when(
					$.getScript(mediaScripts.mediaelement, function(){ return true; }),
					$.getScript(mediaScripts.wp_mediaelement, function(){ return true; })
				).then(function(){
					initmedia();
				});

			}

		}

		// Tabs 

		if ( $('#content').find('.calafate-tabs:not(.woocommerce-tabs)').length > 0 ) {

			$('#content').find('.calafate-tabs:not(.woocommerce-tabs)').each(function(){
				
				var $titles = $(this).find('.tab-title'),
		        $contents = $(this).find('.tab-content'),
		        $openedT = $titles.eq(0),
		        $openedC = $contents.eq(0);

	        $openedT.addClass('active');
	        $openedC.stop().slideDown(0);

	        $titles.click(function(e){

            $openedT.removeClass('active');
            $openedT = $(this);
            $openedT.addClass('active');

            $openedC.stop().slideUp(200);
            $openedC = $contents.eq($(this).index());
            $openedC.stop().delay(200).slideDown(200);

            e.preventDefault();

	        });

			});

		}

		// Toggles

		if ( $('#content').find('.calafate-toggle').length > 0 ) {

			$('#content').find('.calafate-toggle').each(function(){

				$(this).children('h5').click(function(e){

					var $toggle = $(this).parent(),
							$toggleContent = $toggle.children('.content');

					if ( $toggle.hasClass('active') ) {
						$toggle.removeClass('active');
						$toggleContent.stop().slideUp(300);
					} else {
						$toggle.addClass('active');
						$toggleContent.stop().slideDown(300);
					}

					e.preventDefault();

				})

			})

		}

		// Other

		if ( $('body').hasClass('error404') ) {
			setTimeout(function(){
				$('.not-found').css('opacity', 1);
			}, 500);
		}

		if ( $('.text-link').length > 0 ) {
			$('.text-link').each((function(i, elm){
				this._initImageLinks($(elm));
			}).bind(this));
		}

		$('.page-links a').addClass('ajax-link');
		$('img').attr('title', '');

		if ( document.location.hash != '' && ! $('body').hasClass('page-template-template-portfolio') ) {
			if ( $(document.location.hash).length > 0 ) {
				setTimeout(function(){
					$('html, body').stop().animate({'scrollTop': $(document.location.hash).offset().top - 100}, 300);
				}, 200);
			}
		} 

		if ( typeof $.fn.wpcf7InitForm === 'function' ) {
			$('body').find('div.wpcf7 > form').wpcf7InitForm();
		}


	},

	unmount: function( $elm ) {
		
		$(window).off('scroll.page-anim');
		$(window).off('scroll.page-share');
		$(window).off('scroll.page-actions');
		$(window).off('resize.page');
  	$(window).off('scroll.prlx');

		if ( $('body').hasClass('error404') ) {
			$('.not-found').css('opacity', 0);
		}
		setTimeout(function(){
			$('#site > #site-share').removeClass('shoow');
			$('#site > #site-share').children('*').removeClass('active');;
			$('#site-footer').removeClass('active');
		}, 500);

	},

	// VARIOUS FUNCTIONS INIT

	_initFitVid: function( $elm ) {
		$elm.fitVids();
	},

	_initTwitterFeed: function( $elm ) {
		
		var $carousel = $elm.find('.carousel').flickity({
			cellAlign: 'left',
			contain: true,
			prevNextButtons: false,
			pageDots: false,
			wrapAround: true,
			adaptiveHeight: true
		});

		$elm.find('.carousel-prev').on('click', function() {
  		$carousel.flickity('previous');
		});
		$elm.find('.carousel-next').on('click', function() {
  		$carousel.flickity('next');
		});

	},

	_initImageLinks: function( $elm ) {

		if ( $elm.parent().is('a') ) {
			$elm.parent().addClass('image-text-link ajax-link').append('<span><div class="display--table"><div class="display--table-cell">' + $elm.attr('title') + '</div></div>' + svg.arrow + '</span>');
		}

	},

	_preInitContactPage: function( $elm ) {

		if ( $('#map-contact').length > 0 ) {

			$('body').append($('#map-holder'));

			if ( typeof google !== 'undefined' ) {
				this._initContactPage($elm);
			} else {
				$.getScript(mediaScripts.google + $('#map-holder').data('key')).done((function(){
					this._initContactPage($elm);
				}).bind(this));
			}

    }

	},

	_initContactPage: function( $elm ) {

		var $mapInsert = $('#map-contact'),
    		$mapHolder = $('#map-holder');

		$elm.on('click', function(e){

			window.blockhashchange = true;
			e.preventDefault();

			if ( ! $mapHolder.hasClass('opened') ) {
				window.killBodyScrollTheProperWay(true);
				$('body').addClass('map-opened');
				$mapHolder.addClass('opened');
			} else {
				$('body').removeClass('map-opened');
				$mapHolder.removeClass('opened');
				window.killBodyScrollTheProperWay(false);
			}

		});

		$('#map-toggle').on('click', function(e){
			$elm.trigger('click');
			e.preventDefault();
		});

    var map,
    		stylez = [
        {
          featureType: "all",
          elementType: "all",
          stylers: [
            { saturation: -100 }
          ]
        }
    	];
    
    var mapOptions = {
      zoom: $mapInsert.data('zoom'),
      center: new google.maps.LatLng($mapInsert.data('map-lat'), $mapInsert.data('map-long')),
      streetViewControl: true,
      scrollwheel: true,
      panControl: false,
      mapTypeControl: false,
      overviewMapControl: true,
      zoomControl: true,
      draggable: true,
      zoomControlOptions: {
        style: google.maps.ZoomControlStyle.LARGE
      },
      mapTypeControlOptions: {
        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'krownMap']
      }
    };

    map = new google.maps.Map(document.getElementById($mapInsert.attr('id')), mapOptions);

    if( $mapInsert.data('greyscale') == 'Greyscale' ) {

      var mapType = new google.maps.StyledMapType(stylez, { name:"Grayscale" });    
      map.mapTypes.set('krownMap', mapType);
      map.setMapTypeId('krownMap');

    }

    if( $mapInsert.data('marker') == '1' ) {

      var myLatLng = new google.maps.LatLng($mapInsert.data('map-lat'), $mapInsert.data('map-long'));
      var myImg = {
          url: $mapInsert.data('marker-img'),
          scaledSize: new google.maps.Size(120, 120)
      };
      var beachMarker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          icon: myImg
      });

  	}

	}

}