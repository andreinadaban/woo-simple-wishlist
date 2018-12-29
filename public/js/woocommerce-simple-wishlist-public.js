(function( $ ) {

	'use strict';

	$('.wcsw-button-ajax').click(function(e) {

		e.preventDefault();

		var thisBtn = $(this);

		// The ID of the product.
		// This is stored inside the "href" attribute of the button, however it needs to be extracted.
		var id;

		// The parameter name.
		var parameter;

		// This variable stores the function that is meant to run.
		var fn;

		if ( thisBtn.hasClass('wcsw-button-add') ) {

			parameter = 'wcsw-add';
			fn = add;

		}

		if ( thisBtn.hasClass('wcsw-button-remove') ) {

			parameter = 'wcsw-remove';
			fn = remove;

		}

		// The extracted ID of the product.
		id = parseInt(thisBtn.attr('href').replace('?' + parameter + '=', ''));

		// Sends the AJAX request.
		$.ajax({
			url: ajaxURL,
			data: 'action=wcsw_ajax&' + parameter + '=' + id + '&wcsw-ajax=1',
			success: function(result) {

				// Runs the corresponding function.
				fn(thisBtn, result);

			}
		});

	});

	// The code to run when the "Add to wishlist" button is clicked.
	function add(btn, result) {

		$('.single_add_to_cart_button').after(goToWishlistButton);
		btn.remove();

		// Display a notice. See the "add()" function in the "class-wcsw-public-functions.php" file.
		$('#content .col-full > .woocommerce').html(result);

	}

	// The code to run when the "Remove from wishlist" button is clicked.
	function remove(btn, result) {

		var row = btn.parents('tr');

		row.remove();

		// Display a notice. See the "remove()" function in the "class-wcsw-public-functions.php" file.
		$('#content .col-full > .woocommerce').html(result);

		if ( $('.wcsw-table tr').length === 1 ) {

			// Reload to show the notice.
			location.reload();

		}

	}

})( jQuery );
