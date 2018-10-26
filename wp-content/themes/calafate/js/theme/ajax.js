var $ = jQuery;

/* Ajax functions */

window.CALAFATE.Ajax = {

	pageData: null,
	pageLink: null,
	imgLogo: $('#site-logo').hasClass('image-logo-enabled') ? true : false,

	validRequest: true,

	lateInit: function($link) {

		if ( ! $link.hasClass('boohw') ) {

			$link.addClass('boohw');	

			$link.on('click', (function(e) {

				e.preventDefault();
				if ( this.validRequest ) {

					this.validRequest = false;
					setTimeout((function(){
						this.validRequest = true;
					}).bind(this), 250);

					if (e.which === 2 || e.metaKey || e.shiftKey || navigator.platform.toUpperCase().indexOf('WIN') !== -1 && e.ctrlKey) {

						window.open($(e.currentTarget).attr('href'), '_blank');
						
					} else {

						this.pageLink = $(e.currentTarget).attr('href');
						this._preloadPage();

						e.preventDefault();
						
						if ( ! $(e.currentTarget).hasClass('immediate-propagation') ) {
							e.stopImmediatePropagation(); 
						}

					}

				}

			}).bind(this));

		}

	},

	init: function() {

		this._initPagePreloading();

		// on click take the proposed url and call the loading function

		$('.ajax-link').each(function(){
			if ( ! ( $(this).hasClass('no-ajax-link') || $(this).attr('target') == '_blank' ) ) {
				window.CALAFATE.Ajax.lateInit($(this));
			}
		});

		// init ajax forms

		$('.searchform:not(.ajax-form)').each(function(){

			$(this).addClass('ajax-form');

			$(this).on('submit', (function(e) {

				var $form = $(e.currentTarget);

				if ( $form.find('#s').val() != '' && $form.find('#s').val() != ' ' ) {

					$('html, body').stop().animate({scrollTop: 0}, 300);
					this.pageLink = $form.attr('action') + '?s=' + $form.find('#s').val();
					this._preloadPage();

					setTimeout(function(){
						$form.find('#s').val('');
					}, 500);

				}

				if( $('#site-actions-search.opened').length > 0 ) {
					$('#site-actions-search').trigger('click');
				} else if ( $('.responsive-search.opened').length > 0 ) {
					$('.responsive-search').trigger('click');
				}

				if ( $('body').hasClass('sidebar-opened') ) {
					$('#site-sidebar-closer').trigger('click');
				}

				e.preventDefault();

			}).bind(window.CALAFATE.Ajax));

		});

		/* -- BUGGY ! It doesn't work in Chrome :( 

			if ( $('.calafate-pwd').length > 0 ) {

			var $pwdHolder = $('.calafate-pwd'),
					$pwdForm = $('.calafate-pwd form');

			$pwdForm.on('submit', (function(e) {

				$pwdForm.addClass('loading');

				$.ajax({
				  type: $pwdForm.prop('method'),
				  url: $pwdForm.prop('action'),
				  data: $pwdForm.serialize(),
				  success: function(data){

				  	var $data = $(data);

				  	if ( $data.find('.calafate-pwd').length > 0 ) {

				  		$pwdForm.find('.view-info').html($pwdForm.find('.view-info').data('error'));
				  		$pwdForm.removeClass('loading');

				  	} else {

				  		$('.entry-content').html($(data).find('.entry-content'));
				  		window.CALAFATE.init();

				  		setTimeout(function(){
				  			$pwdHolder.fadeOut();
				  			$('body').removeClass('pwd');
				  		}, 500);

				  	}

					}
				});

			e.preventDefault();

		}).bind(window.CALAFATE.Ajax));

		}*/

	},

	_preloadPage: function() {

		var _f = window.CALAFATE;
		var $body = $('body');

		// 1. unmount everything

		if ( $body.hasClass('page-template-template-portfolio') || ( $body.hasClass('woocommerce-page') && $('.portfolio-grid').length > 0 ) || ( $body.hasClass('blog') && $('.portfolio-grid').length > 0 ) || $('.portfolio-grid.shortcode').length > 0 ) {
			_f.Portfolio.unmount();
		}
		if ( $body.hasClass( 'blog' ) ) {
			_f.Blog.unmount();
		} 
		if ( $body.hasClass( 'single' ) ) {
			_f.Post.unmount();
		}

		if ( $('.hero-header').length > 0 ) {
			_f.Hero.unmount();
		}

		_f.Page.unmount();
		_f.Woo.unmount();

		//$('#primary').addClass('close');

		$('#site').addClass('animate');
		setTimeout(function(){
			$('#site').addClass('out');
		}, 25);

		// 2. load new page and set mounting flag after unmounting is finished

		this._loadPage();

		setTimeout((function() {
			$body.addClass('unmounting-finished');
			if ( this.pageData !== null ) {
				this._mountNewPage();
			}
			$('html, body').stop().animate({'scrollTop': 0}, 0);
		}).bind(this), 500);

		// 3. show preloader

		setTimeout(function(){
			$('#preloader').removeClass('all-aboard');
		}, 300);

		// 4. do reveal animations

		setTimeout(function(){
			//window.killBodyScrollTheProperWay(false);
			//$('html, body').stop().animate({'scrollTop': 0}, 0);
		}, 400);

	},

	_loadPage: function() {

		var startTime = new Date().getTime();

		if ( window.siteCache[this.pageLink] !== undefined && window.siteCache[this.pageLink].content !== null ) {

			this.pageData = window.siteCache[this.pageLink].content;
			if ( $('body').hasClass('unmounting-finished' ) ) {
				this._mountNewPage();
			}

		} else {

			$.ajax({
				url: this.pageLink,
				dataType: 'text', 
				global: false,
				success: (function( data ) {

					var endTime = new Date().getTime();

					// after data is loaded mount the page if unmounting is finished

					this.pageData = data;
					if ( $('body').hasClass('unmounting-finished' ) ) {
						this._mountNewPage();
					}

				}).bind(this),
				error: (function( error ) {

					if ( error.status === 404 ) {

						// simple hack to load the custom 404 page via ajax

						this.pageData = error.responseText;
						if ( $('body').hasClass('unmounting-finished' ) ) {
							this._mountNewPage();
						}

					}

				}).bind(this)
			});

		}

	},

	_mountNewPage: function() {

		var data = this.pageData;

		// 1. page header

		// save this reject for future use: (<script[^>]*>[^<]*(?:(?!<\/?script)<[^<]*)*<\/script>*)

		var newHead = data.match(/<head[^>]*>([^<]*(?:(?!<\/?head)<[^<]*)*)<\/head\s*>/i);

		if ( newHead ) {

			$('#head').find('meta, title, link:not([id]), style, script').remove();

			$(newHead[1]).each(function(){

				if ( $(this)[0].nodeName !== 'LINK' || ( $(this)[0].nodeName === 'LINK' && $(this).attr('id') === undefined ) ) {
					if ( ! ( $(this)[0].src !== undefined && $(this)[0].src.indexOf('jquery') > 0 ) ) {
						$('#head').append($(this));
					} 
				}

			});

		}

		// 2. body class

		var oldBodyBG = $('body').css('backgroundColor'),
				oldBodyTXT = $('#preloader span').css('color');

		if ( $('#body-old-color').length > 0 ) 
			$('#body-old-color').remove();

		$('#head').append('<style id="body-old-color">body.hero-1.before { background: ' + oldBodyBG + '; } body.hero-1.before #preloader span { color: ' + oldBodyTXT + '; }</style>');
		var newBodyClass = data.match(/<body[^>]*class="(.+)"/i);

		if ( newBodyClass ) {

			// portfolio grid fix

			if ( $('body').hasClass('sidebar-enabled') && ( newBodyClass[1].indexOf('template-portfolio') > 0 || newBodyClass[1].indexOf('woocommerce-page') > 0  ) ) {
				setTimeout(function(){
					$(window).trigger('resize');
				}, 400);
			}

			// body class change

			var sidebarClass = '';
			if ( $('body').hasClass('sidebar-opened') ) {
				sidebarClass = 'sidebar-opened';
			}
			$('body').attr('class', newBodyClass[1] + ' ' + sidebarClass);

		}
		$('body').removeClass('very-first-init');

		// 3. inner content

		var $data = $(data);

		// - data
		
		window.checkForPwd('close');
		$('#content').html($data.find('#content').html());
		window.checkForPwd('open');

		// - hero

		var newHero = false;

		if ( $('.hero-header').length > 0 ) {
			$('.hero-header').remove();
		}
		if ( $data.find('.hero-header').length > 0 ) {
			newHero = true;
			$('#site').prepend($data.find('.hero-header'));
		}
		
		$('.site-content').css('paddingTop', 0);

		// - js init

		setTimeout(function(){
			window.CALAFATE.init();
		}, 50);

		// - new logo injection

		if ( this.imgLogo && $data.find('.site-logo img').attr('src') != $('.site-logo img').attr('src') ) {
			$('.site-logo h2').html($data.find('.site-logo img'));
		}

		// - rewrite menu classes

		$('.site-navigation').find('.current-menu-item').removeClass('current-menu-item');
		$('.site-navigation').find('.current-menu-parent').removeClass('current-menu-parent');

		$('.overlay-menu').find('.current-menu-item').removeClass('current-menu-item');
		$('.overlay-menu').find('.current-menu-parent').removeClass('current-menu-parent');

		var $newMenu = $data.find('.site-navigation');
		$newMenu.find('.current-menu-item').each(function(){
			$('#'+$(this).attr('id')).addClass('current-menu-item');
		});

		$newMenu.find('.current-menu-parent').each(function(){
			$('#'+$(this).attr('id')).addClass('current-menu-parent');
		});

		setTimeout(function(){
			$('#responsive-menu').addClass('show-this-one');
			$('#responsive-menu').find('.overlay-menu').isotope();
			$('#responsive-menu').removeClass('show-this-one');
		}, 10);

		// - sidebar

		if ( $('body').hasClass('sidebar-enabled') && $('#site-sidebar').length <= 0 ) {
			// add
			$('#site').after('<div id="site-sidebar" class="out"> ' + $data.find('#site-sidebar-wrap').html() + '</div>');
			setTimeout(function(){
				$('#site-sidebar').removeClass('out');
			}, 200);
		} else if ( ! $('body').hasClass('sidebar-enabled') && $('#site-sidebar').length > 0 ) {
			// remove
			$('#site-sidebar').addClass('out')
			setTimeout(function(){
				$('#site-sidebar').remove();
			}, 200);
		}

		// - add/remove filters opener

		if ( $newMenu.find('.open-filters').length > 0 && $('.site-navigation').find('.open-filters').length == 0 ) {

			$newMenu.find('.open-filters').insertAfter($('.site-navigation .responsive-nav'));
			$('.site-navigation').find('.open-filters')
				.css('margin-right', -47)
				.animate({'marginRight': 0, opacity: 1}, 100);

		} else if ( $newMenu.find('.open-filters').length == 0 && $('.site-navigation').find('.open-filters').length > 0 ) {

			$('.site-navigation').find('.open-filters')
				.animate({'marginRight': -47, opacity: 0}, 200, function(){
					$(this).remove()
				});

		}

		if ( $('#portfolio-filters').length > 0 )
			$('#portfolio-filters').remove();

		if ( $data.find('#portfolio-filters').length > 0 ) 
			$('#site-overlay').prepend($data.find('#portfolio-filters')); 

		$('#site-overlay').removeClass('show-overlay');

		// 4. page sharing / actions & wp admin bar
		
		$('.site-share .tw').attr('href', $data.find('.site-share .tw').attr('href'));
		$('.site-share .fb').attr('href', $data.find('.site-share .fb').attr('href'));
		$('.site-share .pin').attr('href', $data.find('.site-share .pin').attr('href'));

		$('#site-actions').html($data.find('#site-actions-holder').html());
		
		$('#wp-toolbar').html($data.find('#wp-toolbar').html());

		// 5. history api

		if ( typeof history !== 'undefined' ) {

			if ( this.pageLink !== window.location.href ) {
				window.blockhashchange = true;
				history.pushState(null, '', this.pageLink);
			}

			window.onpopstate = (function(e) {
				if ( window.blockhashchange ) {
					window.blockhashchange = false;
				} else {
					this.pageLink = window.location.href;
					this._preloadPage();
				}
			}).bind(this);

			setTimeout(function(){
				window.blockhashchange = false;
			}, 1000);

		}

		// 6. google analytics

		if ( typeof ga !== 'undefined' ) { 
		  ga('send', 'pageview', {
				'page': document.location.href,
				'title': document.title
      });
		}

		// 7. reset

		this.pageData = null;
		this.pageLink = null;

	},

	// WIP - Experimental page & hero prefetch

	_initPagePreloading: function() {

		var ajaxApp = this;

		if ( $('body').hasClass('page-template-template-portfolio') ) {
			$('.entry-portfolio .ajax-link').each(function(){
				ajaxApp._sendToPrefetch($(this), ajaxApp);
			});
		} else if ( $('body').hasClass('blog') ) {
			$('.entry-minimal .ajax-link').each(function(){
				ajaxApp._sendToPrefetch($(this), ajaxApp);
			});
		} else if ( $('body').hasClass('single-portfolio') && $('.entry-navigation__item').length > 0) {
			ajaxApp._sendToPrefetch($('.entry-navigation__item'), ajaxApp);
		}

		$('#site-navigation .top-menu .ajax-link').each(function(){
			ajaxApp._sendToPrefetch($(this), ajaxApp);
		});

	},

	_sendToPrefetch: function($this, ajaxApp) {

		var href = $this.attr('href'),
				img = $this.find('.prefetch-hero').length > 0 ? $this.find('.prefetch-hero .media.image') : null;

		$this.on('mouseenter', function(){
			ajaxApp._prefetch(href, img);
		});

		setTimeout(function(){
			//ajaxApp._prefetch(href, img);
		}, $this.parent().index() * 5000 + 5000);

	},

	_prefetch: function(href, img) {

		if ( window.siteCache[href] === undefined ) {

			window.siteCache[href] = {
				loaded: false,
				content: null
			};	

			window.siteCache[href]['pageRequest'] = $.ajax({
				url: href,
				dataType: 'text', 
				global: false,
				success: (function(placeInCache, data){
					placeInCache.loaded = true;
					placeInCache.content = data;
				}).bind(null, window.siteCache[href])

			});

			if ( img !== null ) {

				var preloadHero = new Image();
				preloadHero.src = window._srcsetBg(img, $(window));
				preloadHero.onload = function(){
					// nothing
				}

			}

		}

	}

}