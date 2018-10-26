var $ = jQuery;

window.clftpi = 0;

/* Portfolio & portfolio item functions */

window.CALAFATE.Portfolio = {

	gridLoadingI: null,

	mount: function( $elm ) {

		this.mounted = true;

		var $portfolioGrid = $elm || $('.portfolio-grid');

		if ( $portfolioGrid.length <= 0 || $portfolioGrid.hasClass('onboy') ) 
			return;

		instance = window.clftpi;
		$portfolioGrid.addClass('onboy').addClass('index-' + instance);
		window.clftpi += 1;

		$portfolioGrid.append('<div class="grid-sizer" /><div class="grid-gutter" />');

		// Get initial category

		var initFilter = document.location.hash;
		if ( $('#portfolio-filters').length > 0 && $('#portfolio-filters').find('a[href="' + initFilter + '"]').length > 0 ) {

			setTimeout((function($btn){
				$('#portfolio-filters li').find('a.selected').removeClass('selected');
				$btn.addClass('selected');
				$(window).trigger('scroll.portfolio');
			}).bind(this, $('#portfolio-filters').find('a[href="' + initFilter + '"]')), 400);

			if ( initFilter != '#' ) {
				initFilter = initFilter.replace('#', '.');
			} else {
				initFilter = '*';
			}

		} else {
			window.blockhashchange = true;
			document.location.hash = '';
		}

		if ( initFilter == '' ) {
			initFilter = '*';
		}

		// Init grid in isotope

		$portfolioGrid.data('columns-default', $portfolioGrid.data('columns'));

		var columnWidth = this._getColumnWidth($portfolioGrid),
				columnGap = $portfolioGrid.data('gap');

		if ( ! $portfolioGrid.hasClass('blog') ) {

			$('<style id="portfolio-gap-style">.portfolio-grid.index-' + instance + ' { margin-top: -' + columnGap + 'px; margin-left: -' + columnGap + 'px; width: calc(100% + ' + columnGap + 'px); } .portfolio-grid.index-' + instance + ' .entry-portfolio { clip: rect(' + columnGap + 'px 1440px 1440px ' + columnGap + 'px); } .portfolio-grid[data-columns="' + $portfolioGrid.data('columns-default') + '"] .entry-portfolio[data-size="' + $portfolioGrid.data('columns-default') + '"] { clip: rect(' + columnGap + 'px ' + ( 1440 + columnGap ) + 'px ' + ( 1440 + columnGap ) + 'px ' + columnGap + 'px) !important; } .portfolio-grid.index-' + instance + ' .entry-portfolio .entry-thumbnail img { transform: translate(' + Math.round( columnGap / 2 ) + 'px, ' + Math.round( columnGap / 2 ) + 'px); } .portfolio-grid.index-' + instance + ' .entry-portfolio.product .entry-info { left: ' + columnGap + 'px; width: calc(100% - ' + columnGap + 'px); top: ' + columnGap + 'px; height: calc(100% - ' + columnGap + 'px); } .portfolio-grid.index-' + instance + ' .entry-portfolio.hover-two .entry-info { padding-bottom: ' + columnGap + 'px; } .portfolio-grid.index-' + instance + ' .entry-portfolio.hover-two .entry-thumbnail .price, .portfolio-grid.index-' + instance + ' .entry-portfolio.hover-two .entry-caption { left: ' + columnGap + 'px; bottom: -' + Math.round( columnGap / 2 ) + 'px; } .portfolio-grid.index-' + instance + ' .entry-portfolio.hover-two .entry-caption { padding-top: ' + Math.round( columnGap / 2 ) + 'px; }</style>').insertBefore($portfolioGrid);

		} else if ( $portfolioGrid.hasClass('blog') ) {

			$('<style id="portfolio-gap-style">.blog.entries--grid { margin: 0 0 0 -' + (columnGap/2) + 'px !important; width: calc(100% + ' + columnGap + 'px) !important; } .blog .entry-portfolio { padding: 0 ' + (columnGap/2) + 'px; max-width: 100%; }</style>').insertBefore($portfolioGrid);

		}

		$('.portfolio-grid.index-' + instance + ' .grid-sizer').css('width', columnWidth);
		$('.portfolio-grid.index-' + instance + ' .grid-gutter').css('width', 0);

		$portfolioGrid.find('.entry-portfolio').hide();

		$portfolioGrid.isotope({
			itemSelector: '.entry-portfolio',
			layoutMode: 'packery',
			packery: {
		    columnWidth: '.grid-sizer',
			},
		  transitionDuration: 0,
		  percentPosition: true,
		  filter: $portfolioGrid.hasClass('main-grid') ? initFilter : '*'
		});

		if ( $portfolioGrid.hasClass('mobile-style-always') && touchM ) {

			var originalCaption = $portfolioGrid.hasClass('caption-style-Minimal') ? 'minimal' : 'classic',
					captionSwitch = false;

			$(window).on('resize', function(){

				if ( $(window).width() < 1024 && ! captionSwitch ) {

					captionSwitch = true;

					$portfolioGrid.removeClass('caption-style-Minimal').removeClass('caption-style-Classic');
					$portfolioGrid.addClass('caption-style-hover-two');

					$('.entry-portfolio').removeClass('Minimal').removeClass('Classic');
					$('.entry-portfolio').addClass('hover-two');

					$('.entry-caption').removeClass('Minimal').removeClass('Classic');
					$('.entry-caption').addClass('hover-two');

				} else if ( $(window).width() >= 1024 && captionSwitch ) {

					captionSwitch = false;

					$portfolioGrid.removeClass('caption-style-hover-two');
					$('.entry-portfolio').removeClass('hover-two');
					$('.entry-caption').removeClass('hover-two');

					if ( originalCaption == 'minimal' ) { 

						$portfolioGrid.addClass('caption-style-Minimal');
						$('.entry-portfolio').addClass('Minimal');
						$('.entry-caption').addClass('Minimal');

					} else {

						$portfolioGrid.addClass('caption-style-Classic');
						$('.entry-portfolio').addClass('Classic');
						$('.entry-caption').addClass('Classic');

					}

				}

			}).trigger('resize');

		}

		if ( $('.entry-portfolio.hover-two').length > 0 ) {
			$('.entry-portfolio.hover-two').each(function(){
				$(this).find('.entry-title').insertBefore($(this).find('.entry-meta'));
			});
		}

		if ( $portfolioGrid.hasClass('mobile-style-tap') && window.touchM ) {

			$('.entry-portfolio a').each(function(){

				PreventGhostClick($(this)[0]);

				$(this).hammer().bind('tap', function(e){

					//e.preventDefault();

					if ( ! $(this).hasClass('hover') ) {
						$(this).addClass('hover');
						$('.entry-portfolio:not(#' + $(this).parent().attr('id') + ') a').removeClass('hover');
					} else {
						if ( $(this).hasClass('fancybox') ) {
							$(this).click();
						} else if ( themeSettings.ajax === 'enabled' && $(this).attr('target') != '_blank' ) {
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

		}

		// Add resizing event

		$(window).on('resize.portfolio', function(){

			var columnWidth = window.CALAFATE.Portfolio._getColumnWidth($portfolioGrid);
			$portfolioGrid.find('.grid-sizer').css('width', columnWidth);

			$portfolioGrid.find('.entry-portfolio').each(function() {
				window.CALAFATE.Portfolio._setGridItemSize($(this), $portfolioGrid);
			});

			$portfolioGrid.isotope({
	  		transitionDuration: 0
	  	});

		});

		// Grid loading function (inits images)

		var gridLoading = {};

		gridLoading.gridLoadingArray = [];
		gridLoading.gridLoadingIndex = 0;
		gridLoading.gridLoadingI = null;

		$portfolioGrid.data('gridLoading', gridLoading);

		$portfolioGrid.children('.entry-portfolio').each(function() {

			var $item = $(this),
					$img = $item.find('img');

				if ( $img.attr('src') !== '' ) {
					$portfolioGrid.data('gridLoading').gridLoadingArray.push({
						'img': $img[0],
						'parent': $item,
					});
				}

			$item.css({
				'width': window.CALAFATE.Portfolio._getItemWidth($item, $portfolioGrid)
			});

		});

		if ( initFilter !== '*' ) {
			$portfolioGrid.data('gridLoading').gridLoadingArray = $portfolioGrid.data('gridLoading').gridLoadingArray.sort(function(a, b) {
				if ( $(a.parent).hasClass(initFilter.replace('.', '')) ) {
					return -1;
				}
			});
		}


		var j = 0;

		var columnWidth = this._getColumnWidth($portfolioGrid);
		$portfolioGrid.find('.grid-sizer').css('width', columnWidth);

		$portfolioGrid.data('gridLoading').gridLoadingArray.forEach(function(image){

			$(image.img).attr('srcset', '');

			if ( window.devicePixelRatio > 1 ) {
				$(image.img).attr('src', $(image.img).data('src-retina'));
			}

		});

		/* if ( typeof window.requestAnimationFrame == 'function' ) {
			function repeatOften() {
			  // Do whatever
			  requestAnimationFrame(repeatOften);
			}
			requestAnimationFrame(repeatOften);
		} */

		$portfolioGrid.data('gridLoading').gridLoadingI = setInterval((function() {
			this.__gridLoading($portfolioGrid, initFilter, j, gridLoading);
		}).bind(this), 50);

		// SCROLL ?!

		$(window).on('scroll.portfolio', function(e) {

			// content animation

			var i = 0,
				timeArray = [];

			$($portfolioGrid.find('.entry-portfolio')).each(function() {	
				if ( $(this).hasClass('loaded') ) {
					if ( ( $(window).height() + $(window).scrollTop() > $(this).offset().top ) && ! $(this).hasClass('active') ) {
						clearTimeout(timeArray[$(this).index()]);
						timeArray[$(this).index()] = setTimeout((function() {
							$(this).addClass('active');
						}).bind(this), i++*100);
					} 
				}
			});

		});

		// Init filtering

		if ( $('#portfolio-filters').length > 0 ) {

			var $portfolioFilters = $('#portfolio-filters'),
					$portfolioFiltersList = $portfolioFilters.find('li'),
					$portfolioFiltersLink = $('.site-navigation .open-filters'),

					veryFirstFilter = true,
					firstFilter = true,
					filterEnabled = true;

			$portfolioFilters.addClass('init');

			$('.site-navigation .open-filters').off('click touchstart');
			$('.site-navigation .open-filters').on('click touchstart', function(e) {

				var $filteredGrid = $('.portfolio-grid.main-grid');

				if ( filterEnabled && $filteredGrid.length > 0 ) {

					filterEnabled = false;
					setTimeout(function(){
						filterEnabled = true;
					}, 200);

					if ( firstFilter ) {

						$($filteredGrid.isotope('getItemElements')).each(function() {
							$(this).removeClass('uninit');
						});

					}

					if ( ! $portfolioFilters.hasClass('active') ) {

						$portfolioFilters.addClass('active');
						$portfolioFiltersList.each(function(){
							setTimeout((function(){
								$(this).addClass('active');
							}).bind(this), $(this).index() * 50 + 100); 
						});

						window.openGlobalOverlay(true, $('#portfolio-filters'), $(this));
						window.killBodyScrollTheProperWay(true);

					} else {

						$portfolioFilters.removeClass('active');
						$portfolioFiltersList.each(function(){
							setTimeout((function(){
								$(this).removeClass('active');
							}).bind(this), ( $portfolioFiltersList.size() - $(this).index() ) * 50); 
						});

						setTimeout(function(){
							firstFilter = true;
							$($filteredGrid.isotope('getItemElements')).each(function() {
								$(this).addClass('uninit');
							});
						}, 500);

						window.openGlobalOverlay(false, $('#portfolio-filters'), $(this));
						window.killBodyScrollTheProperWay(false);

					}

					if ( veryFirstFilter ) {

						veryFirstFilter = false;
						
						$portfolioFilters.append('<div class="filters-images" />');
						$portfolioFiltersList.each(function(){

							$('.filters-images').append('<div class="img" ' + ( $(this).data('img') ? ' style="background-image: url(' + $(this).data('img') + ')"' : '' ) + '/>');

							$(this).on('mouseenter', function(e){
								$('.filters-images').children('div').eq($(this).index()).addClass('active');
							}).on('mouseleave', function(e){
								$('.filters-images').children('div').eq($(this).index()).removeClass('active');
							});

						});

					}

				}

				e.preventDefault();

			});

			$('#portfolio-filters').find('a').on('click', function(e) {

				e.preventDefault();

				var $filteredGrid = $('.portfolio-grid.main-grid');

				if ( $filteredGrid.length > 0 ) {

					$filteredGrid.isotope({ 
						filter: $(this).data('filter'),
			  		transitionDuration: window.touchM ? 0 : 400
			  	});

					$portfolioFiltersLink.trigger('click');

					setTimeout((function(){
						$portfolioFiltersList.find('a.selected').removeClass('selected');
						$(this).addClass('selected');
						$(window).trigger('scroll.portfolio');
						$(window).trigger('resize.portfolio');
					}).bind(this), 500);

					$('html, body').stop().animate({scrollTop: 0}, 300);

					window.blockhashchange = true;
					document.location.hash = $(this).attr('href');

				}

			});

		}

		// Portfolio caption animations

		if ( $portfolioGrid.hasClass('caption-style-Minimal') ) {

			// MINIMAL

			$('body').append('<div id="js-caption" class="entry-caption Minimal"><div class="entry-caption-text"><span class="entry-meta"></span><h3 class="entry-title"></h3></div></div>');

			var $jsCaption = $('#js-caption'),
					$jsCaptionMeta = $jsCaption.find('.entry-meta'),
					$jsCaptionTitle = $jsCaption.find('.entry-title');

			$portfolioGrid.on('mousemove', function(e) {

				$jsCaption.css({
					top: e.clientY,
					left: e.clientX
				});

			});

			$portfolioGrid.find('.entry-portfolio a').on('mouseover', function(e) {

				$jsCaptionMeta.text($(this).find('.entry-meta').text());
				$jsCaptionTitle.text($(this).find('.entry-title').text());

				setTimeout(function() {
					$jsCaption.addClass('active').attr('data-id', $portfolioGrid.data('id'))
				}, 1);

			}).on('mouseout', function(e) {

				$jsCaption.removeClass('active');

			});

		} else if ( $portfolioGrid.hasClass('caption-style-huge') ) {

			// HUGE

			$('body').append('<div class="huge-caption"></div>');

			var $hugeCaption = $('.huge-caption');

			$portfolioGrid.find('.entry-portfolio .entry-caption').each(function(){
				$hugeCaption.append($(this));
			});

			/*$portfolioGrid.on('mouseenter', function(e) {
				$(this).addClass('on');
			}).on('mouseleave', function(e) {
				$(this).removeClass('on');
			});*/

			$portfolioGrid.find('.entry-portfolio a').on('mouseenter', function(e) {
				$hugeCaptionChildren.eq($(this).parent().index()).addClass('hover');
				$portfolioGrid.addClass('on');
			}).on('mouseleave', function(e) {
				$hugeCaptionChildren.eq($(this).parent().index()).removeClass('hover');
				$portfolioGrid.removeClass('on');
			}).on('click', function(){
				$hugeCaptionChildren.eq($(this).parent().index()).removeClass('hover');
				$portfolioGrid.removeClass('off');

			});

			var $hugeCaptionChildren = $hugeCaption.children();

		}

	},

	unmount: function() {

		var $portfolioGrid = $('.portfolio-grid');
		$(window).off('scroll.portfolio');
		$(window).off('resize.portfolio');
		$('#portfolio-filters').find('a').off('click');
		clearInterval($portfolioGrid.data('gridLoading').gridLoadingI);

		/*setTimeout(function(){
			$portfolioGrid.isotope('destroy');
		}, 500);*/

		$('#js-caption').stop().fadeOut(100, function(){
			$(this).remove();
		});

		this.mounted = false;

		setTimeout(function(){
			if ( $('.huge-caption').length > 0 ) {
				$('.huge-caption').remove();
			}
		}, 500);

	},

	__gridLoading: function($portfolioGrid, initFilter, j, gridLoading) {

		var image = $portfolioGrid.data('gridLoading').gridLoadingArray[$portfolioGrid.data('gridLoading').gridLoadingIndex];

		// some kind of lazy loading :))

  	if ( typeof image != 'undefined' ) {

			if ( image.img.complete ) {

				if ( initFilter === '*' || image.parent.hasClass(initFilter.replace('.', '')) ) {
					image.parent.show();
					image.parent.addClass('loaded');
				}

				$portfolioGrid.isotope('layout');	

				if ( image.parent.offset().top < $(window).height() + $(window).scrollTop() ) {
					setTimeout((function() {
						image.parent.addClass('active');
						$portfolioGrid.isotope('layout');	
					}).bind(this), j++*100);
				}

				this._setGridItemSize(image.parent, $portfolioGrid);
				$portfolioGrid.isotope({
		  		transitionDuration: 0
		  	});

		  	if ( image.parent.find('.secondary').length > 0 ) {
		  		
		  		var $secImg = image.parent.find('.secondary');

					$secImg.attr('srcset', '');

					if ( window.devicePixelRatio > 1 ) {
						$secImg.attr('src', $secImg.data('src-retina'));
					}

		  	}

				$portfolioGrid.data('gridLoading').gridLoadingIndex++;

			} 

		} else {
			
			$portfolioGrid.data('gridLoading').gridLoadingIndex++;
			
		}

		if ( $portfolioGrid.data('gridLoading').gridLoadingIndex == $portfolioGrid.data('gridLoading').gridLoadingArray.length ) {

			clearInterval( $portfolioGrid.data('gridLoading').gridLoadingI );

			$portfolioGrid.addClass('no-min-height');
			setTimeout(function(){
				$(window).trigger('scroll');
			}, 400);

			if ( $('.post-navigation').length > 0 ) {
				setTimeout(function(){
					$('.post-navigation').css('display', 'block').removeClass('active');
					setTimeout(function(){
						$('.post-navigation').addClass('active');
					}, 50);
				}, 350);
			}

		}

	},

	_getColumnWidth: function( $grid, withoutGap ) {

		// responsive columns

		if ( $grid.data('columns-default') === 2 ) {

			if ( $(window).width() < 640 ) {
				$grid.data('columns', 1);
			} else {
				$grid.data('columns', 2);
			}

		}	else if ( $grid.data('columns-default') === 3 ) {

			if ( $(window).width() < 570 ) {
				$grid.data('columns', 1);
			} else if ( $(window).width() < 970 && ! $grid.hasClass('woocommerce-grid') ) {
				$grid.data('columns', 2);
			} else {
				$grid.data('columns', 3);
			}

		} else if ( $grid.data('columns-default') === 4 || $grid.data('columns-default') === 5 || $grid.data('columns-default') === 6 ) {

			if ( $(window).width() < 520 ) {
				$grid.data('columns', 1);
			} else if ( $(window).width() < 760 ) {
				$grid.data('columns', 2);
			} else if ( $(window).width() < 1000 ) {
				$grid.data('columns', 3);
			} else if ( $grid.data('columns-default') === 4 || ( ( $grid.data('columns-default') === 5 || $grid.data('columns-default') === 6 ) && $(window).width() < 1380 ) ) {
				$grid.data('columns', 4);
			} else if ( $grid.data('columns-default') === 5 || ( $grid.data('columns-default') === 6 && $(window).width() < 1680 ) ) {
				$grid.data('columns', 5);
			} else if ( $grid.data('columns-default') === 6 ) {
				$grid.data('columns', 6);
			}

		}

		// return

		return Math.floor( $grid.width() / $grid.data('columns') );

	},

	_getItemWidth: function( $item, $grid ) {
		var columnWidth = this._getColumnWidth( $grid, true );
		return $item.data('size') * columnWidth;
	},

	_setGridItemSize: function($item, $grid) {

		var img = $item.find('img')[0];
		var newItemWidth = this._getItemWidth($item, $grid);
		var newItemHeight = 0;

		$item.css('width', newItemWidth);

		if ( ! $grid.hasClass('blog') ) {
			var realItemWidth = $item.width();
			var ratio = parseInt($(img).data('width')) && parseInt($(img).data('height')) ? ( $(img).data('height') / $(img).data('width') ) : ( img.naturalHeight / img.naturalWidth );
			newItemHeight = Math.round(realItemWidth * ratio);
			$item.css('height', newItemHeight);
		}

		if ( ( newItemWidth < 360 || newItemHeight < 320 ) && ! $item.hasClass('small-thumb') ) {
			$item.addClass('small-thumb')
		} else if ( newItemWidth >= 360 && $(this).hasClass('small-thumb') ) {
			$item.removeClass('small-thumb');
		}

	}

}
