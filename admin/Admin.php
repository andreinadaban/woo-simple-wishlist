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
 * The admin class.
 *
 * @since    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * The admin class.
 *
 * @since    1.0.0
 */
class Admin {

	/**
	 * Checks if the required plugins are active. If the required plugins are not active, the plugin is deactivated.
     *
     * @since    1.0.0
	 */
	public function dependencies() {

		if ( ! class_exists( 'WooCommerce' ) ) {

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			deactivate_plugins( PLUGIN_MAIN_FILE );

		}

	}

	/**
	 * Adds admin notices.
	 *
	 * @since    1.0.0
	 */
	public function notices() {

		if ( ! class_exists( 'WooCommerce' ) ) {

			$message = __( 'The Simple Wishlist for WooCommerce plugin requires WooCommerce to be installed and active.', 'sw' );

			printf( '<div class="error"><p>%s</p></div>', $message );

		}

	}

}
