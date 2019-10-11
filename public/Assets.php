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
 * The assets class.
 *
 * @since    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * The assets class.
 *
 * @since    1.0.0
 */
class Assets {

	/**
	 * Registers the JavaScript files for the public area.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'sw-public-js', plugin_dir_url( __FILE__ ) . 'assets/dist/js/sw-public.js', array( 'jquery' ), filemtime( DIR . 'public/assets/dist/js/sw-public.js' ), true );

	}

}
