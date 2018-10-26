var $ = jQuery;

/* WooCommerce functions */

window.CALAFATE.Woo = {

	mount: function() {

		this._initGrid();
		this._initSingle();
		this._initCartWidget();
		this._initPages();

		this._reinitJS();

	},

	_dummyInterval: null,

	_initGrid: function() {

		// Do some changes in the product grid item structure, based on the thumbnail style

		if ( $('.portfolio-grid').length > 0 && $('.entry-portfolio.product.hover-two').length > 0 ) {
			$('.entry-portfolio.product.hover-two').each(function(){
				$(this).find('.entry-thumbnail').append($(this).find('.price del'));
				$(this).find('.entry-thumbnail del').wrap('<span class="price" />');
				$(this).find('.entry-thumbnail').append($(this).find('.entry-buttons'));
			})
		}

		// Reinit Ajax add to cart button

		if ( $('.ajax_add_to_cart.add_to_cart_button').length > 0 && themeSettings.ajax == 'enabled' ) {
			$( document ).off( 'click', '.add_to_cart_button');
			$.getScript(wooScripts.add_to_cart);
			$.getScript(wooScripts.blockUI);
			$('.ajax_add_to_cart.add_to_cart_button').on('click', function(e){
				window.addedToCartViaAjaxProduct = $(this).data('product_title');
			});
		}

		// Touch devices hover emulation

		if ( $('.portfolio-grid').length > 0 ) {

			$('.entry-portfolio.product').each(function(){

				$(this).hammer().bind('tap', function(e){

					if ( ! $(this).hasClass('hover') ) {
						$(this).addClass('hover');
						$('.entry-portfolio.product.hover:not(#' + $(this).attr('id') + ')').removeClass('hover');
					} else {
						$('.entry-portfolio.product.hover').removeClass('hover');
					}

				});

			});
			
		}

	},

	_initSingle: function() {

		if ( $('body').hasClass('single-product') ) {

			// Set add to cart ajax form

			if ( $('.single-product form.cart').length > 0 && themeSettings.ajax == 'enabled' && wooSettings.cart_redirect != 'yes' ) {

				var $singleProductForm = $('.single-product form.cart'),
						$singleProductButton = $singleProductForm.find('button[type="submit"]'),
						singleProductButtonText = $singleProductButton.text();

				$singleProductButton.wrapInner('<span class="text" />')
				$singleProductButton.append(wooSVG.loading);

				if ( wc_add_to_cart_params.wc_ajax_url.indexOf(document.location.pathname) == -1 ) {
					wc_add_to_cart_params.wc_ajax_url = document.location.pathname + '?wc-ajax=%%endpoint%%';
				}

				$singleProductForm.on('submit', function(e){

					e.preventDefault();

					$singleProductButton.addClass('loading');

					$.ajax({
						type: $singleProductForm.prop('method'),
						url: $singleProductForm.prop('action'),
						data: $singleProductForm.serialize(),
						success: function(data, status, request) {

							var getMessage = '<div class="woocommerce-message no-transition active">' + wooLang.view_cart_button + '"' + $('h1.product_title').text() + '" ' + wooLang.added_to_cart + '</div>';

							if ( $('.woocommerce-message').length > 0 ) {
								$('.woocommerce-message').remove();
							}

							$(getMessage).insertBefore($('#main > .grid__item > div.product'));
							$('.woocommerce-message').css('display', 'none').fadeIn(100);

							$singleProductButton.removeClass('loading');

						},
						error: function(request, status, error) {
							console.log('error: ', request, status, error);
						}
					});

				});

			}

			// Load variations script with all dependencies, in proper order

			if ( typeof $.fn.wc_variation_form !== 'function' ) {
				$.getScript(wooScripts.underscore).done(function(){
					$.getScript(wooScripts.wp_util).done(function(){
						$.getScript(wooScripts.add_to_cart_variation);
					});
				});
			} else {
				$.getScript(wooScripts.add_to_cart_variation);
			}
			

			// Variations form styling

			if ( $('form.variations_form').length > 0 ) {

	      $('form.variations_form select:not(.styled)').each(function(){
          $(this).styledSelect({
            coverClass: 'simple-select-cover',
            innerClass: 'simple-select-inner'
          }).addClass('styled');
	      });

			}

			// Add padding for pages without images

			if ( $('.imgs-hide').length > 0 ) {

				$(window).on('resize.single-product', window.debounce(function(){
					$('.content-holder').css('paddingTop', $(window).height() - $('#main').offset().top);
				}, 250));

			}

			// Single product responsiveness

			if ( ! ( $('.sprq').length > 0 ) ) {
				$('body').append('<div class="sprq" />');
			}

			var $sprq = $('.sprq'),
					$productHolder = $('#content'),
					$productSummary = $('.single-product .summary'),
					$productImages = $('.single-product .images').length > 0 ? $('.single-product .images') : null;

			var firstTimer = true;

			$(window).on('resize.product-summary-responsive', window.debounce(function(){

				if ( $sprq.css('display') === 'block' ) {
					
					var crazyVariable = ( $(window).height() - $productHolder.offset().top ) - ( $productSummary.find('.product_title').outerHeight(true) + $productSummary.find('.woocommerce-breadcrumb').outerHeight(true) + 40 );
					$('#main').css('paddingTop', crazyVariable);

					if ( firstTimer ) {
						firstTimer = false;
						$productSummary.find('.product_title').addClass('active');
						setTimeout(function(){
							$productSummary.find('.woocommerce-breadcrumb').addClass('active');
						}, 50);
					}

				} else {
					$('#main').css('marginTop', 0);
				}
					
			}, 250)).trigger('resize.product-summary-responsive');

			// Carousel

			if ( $('.single-product .images-carousel').length > 0 ) {

				var $carousel = $('.single-product .images-carousel').flickity({
				  cellAlign: 'left',
				  contain: true,
				  prevNextButtons: false,
				  pageDots: false,
				  wrapAround: true,
				  imagesLoaded: true,
				  adaptiveHeight: true
				});

				setTimeout(function() {
					$('.single-product .images-view').addClass('active');
					$thumbnails.find('img').each(function(){
						setTimeout((function(){
							$(this).addClass('active');
						}).bind(this), $(this).parent().index()*50)
					})
					$thumbnailsHolder.stop().animate({scrollTop: 0}, 0);
				}, 250);

				var flkty = $('.single-product .images-carousel').data('flickity'),
						$thumbnails = $('.single-product .thumbnails a'),
						$thumbnailsHolder = $('.single-product .thumbnails .holder');

				$thumbnails.eq(0).addClass('active');
				$thumbnails.on('click', function(e){
					flkty.select($(this).index());
					e.preventDefault();
				});

				$carousel.on('select.flickity', function() {

					$('.single-product .thumbnails a.active').removeClass('active');
					$thumbnails.eq(flkty.selectedIndex).addClass('active');

					var scrollY = $thumbnails.eq(flkty.selectedIndex).position().top + $thumbnailsHolder.scrollTop() - ( $thumbnailsHolder.parent().height() - 100 ) / 2;
					$thumbnailsHolder.stop().animate({scrollTop: scrollY});

				});

				var fancyboxArray = [],
						i = 0;
			 	$('.single-product .images-carousel .carousel-cell').each(function(){
			 		fancyboxArray.push({
			 			src: $(this).data('full'),
			 			i: i++
			 		});
			 	})

				$carousel.on( 'staticClick.flickity', function(e, p, el, ind) {

				  if ( !el ) {
				    return;
				  }

					$.fancybox.open(fancyboxArray.slice(ind).concat(fancyboxArray.slice(0, ind)));

				});

				setTimeout(function(){
					$.fn.wc_variations_image_update = function( variation ) {
						if ( variation && variation.image_id.length > 0 && $('.single-product .carousel-cell.variation-' + variation.variation_id).length > 0 ) {
							flkty.select($('.single-product .carousel-cell.variation-' + variation.variation_id).index());
						}
					};
				}, 1000);

			} else {

				setTimeout(function() {
					$('.single-product .images-view').addClass('active');
				}, 250);

			}

			// ...

			this._initReviews();
			this._initProductScroll();

		}

	},

	_initProductScroll: function() {

		// Remove any empty content

		if ( ( $('.content-holder').find('.grid').children('.grid__item').length === 1 && $('.content-holder').find('.grid').children('.grid__item:empty').length > 0 ) || $('.content-holder').find('.grid').children('.grid__item').length === 0 ) {
			$('.content-holder').remove();
		}

		// Set variables

		var $productSummary = $('.single-product .summary'),
				$productParent = $productSummary.parent(),
				scrollOffset = 70;

		if ( $('.single-product .content-holder .full-width').length > 0 ) {
			$scrollBlock = $('.single-product .content-holder .full-width');
		} else if ( $('.single-product .upsells').length > 0 ) {
			$scrollBlock = $('.single-product .upsells');
		} else if ( $('.single-product .related').length > 0 ) {
			$scrollBlock = $('.single-product .related');
		} else if ( $('.single-product .entry-navigation').length > 0 ) {
			$scrollBlock = $('.single-product .entry-navigation');
		}

		// More variables (numbers)

		var scrollBlockOffset = $scrollBlock.offset().top;
		var rightSideDifference = $(window).height() - ( $productSummary.outerHeight() + scrollOffset + parseInt($scrollBlock.css('marginTop')) );
		var leftSideHeight = 0 + ( $productParent.find('.content-holder').length > 0 ? $productParent.find('.content-holder').outerHeight(true) : 0 ) + ( $productParent.find('.images').length > 0 ? $productParent.find('.images').outerHeight(true) : 0 );
		var rightSideHeight = $productSummary.outerHeight();

		if ( leftSideHeight < rightSideHeight ) {

			// case 2 & 3 

			$scrollBlock.before('<div id="dummy-padding" style="float: left; width: 58.33%; height:' + (rightSideHeight - leftSideHeight) + 'px;" />');

			var $dummyPadding = $('#dummy-padding');

			this._dummyInterval = setInterval(function(){
				leftSideHeight = 0 + ( $productParent.find('.content-holder').length > 0 ? $productParent.find('.content-holder').outerHeight(true) : 0 ) + ( $productParent.find('.images').length > 0 ? $productParent.find('.images').outerHeight(true) : 0 );
				rightSideHeight = $productSummary.outerHeight();
				$dummyPadding.css('height', (rightSideHeight - leftSideHeight));
			}, 1000);

		} else {

			$(window).on('resize.product-summary', window.debounce(function(){

				$productSummary.width(Math.round( ( parseInt($productSummary.css('paddingRight')) === 1 ? 38.333 : 33.333 ) * $productParent.width() / 100 ))
					.css('right', $productParent.offset().left);

			}, 250)).trigger('resize.product-summary');

			$productSummary.addClass('stick').removeClass('non-stick');
			$productSummary.width(Math.round( ( parseInt($productSummary.css('paddingRight')) === 1 ? 38.333 : 33.333 ) * $productParent.width() / 100 ))
					.css('right', $productParent.offset().left);

			// continue with scrolling

			$(window).on('scroll.product-summary', window.regularplus(function(){

				// variables - again

				rightSideDifference = $(window).height() - ( $productParent.offset().top + $productSummary.outerHeight() + parseInt($scrollBlock.css('marginTop')) );
				leftSideHeight = 0 + ( $productParent.find('.content-holder').length > 0 ? $productParent.find('.content-holder').outerHeight(true) : 0 ) + ( $productParent.find('.images').length > 0 ? $productParent.find('.images').outerHeight(true) : 0 );
				rightSideHeight = $productSummary.outerHeight();
				scrollBlockOffset = $scrollBlock.offset().top;

				if ( rightSideDifference >= 0 ) {
					scrollBlockOffset += rightSideDifference;
				}

				// case 1 & 4
			
				var scrollPlusWindow = $(window).scrollTop() + $(window).height();

				var translateValue = 0;

				if ( rightSideDifference < 0 ) { 

					// case 1

					var mistyerVariable = $productParent.offset().top - scrollOffset;

					var scrollPercent = Math.min(Math.max(0, Math.round(( $(window).scrollTop() - mistyerVariable ) * 100 / ( $scrollBlock.offset().top - $(window).height() - mistyerVariable ))), 100);

					translateValue = scrollPercent * rightSideDifference / 100;


				} else {

					// case 4

					translateValue = 0;

				}

				if ( $(window).scrollTop() + scrollOffset >= $productParent.offset().top ) {

					if ( scrollPlusWindow <= scrollBlockOffset ) {

						$productSummary.css('transform', 'translateY(' + translateValue + 'px)');

					} else {
						var addTheScroll = scrollPlusWindow - scrollBlockOffset;
						$productSummary.css('transform', 'translateY(' + ( translateValue - addTheScroll ) + 'px)');
					}

				} else {
					$productSummary.css('transform', 'translateY(0px)');
				}

			}, 1000));

		}

	},

	_initReviews: function() {

		if ( $('.star-rating').length > 0 ) {

			var $siteOverlay = $('#site-overlay');

			// Set open/close event for the reviews button

			$('.star-rating').on('click touchstart', function(){

				if ( ! $siteOverlay.hasClass('opened-reviews') ) {

					$siteOverlay.addClass('opened-reviews');
					$siteOverlay.addClass('opened-reviews-instant');

					window.openGlobalOverlay(true, $('#reviews-holder'), $(this));
					window.killBodyScrollTheProperWay(true);
					$carousel.flickity('resize');

				} else {

					setTimeout(function(){
						$siteOverlay.removeClass('opened-reviews');
					}, 500);
					$siteOverlay.removeClass('opened-reviews-instant');

					window.openGlobalOverlay(false, $('#reviews-holder'), $(this));
					window.killBodyScrollTheProperWay(false);

				}

			});

			// Do some DOM modifications

			$('#reviews-holder').prependTo('#site-overlay');
			$('#tab-content-reviews').remove();

			$('.calafate-tabs.woocommerce-tabs').each(function(){
			
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

			$('#reviews li').each(function(){
				var score = $(this).find('strong[itemprop="ratingValue"]').html();
				$(this).addClass('score-' + score);
			});

			$('#reviews .commentlist').addClass('calafate-slider carousel');
			$('#reviews .commentlist').children('li').addClass('carousel-cell');

			// Init carousel

			var $carousel = $('#reviews .commentlist').flickity({
			  cellAlign: 'left',
			  contain: true,
			  prevNextButtons: false,
			  pageDots: true,
			  wrapAround: true,
			  imagesLoaded: true,
			  adaptiveHeight: true
			});

			$('#reviews').append('<span class="write-review">' + wooSVG.plus + wooLang.write_review + '</span>');

			// More events and tweaks

			var $reviews = $('#reviews'),
					$reviewsButton = $('.write-review');

			$reviewsButton.on('click', function(){

				if ( ! $reviews.hasClass('show-form') ) {
					$reviews.addClass('show-form');
					$reviewsButton.html(wooSVG.close + wooLang.close_review);
				} else {
					$reviews.removeClass('show-form');
					$reviewsButton.html(wooSVG.plus + wooLang.write_review);
				}

			});

      $('.comment-form-rating select').styledSelect({
        coverClass: 'big-select-cover',
        innerClass: 'big-select-inner'
      }).addClass('styled');

      $('#tab-title-reviews').remove();
			if ( $('.woocommerce-tabs .tabs-titles').children('h4').length === 0 ) {
				$('.woocommerce-tabs').remove();
			}

      // Ajax functionality

      $('#respond').before('<p id="ajax-response"></p>');

			var $commentForm = $('#commentform'),
					$nameInput = $commentForm.find('#author'),
					$emailInput = $commentForm.find('#email'),
					$commentInput = $commentForm.find('#comment'),
					$submitButton = $commentForm.find('input#submit'),
					$ratingButton = $commentForm.find('#rating'),
					$ajaxResponse = $('#ajax-response');

			$nameInput.focus(function(){$ajaxResponse.text('')});
			$emailInput.focus(function(){$ajaxResponse.text('')});
			$commentInput.focus(function(){$ajaxResponse.text('')});

			$commentForm.submit(function(e){

				if ( $ratingButton.val() == '' ) {
					alert(wooLang.required_rating);
				} else {

					var ok = true,
			        emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

					if ( $nameInput.length > 0 && $nameInput.val().length < 3 ) {
						ok = false;
					}

					if ( $emailInput.length > 0 && $emailInput.val().length < 3 && ! emailReg.test($emailInput.val()) ) {
						ok = false;
					}

					if ( $commentInput.length > 0 && $commentInput.val().length < 3 ) {
						ok = false;
					}

					if ( ok ) {

						$submitButton.addClass('disabled');

						$.ajax({
							type: $commentForm.prop('method'),
							url: $commentForm.prop('action'),
							data: $commentForm.serialize(),
							success: function(data, status, request) {

								$ajaxResponse.text(wooLang.posted_review);
								$submitButton.removeClass('disabled');

								setTimeout(function(){

									if ( $siteOverlay.hasClass('opened-reviews-instant') ) {
										$('.show-reviews').trigger('click');
									}

									setTimeout(function(){
										$ajaxResponse.text('');
										$reviewsButton.trigger('click');
									}, 500);

								}, 2000);

							},
							error: function(request, status, error) {
								if ( request.status == 409 ) {
									$ajaxResponse.text(wooLang.duplicate_review);
									$submitButton.removeClass('disabled');
								}
							}
						});

						$ajaxResponse.text(wooLang.posting_review);

					} else {
						$ajaxResponse.text(wooLang.required_review);
					}

				}

				e.preventDefault();

			});

		}

	},

	_initCartWidget: function() {

		// Add ajax link

		if ( $('.return-to-shop').length > 0 ) {
			$('.return-to-shop a').addClass('ajax-link');
			window.CALAFATE.Ajax.lateInit($('.return-to-shop a'));
		}

		/* We need to catch all ajax calls done by WooCommerce and check for cart updates, then do the proper modifications.. 

		These are all for WooCommerce. Our own AJAX calls are not handled here ! */

		$(document).off('ajaxComplete');
		$(document).ajaxComplete((function(e, XMLHttpRequest, ajaxOptions) {

			var data = XMLHttpRequest.responseText;

			if ( data != undefined && data != '' ) {

				var newWidget = data.match(/(<div class=\"widget_shopping_cart_content\"[^>]*>[^<]*(?:(?!<\/?div class=\"widget_shopping_cart_content\")<[^<]*)*<span id=\"regex-search-until-here\">)/i);
				
				if ( newWidget && newWidget[0] ) {
					$('.widget_shopping_cart_content').replaceWith(newWidget[0]);
					$('#mini-cart').removeClass('block-actions');
				}

				if ( $('.shop_table tr, .cart-collaterals').length > 0 ) {
					$('.shop_table tr, .cart-collaterals').addClass('no-transition active');
				}

				$('.woocommerce-message, .woocommerce').addClass('no-transition active');

				if( XMLHttpRequest['responseJSON'] && XMLHttpRequest['responseJSON']['fragments'] ) {

					if ( window.addedToCartViaAjaxProduct ) {

						var getMessage = '<div class="woocommerce-message no-transition active">' + wooLang.view_cart_button + '"' + window.addedToCartViaAjaxProduct + '" ' + wooLang.added_to_cart + '</div>';

						if ( $('.woocommerce-message').length > 0 ) {
							$('.woocommerce-message').remove();
						}

						$('#site').append(getMessage);

						$('.woocommerce-message').css('display', 'none').fadeIn(100);

						window.window.addedToCartViaAjaxProduct = null;
						
					}

				}

				this._reinitJS();

			}

		}).bind(this));

	},

	_initPages: function() {

		// Cart buttons update (for qty buttons)

		$('input[name="update_cart"], input[name="calc_shipping"]').on('click', (function(e){
			setTimeout((function(){
				this._fixQtyButtons();
			}).bind(this), 1000);
		}).bind(this));

		// Order page styling

		if ( $('body').hasClass('woocommerce-orders') ) {
			$('td.order-status').each(function(){
				$(this).addClass($(this).text().replace(' ', '-').toLowerCase())
			})
		}

		if ( $('.woocommerce-MyAccount-navigation').length > 0 ) {
			$('.woocommerce-MyAccount-navigation a').addClass('fancybox');
		}

		// Checkout page styling (DOM modifications actually)

		if ( $('body').hasClass('woocommerce-checkout') ) {

			// Billing area 

			$('.woocommerce-billing-fields__field-wrapper').children('p').appendTo($('.woocommerce-billing-fields'));

			$('.woocommerce-billing-fields__field-wrapper').remove();

			$('.woocommerce-billing-fields').find('#billing_first_name_field, #billing_last_name_field, #billing_company_field, #billing_email_field, #billing_phone_field').wrapAll('<div class="grid grid__item six-twelfths lap--one-whole palm--one-whole " />' );

			$('.woocommerce-billing-fields').children('p').wrapAll('<div class="grid grid__item five-twelfths old-breakpoint--one-half lap--one-whole palm--one-whole " />');

			$('.woocommerce-billing-fields').children('div.grid__item:first-of-type').after('<div class="grid__item one-twelfth old-breakpoint--hide lap--show" />');

			$('.woocommerce-billing-fields').children('div.clear').remove();

			$('.woocommerce-billing-fields').addClass('grid').css('width', 'calc(100% + 90px)');

			$('.woocommerce-billing-fields .create-account').appendTo($('.woocommerce-billing-fields').children('div.grid__item:first-of-type')).addClass('grid__item one-whole');

			// Shipping area

			if ( $('.shipping_address').length > 0 ) {			

				var rememberToHide = false;
				if ( $('.shipping_address').css('display') == 'none' ) {
					rememberToHide = true;
				}		

				$('.woocommerce-shipping-fields__field-wrapper').children('p').appendTo($('.woocommerce-shipping-fields'));

				$('.woocommerce-shipping-fields__field-wrapper').remove();

				$('.woocommerce-shipping-fields').children('div.clear').remove();

				$('.woocommerce-shipping-fields').addClass('grid').css('width', 'calc(100% + 90px)');

				$('#ship-to-different-address').addClass('grid__item one-whole');

				$('.shipping_address').addClass('grid grid__item six-twelfths lap--one-whole palm--one-whole');
				$('.woocommerce-shipping-fields').children('p').wrapAll('<div class="grid grid__item five-twelfths old-breakpoint--one-half lap--one-whole palm--one-whole shipping_address" />');

				if ( $('.woocommerce-additional-fields').length > 0 ) {
					$('.woocommerce-additional-fields').addClass('grid grid__item five-twelfths old-breakpoint--one-half lap--one-whole palm--one-whole calafate-order-notes');
					$('.woocommerce-additional-fields').insertAfter($('#ship-to-different-address'))
				}

				$('.shipping_address').insertAfter($('.calafate-order-notes'));
				$('.shipping_address').before('<div class="grid__item one-twelfth old-breakpoint--hide lap--show">&nbsp;</div>');
				
				if ( rememberToHide ) {
					$('.shipping_address').css('display', 'none');
				}


			}

			// Payments area

			$(window).on('resize.checkout', window.debounce(function(){
				$('#payment').css('marginTop', -$('.woocommerce-checkout-review-order-table tfoot').height() - 24)
			}, 250));

			setTimeout(function(){
				$(window).trigger('resize');
			}, 1000);

			// Coupon

			if ( $('#move-this-block').length > 0 ) {
				$('#move-this-block').appendTo($('#append-coupon-here'));
			}

		}

		// Carousel (both in checkout and login pages)

		if ( $('.calafate-checkout-content').length > 0 ) {

			var $carousel = $('.calafate-checkout-content').flickity({
				cellSelector: '.carousel-cell',
				cellAlign: 'left',
				contain: true,
				prevNextButtons: false,
				pageDots: false,
				adaptiveHeight: true,
				draggable: false,
				accessibility: false
			});

			$('.calafate-checkout-navigation li').on('click', function(e){ 

				if ( ! $(this).hasClass('active') ) {

					$(this).removeClass('done').addClass('active');
					$(this).prevAll('li').removeClass('active').addClass('done');
					$(this).nextAll('li').removeClass('active').removeClass('done');

					$carousel.flickity('select', $(this).index());

				}

			});

			$carousel.on('settle.flickity', function(){
				$carousel.flickity('resize');
			});

			$('#ship-to-different-address-checkbox').on('change', function(e){
				e.preventDefault();
				var carouselI = setInterval(function(){
					$carousel.flickity('resize');
				}, 100);
				setTimeout(function(){
					clearInterval(carouselI);
				}, 1000);
			})

			$('.chck-link').on('click', function(e){
				$('.calafate-checkout-navigation li').eq($(this).data('slide')).trigger('click');
				e.preventDefault();
			});

		}

		// stuff

		if ( $('body').hasClass('woocommerce-cart') || $('body').hasClass('woocommerce-checkout') || $('body').hasClass('woocommerce-account') ) {
			$('.page-content.entry-content').removeClass('entry-content');
		}

	},

	_reinitJS: function() {

		// All kinds of javascript issues that need to be redeclared after separate WooCommerce AJAX calls

		if ( $('div.quantity').length > 0 ) 
			this._fixQtyButtons();

		if ( typeof $.fn.select2 === 'function' ) {

			if ( $('select#calc_shipping_country').length > 0 )
				$('select#calc_shipping_country').select2();

			if ( $('select#billing_country').length > 0 )
				$('select#billing_country').select2();

			if ( $('select#shipping_country').length > 0 )
				$('select#shipping_country').select2();

		}

		if ( $('.top-menu .cart-item').length > 0 && $('#mini-cart').length > 0 ) {

			$('.top-menu .cart-item').off('click');

			if ( parseInt( $('.widget_shopping_cart .woocommerce-cart-no').text() ) === 0 ) {
				$('.widget_shopping_cart').addClass('empty');
			} else {
				$('.widget_shopping_cart').removeClass('empty');
			}

			$('.top-menu .cart-item').off('click').on('click', function(e){

				e.preventDefault();

				if ( ! $('body').hasClass('cart-opened') ) {
					window.killBodyScrollTheProperWay(true);
					$('body').addClass('cart-opened');
				} else {
					$('body').removeClass('cart-opened');
					window.killBodyScrollTheProperWay(false);
				}

			});

			var safeTouch = true;
			$('.responsive-bag').off('click touchstart').on('click touchstart', function(e){

				if ( safeTouch ) {

					safeTouch = false;
					setTimeout(function(){
						safeTouch = true;
					}, 250);
					$('.top-menu .cart-item').trigger('click');

				}

				e.preventDefault();

			});

			$('#mini-cart .remove-button').off('click').on('click', function(e){
				$('.top-menu .cart-item').trigger('click');
			});

			$('.top-menu .woocommerce-cart-no, .responsive-bag .woocommerce-cart-no').text($('.widget_shopping_cart .woocommerce-cart-no').text());

			//$('#mini-cart').find('a:not(.cart-checkout):not(.remove)').addClass('ajax-link');
			//window.CALAFATE.Ajax.lateInit($('#mini-cart a.ajax-link'));

			$('#mini-cart a.remove').off('click').on('click', function(e){

				$('#mini-cart').addClass('block-actions');

				e.preventDefault();
				var href = $(this).attr('href');

				$(this).parent().slideUp(200, function(){
					$(this).remove();
				});

				$.ajax({

					url: href,
					dataType: 'text'

				});

			});

		}

		if ( $('body').hasClass('woocommerce-checkout') ) {
			$(window).trigger('resize');
		} else {

			if ( $('#site .woocommerce-message, #site .woocommerce-info').length > 0 ) {
				$('#site .woocommerce-message, #site .woocommerce-info').wrapInner('<div class="color" />').wrapInner('<div class="wrapper" />').appendTo('body');
				setTimeout(function(){
					$('.woocommerce-message, .woocommerce-error, .woocommerce-info').fadeOut(200);
				}, 4000);
				setTimeout(function(){
					$('.woocommerce-message, .woocommerce-error, .woocommerce-info').remove();
				}, 4500);
			}

		}

	},

	_fixQtyButtons: function() {

		$(document).off('click', '.plus, .minus');
		
		$( 'div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)' ).append( '<input type="button" value="+" class="plus" />' ).prepend( '<input type="button" value="-" class="minus" />' ).addClass('buttons_added');

		$(document).on( 'click', '.plus, .minus', function() {

			var $qty		= $( this ).closest( '.quantity' ).find( '.qty' ),
				currentVal	= parseFloat( $qty.val() ),
				max			= parseFloat( $qty.attr( 'max' ) ),
				min			= parseFloat( $qty.attr( 'min' ) ),
				step		= $qty.attr( 'step' );

			// Format values
			if ( ! currentVal || currentVal === '' || currentVal === 'NaN' ) currentVal = 0;
			if ( max === '' || max === 'NaN' ) max = '';
			if ( min === '' || min === 'NaN' ) min = 0;
			if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) step = 1;

			if ( $( this ).is( '.plus' ) ) {

				if ( max && ( max == currentVal || currentVal > max ) ) {
					$qty.val( max );
				} else {
					$qty.val( currentVal + parseFloat( step ) );
				}

			} else {

				if ( min && ( min == currentVal || currentVal < min ) ) {
					$qty.val( min );
				} else if ( currentVal > 0 ) {
					$qty.val( currentVal - parseFloat( step ) );
				}

			}
			
			$qty.trigger( 'change' );

		});

	},

	unmount: function() {

		$(window).off('resize.checkout');
		$(window).off('resize.single-product');
		$(window).off('scroll.hero-woo');
		$(window).off('resize.product-summary-responsive');
		$(window).off('resize.product-summary');
		$(window).off('scroll.product-summary');
		$(document).off('ajaxComplete');
		$(document).off('click', '.plus, .minus');

		$('.woocommerce-message').remove();

		if ( this._dummyInterval !== null ) {
			clearInterval(this._dummyInterval);
		}

	}

}