'use strict';

import {getButton, getAction, getURLParameters} from './components/helpers';

(function($) {

	$(document).click(function(e) {

		// Selects the target button.
		let thisBtn = getButton(e);

		if ( ! thisBtn ) {
			return;
		}

		e.preventDefault();

		let action     = getAction(thisBtn);
		let parameters = getURLParameters(thisBtn.attr('href'));
		let productID  = parseInt(parameters[action.parameter]);
		let nonce      = parameters['nonce-token'];

		// Sends the AJAX request.
		$.ajax({
			url: ajaxURL,
			data: 'action=sw_ajax&' + action.parameter + '=' + productID + '&nonce-token=' + nonce + '&sw-ajax=1',
			success: function(result) {

				result = JSON.parse(result);

				let element = $('.sw-content');

				// Updates the template content.
				if ( element.length > 0 ) {
					element.replaceWith(result.template);
				}

				// Runs the corresponding function.
				action.fn(thisBtn, productID, result);

			}
		});

	});

}(jQuery));
