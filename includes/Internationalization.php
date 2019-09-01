<?php

namespace WCSW;

/**
 * Defines the internationalization functionality.
 *
 * Loads and defines the internationalization files.
 *
 * @since    1.0.0
 */

/**
 * If this file is called directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines the internationalization functionality.
 *
 * Loads and defines the internationalization files.
 *
 * @since     1.0.0
 */
class Internationalization {

	/**
	 * Loads the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( 'wcsw', false, DIR . 'languages/' );

	}

}
