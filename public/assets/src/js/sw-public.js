/**
 * Simple Wishlist for WooCommerce
 * Copyright (C) 2018-2019 Andrei Nadaban
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

(function($) {

	'use strict';

	$('.sw-button-ajax').click(function(e) {

		e.preventDefault();

		var thisBtn = $(this);

		// The ID of the product.
		// This is stored inside the "href" attribute of the button, however it needs to be extracted.
		var id;

		// Nonce.
		var nonce;

		// The parameter name.
		var parameter;

		// This variable stores the function that is meant to run.
		var fn;

		if ( thisBtn.hasClass('sw-button-add') ) {

			parameter = 'sw-add';
			fn = add;

		}

		if ( thisBtn.hasClass('sw-button-remove') ) {

			parameter = 'sw-remove';
			fn = remove;

		}

		if ( thisBtn.hasClass('sw-button-clear') ) {

			parameter = 'sw-clear';
			fn = clear;

		}

		// The extracted ID of the product.
		id    = parseInt(getAllUrlParams(thisBtn.attr('href'))[parameter]);
		nonce = getAllUrlParams(thisBtn.attr('href'))['nonce-token'];

		// Sends the AJAX request.
		$.ajax({
			url: ajaxURL,
			data: 'action=sw_ajax&' + parameter + '=' + id + '&nonce-token=' + nonce + '&sw-ajax=1',
			success: function(result) {

				// Runs the corresponding function.
				fn(thisBtn, id, result);

			}
		});

	});

	// The code to run when the "Add to wishlist" button is clicked.
	function add(btn, id, result) {

		// Replace the "Add to wishlist" button with the "Remove from wishlist" button.
		btn.siblings($('.sw-button-remove')).show();
		btn.hide();

		if ( isSinglePage ) {

			// Display a notice.
			$('.woocommerce-notices-wrapper').html(result);

		}

		// Event.
		let eventAdd = new CustomEvent('sw_add', {
			detail: {
				btn: btn,
				id: id,
				result: result
			}
		});

		document.dispatchEvent(eventAdd);

	}

	// The code to run when the "Remove from wishlist" button is clicked.
	function remove(btn, id, result) {

		// If on a product page.
		if ( isProductPage ) {

			// Replace the "Remove from wishlist" button with the "Add to wishlist" button.
			btn.siblings($('.sw-button-add')).show();
			btn.hide();

			if ( isSinglePage ) {

				// Display a notice.
				$('.woocommerce-notices-wrapper').html(result);

			}

		}

		// If on the account page.
		if ( isAccountPage ) {

			// Remove row by ID.
			$('.sw-tr-' + id).remove();

			// Display a notice.
			$('.woocommerce-notices-wrapper').html(result);

			// If the last product was removed.
			if ( $('.sw-table tbody tr').length < 1 ) {

				// Shows notice.
				$('.woocommerce-MyAccount-content').prepend(emptyWishlistNotice);

				// Removes table.
				$('.sw-table').remove();

			}

		}

		// Event.
		let eventRemove = new CustomEvent('sw_remove', {
			detail: {
				btn: btn,
				id: id,
				result: result
			}
		});

		document.dispatchEvent(eventRemove);

	}

	// The code to run when the "Clear wishlist" button is clicked.
	function clear(btn, id, result) {

		$('.woocommerce-notices-wrapper').html(result);

		$('.woocommerce-MyAccount-content').prepend(emptyWishlistNotice);

		$('.sw-table').remove();

		btn.remove();

		// Event.
		let eventClear = new CustomEvent('sw_clear', {
			detail: {
				btn: btn,
				id: id,
				result: result
			}
		});

		document.dispatchEvent(eventClear);

	}

	// Get URL parameters.
	function getAllUrlParams(url) {

		// Get query string from url (optional) or window.
		var queryString = url ? url.split('?')[1] : window.location.search.slice(1);

		// We'll store the parameters here.
		var obj = {};

		// If query string exists.
		if ( queryString ) {

			// Stuff after # is not part of query string, so get rid of it.
			queryString = queryString.split('#')[0];

			// Split our query string into its component parts.
			var arr = queryString.split('&');

			for (var i = 0; i < arr.length; i++) {
				// Separate the keys and the values.
				var a = arr[i].split('=');

				// Set parameter name and value (use 'true' if empty).
				var paramName = a[0];
				var paramValue = typeof (a[1]) === 'undefined' ? true : a[1];

				// Keep case consistent (optional).
				paramName = paramName.toLowerCase();
				if ( typeof paramValue === 'string' ) paramValue = paramValue.toLowerCase();

				// If the paramName ends with square brackets, e.g. colors[] or colors[2].
				if ( paramName.match(/\[(\d+)?\]$/) ) {

					// Create key if it doesn't exist.
					var key = paramName.replace(/\[(\d+)?\]/, '');
					if ( !obj[key] ) obj[key] = [];

					// If it's an indexed array e.g. colors[2].
					if ( paramName.match(/\[\d+\]$/) ) {
						// Get the index value and add the entry at the appropriate position.
						var index = /\[(\d+)\]/.exec(paramName)[1];
						obj[key][index] = paramValue;
					} else {
						// Otherwise add the value to the end of the array.
						obj[key].push(paramValue);
					}
				} else {
					// We're dealing with a string.
					if ( !obj[paramName] ) {
						// If it doesn't exist, create property.
						obj[paramName] = paramValue;
					} else if ( obj[paramName] && typeof obj[paramName] === 'string' ) {
						// If property does exist and it's a string, convert it to an array.
						obj[paramName] = [obj[paramName]];
						obj[paramName].push(paramValue);
					} else {
						// Otherwise add the property.
						obj[paramName].push(paramValue);
					}
				}
			}
		}

		return obj;

	}

}(jQuery));
