<?php

namespace WCSW;

/**
 * Returns the core class instance.
 *
 * @since  1.0.0
 */
function core() {
	return Core::instantiate();
}

/**
 * Runs the plugin.
 *
 * @since  1.0.0
 */
function run() {
	core()->run();
}

/**
 * Creates the add to wishlist and remove from wishlist buttons.
 *
 * @since    1.0.0
 */
function button_add_remove( $product_id = false ) {
	core()->get_public()->button_add_remove( $product_id );
}

/**
 * Shows the Clear wishlist button HTML.
 *
 * @since    1.0.0
 */
function button_clear() {
	core()->get_public()->button_clear();
}

/**
 * Loads the wishlist template.
 *
 * @since    1.0.0
 */
function load_template() {
	core()->get_public()->load_template();
}

/**
 * Gets existing data, for any user, from the database as JSON and converts it to an array before returning it.
 *
 * @since    1.0.0
 */
function get_user_data( $user_id ) {
	return core()->get_public()->get_user_data( $user_id );
}

/**
 * Checks if the product is already in the wishlist.
 *
 * @since    1.0.0
 */
function is_in_wishlist( $product_id ) {
	return core()->get_public()->is_in_wishlist( $product_id );
}
