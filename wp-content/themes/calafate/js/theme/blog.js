var $ = jQuery;

/* Blog list functions */

window.CALAFATE.Blog = {

	mount: function( $elm ) {

		// GRID

		if ( $('.blog-posts-carousel').length > 0 ) {

			var $carousel = $('.blog-posts-carousel').flickity({
				imagesLoaded: true,
				wrapAround: true,
				freeScroll: false,
				adaptiveHeight: true,
				pageDots: false,
				prevNextButtons: false
			});

			var flkty = $carousel.data('flickity');
			var $carouselCells = $('.blog-posts-carousel .carousel-cell');

			$carousel.on( 'select.flickity', function() {

				$carouselCells.removeClass('before-selected after-selected');

				if ( flkty.selectedIndex == 0 ) {
					$carouselCells.eq($carouselCells.length - 1).addClass('before-selected');
					$carouselCells.eq(flkty.selectedIndex + 1).addClass('after-selected');
				} else if ( flkty.selectedIndex == $carouselCells.length - 1 ) {
					$carouselCells.eq(flkty.selectedIndex - 1).addClass('before-selected');
					$carouselCells.eq(0).addClass('after-selected');
				} else {
					$carouselCells.eq(flkty.selectedIndex - 1).addClass('before-selected');
					$carouselCells.eq(flkty.selectedIndex + 1).addClass('after-selected');
				}

			});

			$carousel.on( 'staticClick.flickity', function( event, pointer, cellElement, cellIndex ) {
			  if ( !cellElement ) {
			    return;
			  }
				$carousel.flickity('select', $( cellElement ).data('index'))
			});

		}

		if ( $('.entries--grid').length > 0 ) {
			setTimeout(function(){
				$('.post-navigation').css('display', 'block');
			}, 5000);
		}

		// JOURNAL

		// Append needed containers

		$('.entries--minimal').parent().append('<div class="entries-thumbnails grid__item one-third"><div class="entries-thumbnails__container" /></div>');
		
		var $elt = $('.entries-thumbnails');
		var $eltContainer = $('.entries-thumbnails__container');

		// Thumbnails animation

		$('.entry-minimal').each(function() { 

			if ( $(this).find('img').length > 0 ) {
				$eltContainer.append($(this).find('img').clone());
			} else {
				$eltContainer.append('<div class="entry-minimal__image" style="padding-bottom: 64%" />');
			}

			setTimeout((function() {
				$(this).addClass('active');
			}).bind(this), $(this).index()*50);

			if ( ! window.touchM ) {

			$(this).on('mouseenter', function() {

					var eltHeight = $elt.width()/1.5666666667;

					$elt.css('top', $(this).offset().top-$(this).parent().offset().top/*-$(this).parent().offset().top+$(this).outerHeight()/2*/).addClass('active');
					$eltContainer.css('transform', 'translateY(' + (-$(this).index()*eltHeight-2) + 'px)')

				}).on('mouseleave', function() {
					$elt.removeClass('active');
				});

			}

		});

		// Thumbnails resizing

		$(window).on('resize.blog', function(){
			var eltHeight = $elt.width()/1.5666666667;
			$elt.css({
				height: Math.floor(eltHeight-2),
				//marginTop: -eltHeight/2
			});
		}).trigger('resize');

	},

	unmount: function() {

		$(window).off('resize.blog');

		/*$('.entry-minimal').each(function() {
			setTimeout((function() {
				$(this).addClass('re');
			}).bind(this), 25 * $(this).index());
		});

		$('.entries-thumbnails').addClass('re');*/
		
	}
}