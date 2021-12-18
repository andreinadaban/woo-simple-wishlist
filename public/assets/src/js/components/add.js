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
