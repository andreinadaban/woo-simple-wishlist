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

/**
 * The code to run when the "Add to wishlist" button is clicked.
 */
export function add(btn, id, result) {

    let $ = jQuery;

    $('.woocommerce-notices-wrapper').html(result.notice);

    // If the operation is successful.
    if ( result.status ) {

        let addBtn = $('#sw-button-add-' + id);
        let removeBtn = $('#sw-button-remove-' + id);

        // If the buttons exist.
        if ( addBtn.length > 0 && removeBtn.length > 0 ) {

            // Swaps the "Remove from wishlist" button with the "Add to wishlist" button.
            addBtn.hide();
            removeBtn.show();

        }

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
