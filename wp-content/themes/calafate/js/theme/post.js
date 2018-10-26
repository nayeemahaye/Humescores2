var $ = jQuery;

/* Post functions */

window.CALAFATE.Post = {

	mount: function() {

		//$('.entry-navigation').calafate_sticking_engine();

		setTimeout(function() {
			$('.entry-navigation').addClass('active');
		}, 500);

		$('.entry-navigation').clone().addClass('responsive').insertAfter('article.hentry');

		// Comments link initialization

		if ( $('#comments').length > 0 ) {

			var $comments = $('#comments'),
					$commentsLink = $('.comments-link');

			$commentsLink.on('click', function(e) {

				if ( ! $(this).hasClass('opened') ) {

					$comments.css('display', 'block');
					setTimeout(function(){
						$comments.addClass('opened');
					}, 100);

					$('html, body').animate({
						'scrollTop': $comments.offset().top - 200
					});

					$(this).addClass('opened');

				} else {

					$comments.removeClass('opened');
					setTimeout(function(){
						$comments.css('display', 'none');
					}, 200);

					$('html, body').animate({
						'scrollTop': $commentsLink.offset().top - 800
					});

					$(this).removeClass('opened');

				}
				e.preventDefault();

			});

			$('.hide-comments, .entry-meta .comments a').on('click', function(e) {
				$commentsLink.trigger('click');
				e.preventDefault();
			});

			this._initAjaxComments();

			// jump
			
			if ( window.location.hash == '#comments' ) {
				setTimeout(function() {
					$commentsLink.trigger('click');
				}, 1000);
			}

		}

		// Stuff

		$('.post-tags a').addClass('ajax-link');

		// Related posts plugin

		if ( $('.rp4wp-related-posts ').length > 0 ) {

			$('.rp4wp-related-post-content').each(function(){

				$(this).wrapInner('<a class="ajax-link" href="' + $(this).find('a').attr('href') + '" />');
				$(this).find('a.ajax-link').prepend('<h5>' + $(this).find('a:not(.ajax-link)').html() + '</h5>');
				$(this).find('a:not(.ajax-link)').remove();

				$(this).on('click', function(){

				})

			});

		}

	},

	unmount: function() {
		/*$('.entry-navigation').addClass('re');
		$('.entry-header').addClass('re');*/
	},

	_initAjaxComments: function() {

		$('#comment-form').find('input#submit').before('<p id="ajax-response"></p>');

		var $commentForm = $('#comment-form'),
				$commentsList = $('#comments-list'),
				$nameInput = $commentForm.find('#name'),
				$emailInput = $commentForm.find('#email'),
				$commentInput = $commentForm.find('#comment'),
				$submitButton = $commentForm.find('input#submit'),
				$ajaxResponse = $('#ajax-response');

		$nameInput.focus(function(){$ajaxResponse.text('')});
		$emailInput.focus(function(){$ajaxResponse.text('')});
		$commentInput.focus(function(){$ajaxResponse.text('')});

		$commentForm.submit(function(e){

			var ok = true,
	        emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

			if ( $nameInput.length > 0 && $nameInput.val().length < 3 ) {
				ok = false;
			}

			if ( $emailInput.length > 0 && ! emailReg.test($emailInput.val()) ) {
				ok = false;
			}

			if ( $commentInput.length > 0 && $commentInput.val().length < 3 ) {
				ok = false;
			}

			if ( ok ) {

				$.ajax({
					type: $commentForm.prop('method'),
					url: $commentForm.prop('action'),
					data: $commentForm.serialize(),
					success: function(data, status, request) {
						$commentsList.html($(data).find('#comments-list')[0]);
						$ajaxResponse.text(langObj.posted_comment);
						$submitButton.removeClass('disabled');
					},
					error: function(request, status, error) {
						if ( request.status == 409 ) {
							$ajaxResponse.text(langObj.duplicate_comment);
							$submitButton.removeClass('disabled');
						}
					}
				});

				$ajaxResponse.text(langObj.posting_comment);

			} else {
				$ajaxResponse.text(langObj.required_comment);
			}

			e.preventDefault();

		});

	}

}