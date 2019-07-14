<?php

/**
 * Creates the add to wishlist and remove from wishlist buttons.
 *
 * @since    1.0.0
 */
function wcsw_add_remove_buttons( $product_id ) {

	wcsw()->get_public()->add_remove_buttons( $product_id );

}

/**
 * Shows the Clear wishlist button HTML.
 *
 * @since    1.0.0
 */
function wcsw_clear_button() {

	wcsw()->get_public()->clear_button();

}

/**
 * Checks if the product is already in the wishlist.
 *
 * @since    1.0.0
 */
function wcsw_is_in_wishlist( $product_id ) {

	return wcsw()->get_public()->is_in_wishlist( $product_id );

}

/**
 * Gets existing data, for any user, from the database as JSON and converts it to a PHP array before returning it.
 *
 * @since    1.0.0
 */
function wcsw_get_user_data( $user_id ) {

	return wcsw()->get_public()->get_any_user_data( $user_id );

}
