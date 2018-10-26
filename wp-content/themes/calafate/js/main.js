// Start !

(function($){

	$(document).ready(function(){

		window.checkForPwd();

		// continue ..

		if ( ! ( document.location.hash != '' && ! $('body').hasClass('page-template-template-portfolio') ) ) {
			setTimeout(function(){
				$('html, body').stop().animate({'scrollTop': 0}, 0);
			}, 200);
		}

    if ( window.touchM ) {
      $('body').removeClass('no-touch')
        .addClass('touch');
    }

    // Sticky header init

  	var $body = $('body'),
  			$site = $('#site'),
  			$siteHeader = $('#site-header'),
  			$heroHeader = $('.hero-header');

    if ( themeSettings.sticky === 'enabled' ) {

  	  var didScroll;
      $.lastScrollTop = 0;
      var delta = 5;
      var navbarHeight = $siteHeader.outerHeight() + $siteHeader.offset().top;

      var stickyScrollT = null;

      $(window).on('scroll.sticky', function(){//window.throttle(function(){

      	if ( ! window.bodyHasScrollKilledTheProperWay ) {

	        var st = $(window).scrollTop();

	        if ( st < 0 || Math.abs($.lastScrollTop - st) <= delta ) 
	          return;	

	        if ( ( st <= ( parseInt($siteHeader.css('marginBottom')) + parseInt($siteHeader.css('paddingBottom')) ) && $siteHeader.hasClass('sticky') ) || ( st >= $.lastScrollTop && $siteHeader.hasClass('sticky') ) ) {

	        	// hide 

	        	$siteHeader.removeClass('sticky animate shoow');
	        	$body.css('paddingTop', 0);

						if ( $heroHeader.length > 0 && $body.hasClass('scroll-1') ) {
							$heroHeader.css('marginTop', 0);
						}

	        	if ( stickyScrollT !== null ) 
	        		clearTimeout(stickyScrollT);

	        } else if ( st < $.lastScrollTop && st > 500 && st < ( $(document).height() - $(window).height() ) && ! $siteHeader.hasClass('sticky') ) {

	        	// show

	        	$body.css('paddingTop', $siteHeader.outerHeight(true));
						$siteHeader.addClass('sticky');

						if ( $heroHeader.length > 0 && $body.hasClass('scroll-1') ) {
							$heroHeader.css('marginTop', parseInt($body.css('paddingTop'))*(-1));
						}

	        	stickyScrollT = setTimeout(function(){

	        		$siteHeader.addClass('animate');

		        	stickyScrollT = setTimeout(function(){

		        		$siteHeader.addClass('shoow');

		        	}, 10);

	        	}, 10);
	        	
	        }

	      }

        $.lastScrollTop = st;
	        
     })// }, 100));

    } else if ( themeSettings.sticky === 'always' ) {

    	$siteHeader.addClass('sticky');

    	var $heroHeader = $('.hero-header').length > 0 ? $('.hero-header') : null;

      $(window).on('resize.sticky', function(){
      	$site.css('paddingTop', $siteHeader.outerHeight(true));
      	if ( $heroHeader !== null ) {
      		$heroHeader.css({
      			height: $(window).height() - $siteHeader.outerHeight(),
      			top: $siteHeader.outerHeight()
      		})
      	}
      }).trigger('resize.sticky');

    }

		// Responsive menu init

		$('#site-overlay').prepend('<div id="responsive-menu" class="cready-by-js" style="width: 100%"><div class="wrapper"><ul class="overlay-menu">' + $('.top-menu').html() + '</ul></div></div>');

		var $rMenu = $('#responsive-menu'),
				$rMenuList = $rMenu.find('.overlay-menu').children('li'),
				$rMenuOpener = $('.responsive-nav'),

				rMenuEnabled = true,

				$siteNav = $('#site-navigation');

		$rMenu.find('a').each(function() {
			$(this).addClass('immediate-propagation')
				.on('click', function() {
					if ( $(this).parent().parent().hasClass('sub-menu') ) {
						$(this).parent().parent().parent().removeClass('touch-hover');
						rMenuTouchFix();
					}
					setTimeout(function(){
						$rMenuOpener.trigger('click');
						$rMenu.find('.touch-hover').removeClass('touch-hover')
					}, 250);
					$('html, body').stop().animate({scrollTop: 0}, 300);
				})
		})

		$rMenuOpener.on('click touchstart', function(e) {

			if ( rMenuEnabled ) {
				rMenuEnabled = false;
				setTimeout(function(){
					rMenuEnabled = true;
				}, 200);
				
				window.blockhashchange = true;

				if ( ! $(this).hasClass('opened') ) {

					setTimeout(function(){
						fixSubmenus('overlay');
					}, 500)

					$(this).addClass('opened');
					$rMenu.addClass('active');
					$rMenuList.each(function(){
						$(this).addClass('init-anim');
						setTimeout((function(){
							$(this).addClass('active');
						}).bind(this), $(this).index() * 50 + 100); 
					});

					setTimeout(function(){
						$rMenu.find('.overlay-menu').children('li').removeClass('init-anim');
					}, 400);

					rMenuTouchFix2();
				
					window.openGlobalOverlay(true, $('#responsive-menu'), $(this));
					window.killBodyScrollTheProperWay(true);

				} else {

					$(this).removeClass('opened');
					$rMenu.removeClass('active');
					$rMenuList.each(function(){
						$(this).addClass('init-anim');
						setTimeout((function(){
							$(this).removeClass('active');
						}).bind(this), ( $rMenuList.size() - $(this).index() ) * 50); 
					});

					setTimeout(function(){
						$rMenu.find('.overlay-menu').children('li').removeClass('init-anim');
					}, 400);

					window.openGlobalOverlay(false, $('#responsive-menu'), $(this));
					window.killBodyScrollTheProperWay(false);

				}

			}

			e.preventDefault();

		});

		setTimeout(function(){
			rMenuTouchFix2();
		}, 10);

		if ( ! window.touchM ) {

			$rMenu.find('.menu-item-has-children').on('mouseenter mouseleave', function() {
				rMenuTouchFix();
			});

			$siteNav.find('li.menu-item-has-children').on('mouseenter', function(){
				$siteNav.addClass('subtle-fade');
			}).on('mouseleave', function(){
				$siteNav.removeClass('subtle-fade');
			});

		} else {

			$rMenu.find('.menu-item-has-children').on('touchend', function(e) {

				e.preventDefault();

				rMenuTouchFix();

				var $this = $(this);

				$rMenu.find('.touch-hover').each(function(){
					if ( $(this) != $this ) {
						$(this).removeClass('touch-hover');
					}
				});

				if ( ! $(this).hasClass('touch-hover') ) {
					$(this).addClass('touch-hover');
				} else {
					$(this).removeClass('touch-hover');
				}

			});

			$rMenu.find('.menu-item-has-children .sub-menu').find('a').on('touchend', function(e) {
				e.stopPropagation();
			})

		}

		// stupid hacks :(

		function rMenuTouchFix() {

			clearInterval(rmi);
			clearTimeout(rmt);

			var rmi = setInterval(function(){
				$rMenu.find('.overlay-menu').isotope('layout');
			}, 20);

			var rmt = setTimeout(function(){
				clearInterval(rmi);
			}, 300);

		}

		function rMenuTouchFix2() {
			$rMenu.addClass('show-this-one');
			$rMenu.find('.overlay-menu').isotope({
				itemSelector: '.top-level-item',
				layoutMode: 'fitRows',
				transition: 0
			});
			$rMenu.removeClass('show-this-one');
		}

		var $topMenu = $('.top-menu'),
				$overlayMenu = $('.overlay-menu');

		$(window).on('resize', window.debounce(function(){
			fixSubmenus();
		}, 300));

		function fixSubmenus() {

			$('.top-menu .sub-menu').each(function(){

				var liWidth = 0;
				$(this).find('li').each(function(){
					liWidth += $(this).outerWidth(true);
				})

				if ( $(this).offset().left + liWidth > $topMenu.offset().left + $topMenu.width() ) {
					var ml = ( $(this).offset().left + liWidth ) - ( $topMenu.offset().left + $topMenu.width() ) + 10;
					$(this).attr('style', 'margin-left: -' + ml + 'px !important' );
				} else {
					$(this).css('marginLeft', '0')
				}
			});

			$('.overlay-menu .sub-menu').each(function(){

				var liWidth = 0;
				$(this).find('li').each(function(){
					liWidth += $(this).outerWidth(true);
				})

				if ( $(this).offset().left + liWidth > $overlayMenu.offset().left + $overlayMenu.width() ) {
					var ml = ( $(this).offset().left + liWidth ) - ( $overlayMenu.offset().left + $overlayMenu.width() ) + 40;
					$(this).attr('style', 'margin-left: -' + ml + 'px !important' );
				} else {
					$(this).css('marginLeft', '0')
				}

			});

		}

		fixSubmenus();

		// Here we set a simple scroll helper

		setTimeout(function(){
			$(window).on('resize', function() {
				$rMenu.find('.overlay-menu').isotope('layout');
				$(window).trigger('scroll');
			});
		}, 30);

		// Device helper

		window.addEventListener("orientationchange", function() {
			setTimeout(function() {
				$(window).trigger('resize');
			}, 250);
		});

		// sharing duplicate

		$('#site-share').clone().prependTo('#site-footer');

		// edge fix

		if ( window.detectEdge() ) {
			$('body').addClass('medge-fix')
		}

		// RUN THE SHOW !	

		window.CALAFATE.init();

	});

})(jQuery);

/* These are basic initializations of all the functions needed to run each page.
	We need this as a global object because it will be called after each new AJAX reload.
*/

window.CALAFATE.init = function() {

	var $ = jQuery,
			_f = window.CALAFATE;
			$body = $('body');

	// Sidebar init (only once)

	if ( $('body').hasClass('sidebar-enabled') && ! $('#site-sidebar').hasClass('init') ) {

		var $body = $('body'),
				$siteSidebar = $('#site-sidebar'),
				$siteSidebarOpener = $('#site-sidebar-opener'),
				$siteSidebarCloser = $('#site-sidebar-closer');

		$siteSidebar.addClass('init');

		$siteSidebarOpener.on('click', function() {

			if ( ! $siteSidebarOpener.hasClass('opened') ) {

				window.killBodyScrollTheProperWay(true);
				$siteSidebarOpener.addClass('opened');
				$siteSidebar.addClass('opened');
				$body.addClass('sidebar-opened');

				/*$('#site').on('click.close-overlay', function(e){
					$siteSidebarCloser.trigger('click');
					e.preventDefault();
					e.stopImmediatePropagation(); 
				});*/

			}

		});

		$siteSidebarCloser.on('click', function() {

			if ( $siteSidebarOpener.hasClass('opened') ) {

				$siteSidebarOpener.removeClass('opened');
				$siteSidebar.removeClass('opened');
				$body.removeClass('sidebar-opened');
				window.killBodyScrollTheProperWay(false);

				//$('#site').off('click.close-overlay');

			}

		});

		// AJAX

		$siteSidebar.find('a').each(function(){

			if ( ! $(this).hasClass('ajax-link') && $(this).attr('target') !== '_blank' ) {

				var href = $(this).attr('href');

				if ( href !== undefined && href.indexOf(window.location.hostname) > 0 && href.indexOf(window.location.hostname) < 10 && href.indexOf('wp-admin') <= 0 && href.indexOf('wp-login') <= 0 && href.indexOf('#') <= 0 ) {
					$(this).addClass('ajax-link');
				}

			}

		});

		$siteSidebar.find('a.ajax-link').addClass('immediate-propagation');
		$siteSidebar.find('a.ajax-link').on('click', function(e) { 
			$siteSidebarCloser.trigger('click');
		});

		$siteSidebar.find('.ajax-form').on('submit', function(e) {
			$siteSidebarCloser.trigger('click');
		});

	}

	// WooCommerce ajax links

	if ( $('body').hasClass('woocommerce-page') ) {
		$('.woocommerce-breadcrumb a, .shop_table.cart a:not(.remove)').addClass('ajax-link');
	}

	// INIT EVERYTHING !!! 

	$.ajaxSetup({
	  cache: true
	});

	if ( $body.hasClass('blog') || $body.hasClass('archive') || $('.blog-shortcode').length > 0 ) {
		_f.Blog.mount();
	}

	if ( $body.hasClass('single-post') ) {
		_f.Post.mount();
	}

	if ( $body.hasClass('page-template-template-cover') ) {
		_f.Page.mountCovers();
	}

	// yeah, i really wrote this :)
	if ( $body.hasClass('page-template-template-portfolio') || ( $body.hasClass('woocommerce-page') && $('.portfolio-grid').length > 0 ) || ( $body.hasClass('blog') && $('.portfolio-grid').length > 0 && $('.blog-posts-carousel').length <= 0 ) || $('.portfolio-grid.shortcode').length > 0 ) {
		
		_f.Portfolio.mount($('.portfolio-grid:not(.shortcode)'));

		if ( $('.portfolio-grid.shortcode').length > 0 ) {
			$('.portfolio-grid.shortcode').each(function(){
				_f.Portfolio.mount($(this));
			})
		}

	}

	if ( ! $body.hasClass('woocommerce-page') && $('.portfolio-grid.woocommerce-grid').length > 0 ) {
		_f.Portfolio.mount($('.portfolio-grid.woocommerce-grid'));
	}

	if ( $('.hero-header').length > 0 ) {

		// delay loading on pages with a hero element

		_f.Hero.mount(null, function() {

			siteIN();

			setTimeout(function(){

				_f.Page.mount();
				_f.Woo.mount();

				if ( themeSettings.ajax === 'enabled' ) {
					_f.Ajax.init();
				}

			}, 500);

		});

	} else if ( $('.blog-posts-carousel').length > 0 ) {

		$('.blog-posts-carousel').addClass('enabled');

		// delay loading on pages with blog posts carousel

		var carouselI = setInterval(function(){

			if ( $('.blog-posts-carousel').hasClass('flickity-enabled') ) {

				clearInterval(carouselI);

				setTimeout(function(){

					_f.Page.mount();
					_f.Portfolio.mount();
					_f.Woo.mount();

					siteIN();
					
					if ( themeSettings.ajax === 'enabled' ) {
						_f.Ajax.init();
					}

				}, 1000);

			}

		}, 200);

	} else {

		_f.Page.mount();
		_f.Woo.mount();

		siteIN();
		
		if ( themeSettings.ajax === 'enabled' ) {
			_f.Ajax.init();
		}

	}

}

function siteIN() {

	setTimeout(function(){
		$('#site').removeClass('out');
	}, 50);

	setTimeout(function(){
		$('#site').removeClass('animate');
	}, 600);

	$('#preloader').addClass('all-aboard');

}
