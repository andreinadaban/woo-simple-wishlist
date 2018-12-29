(function( $ ) {

	'use strict';

	$('.wcsw-button-ajax').click(function(e) {

		e.preventDefault();

		var thisBtn = $(this);
		var id;
		var parameter;
		var fn;

		if ( thisBtn.hasClass('wcsw-button-add') ) {

			parameter = 'wcsw-add';
			fn = add;

		}

		if ( thisBtn.hasClass('wcsw-button-remove') ) {

			parameter = 'wcsw-remove';
			fn = remove;

		}

		id = parseInt(thisBtn.attr('href').replace('?' + parameter + '=', ''));

		$.ajax({
			url: ajaxURL,
			data: 'action=wcsw_ajax&' + parameter + '=' + id + '&wcsw-ajax=1',
			success: function(result) {

				fn(thisBtn, result);

			}
		});

	});

	function add(btn, result) {

		$('.single_add_to_cart_button').after(goToWishlistButton);
		btn.remove();

		$('#content .col-full > .woocommerce').html(result);

	}

	function remove(btn, result) {

		var row = btn.parents('tr');

		row.remove();

		$('#content .col-full > .woocommerce').html(result);

	}

})( jQuery );
