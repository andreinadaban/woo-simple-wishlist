<?php

namespace SW;

defined( 'ABSPATH' ) || exit;

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
function the_buttons( $product_id = false ) {
	core()->get_public()->the_buttons( $product_id );
}

/**
 * Loads the wishlist template.
 *
 * @since    1.0.0
 */
function the_template() {
	core()->get_public()->the_template();
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
