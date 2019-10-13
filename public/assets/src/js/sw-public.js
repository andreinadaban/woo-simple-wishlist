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

'use strict';

import {getButton, getParameterAndFunction, getURLParameters} from './components/helpers';

(function($) {

	$(document).click(function(e) {

		// Selects the target button.
		let thisBtn = getButton(e);

		if ( ! thisBtn ) {
			return;
		}

		e.preventDefault();

		// The button link that contains the necessary parameters.
		let href = thisBtn.attr('href');

		// Cache.
		let parameterAndFunction = getParameterAndFunction(thisBtn);
		let URLParameters = getURLParameters(href);

		// The parameter name.
		let parameter = parameterAndFunction.parameter;

		// The function that is meant to run.
		let fn = parameterAndFunction.fn;

		let productID = parseInt(URLParameters[parameter]);
		let nonce = URLParameters['nonce-token'];

		// Sends the AJAX request.
		$.ajax({
			url: ajaxURL,
			data: 'action=sw_ajax&' + parameter + '=' + productID + '&nonce-token=' + nonce + '&sw-ajax=1',
			success: function(result) {

				result = JSON.parse(result);

				let element = $('.sw-content');

				// Updates the template content.
				if ( element.length > 0 ) {
					element.replaceWith(result.template);
				}

				// Runs the corresponding function.
				fn(thisBtn, productID, result);

			}
		});

	});

}(jQuery));
