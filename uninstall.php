<?php

/**
 * Simple Wishlist for WooCommerce
 * Copyright (C) 2018-2020 Andrei Nadaban
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

/**
 * Fired when the plugin is uninstalled.
 *
 * @since    1.0.0
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

/**
 * Deletes the plugin data from the options table.
 *
 * @since  1.0.0
 */
if ( get_option( 'sw_version' ) ) {
	delete_option( 'sw_version' );
}
