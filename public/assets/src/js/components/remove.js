'use strict';

/**
 * The code to run when the "Remove from wishlist" button is clicked.
 */
export function remove(btn, id, result) {

    let $ = jQuery;

    $('.woocommerce-notices-wrapper').html(result.notice);

    // If the operation is successful.
    if ( result.status ) {

        let addBtn = $('#sw-button-add-' + id);
        let removeBtn = $('#sw-button-remove-' + id);

        // If the buttons exist.
        if ( addBtn.length > 0 && removeBtn.length > 0 ) {

            // Swaps the "Remove from wishlist" button with the "Add to wishlist" button.
            addBtn.show();
            removeBtn.hide();

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
