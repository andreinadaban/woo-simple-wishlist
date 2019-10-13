<?php

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
