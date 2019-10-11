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

/**
 * The deactivator class.
 *
 * Copyright (C) 2019 Devin Vinson, Josh Eaton, Ulrich Pogson, Brad Vincent
 *
 * Modifications Copyright (C) 2018-2019 Andrei Nadaban
 *
 * Changed the class and file name and comments.
 *
 * @since     1.0.0
 * @author    Devin Vinson
 * @author    Josh Eaton
 * @author    Ulrich Pogson
 * @author    Brad Vincent
 * @link      https://github.com/DevinVinson/WordPress-Plugin-Boilerplate
 */

defined( 'ABSPATH' ) || exit;

/**
 * The deactivator class.
 *
 * @since     1.0.0
 */
class Deactivator {

	/**
	 * Runs on plugin deactivation.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		if ( get_transient( 'sw_flush' ) ) {

			delete_transient( 'sw_flush' );

		}

		flush_rewrite_rules();

	}

}
